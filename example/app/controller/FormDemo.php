<?php

namespace controller;

use Formbuilder\Button;
use Formbuilder\Formbuilder;

class FormDemo extends BaseController {
    function __construct() {
        parent::__construct();
    }

    public function index () : void {
    }
    
    public function ContactForm() : void {
        $lang = $_GET["lang"]??'en';
        $this->data['lang'] = $lang;
        $formname = 'Contact-Form';

        $btn = new Button();
        $this->data['switch_en'] = $btn->href('/contact?lang=en')->icon('en.webp')->class('btn btn-secondary')->button('English');
        $this->data['switch_es'] = $btn->href('/contact?lang=es')->icon('es.webp')->button('Español');
        $this->data['switch_de'] = $btn->href('/contact?lang=de')->icon('de.webp')->button('Deutsch');
        
        $form = new Formbuilder($formname,['wrapper'=>'bootstrap-inline', 'lang'=>$lang]);
        $form->add_lang(APP."/i18n/contactform-$lang.php");
        $form->text('contact_name', ['label'=>'{contact_name}'])->rule('required');
        $form->text('contact_mail', ['label'=>'{contact_mail}'])->rule('required')->rule('email');
        $form->text('contact_subject', ['label'=>'{contact_subject}']);
        $form->textarea('contact_message', ['label'=>'{contact_message}'])->rule('required');
        $form->checkbox('contact_consent', ['label'=>'{contact_consent}'])->rule('checked');
        $form->submit('contact_submit', '{contact_submit}');

        if ( $form->submitted() ) {
            $data = $form->validate('contact_name,contact_mail,contact_subject,contact_message,contact_consent,contact_submit');

            if ( $data === false )
                $form->reset()->message('{error}');
            else
                if ( $form->ok() )
                    $form->reset()->message('{thank_you}', 'class="alert alert-success" ');
        }

        $this->data['formname'] = $formname;
        $this->data['form'] = $form->render();
        $this->view('form');
    }

    public function MixedForm () : void {
        $formname = 'Mixed-Form';
        $form = new Formbuilder($formname, ['wrapper'=>'bootstrap-h-md']);
        $form->text('name')->rule('required');
        $form->text('email')->rule('email');
        $form->text('phone');
        $form->password('password');
        $form->date('date', ['value'=>date("Y-m-d")]);
        $form->datetime('date_time');
        $form->datetext('datetext');
        $form->timetext('timetext');
        $form->textarea('message');
        $form->fieldset_open('Your Choice');
        $form->radio('radio', 'radio a', ['checked'=>true]);
        $form->radio('radio', 'radio b');
        $form->fieldset_close();
        $form->select('select_from', 'one,two,three');
        $form->datalist('choose_from', 'a list,b list,c list', ['value'=>'c list']);
        $form->file('file');
        $form->number('amt',['label'=>'How many', 'value'=>1]);
        $form->grid('grid', ['label'=>'Field Grid', 'rows'=>3, 'cols'=>3] );
        $form->checkbox('agreement', ['label'=>'i do agree','checked'=>true]);
        $form->submit('submit');

        if ( $form->submitted() ) {
            $data = $form->validate('name,email,phone,password,date,datetext,timetext,date_time,message,radio,select_from,choose_from,file,agreement,amt,grid,submit');

            if ( $data === false ) // caused by csrf check, honypot or timer check
                $form->message('something went verry wrong');
            
            if ( $form->ok() ) {
                $form->reset()->message('form submitted', 'class="alert alert-success" ');
            }
        }

        $this->data['formname'] = $formname;
        $this->data['form'] = $form->render();
        $this->view('form');
    }
}