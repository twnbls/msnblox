<?php
$UserId = $_GET['id'] ?? 0;

echo implode(";", [
    "http://www.torium.fun/asset/BodyColors.ashx?id=$UserId", // head color
]);
?>