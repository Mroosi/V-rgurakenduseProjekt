<?php
    if (!isset($_SESSION)) session_start();
    $_SESSION["kasutaja"] = 0;
    require_once('header.html');
        if( !empty($_GET)){
             $vaartus = $_GET;
        }else{
             $vaartus = array('pealeht');
             echo "mingijama";
             echo implode($_GET);
        }
      
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
        
    }
  }
    require_once('footer.html');
?>
