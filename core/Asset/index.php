<?php
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit("Missing id");
}

$id = $_GET['id'];

if (!ctype_digit($id)) {
    http_response_code(400);
    exit("Invalid id");
}

$baseDir = __DIR__ . "/assets/";
$file = $baseDir . $id;

if (file_exists($file) && is_file($file)) {
    header("Content-Length: " . filesize($file));
    header("Cache-Control: public, max-age=86400");

    readfile($file);
    exit;
}

$fallbackUrl = "https://assetdelivery.ttblox.mom/v1/asset?Id=" . $id;

$ch = curl_init($fallbackUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HEADER => false,
    CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($ch);

if ($response === false) {
    http_response_code(502);
    exit("Fallback failed");
}

$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$body = substr($response, $headerSize);
curl_close($ch);

if ($statusCode !== 200 || empty($body)) {
    http_response_code(404);
    exit("Asset not found");
}

header("Content-Length: " . strlen($body));
header("Cache-Control: public, max-age=86400");

echo $body;
exit;
?>