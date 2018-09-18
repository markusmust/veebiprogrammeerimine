<?php
  $firstName = "Tundmatu";
  $lastName = "Kodanik";
  //püüan post andmed kinni
  if (isset($_POST["firstname"])){
	  $firstName = $_POST["firstname"];
  }
  if (isset($_POST["lastname"])){
	  $lastName = $_POST["lastname"];
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>õppetöö</title>
  </head>
  <body>
  <h1><?php echo $firstName ." " .$lastName; ?></h1>
  <p>See on minu <a href="https://www.tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <hr>
  <form method="POST">
	<label>Eesnimi: </label>
	<input type="text" name="firstname">
    <label>   Perekonnanimi: </label>
	<input type="text" name="lastname">
	<label>Sünniaasta: </label>
	<input type="number" min="1914" max="2000" value="1999" name="birthyear">
	<input type="submit" name="submitUserdata" value="Saada andmed">
  </form>
  <hr>
  <?php
  if (isset($_POST["birthyear"])){
  echo "<p>Olete üle elanud järgnevad aastad:</p>";
  echo "<ul>";
  for ($i = $_POST["birthyear"]; $i <= date("Y");$i++) {
	  echo "<li>" .$i ."</li \n>";
  }
  echo "</ul> \n";
  }
  ?>
  </body>
</html>