<?php
include_once __DIR__ . '/../core/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}

$input = file_get_contents("php://input");
$inputbackup = $input;

if (substr($input, 0, 2) === "\x1f\x8b") {
    $input = gzdecode($input);
    if ($input === false) {
        $input = $inputbackup;
    }
}

$data = json_decode($input, true);
if (!is_array($data)) {
    http_response_code(400);
    exit("Invalid JSON");
}

$renderid = $data['renderid'] ?? null;
$render   = $data['render'] ?? null;

if ($renderid === null || $render === null) {
    http_response_code(400);
    exit("Missing parameters");
}

if (!ctype_digit((string)$renderid)) {
    http_response_code(401);
    exit("Invalid renderid");
}

if (base64_decode($render, true) === false) {
    http_response_code(400);
    exit("Invalid base64 data");
}

$stmt = $conn->prepare(
    "UPDATE renders SET base64 = ? WHERE renderid = ?"
);
$stmt->bind_param("si", $render, $renderid);
$stmt->execute();
$stmt->close();

?>