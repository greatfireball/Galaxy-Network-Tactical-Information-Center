// XMLHTTPRequest wrapper Klasse
function Ajax() {
	this.xhr = this.createXHRObject(); // XMLHTTPRequest object    
	this.working = false;
	this.callback = false;
	this.requestQueue = [];
	this.workingCallback = false;
}

Ajax.prototype.createXHRObject = function() {
	var xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!xhr) {
		alert('Giving up :( Cannot create an XMLHTTPRequest instance');
	}
	return xhr;
}

Ajax.prototype.responseHandler = function(e) {
	//alert("responseHandler: "+e+" state: "+this.xhr.readyState + " foo: " + this.foo);
	if (this.xhr.readyState == 4) {
		if (this.xhr.status == 200) {
			//alert(this.xhr.responseText);
			var callbackParam;
			try {
				// try evalulating response as JSON
				var callbackParam = eval('('+this.xhr.responseText+')')
			} catch (e) {
				// fallback, give application the literal response
				callbackParam = this.xhr.responseText;
			}
			this.callback(callbackParam);
		} else {
			alert('http error: '+this.xhr.status);
		}
		this.setWorking(false);
		this.checkQueue();
	}
}

Ajax.prototype.checkQueue = function() {
	if (this.requestQueue.length == 0)
		return;
	var req = this.requestQueue.shift();
	this.makeRequest(req[0], req[1], req[2]);
}

Ajax.prototype.onError = function(e) {
	alert("Error " + e.target.status + " occurred while receiving the document.");
}

// post ist entweder false -> method = GET
// oder post ist ein assoc array mit variablen und werten
Ajax.prototype.makeRequest = function(/*url, callback, post*/) {
	var url = arguments[0];
	var callback = arguments[1];
	if (arguments.length > 2)
		var post = arguments[2];
	else
		var post = false;
	//alert(url);
	
	if (this.working) {
		this.requestQueue.push(Array(url, callback, post));
		return true;
	}
	if (window.ActiveXObject) // IE is dumm und kann XMLHTTPRequest immer nur für einen request nutzen... :(
		this.xhr = this.createXHRObject();
	
	this.callback = callback;
	this.setWorking(true);
	this.xhr.onreadystatechange = (function(x) { return function(e) { x.responseHandler(e); }; })(this);
	if (this.xhr.onerror) {
		this.xhr.onerror = (function(x) { return function(e) { this.onError(e); }; })(this);
	}
	if (post) {
		var parameters = '';
		for (variable in post) {
			var value = post[variable];
			parameters += '&';
			if (parameters == '&')
				parameters = '';
			parameters += variable+'='+encodeURI(value);
		}
		//alert('post: '+parameters);
		
		this.xhr.open('POST', url, true);
		this.xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		this.xhr.setRequestHeader("Content-length", parameters.length);
		this.xhr.setRequestHeader("Connection", "close");
		this.xhr.send(parameters);
	} else {
		this.xhr.open('GET', url, true);
		this.xhr.send(null);
	}
	return true;
}

Ajax.prototype.setWorkingCallback = function(func) {
	this.workingCallback = func;
}

Ajax.prototype.setWorking = function(w) {
	if (this.working == w)
		return;

	this.working = w;
	if (this.workingCallback != false)
		this.workingCallback(this.working);
}

if (!ajax) {
	var ajax = new Ajax();
}