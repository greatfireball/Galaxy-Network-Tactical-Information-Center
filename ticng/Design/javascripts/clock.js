function clock () {
    var jetzt = new Date();

    var h = jetzt.getHours();
    var m = jetzt.getMinutes();
    var s = jetzt.getSeconds();
    var time = ((h < 10) ? "0" : "") + h + ((m < 10) ? ":0" : ":") + m + ((s < 10) ? ":0" : ":") + s;
    window.document.getElementById("clock").innerHTML = time;

    //setTimeout("clock()", 1000);
}

//setInterval('clock()', 1000);

