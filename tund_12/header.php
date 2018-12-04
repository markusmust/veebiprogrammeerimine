<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>VP <?php echo $pageTitle; ?></title>
	<style>
	  <?php
        echo "body{background-color: " .$_SESSION["bgColor"] ."; \n";
		echo "color: " .$_SESSION["txtColor"] ."} \n";
	  ?>
	</style>
	<?php
	if(isset($scripts)){
		echo $scripts;
	}
	?>
  </head>
  <body>
  
    <div>
	  <a href="main.php">
	    <img src="../vp_picfiles/vp_logo_w135_h90.png" alt="VP logo">
	  </a>
	  <img src="../vp_picfiles/vp_banner.png" alt="VP bänner">
	</div>
  
    <h1><?php echo $pageTitle; ?></h1>