<?php
	$firstName = "eesnimi";
	$lastName = "perenimi";
	$birthYear = 1998;
	$birthMonth = date("M");
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
	<label>Sünnikuu: </label>
	<select name="birthMonth">
  <option value="1">jaanuar</option>
  <option value="2">veebruar</option>
  <option value="3">märts</option>
  <option value="4">aprill</option>
  <option value="5">mai</option>
  <option value="6">juuni</option>
  <option value="7">juuli</option>
  <option value="8">august</option>
  <option value="9">september</option>
  <option value="10">oktoober</option>
  <option value="11">november</option>
  <option value="12">detsember</option>
</select>
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