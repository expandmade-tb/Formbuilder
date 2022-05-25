<?php

namespace Formbuilder;

/**
 * Forms for Model View Controllers
 * Version 1.8.1
 * Author: expandmade / TB
 * Author URI: https://expandmade.com
 */

use Exception;
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

    /**
     * Method __construct
     *
     * @param string $form_id the form of the id
     * @param array $args one or more of the following arguments:
     * 
     *| arg       | default      | description |
     *|:----------|:-------------|:--------------------------|
     *| action    | ''           | sets the form action      |
     *| string    | ''           | additional form attributs |
     *| method    | 'post'       | the form method to use    |
     *| wrapper   | 'bootstrap'  | which wrapper to use      |
     *| lang      | 'en'         | sets the language         |
     *
     * @return void
     */
    function __construct(string $form_id, array $args) {
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
        if ( substr($name, 0, 1) == '*' || $this->get_field($name) === false )
            if ( $append )
                $this->fields[] = new Field($name, $element);
            else
                array_unshift($this->fields, new Field($name, $element));
        else
            throw new Exception("fields already exists: ".$name);
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
        unset($_POST['_token']);

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
     * built in standard validation rule 
     *
     * @param mixed $value checks if numeric
     *
     * @return string empty | error message
     */
    public function val_numeric ( $value ) : string {
        if (is_array($value) ) {
            foreach ($value as $key => $single_value) 
                if ( !is_numeric($single_value) ) 
                    return $this->get_i18n('val_numeric');
        }
        else
            if ( !is_numeric($value) ) 
                return $this->get_i18n('val_numeric');

        return  '';
    }

    /**
     * built in standard validation rule 
     *
     * @param mixed $value checks if not empty
     *
     * @return string empty | error message
     */
    public function val_empty ( $value ) : string {
        if (is_array($value) ) {
            foreach ($value as $key => $single_value) 
                if ( empty($single_value) ) 
                    return $this->get_i18n('val_empty');
        }
        else
            if ( empty($value) ) 
                return $this->get_i18n('val_empty');

        return  '';
    }
        
    /**
     * built in standard validation rule 
     *
     * @param mixed $value checks if integer
     *
     * @return string empty | error message
     */
    public function val_integer ( $value ) : string {
        if (is_array($value) ) {
            foreach ($value as $key => $single_value) 
                if ( !is_numeric($single_value) || intval($single_value) != $single_value )
                    return $this->get_i18n('val_integer');
        }
        else
            if ( !is_numeric($value) || intval($value) != $value )
                return $this->get_i18n('val_integer');
        
        return '';
    }

    /**
     * built in standard validation rule 
     *
     * @param mixed $value checks if date
     *
     * @return string empty | error message
     */
    public function val_date ( $value ) : string {
        if (is_array($value) ) {
            foreach ($value as $key => $single_value) 
                if ( strtotime($single_value) === false )
                    return $this->get_i18n('val_date');
        }
        else
            if ( strtotime($value) === false )
                return $this->get_i18n('val_date');

        return '';
    }
    
    /**
     * built in standard validation rule 
     *
     * @param mixed $value checks if email
     *
     * @return string empty | error message
     */
    public function val_email ($value) :string {
        if (is_array($value) ) {
            foreach ($value as $key => $single_value)
                if ( filter_var($value, FILTER_VALIDATE_EMAIL) === false )
                    return $this->get_i18n('val_email');
        }
        else 
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

        $element = Wrapper::elements('submit', $name, '', $name, $value, $string);
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
            $element = Wrapper::element_parts('submit_bar_element', $name, '', $name, $value, $string);
            $this->add_field($name, $element);
        }

        $element = Wrapper::element_parts('submit_bar_footer', '*submit_bar_footer');
        $this->add_field('*submit_bar_footer', $element);
        return $this;
    }
            
    /**
     * creates an input field type text
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param string $string additional attributes
     * @param string $value input fields value
     *
     * @return $this
     */
    public function text (string $name, string $label='', string $string='', string $value='') {
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('text', $name, $this->lang($label), $name, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type password
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param string $string additional attributes
     * @param string $value input fields value
     *
     * @return $this
     */
    public function password (string $name, string $label='', string $string='', string $value='') {
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('password', $name, $this->lang($label), $name, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }

    public function hidden(string $name, string $value='', $string='') {
        $element = Wrapper::elements('hidden', $name, '', $name, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }
    
    /**
     * creates an input field type date
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param string $string additional attributes
     * @param string $value input fields value
     *
     * @return $this
     */
    public function date (string $name, string $label='', string $string='', string $value='') {
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('date', $name, $this->lang($label), $name, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }
          
    /**
     * creates an input field type datetime
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param string $string additional attributes
     * @param string $value input fields value
     *
     * @return $this
     */
    public function datetime (string $name, string $label='', string $string='', string $value='') {
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('datetime', $name, $this->lang($label), $name, $value, $string);
        $this->add_field($name, $element);
        return $this;
    }
          
    /**
     * creates an input field type checkbox
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param bool $checked checkbox checked | unchecked
     * @param string $string additional attributes
     *
     * @return $this
     */
    public function checkbox (string $name, string $label='', bool $checked=false, string $string='') {
        $post = $this->post($name);
        $value = '';

        if ( !$this->submitted() )
            if ( $checked )
                $value = 'checked';
            else
                $value = '';
        else
            if ( $value === $post )
                $value = 'checked';
            
        $element = Wrapper::elements('checkbox', $name, $this->lang($label), $name, $value, $string.$value);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type radio
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param bool $checked radio checked | unchecked
     * @param string $string additional attributes
     *
     * @return $this
     */
    public function radio (string $name, string $id, string $value, string $label='',bool $checked=false, string $string='') {
        $post = $this->post($name);

        if ( $checked )
            $checked = 'checked';
        else
            $checked = '';

        $element = Wrapper::elements('radio', $name, $this->lang($label), $id, $value, $string.' '.$checked);
        $this->add_field($name, $element);
        return $this;
    }
    
    /**
     * creates an input field type select
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param string $valuelist the fields optional values spararated by ','
     * @param string $value input fields value
     * @param string $string additional attributes
     *
     * @return $this
     */
    public function select (string $name, string $label='',string $valuelist='' , string $value='', string $string='')  {
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

        $element = Wrapper::elements('select', $name, $this->lang($label), $name, $opt, $string);
        $this->add_field($name, $element);
        return $this;
    }

    /**
     * creates an input field type datalist
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param string $valuelist the fields optional values spararated by ','
     * @param string $value input fields value
     * @param string $string additional attributes
     *
     * @return $this
     */
    public function datalist (string $name, string $label='', string $valuelist='' , string $value='', string $string='')  {
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;
            
        $arr_value = explode(',', $valuelist);
        $opt = '';

        foreach ($arr_value as $key => $option) 
            $opt .= '<option value="'.$option.'">';

        $element = Wrapper::elements('datalist', $name, $this->lang($label), $name, $value, $string, $opt);
        $this->add_field($name, $element);
        return $this;
    }
    
    /**
     * creates an input field type textarea
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param int $rows size / amount of rows 
     * @param int $cols size / amount of cols
     * @param string $string additional attributes
     * @param string $value input fields value
     *
     * @return $this
     */
    public function textarea (string $name, string $label, int $rows=2, int $cols=40, string $string='', string $value='') {
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('textarea', $name, $this->lang($label), $name, $value, $string, '', $rows, $cols);
        $this->add_field($name, $element);
        return $this;
    }
        
    /**
     * creates an input field type file
     *
     * @param string $name the input fields name
     * @param string $label label text for the input field
     * @param string $string additional attributes
     * @param string $value input fields value
     *
     * @return $this
     */
    public function file (string $name, string $label='', string $string='', string $value='') {
        $post = $this->post($name);

        if ( $post != null) 
            $value = $post;

        $element = Wrapper::elements('file', $name, $this->lang($label), $name, $value, $string);
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
     * @param string $name the name of the field to validate
     * @param callable|string  $rule either one of the built-in validations by its name or a callback function
     * 
     * @return $this
     */
    public function rule ( string $name, $rule) {
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
     * reset input $_POST, $_GET, $_FILES
     *
     * @return $this
     */
    public function reset () {
        unset($_POST);
        unset($_GET);
        unset($_FILES);

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

        foreach ($fields as $field) {
            $value = $this->post($field);

            if ( $value === null )
                $result[$field] = null;
            else
                $result[$field] = filter_var(strip_tags($value),FILTER_SANITIZE_SPECIAL_CHARS);
            
            $ruleset = $this->get_rule($field);

            if ( !empty($ruleset) ) {
                foreach ($ruleset as $key => $rule) {
                    $err = call_user_func($rule, $value);

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
        $element = Wrapper::elements('message', 'msg', '', '', $message, $string);
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