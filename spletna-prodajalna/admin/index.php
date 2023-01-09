<?php 
session_start();
require_once("index.php");
?>

<!doctype html>
<title>Spletna prodajalna</title>  
<p>
[
<a href="<?= BASE_URL . "logout" ?>">Odjava</a>] </p>


 <h1>Ustvari prodajalca</h1>

 <?=  $form ?>

 <dl>
 <?php foreach ($prodajalci as $prodajalec):?>
     <dd><a href="<?= BASE_URL . "admin/urediProdajalca?id=" . $prodajalec["id"] ?>">
         <?= $prodajalec["email"] ?>
         </a></dd>
 <?php endforeach; ?>
         

</dl>
 <hr>
 
 <a href="<?= BASE_URL . "admin/urediSebe?id=" . $_SESSION["userValues"]["id"] ?>">Uredi svoje podatke</a>
 