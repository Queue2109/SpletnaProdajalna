<?php
require_once("index.php");
require_once('forms/LoginForm.php');
require_once('ViewHelper.php');
require_once('database_spletna_prodajalna.php');
require_once('controller/registerController.php');
session_start();

class DBLoginController {
    
    public static function login() {
        
        $form = new LoginForm("login");

        if ($form->validate()) {
            $values =$form->getValue();
            $nacinPrijave = $values["nacin"];
            if(!DBSpletnaProdajalna::checkIfValid($values, $nacinPrijave)) {
                echo ViewHelper::redirect("login");
                return;
            }
            switch ($nacinPrijave) {
                case "admin":
                    $_SESSION["user"] = "admin";
                    $_SESSION["userValues"] = DBSpletnaProdajalna::getUserByEmail($values["email"], "admin");
                    print_r($_SESSION);
//                    header('Location: ../certificate/index.php');
//                    exit;
                    break;
                case "prodajalec":
                    $_SESSION["user"] = "prodajalec";                    
                    $_SESSION["userValues"] = DBSpletnaProdajalna::getUserByEmail($values["email"], "prodajalec");
                    ViewHelper::redirect("prodajalec");
                    break;
                    case "stranka":
                    $_SESSION["user"] = "stranka";                    
                    $_SESSION["userValues"] = DBSpletnaProdajalna::getUserByEmail($values["email"], "stranka");
                    ViewHelper::redirect("instruments");
                    break;
                default :
                    echo "Ta nacin prijave ne obstaja";
                    break;
            }
        } else {
            echo ViewHelper::render("view/loginView.php", [
                "form" => $form 
            ]);
        }
    }
}