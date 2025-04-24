function client_ID(dest, token) {
  const fpPromise = import('https://openfpcdn.io/fingerprintjs/v3')
  .then(FingerprintJS => FingerprintJS.load())

  fpPromise
  .then(fp => fp.get())
  .then(result => {
    const visitorId = result.visitorId
    var xhr = new XMLHttpRequest();
    var url = dest + '/' + visitorId;
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Ajax-Request-Token', token);
    xhr.send();
  })
}

function LimitFilesize(src, size) {
  if( src.files[0].size > size ) {
    alert('invalid file size');
    src.value = "";
  }
}