function popup(w,h,site) {
    x = screen.availWidth/2-w/2;
    y = screen.availHeight/2-h/2;
    var popupWindow = window.open(
        '','','width='+w+',height='+h+',left='+x+',top='+y+',screenX='+x+',screenY='+y);
    popupWindow.document.write(site);
}
