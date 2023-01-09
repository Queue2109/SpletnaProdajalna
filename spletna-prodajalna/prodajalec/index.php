<?php 
require_once("index.php");
session_start();

?>

<!doctype html>
<title>Spletna prodajalna</title>  
 
<a href="<?= BASE_URL . "instruments" ?>">Vsi instrumenti</a> |
<a href="<?= BASE_URL . "logout" ?>">Odjava</a> 

 <h1>Ustvari stranko</h1>
 
 <?=  $form ?>

  <dl>
 <?php foreach ($stranke as $stranka):?>
     <dd><a href="<?= BASE_URL . "prodajalec/urediStranko?id=" . $stranka["id"] ?>">
         <?= $stranka["email"] ?>
         </a></dd>
 <?php endforeach; ?>
       
</dl>  
 <hr>
 
 <a href="<?= BASE_URL . "prodajalec/urediSebe?id=" . $_SESSION["userValues"]["id"] ?>">Uredi svoje podatke</a>