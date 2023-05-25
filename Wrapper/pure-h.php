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
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]></div>',
    'number' =>
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><input type="number" name="[:name]" id="[:id]" value="[:value]" min="[:min]" max="[:max]" step="[:step]" [:class-ovwr] [:attributes]></div>',
    'password' =>
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><input type="password" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]></div>',
    'date' => 
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><input type="date" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]></div>',
    'datetime' => 
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><input type="datetime-local" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]></div>',
    'textarea' =>
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><textarea name="[:name]" id="[:id]" rows="[:row]" cols="[:col]" [:class-ovwr] [:attributes]>[:value]</textarea></div>',
    'submit' =>
        '<div class="pure-controls"><button type="submit" [:class-ovwr] class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:attributes]>[:value]</button></div>',
    'button' =>
        '<div class="pure-controls"><button type="button" [:class-ovwr] class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:attributes]>[:value]</button></div>',
    'checkbox' =>
        '<div class="pure-controls"><label for="[:id]" [:class-ovwr] class="pure-checkbox"><input type="checkbox" id="[:id]" value="[:value]" [:attributes] /> [:label]</label></div>',
    'radio' =>
        '<div class="pure-controls"><label for="[:id]" [:class-ovwr] class="pure-radio"><input type="radio" id="[:id]" name="[:name]" value="[:value]" [:attributes] /> [:label]</label></div>',
    'select' =>
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><select [:class-ovwr] name="[:name]" id="[:id]" [:attributes]>[:value]</select></div>',
    'datalist' =>
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><input [:class-ovwr] name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" [:attributes]><datalist id="list_[:id]">[:options]</datalist></div>',
    'alert' =>
        '<span class="pure-form-message" style="color:red;">[:value]</span>',
    'message' =>
        '<span class="pure-form-message" >[:value]</span>',
    'file' =>
        '<div class="pure-control-group"><label for="[:name]"> [:label]</label><input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]></div>',
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>',
    'search' =>
        '<div class="pure-control-group"><label for="[:id]">[:label]</label><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes] oninput="">
         <ul class="dropdown-menu" id="[:id]-results" style="display:none">
             <li class="dropdown-item">?</li>
         </ul></div>',
    'form' => 'pure-form pure-form-aligned'
    ],
    'element_parts' => //--- multiple elements
    [
    'submit_bar_header' =>
    '<div class="pure-button-group pure-controls" role="group">'
    ,
    'submit_bar_element' =>
    '<button type="submit" [:class-ovwr] class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:attributes]>[:value]</button>'
    ,
    'submit_bar_footer' =>
    '</div></div>'
    ,
    'button_bar_header' =>
    '<div class="pure-button-group pure-controls" role="group" >'
    ,
    'button_bar_element' =>
    '<button type="button" [:class-ovwr] class="pure-button pure-button-primary" name="[:name]" id="[:id]" [:attributes]>[:value]</button>'
    ,
    'button_bar_footer' =>
    '</div>'
    ,
    'grid_header'=>
    '<div class="pure-control-group"><label for="[:id]">[:label]</label><table id="[:id]" class="pure-controls">'
    ,
    'grid_cell' =>
    '<td class="fb-grid-cell"><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] [:attributes]><td>'
    ,
    'grid_footer'=>
    '</table></div>'
    ]
];
 