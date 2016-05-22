<?php

function connect_db(){
	global $connection;
	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust - ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
}
//kasutaja registreerumise teade
if((isset ($_SESSION["kontroll"])) && ($_SESSION["kontroll"] == 1 )){
    echo "Kasutaja ". implode($_SESSION["nimi"]). " lisamine õnnestus. "; 
    $_SESSION["kontroll"] = 2;     
   }
//Sisselogimise teade
if(isset ($_SESSION["nimi"])){
    if(!isset ($_SESSION["tervitus"])){
        echo"Tere tulemast ". implode($_SESSION["nimi"])."!";
        $_SESSION["tervitus"] = 1;
    }
}else{
    header('Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=logi');
}
//Postituse sisestamine ja saatmine andmebaasi.
if(isset ($_POST["sisendtext"])){
  if((!$_POST["sisendtext"] == "" )){
   connect_db();
   global $connection;
   $kasutaja= implode($_SESSION["kasutajanimi"]);
   $t = htmlspecialchars($_POST["sisendtext"]);
   $tekst = mysqli_real_escape_string($connection, $t);
   $paring = "INSERT INTO Mroosi_text (`Kasutajanimi`, `Sisendtext`) VALUES ('$kasutaja','$tekst')";
   $sisse = mysqli_query($connection, $paring);
  }else{
      $viga= "Tühja välja ei saa postitada!";
      echo $viga;
  }
            
  }
//Siin küsitakse andmebaast olemasolevad komentaadid ja vastused.
 function foorum(){
   connect_db();
   global $connection; 
   $paring = "SELECT Kasutajanimi, Sisendtext FROM Mroosi_text";
   $foorum = mysqli_query($connection, $paring);
   $rida = mysqli_num_rows($foorum);
     if($rida>0){
         for ($i=0; $i <$rida ; $i++) { 
            $vastused[] = mysqli_fetch_assoc($foorum);
         }
         $_SESSION["vastus"] = $vastused;
     }else{
         $viga= "Hetkel kirjeid pole, ole esimene!";
         echo $viga;
     }
 }
       
require_once('minu.html');

?>
