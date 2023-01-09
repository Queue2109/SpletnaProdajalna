<?php

require_once("database_spletna_prodajalna.php");
require_once("model/cart.php");
require_once("ViewHelper.php");

class CheckoutController {
    
    public static function index() {
        $vars = [
            "cart" => Cart::getAll(),
            "total" => Cart::total()
        ];
        echo ViewHelper::render("prodajalna/checkout.php", $vars);
    }
    
    public static function dodajNarocilo() {
//        pridobi podatke: stranka id, instrument_id, kolicina, status
//        loop through cart
        $cartContent = Cart::getAll();
        $idNarocila = DBSpletnaProdajalna::returnLastId();
        if(!$idNarocila) {
            $idNarocila = 1;
        } else {
            $idNarocila = intval($idNarocila) + 1;
        }
        foreach ($cartContent as $instrument):
            DBSpletnaProdajalna::dodajNarocilo(strval($idNarocila), $_SESSION["userValues"]["id"], $instrument["id"], $instrument["quantity"], "neobdelano");
        endforeach;
        Cart::purge();
        ViewHelper::redirect(BASE_URL . "instruments");
    }

}
