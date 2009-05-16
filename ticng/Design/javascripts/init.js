function init() {
    window.TIC_URL = './';
    setInterval(clock, 1000);
    var div = document.getElementById("taktikhud");
    var hud = new TaktikHUD(div);
    hud.refresh();
}

