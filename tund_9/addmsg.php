<?php
  require("functions.php");
  $notice = null;
  if (isset($_POST["submitMessage"])){
	if($_POST["message"] != "Kirjuta sõnum siia ..." and !empty($_POST["message"])){
		$message = test_input($_POST["message"]);
		$notice = saveamsg($message);	
	} else {
		$notice = "Palun kirjuta sõnum!";
	}
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>õppetöö</title>
  </head>
 <body>
  <h1>Sõnumi lisamine</h1>
  <p>See on minu <a href="https://www.tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label>Sõnum (256 märki):</label>
	<br>
	<textarea name="message" rows="4" cols="64">Kirjuta sõnum siia ...</textarea>
	<br>
	<input type="submit" name="submitMessage" value="Salvesta sõnum">
  </form>
  <hr>
  <p><?php echo $notice; ?></p>
 </body>
</html>