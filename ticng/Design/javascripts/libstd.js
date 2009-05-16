
/* ======================================== JSON ============================================ */

function isEmpty(o) {
	for (i in o) {
		return false;
	}
	return true;
}

// ersatz für Object.toSource() da der IE das net kann
function json_encode(stuff) {
	var accu = '';
	if (typeof(stuff) == 'array' || (typeof(stuff) == 'object' && stuff instanceof Array)) {
		accu += '[';
		for (key in stuff) {
			accu += json_encode(stuff[key], true)+',';
		}
		if (!isEmpty(stuff))
			accu = accu.substr(0, accu.length-1);
		accu += ']';
	} else if (typeof(stuff) == 'object') {
		accu += '{';
		for (key in stuff) {
			accu += "\""+key+"\":"+json_encode(stuff[key], true)+',';
		}
		if (!isEmpty(stuff))
			accu = accu.substr(0, accu.length-1);
		accu += '}';
	} else if (typeof(stuff) == 'string') {
		accu += "\""+stuff.replace("\"", "\\\"")+"\"";
	} else if (typeof(stuff) == 'boolean') {
		if (stuff) {
			accu += 'true';
		} else {
			accu += 'false';
		}
	} else if (typeof(stuff) == 'number') {
		accu += stuff;
	} else if (stuff == null) {
		accu += 'null'; //FIXME ok??
	} else if (typeof(stuff) == 'undefined') {
		//accu += '[]';
	}
	return accu;
}


/* ======================================== EVENTS ============================================ */

// eventhandler wrapper, da der IE sich mal wieder an keine standards hält
// http://www.mediaevent.de/javascript/event_listener.html
function addEvent( obj, type, fn )
{
	if (obj.addEventListener)
		obj.addEventListener( type, fn, false );
	else if (obj.attachEvent)
	{
		obj["e"+type+fn] = fn;
		obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
		obj.attachEvent( "on"+type, obj[type+fn] );
	}
}

function removeEvent( obj, type, fn )
{
	if (obj.removeEventListener)
		obj.removeEventListener( type, fn, false );
	else if (obj.detachEvent)
	{
		obj.detachEvent( "on"+type, obj[type+fn] );
		obj[type+fn] = null;
		obj["e"+type+fn] = null;
	}
}

// Hover-Menus fuer den IE. Browser koennen das sogar mit CSS-only!
var ieHover = function(elements) {
	for (var i = 0; i < elements.length; i++) {
		elements[i].onmouseover = function() {
			this.className += ' iehover';
		}
		elements[i].onmouseout = function() {
			this.className = this.className.replace(new RegExp(" iehover\\b"), "");
		}
	}
}

/* ======================================== DOM ============================================ */

function deleteChildren(node) {
	while (node.childNodes.length > 0) {
		node.removeChild(node.childNodes[0]);
	}
}

// setzt rekursiv alle eingabe elemente im übergebenen dom baum auf disabled, oder enabled sie wieder
function disableFormRecursive(tree, disable) {
	if (tree.nodeName) {
		if (tree.nodeName.toLowerCase() == 'input' ||
			tree.nodeName.toLowerCase() == 'button' ||
			tree.nodeName.toLowerCase() == 'textarea' ||
			tree.nodeName.toLowerCase() == 'select') {
			tree.disabled = disable;
		}
	}
	for (var i = 0; i < tree.childNodes.length; i++) {
		disableFormRecursive(tree.childNodes[i], disable);
	}
}

/* ======================================== ARRAYS ============================================ */

function inArray(needle, haystack) {
	for (var i=0; i < haystack.length; i++) {
		if (haystack[i] != needle)
			continue;
		
		return true;
	}
	return false;
}

function removeElementsFromArray(/* workingArray, removeArray, inArrayFunc */) {
	var workingArray = arguments[0];
	var removeArray = arguments[1];
	if (arguments.length > 2)
		var inArrayFunc = arguments[2];
	else
		inArrayFunc = inArray;
	var newArray = [];
	
	for (var i=0; i < workingArray.length; i++) {
		if ((inArrayFunc(workingArray[i], removeArray) == true))
			continue;

		newArray[newArray.length] = workingArray[i];
	}
	
	return newArray;
}

/* ======================================== OO ============================================ */

// http://www.sitepoint.com/blogs/2006/01/17/javascript-inheritance/
function copyPrototype(descendant, parent) {
    var sConstructor = parent.toString();
    var aMatch = sConstructor.match( /\s*function (.*)\(/ );
    if ( aMatch != null ) { descendant.prototype[aMatch[1]] = parent; }
    for (var m in parent.prototype) {
        descendant.prototype[m] = parent.prototype[m];
    }
};

/* ========================================== CSS ========================================== */

/**
 * Switcht style.display des Elements mit ID id bei jedem Aufruf zw. displayMode und 'none'
 */
function toggle(id, displayMode) {
	var element = document.getElementById(id);
	
	if (!element)
		return;
	
	if (element.style.display == 'none' || !element.style.display)
		element.style.display = displayMode;
	else
		element.style.display = 'none';
}
