<?php
	require("../../../config.php");
	$database = "if18_markus_mu_1";
	//echo $serverHost;
	
	//kasutan sessiooni
	session_start();

	
	
	//kasutajaprofiili salvestamine
  function storeuserprofile($desc, $bgcol, $txtcol){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil juba olemas, uuendame
		$stmt->close();
		$stmt = $mysqli->prepare("UPDATE vpuserprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		echo $mysqli->error;
		$stmt->bind_param("sssi", $desc, $bgcol, $txtcol, $_SESSION["userId"]);
		if($stmt->execute()){
			$notice = "Profiil edukalt uuendatud!";
			$_SESSION["bgColor"] = $bgcol;
		    $_SESSION["txtColor"] = $txtcol;
		} else {
			$notice = "Profiili uuendamisel tekkis tõrge! " .$stmt->error;
		}
	} else {
		//profiili pole, salvestame
		$stmt->close();
		//INSERT INTO vpusers3 (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)"
		$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $mysqli->error;
		$stmt->bind_param("isss", $_SESSION["userId"], $desc, $bgcol, $txtcol);
		if($stmt->execute()){
			$notice = "Profiil edukalt salvestatud!";
			$_SESSION["bgColor"] = $bgcol;
		    $_SESSION["txtColor"] = $txtcol;
		} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
	
	function readallunvalidatedmessagesbyusers(){
		$msghtml = "";
		$totalhtml = "";
		$counter = 0;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],   $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
		echo $mysqli->error;
		$stmt->bind_result($idfromdb, $firstnamefromdb, $lastnamefromdb);
		$stmt2=$mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
		$stmt2->bind_param("i", $idfromdb);
		$stmt2->bind_result($msgfromdb, $acceptedfromdb);
		$stmt->execute();
		//et saadud tulemus püsiks ja oleks kasutatav ka järgmises päringus($stmt2)
		$stmt->store_result();
		while($stmt->fetch()){
			$msghtml .= "<h3>" . $firstnamefromdb ." " . $lastnamefromdb ."</h3> \n";
			$stmt2->execute();
			while($stmt2->fetch()){
				$msghtml .="<p><b>";
				$counter++;
				if($acceptedfromdb == 1){
					$msghtml .= "Lubatud: ";
				}else{
					$msghtml .= "Keelatud: ";
				}
				$msghtml .= "</b>" .$msgfromdb ."</p> \n";
			}//while stmt2 fetch lõppeb
		if($counter==0){
			$msghtml = "";
		}
		$counter=0;	
		}//while stmt fetch lõppeb
		$stmt2->close();
		$stmt->close();	
		$mysqli->close();
		return $msghtml;
	}
	
	
    function allvalidmessages(){
		$notice = "";
        $accepted = 1;
        $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],   $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted = ?");
        echo $mysqli->error;
        $stmt->bind_param("i",$accepted);
		$stmt->bind_result($msg);
        if($stmt->execute()){
			while($stmt->fetch()){
				$notice .="<li>" .$msg .'<br>' ."\n";
			}
		}else{
			$notice .= "<li>Sõnumite lugemisel tekkis viga!" .$stmt->error ."</li> \n";
		}
		$notice .= "</ul> \n";
		$stmt->close();	
		$mysqli->close();
		return $notice;
    }

	//SQL käsk andmete uuendamiseks
	//UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE if=?
	function validatemessage($id, $validation){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
	echo $mysqli->error;
	$stmt->bind_param("iii", $_SESSION["userId"], $validation, $id);
	$stmt->execute();
	$stmt->close();
	$mysqli->close();
	header("Location: validatemsg.php");
	exit();
	}
	//valitud sõnumi lugemine valideerimiseks
	function readmsgforvalidation($editId){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
		$stmt->bind_param("i", $editId);
		$stmt->bind_result($msg);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $msg;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
  }
	
	//valideerimata sõnumite nimekiri
	function readallunvalidatedmessages(){
		$notice = "<ul> \n";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],   $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_result($msgid, $msg);
		if($stmt->execute()){
			while($stmt->fetch()){
				$notice .="<li>" .$msg .'<br><a href="validatemessage.php?id=' .$msgid .'">Valideeri</a></li>' ."\n";
			}
		}else{
			$notice .= "<li>Sõnumite lugemisel tekkis viga!" .$stmt->error ."</li> \n";
		}
		$notice .= "</ul> \n";
		$stmt->close();	
		$mysqli->close();
		return $notice;
	}
	
	//sisselogimine
	function signin($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],   $GLOBALS["serverPassword"], $GLOBALS["database"]);	
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");	
		echo $mysqli->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb,  $passwordFromDb);
		if($stmt->execute()){
			//andmebaasi päring õnnestus
			if($stmt->fetch()){
			//kui kasutaja on olemas
				if(password_verify($password, $passwordFromDb)){
					//parool õige
					$notice = "Olete õnnelikult sisse loginud!";
					//määrame sessiooni muutujad
					$_SESSION["userId"] = $idFromDb;
					$_SESSION["firstName"] = $firstnameFromDb;
					$_SESSION["lastName"] = $lastnameFromDb;
					$stmt->close();	
					$mysqli->close();
					header("Location: main.php");
					exit();
				} else {
					$notice = "Kahjuks vale salasõna!";
				}
			} else {
				$notice = "Kahjuks sellise kasutajatunnusega (" .$email .") kasutajat ei leitud!";
			}
		} else {
			$notice = "sisselogimisel tekkis tehniline viga!" .$stmt->error;
		}
			
			
			
		$stmt->close();	
		$mysqli->close();
		return $notice;
	}
	
	
	//kasutaja salvestamine
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