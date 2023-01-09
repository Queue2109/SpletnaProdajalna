<?php 
require_once('index.php');
session_start();
?>

<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Add entry</title>

<p>[
<a href="<?= BASE_URL . "instruments" ?>">Vsi instrumenti</a> |
    <?php
    if(!isset($_SESSION["user"])) {
        ?><a href="<?= BASE_URL . "login" ?>">Prijava</a> |<?php
    }
    ?>

<a href="<?= BASE_URL . $_SESSION["user"] ?>"><?= $_SESSION["user"] ?></a>
]</p>


<h1>Dodaj nov instrument</h1>


<form action="<?= BASE_URL . "instruments/add" ?>" method="post">
    <p><label>Ime instrumenta: <input type="text" name="ime" value="<?= $ime ?>" autofocus /></label></p>
    <p><label>Proizvajalec: <input type="text" name="title" value="<?= $company ?>" /></label></p>
    <p><label>Cena: <input type="number" name="price" value="<?= $cena ?>" /></label></p>
    <p><label>Opis: <input type="number" name="year" value="<?= $opis ?>" /></label></p>
    <p><button>Dodaj</button></p>
    
</form>
