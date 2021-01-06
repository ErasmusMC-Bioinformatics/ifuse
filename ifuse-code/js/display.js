function display(show, hide){
    if (typeof(all) != 'undefined') {
        toggleAllDisplay(hide, 'none');
    }
    if (typeof(all) != 'undefined') {
        toggleAllDisplay(show, ''    );
    }
}

function toggleDisplay(obj, display_value) {
    if (isArray(obj)) {
        for (var i in obj) {
            if(obj[i].style) {
                toggleDisplay(obj[i], display_value);
            }
        }
    } else {
        if (obj.style) {
            obj.style.display = (typeof(display_value) != 'undefined') ? display_value : (obj.style.display == 'none') ? '' : 'none' ;
        }
    }
}

function createHelp(obj,msg) {
    if (obj) {
        obj.onmouseover = new Function("helpMSG('"+msg+"');");
        obj.onmouseout  = new Function("helpMSG('&nbsp;');");
        helpMSG(msg);
        return true;
    }
    return false;
}

function helpMSG(msg) {
    if (document.getElementById('helpLabel')) {
        document.getElementById('helpLabel').innerHTML = msg;
        return true;
    }
    return false;
}