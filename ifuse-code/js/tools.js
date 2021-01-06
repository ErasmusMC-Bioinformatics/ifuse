function destroyObject(obj) {
    obj.parentNode.removeChild(obj);
}

function isArray(obj) {
    if (obj.constructor.toString().indexOf("Array") == -1) {
        return false;
    } else{
        return true;
    }
}

function isNumeric(elem, helperMsg){
    var numericExpression = /^[0-9]+$/;
    if(elem.match(numericExpression)){
        return true;
    } else {
        alert(helperMsg);
        //elem.focus();
        return false;
    }
}