# **Formbuilder**

A lightweight php form builder to quickly generate and validate html forms using Bootstrap 5 or Pure CSS. Other CSS frameworks can easily be adapted. Internationalization is supported and the library is independent from other PHP frameworks.

___

# Properties

- [Methods](#methods)
- [Translation](#translation)
- [Skeleton](#skeleton)

___

## check_timer

```PHP
public int $check_timer = 0;
```

This property determines the time, in seconds, between entering form data and submitting the form. If the given time has not been reached, the form validation will always return false, assuming it is a bot because a user cannot enter data in that short time. Setting this property to zero no check will be made.

___

## date_format

```PHP
public string $date_format = 'Y-m-d';
```

This property defines the format of the date. The format will be used when validating a date.

___

## date_placeholder

```PHP
public string $date_placeholder = 'yyyy-mm-dd';
```

This property defines the placeholder to be shown when using a datetext type of input fild.

___

## time_format

```PHP
public string $time_format = 'H:i';
```

This property defines the format of the time. The format will be used when validating a time.

___

## time_placeholder

```PHP
public string $time_placeholder = 'hh:mm';
```

This property defines the placeholder to be shown when using a timetext type of input fild.

___

## use_session

```PHP
public bool $use_session = false;
```

A CSRF check will automatically added to the form. If *use_session* is set to true, the csrf-token will be stored in a session, which means a session has to be available. By default a stateless token will be used.

___

## warnings_on

```PHP
public bool $warnings_on = false;
```

Some methods are throwing warnings. This value can be set to true when you are testing / debugging.

___

# Methods

- [Properties](#properties)
- [Translation](#translation)  
- [Skeleton](#skeleton)

___

## Formbuilder constructor

```PHP
function __construct(string $form_id, array $args=[])
```

Creates a new instance of the formbuilder class. The following arguments in ***arg*** are valid:

| arg     | default     | description                |
|:------- |:----------- |:-------------------------- |
| action  | ''          | sets the form action       |
| string  | ''          | additional form attributes |
| method  | 'post'      | the form method to use     |
| wrapper | 'bootstrap' | which wrapper to use       |
| lang    | 'en'        | sets the language          |

*example:*

```PHP
    $form = new Formbuilder('demoform');
```

___

## add_lang

```PHP
public function add_lang (string $filename)
```

Adds a language translation file    

*example:*

```PHP
    $this->i18n_file = $path . "i18n/$lang.php";
    $this->lang = $lang;

    $form = new Formbuilder('aform', ['wrapper'=>'bootstrap-h-md', 'lang'=>$this->lang]);
    $form->add_lang($this->i18n_file);
```

___

## button

```PHP
public function button (string $name, string $value='', string $onclick='', string $type='button', string $string='' )
```

creates a button with either a href to the button:

*example:*

```PHP
$form->button('click_btn','click me','http:example.com');  
```

or a js event:

```PHP
$form->button('click_btn','click me','onclick="myfucntion();"');
```

___

## button_bar

```PHP
public function button_bar (array $names, array $values=[], array $onclicks=[], array $types=[], array $strings=[] )
```

Create a button bar. The parameters and functionality are the same as with button.

___

## checkbox

```PHP
public function checkbox (string $name, array $args=[] )
```

Adds an input field type checkbox to the form. The following arguments in ***arg*** are valid:

| arg     | description                              |
|:------- |:---------------------------------------- |
| label   | label text for the input field           |
| string  | additional field attributes              |
| checked | the input field is checked / not checked |
| id      | the input fields id                      |

*example:*

```PHP
    $form->checkbox('agreement', ['label'=>'i do agree','checked'=>true]);

    // finally after the validation
    $data = $form->validate('agreement,some_other_field');

    // you can check what was chosen
    if ( isset($data['agreement']) )
        // checkbox checked
    else
        // checkbox checked    
```

___

## datalist

```PHP
public function datalist (string $name,  $valuelist, array $args=[] )
```

Add an input field type datalist to the form. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->datalist('choose_from', '1,2,3,4,5', ['value'=>'4']);
```

or

```PHP
    $form->datalist('choose_from', ['1','2','3','4','5'], ['value'=>'4']);
```

___

## date

```PHP
public function date (string $name, array $args=[] )
```

Adds an input field type date to the form. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->date('date', ['value'=>date("Y-m-d")]);
```

___

## datetext

```PHP
public function datetext (string $name, array $args=[] )
```

Adds an input field type text to the form, but only valid dates can be entered. The date will be validated according to date_format property. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->datetext('date_field');
```

___

## datetime

```PHP
public function datetime (string $name, array $args=[] )
```

Adds an input field type datetime to the form. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->date('datetime');
```

___

## div_close

```PHP
public function div_close ()
```

Closes a div. Counterpart from the **div_open()** function.

___

## div_open

```PHP
public function div_open (string $string='')
```

Opens a div. Counterpart to the **div_close()** function.

*example:*

```PHP
    $form->div_open('class="input-group"');
```

___

## fieldset_close

```PHP
public function fieldset_close ()
```

Closes a fieldset. Counterpart to the **fieldset_open()** function.

___

## fieldset_open

```PHP
public function fieldset_open (string $legend='', string $string='')
```

Opens a fieldset. Counterpart to the **fieldset_close()** function.

*example:*

```PHP
    $form->fieldset_open('Bank Transfer Payment', 'id="fieldset_bank_transfer"');
```

___

## file

```PHP
public function file (string $name, array $args=[] )
```

Adds an input field type file to the form. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form = new Formbuilder('frm1', ['string'=>'enctype="multipart/form-data"']);
    $form->file('sl_image',['label'=>'Image:','string'=>'required']);
```

___

## grid

```PHP
public function grid ( string $name, array $args=[] )
```

Adds a table to the form - each cell is a text input field (keep in mind that the resulting data will be a multidimensional array). The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the grid fields values         |
| id     | the input fields id            |
| rows   | size / amount of rows          |
| cols   | size / amount of cols          |

*example:*

```PHP
    $form->grid('grid', ['label'=>'Grid', 'rows'=>3, 'cols'=>2] );

    $form->grid('mash',
    [
        'label'=>'',
        'rows'=>4,
        'cols'=>3,
        'value'=>[
            ['Row 1','b1','c1'],
            ['Row 2','b2','c2'],
            ['Row 3','b3','c3'],
            ['Row 4','b4','c4']
        ],
        'string'=>[
            ['readonly'],
            ['readonly'],
            ['readonly'],
            ['readonly'],
            ['readonly']
        ]
    ]);
```

__

## hidden

```PHP
public function hidden(string $name, array $args=[] )
```

Adds an input field type hidden to the form. The following arguments in ***arg*** are valid:

| arg    | description                 |
|:------ |:--------------------------- |
| string | additional field attributes |
| value  | the fields value            |
| id     | the fields id               |

*example:*

```PHP
    $form->hidden('em_booking_id', ['value'=>$booking_id]);
```

___

## html

```PHP
public function html (string $value )
```

Adds whatever html to the form. 

*example:*

```PHP
    $form->html('<img alt="present" src="'.$image.'" width=100" height="40"> ');
```

___

## lang

```PHP
public function lang (string $value) : string 
```

Checks if the passed value contains {} and treats the $value as a keyword to lookup in the translation script. If the keyword cannot be found the function will return the passed value, otherwise the translation. If there is nothing to lookup ( {} ) aremissing, the function will return the original passed value. This function is also used for labels/values, errors and messages.

*example:*

```PHP
    $form->lang('{thank_you}'); // this text will be looked up in the translation files
    $form->message('{thank_you}', 'class="alert alert-success" '); // will have the same text output
    $form->message('thank_you', 'class="alert alert-success" '); // the output will simply be 'thank you'
```

___

## message

```PHP
public function message (string $message, $string='')
```

Adds an alerting message at the top of the form.

*example:*

```PHP
    $form->message('Your mail has not been sent');
```

___

## number

```PHP
    public function number (string $name, array $args=[] )
```

Adds an input field type number to the form. The following arguments in ***arg*** are valid:

| arg    | description                                   |
|:------ |:--------------------------------------------- |
| label  | label text for the input field                |
| string | additional field attributes                   |
| value  | the input fields value                        |
| id     | the input fields id                           |
| min    | the input fields minimum value, default is 1  |
| max    | the input fields maximum value, default is 10 |
| step   | the input fields spinner steps, default is 1  |

*example:*

```PHP
    $form->number('total',['label'=>'No. of persons', 'value'=>1, 'min'=>1, 'max'=>5]);
```

___

## ok

```PHP
public function ok ( bool $reset=false ) : bool
```

Checks if a form has errors after a validation. 

*example:*

```PHP
    if ( $form->submitted() ) {
        $data = $form->validate('name,email,phone,message');

        if ( $data === false ) // caused by csrf check, honeypot or timer check
            $form->message('something went wrong');

        if ( $form->ok() ) {
            // do something with the data...
            $name = $data['name'];
            $email = $data['email'];

        }
    }
```

___

## password

```PHP
public function password (string $name, array $args=[] )
```

Adds an input field type password to the form. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->password('password',['label'=>'{password}','string'=>'required']);
```

___

## radio

```PHP
public function radio (string $name, string $label, array $args=[] )
```

Adds an input field type radio to the form. After submit the value of the label with be the value of the radio button chosen. The following arguments in ***arg*** are valid:

| arg     | description                              |
|:------- |:---------------------------------------- |
| label   | label text for the input field           |
| string  | additional field attributes              |
| checked | the input field is checked / not checked |
| id      | the input fields id                      |

*example:*

```PHP
    $form->radio('radio', 'choose a', ['checked'=>true]); // field name is always the same for a radio group -> radio
    $form->radio('radio', 'choose b');
    $form->radio('radio', 'radio c', ['value'=>'option_c']);

    if ( $form->submitted() ) {
        $data = $form->validate('tfield,radio'); // get contents of the radio group -> radio

        if ( $data === false ) // caused by csrf check, honeypot or timer check
            $form->message('something went wrong');

        if ( $form->ok() ) {
            if ( $data["radio"] == 'choose_b' ) // if no value given, the value is the converted value of the label !!!
                $radiob = true;

            if ( $data["radio"] == 'option_c' ) // with a value given, it will be returned the same if selected
                $radioc = true;

        }
    }
```

___

## render

```PHP
public function render () : string 
```

Renders the whole form and returns it as an html string.

___

## render_array

```PHP
public function render_array () : array 
```

Renders the whole form and returns it as an array of html string with the field names as key.

___

## reset

```PHP
public function reset ()
```

Resets (unsets) input $_POST, $_GET, $_FILES and defined form fields and rules

___

### rule

```PHP
 public function rule ( $rule, $name='')
```

Adds a rule to a field which will be later checked in the function **validate()**. There are a few internal rules already built in:

+ 'required'
+ 'numeric'
+ 'integer'
+ 'email'
+ 'date'
+ 'time'

others you will have to define on your owhn.

*example:*

```PHP
    public function val_phone ($value, $field) {
        if ( empty($value) )
            return '';

        $pattern = '/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/';
        $result = preg_match($pattern, $value);

        if ( $result === 1)
            return '';
        else 
            return 'invalid_phone';
    }

    $form->text('phone')->rule('phone', [$this, 'val_phone']); // callback to function val_phone
    $form->text('email')->rule('email'); // callback to an internal validation
    $form->rule('required', 'name'); // if not chained, the name of the field is mandatory !
```

___

## search

```PHP
public function search (string $name, array $args=[], $oninput='')
```

Creates an input field type search - with the oninput event a live search can be implemented. An example javascript is given in livesearch.js, you have to add your controller.

*example:*

```PHP
    $ajax_token = $this->token();
    $controller = "'/livesearch/$table'";
    $form->search('search', ['label'=>'Live Search'],"livesearchResults(this, '/livesearch/search', '$ajax_token')");
```

___

## select

```PHP
public function select (string $name, $valuelist, array $args=[] )
```

Creates an input field of type select. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->select('options','1,2,3,4,5,6', ['label'=>'Select Option']);
```

___

## set_prePOST

```PHP
public function set_prePOST(array $data)
```

Sets the values of form fields at once instead of setting each value.

*example:*

```PHP
    $reservation = $wpdb->get_row( // get a row from the database....
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}reservations where transaction_id='{$token}'") 
    );

    $form = new Formbuilder('event_info', ['wrapper'=>'bootstrap-h-md', 'lang'=>$this->lang]);
    $form->set_prePOST($reservation); // ... and post the values into the form fields
    $form->text('event');
    $form->text('name');
    $form->text('phone');
    $form->text('email');
```

___

## set_secrets

```PHP
public function set_secrets(string $secret, string $uid)
```

Sets the secret key and unique id for stateless **csrf token**. Obviously you should set the secret in every controller if you are using stateless **csrf-tokens**.

___

## submit

```PHP
public function submit (string $name, string $value='', string $string='')
```

Creates a form input field of type submit.

___

## submit_bar

```PHP
public function submit_bar (array $names, array $values=[], array $strings=[])
```

Creates form input fields of type submit as a bar.

*example:*

```PHP
    $form->submit_bar(['ok','cancel']);
```

____

## submitted

```PHP
public function submitted () : bool
```

Checks if the form was submitted, which means we can start to process the form with validation etc.

*example:*

```PHP
    $form = new Formbuilder('demo');
    $form->text('name');
    // and so on....just define the rest of the form

    if ( $form->submitted() ) { //can we start to process the form ?
        $data = $form->validate('name,email,phone,message');

        if ( $data === false ) // caused by csrf check, honeypot or timer check
            $form->message('something went wrong');

        if ( $form->ok() ) {
            // do something with the data...
            $name = $data['name'];
            $email = $data['email'];

        }
    }
```

___

## text

```PHP
public function text (string $name, array $args=[]
```

Creats a form input field of type text. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->text('name',['label'=>'your name here'])->rule('required');
```

___

## textarea

```PHP
public function textarea (string $name, array $args=[] )
```

Creates a form input field of type textarea. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |
| rows   | size / amount of rows          |
| cols   | size / amount of cols          |

*example:*

```PHP
    $form->textarea('message', ['label'=>'Leave us a message']);
```

___

## timetext

```PHP
public function timetext (string $name, array $args=[] )
```

Adds an input field type text to the form, but only valid times can be entered. The time will be validated according to time_format property. The following arguments in ***arg*** are valid:

| arg    | description                    |
|:------ |:------------------------------ |
| label  | label text for the input field |
| string | additional field attributes    |
| value  | the input fields value         |
| id     | the input fields id            |

*example:*

```PHP
    $form->timetext('texttime_field');
```

___

## validate

```PHP
public function validate ($field_list) 
```

Validates the whole form based on the defined rules. The field list can either be a comma separated sting or an array. Only the fields passed into the function will be validated, sanitized and returned as an **array**. The function will return **false** if the built-in csrf-check failed, the honeypot was triggered or the check timer didnt pass.

*example:*

```PHP
    $form = new Formbuilder('demo');
    $form->text('name');
    $form->text('email');
    $form->text('phone');
    $form->text('message');
    $form->text('forgotten_field'); 
    // and so on....just define the rest of the form

    if ( $form->submitted() ) { //can we start to process the form ?
        $data = $form->validate('name,email,phone,message'); // !!! the field forgotten_field will not validate and returned !!!

        if ( $data === false ) // caused by csrf check, honypot or timer check
            $form->message('something went wrong');

        if ( $form->ok() ) {
            // do something with the data...
            $name = $data['name'];
            $email = $data['email'];

        }
    }
```

___

# Translation

- [Properties](#properties)
- [Methods](#methods)
- [Skeleton](#skeleton)

___

To define a language to be used for all translations, pass the lang parameter during form creation. The default language is 'en'.

```PHP
    // setup this somewhere
    $lang = 'es'; // or however you determin the language
    $path = plugin_dir_path(__DIR__); // example for wordpress 
    $this->i18n_file = $path . "i18n/$lang.php"; // a subdirectory of the plugin path

    $form = new Formbuilder('demo', ['wrapper'=>'bootstrap-h-md', 'lang'=>$this->lang]); // then create your instance
    $form->add_lang($this->i18n_file); // finally add your translation file
```

How does the translation file look like:

```PHP
<?php
return [
    'name_complete' => 'Name complete',
    'email' => 'eMail',
    'phone' => 'Phone',
    'authorization' => 'Deposit authorization code',
    'persons' => 'No of persons',
    'warning' => 'something went wrong',
    'submit' => 'Submit this form'
];
```

Save it under your choosen path and name it **en.php** and your done. Do the same with spanish **es.php** etc. Now you can use the translation as you wish.

```PHP
    $form->text('name', ['label'=>'{name_complete}']);
    $form->message('{warning}');
    $auth_text = $form->lang('{authorization}');
    $form->submit('submit','{submit}');
```

# Skeleton

- [Properties](#properties)
- [Methods](#methods)
- [Translation](#translation)  

___

Just a copy and paste skeleton.

```PHP
    $form = new Formbuilder('form', ['string'=>'enctype="multipart/form-data"', 'wrapper'=>'bootstrap-h-md']);
    $form->text('field1', ['label'=>'field1 label:'])->rule('required');
    $form->fieldset_open('Your choices:');
    $form->radio('radio', 'option 1:', ['checked'=>true, 'value'=>'option1']);
    $form->radio('radio', 'option 2:', ['value'=>'option2']);
    $form->fieldset_close();
    $form->checkbox('checkbox1',['label'=>'checkbox', 'checked'=>true]);
    $form->submit('submit','submit');

    if ( $form->submitted() ) {
        $data = $form->validate('field1');

        if ( $data === false )
            $form->message('something went wrong');

        if ( $form->ok() ) {
                $form->reset()->message('all done', 'class="alert alert-success" ');
                // do something with $data here
        }

        echo $form->render();
    }
```
