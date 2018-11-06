<?php
  $firstName = "Markus";
  $lastName = "Must";
  //loeme kataloogi sisu
  $dirtoRead = "../../pics/";
  $allFiles = scandir($dirtoRead);
  $picFiles = array_slice($allFiles, 2);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>
	<?php 
	echo $firstName;
	echo " ";
	echo $lastName;
	?>
	, õppetöö</title>
  </head>
  <body>
  <h1><?php echo $firstName ." " .$lastName; ?></h1>
  <p>See on minu <a href="https://www.tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  
  <?php
  //<img src="pilt.jpg" alt="pilt">
  
  for ($i = 0; $i < count($picFiles); $i++ ){
	echo '<img src="' .$dirtoRead .$picFiles[$i] .'" alt="pilt">';
  }
  ?>
  
  </body>
</html>