<?php
  //echo "see on minu esimene php!";
  $firstName = "Markus";
  $lastName = "Must";
  $dateToday = date("d.m.Y");
  $hourNow = date("G");
  $partofDay = "";
  if ($hourNow < 8){
	  $partofDay = "varahommik";
  }
  if ($hourNow >= 8 and $hourNow < 16) {
	  $partofDay = "koolipäev";
  }
  if ($hourNow > 16) {
	  $partofDay = "vaba aeg";
  }
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
  <?php echo "tänane kuupäev on: " . $dateToday . "."; ?>
  <?php echo "<p>Lehe avamise hetkel oli kell " .date("H.i.s") .", käes oli " .$partofDay ."."; ?>
  <p></p>
  <?php echo " Lehe avamise hetkel oli " .$partofDay ."."; ?>
  <p>See on minu <a href="https://www.tlu.ee/">TLÜ</a> õppetöö raames  valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu</p>
  <img src="../tlu_terra_600x400_2.jpg" alt="TLÜ Terra õppehoone">
  <p>Minu sõber teeb ka <a href="../../~jaagala" target=blank>veebi</a></p>
  </body>
</html>