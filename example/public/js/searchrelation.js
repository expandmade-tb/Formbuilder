function searchrelationResults(src, dest, token){
    var results = document.getElementById(src.id + '-results');
    var searchVal = src.value;

    if(searchVal.length < 1){
        results.style.display='none';
        return;
    }

    var xhr = new XMLHttpRequest();
    var url = dest + '/' + searchVal;
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Ajax-Request-Token', token);

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            var text = xhr.responseText;
            results.style.display='inline';
            results.innerHTML = text;
        }
    }

    xhr.send();
}

function searchrelationSelect(src) {
    var parent_element = src.parentElement;
    var input_element = document.getElementById(parent_element.id.split("-")[0]);
    input_element.value = src.textContent;
    parent_element.style.display='none';
}