
<?php 
require_once("index.php");
session_start();
?>

<!DOCTYPE html>
<meta charset="UTF-8" />
<title>Add entry</title>


<p>[
<a href="<?= BASE_URL . "instruments" ?>">Vsi instrumenti</a> |
<a href="<?= BASE_URL . $_SESSION["user"] ?>">Profil</a> |
    <?php
    if(!isset($_SESSION["user"])) {
        ?><a href="<?= BASE_URL . "login" ?>">Prijava</a> <?php
    } else {
        
        ?><a href="<?= BASE_URL . "logout" ?>">Odjava</a> <?php
    }
    ?>

]</p>

<h1><?= $title ?></h1>

<?= $form ?>