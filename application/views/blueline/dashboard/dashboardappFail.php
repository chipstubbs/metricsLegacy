<?php 
$response = new stdClass();

$response->error = 'Incorrect Username and/or Password!';

$response = json_encode($response, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR);

echo $response;
?>