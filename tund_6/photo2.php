<?php
  $firstName = "Markus";
  $lastName = "Must";
  //loeme kataloogi sisu
  $dirtoRead = "../../pics/";
  $allFiles = scandir($dirtoRead);
  $picFiles = array_slice($allFiles, 2);
  $picURL = "http://greeny.cs.tlu.ee/~markmus/pics/doge";
  $picEXT = ".jpg";
  $picNUM = mt_rand(1,4);
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
  <p>See on minu <a href="https://www.tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <img src="<?php echo $pickFILE; ?>" alt="Pildid TLÜ õppehoonest">
  </body>
</html>