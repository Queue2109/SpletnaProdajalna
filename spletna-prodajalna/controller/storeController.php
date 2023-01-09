<?php
session_start();
require_once('index.php');
require_once("database_spletna_prodajalna.php");
require_once("model/cart.php");
require_once("ViewHelper.php");

class StoreController {

    public static function index() {
        $vars = [
            "instruments" => DBSpletnaProdajalna::getAll("instrument"),
            "cart" => Cart::getAll(),
            "total" => Cart::total(),
            "narocila" => self::oblikujNarocila()
        ];
        
        if($_SESSION["user"] == "prodajalec") {
            $vars = [
            "instruments" => DBSpletnaProdajalna::getAll("instrument"),
            "cart" => Cart::getAll(),
            "total" => Cart::total(),
            "narocila" => self::oblikujNarocila(),
            "potrjenaNarocila" => DBSpletnaProdajalna::getOrdersByStatus("potrjeno")
        ];
        }
        echo ViewHelper::render("prodajalna/index.php", $vars);

    }
    
    public static function oblikujNarocila() {
//        vsa narocila
        $vsaNarocila = DBSpletnaProdajalna::getAll("narocilo");
        $col = array_column( $vsaNarocila, "price" );
        array_multisort( $col, SORT_ASC, $vsaNarocila);
        return $vsaNarocila;
    }
    
       public static function spremeniStatus() {
        $status = $_POST["status"];
        $narocilo_st = $_POST["narocilo_st"];
        if($status == "potrjeno" || $status == "preklicano" || $status == "stornirano") {
            DBSpletnaProdajalna::changeOrderStatus($narocilo_st, $status);
        }
                ViewHelper::redirect(BASE_URL . "instruments");
    }

    
    public static function orderDetails() {
        
         $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);
                if (isset($data["id"])) {
                     echo ViewHelper::render("view/orderDetails.php", [
                    "narocilo" => DBSpletnaProdajalna::get($data["id"], "narociloById")
                    ]);
                }       
    }

    public static function addToCart() {
        $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;

        if ($id !== null) {
            Cart::add($id);
        }

        ViewHelper::redirect(BASE_URL . "prodajalna");
    }

    public static function updateCart() {
        $id = (isset($_POST["id"])) ? intval($_POST["id"]) : null;
        $quantity = (isset($_POST["quantity"])) ? intval($_POST["quantity"]) : null;

        if ($id !== null && $quantity !== null) {
            Cart::update($id, $quantity);
        }

        ViewHelper::redirect(BASE_URL . "prodajalna");
    }

    public static function purgeCart() {
        Cart::purge();

        ViewHelper::redirect(BASE_URL . "prodajalna");
    }

}
