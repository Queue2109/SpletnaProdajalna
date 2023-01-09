  <?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/InputPassword.php';
require_once 'HTML/QuickForm2/Element/InputCheckbox.php';


abstract class RegisterForm extends HTML_QuickForm2 {

    public $email;
    public $geslo;
    public $ime;
    public $priimek;
    public $button;
    public $ulica;
    public $hisna_stevilka;
    public $posta;
    public $postna_stevilka;
    public $is_active;

    public function __construct($id) {
        parent::__construct($id);
        
        $this->ime = new HTML_QuickForm2_Element_InputText('ime');
        $this->ime->setAttribute('size', 25);
        $this->ime->setLabel('Ime:');
        $this->ime->addRule('required', 'Vnesite ime.');
        $this->addElement($this->ime);
        
        $this->priimek = new HTML_QuickForm2_Element_InputText('priimek');
        $this->priimek->setAttribute('size', 25);
        $this->priimek->setLabel('Priimek:');
        $this->priimek->addRule('required', 'Vnesite priimek.');
        $this->addElement($this->priimek);

        $this->email = new HTML_QuickForm2_Element_InputText('email');
        $this->email->setAttribute('size', 25);
        $this->email->setLabel('Elektronski naslov:');
        $this->email->addRule('required', 'Vnesite elektronski naslov.');
        $this->email->addRule('callback', 'Vnesite veljaven elektronski naslov.', array(
            'callback' => 'filter_var',
            'arguments' => [FILTER_VALIDATE_EMAIL])
        );
        $this->addElement($this->email);

        $this->geslo = new HTML_QuickForm2_Element_InputPassword('geslo');
        $this->geslo->setLabel('Izberite geslo:');
        $this->geslo->setAttribute('size', 15);
        $this->geslo->addRule('required', 'Vnesite geslo.');
        $this->geslo->addRule('minlength', 'Geslo naj vsebuje vsaj 5 znakov.', 5);
     
        $this->addElement($this->geslo);
        
        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        
       
        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }
}



class AddUserForm extends RegisterForm {
    
     public function __construct($id) {
        parent::__construct($id);
        $this->addElement($this->button);

        $this->button->setAttribute('value', 'Registracija');   
    }
    
}

class StrankaForm extends RegisterForm {
    
     public function __construct($id) {
        parent::__construct($id);
          
        $this->ulica = new HTML_QuickForm2_Element_InputText('ulica');
        $this->ulica->setAttribute('size', 25);
        $this->ulica->setLabel('Ulica:');
        $this->ulica->addRule('required', 'Vnesi ime ulice.');
        $this->ulica->addRule('regex', 'Pri nazivu uporabite le črke.', '/^[a-zA-ZščćžŠČĆŽ ]+$/');
        $this->ulica->addRule('maxlength', 'Naziv naj bo krajši od 255 znakov.', 255);
        $this->addElement($this->ulica);
        
        $this->hisna_stevilka = new HTML_QuickForm2_Element_InputText('hisna_stevilka');
        $this->hisna_stevilka->setAttribute('size', 25);
        $this->hisna_stevilka->setLabel('Hisna stevilka:');
        $this->hisna_stevilka->addRule('required', 'Vnesi hisno stevilko.');
        $this->hisna_stevilka->addRule('maxlength', 'Naziv naj bo krajši od 255 znakov.', 255);
        $this->addElement($this->hisna_stevilka);
       
        $this->posta = new HTML_QuickForm2_Element_InputText('posta');
        $this->posta->setAttribute('size', 25);
        $this->posta->setLabel('Posta:');
        $this->posta->addRule('required', 'Vnesi naziv pošte.');
        $this->posta->addRule('regex', 'Pri nazivu uporabite le črke.', '/^[a-zA-ZščćžŠČĆŽ ]+$/');
        $this->posta->addRule('maxlength', 'Naziv naj bo krajši od 255 znakov.', 255);
        $this->addElement($this->posta);
      
        $this->postna_stevilka = new HTML_QuickForm2_Element_InputText('postna_stevilka');
        $this->postna_stevilka->setAttribute('size', 5);
        $this->postna_stevilka->setLabel('Poštna številka');
        $this->postna_stevilka->addRule('required', 'Vnesi številko pošte.');
        $this->postna_stevilka->addRule('regex', 'Le števila med 1000 in 9999.', '/^[1-9][0-9]{3}$/');
        $this->addElement($this->postna_stevilka);
        
       
        

        $this->button->setAttribute('value', 'Registracija');          
        $this->addElement($this->button); 
    }
    
}

class EditAdminForm extends RegisterForm {
    public $id;

    public function __construct($id) {
        parent::__construct($id);
        
         
        $this->button->setAttribute('value', 'Uredi svoje podatke');
        $this->addElement($this->button);
        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);
    }
    
}


class EditUserForm extends RegisterForm {
    public $id;

    public function __construct($id) {
        parent::__construct($id);
        
         
        $this->is_active = new HTML_QuickForm2_Element_InputText('is_active');
        $this->is_active->setAttribute('size', 25);
        $this->is_active->setLabel('Aktiven:');
        $this->is_active->addRule('maxlength', 'Naziv naj bo krajši od 255 znakov.', 255);
        $this->addElement($this->is_active);
        
        $this->button->setAttribute('value', 'Uredi svoje podatke');
        $this->addElement($this->button);
        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);
    }
    
}
class EditStrankaForm extends StrankaForm {
    public function __construct($id) {
        parent::__construct($id);
 
        $this->is_active = new HTML_QuickForm2_Element_InputText('is_active');
        $this->is_active->setAttribute('size', 25);
        $this->is_active->setLabel('Aktiven:');
        
        $this->is_active->addRule('maxlength', 'Naziv naj bo krajši od 255 znakov.', 255);
        $this->addElement($this->is_active);
        $this->button->setAttribute('value', 'Uredi svoje podatke');
        $this->addElement($this->button);
        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);
    }
}


class IzbrisiProdajalca extends HTML_QuickForm2 {
    
    public $id;

    public function __construct($id) {
        
        parent::__construct($id, "post", ["action" => BASE_URL . "admin/delete"]);

        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->button->setAttribute('value', 'Izbrisi prodajalca');
        
        $this->addElement($this->button);
    }
}


class IzbrisiStranko extends HTML_QuickForm2 {
    
    public $id;

    public function __construct($id) {
        
        parent::__construct($id, "post", ["action" => BASE_URL . "prodajalec/delete"]);

        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->button->setAttribute('value', 'Izbrisi stranko');
        
        $this->addElement($this->button);
    }
}

    