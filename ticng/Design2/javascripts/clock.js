<!--

function clock () {
    jetzt = new Date();

    h = jetzt.getHours();
    m = jetzt.getMinutes();
    s = jetzt.getSeconds();
    time = ((h < 10) ? "0" : "") + h + ((m < 10) ? ":0" : ":") + m + ((s < 10) ? ":0" : ":") + s;
    window.document.getElementById("clock").innerHTML = time;

    setTimeout("clock()", 1000);
}

// -->
