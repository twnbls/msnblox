<?php
include "../core/config.php";

if (!isset($_GET['userid'])) {
    http_response_code(400);
    echo "Missing userid";
    exit;
}

$userid = intval($_GET['userid']);
$forcerender = isset($_GET['render']) && $_GET['render'] == "1";

$stmt = $conn->prepare("SELECT 1 FROM users WHERE userid = ? LIMIT 1");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    http_response_code(404);
    echo "User not found";
    $stmt->close();
    exit;
}
$stmt->close();

$DefaultAvatar = __DIR__ . "/renders/avatars/default.png";
$UserAvatar = __DIR__ . "/renders/avatars/$userid.png";

if (!file_exists($DefaultAvatar)) {
    http_response_code(404);
    echo "Default Avatar not found";
    exit;
}

if (file_exists($UserAvatar) && !$forcerender) {
    header("Content-Type: image/png");
    header("Content-Length: " . filesize($UserAvatar));
    readfile($UserAvatar);
    exit;
}

function GetCharApp($ruserid) {
    global $conn;

    $ruserid = (int)$ruserid;
    $items = [];

    $stmt = $conn->prepare("SELECT itemid FROM wearing WHERE userid = ?");
    $stmt->bind_param("i", $ruserid);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $items[] = "http://torium.fun/Asset/?id=" . $row['itemid'];
    }

    $stmt->close();

    $items[] = "http://torium.fun/Asset/Bodycolors.ashx.php/?id=" . $ruserid;

    return implode(";", $items);
}

$base64 = "";

$stmt = $conn->prepare("INSERT INTO renders (base64, userid) VALUES (?, ?)");
$stmt->bind_param("si", $base64, $userid);
$stmt->execute();

$renderId = $conn->insert_id;
$stmt->close();

$scriptText = file_get_contents(__DIR__ . "/avatar.lua");
$scriptText = str_replace("{{APP}}", GetCharApp($userid), $scriptText);
$scriptText = str_replace("{{RENDERID}}", $renderId, $scriptText);

$endpoint = $RccService . "/OpenJob";

$soapXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xsd="http://www.w3.org/2001/XMLSchema"
    xmlns:ns1="http://{$RccServiceURL}/">
    <SOAP-ENV:Body>
        <ns1:OpenJob>
            <ns1:job>
                <ns1:id>{$renderId}</ns1:id>
                <ns1:expirationInSeconds>10</ns1:expirationInSeconds>
                <ns1:category>0</ns1:category>
                <ns1:cores>1</ns1:cores>
            </ns1:job>
            <ns1:script>
                <ns1:name>Starter Script</ns1:name>
                <ns1:script>{$scriptText}</ns1:script>
                <ns1:arguments></ns1:arguments>
            </ns1:script>
        </ns1:OpenJob>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
XML;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $soapXml);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: text/xml; charset=utf-8",
    "Content-Length: " . strlen($soapXml),
    "SOAPAction: \"OpenJob\""
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);

$renderPath = __DIR__ . "/renders/avatars/$userid.png";

$timeout = 10;
$start = time();

while ((time() - $start) < $timeout) {
    $stmt = $conn->prepare("SELECT base64 FROM renders WHERE renderid = ? LIMIT 1");
    $stmt->bind_param("i", $renderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row && $row['base64'] !== "") {
        $data = base64_decode($row['base64']);
        file_put_contents($renderPath, $data);
        header("Content-Type: image/png");
        echo $data;
        exit;
    }

    usleep(300000);
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $DefaultAvatar);
finfo_close($finfo);

header("Content-Type: " . $mimeType);
header("Content-Length: " . filesize($DefaultAvatar));
readfile($DefaultAvatar);
exit;
?>