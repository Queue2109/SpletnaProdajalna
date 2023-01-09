<?php 
require_once('controller/loginController.php');
require_once('controller/registerController.php');
require_once('controller/instrumentController.php');
require_once('controller/storeController.php');
require_once('controller/checkoutController.php');
require_once('ViewHelper.php');
session_start();


define("BASE_URL", $_SERVER["SCRIPT_NAME"] . "/");
$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

$urls = [
    "instruments" => function() {
        InstrumentController::index();
    },
     "instruments/edit" => function() {
        InstrumentController::edit();
    },
    "instruments/delete" => function() {
        InstrumentController::delete();
    },
    "instruments/add" => function() {
        InstrumentController::add();
    },
    "login" => function () {
        DBLoginController::login();   
    },
    "admin" => function() {
        DBRegisterController::index("admin");
    },
    "admin/urediProdajalca" => function() {
        DBRegisterController::edit("prodajalec");
    },
    "admin/delete" => function() {
        DBRegisterController::delete("prodajalec");
    },
    "admin/urediSebe" => function() {
        DBRegisterController::editAdmin();
    },
    "prodajalec" => function() {
        DBRegisterController::index("prodajalec");
    },
    "prodajalec/urediStranko" => function() {
        DBRegisterController::edit("stranka");
    },
    "prodajalec/urediSebe" => function() {
        DBRegisterController::edit("prodajalec");
    },
    "prodajalec/delete" => function() {
        DBRegisterController::delete("stranka");
    },
    "prodajalna" => function() {
        StoreController::index();
    },
    "prodajalna/dodaj" => function() {
        StoreController::addToCart();
    },
    "prodajalna/update" => function() {
        StoreController::updateCart();
    },
    "prodajalna/pocisti" => function() {
        StoreController::purgeCart();
    },
    "prodajalna/checkout" => function() {
        CheckoutController::index();
    },
    "prodajalna/oddajNarocilo" => function() {
        CheckoutController::dodajNarocilo();
    },
    "logout" => function() {
        unset($_SESSION["user"]);
        unset($_SESSION["userValues"]);
        ViewHelper::redirect(BASE_URL . "login");
    },
    "orderDetails" => function() {
        StoreController::orderDetails();
    },
    "spremeniStatus" => function() {
        StoreController::spremeniStatus();
    },
    "" => function () {
        ViewHelper::redirect(BASE_URL . "instruments");
    },
       
];
    
    
try {
    if (isset($urls[$path])) {
        $urls[$path]();
    } else {
//        neveljaven naslov
        echo "No controller for '$path'";
    }
} catch (InvalidArgumentException $e) {
    ViewHelper::error404();
} catch (Exception $e) {
    echo "An error occurred: <pre>$e</pre>";
} 
