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
        '<label for="[:id]">[:label]</label><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]>',
    'number' =>
        '<label for="[:id]">[:label]</label><input type="number" name="[:name]" id="[:id]" value="[:value]" min="[:min]" max="[:max]" step="[:step]" [:class-ovwr] [:attributes]>',
    'password' =>
        '<label for="[:id]">[:label]</label><input type="password" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]>',
    'date' => 
        '<label for="[:id]">[:label]</label><input type="date" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]>',
    'datetime' => 
        '<label for="[:id]">[:label]</label><input type="datetime-local" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]>',
    'textarea' =>
        '<label for="[:id]">[:label]</label><textarea name="[:name]" id="[:id]" rows="[:row]" cols="[:col]" [:class-ovwr] [:attributes]>[:value]</textarea>',
    'submit' =>
        '<input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] [:attributes]/>',
    'button' =>
        '<button name="[:name]" id="[:id]" type="button" [:class-ovwr] >[:value]</button>',
    'checkbox' =>
        '<label for="[:id]">[:label]</label><input [:class-ovwr] type="checkbox" name="[:name]" value="[:value]" id="[:id]" [:attributes]>',
    'radio' =>
        '<label for="[:id]">[:label]</label><input [:class-ovwr] type="radio" name="[:name]" value="[:value]" id="[:id]" [:attributes]>',
    'select' =>
        '<label for="[:id]">[:label]</label><select [:class-ovwr] name="[:name]" id="[:id]" [:attributes]>[:value]</select>',
    'datalist' =>
        '<label for="[:id]">[:label]</label><input [:class-ovwr] name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" [:attributes]><datalist id="list_[:id]">[:options]</datalist>',
    'alert' =>
        '<div class="text-danger">[:value]</div>',
    'message' =>
        '<div [:class-ovwr] class="alert alert-danger" role="alert [:attributes]">[:value]</div>',
    'file' =>
        '<label for="[:id]">[:label]</label><input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]></div>',
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>',
    'search' =>
        '<label for="[:id]">[:label]</label><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes] oninput="">
         <ul class="dropdown-menu" id="[:id]-results" style="display:none">
             <li class="dropdown-item">?</li>
         </ul>'
    ],
    'element_parts' => //--- multiple elements
    [
    'submit_bar_header' =>
    '<div class="fb-submit-bar">'
    ,
    'submit_bar_element' =>
    '<input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] [:attributes]/>'
    ,
    'submit_bar_footer' =>
    '</div></div>'
    ,
    'button_bar_header' =>
    '<div class="fb-button-bar">'
    ,
    'button_bar_element' =>
    '<button name="[:name]" id="[:id]" type="button" [:class-ovwr] >[:value]</button>'
    ,
    'button_bar_footer' =>
    '</div>'
    ,
    'grid_header'=>
    '<div class="fb-grid"><label for="[:id]">[:label]</label><table id="[:id]">'
    ,
    'grid_cell' =>
    '<td class="fb-grid-cell"><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]><td>'
    ,
    'grid_footer'=>
    '</table></div>'
    ]
];
 