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
        '<div class="field">
            <div class="control has-floating-label">
                <input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input is-medium with-floating-label" placeholder="" [:attributes]>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',
    'number' =>
        '<div class="field">
            <div class="control has-floating-label">
                <input type="number" name="[:name]" id="[:id]" value="[:value]" min="[:min]" max="[:max]" step="[:step]" [:class-ovwr] class="input is-medium with-floating-label" placeholder="" [:attributes]>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',
    'password' =>
        '<div class="field">
            <div class="control has-floating-label">
                <input type="password" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input is-medium with-floating-label" placeholder="" [:attributes]>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',
    'date' =>
        '<div class="field">
            <div class="control has-floating-label">
                <input type="date" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input is-medium with-floating-label" placeholder="" [:attributes]>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',
    'datetime' => 
        '<div class="field">
            <div class="control has-floating-label">
                <input type="datetime-local" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input is-medium with-floating-label" placeholder="" [:attributes]>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',
    'textarea' =>
        '<div class="field">
            <div class="control has-floating-label">
                <textarea name="[:name]" id="[:id]" rows="[:row]" cols="[:col]" [:class-ovwr] class="textarea input is-medium with-floating-label" placeholder=""  [:attributes]>[:value]</textarea>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',
    'submit' =>
        '<input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] class="button is-primary" [:attributes]/>',
    'button' =>
        '<button name="[:name]" id="[:id]" type="button" [:class-ovwr] class="button is-primary">[:value]</button>',
    'checkbox' =>
        '<label class="checkbox" for="[:id]">
            <input [:class-ovwr] type="checkbox" name="[:name]" value="[:value]" id="[:id]" [:attributes]>[:label]
        </label>',
    'radio' =>
        '<div class="control">
            <label class="radio"><input type="radio" name="[:name]" id="[:id]" [:attributes] [:class-ovwr]>[:value]</label>
        </div>',    
    'select' =>
        '<div class="field">
            <div class="control has-floating-label">
                <select [:class-ovwr] name="[:name]" id="[:id]" class="input is-medium with-floating-label" placeholder="" [:attributes]>[:value]</select>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',       
    'datalist' =>
        '<div class="field">
            <div class="control has-floating-label">
                <input [:class-ovwr] class="input is-medium with-floating-label" placeholder=""  name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" [:attributes]>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
                <datalist id="list_[:id]">
                    [:options]
                </datalist>
            </div>
        </div>',
    'alert' =>
        '<div class="message is-danger">[:value]</div>',
    'message' =>
        '<div [:class-ovwr] class="notificationr is-danger" [:attributes]">[:value]</div>',
    'file' =>
        '<div class="field">
            <div class="control has-floating-label">
                <input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input is-medium with-floating-label" placeholder=""  [:attributes]>
                <label class="label is-floating-label" for="[:name]">[:label]</label>
            </div>
        </div>',       
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>',
    'search' =>
        '<div class="field">
            <div class="control has-floating-label">
                <input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input is-medium with-floating-label" placeholder="" [:attributes]>
                <ul class="dropdown-menu" id="[:id]-results" style="display:none">
                    <li class="dropdown-item">?</li>
                </ul>
            </div>
        </div>',       
    'ROW_OPEN' => '<div class="row">',
    'ROW_CLOSE' => '</div>',
    'COL_OPEN' => '<div class="col">',
    'COL_CLOSE' => '</div>'
    ],
    'element_parts' => //--- multiple elements
    [
    'submit_bar_header' =>
        '<div class="submit-bar">',
    'submit_bar_element' =>
        '<input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] class="button is-primary" [:attributes]/>',
    'submit_bar_footer' =>
        '</div>',
    'button_bar_header' =>
        '<div class="button-bar">',
    'button_bar_element' =>
        '<button name="[:name]" id="[:id]" type="button" [:class-ovwr] class="button is-primary">[:value]</button>',
    'button_bar_footer' =>
        '</div>',
    'grid_header'=>
        '<div class="field"><label for="[:name]" class="label">[:label]</label><table id="[:id]">',
    'grid_cell' =>
        '<td><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]><td>',
    'grid_footer'=>
        '</table></div>'
    ]
];
 