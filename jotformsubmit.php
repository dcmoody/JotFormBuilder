<?php
$apiKey="";//add api key
$form=$_GET['formid'];
$url = "https://api.jotform.com/form/".$form."/submissions?apiKey=".$apiKey;
$data = json_encode($_GET);
$options = array(
    'http' => array(
        'header'  => "Content-type: application\json\r\n",
        'method'  => 'POST',
        'content' => $data
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ }
var_dump($result);?>

