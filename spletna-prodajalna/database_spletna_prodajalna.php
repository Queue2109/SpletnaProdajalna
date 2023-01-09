<?php
require_once("index.php");
require_once('ViewHelper.php');
require_once './database_init.php';
session_start();

class DBSpletnaProdajalna {
    public static function getAll($person) {
         $db = DBInit::getInstance();
         switch ($person) {
            case "admin":
                $statement = $db->prepare("SELECT * FROM admin");
                break;
            case "prodajalec":
                $statement = $db->prepare("SELECT * FROM prodajalec");
                break;
            case "stranka":
                $statement = $db->prepare("SELECT * FROM stranka");
                break;
             case "instrument":
                $statement = $db->prepare("SELECT * FROM instrument");
                break;
            case "narocilo":
                if($_SESSION["user"] == "stranka") {
                    $statement = $db->prepare("SELECT * FROM narocilo WHERE stranka_id = :stranka_id");
                    $statement->bindParam(":stranka_id", $_SESSION["userValues"]["id"]);
                } else {
                    return self::getOrdersByStatus("neobdelano");
                }
                break;
            
        }
        $statement->execute();

        return $statement->fetchAll();
    }
     public static function getOrdersByStatus($status) {
         
         $db = DBInit::getInstance();
         $statement = $db->prepare("SELECT * FROM narocilo WHERE status = :status");
         $statement->bindParam(":status", $status);
    
        $statement->execute();

        return $statement->fetchAll();
    }
    
    
      public static function getForIds($ids) {
        $db = DBInit::getInstance();

        $id_placeholders = implode(",", array_fill(0, count($ids), "?"));

        $statement = $db->prepare("SELECT id, ime, company, cena, opis FROM instrument 
            WHERE id IN (" . $id_placeholders . ")");
        $statement->execute($ids);

        return $statement->fetchAll();
    }
    
    public static function checkIfValid($values, $nacin) {
    //if true, it redirects to correct page
    //if not, it says user does not exist
        $db = DBInit::getInstance();    
        if($nacin == "admin") {
            $statement = $db->prepare("SELECT * FROM admin WHERE email = :email AND geslo = :geslo");
        } else if($nacin == "prodajalec") {
            $statement = $db->prepare("SELECT * FROM prodajalec WHERE email = :email AND geslo = :geslo AND is_active = 1");
        } else if($nacin =="stranka") {
            $statement = $db->prepare("SELECT * FROM stranka WHERE email = :email AND geslo = :geslo AND is_active = 1");

        }
        $statement->bindParam(":email", $values["email"]);
        $statement->bindValue(':geslo', $values["geslo"]);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
//        if(!$user["status"]) {
//            return false;
//        }
        return $user;
    }
    
    public static function get($id, $oseba) {
        $db = DBInit::getInstance();
        switch ($oseba) {
            case "admin":
                $statement = $db->prepare("SELECT * FROM admin WHERE id = :id");
                break;
            case "prodajalec":
                $statement = $db->prepare("SELECT * FROM prodajalec WHERE id = :id");
                break;
            case "stranka":
                $statement = $db->prepare("SELECT * FROM stranka WHERE id = :id");
                break;
            case "instrument":
                $statement = $db->prepare("SELECT * FROM instrument WHERE id = :id");
                break;
            case "narocilo":
                $statement = $db->prepare("SELECT * FROM narocilo WHERE stranka_id = :id");
                break;
            case "narociloById":
                $statement = $db->prepare("SELECT * FROM narocilo WHERE id = :id");
                break;
            
        }
        $statement->bindParam(":id", $id);
        $statement->execute();
        $person = $statement->fetch(PDO::FETCH_ASSOC);
        return $person;
    }
    
    public static function returnUser() {
        
        
        $authorized_users = $_SESSION["user"];
        
        # preberemo odjemačev certifikat
        $client_cert = filter_input(INPUT_SERVER, "SSL_CLIENT_CERT");
//
//        # in ga razčlenemo
        $cert_data = openssl_x509_parse($client_cert);
//        
//        # preberemo ime uporabnika (polje "common name")
        $commonname = $cert_data['subject']['CN'];
        if (in_array($commonname, $authorized_users)) {
            if($commonname == "admin") {
                ViewHelper::redirect("admin");
            } else if($commonname == "prodajalec") {
                ViewHelper::redirect("admin");

            }
        } 
    }
    
    public static function changeOrderStatus($narocilo_st, $status) {
        $db = DBInit::getInstance();

        if($status == "stornirano") {
            $statement = $db->prepare("SELECT * FROM narocilo WHERE narocilo_st = :narocilo_st");

            $statement->bindParam(":narocilo_st", $narocilo_st);
            $statement->execute();
            $statusTrenutni = $statement->fetch(PDO::FETCH_ASSOC)["status"];
            if($statusTrenutni != "potrjeno"){
                return;
            }
        }
        
        $statement = $db->prepare("UPDATE narocilo SET status = :status WHERE narocilo_st = :narocilo_st");
        $statement->bindParam(":narocilo_st", $narocilo_st);
        $statement->bindParam(":status", $status);
    
        $statement->execute();
    }
    
        
    public static function getUserByEmail($email, $person) {
        $db = DBInit::getInstance();
        
        switch ($person) {
            case "admin": 
                $statement = $db->prepare("SELECT * FROM admin WHERE email = :email");
                break;
            case "prodajalec":
                $statement = $db->prepare("SELECT * FROM prodajalec WHERE email = :email");
                break;
            case "stranka":
                $statement = $db->prepare("SELECT * FROM stranka WHERE email = :email");
                break;
        }
        $statement->bindParam(":email", $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
     public static function uredi($values, $person) {
        $db = DBInit::getInstance();
        
        switch ($person) {
            case "admin":
                $statement = $db->prepare("UPDATE admin SET ime = :ime, priimek = :priimek, email = :email, geslo = :geslo WHERE id = :id");
                break;
            case "prodajalec":
                $statement = $db->prepare("UPDATE prodajalec SET ime = :ime, priimek = :priimek, email = :email, geslo = :geslo, is_active = :is_active WHERE id = :id");
                $statement->bindValue(':is_active',  $values["is_active"]);

                break;
            case "stranka":
                $statement = $db->prepare("UPDATE stranka SET ime = :ime, priimek = :priimek, email = :email, geslo = :geslo, ulica = :ulica, hisna_stevilka = :hisna_stevilka, posta = :posta, postna_stevilka = :postna_stevilka, is_active = :is_active WHERE id = :id");
                $statement->bindParam(":ulica", $values["ulica"]);        
                $statement->bindParam(":hisna_stevilka", $values["hisna_stevilka"]);
                $statement->bindParam(":posta",  $values["posta"]);
                $statement->bindValue(':postna_stevilka',  $values["postna_stevilka"]);                
                $statement->bindValue(':is_active',  $values["is_active"]);

                break;
             case "instrument":
                $statement = $db->prepare("UPDATE instrument SET ime = :ime, company = :company, cena = :cena, opis = :opis, is_active = :is_active WHERE id = :id");
                $statement->bindParam(":ime", $values["ime"]);        
                $statement->bindParam(":company", $values["company"]);
                $statement->bindParam(":cena",  $values["cena"]);
                $statement->bindValue(':opis',  $values["opis"]);
                $statement->bindValue(':is_active',  $values["is_active"]);

      
                break;
        }
        if($person != "instrument") {
            $statement->bindParam(":ime", $values["ime"]);        
            $statement->bindParam(":priimek", $values["priimek"]);
            $statement->bindParam(":email",  $values["email"]);
            $statement->bindValue(':geslo',  $values["geslo"]);
            
        }
            $statement->bindValue(':id',  $values["id"]);
       
        $statement->execute();
    }
    
    
    public static function izbrisi($id, $person) {
        $db = DBInit::getInstance();
         switch ($person) {
            case "admin":
                $statement = $db->prepare("DELETE FROM admin WHERE id = :id");
                break;
            case "prodajalec":
                $statement = $db->prepare("DELETE FROM prodajalec  WHERE id = :id");
                break;
            case "stranka":
                $statement = $db->prepare("DELETE FROM stranka WHERE id = :id");
                break;
            case "instrument":
                $statement = $db->prepare("DELETE FROM instrument WHERE id = :id");
                break;
        }
        $statement->bindParam(":id", $id);
        $statement->execute();
    }
    
    public static function dodaj($values, $person) {
        $db = DBInit::getInstance();
          switch ($person) {
            case "prodajalec":
                $statement = $db->prepare("INSERT INTO prodajalec (ime, priimek, email, geslo) VALUES (:ime, :priimek, :email, :geslo)");

                break;
            case "stranka":
                $statement = $db->prepare("INSERT INTO stranka (ime, priimek, email, geslo, ulica, hisna_stevilka, posta, postna_stevilka) VALUES (:ime, :priimek, :email, :geslo, :ulica, :hisna_stevilka, :posta, :postna_stevilka)");
                $statement->bindParam(":ulica", $values["ulica"]);        
                $statement->bindParam(":hisna_stevilka", $values["hisna_stevilka"]);
                $statement->bindParam(":posta",  $values["posta"]);
                $statement->bindValue(':postna_stevilka',  $values["postna_stevilka"]);
                break;
            case "instrument":
                $statement = $db->prepare("INSERT INTO instrument (ime, company, cena, opis, is_active) VALUES (:ime, :company, :cena, :opis, 1)");
                $statement->bindParam(":ime", $values["ime"]);        
                $statement->bindParam(":company", $values["company"]);
                $statement->bindParam(":cena",  $values["cena"]);
                $statement->bindValue(':opis',  $values["opis"]);
       
                break;
            
        }
        if($person != "instrument")  {
            $statement->bindParam(":ime", $values["ime"]);        
            $statement->bindParam(":priimek", $values["priimek"]);
            $statement->bindParam(":email",  $values["email"]);
            $statement->bindValue(':geslo',  $values["geslo"]);
        }
            $statement->execute();
        
    }
    
    public static function dodajNarocilo($narocilo_st, $stranka_id, $instrument_id, $kolicina, $status) {
        $db = DBInit::getInstance();
        $statement = $db->prepare("INSERT INTO narocilo (narocilo_st, stranka_id, instrument_id, kolicina, status) VALUES (:narocilo_st, :stranka_id, :instrument_id, :kolicina, :status)");
        
        $statement->bindParam(":narocilo_st", $narocilo_st);
            $statement->bindParam(":stranka_id", $stranka_id);        
            $statement->bindParam(":instrument_id", $instrument_id);
            $statement->bindParam(":kolicina",  $kolicina);
            $statement->bindValue(':status',  $status);
        
            $statement->execute();
    }
    
    public static function returnLastId() {
        $db = DBInit::getInstance();
        $statement = $db->prepare("SELECT * FROM narocilo ORDER BY ID DESC LIMIT 1"); 
            $statement->execute();
        $lastRow = $statement->fetch(PDO::FETCH_ASSOC);
        $element = array_values(array_slice($lastRow, 1))[0];
        return $element;
    }
}
