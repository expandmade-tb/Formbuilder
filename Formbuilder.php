<?php

namespace Formbuilder;

/**
 * Forms for Model View Controllers
 * Version 2.3.1
 * Author: expandmade / TB
 * Author URI: https://expandmade.com
 */

use Formbuilder\Wrapper\Wrapper;
Use Formbuilder\StatelessCSRF;

/**
 * Field Supporting Class for Formbuilder
 */
class Field {
    public string $name;
    public string $element;

    function __construct(string $name, string $element) {
        $this->name = $name;
        $this->element = $element;
    }
}

/**
 * Rules Supporting Class for Formbuilder
 */
class Rule {
     public string $name;
     public $function;   

     function __construct(string $name, callable $function) {
        $this->name = $name;
        $this->function = $function;
    }
}

/**
 * Formbuilder Main Class
 */
class Formbuilder {
    private string $secret = '412D442A472D4B6150645367566B5970';
    private string $uid    = 'A57538782F413F4428472B4B62506553';
    protected string $form = '';
    protected array $fields = [];
    protected array $errors = [];
    protected string $success = '';
    protected array $rules = [];
    protected array $prePOST = [];
    protected string $form_id = '';
    protected array $i18n = [];
    public int $check_timer = 0;
    public bool $use_session = false;
    public bool $warnings_on = false;

    /**
     * Method __construct
     *
     * @param string $form_id the form of the id
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | default        | description 
     *|:----------|:---------------|:-----------------------------
     *| action    | = ''           | : sets the form action      
     *| string    | = ''           | : additional form attributs 
     *| method    | = 'post'       | : the form method to use    
     *| wrapper   | = 'bootstrap'  | : which wrapper to use      
     *| lang      | = 'en'         | : sets the language         
     *
     * @return void
     */
    function __construct(string $form_id, array $args=[]) {
        require(__DIR__.'/Wrapper/Wrapper.php');
        require(__DIR__.'/StatelessCSRF.php');

        $action = '';
        $string = '';
        $method = 'post';
        $wrapper = 'bootstrap';
        $lang = 'en';
        extract($args, EXTR_IF_EXISTS); // overwrite predefined vars

        $this->form_id = $form_id;
        $this->form='<form name="'.$form_id.'" id="'.$form_id.'" action="'.$action.'" method="'.$method.'" '.$string.' >';

        if ( !file_exists(__DIR__ . "/$lang.php") ) 
            $lang = 'en';

        $this->i18n = require(__DIR__ . "/i18n/$lang.php");

        Wrapper::factory($wrapper);
    }
    
    protected function add_field (string $name, string $element, bool $append=true) {
        if ( $append )
            $this->fields[] = new Field($name, $element);
        else
            if ( empty($this->fields) )
                $this->fields[] = new Field($name, $element);
            else
                array_unshift($this->fields, new Field($name, $element));
    }

    protected function get_field (string $name) {
        foreach ($this->fields as $key => $field) {
            if ( $field->name == $name )
                return $field;
        }

        return false;
    }

    protected function add_rule (string $name, callable $rule) {
        $this->rules[] = new Rule($name, $rule);
    }

    protected function get_rule (string $name) : array {
        $ruleset = [];

        foreach ($this->rules as $key => $rule) {
            if ( $rule->name == $name )
                $ruleset[] = $rule->function;
        }

        return $ruleset;
    }

    protected function get_i18n (string $key) : string {
        return $this->i18n[$key]??":$key:";
    }

    protected function error_msg (string $name, string $msg) {
        if ( !isset($this->errors[$name]) )
            $this->errors[$name] = $msg;

        return $this;
    }

    protected function csrf () : string {
        if ( $this->use_session ) {
            $token = bin2hex(random_bytes(16));
        }
        else {
            $csrf_generator = new StatelessCSRF($this->secret);
            $csrf_generator->setGlueData('ip', $_SERVER['REMOTE_ADDR']);
            $csrf_generator->setGlueData('user-agent', $_SERVER['HTTP_USER_AGENT']);            
            $token = $csrf_generator->getToken($this->uid, time() + 900); // valid for 15 mins.           
        }
        
        $element = '<input type="hidden" name="_token" id="csrf-token" value="'.$token.'" />';

        if ( $this->use_session )
            $_SESSION['csrf-token'] = $token;;     

        return $element;
    }
    
    protected function honeypot () : string {
        $element = '<input type="hidden" name="_hopo" id="hopo-token" value="" />';
        return $element;
    }
    
    protected function timer () : string {
        $time = dechex(time());
        $element = '<input type="hidden" name="_timer" id="timer-token" value="'.$time.'" />';
        return $element;
    }

    protected function inline_js() {
        $id = array_key_first($this->errors);
        return "<script>const element = document.getElementById('$id'); element.scrollIntoView(); </script>";
    }

    protected function beautify (string $name) : string {
        return ucwords(str_replace(['_', '-', '.'], ' ', $name));
    }
        
    /**
     * add a language translation file 
     *
     * @param string $filename  the language file
     *
     * @return $this
     */
    public function add_lang (string $filename) {
        $add_lang = require($filename);
        $this->i18n = array_merge($this->i18n, $add_lang);
        return $this;
    }

    /**
     * checks if the passed value contains {} and treats the $value as a keyword to lookup in the translation script.
     * if the keyword cannot be found the function will return the passed value, otherwise the translation.
     * If there is nothing to lookup, the function will return the original passed value.
     *
     * @param string $value the string to be analyzed and translated
     *
     * @return string either the translated keyword or {keyword} or original value
     */
    public function lang (string $value) : string {
        if ( empty($value) )
            return $value;

        if ( '{' !== $value[0] )
            return $value;

        if ( substr($value, -1) !== '}' ) 
            return $value;

        return $this->get_i18n(trim($value, '{}'), $value);
    }

    /**
     * sets the secret key and unique id for stateless csrf token
     *
     * @param string $secret the secret key 
     * @param string $uid the unique id
     *
     * @return $this
     */
    public function set_secrets(string $secret, string $uid) {
        $this->secret = $secret;
        $this->uid = $uid; 
        return $this;
    }

    /**
     * sets the value of the form fields before they are posted. if there are post values, these values will be ignored
     * instead of passing a value to every field an array with the field value pairs will do the same here
     *
     * @param array $data an array with field => value pairs
     *
     * @return $this
     */
    public function set_prePOST(array $data) {
        $this->prePOST = $data;
        return $this;
    }

    /**
     * built in standard validation rule for a single field
     *
     * @param mixed $value checks if numeric
     *
     * @return string empty | error message
     */
    public function val_numeric ( $value ) : string {
        if ( empty($value) )
            return '';

        if ( !is_numeric($value) ) 
            return $this->get_i18n('val_numeric');

        return  '';
    }

    /**
     * built in standard validation rule for a single field
     *
     * @param mixed $value checks if not empty
     *
     * @return string empty | error message
     */
    public function val_empty ( $value ) : string {
        if ( empty($value) ) 
            return $this->get_i18n('val_empty');

        return  '';
    }
        
    /**
     * built in standard validation rule for a single field 
     *
     * @param mixed $value checks if integer
     *
     * @return string empty | error message
     */
    public function val_integer ( $value ) : string {
        if ( empty($value) )
            return '';
            
        if ( !is_numeric($value) || intval($value) != $value )
            return $this->get_i18n('val_integer');
        
        return '';
    }

    /**
     * built in standard validation rule for a single field 
     *
     * @param mixed $value checks if date
     *
     * @return string empty | error message
     */
    public function val_date ( $value ) : string {
        if ( empty($value) )
            return '';
            
        if ( strtotime($value) === false )
            return $this->get_i18n('val_date');

        return '';
    }
    
    /**
     * built in standard validation rule for a single field 
     *
     * @param mixed $value checks if email
     *
     * @return string empty | error message
     */
    public function val_email ($value) :string {
        if ( empty($value) )
            return '';
            
        if ( filter_var($value, FILTER_VALIDATE_EMAIL) === false )
            return $this->get_i18n('val_email');

        return '';
    }

    /**
     * creates a submit button
     *
     * @param string $name button name
     * @param string $value button value
     * @param string $string additional attributes
     *
     * @return $this
     */
    public function submit (string $name, string $value='', string $string='') {
        if ( empty($value) )
            $value = $name;

        $element = Wrapper::elements('submit', $name, '', $name, $this->lang($value), $string);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates a submit button bar 
     *
     * @param array $names button names
     * @param array $values button values
     * @param array $strings additional attributes
     *
     * @return $this
     */
    public function submit_bar (array $names, array $values=[], array $strings=[]) {
        $element = Wrapper::element_parts('submit_bar_header', '*submit_bar_header');
        $this->add_field('_submit_bar_header', $element);

        foreach ($names as $key => $name) {
            $value = !empty($values[$key]) == true ? $values[$key] : $name;
            $string = !empty($strings[$key]) == true ? $strings[$key] : '';
            $element = Wrapper::element_parts('submit_bar_element', $name, '', $name, $this->lang($value), $string);
            $this->add_field($name, $element);
        }

        $element = Wrapper::element_parts('submit_bar_footer', '*submit_bar_footer');
        $this->add_field('*submit_bar_footer', $element);
        return $this;
    }
            
    /**
     * creates an input field type text
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      
     * 
     * @return $this
     */
    public function text (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $string = '';
        $value = '';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('text', $name, $this->lang($label), $id, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type number
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      
     *| min       | : the input fields minimum value, default is 1   
     *| max       | : the input fields maximum value, default is 10
     *| step      | : the input fields spinner steps, default is 1
     * 
     * @return $this
     */
    public function number (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $string = '';
        $value = '';
        $id = $name;
        $min = 1;
        $max = 10;
        $step = 1;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('number', $name, $this->lang($label), $id, $value, $string,'','','',$min,$max,$step);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type password
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      
     *
     * @return $this
     */
    public function password (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $string = '';
        $value = '';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('password', $name, $this->lang($label), $id, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type hidden
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      

     * @return $this
     */
    public function hidden(string $name, array $args=[] ) {
        $string = '';
        $value = '';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $element = Wrapper::elements('hidden', $name, '', $id, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }
    
    /**
     * creates an input field type date
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      

     * @return $this
     */
    public function date (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $string = '';
        $value = '';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('date', $name, $this->lang($label), $id, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }
          
    /**
     * creates an input field type datetime
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      
     *
     * @return $this
     */
    public function datetime (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $string = '';
        $value = '';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('datetime', $name, $this->lang($label), $id, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }
          
    /**
     * creates an input field type checkbox
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| checked   | : the input field is checked / not checked
     *| id        | : the input fields id      

     * @return $this
     */
    public function checkbox (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $checked=false;
        $string='';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $this->submitted() )
            $value = $post === null ? '' : 'checked';
        else {
            if ( array_key_exists($name, $this->prePOST) )
                $value = $post === null ? '' : 'checked';
            else
                if ( $checked )
                    $value = 'checked';
        }

        $element = Wrapper::elements('checkbox', $name, $this->lang($label), $id, $value, $value.' '.$string);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type radio
     *
     * @param string $name the input field name
     * @param string $label the input field label text
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| value     | : the label text for this radio button 
     *| string    | : additional field attributes
     *| checked   | : is the radio button checked / not checked
     *| id        | : the input fields id      
     *
     * @return $this
     */
    public function radio (string $name, string $label, array $args=[] ) {
        $checked=false;
        $value='';
        $id='';
        $string='';
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);
        $checked_val = '';

        if ( empty($id) )
            $id = preg_replace('/[^a-zA-Z0-9]+/', '_', str_replace(['{','}'],'', strip_tags($label)));

        if ( empty($value) )
            $value = $id;

        if ( $this->submitted() )
            $checked_val = $post != $value ? '' : 'checked';
        else {
            if ( array_key_exists($name, $this->prePOST) )
                $checked_val = $post != $value ? '' : 'checked';
            else
                if ( $checked )
                    $checked_val = 'checked';
        }

        $element = Wrapper::elements('radio', $name, $this->lang($label), $id, $value, $checked_val.' '.$string);
        $this->add_field($name, $element);
        return $this;
    }
    
    /**
     * creates an input field type select
     *
     * @param string $name the input field name
     * @param string $valuelist a list of comma separated values
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      

     * @return $this
     */
    public function select (string $name, string $valuelist, array $args=[] ) {
        $label = $this->beautify($name);
        $value='';
        $string='';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;
            
        $arr_value = explode(',', $valuelist);
        $opt = '';

        foreach ($arr_value as $key => $option)
            if ( $value == $option )
                $opt .= '<option value="'.$option.'"  selected>'.$option.'</option>';
            else
                $opt .= '<option value="'.$option.'">'.$option.'</option>';

        $element = Wrapper::elements('select', $name, $this->lang($label), $id, $opt, $string);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type datalist
     *
     * @param string $name the input field name
     * @param string $valuelist a list of comma separated values
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      

     * @return $this
     */
    public function datalist (string $name,  string $valuelist, array $args=[] ) {
        $label = $this->beautify($name);
        $value='';
        $string='';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;
            
        $arr_value = explode(',', $valuelist);
        $opt = '';

        foreach ($arr_value as $key => $option) 
            $opt .= '<option value="'.$option.'">';

        $element = Wrapper::elements('datalist', $name, $this->lang($label), $id, $value, $string, $opt);
        $this->add_field($name, $element);
        return $this;
    }
    
    /**
     * creates an input field type textarea
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      
     *| rows      | : size / amount of rows       
     *| cols      | : size / amount of cols      
     *
     * @return $this
     */
    public function textarea (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $rows=2;
        $cols=40;
        $string='';
        $value='';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('textarea', $name, $this->lang($label), $id, $value, $string, '', $rows, $cols);
        $this->add_field($name, $element);
        return $this;
    }
        
    /**
     * creates an input field type file
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the input fields value 
     *| id        | : the input fields id      
     * 
     * @return $this
     */
    public function file (string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $string='';
        $value='';
        $id = $name;
        extract($args, EXTR_IF_EXISTS);
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('file', $name, $this->lang($label), $id, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }
    
    /**
     * opens a div 
     *
     * @param string $string additional attributes
     *
     * @return $this
     */
    public function div_open (string $string='') {
        $this->add_field('*div_open', '<div '.$string.'>');
        return $this;
    }
    
    /**
     * closes a div previsously openend with div_open
     *
     * @return $this
     */
    public function div_close () {
        $this->add_field('*div_close', '</div>');
        return $this;
    }
        
    /**
     * opens a new fieldset
     *
     * @param string $legend the legend to be shown
     * @param string $string additional attributes
     *
     * @return $this
     */
    public function fieldset_open (string $legend='', string $string='') {
        $this->add_field('*fieldset_open', "<fieldset $string><legend>{$this->lang($legend)}</legend>");
        return $this;
    }
    
    /**
     * closes a previously opened fieldset
     *
     * @return $this
     */
    public function fieldset_close () {
        $this->add_field('*fieldset_close', "</fieldset>");
        return $this;
    }

    /**
     * adds a rule for later validation
     *
     * @return $this
     */
    public function rule ( $rule, $name='') {
        if ( empty($name) ) // if left empty we assume its the last added field (working on method chaining only ! )
            $name = end($this->fields)->name;
        else
            if ( $this->warnings_on && $this->get_field($name) === false )
                trigger_error("field $name unknown", E_USER_WARNING );

        switch ($rule) {
            case 'required':
                $this->add_rule($name, array($this,'val_empty'));
                break;
            case 'numeric':
                $this->add_rule($name, array($this,'val_numeric'));
                break;
            case 'integer':
                $this->add_rule($name, array($this,'val_integer'));
                break;
            case 'date':
                $this->add_rule($name, array($this,'val_date'));
                break;
            case 'email':
                $this->add_rule($name, array($this,'val_email'));
                break;
            default:
                $this->add_rule($name, $rule);
                break;
        }

        return $this;
    }
    
    /**
     * checks if the form was submitted
     *
     * @return bool
     */
    public function submitted () : bool {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
            return true;
        else
            return false;
    }
    
    /**
     * reset all of the form input $_POST, $_GET, $_FILES
     *
     * @return $this
     */
    public function reset () {
        unset($_POST);
        unset($_GET);
        unset($_FILES);
        unset($this->fields);
        unset($this->rules);

        return $this;
    }
    
    /**
     * checks if a form has errors after a validation
     *
     * @param bool $reset input will be reset after successfull validation
     *
     * @return bool
     */
    public function ok ( bool $reset=false ) : bool {
        if ( !empty($this->errors) )
            return false;

        if ( $reset ) {
            $this->reset();
        }

        return true;
    }
    
    /**
     * gets the post value of a field
     *
     * @param string $name fields name
     *
     * @return string the posted value
     */
    protected function post (string $name) {
        $post = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST[$name]) )
                $post = $_POST[$name];
            else
                if (isset($_GET[$name]) )
                    $post = $_GET[$name];
        }
        else {
           if (isset($this->prePOST[$name]) )
               $post = $this->prePOST[$name];
        }
            
        return $post;
    }
    
    /**
     * validates the form with given rules after the form was submitted
     *
     * @param string $field_list a list of comma separated fields to validate on given rules
     *
     * @return mixed returns an array of the fields and their values | false if csrf token or timer are 'invalid'
     */
    public function validate (string $field_list)  {
        
        if ( $this->use_session ) {
            if ( ( $_POST['_token'] != $_SESSION['csrf-token'] )) {
                $this->error_msg('_token', $this->get_i18n('val_csrf'));
                return false;
            } 
        }
        else {
            $csrf_generator = new StatelessCSRF($this->secret);
            $csrf_generator->setGlueData('ip', $_SERVER['REMOTE_ADDR']);
            $csrf_generator->setGlueData('user-agent', $_SERVER['HTTP_USER_AGENT']);            
            $result = $csrf_generator->validate($this->uid, $_POST['_token'], time());

            if ( $result === false)
                return false;
        }

        if ( !empty($_POST['_hopo'] )) {
            $this->error_msg('_hopo', $this->get_i18n('val_hopo'));
            return false;
        }

        if ( $this->check_timer ) {
            $posted = hexdec($_POST['_timer']??'');

            if ( ($posted + $this->check_timer) > time() ) {
                $this->error_msg('_timer', $this->get_i18n('val_timer'));
                return false;
            }
        }

        $result = array();
        $fields = explode(',',$field_list);

        if ( $this->warnings_on ) {
            $def_flds = [];

            foreach ($this->fields as $key => $field) {
                $name = $field->name;
                if ( substr($name,0, 1) != '*' ) $def_flds[] = $name;
            }
            
            $no_validates = implode(',', array_diff($def_flds, $fields));

            if ( !empty($no_validates) )
                trigger_error("fields [$no_validates] not validated", E_USER_WARNING );
        }


        foreach ($fields as $field) {
            if (  $this->warnings_on && $this->get_field($field) === false )
                trigger_error("field $field unknown", E_USER_WARNING );

            $value = $this->post($field);

            if ( $value === null )
                $result[$field] = null;
            else
                if ( is_array($value) )
                    foreach ($value as $row_key => $row_value) 
                        foreach ($row_value as $col_key => $col_value)
                            $result[$field][$row_key][$col_key] = filter_var(strip_tags($col_value),FILTER_SANITIZE_SPECIAL_CHARS);
                else
                    $result[$field] = filter_var(strip_tags($value),FILTER_SANITIZE_SPECIAL_CHARS);
            
            $ruleset = $this->get_rule($field);

            if ( !empty($ruleset) ) {
                foreach ($ruleset as $key => $rule) {
                    $err = $this->lang(call_user_func($rule, $value));

                    if ( !empty($err) ) {
                        $this->error_msg($field, $err);
                        break;
                    }
                }
            }
        }

        return $result;
    }
     
    /**
     * adds an alerting message at the top of the form
     *
     * @param string $message the message text
     * @param $string='' additional attributes
     *
     * @return $this
     */
    public function message (string $message, $string='') {
        $element = Wrapper::elements('message', 'msg', '', '', $this->lang($message), $string);
        $this->add_field('alert_message', $element, false);
        return $this;
    }
    
    /**
     * adds html to the form
     *
     * @param string $value the html string
     *
     * @return $this
     */
    public function html (string $value ) {
        $this->add_field('*html', $value);
        return $this;
    }
    
    /**
     * add an input grid
     *
     * @param string $name the input field name
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | description 
     *|:----------|:-----------------------------------------------
     *| label     | : label text for the input field 
     *| string    | : additional field attributes
     *| value     | : the grid fields values 
     *| id        | : the input fields id      
     *| rows      | : size / amount of rows       
     *| cols      | : size / amount of cols      
     *
     * @return $this
     */
    public function grid ( string $name, array $args=[] ) {
        $label = $this->beautify($name);
        $id = $name;
        $value=[];
        $string='';
        $rows = 2;
        $cols = 2;
        extract($args, EXTR_IF_EXISTS);

        $post = $this->post($name); 

        if ( $post != null) 
            $value = $post;

        $html = Wrapper::element_parts('grid_header', $name, $label, $id).PHP_EOL;

        for ($r=0; $r < $rows; $r++) { 
            $html .= '<tr>';
            
            for ($c=0; $c < $cols; $c++) { 
                $cell_name = $name . "[$r][$c]";
                $cell_id = $name . "-$r-$c";
                $cell_value = $value[$r][$c]??'';
                $html .= Wrapper::element_parts('grid_cell', $cell_name, $label, $cell_id, $cell_value, $string);
            }

            $html .= '</tr>'.PHP_EOL;
        }

        $html .= Wrapper::element_parts('grid_footer');
        $this->add_field($name, $html);
        return $this;
    }

    /**
     * returns the form ready for the view
     *
     * @return array
     */
    public function render () : string {
        $result = $this->form.PHP_EOL;
        $result .= $this->csrf().PHP_EOL;
        $result .= $this->honeypot().PHP_EOL;

        if ( $this->check_timer )
            $result .= $this->timer().PHP_EOL;

        foreach ($this->fields as $key => $field) {
            $result .= $field->element.PHP_EOL;

            if ( isset($this->errors[$field->name]) ) 
                $result .= Wrapper::elements('alert', $field->name, '', '', $this->errors[$field->name]); 
        }

        $result .= '</form>'.PHP_EOL;
       
        if ( !empty($this->errors) )
            $result .= $this->inline_js();
       
        return $result;
    }
}