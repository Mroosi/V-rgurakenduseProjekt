<?php
if (!isset($_SESSION)) session_start();
    $_SESSION["kasutaja"] = 0;
    require_once('vaated/header.html');
//peale POST pärinugu toimumist valitakse kas tegu oli sisselogimise või registreerimise POSTiga
//või teksti sisestamises POSTiga.
if( !empty($_POST)){
    if((isset($_POST["username"])) || (isset($_POST["regusername"]))){
        require('logi.php');
    }
    if((isset($_POST["sisendtext"])) || (isset($_POST["chattext"])) || 
        (isset($_POST["kasutajad"])) || (isset($_POST["puhasta"]))){
            require('minu.php');
    }
}

//Get päring suunab õigelel lehele, kui GET on tühi siis saab vaikimisi väärtuseks "pealeht".
if( !empty($_GET)){
    $vaartus = $_GET;
}else{
    $vaartus = array('pealeht');
}

//SWITCH CASE-id õigele lehele jõudmiseks
$leht = implode($vaartus);
if(empty($_POST)){
    switch ($leht) {
        case 'pealeht':
            require('vaated/pealeht.html');
        break;

        case 'logi':
            require('logi.php');
        break;

        case 'minu':
            require('minu.php');
        break;

        case 'exit':
            require('exit.php');
        break;

    }
}
require_once('vaated/footer.html');
?>
