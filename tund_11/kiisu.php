<?php
	require("functions.php");
	$catname = $_POST["knimi"];
	$catcolor = $_POST["varv"];
	$cattail = $_POST["saba"];
	$cattail_int = (int)$cattail;
	//var_dump($_POST);
	if (isset($_POST["submitUserdata"])){
		addcat($catname, $catcolor, $cattail_int);	
	} else {
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>õppetöö</title>
  </head>
  <body>
  <h1><?php echo "Kiisu andmebaasi lisamine" ?></h1>
  <p>See on minu <a href="https://www.tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <hr>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label>Nimi: </label>
	<input type="text" name="knimi">
    <label>Värvus: </label>
	<input type="text" name="varv">
	<label>Saba pikkus: </label>
	<input type="text" name="saba">
</select>
	<input type="submit" name="submitUserdata" value="Saada andmed">
  </form>
  <hr>
  </body>
</html>