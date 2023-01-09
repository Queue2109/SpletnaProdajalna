<?php
require_once('forms/RegisterForm.php');
require_once('ViewHelper.php');
require_once('database_spletna_prodajalna.php');
require_once("index.php");

session_start();
        

class DBRegisterController {
    
    public static function register($person) {
        $form = new AddUserForm("register");
//        header('Location: ../certificate');
        if($person == "prodajalec") {
            $form = new StrankaForm("register");
        }
        if ($form->validate()) {
            $values =$form->getValue();
            switch ($person) {
                case "admin":
                    DBSpletnaProdajalna::dodaj($values, "prodajalec");
                     echo ViewHelper::render("admin/index.php", [
                        "form" => new AddUserForm("newRegister"),
                        "prodajalci" => DBSpletnaProdajalna::getAll("prodajalec")
                    ]);
                    break;
                case "prodajalec":
                    DBSpletnaProdajalna::dodaj($values, "stranka");
                        echo ViewHelper::render("prodajalec/index.php", [
                           "form" => new StrankaForm("newRegister"),
                           "stranke" => DBSpletnaProdajalna::getAll("stranka")
                       ]);
                    break;
                default :
                    echo "Ta nacin prijave ne obstaja";
                    break;
                    
            }
            
        } else {
             switch ($person) {
                case "admin":
                    echo ViewHelper::render("admin/index.php", [
                        "form" => $form,
                        "prodajalci" => DBSpletnaProdajalna::getAll("prodajalec")
                    ]);  
                    break;
                case "prodajalec":
                     echo ViewHelper::render("prodajalec/index.php", [
                        "form" => $form,
                        "stranke" => DBSpletnaProdajalna::getAll("stranka")
                    ]);  
                    break;
                default :
                    echo "Ta nacin prijave ne obstaja";
                    break;
                    
            }
               
        }
    }
    
    public static function index($person) {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);
        if($_SESSION["user"] != $person) {
            echo "ne morete dostopati do te strani";
            return;
        }
                
        
        switch ($person) {
            case "admin":
                if (isset($data["id"])) {
                    echo ViewHelper::render("view/oseba-detail.php", [
                        "oseba" => DBSpletnaProdajalna::getAll($data["id"], "prodajalec")
                    ]);
                } else {
                    self::register("admin");
                }
                
            break;
            case "prodajalec":
                if (isset($data["id"])) {
                    echo ViewHelper::render("view/oseba-detail.php", [
                        "oseba" => DBSpletnaProdajalna::getAll($data["id"], "stranka")
                    ]);
                } else {
                    self::register("prodajalec");
                }
                break;
        }
    }
    
     public static function edit($person) {
        $editForm = new EditUserForm("edit_form");
        $deleteForm = new IzbrisiProdajalca("delete_form");
        if($person == "stranka") {
            $editForm = new EditStrankaForm("edit_form");
            $deleteForm = new IzbrisiStranko("delete_form");
        }
        
        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                $_SESSION["userValues"] = $data;
                switch ($person) {
                    case "prodajalec":
                        DBSpletnaProdajalna::uredi($data, "prodajalec");
                        break;
                    case "stranka":
                        DBSpletnaProdajalna::uredi($data, "stranka");
                        break;
                }
            } 
                ViewHelper::redirect(BASE_URL . $_SESSION["user"]);
            
        } else {
            $rules = [
                "id" => [
                    'filter' => FILTER_VALIDATE_INT,
                    'options' => ['min_range' => 1]
                ]
            ];

            $data = filter_input_array(INPUT_GET, $rules);

            if ($data["id"]) {
                switch ($person) {
                    case "prodajalec":
                        $oseba = DBSpletnaProdajalna::get($data["id"], "prodajalec");
                        break;
                    case "stranka":
                        $oseba = DBSpletnaProdajalna::get($data["id"], "stranka");
                        break;
                }
                
                $dataSource = new HTML_QuickForm2_DataSource_Array($oseba);
                $editForm->addDataSource($dataSource);
                $deleteForm->addDataSource($dataSource);

                echo ViewHelper::render("view/osebaForm.php", [
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            } else {
                throw new InvalidArgumentException("editing nonexistent entry");
            }
        }
    }
    
     public static function editAdmin() {
        $editForm = new EditAdminForm("edit_form");
        
        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                $_SESSION["userValues"] = $data;

                DBSpletnaProdajalna::uredi($data, "admin");
            }
            ViewHelper::redirect(BASE_URL . "admin");

        } else {
            $rules = [
                "id" => [
                    'filter' => FILTER_VALIDATE_INT,
                    'options' => ['min_range' => 1]
                ]
            ];

            $data = filter_input_array(INPUT_GET, $rules);

            if ($data["id"]) {
                $oseba = DBSpletnaProdajalna::get($data["id"], "admin");

                $dataSource = new HTML_QuickForm2_DataSource_Array($oseba);
                $editForm->addDataSource($dataSource);

                echo ViewHelper::render("view/osebaForm.php", [
                    "form" => $editForm
                ]);
            } else {
                throw new InvalidArgumentException("editing nonexistent entry");
            }
        }
    }


     public static function delete($person) {
        $form = new IzbrisiProdajalca("delete_form");
        if($person == "stranka") {
            $form = new IzbrisiStranko("delete_form");
        }
        $data = $form->getValue();

        if ($form->isSubmitted() && $form->validate()) {
            switch ($person) {
                    case "prodajalec":
                        DBSpletnaProdajalna::izbrisi($data["id"], "prodajalec");
                        ViewHelper::redirect(BASE_URL . "admin");
                        break;
                    case "stranka":
                        DBSpletnaProdajalna::izbrisi($data["id"], "stranka");
                        ViewHelper::redirect(BASE_URL . "prodajalec");
                        break;
                }
        } else {
               switch ($person) {
                    case "prodajalec": 
                        if (isset($data["id"])) {
                            $url = BASE_URL . "admin/urediProdajalca?id=" . $data["id"];
                        } else {
                            $url = BASE_URL . "admin";
                        }
                        break;
                    case "stranka":
                        if (isset($data["id"])) {
                            $url = BASE_URL . "prodajalec/urediStranko?id=" . $data["id"];
                        } else {
                            $url = BASE_URL . "prodajalec";
                        }
                        break;
                }
           
            ViewHelper::redirect($url);
        }
    }
    
}