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
    '<div class="form-floating mb-3">
        <input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" placeholder="[:name]" [:attributes]>
        <label for="[:name]" >[:label]</label>
    </div>',
    'number' =>
    '<div class="form-floating mb-3">
        <input type="number" name="[:name]" id="[:id]" value="[:value]" min="[:min]" max="[:max]" step="[:step]" [:class-ovwr] class="form-control" placeholder="[:name]" [:attributes]>
        <label for="[:name]" >[:label]</label>
    </div>',
    'password' =>
    '<div class="form-floating mb-3">
        <input type="password" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" placeholder="[:name]" [:attributes]>
        <label for="[:name]" >[:label]</label>
    </div>',
    'date' => 
    '<div class="form-floating mb-3">
        <input type="date" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" placeholder="[:name]" [:attributes]>
        <label for="[:name]" >[:label]</label>
    </div>',
    'datetime' => 
    '<div class="form-floating mb-3">
        <input type="datetime-local" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" placeholder="[:name]" [:attributes]>
        <label for="[:name]" >[:label]</label>
    </div>',
    'textarea' =>
    '<div class="form-floating mb-3">
        <textarea name="[:name]" id="[:id]" rows="[:row]" cols="[:col]" [:class-ovwr] class="form-control" placeholder="[:name]" [:attributes]>[:value]</textarea>
        <label for="[:name]" >[:label]</label>
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
    '<div class="form-floating mb-3">
        <select [:class-ovwr] class="form-select" name="[:name]" id="[:id]" placeholder="[:name]" [:attributes]>
            [:value]
        </select>
        <label for="[:name]" >[:label]</label>
    </div>',
    'datalist' =>
        '<div class="form-floating mb-3">
        <input [:class-ovwr] class="form-control" name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" placeholder="[:name]" [:attributes]>
        <datalist id="list_[:id]">
            [:options]
        </datalist>
        <label for="[:name]" >[:label]</label>
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
        <label for="[:name]" >[:label]</label>
        <input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="form-control" placeholder="[:name]" [:attributes]>
    </div>',
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>',
    'ROW_OPEN' => '<div class="row">',
    'ROW_CLOSE' => '</div>',
    'COL_OPEN' => '<div class="col">',
    'COL_CLOSE' => '</div>'
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
            <label for="[:name]" >[:label]</label>
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
 