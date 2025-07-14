function form_field_onchange(src, event="onchange"){
    if ( src.dataset.deferOnchange === event )
        return;

    let value = src.value;

    if (value.length < 1)
        return;

    var xhr = new XMLHttpRequest();
    var url = '/' + src.form.dataset.controller + '/' + src.dataset.methodOnchange + '?changed_value=' + encodeURIComponent(value);
    var token = src.dataset.token;
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Ajax-Request-Token', token);

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            var data = JSON.parse(xhr.responseText);
            var map = JSON.parse(src.dataset.mappingOnchange);

            for (const [key, value] of Object.entries(map)) { // map result values to form fields
                let result = data[key];

                if (result) {
                    let form_field = document.getElementById(value);

                    if (form_field)
                        form_field.value = result;
                }
            }           
        }
    }

    xhr.send();
}