function copyText(element) {
    var copyText = document.getElementById(element);
    copyText.style.display = 'block';

    copyText.select();
    copyText.setSelectionRange(0, 99999);

    document.execCommand("copy");

    copyText.style.display = 'none';
}

function prettyPrint(element) {
    var ugly = element.value;
    var obj = JSON.parse(ugly);
    var pretty = JSON.stringify(obj, undefined, 4);

    element.value = pretty;
}

