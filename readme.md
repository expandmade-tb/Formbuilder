# **Formbuilder**

A lightweight PHP form builder for quickly generating and validating HTML forms. Supports Bootstrap 5, Pure CSS, and is easily adaptable to other CSS frameworks. Includes built-in internationalization and works independently of any PHP frameworks.

---

# Table of Contents
- [Properties](#properties)
- [Constructor](#formbuilder-constructor)
- [Methods](#methods)
  - [Form Structure](#form-structure)
  - [Input Fields](#input-fields)
  - [Selectors](#selectors)
  - [Other Fields](#other-fields)
  - [Security](#security)
  - [Validation](#validation)
  - [Helpers](#helpers)
- [Built-in Validation Rules](#built-in-validation-rules)
- [Translation](#translation)
- [Skeleton](#skeleton)
- [Example](#example)

---

# Quickstart Example

```php
$form = new Formbuilder('my_form', [
    'method' => 'post',
    'action' => '/submit',
    'wrapper' => 'bootstrap-h',
    'lang' => 'en'
]);

$form->text('name', ['label' => 'Your Name'])->rule('required');
$form->submit('submit', 'Send');

if ($form->submitted()) {
    $data = $form->validate('name');
    if ($data) {
        echo "Thanks, " . htmlspecialchars($data['name']);
    }
}

echo $form->get_html();
```

---

# Properties

```php
public int $check_timer = 0;        // Anti-bot timer (in seconds)
public string $date_format = 'Y-m-d';
public string $date_placeholder = 'yyyy-mm-dd';
public string $time_format = 'H:i';
public string $time_placeholder = 'hh:mm';
public bool $use_session = false;   // Enables CSRF protection
public bool $warnings_on = false;   // Enables warnings for missing labels/IDs
```

---

# Formbuilder Constructor

```php
function __construct(string $form_id, ...$args)
```

| Argument    | Default                      | Description                                       |
|-------------|------------------------------|---------------------------------------------------|
| `action`    | `$_SERVER['REQUEST_URI']`    | URL where the form is submitted.                 |
| `string`    | `''`                         | Additional HTML attributes for the form.         |
| `method`    | `'post'`                     | HTTP method (e.g., `'post'`, `'get'`).           |
| `wrapper`   | `'bootstrap-v'`              | CSS layout (e.g., `bootstrap-v`, `pure`, etc.).  |
| `lang`      | `'en'`                       | Language code for translations.                  |
| `controller`| `'clientRequests'`           | Controller used for Ajax callbacks.              |

---

# Methods

## Form Structure
- `add_class`
- `fieldset_open`
- `fieldset_close`
- `open_row`
- `close_row`
- `layout_grid`

## Input Fields
- `text`
- `search`
- `email`
- `tel`
- `url`
- `password`
- `number`
- `date`
- `datetime`
- `datetext`
- `timetext`

## Selectors
- `checkbox`
- `radio`
- `select`
- `datalist`

## Other Fields
- `textarea`
- `file`
- `grid`

## Security
- `set_secrets`
- `csrf_field`
- `honeypot_field`
- `timer_field`

## Validation
- `rule`
- `validate`
- `has_errors`
- `get_errors`
- `ok`

## Helpers
- `message`
- `get_message`
- `when`
- `set_prePOST`
- `submitted`
- `get_html`

---

# Built-in Validation Rules

Use with the `rule()` method by passing the rule name as a string:

- `'val_numeric'`: Must be numeric.
- `'val_integer'`: Must be an integer.
- `'val_empty'`: Must not be empty.
- `'val_checked'`: Checkbox must be checked.
- `'val_date'`: Must match `date_format`.
- `'val_time'`: Must match `time_format`.
- `'val_email'`: Must be a valid email address.

**Example:**
```php
$form->text('age', ['label' => 'Your Age'])->rule('val_integer', 'Please enter a valid integer.');
$form->email('contact_email', ['label' => 'Email'])->rule('val_email', 'Invalid email format.');
```

---

# Translation

Internationalization is supported via PHP array-based language files.

**Example `lang/en.php`:**
```php
return [
    'name_complete' => 'Your full name',
    'authorization' => 'Authorization code',
    'persons' => 'No of persons',
    'warning' => 'Something went wrong',
    'submit' => 'Submit this form'
];
```

Use translated strings with curly braces:

```php
$form = new Formbuilder('my_form', ['lang' => 'en']);
$form->text('name', ['label'=>'{name_complete}']);
$form->message('{warning}');
$auth_text = $form->lang('{authorization}');
$form->submit('submit', '{submit}');
```

---

# Skeleton

```php
$form = new Formbuilder('form', ['string'=>'enctype="multipart/form-data"', 'wrapper'=>'bootstrap-h-md']);
$form->text('field1', ['label'=>'field1 label:'])->rule('required');
$form->fieldset_open('Your choices:');
$form->radio('radio', 'option 1:', ['checked'=>true, 'value'=>'option1']);
$form->radio('radio', 'option 2:', ['value'=>'option2']);
$form->fieldset_close();
$form->checkbox('checkbox1', ['label'=>'checkbox', 'checked'=>true]);
$form->submit('submit','submit');

if ($form->submitted()) {
    $data = $form->validate('field1');

    if ($data === false)
        $form->message('Something went wrong');

    if ($form->has_errors())
        $form->message('Please correct the errors in the form.');

    if ($data) {
        echo "Form data:<pre>";
        print_r($data);
        echo "</pre>";
        $form->message('Form processed successfully!');
    }
}

echo $form->get_html();
```

---

# Example

```php
public function index () {
    $form = new Formbuilder('demoform', wrapper: 'bootstrap-inline');

    $form
        ->set_secrets('some secret passphrase', 'an application identifier')
        ->text('name')->rule('required')
        ->text('email')->rule('email')
        ->search('search', label: 'Album Title')
        ->text('artist')
        ->text('phone')
        ->password('password')
        ->date('date', value: date("Y-m-d"))
        ->datetime('date_time')
        ->datetext('datetext', datepicker: 'yyyy-mm-dd')
        ->timetext('timetext')
        ->textarea('message')
        ->fieldset_open('Your Choice')
        ->radio('radio', 'radio a', checked: true)
        ->radio('radio', 'radio b')
        ->fieldset_close()
        ->select('select_from', 'one,two,three')
        ->datalist('choose_from', 'a list,b list,c list', value: 'c list')
        ->file('file')
        ->number('amt', label: 'How many', value: 1)
        ->grid('grid', label: 'Field Grid', rows: 3, cols: 2)
        ->checkbox('agreement', label: 'I do agree', checked: true)
        ->submit('submit')
        ->layout_grid(['name,email','phone,password','date,date_time,datetext,timetext','search,artist','select_from,choose_from']);

    if ($form->submitted()) {
        $data = $form->validate('name,email,search,artist,phone,password,date,datetext,timetext,date_time,message,radio,select_from,choose_from,file,agreement,amt,grid,submit');

        if ($data === false)
            $form->message('Something went very wrong');

        if ($form->ok()) {
            $form->reset()->message('Form Submitted', 'class="alert alert-success"');
            $values = $data;
        }
    }

    $this->data['form'] = $form->render();
    $this->view('Form', $this->data);
}
```

---

# Requirements
- PHP 7.4 or higher
- JavaScript for optional enhancements (datepicker, event handlers)
- Public access to `/js` folder for frontend assets
- Controller and routes for any Ajax handlers (e.g., `AlbumOninput`, `AlbumOnchange`)

---

