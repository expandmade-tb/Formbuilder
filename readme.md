# **Formbuilder**

A lightweihgt php form builder to quickly generate and validate html forms using Bootstrap 5. Other CSS frameworks can easily be adapted. Internationalization is supported and the library is indepentent from other PHP frameworks.

# 1. Properties

### <span style="color: green;">check_timer = 0</span>
This property determines the time, in seconds, between entering form data and submitting the form. If the given time has not been reached, the form validation will always return false, asuming it is a bot because a user cannot enter data in that short time. Setting this property to zero no check will be made.

### <span style="color: green;">use_session = false</span>
A CSRF check will automatially added to the form. If *use_session* is set to true, the csrf-token will be stored in a session, which means a session has to be available. By default a stateless token will be used.

# 2. Methods

Input generating methods do have generally the following parameters: *(string $name, string $label='', string $string='', string $value='')*

### <span style="color: green;">checkbox</span>
creates an input field of type checkbox

&mdash; <u>example:</u> *$form->checkbox('checkbox_field', 'do you agree');*

### <span style="color: green;">datalist</span>
creates an input field of type datalist

&mdash; <u>example:</u> *$form->datalist('datalist_field', 'select a value','list1,list2,list3');*

### <span style="color: green;">date</span>
creates an input field of type date

&mdash; <u>example:</u> *$form->date('datefield', 'date of birth');*

### <span style="color: green;">datetime</span>
creates an input field of type datetime-local

### <span style="color: green;">div_close</span>
closes a div

&mdash; <u>example:</u> *$form->div_close();*

### <span style="color: green;">div_open</span>
opens a new div

&mdash; <u>example:</u> *$form->div_open('class="container"');*

### <span style="color: green;">fieldset_close</span>
closes a previously opened fieldset

&mdash; <u>example:</u> *$form->fieldset_close();*

### <span style="color: green;">fieldset_open</span>
opens a fieldset

&mdash; <u>example:</u> *$form->fieldset_open('Address Data');*

### <span style="color: green;">file</span>
creates an input field of type file

&mdash; <u>example:</u> *$form->file('file_field', 'Upload file');*

### <span style="color: green;">html</span>
adds whatever html to the form

&mdash; <u>example:</u> *$form->html(`'<h2>This is heading 2</h2>'`);*

### <span style="color: green;">message</span>
adds an alerting message at the top of the form

&mdash; <u>example:</u> *$form->message('mail could not be sent');*

### <span style="color: green;">password</span>
creates an input field of type password

&mdash; <u>example:</u> *$form->password('pass_field', 'enter password');*

### <span style="color: green;">ok</span>
checks if a form has errors after a validation

&mdash; <u>example:</u> *if ( $form->ok() ) {//...do something here... }*

### <span style="color: green;">radio</span>
creates an input field of type radio button

&mdash;  <u>example:</u> *$form->radio('radio_field','radio_field1','select option 1');*

### <span style="color: green;">render</span>
returns the complete html for the form

&mdash;  <u>example:</u> *echo $form->render();*

### <span style="color: green;">reset</span>
reset (unsets) input $_POST, $_GET, $_FILES

&mdash; <u>example:</u> *$form->reset();*

### <span style="color: green;">rule</span>
adds a rule for later validation

&mdash; <u>example:</u> *$form->rule('phone', [$this, 'val_phone']); ( [usage](#Usage) )

### <span style="color: green;">select</span>
creates an input field of type select

&mdash; <u>example:</u> *$form->select('select_field', 'pls choose', 'one,two,three');*

### <span style="color: green;">set_prePOST</span>
sets the values of the form fields before they are posted. if there are post values, these values will be ignored

&mdash; <u>example:</u> *$form->set_prePOST($key_value_array);*

### <span style="color: green;">set_secrets</span>
sets the secret key and unique id for stateless csrf token

&mdash; <u>example:</u> *$form->set_secrets('f04ff06c3278ad1a66d43f3d2b17e6c5', '187e6b3e3bd8ecf78f95793cd3a72919');*

### <span style="color: green;">submit</span>
creates a submit button

&mdash; <u>example:</u> *$form->submit('submit');*

### <span style="color: green;">submit_bar</span>
creates a submit button bar

&mdash; <u>example:</u> *$form->submit_bar(['submit','cancel'],['submit','cancel']);*

### <span style="color: green;">submitted</span>
checks if the form was submitted

&mdash; <u>example:</u> *if ( $form->submitted() ) {//...do here what has to be done... }*

### <span style="color: green;">text</span>
creates an input field of type text

&mdash; <u>example:</u> *$form->text('text_field', 'enter something');*

### <span style="color: green;">textarea</span>
creates an input textarea

&mdash; <u>example:</u> *$form->textarea('txtarea_field', 'notes here');*

### <span style="color: green;">validate</span>
validates the form with given rules after the form was submitted. the return will either be an **array** with key => value pairs or **false** if csrf-check,honeypot or timer check failed.

&mdash; <u>example:</u> *$data = $form->validate('name,email,phone');*

# 3. Usage {#Usage}
### <span style="color: green;">3.1 Rules</span>
For the form validation callback rules can be defined for each field:

    $form->rule('phone', [$this, 'val_phone'] );

where the function does have alway one parameter and need to return a string:

    public function val_phone ($value) : string {
        $pattern = '/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/';
        $result = preg_match($pattern, $value);

        if ( $result === 1)
            return '';
        else
            return 'invalid_phone';
    }

There are a few already existing "builtin" rules:

 1. $form->rule('field', 'required' );
 2. $form->rule('field', 'numeric' );
 3. $form->rule('field', 'integer' );
 4. $form->rule('field', 'email' );
 5. $form->rule('field', 'date' );

### <span style="color: green;">3.2 The method parameter string</span>
All methods generating input field do have a parameter **string $string=''**. With this parameter you can pass attributes like *readonly, required* etc. These will be added to the attributes already predefined in the wrapper.**But !**, if you use a string like *class="container xyz"*, the original classes will be overwritten and you will have to add them manually.

### <span style="color: green;">3.3 Complete example</span>
This is a code snipet of a class presenting a simple contact form:

    public function process($data) {
        // ... do whatever need to be done
        return true;
    }

    public function val_phone ($value) {
        if ( empty($value) )
            return '';

        $pattern = '/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/';
        $result = preg_match($pattern, $value);

        if ( $result === 1)
            return '';
        else
            return 'invalid_phone';
    }

    public function contactform() {
        $form = new Formbuilder('contactform');

        if ( $form->submitted() ) {
            
            $form->rule('email', 'email')
                 ->rule('phone', [$this, 'val_phone']);

            $data = $form->validate('name,email,phone,message');

            if ( $data === false ) // caused by csrf check, honypot or timer check
                die('something went wrong');

            if ( $form->ok() ) {
                if ( $this->process($data) === true) 
                    $form->message('thank your for your mail', 'class="alert alert-success" ');
                else
                    $form->message('we couldnt send the mail');

                echo $form->render();
                return;
            }
        }

        $form->text('name','your name', 'required')
             ->text('email','your email', 'required')
             ->text('phone', 'your phone')
             ->textarea('message', 'your message')
             ->checkbox('agreement','I have read and agree to the privacy policy and terms of use', false, 'required')
             ->submit('submit');

        echo $form->render();
    }