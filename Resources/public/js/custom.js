function copyText(element) {
    var copyText = document.getElementById(element);
    copyText.style.display = 'block';

    copyText.select();
    copyText.setSelectionRange(0, 99999);

    document.execCommand("copy");

    copyText.style.display = 'none';
}
