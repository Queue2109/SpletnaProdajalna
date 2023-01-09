  <?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Container/Fieldset.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/Textarea.php';
require_once 'HTML/QuickForm2/Element/InputCheckbox.php';

abstract class InstrumentForm extends HTML_QuickForm2 {

    public $ime;
    public $company;
    public $cena;
    public $opis;
    public $button;
    public $is_active;

    public function __construct($id) {
        parent::__construct($id);

        $this->ime = new HTML_QuickForm2_Element_InputText('ime');
        $this->ime->setAttribute('size', 70);
        $this->ime->setLabel('Ime instrumenta:');
        $this->ime->addRule('required', 'Zahtevano ime.');
        $this->ime->addRule('maxlength', 'Should be shorter than 255 characters.', 255);
        $this->addElement($this->ime);

        $this->company = new HTML_QuickForm2_Element_InputText('company');
        $this->company->setAttribute('size', 70);
        $this->company->setLabel('Proizvajalec');
        $this->company->addRule('required', 'Napisite ime proizvajalca');
        $this->company->addRule('regex', 'Letters only.', '/^[a-zA-ZščćžŠČĆŽ ]+$/');
        $this->addElement($this->company);

        $this->cena = new HTML_QuickForm2_Element_InputText('cena');
        $this->cena->setAttribute('size', 10);
        $this->cena->setLabel('Cena:');
        $this->cena->addRule('required', 'Price is mandatory.');
        $this->cena->addRule('callback', 'Price should be a valid number.', array(
            'callback' => 'filter_var',
            'arguments' => [FILTER_VALIDATE_FLOAT]
                )
        );
        $this->addElement($this->cena);

        $this->opis = new HTML_QuickForm2_Element_Textarea('opis');
        $this->opis->setAttribute('rows', 10);
        $this->opis->setAttribute('cols', 70);
        $this->opis->setLabel('Opis');
        $this->opis->addRule('required', 'Provide some text.');
        $this->addElement($this->opis);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }

}

class InstrumentInsertForm extends InstrumentForm {

    public function __construct($id) {
        parent::__construct($id);
        
        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->button->setAttribute('value', 'Dodaj instrument');
        $this->addElement($this->button);
    }

}

class InstrumentEditForm extends InstrumentForm {

    public $id;

    public function __construct($id) {
        parent::__construct($id);
        
        
        $this->is_active = new HTML_QuickForm2_Element_InputText('is_active');
        $this->is_active->setAttribute('size', 25);
        $this->is_active->setLabel('Aktiven:');
        $this->is_active->addRule('maxlength', 'Naziv naj bo krajši od 255 znakov.', 255);
        $this->addElement($this->is_active);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->button->setAttribute('value', 'Uredi instrument');
        $this->addElement($this->button);
    }

}