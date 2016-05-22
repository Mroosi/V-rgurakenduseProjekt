<?php
    if (!isset($_SESSION)) session_start();
    $_SESSION["kasutaja"] = 0;
    require_once('header.html');
    //peale POST pärinugu toimumist valitakse kas tegu oli sisselogimise või registreerimise POSTiga
    //või teksti sisestamises POSTiga.
        if( !empty($_POST)){
            if((!empty($_POST["username"])) || (!empty($_POST["regusername"]))){
                require('logi.php');
            }
            if(isset($_POST["sisendtext"])){
                require('minu.php');
            }
     //Get päring suunab õigelel lehele, kui GET on tühi siis saab vaikimisi väärtuseks "pealeht".
        }
        if( !empty($_GET)){
             $vaartus = $_GET;
        }else{
             $vaartus = array('pealeht');
        }
      //SWITCH CASE-id valimaks õige lehe
        $leht = implode($vaartus);
        if(empty($_POST)){
        switch ($leht) {
        case 'pealeht':
        require('pealeht.html');
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
    require_once('footer.html');
?>
