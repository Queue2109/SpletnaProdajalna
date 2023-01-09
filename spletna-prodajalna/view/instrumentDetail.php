<?php 
require_once("index.php");
session_start();
?>

<!doctype html>
<meta charset="UTF-8" />
<title>Instrumenti</title>


<p>[
<a href="<?= BASE_URL . "instruments" ?>">Vsi instrumenti</a> |
    <?php
    if(!isset($_SESSION["user"])) {
        ?><a href="<?= BASE_URL . "login" ?>">Prijava</a> |<?php
    }
    if($_SESSION["user"] == "stranka") {
        ?><a href="<?= BASE_URL . "prodajalna" ?>">Prodajalna</a> |<?php
    } else {?>
        <a href="<?= BASE_URL . $_SESSION["user"] ?>"><?= $_SESSION["user"] ?></a>
   <?php }?>
]</p>

<ul>
    <li>Ime instrumenta: <b><?= $instrument["ime"] ?></b></li>
    <li>Proizvajalec: <b><?= $instrument["company"] ?></b></li>
    <li>Cena: <b><?= $instrument["cena"] ?> EUR</b></li>
    <li>Opis: <i><?= $instrument["opis"] ?></i></li>
</ul>
 <?php
    if($_SESSION["user"] == "prodajalec") {
        ?><p>[ <a href="<?= BASE_URL . "instruments/edit?id=" . $instrument["id"] ?>">Edit</a>]</p><?php
    }
    ?>
