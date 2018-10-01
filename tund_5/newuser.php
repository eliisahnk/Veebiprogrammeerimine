<?php
  require("functions.php");
  $name = "";
  $surname = "";
  $email = "";
  $gender = "";
  $birthMonth = null;
  $birthYear = null;
  $birthDay = null;
  $birthDate = null;
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  //muutujad võimalike veateadetega
  $nameError = "";
  $surnameError = "";
  $birthMonthError = "";
  $birthYearError = "";
  $birthDayError = "";
  $genderError = "";
  $emailError = "";
  $passwordError = "";
  
  //kui on uue kasutaja loomise nuppu vajutatud
  if(isset($_POST["submitUserData"])){
  
  if (isset($_POST["firstName"]) and !empty($_POST["firstName"])){
	//$name = $_POST["firstName"];
	$name = test_input($_POST["firstName"]);
  } else {
	  $nameError = "Palun sisesta eesnimi!";
  }
  
  if (isset($_POST["surName"])){
	//$surname = $_POST["surName"];
	$surname = test_input($_POST["surName"]);
  }
  
  if(isset($_POST["gender"])){
	$gender = intval ($_POST["gender"]);
  } else {
	  $genderError = "Palun märgi sugu!";
  }
  //kontrollime, kas sünniaeg sisestati ja kas on korrektne
  if(isset($_POST["birthDay"])){
	  $birthDay= $_POST[birthDay];
  }
   if(isset($_POST["birthMonth"])){
	  $birthMonth= $_POST[birthMonth];
  }
   if(isset($_POST["birthYear"])){
	  $birthDay= $_POST[birthYear];
  }
  //kontrollin kuupäeva õigsust
  if(isset($_POST["birthDay"]) and isset($_POST["birthMonth"]) and isset($_POST["birthYear"])){
  //checkdate(päev,kuu,aasta)
  if(checkdate(intval($_POST["birthMonth"]), intval($_POST["birthDay"]), intval($_POST["birthYear"]))) {
	$birthDate = date_create($_POST["birthMonth"] ."/" .$_POST["birthDay"] ."/" .$_POST["birthYear"]);
	$birthDate = date_format($birthDate, "Y-m-d");
	echo $birthDate;
	} else {
	  $brthYearError= "Kuupäev on vigane";
	  
  }
  }
  //kui kõik on korras salvestame kasutaja
  if(empty($nameError) and empty($surnameError) and empty($birthMonthError)and empty($birthYearError)and empty($birthDayError)and empty($genderError)and empty($emailError)and empty($passwordError)){
	$notice = signup($name, $surname, $email, $gender, $birthDate, $_POST["password"])
	
  }
  }//kui on nuppu vajutatud - lõppeb
?>
 <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Katselise veebi uue kasutaja loomine</title>
  </head>
  <body>
     <h1>
     Uue kasutaja loomine
	 </h1>
  
          <p>Siin on <a href="http://www.tlu.ee" target-"_blank">TLÜ</a>  õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
<hr>
    <form method= "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Eesnimi: </label>
		<input name="firstName" type="text" value= "">
		<br><br>
		<label>Perenimi: </label>
		<input name="surName" type="text" value= "">
		<br><br>
		
	<label>Sünnipäev: </label>
	  <?php
	    echo '<select name="birthDay">' ."\n";
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
		for ($i = $birthYear; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <br><br>
	  <input name="submitUserData" type="submit" value="Saada andmed">
	</form>
	
	
	<?php
		if (isset($_POST["firstName"])){
			//demoks üks väike funktsioon (tegelikult mõttetu)
			fullName();
			echo "<br><p>Olete elanud järgnevatel aastatel: </p>";
			echo "<ul> \n";
		for ($i = $_POST["birtYear"]; $i <= date("Y"); $i++){
			echo "<li>" . $i . "</li> \n";
		}
			echo "</ul> \n";
		}
	?>

</body>
</html>