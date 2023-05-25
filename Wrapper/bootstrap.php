<?php

/*
    replacements:
        [:name], [:label], [:id], [:value], [:attributes], [:options], [:row], [:col], [:min], [:max], [:step]
    
    class overwrite:
        [:class-ovwr]
*/

return [
    'elements'=> //--- single elements
    [
    'text' =>
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
    </div>',
    'number' =>
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <input type="number" name="[:name]" id="[:id]" value="[:value]" min="[:min]" max="[:max]" step="[:step]" [:class-ovwr] class="form-control" [:attributes]>
    </div>',
    'password' =>
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <input type="password" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
    </div>',
    'date' => 
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <input type="date" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
    </div>',
    'datetime' => 
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <input type="datetime-local" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
    </div>',
    'textarea' =>
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <textarea name="[:name]" id="[:id]" rows="[:row]" cols="[:col]" [:class-ovwr] class="form-control" [:attributes]>[:value]</textarea>
    </div>',
    'submit' =>
    '<div class="mb-3">
        <input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] class="btn btn-primary" [:attributes]/>
    </div>',
    'button' =>
    '<div class="mb-3">
        <button name="[:name]" id="[:id]" type="button" [:class-ovwr] class="btn btn-primary">[:value]</button>
    </div>',
    'checkbox' =>
    '<div class="mb-3">
        <div class="form-check form-check-inline">
            <input [:class-ovwr] class="form-check-input" type="checkbox" name="[:name]" value="[:value]" id="[:id]" [:attributes]>
            <label class="form-check-label" for="[:id]">[:label]</label>
        </div>
    </div>',
    'radio' =>
    '<div class="mb-3">
        <div class="form-check form-check-inline">
            <input [:class-ovwr] class="form-check-input" type="radio" name="[:name]" value="[:value]" id="[:id]" [:attributes]>
            <label class="form-check-label" for="[:id]">[:label]</label>
        </div>
    </div>',
    'select' =>
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <select [:class-ovwr] class="form-select" name="[:name]" id="[:id]" [:attributes]>
            [:value]
        </select>
    </div>',
    'datalist' =>
        '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <input [:class-ovwr] class="form-control" name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" [:attributes]>
        <datalist id="list_[:id]">
            [:options]
        </datalist>
    </div>',
    'alert' =>
    '<div class="mb-3">
        <div class="text-danger mb-3">
            [:value]
    </div>',
    'message' =>
    '<div [:class-ovwr] class="alert alert-danger" role="alert [:attributes]">
        [:value]
    </div>',
    'file' =>
    '<div class="mb-3">
        <label for="[:name]" class="form-label">[:label]</label>
        <input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]>
    </div>',
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>'
    ],
    'element_parts' => //--- multiple elements
    [
        'submit_bar_header' =>
        '<div class="mb-3">'
        ,
        'submit_bar_element' =>
        '       <input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] class="btn btn-primary" [:attributes]/>'
        ,
        'submit_bar_footer' =>
        '   </div>
        </div>'
        ,
        'button_bar_header' =>
        '<div class="mb-3">'
        ,
        'button_bar_element' =>
            '<button name="[:name]" id="[:id]" type="button" [:class-ovwr] class="btn btn-primary">[:value]</button>'
        ,
        'button_bar_footer' =>
        '   </div>
        </div>'
        ,
        'grid_header'=>
        '<div class="mb-3">
            <label for="[:name]" class="form-label">[:label]</label>
            <table id="[:id]">'
        ,
        'grid_cell' =>
        '<td><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" [:attributes]><td>'
        ,
        'grid_footer'=>
        '   </table>
        </div>'
    ]
];
 