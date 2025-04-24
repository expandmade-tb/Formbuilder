function addDropdownItems(src){
    var results = document.getElementById(src.id + '-results');
    var searchVal = src.value;

    if (searchVal.length < 1){
        results.style.display='none';
        return;
    }

    var xhr = new XMLHttpRequest();
    var datasource = src.dataset.source;
    var controller = src.form.dataset.controller;
    var token = document.getElementById('csrf-token').value;
    var url = '/' + controller + '/' + datasource + 'Search/' + searchVal;
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Ajax-Request-Token', token);

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            var response = JSON.parse(xhr.responseText);
            var innerHTML = '';

            response.forEach((response) => {
                innerHTML = innerHTML + ('<li class="dropdown-item" onclick="selectDropdownItem(this);">'+response+'</li>');
            });
            
            results.style.display='inline';
            results.innerHTML = innerHTML;
        }
    }

    xhr.send();
}

function selectDropdownItem(src) {
    var parent_element = src.parentElement;
    var input_element = document.getElementById(parent_element.id.split("-")[0]);
    var datasource = input_element.dataset.source;
    var controller = input_element.form.dataset.controller;

    input_element.value = src.textContent;
    input_element.textContent = src.textContent;
    parent_element.style.display='none';

    var xhr = new XMLHttpRequest();
    var token = document.getElementById('csrf-token').value;
    var url = '/' + controller + '/' + datasource + 'Find?search_value=' + input_element.textContent;
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Ajax-Request-Token', token);

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200) {
             jdata = xhr.responseText;
             callback = 'update' + datasource + 'Data';
             window[callback](jdata);
        }
    }

    xhr.send();
}