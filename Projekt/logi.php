<?php
    $_SESSION["teade"] = 0;
    function connect_db(){
	global $connection;
	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust - ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
}
//Kas on täidetud sisselogimise või registreerimise vorm või kas kasutaja on juba sees
if(isset($_SESSION["nimi"])){
   header('Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=minu');
}
if((!empty($_POST)) && (isset($_POST["regpassword"] ))){
     reg();
}else{
    logi();
}

    function logi(){
        //Siin toimub sisselogimine ja sisselogimise andmete kontroll
        if(!empty($_POST)){
            if((!$_POST["password"] == "" ) && (!$_POST["username"] == "" )){
            connect_db();
            global $connection;
            $k = htmlspecialchars($_POST["username"]);
            $p = htmlspecialchars($_POST["password"]);
			$kasutaja = mysqli_real_escape_string($connection, $k);
			$parool = mysqli_real_escape_string($connection, $p);
            $paring = "SELECT Nimi FROM Mroosi_2 WHERE Kasutajanimi = '$kasutaja' AND Parool = SHA1('$parool') ";
            $kyss = mysqli_query($connection, $paring);
            $rida = mysqli_num_rows($kyss);
            mysqli_close($connection);
            for ($i=0; $i <$rida ; $i++) { 
              $_SESSION["nimi"] = mysqli_fetch_assoc($kyss);
            }
            if($rida>0){
                $_SESSION["kasutajanimi"] = array($kasutaja);
                header('Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=minu');
              }else{
                    $_SESSION["viga"] = "Vale kasutajanimi või parool!";
                    $_SESSION["teade"] = 1;
                }
            }
            else{
            $_SESSION["viga"] = "Kõik väljad peavad olema täidetud!";
            $_SESSION["teade"] = 1;
            header("Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=logi");
        }
         
        } 
        }

    function reg(){
        //Siin saab kasutaja ennast registreerida ja kontrollitakse, et kasutajanimi poleks võetud.
     if(!empty($_POST)){
     if((!$_POST["regpassword"] == "" ) && (!$_POST["regusername"] == "" ) && 
     (!$_POST["name"] == "" ) &&  (!$_POST["regpassword2"] == "" )){
            $_SESSION["viga"] = "Kõik väljad peavad olema täidetud!";
            connect_db();
            global $connection;
            $rk = htmlspecialchars($_POST["regusername"]);
            $rp = htmlspecialchars($_POST["regpassword"]);
            $rp2 = htmlspecialchars($_POST["regpassword2"]);
            $n = htmlspecialchars($_POST["name"]);
            $re = htmlspecialchars($_POST["email"]);
			$regkasutaja = mysqli_real_escape_string($connection, $rk);
			$regparool = mysqli_real_escape_string($connection, $rp);
            $nimi = mysqli_real_escape_string($connection, $n);
			$regarool2 = mysqli_real_escape_string($connection, $rp2);
            $email = mysqli_real_escape_string($connection, $re);
            
              //kasutaja olemasolu kontroll ja uue kasutaja lisamine
            $regparing = "SELECT Kasutajanimi FROM Mroosi_2 WHERE Kasutajanimi = '$regkasutaja' ";
            $kyss = mysqli_query($connection, $regparing);
            $regrida = mysqli_num_rows($kyss);          
            if($regrida == 0 ){
            if($regparool == $regarool2){
            $lisaKasutaja = "INSERT INTO Mroosi_2 (`Kasutajanimi`, `Nimi`, `Email`, `Parool`) VALUES ('$regkasutaja','$nimi', '$email', SHA1('$regparool'))";
            $lisamine = mysqli_query($connection, $lisaKasutaja);       
            $_SESSION["kontroll"] = mysqli_affected_rows($connection);
             mysqli_close($connection);
            if($_SESSION["kontroll"] >0 ){              
                $_SESSION["kasutaja"] = 1;
                $_SESSION["nimi"] = array($nimi);
                $_SESSION["kasutajanimi"] = array($regkasutaja);
                 header('Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=minu');
            }
            }else{
                 $_SESSION["viga"] = "Paroolid ei klapi!";
                 $_SESSION["teade"] = 1;
            }
               
           }else{
                $_SESSION["viga"] = "Selline kasutaja on juba olemas, vali midagi muud!";
                $_SESSION["teade"] = 1;
           }
  
        }else{
            $_SESSION["viga"] = "Kõik väljad peavad olema täidetud!";
            $_SESSION["teade"] = 1;
        }
        
    }else{
            header("Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=logi");
        }
    }   

require_once('logi.html');
?>
