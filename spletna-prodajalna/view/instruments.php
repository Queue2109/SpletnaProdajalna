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
    ?>
  <?php
    if($_SESSION["user"] == "stranka") {
        ?><a href="<?= BASE_URL . "prodajalna" ?>">Prodajalna</a> |<?php
    } else if($_SESSION["user"] == "prodajalec") {?>
        <a href="<?= BASE_URL . $_SESSION["user"] ?>">Profil</a> |
        <a href="<?= BASE_URL . "instruments/add" ?>">Dodaj instrument</a> |
   <?php }
   if(isset($_SESSION["user"])) {
        ?><a href="<?= BASE_URL . "logout" ?>"> Odjava</a> <?php
    }
    ?>
    
]</p>


<ul>

    <?php foreach ($instruments as $instrument): ?>
        <li><a href="<?= BASE_URL . "instruments?id=" . $instrument["id"] ?>"><?= $instrument["ime"] ?> 
                <?= $instrument["company"] ?> <?= $instrument["opis"] ?> (<?= $instrument["cena"] ?>€)</a></li>
    <?php endforeach; ?>

</ul>

  <?php  
  if($_SESSION["user"] == "prodajalec") { ?>
                <div><h1>Neobdelana narocila</h1><?php

              $prev = null;
              $first = 1;

              foreach ($narocila as $narocilo):
                  if ($narocilo["narocilo_st"] != $prev) {
                      // print data from first, second, and third index
                      if($first == 1) {
                          $first = 0;
                      } else {
                          echo ", status: " ; ?>
                          <a href="<?= BASE_URL . "orderDetails?id=" . $narocilo["id"] ?>"><?= $narocilo["status"]  ?></a> 

                     <?php }?><br><?php
                      echo 'Narocilo stevilka ' .  $narocilo["narocilo_st"] . ': ' . $narocilo["kolicina"] . ' x ' . DBSpletnaProdajalna::get($narocilo["instrument_id"], "instrument")["ime"];
                      $prev = $narocilo["narocilo_st"];
                  } else {
                      // print data from second and third index 
                      echo ", " . $narocilo["kolicina"] . ' x ' . DBSpletnaProdajalna::get($narocilo["instrument_id"], "instrument")["ime"];
                  }
              endforeach; 
              if($first == 0) {
                  echo ", status: " ; ?>
                     <a href="<?= BASE_URL . "orderDetails?id=" . $narocilo["id"] ?>"><?= $narocilo["status"]  ?></a> <?php
              } else {
                  echo "Ni preteklih narocil";

              }?>

          </div>


          <div>
              <?php 
                  ?> <h1>Zgodovina narocil</h1><?php
              $prev = null;
              $first = 1;

              foreach ($potrjenaNarocila as $narocilo):
                  if ($narocilo["narocilo_st"] != $prev) {
                      // print data from first, second, and third index
                      if($first == 1) {
                          $first = 0;
                      } else {
                          echo ", status: " ;?> 
                          <a href="<?= BASE_URL . "orderDetails?id=" . $narocilo["id"] ?>"><?= $narocilo["status"]  ?></a> 
                         <?php
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
                  echo ", status: " ;?>  
                     <a href="<?= BASE_URL . "orderDetails?id=" . $narocilo["id"] ?>"><?= $narocilo["status"]  ?></a> 
                  <?php  
              }else {
                  echo "Ni preteklih narocil";
              }?> 
          </div>
<?php       } ?>

