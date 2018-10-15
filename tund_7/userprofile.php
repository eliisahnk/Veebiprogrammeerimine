<?php
  require ("functions.php");
  
  $notice = null;
	if (isset($_POST["submitMessage"])){
	if ($_POST["message"] != "Kirjuta siia oma sõnum..." and !empty($_POST["message"])){
		$notice = "Sõnum olemas!";
		$notice = saveAMsg($_POST["message"]);
	}	else {
			$notice = "Palun kirjutage sõnum!";
		}
		
	}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>
	  <?php
	    echo $_SESSION["userName"];
		echo " ";
		echo $_SESSION["userSurname"];
	  ?>
	, õppetöö</title>
  </head>
  <body>
    <h1>
	  <?php
	    echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	  ?>
	</h1>
          <p>Siin on <a href="http://www.tlu.ee" target-"_blank">TLÜ</a>  õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<hr>
	<ul>
	  <li><a href="?logout=1">Logi välja</a>!</li>
	  <li><a href="main.php">Tagasi pealehele</a></li>
	</ul>
<hr>
    <form method= "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Minu kirjeldus: </label>
		<br><br>
		<textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
		<br>
		<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
		<label>Minu valitud tekstivärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
		<br>
		<input name="submitMessage" type="submit" value= "Salvesta profiil ">
		<br><br>
		</form>
		<p>
		<?php
			echo $notice;
		?>
		</p>
		
	

</body>
</html>