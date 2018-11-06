<?php
  //echo "see on minu esimene php!";
  $firstName = "Markus";
  $lastName = "Must";
  $dateToday = date("d.m.Y");
  $weekdayToday = date("N");
  $weekdayNamesET = ["esmaspäev","teisipäev","kolmapäev","neljapäev","reede","laupäev","pühapäev"];
  $monthNames = ["Jaanuar","Veebruar","Märts","Aprill","Mai","Juuni","Juuli","August","September","Oktoober","November","Detsember"];
  $monthNow = date("n");
  $hourNow = date("G");
  $dayNow = date("d");
  $yearNow = date("Y");
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
  
  //juhusliku pildi valimine
  $picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
  $picEXT = ".jpg";
  $picNUM = mt_rand(2,42);
  $pickFILE = $picURL .$picNUM .$picEXT;
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
  <?php 
  echo "täna on " . $weekdayNamesET[$weekdayToday-1] .", " .$dayNow.". ".$monthNames[$monthNow-1] ." ".$yearNow. ". \n";
  ?>
  <?php echo "<p>Lehe avamise hetkel oli kell " .date("H.i.s") .", käes oli " .$partofDay .".</p> \n"; ?>
  <p>See on minu <a href="https://www.tlu.ee/">TLÜ</a> õppetöö raames valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu</p>
  <p>Teised lehed: <a href="photo.php">photo.php, </a><a href="page.php">page.php</a>.</p>
  <img src="<?php echo $pickFILE; ?>" alt="Pildid TLÜ õppehoonest">
  <p>Minu sõber teeb ka <a href="../../../~jaagala" target=blank>veebi</a></p>
  </body>
</html>