<?php
  require("functions.php");
  $notice = readallmessages();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>anonüümsed sõnumid</title>
  </head>
 <body>
  <h1>Sõnumid</h1>
  <p>See on minu <a href="https://www.tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebileht ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <hr>
<p><?php echo $notice; ?></p>
  <hr> 
 </body>
</html>