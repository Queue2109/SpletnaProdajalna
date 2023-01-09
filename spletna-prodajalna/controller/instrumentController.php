<?php

require_once("database_spletna_prodajalna.php");
require_once("ViewHelper.php");
require_once("forms/InstrumentForm.php");
require_once('controller/storeController.php');

class InstrumentController {

    public static function index() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);

        if (isset($data["id"])) {
            echo ViewHelper::render("view/instrumentDetail.php", [
                "instrument" => DBSpletnaProdajalna::get($data["id"], "instrument")
            ]);
        } else {
            if($_SESSION["user"] == "prodajalec") {
                echo ViewHelper::render("view/instruments.php", [
                    "instruments" => DBSpletnaProdajalna::getAll("instrument"),
                    "narocila" => StoreController::oblikujNarocila(),
                    "potrjenaNarocila" => DBSpletnaProdajalna::getOrdersByStatus("potrjeno")
                ]);
            } else {
                echo ViewHelper::render("view/instruments.php", [
                    "instruments" => DBSpletnaProdajalna::getAll("instrument")
                ]);
            }
            
        }
    }

    public static function add() {
        $form = new InstrumentInsertForm("add");

        if ($form->validate()) {
            DBSpletnaProdajalna::dodaj($form->getValue(), "instrument");
            ViewHelper::redirect(BASE_URL . "instruments");
        } else {
            echo ViewHelper::render("view/instrumentFormView.php", [
                "title" => "Dodaj instrument",
                "form" => $form
            ]);
        }
    }

    public static function edit() {
        $editForm = new InstrumentEditForm("edit_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                DBSpletnaProdajalna::uredi($data, "instrument");
                ViewHelper::redirect(BASE_URL . "instruments");
            } else {
                echo ViewHelper::render("view/instrumentFormView.php", [
                    "title" => "Uredi instrument",
                    "form" => $editForm
                ]);
            }
        } else {
            $rules = [
                "id" => [
                    
                    'filter' => FILTER_VALIDATE_INT,
                    'options' => ['min_range' => 1]
                ]
            ];

            $data = filter_input_array(INPUT_GET, $rules);

            if ($data["id"]) {
                $instrument = DBSpletnaProdajalna::get($data["id"], "instrument");
                $dataSource = new HTML_QuickForm2_DataSource_Array($instrument);
                $editForm->addDataSource($dataSource);

                echo ViewHelper::render("view/instrumentFormView.php", [
                    "title" => "Uredi instrument",
                    "form" => $editForm
                ]);
            } else {
                throw new InvalidArgumentException("editing nonexistent entry");
            }
        }
    }
}
