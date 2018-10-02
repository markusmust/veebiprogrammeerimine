<?php
  //lisan teise php faili
  require("functions.php");
  $notice = "";
  $firstName = "";
  $lastName = "";
  $birthMonth = null;
  $birthYear = null;
  $birthDay = null;
  $birthdate = "";
  $gender = null;
  $email = "";
  
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  $firstNameError = "";
  $lastNameError = "";
  $birthMonthError = "";
  $birthYearError = "";
  $birthDayError = "";
  $birthdateError = "";
  $genderError = "";
  $emailError = "";
  $passwordError = "";
  
  //püüan POST andmed kinni
  if(isset($_POST["submitUserdata"])){//kas on nuppu vajutatud
    //eesnime kontroll
	  if(isset($_POST["firstname"]) and !empty($_POST["firstname"])){
		$firstName = test_input($_POST["firstname"]);
		  }else {
			  $firstNameError = " Palun sisesta oma eesnimi!";
		  }
	// perenime kontroll	  
  if(isset($_POST["lastname"]) and !empty($_POST["lastname"])){
		$lastName = test_input($_POST["lastname"]);
		  }else {
			  $lastNameError = " Palun sisesta oma perenimi!";
		  }
	//emaili kontroll	  
  if(isset($_POST["email"]) and !empty($_POST["email"])){
		$email = test_input($_POST["email"]);
		  }else {
			  $emailError = " Palun sisesta oma email!";
		  }
		  
		  //soo kontroll
		  if(isset($_POST["gender"]) and !empty($_POST["gender"])){
			  $gender = intval($_POST["gender"]);
		  }else {
			  $genderError = " Palun märgi oma sugu";
			}
	   if(!empty($_POST["birthDay"]) and !empty($_POST["birthMonth"]) and !empty($_POST["birthYear"])) {
		 if(checkdate(intval($_POST["birthMonth"]), intval($_POST["birthDay"]), intval($_POST["birthYear"]))){
			 //kontrollime kuupäeva valiidsust, checkdate ootab 3 täisarvu(kuu,päev,aasta)
			 $birthDate = date_create($_POST["birthMonth"] ."/". $_POST["birthDay"] ."/". $_POST["birthYear"]);
			 //vormindame andmebaasi jaoks sobivaks
			 $birthDate = date_format($birthDate, "Y-m-d");
			 //echo $birthDate;
		 }  else {
			 $birthDateError = " Kahjuks on sisestatud võimatu kuupäev";
		 }	
		 
		//parooli pikkuse kontroll
		if(strlen($_POST["password"]) >= 8){
			$password = test_input($_POST["password"]);
		} else {
			$passwordError = "Parool peab olema vähemalt 8 tähemärki!";
		}
				
	
	   }//kõik kontrollid tehtud
	   if(empty($firstNameError) and empty($lastNameError) and empty($birthMonthError) and empty($birthYearError) and empty($birthDayError) and  empty($birthdateError) and empty($genderError) and empty($emailError) and empty($passwordError)){
		$notice = signup($firstName, $lastName, $birthDate, $gender, $_POST["email"], $_POST["password"]);
	   }
  }//kotrolli lõpp
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Uue kasutaja loomine</title>
</head>
<body>
  <h1>Loo kasutaja</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Eesnimi: </label><br>
    <input type="text" name="firstname" value="<?php echo $firstName ; ?>"><span><?php echo $firstNameError ; ?></span><br>
    <label>Perekonnanimi: </label><br>
    <input type="text" name="lastname" value="<?php echo $lastName ; ?>"><span><?php echo $lastNameError ; ?></span><br><br>
	<label>Sünnipäev: </label>
	  <?php
	    echo '<select name="birthDay">' ."\n";
		echo '<option value="" selected disabled>Päev</option>' ."\n";
		for ($i = 1; $i < 32; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthDay){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünnikuu: </label>
	  <?php
	    echo '<select name="birthMonth">' ."\n";
		echo '<option value="" selected disabled>Kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthMonth){
				echo " selected ";
			}
			echo ">" .$monthNamesET[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünniaasta: </label>
	  <!--<input name="birthYear" type="number" min="1914" max="2003" value="1998">-->
	  <?php
	    echo '<select name="birthYear">' ."\n";
		echo '<option value="" selected disabled>Aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <br>
	  <label>Sugu:</label><br>
	  <input name="gender" type="radio" value="2" <?php if($gender == 2){ echo "checked";}?>><label>Naine</label>
	  <br>
	  <input name="gender" type="radio" value="1" <?php if($gender == 1){ echo "checked";}?>><label>Mees</label>
	  <br>
	  <span><?php echo $genderError; ?></span>
	  <br>
	  <label>E-postiaadress (kasutajatunnuseks): </label><br>
	  <input type="email" name="email" value="<?php echo $email ; ?>"><span><?php echo $emailError ; ?></span><br>
      <label>Salasõna (min 8 märki): </label><br>
      <input type="password" name="password">
	  <br>
	  
	  <input type="submit" name="submitUserdata" value="loo kasutaja">
		</form>
		<hr>
		<p><?php echo $notice; ?></p>
</body>
</html>