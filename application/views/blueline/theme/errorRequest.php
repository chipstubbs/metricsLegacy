<?php
header("HTTP/1.1 401 Unauthorized", false);
header('Content-Type: application/json', false);
header('Access-Control-Allow-Origin: *', false);
echo $yield;
?>