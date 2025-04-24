function onRowClick(src, method, value){
    var result = src.dataset.externalid;
    var cell = src.cells[1];
    var token = src.closest('table')?.dataset.token;

    if ( token === undefined ) token = 'undefined';

    var xhr = new XMLHttpRequest();
    var url = '/clientRequests/' + method + '/' + result;
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Ajax-Request-Token', token);

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200) {
             result = xhr.responseText;

             if (result == 1) {
                var c = (parseInt(cell.textContent) || 0) + value;
                cell.textContent = c < 0 ? cell.textContent = 0 : cell.textContent = c; 
                oldColor = cell.style.backgroundColor;
                cell.style.backgroundColor = "orange";

                setTimeout(function() {
                    cell.style.backgroundColor = oldColor;
                }, 1000);

                location.reload();
             }
        }
    }

    xhr.send();
} 