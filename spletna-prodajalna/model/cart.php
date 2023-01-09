<?php

require_once("database_spletna_prodajalna.php");

class Cart {

    public static function getAll() {
        if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
            return [];
        }

        $ids = array_keys($_SESSION["cart"]);
        $cart = DBSpletnaProdajalna::getForIds($ids);

        // Adds a quantity field to each book in the list
        foreach ($cart as &$instrument) {
            $instrument["quantity"] = $_SESSION["cart"][$instrument["id"]];
        }

        return $cart;
    }

    public static function add($id) {
        $instrument = DBSpletnaProdajalna::get($id, "instrument");

        if ($instrument != null) {
            if (isset($_SESSION["cart"][$id])) {
                $_SESSION["cart"][$id] += 1;
            } else {
                $_SESSION["cart"][$id] = 1;
            }            
        }
    }

    public static function update($id, $quantity) {
        
        $instrument = DBSpletnaProdajalna::get($id, "instrument");
        $quantity = intval($quantity);

        if ($instrument != null) {
            if ($quantity <= 0) {
                unset($_SESSION["cart"][$id]);
            } else {
                $_SESSION["cart"][$id] = $quantity;
            }
        }
    }

    public static function purge() {
        unset($_SESSION["cart"]);
    }

    public static function total() {
        return array_reduce(self::getAll(), function ($total, $instrument) {
            return $total + $instrument["cena"] * $instrument["quantity"];
        }, 0);
    }
}
