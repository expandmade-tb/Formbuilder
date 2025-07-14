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
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]>
                    </div>
                </div>
            </div>
        </div>',
    'number' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input type="number" name="[:name]" id="[:id]" value="[:value]" min="[:min]" max="[:max]" step="[:step]" [:class-ovwr] class="input" [:attributes]>
                    </div>
                </div>
            </div>
        </div>',
    'password' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input type="password" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]>
                    </div>
                </div>
            </div>
        </div>',
    'date' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input type="date" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]>
                    </div>
                </div>
            </div>
        </div>',
    'datetime' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input type="datetime" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]>
                    </div>
                </div>
            </div>
        </div>',
    'textarea' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <textarea name="[:name]" id="[:id]" rows="[:row]" cols="[:col]" [:class-ovwr] class="textarea" [:attributes]>[:value]</textarea>
                    </div>
                </div>
            </div>
        </div>',
    'submit' =>
        '<input type="submit" name="[:name]" value="[:value]" id="[:id]" [:class-ovwr] class="button is-primary" [:attributes]/>',
    'button' =>
        '<button name="[:name]" id="[:id]" type="button" [:class-ovwr] class="button is-primary is-horizontal">[:value]</button>',
    'checkbox' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <label class="checkbox" for="[:id]">
                            <input [:class-ovwr] type="checkbox" name="[:name]" value="[:value]" id="[:id]" [:attributes]>
                            [:label]
                        </label>
                    </div>
                </div>
            </div>
        </div>',
    'radio' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="[:name]" id="[:id]" [:attributes] [:class-ovwr]>
                            [:value]
                        </label>
                    </div>
                </div>
            </div>
        </div>',
    'select' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="select">
                        <select [:class-ovwr] name="[:name]" id="[:id]" [:attributes]>[:value]</select>
                    </div>
                </div>
            </div>
        </div>',
    'datalist' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <input [:class-ovwr] class="input" name="[:name]" list="list_[:id]" id="[:id]" value="[:value]" [:attributes]>
                    <datalist id="list_[:id]">
                        [:options]
                    </datalist>
                </div>
            </div>
        </div>',
    'alert' =>
        '<div class="message is-danger">[:value]</div>',
    'message' =>
        '<div [:class-ovwr] class="notificationr is-danger" [:attributes]">[:value]</div>',
    'file' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <input type="file" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]>
                </div>
            </div>
        </div>',
    'hidden' =>
        '<input type="hidden" name="[:name]" id="[:id]" value="[:value]" [:attributes]>',
    'search' =>
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]>
                        <ul class="dropdown-menu" id="[:id]-results" style="display:none">
                            <li class="dropdown-item">?</li>
                        </ul>
                    </div>
                </div>
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
        '<div class="field is-horizontal">
            <div class="field-label is-normal"><label class="label">[:label]</label></div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <table id="[:id]">',
    'grid_cell' =>
        '<td><input type="text" name="[:name]" id="[:id]" value="[:value]" [:class-ovwr] class="input" [:attributes]><td>',
    'grid_footer'=>
        '</table></div></div></div></div>'
    ]
];