<?php
 require("../../../config.php");
 //echo $GLOBALS["serverHost"];
 //echo $GLOBALS["serverUsername"];
 //echo $GLOBALS["serverPassword"];
 $database = "if18_eliisa_ha_1";
 //alustan sessiooni
 session_start();

	function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg3eh WHERE valid IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();
	
	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
	function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg3 WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

function signin($email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt =$mysqli->prepare("SELECT  id, firstname, lastname, password FROM vpusers WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s",$email);
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb , $passwordFromDb);
	if($stmt->execute()){
		//kui päring õnnestus
	 if($stmt->fetch()){
		  //kasutaja on olemas
		 if(password_verify($password,$passwordFromDb)){
			 //kui salasõna klapib
			 $notice = "Logisite sisse!";
			 //määran sessioonimuutajad
			 $_SESSION["userid"] = $idFromDb;
			 $_SESSION["userFirstName"] = $firstnameFromDb;
			 $_SESSION["userLastName"] = $lastnameFromDb;
			 $_SESSION["userEmail"] = $email;
			 //liigume vaid sisselogitutele mõeldud pealehele
			 $stmt->close();
			 $mysqli->close();
			 header("Location: main.php");
			 exit();
		 } else {
			 //kui salasõna ei klapib
			 $notice = "Vale salasõna";
		 }
	  } else {
		 $notice="Sellist kasutajad (" .$email .") ei leitud"; 	 
	  }
	} else {
	 $notice = "Sisselogimisel tekkis tehniline viga!"; $stmt->error;
	}
	
	//sulgen käsu
 	$stmt->close();
 	//sulgen andmebaasiühenduse
 	$mysqli->close();
 	//tagastan funktsiooni väljakutsujale kokku pandud html-koodi
 	return $notice;
	
}//sisselogimine lõpeb
   function signup($name, $surname, $email, $gender, $birthDate, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
	echo $mysqli->error;
	//krüpteerin parooli, kasutades juhuslikku soolamisfraasi (salting string)
	$options = [
	  "cost" => 12,
	  "salt" => substr(sha1(rand()), 0, 22),
	  ];
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	echo "Kuupäev: ".$birthDate;
	$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	if($stmt->execute()){
		$notice = "ok";
	} else {
	  $notice = "error" .$stmt->error;	
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
   function saveAMsg($msg){
    //echo "Töötab!";
    $notice = ""; //see on teade, mis antakse salvestamise kohta
     //loome ühenduse andmebaasiserveriga
     $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
     //Valmistame ette SQL päringu
     $stmt = $mysqli->prepare("INSERT INTO vpamsg3eh (message) VALUES(?)");
     echo $mysqli->error;
     $stmt->bind_param("s", $msg);//s - string, i - integer, d - decimal
     if ($stmt->execute()){
       $notice = 'Sõnum: "' .$msg .'" on salvestatud!';  
     } else {
 	  $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
     }
     $stmt->close();
     $mysqli->close();
     return $notice;
   }
 
   //funktsioon, mis loeb kõiki salvestatud sõnumeid (seda kutsub readmsg.php)
   function readallmessages(){
 	$notice = "";
 	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
 	//valmistame ette sõnumite lugemise SQL käsu
 	$stmt = $mysqli->prepare("SELECT message FROM vpamsg3eh");
 	echo $mysqli->error;
 	//seon loetavad andmed muutujatega, siin praegu iga kirjapandud sõnumi kohta küsisingi vaid sõnumit ennas ja seon selle muutujaga $msg
 	$stmt->bind_result($msg);
 	//täidan käsu
 	$stmt->execute();
 	//järgnevalt saab iga järgmise loetud sõnumi käsuga $stmt->fetch()
 	//kasutan "while" tsüklit, mida täidetakse siinkohal kuni veel on midagi võtta ehk fetchida
 	while($stmt->fetch()){
 		//iga kord järjekordset sõnumit võttes panen selle eespool loodud muutuja $notice väärtusele juurde ( .= nagu arvudega oleks += )
 		//siinkohal moodustan iga sõnumi jaoks html lõigu
 		$notice .= "<p>" .$msg ."</p> \n";
 	}
 	//sulgen käsu
 	$stmt->close();
 	//sulgen andmebaasiühenduse
 	$mysqli->close();
 	//tagastan funktsiooni väljakutsujale kokku pandud html-koodi
 	return $notice;
   }
      
   function test_input($data) {
     //echo "Koristan!\n";
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
   }
 ?>