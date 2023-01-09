<?php 
require_once("index.php");
session_start();
?>

<!doctype html>
<meta charset="UTF-8" />
<title>Narocilo</title>


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

<p>Status narocila: <?= $narocilo["status"] ?></p>

<p>
    <?php 
if($narocilo["status"] == "potrjeno") {
    ?>
        <form action="<?= BASE_URL . "spremeniStatus" ?>" method="post">
        <p><label><input type="hidden" name="narocilo_st" value="<?= $narocilo["narocilo_st"] ?>" /></label></p>

        <p><label><input type="hidden" name="status" value="stornirano" /></label></p>
       <p><button>Storniraj</button></p>
    </form>
        <?php
} else {
?>Spremeni status:

    <form action="<?= BASE_URL . "spremeniStatus" ?>" method="post">
        <p><label><input type="hidden" name="narocilo_st" value="<?= $narocilo["narocilo_st"] ?>" /></label></p>

        <p><label><input type="text" name="status" value="<?= $narocilo["status"] ?>" /></label></p>
       <p><button>Spremeni</button></p>
    </form><?php 
}?>



</p>