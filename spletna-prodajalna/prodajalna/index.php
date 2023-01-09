<?php
require_once('index.php');
require_once('database_spletna_prodajalna.php');
require_once('controller/storeController.php');
session_start();
?>
<!DOCTYPE html>

<meta charset="UTF-8" />
<title>Prodajalna</title>
<p>
[<a href="<?= BASE_URL . "instruments" ?>">Vsi instrumenti</a> |
<a href="<?= BASE_URL . "logout" ?>">Odjava</a>] </p>
<h1>Bookstore</h1>



<div>
    <?php foreach ($instruments as $instrument): ?>

        <div>
            <form action="<?= BASE_URL . "prodajalna/dodaj" ?>" method="post">
                <input type="hidden" name="id" value="<?= $instrument["id"] ?>" />
                <p><?= $instrument["ime"] ?></p>
                <p><?= $instrument["company"] ?></p>
                <p><?= number_format($instrument["cena"], 2) ?> EUR<br/>
                <button>Add to cart</button>
            </form> 
        </div>

    <?php endforeach; ?>

</div>

<?php if (!empty($cart)): ?>

    <div>
        <h3>Nakupovalna kosarica</h3>
        <?php foreach ($cart as $instrument): ?>

            <form action="<?= BASE_URL . "prodajalna/update" ?>" method="post">
                <input type="hidden" name="id" value="<?= $instrument["id"] ?>" />
                <input type="number" name="quantity" value="<?= $instrument["quantity"] ?>" class="update-cart" />
                &times; <?= $instrument["ime"] ?> 
                <button>Update</button> 
            </form>

        <?php endforeach; ?>

        <p>Total: <b><?= number_format($total, 2) ?> EUR</b></p>
        <form action="<?= BASE_URL . "prodajalna/checkout" ?>" method="post">
            <p><button>Zakljuci nakup</button></p>
        </form>
        <form action="<?= BASE_URL . "prodajalna/pocisti" ?>" method="post">
            <p><button>Pocisti izdelke</button></p>
        </form>
    </div>    

<?php endif; ?>

<div>
    <h1>Moja narocila</h1><?php
   
    $prev = null;
    $first = 1;

    foreach ($narocila as $narocilo):
        if ($narocilo["narocilo_st"] != $prev) {
            // print data from first, second, and third index
            if($first == 1) {
                $first = 0;
            } else {
                echo ", status: " . $narocilo["status"];
            }?>
    <br><?php
            echo 'Narocilo stevilka ' .  $narocilo["narocilo_st"] . ': ' . $narocilo["kolicina"] . ' x ' . DBSpletnaProdajalna::get($narocilo["instrument_id"], "instrument")["ime"];
            $prev = $narocilo["narocilo_st"];
        } else {
            // print data from second and third index 
            echo ", " . $narocilo["kolicina"] . ' x ' . DBSpletnaProdajalna::get($narocilo["instrument_id"], "instrument")["ime"];
        }
    endforeach; 
    if($first == 0) {
            echo ", status: ". $narocilo["status"];
               
            }else {
                echo "Ni preteklih narocil";
            }?>
                 
</div>
