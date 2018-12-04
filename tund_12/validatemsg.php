<?php
  require("functions.php");
  
  //kui pole sisse loginud
  if(!isset($_SESSION["userId"])){
	header("Location: index_2.php");
    exit();	
  }
  
  //välja logimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location: index_2.php");
    exit();
  }
  
  $msglist = readallunvalidatedmessages();
  //lehe päise laadimine
  $pageTitle = "Valideerimata sõnumid";
  require("header.php");
?>

  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>
	<li><a href="?logout=1">Logi välja</a>!</li>
	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <hr>
  
  <?php echo $msglist; ?>

</body>
</html>