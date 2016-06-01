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
    echo "Kasutaja ". implode($_SESSION["kasutajanimi"]). " lisamine õnnestus. ";
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
        
        $k= htmlspecialchars(implode($_SESSION["kasutajanimi"]));
        $t = htmlspecialchars($_POST["sisendtext"]);
        $kasutaja = mysqli_real_escape_string($connection, $k);
        $tekst = mysqli_real_escape_string($connection, $t);
        $paring = "INSERT INTO Mroosi_text (`Kasutajanimi`, `Sisendtext`) VALUES ('$kasutaja','$tekst')";
        $sisse = mysqli_query($connection, $paring);
        
        header("Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=minu");
        mysqli_close($connection);
    }else{
        $_SESSION["viga"] = "Tühja välja ei saa postitada!";
        $_SESSION["teade"] = 1;
    }
}

//Siin küsitakse andmebaast olemasolevad komentaadid ja vastused.
function foorum(){
    connect_db();
    global $connection;
    
    $paring = "SELECT Kasutajanimi, Sisendtext FROM Mroosi_text";
    $foorum = mysqli_query($connection, $paring);
    $rida = mysqli_num_rows($foorum);
    mysqli_close($connection);
    
    if($rida>0){
        for ($i=0; $i <$rida ; $i++) {
            $vastused[] = mysqli_fetch_assoc($foorum);
        }
        $_SESSION["vastus"] = $vastused;
    }else{
        $_SESSION["viga"] = "Hetkel kirjeid pole, ole esimene!";
        $_SESSION["teade"] = 1;
    }
}

//see funktsioon võimaldab kindlale kasutajale teadet saata.
function chat(){
    if((isset($_POST["chattext"])) && (isset($_POST["adressaat"]))){
        if((!$_POST["chattext"] =="" ) && (!$_POST["adressaat"] =="") ){
            
            //sisestatud andmete kontroll ja andmebaasi saatmine.
            connect_db();
            global $connection;
            
            $k= htmlspecialchars(implode($_SESSION["kasutajanimi"]));
            $adr = htmlspecialchars($_POST["adressaat"]);
            $t = htmlspecialchars($_POST["chattext"]);
            $kasutaja = mysqli_real_escape_string($connection, $k);
            $adresaat = mysqli_real_escape_string($connection, $adr);
            $text = mysqli_real_escape_string($connection, $t);
            
            //Kas kasutaja kellele postitus oli mõeldud oli üldse olemas
            $kontroll = "SELECT Kasutajanimi FROM Mroosi_2 WHERE Kasutajanimi = '$adresaat' ";
            $kontr = mysqli_query($connection, $kontroll);
            $rida = mysqli_num_rows($kontr);
            
            //kui kasutaja on olemas siis saada postitus
            if($rida>0){
                $paring = "INSERT INTO Mroosi_jutt (`Kasutajanimi`, `Sisendtext`,`Adressaat`) VALUES ('$kasutaja','$text','$adresaat')";
                $sisestus = mysqli_query($connection, $paring);
                header("Location: http://enos.itcollege.ee/~mroosi/Projekt/main.php?page=minu");
            }else{
                $_SESSION["viga"] = "Sellist kasutajat pole!";
                $_SESSION["teade"] = 1;
            }
            mysqli_close($connection);
            }else{
                $_SESSION["viga"] = "Vigased väljad!";
                $_SESSION["teade"] = 1;
            }
    }
}

//sisselogitud kasutajale teadete kuvamine.
function teated(){
    connect_db();
    global $connection;
    
    $k= htmlspecialchars(implode($_SESSION["kasutajanimi"]));
    $kasutaja = mysqli_real_escape_string($connection, $k);
    $paring = "SELECT Kasutajanimi FROM Mroosi_jutt WHERE Adressaat = '$kasutaja'";
    $paring2 = "SELECT Adressaat FROM Mroosi_jutt WHERE Kasutajanimi = '$kasutaja'";
    $vastused = mysqli_query($connection, $paring);
    $vastused2 = mysqli_query($connection, $paring2);
    $rida = mysqli_num_rows($vastused);
    $rida2 = mysqli_num_rows($vastused2);
    mysqli_close($connection);
    
    //siin võetakse kõik sisselogitud kasutajale saadetud teadete autorid(saatja kasutajanimed) ja lisatakse masiivi.
    if($rida>0){
        for ($i=0; $i <$rida ; $i++) {
            $kasutajalist[] = mysqli_fetch_assoc($vastused);
            $kasut[$i]= ($kasutajalist[$i]['Kasutajanimi']);
        }
    }else{
        $kasut[0] = "Sissetulevaid teateid pole";
    }
    
    //siin võetakse kõik sisselogitud kasutaja poolt saadetud teadete adressaadid ja lisatakse masiivi.
    if($rida2>0){
        for ($i=0; $i <$rida2 ; $i++) {
            $kasutajalist2[] = mysqli_fetch_assoc($vastused2);
            $kasut2[$i]= ($kasutajalist2[$i]['Adressaat']);
        }
    }else{
        $kasut2[0] = "Saadetud teateid pole";
    }
    
//siin liidetakse massiivid kokku ja eemaldatakse toplelt väärtused
$liitmine = array_merge($kasut,$kasut2);
$tulemus = array_unique($liitmine, SORT_REGULAR);
$_SESSION["kasutajalist"] = $tulemus;
}

//siin toimub sisselogitud kasutaja ja valitud kasutaja vaheliste postituste andmebaasist välja küsimine
function kuvapostitusi(){
    if(isset($_POST["kasutajad"])){
    connect_db();
    global $connection;
    
    $k= htmlspecialchars(implode($_SESSION["kasutajanimi"]));
    $teinek = htmlspecialchars($_POST["kasutajad"]);
    $kasutaja = mysqli_real_escape_string($connection, $k);
    $teinekasutaja = mysqli_real_escape_string($connection, $teinek);
    $paring = "SELECT Kasutajanimi, Sisendtext FROM Mroosi_jutt WHERE Kasutajanimi IN('$kasutaja','$teinekasutaja') AND Adressaat IN ('$kasutaja','$teinekasutaja')";
    $vastused = mysqli_query($connection, $paring);
    $rida = mysqli_num_rows($vastused);
    
    mysqli_close($connection);
        if($rida>0){
            for ($i=0; $i <$rida ; $i++) {
                $postitused[] = mysqli_fetch_assoc($vastused);
            }
            $_SESSION["postitused"] = $postitused;
        }else{
            $_SESSION["viga"] = "Hetkel postitusi pole!";
            $_SESSION["teade"] = 1;
        }
    }
}

//Kuvatud postituste välja puhastamine
if(isset($_POST["puhasta"])){
    unset($_SESSION['postitused']);
}

require_once('vaated/minu.html');
?>
