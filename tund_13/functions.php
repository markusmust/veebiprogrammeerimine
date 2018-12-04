<?php
  require("../../../config.php");
  $database = "if18_markus_mu_1";
  //echo $serverHost;
  
  //kasutan sessiooni
  session_start();
  function findTotalPrivateImages(){
	$privacy = 3;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT COUNT(*) FROM vpphotos WHERE privacy=? AND userid=? AND deleted IS NULL");
	$stmt->bind_param("ii", $privacy, $_SESSION["userId"]);
	$stmt->bind_result($imageCount);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$mysqli->close();
	return $imageCount;  
  }
  
  function listmyprivatephotos($page, $limit){
    $html = "";
	$privacy = 3;
	$skip = ($page - 1) * $limit;
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy=? AND userid=? AND deleted IS NULL LIMIT ?,?");
    echo $mysqli->error;
    $stmt->bind_param("iiii", $privacy, $_SESSION["userId"], $skip, $limit);
    $stmt->bind_result($filenameFromDb, $alttextFromDb);
    $stmt->execute();
    while($stmt->fetch()){
      //<img src="kataloog/fail" alt="tekst">
      $html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
    }
    if(empty($html)){
      $html = "<p>Kahjuks privaatseid pilte pole!</p> \n";
    }
    $stmt->close();
	$mysqli->close();
    return $html;
  }
  
  //SQL käsk, mis väljastab arvu...
  //   SELECT COUNT(*) FROM vpphotos WHERE deleted IS null ...
  function findTotalPublicImages(){
	$privacy = 2;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT COUNT(*) FROM vpphotos WHERE privacy<=? AND deleted IS NULL");
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($imageCount);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$mysqli->close();
	return $imageCount;	
  }
  
  function allPublicPictureThumbsPage($page, $limit){
	$html = "";
	$privacy = 2;
	$skip = ($page - 1) * $limit;
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT id, filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL LIMIT ?,?");
    echo $mysqli->error;
    $stmt->bind_param("iii", $privacy, $skip, $limit);
    $stmt->bind_result($idFromDb, $filenameFromDb, $alttextFromDb);
    $stmt->execute();
    while($stmt->fetch()){
      //<img src="kataloog/fail" alt="tekst">
      $html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'" data-fn="' .$filenameFromDb .'" data-id="' .$idFromDb .'">' ."\n";
    }
    if(empty($html)){
      $html = "<p>Kahjuks avalikke pilte pole!</p> \n";
    }
    $stmt->close();
	$mysqli->close();
    return $html;
  }
  
  function allPublicPictureThumbs($privacy){
	$html = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL");
	echo $mysqli->error;
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($filenameFromDb, $alttextFromDb);
	$stmt->execute();
	while($stmt->fetch()){
	  //<img src="kataloog/pildifail" alt="alttext">
	  $html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
	}
	if(empty($html)){
	  $html = "<p>Vabandame, avalikke pilte pole!</p> \n";
	}
	
	$stmt->close();
	$mysqli->close();
	return $html;  
  }
  
  function lastPicture($privacy){
	$html = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE id=(SELECT MAX(id) FROM vpphotos WHERE privacy=? AND deleted IS NULL)");
	echo $mysqli->error;
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($filenameFromDb, $alttextFromDb);
	$stmt->execute();
	if($stmt->fetch()){
	  //<img src="kataloog/pildifail" alt="alttext">
	  $html = '<img src="' .$GLOBALS["picDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
	} else {
	  $html = "<p>Vabandame, avalikke pilte pole!</p> \n";
	}
	
	$stmt->close();
	$mysqli->close();
	return $html;
  }
  
  function addUserPhotoData($fileName){
	$addedId = null;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("INSERT INTO vp_user_pictures (userid, filename) VALUES (?, ?)");
	echo $mysqli->error;
	$stmt->bind_param("is", $_SESSION["userId"], $fileName);
	if($stmt->execute()){
	  $addedId = $mysqli->insert_id;
	  //echo $addedId;
	} else {
	  echo $stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $addedId;
  }
  
  function addPhotoData($filename, $alttext, $privacy){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES(?, ?, ?, ?)");
	echo $mysqli->error;
	if(empty($privacy) or $privacy > 3 or $privacy < 1){
		$privacy = 3;
	}
	$stmt->bind_param("issi", $_SESSION["userId"], $filename, $alttext, $privacy);
	if($stmt->execute()){
	  $notice = "Andmed lisati andmebaasi!";
	} else {
      echo "Foto lisamisel andmebaasi tekkis tehniline viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
    return $notice;
  }
  
  function readprofilecolors(){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT bgcolor, txtcolor FROM userprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($bgcolor, $txtcolor);
	$stmt->execute();
	$profile = new Stdclass();
	if($stmt->fetch()){
		$_SESSION["bgColor"] = $bgcolor;
		$_SESSION["txtColor"] = $txtcolor;
	} else {
		$_SESSION["bgColor"] = "#FFFFFF";
		$_SESSION["txtColor"] = "#000000";
	}
	$stmt->close();
	$mysqli->close();
  }
  
  //kasutajaprofiili salvestamine
  function storeuserprofile($desc, $bgcol, $txtcol, $picId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM userprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil juba olemas, uuendame
		$stmt->close();
		//kui on pilt lisatud
		if(!empty($picId)){
		  $stmt = $mysqli->prepare("UPDATE userprofiles SET description=?, bgcolor=?, txtcolor=?, picture=? WHERE userid=?");
		  echo $mysqli->error;
		  $stmt->bind_param("sssii", $desc, $bgcol, $txtcol, $picId, $_SESSION["userId"]);
		} else {
		  $stmt = $mysqli->prepare("UPDATE userprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		  echo $mysqli->error;
		  $stmt->bind_param("sssi", $desc, $bgcol, $txtcol, $_SESSION["userId"]);
		}
		
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
		//kui on pilt ka lisatud
		if(!empty($picId)){
		  $stmt = $mysqli->prepare("INSERT INTO userprofiles (userid, description, bgcolor, txtcolor, picture) VALUES(?,?,?,?,?)");
		  echo $mysqli->error;
		  $stmt->bind_param("isssi", $_SESSION["userId"], $desc, $bgcol, $txtcol, $picId);
		} else {
		  //INSERT INTO vpusers3 (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)"
		  $stmt = $mysqli->prepare("INSERT INTO userprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		  echo $mysqli->error;
		  $stmt->bind_param("isss", $_SESSION["userId"], $desc, $bgcol, $txtcol);
		}
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
  
  //kasutajaprofiili väljastamine
  function showmyprofile(){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor, picture FROM userprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor, $picture);
	$stmt->execute();
	$profile = new Stdclass();
	if($stmt->fetch()){
		$profile->description = $description;
		$profile->bgcolor = $bgcolor;
		$profile->txtcolor = $txtcolor;
		$profile->picture = $picture;
	} else {
		$profile->description = "";
		$profile->bgcolor = "";
		$profile->txtcolor = "";
		$profile->picture = null;
	}
	$stmt->close();
	//kui on pilt olemas
	if(!empty($profile->picture)){
	  $stmt = $mysqli->prepare("SELECT filename FROM vp_user_pictures WHERE id=?");
	  echo $mysqli->error;
	  $stmt->bind_param("i", $profile->picture);
	  $stmt->bind_result($pictureFile);
	  $stmt->execute();
	  if($stmt->fetch()){
		$profile->picture = $pictureFile;  
	  }
	  $stmt->close();
	}
	$mysqli->close();
	return $profile;
  }
  
  //väljastame kõik valideeritud sõnumid kasutajate kaupa
  function readallvalidatedmessagesbyuser(){
	$msghtml = "";
	$result = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
	echo $mysqli->error;
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
	$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
	echo $mysqli->error;
	$stmt2->bind_result($msgFromDb, $acceptedFromDb);
	
	$stmt->execute();
	//järgmine käsk hoiab tulemust pikemalt kinni, et saaks ka järgmises käsus kasutada
	$stmt->store_result();
	
	$stmt2->bind_param("i", $idFromDb);
	
	while($stmt->fetch()){
		$msghtml .="<h3>" .$firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
		$resultcounter = 0;
		$stmt2->execute();
		while($stmt2->fetch()){
		  $msghtml .="<p>";
		  if($acceptedFromDb == 0){
			$msghtml .= "<b>Keelatud: </b>";
		  } else {
			$msghtml .= "<b>Lubatud: </b>";
		  }
		  $msghtml .=  $msgFromDb ."</p>\n";
		  $resultcounter ++;
		}
		if($resultcounter > 0){
		  $result .= $msghtml;
		}
		
		$msghtml = "";
	}
	$stmt->free_result();
	$stmt->close();
	$stmt2->close();
	$mysqli->close();
	return $result;
  }
  
  //kõigi valideeritud sõnumite lugemine valideerija kaupa
  function readallvalidatedmessagesbyuser_vana(){
	$msghtml ="";
	$result = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
	echo $mysqli->error;
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
	
	$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
	$stmt2->bind_param("i", $idFromDb);
	$stmt2->bind_result($msgFromDb, $acceptedFromDb);
	
	$stmt->execute();
	//et saadud tulemus püsiks ja oleks kasutatav ka järgmises päringus ($stmt2)
	$stmt->store_result();
	
	while($stmt->fetch()){
	  $msghtml .= "<h3>" . $firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
	  $resultcounter = 0;
	  $stmt2->execute();
	  while($stmt2->fetch()){
		$msghtml .= "<p><b>";
		if($acceptedFromDb == 1){
		  $msghtml .= "Lubatud: ";
		} else {
		  $msghtml .= "Keelatud: ";
		}
		$msghtml .= "</b>" .$msgFromDb ."</p> \n";
		$resultcounter ++;
	  }//while $stmt2 fetch
	  if($resultcounter > 0){
		  $result .= $msghtml;
		}
		
		$msghtml = "";
	}//while $stmt fetch
	$stmt->free_result();
	$stmt2->close();
	$stmt->close();
	$mysqli->close();
	return $msghtml;
  }
  
  //kasutajate nimekiri
  function listusers(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id !=?");
	
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($firstname, $lastname, $email);
	if($stmt->execute()){
	  $notice .= "<ol> \n";
	  while($stmt->fetch()){
		  $notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."</li> \n";
	  }
	  $notice .= "</ol> \n";
	} else {
		$notice = "<p>Kasutajate nimekirja lugemisel tekkis tehniline viga! " .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function allvalidmessages(){
	$html = "";
	$valid = 1;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted=? ORDER BY accepttime DESC");
	echo $mysqli->error;
	$stmt->bind_param("i", $valid);
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	if(empty($html)){
		$html = "<p>Kontrollitud sõnumeid pole.</p>";
	}
	return $html;
  }
  
  function validatemsg($editId, $validation){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
	$stmt->bind_param("iii", $_SESSION["userId"], $validation, $editId);
	if($stmt->execute()){
	  echo "Õnnestus";
	  header("Location: validatemsg.php");
	  exit();
	} else {
	  echo "Tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
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
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL");
	echo $mysqli->error;
	$stmt->bind_result($msgid, $msg);
	if($stmt->execute()){
	  while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$msgid .'">Valideeri</a></li>' ."\n"; 
	  }
    } else {
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
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s", $email);
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
	if($stmt->execute()){
	  //andmebaasi päring õnnestus
	  if($stmt->fetch()){
		//kasutaja on olemas
		if(password_verify($password, $passwordFromDb)){
		  //parool õige
		  $notice = "Olete õnnelikult sisse loginud!";
		  //määrame sessioonimuutujad
		  $_SESSION["userId"] = $idFromDb;
		  $_SESSION["lastName"] = $lastnameFromDb;
		  $_SESSION["firstName"] = $firstnameFromDb;
		  readprofilecolors();
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
	  $notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //kasutaja salvestamine
  function signup($name, $surname, $email, $gender, $birthDate, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//kontrollime, ega kasutajat juba olemas pole
	$stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s",$email);
	$stmt->execute();
	if($stmt->fetch()){
		//leiti selline, seega ei saa uut salvestada
		$notice = "Sellise kasutajatunnusega (" .$email .") kasutaja on juba olemas! Uut kasutajat ei salvestatud!";
	} else {
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
    	echo $mysqli->error;
	    $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	    $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	    $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	    if($stmt->execute()){
		  $notice = "ok";
	    } else {
	      $notice = "error" .$stmt->error;	
	    }
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function saveamsg($msg){
	$notice = "";
    //loome andmebaasiühenduse
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//valmistan ette andmebaasikäsu
	$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
	echo $mysqli->error;
	//asendan ettevalmistatud käsus küsimärgi(d) päris andmetega
	// esimesena kirja andmetüübid, siis andmed ise
	//s - string; i - integer; d - decimal
	$stmt->bind_param("s", $msg);
	//täidame ettevalmistatud käsu
	if ($stmt->execute()){
	  $notice = 'Sõnum: "' .$msg .'" on edukalt salvestatud!';
	} else {
	  $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
	}
	//sulgeme ettevalmistatud käsu
	$stmt->close();
	//sulgeme ühenduse
	$mysqli->close();
	return $notice;
  }
  
  function readallmessages(){
	$notice = "";
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