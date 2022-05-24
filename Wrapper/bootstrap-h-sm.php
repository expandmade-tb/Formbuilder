<?php

// replacements: [:name]','[:label]','[:id]','[:value]','[:attributes]','[:options]','[:row]','[:col]
// class overwrite: [:class-ovwr]

return [
    'elements'=> //--- single elements
    [
    'text' =>
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
        </div>
    </div>',
    'password' =>
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <input type="password" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
        </div>
    </div>',
    'date' => 
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <input type="date" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
        </div>
    </div>',
    'datetime' => 
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <input type="datetime-local" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
        </div>
    </div>',
    'textarea' =>
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <textarea name="[:name]" id="[:id]" rows="[:row]" cols="[:col]" [:class-ovwr] class="form-control" [:attributes]>[:value]</textarea>
        </div>
    </div>',
    'submit' =>
    '<div class="form-group row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-4">
            <input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] class="btn btn-primary" [:attributes]/>
        </div>
    </div>',
    'checkbox' =>
    '<div class="form-group row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-4">
            <div class="form-check form-check-inline">
                <input [:class-ovwr] class="form-check-input" type="checkbox" name="[:name]" value="[:value]" id="[:id]" [:attributes]>
                <label class="form-check-label" for="[:id]">[:label]</label>
            </div>
         </div>
    </div>',
    'radio' =>
    '<div class="form-group row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-4">
            <div class="form-check form-check-inline">
                <input [:class-ovwr] class="form-check-input" type="radio" name="[:name]" value="[:value]" id="[:id]" [:attributes]>
                <label class="form-check-label" for="[:id]">[:label]</label>
            </div>
         </div>
    </div>',
    'select' =>
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <select [:class-ovwr] class="form-select" name="[:name]" id="[:id]" [:attributes]>
                [:value]
            </select>
        </div>
    </div>',
    'datalist' =>
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <input [:class-ovwr] class="form-control" name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" [:attributes]>
            <datalist id="list_[:id]">
                [:options]
            </datalist>
        </div>
    </div>',
    'alert' =>
    '<div class="form-group row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="text-danger col-sm-4">
            [:value]
        </div>
    </div>',
    'message' =>
    '<div [:class-ovwr] class="alert alert-danger" role="alert [:attributes]">
        [:value]
    </div>',
    'file' =>
    '<div class="form-group row">
        <label for="[:name]" class="col-sm-2 col-form-label">[:label]</label>
        <div class="col-sm-4">
            <input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
        </div>
    </div>',
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>'
    ],
    'element_parts' => //--- multiple elements
    [
        'submit_bar_header' =>
        '<div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-4">'
        ,
        'submit_bar_element' =>
        '       <input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] class="btn btn-primary" [:attributes]/>',
        'submit_bar_footer' =>
        '   </div>
        </div>'
    ]
];