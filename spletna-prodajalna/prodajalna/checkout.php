<?php
require_once('index.php');
session_start();
?>

<!doctype html>

<p>[
<a href="<?= BASE_URL . "instruments" ?>">Vsi instrumenti</a> |
    <?php
    if(!isset($_SESSION["user"])) {
        ?><a href="<?= BASE_URL . "login" ?>">Prijava</a> |<?php
    }
    ?>
  <?php
    if($_SESSION["user"] == "stranka") {
        ?><a href="<?= BASE_URL . "prodajalna" ?>">Prodajalna</a> <?php
    } 
    ?>
    
]</p>

<h1>Predogled narocila</h1>

<?php foreach ($cart as $instrument): ?>
                <?= $instrument["quantity"] ?> &times; <?= $instrument["ime"] ?> 
        <?php endforeach; ?>

        <p>Total: <b><?= number_format($total, 2) ?> EUR</b></p>
     <form action="<?= BASE_URL . "prodajalna/oddajNarocilo" ?>" method="post">
            <p><button>Oddaj narocilo</button></p>
        </form>