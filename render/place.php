<?php
include "../core/config.php";

if (!isset($_GET['placeid'])) {
    http_response_code(400);
    echo "Missing placeid";
    exit;
}

$placeid = intval($_GET['placeid']);

$placePath = __DIR__ . "/renders/places/default.png";

if (!file_exists($placePath)) {
    http_response_code(404);
    echo "Place not found";
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $placePath);
finfo_close($finfo);

header("Content-Type: " . $mimeType);
header("Content-Length: " . filesize($placePath));
readfile($placePath);
exit;
?>