<?php
	require("../../../config.php");
	$database = "if18_markus_mu_1";
	//echo $serverHost;
	
	function signup($firstName, $lastName, $birthDate, $gender, $email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
		echo $mysqli->error;
		//krüpteerime parooli
		$options = ["cost"=>12, "salt"=>substr(sha1(mt_rand()), 0, 22)];
		$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
		$stmt->bind_param("sssiss", $firstName, $lastName, $birthDate, $gender, $email, $pwdhash);
		if ($stmt->execute()){
		  $notice = 'kasutaja on edukalt loodud!';
		} else {
			$notice = "kasutaja loomisel tekkis tõrge: " .$stmt->error;  
		}
		$stmt->close();	
		$mysqli->close();
		return $notice;
	}

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
	
	function addcat($catname, $catcolor, $cattail_int){	
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO kiisud (nimi, v2rv, saba) VALUES (?, ?, ?)");
		echo $mysqli->error;		
		$stmt->bind_param("ssi", $catname, $catcolor, $cattail_int);
		if ($stmt->execute()){
		  echo 'kiisu "' .$catname .'" on edukalt salvestatud! ';
		} else {
			echo "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;  
		}
		$stmt->close();	
		$mysqli->close();
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