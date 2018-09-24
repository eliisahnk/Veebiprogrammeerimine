<?php
require("../../../config.php");
	//echo $GLOBALS["serverHost"];
$database = "if18_eliisa_ha_1";
	
function saveAMsg ($msg) {
	//echo "Töötab!";
	$notice = ""; //see on teade, mis antakse salvestamise kohta
	//loome ühenduse andmebaasiserveriga
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//valmistame ette SQL päringu
	$stmt = $mysqli->prepare("INSERT INTO vpamsg3eh (message) VALUES(?)");
	echo $mysqli->error;
	$stmt->bind_param("s", $msg);//s - string, i - imteger, d - decimal 
	if ($stmt->execute()){
		$notice = 'Sõnum: "' .$msg .'" on salvestatud!';
		
	} else {
	  $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
}
?>