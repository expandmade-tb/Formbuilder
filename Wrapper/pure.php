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
        '<button type="submit" class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:class-ovwr] [:attributes]>[:value]</button>',
    'button' =>
        '<button type="button" class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:class-ovwr] [:attributes]>[:value]</button>',
    'checkbox' =>
        '<label for="[:id]" class="pure-checkbox"><input type="checkbox" id="[:id]" value="[:value]" [:class-ovwr] [:attributes] /> [:label]</label>',
    'radio' =>
        '<label for="[:id]" class="pure-radio"><input type="radio" id="[:id]" name="[:name]" value="[:value]" [:class-ovwr] [:attributes] /> [:label]</label>',
    'select' =>
        '<label for="[:id]">[:label]</label><select [:class-ovwr] name="[:name]" id="[:id]" [:attributes]>[:value]</select>',
    'datalist' =>
        '<label for="[:id]">[:label]</label><input [:class-ovwr] name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" [:attributes]><datalist id="list_[:id]">[:options]</datalist>',
    'alert' =>
        '<span class="pure-form-message" style="color:red;">[:value]</span>',
    'message' =>
        '<span class="pure-form-message" >[:value]</span>',
    'file' =>
        '<div><label for="[:name]"> [:label]</label><input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]></div>',
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>',
    'search' =>
        '<label for="[:id]">[:label]</label><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes] oninput="">
         <ul class="dropdown-menu" id="[:id]-results" style="display:none">
             <li class="dropdown-item">?</li>
         </ul>',
    'form' => 'pure-form pure-form-stacked'
    ],
    'element_parts' => //--- multiple elements
    [
    'submit_bar_header' =>
    '<div class="pure-button-group" role="group">'
    ,
    'submit_bar_element' =>
    '<button type="submit" class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:class-ovwr] [:attributes]>[:value]</button>'
    ,
    'submit_bar_footer' =>
    '</div></div>'
    ,
    'button_bar_header' =>
    '<div class="pure-button-group" role="group">'
    ,
    'button_bar_element' =>
    '<button type="button" class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:class-ovwr] [:attributes]>[:value]</button>'
    ,
    'button_bar_footer' =>
    '</div>'
    ,
    'grid_header'=>
    '<div class="pure-control-group"><label for="[:id]">[:label]</label><table id="[:id]">'
    ,
    'grid_cell' =>
    '<td class="fb-grid-cell"><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]><td>'
    ,
    'grid_footer'=>
    '</table></div>'
    ]
];
 