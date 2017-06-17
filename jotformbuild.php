<?php
$apiKey="";//add api key
$idlist=$_POST["idlist"];
$jsondata="";
foreach ($idlist as $form){
	$url="https://api.jotform.com/form/".$form."/questions?apiKey=".$apiKey;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);	
	curl_close($ch);
	$x=json_decode($result, true);
	$con=$x["content"];
	$jsondata.=json_encode($con);
}
echo "{\"root\":[".str_replace("}{","},{",$jsondata)."]}";//converts multiple JSON data segments into a single JSON with the root 'root'
?>
