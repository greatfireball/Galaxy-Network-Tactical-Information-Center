function TaktikHUD(div) {
    this.SHOW_ALLIANZ_LIMIT = 8;
    this.data = false;
    this.div = div;
    this.ajax = new Ajax();
    //this.ajax = window.ajax;
    this.refreshTime = 1; // minutes

    window.setInterval((function(x) { return function() { x.refresh(); }; })(this),
                       this.refreshTime * 60 * 1000);

    this.refresh = function() {
        var URL = window.TIC_URL+'/wrapper.php?mod=JSON&menu=taktikhud';
        this.ajax.makeRequest(URL,
                              (function(x) { return function(y) { x.updateHandler(y); }; })(this),
                              false);
    }
    this.refresh();

    this.updateHandler = function(data) {
        this.data = data;
        if (this.countAllianzen(data) > this.SHOW_ALLIANZ_LIMIT)
            var display = 'meten';
        else
            var display = 'allianzen';

        // clear Hud
        while (this.div.childNodes.length > 0) {
            this.div.removeChild(this.div.childNodes[0]);
        }

        var table = document.createElement('table');
        table.setAttribute('align', 'center');
        table.setAttribute('class', 'taktikhud');
        this.div.appendChild(table);
        var tbody = document.createElement('tbody');
        table.appendChild(tbody);

        //name
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        tr.appendChild(td);
        td.appendChild(document.createTextNode('Name:'));
        this.addHudTableRow(display, data, 'name', tr);

        //incs
        tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        tr.appendChild(td);
        td.appendChild(document.createTextNode('Incs:'));
        tbody.appendChild(tr);

        this.addHudTableRow(display, data, 'incs', tr);

        //online
        tr = document.createElement('tr');
        var td = document.createElement('td');
        tr.appendChild(td);
        td.appendChild(document.createTextNode('Online:'));
        tbody.appendChild(tr);
        this.addHudTableRow(display, data, 'online', tr);
    }

    this.addHudTableRow = function(display, data, row, tr) {
        if (display == 'meten') {
            for (var i in data) {
                var meta = data[i];
                
                var td = document.createElement('td');
                tr.appendChild(td);
                var text = '';
                switch (row) {
                case 'name':
                    var span = document.createElement('span');
                    td.appendChild(span);
                    span.setAttribute('class', 'metatag');
                    span.appendChild(document.createTextNode(meta.tag));
                    break;
                case 'incs':
                    var span = document.createElement('span');
                    td.appendChild(span);
                    span.setAttribute('class', 'incs_open');
                    span.appendChild(document.createTextNode(this.sumMetaAttr(meta, 'open')));
                    td.appendChild(document.createTextNode(' / '));

                    span = document.createElement('span');
                    td.appendChild(span);
                    span.setAttribute('class', 'incs_undertime');
                    span.appendChild(document.createTextNode(this.sumMetaAttr(meta, 'undertime')));
                    td.appendChild(document.createTextNode(' / '));

                    span = document.createElement('span');
                    td.appendChild(span);
                    span.setAttribute('class', 'incs_safe');
                    span.appendChild(document.createTextNode(this.sumMetaAttr(meta, 'safe')));
                    break;
                case 'online':
                    var span = document.createElement('span');
                    td.appendChild(span);
                    span.setAttribute('class', 'member_online');
                    span.appendChild(document.createTextNode(this.sumMetaAttr(meta, 'online')));
                    td.appendChild(document.createTextNode(' / '));

                    span = document.createElement('span');
                    td.appendChild(span);
                    span.setAttribute('class', 'member');
                    span.appendChild(document.createTextNode(this.sumMetaAttr(meta, 'member')));
                    break;
                }
            }
        } else {
            for (var i in data) {
                for (var j in data[i]['allianzen']) {
                    var alli = data[i]['allianzen'][j];
                    var td = document.createElement('td');
                    tr.appendChild(td);
                    var text = '';
                    switch (row) {
                    case 'name':
                        var span = document.createElement('span');
                        td.appendChild(span);
                        span.setAttribute('class', 'allitag');
                        span.appendChild(document.createTextNode(alli.tag));
                        break;
                    case 'incs':
                        var span = document.createElement('span');
                        td.appendChild(span);
                        span.setAttribute('class', 'incs_open');
                        span.appendChild(document.createTextNode(alli.open));
                        td.appendChild(document.createTextNode(' / '));

                        span = document.createElement('span');
                        td.appendChild(span);
                        span.setAttribute('class', 'incs_undertime');
                        span.appendChild(document.createTextNode(alli.undertime));
                        td.appendChild(document.createTextNode(' / '));

                        span = document.createElement('span');
                        td.appendChild(span);
                        span.setAttribute('class', 'incs_safe');
                        span.appendChild(document.createTextNode(alli.safe));
                        break;
                    case 'online':
                        var span = document.createElement('span');
                        td.appendChild(span);
                        span.setAttribute('class', 'member_online');
                        span.appendChild(document.createTextNode(alli.online));
                        td.appendChild(document.createTextNode(' / '));

                        span = document.createElement('span');
                        td.appendChild(span);
                        span.setAttribute('class', 'member');
                        span.appendChild(document.createTextNode(alli.member));
                        break;
                    }
                }
            }
        }
    }

    this.sumMetaAttr = function(meta, attr) {
        var count = 0;
        for (var i = 0; i < meta.allianzen.length; i++) {
            count += meta.allianzen[i][attr];
        }
        return count;
    }

    this.countAllianzen = function(data) {
        var count = 0;
        for (var i in data) {
            count += data[i]['allianzen'].length;
        }
        return count;
    }
}

