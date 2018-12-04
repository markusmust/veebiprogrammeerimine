<?php
  require("../../../config.php");
  $database = "if18_markus_mu_1";
  $privacy = 2;
  $limit = 10;
  $html = NULL;
  $photolist = [];
  $mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
  $stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy <= ? AND deleted IS NULL ORDER By id DESC LIMIT ?");
  $stmt->bind_param("ii", $privacy, $limit);
  $stmt->bind_result($filenameFromDb, $alttextFromDb);
  $stmt->execute();
  while($stmt->fetch()){
	  $myPhoto = new stdClass();
	  $myPhoto->filename = $filenameFromDb;
	  $myPhoto->alttext = $alttextFromDb;
	  array_push($photolist, $myPhoto);
	  
	  //<img src="fail" alt="tekst">
	  //$html = '<img src="' .$picDir .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
  }
  $photoCount = count($photolist);
  if($photoCount > 0){
	  $randPic = mt_rand(0, $photoCount - 1);
	  $html = '<img src="' .$picDir .$photolist[$randPic]->filename .'" alt="' .$photolist[$randPic]->alttext .'">' ."\n";
  }
  //massiivi läbimise tsükkel
  foreach($photolist as $pic){
	  $html .= "<p>" .$pic->filename ." / " .$pic->alttext ."</p> \n";
  }
  $stmt->close();
  $mysqli->close();
  echo $html;
?>