/* ============================ TaktikScreen ============================ */

function TaktikScreen(screen) {
    this.columns = ['outgoing', 'incs', 'deff'];

    this.gala = screen.gala;
    this.updateUser = screen.updateUser;
    this.date = screen.date;
    this.age = screen.age;
    this.ageShow = screen.ageShow;
    this.ageCss = screen.ageCss;

    this.flotten = [];
    for (koord in screen.flotten) {
        this.flotten[koord] = {};

        for (i = 0; i < this.columns.length; i++) {
            column = this.columns[i];

            this.flotten[koord][column] = [];
            for (j = 0; j < screen.flotten[koord][column].length; j++) {
                var flotte = screen.flotten[koord][column][j];
                this.flotten[koord][column].push(new Flotte(flotte));
            }
        }
    }
}

TaktikScreen.prototype.draw = function() {
    var thead = document.createElement('thead');
    var tbody = document.createElement('tbody');

    // headline
    var tr = document.createElement('tr');
    var th = document.createElement('th');
    th.class = 'taktikschirm_status';
    th.setAttribute('colspan', '7');
    th.appendChild(document.createTextNode('Taktikschirm der Galaxie '+this.gala+' -- Letztes Update '+this.date+' von '+this.user+'.'));
    if (this.ageShow) {
        th.appendChild(document.createTextNode('Der Schirm ist '));
        var age = document.createElement('span')
        age.appendChild(document.createTextNode(this.age));
        age.class = this.ageCss;        
        th.appendChild(age);
        th.appendChild(document.createTextNode('alt.'));
    }
    tr.appendChild(th);
    thead.appendChild(tr);

    // column headings
    var tr = document.createElement('tr');
    
    tr.appendChild(document.createElement('th').appendChild(document.createTextNode('Benuter')));

    var th = document.createElement('th');
    th.appendChild(document.createTextNode('Benutzerflotte'));
    th.setAttribute('colspan', '2');
    tr.appendChild(th);

    var th = document.createElement('th');
    th.appendChild(document.createTextNode('Angreifer'));
    th.setAttribute('colspan', '2');
    tr.appendChild(th);
    
    var th = document.createElement('th');
    th.appendChild(document.createTextNode('Verteidiger'));
    th.setAttribute('colspan', '2');
    tr.appendChild(th);

    thead.appendChild(tr);

    // users
    for (koord in this.flotten) {
        var user = this.flotten[koord];
        var tr = document.createElement('tr');
        tr.appendChild(document.createElement('td').appendChild(document.createTextNode(user.user)));
        tbody.appendChild(tr);

        // flotten
        var tr = document.createElement('tr');
        for (i = 0; i < this.columns.length; i++) {
            column = this.columns[i];
            var tdMain = document.createElement('td');
            var tdEta = document.createElement('td');
            for (j = user.outgoing.length-1; j >= 0; j--) {
                var flotte = user.outgoing[j];
                tdMain.appendChild(flotte.draw(column));
                tdEta.appendChild(flotte.drawEta(column));
                if (j > 0) {
                    tdMain.appendChild(document.createElement('br'));
                    tdEta.appendChild(document.createElement('br'));
                }
            }
            tr.appendChild(tdMain);
            tr.appendChild(tdEta);
        }    
        tbody.appendChild(tr);
    }

    // table
    var table = document.createElement('table');
    table.appendChild(thead);
    table.appendChild(tbody);

    // outer div
    var screen = document.createElement('div');
    screen.class = 'taktikschirm';
    screen.setAttribute('align', 'center');
    screen.appendChild(table);
    return screen;
}


/* =============================== Flotte =============================== */

function Flotte(flotte) {
    for (key in flotte)
        this.key = flotte[key];
}

Flotte.prototype.draw = function(mode) {
    var str = '';
    str += this.angriff ? 'A: ' : 'D: ';
    str += this.rueckflug ? 'RF: ' : '';
    if (mode == 'outgoing') {
        str += this.zielGala+':'+this.zielPlanet;
    } else {
        str += this.startGala+':'+this.startPlanet;
    }
    str += ' #'+this.flotte;


    var span = document.createElement('span');
    if (mode == 'outgoing')
        span.class = this.angriff ? 'att' : 'deff';
    else
        span.class = this.save ? 'safe' : 'unsafe';
    span.appendChild(document.createTextNode(str));
    return span;
}

Flotte.prototype.drawEta = function() {
   return document.createTextNode(this.eta); 
}




/* =============================== Taktik =============================== */
function Taktik() {
    this.screens = [];
    this.loadTaktikScreens(279);
}

Taktik.prototype.draw = function() {
    var root = document.getElementById('taktik_root_div');
    deleteChildren(root);

    for (i = 0; i < this.screens.length; i++) {
        root.appendChild(this.screens[i].draw());
    }
}


Taktik.prototype.loadTaktikScreens = function(gala) {
     ajax.makeRequest( 'wrapper.php?mod=JSON&menu=taktikscreens&gala='+gala,
                      (function(x) { return function(y) { x.callbackLoadTaktikScreens(y); }; })(this)
                     );
}
Taktik.prototype.callbackLoadTaktikScreens = function(screens) {
    this.screens = [];
    for (i = 0; i < screens.length; i++) {
        var screen = new TaktikScreen(screens[i]);
        this.screens.push(screen);
        this.draw();
    }
}

