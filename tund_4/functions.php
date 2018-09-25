<?php
	require("../../../config.php");
	$database = "if18_markus_mu_1";
	//echo $serverHost;
	

	function saveamsg($msg){
		$notice = "";
		//loome andmebaasi ühenduse
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette andmebaasikäsi
		$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
		echo $mysqli->error;
		//asendan ettevalmistatud käsus küsimärgid päris andmetega
		//esimesena kirja andmetüübid, siis andmed ise
		//s-string i-integer d-decimal
		$stmt->bind_param("s", $msg);
		//täidame ettevalmistatud käsus
		if ($stmt->execute()){
		  $notice = 'Sõnum: "' .$msg .'" on edukalt salvestatud!';
		} else {
			$notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;  
		}
		//sulgeme ettevalmistatud käsus
		$stmt->close();
		//sulgeme ühenduse
		$mysqli->close();
		return $notice;
	}
		
	function readallmessages(){
		$notice =  "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg");
		echo $mysqli->error;
		$stmt->bind_result($msg);
		$stmt->execute();
		while($stmt->fetch()){
			$notice .= "<p>" .$msg ."</p> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
		}
	
	//teksti sisendi kontrollimine
	function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
	}
	

?>