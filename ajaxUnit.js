/**
 * @package		ajaxUnit
 * @author		Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license		http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link		http://www.dominicsayers.com
 * @version		0.1 - First attempt
 */
/*global window, document, event, ActiveXObject */ // For JSLint
var oAjaxUnit, oAjaxUnit_ajax;

//	---------------------------------------------------------------------------
//									C_ajaxUnit_cookies
//	---------------------------------------------------------------------------
//	General cookie management
//	---------------------------------------------------------------------------
function C_ajaxUnit_cookies() {
	//	Public methods
	this.persist = function (name, value, days) {
		var date, expires;

		if (typeof(days) !== 'undefined') {
			date = new Date();
			date.setTime(date.getTime() + (days * 1000 * 3600 * 24));
			expires = '; expires=' + date.toGMTString();
		} else {
			expires = '';
		}

		document.cookie = name + '=' + value + expires + '; path=/';
	};

	this.acquire = function (name) {
		name = name + '=';
		var i, c, carray = document.cookie.split(';');

		for (i = 0; i < carray.length; i += 1) {
			c = carray[i];

			while (c.charAt(0) === ' ') {
				c = c.substring(1, c.length);
			}

			if (c.indexOf(name) === 0) {
				return c.substring(name.length, c.length);
			}
		}

		return null;
	};

	this.remove = function (name) {
		this.persist(name, '', -1);
	};
}

//	---------------------------------------------------------------------------
//									C_ajaxUnit
//	---------------------------------------------------------------------------
//	The main ajaxUnit client-side class
//	---------------------------------------------------------------------------
function C_ajaxUnit() {
	//	Private properties
	var cookies = new C_ajaxUnit_cookies();

	// Private methods
//	---------------------------------------------------------------------------
	function getCookies() {
		var username		= cookies.acquire('$cookieUsername'),
			passwordHash	= cookies.acquire('$cookiePassword'),
			sessionID		= cookies.acquire('$sessionName');
	}

//	---------------------------------------------------------------------------

	//	Public methods
//	---------------------------------------------------------------------------
	this.updateCookies = function () {
		if (this.rememberMe) {
			//	Remember username & password for 30 days
			cookies.persist('$cookieUsername', this.username, 30);
			cookies.persist('$cookiePassword', this.passwordHash, 30);
		} else {
			cookies.remove('$cookieUsername');
			cookies.remove('$cookiePassword');
		}

		if (this.staySignedIn) {
			//	Stay signed in for 2 weeks
			cookies.persist('$cookieStaySignedIn', true, 24);
		} else {
			cookies.remove('$cookieStaySignedIn');
		}
	};

//	---------------------------------------------------------------------------
	this.setFocus = function () {
		var textBox = document.getElementById('$package-suite');

		if (textBox !== null) {
			if (textBox.disabled !== 'disabled') {
				textBox.focus();
				textBox.select();
			}
		}
	};

//	---------------------------------------------------------------------------
	this.replaceHTML = function (id, html) {
		var newElement, originalElement;

		originalElement = document.getElementById(id);

		if (originalElement === null) {
			return;
		}

		if (typeof(originalElement) === 'undefined') {
			return;
		}

		newElement = originalElement.cloneNode(false);
		newElement.innerHTML = html;
		originalElement.parentNode.replaceChild(newElement, originalElement);
	};

//	---------------------------------------------------------------------------
//	Constructor
//	---------------------------------------------------------------------------
	getCookies();
}

//	---------------------------------------------------------------------------
//								C_ajaxUnit_AJAX
//	---------------------------------------------------------------------------
//	Talk to the man
//	---------------------------------------------------------------------------
function C_ajaxUnit_AJAX() {

	// Private methods
//	---------------------------------------------------------------------------
	function getXMLHttpRequest() {
		if (typeof(window.XMLHttpRequest) === 'undefined') {
			try {
				return new ActiveXObject('MSXML3.XMLHTTP');
			} catch (errMSXML3) {}

			try {
				return new ActiveXObject('MSXML2.XMLHTTP.3.0');
			} catch (errMSXML2) {}

			try {
				return new ActiveXObject('Msxml2.XMLHTTP');
			} catch (errMsxml2) {}

			try {
				return new ActiveXObject('Microsoft.XMLHTTP');
			} catch (errMicrosoft) {}

			return null;
		} else {
			return new window.XMLHttpRequest();
		}
	}

	//	Private properties
	var ajax = getXMLHttpRequest();

//	---------------------------------------------------------------------------
	function handleServerResponse() {
		if ((ajax.readyState === 4) && (ajax.status === 200)) {
			var id = ajax.getResponseHeader('$componentHeader');

			if (id === null) {
				id = '$package';
			}

			if (id === '$package') {
				//	Put the CSS in the CSS place
				var i, sheetsCount = document.styleSheets.length, found = false;
				
				for (i=0; i<sheetsCount; i++) {
					if (document.styleSheets[i].title === '$package') {
						found = true;
					}
				}
				
				if (!found) {
					var ajaxUnit_node	= document.createElement('link');
					ajaxUnit_node.type	= 'text/css';
					ajaxUnit_node.rel	= 'stylesheet';
					ajaxUnit_node.href	= '$URL?$actionCSS';
					ajaxUnit_node.title	= '$package';
					document.getElementsByTagName('head')[0].appendChild(ajaxUnit_node);
				}
			}

			oAjaxUnit.replaceHTML(id, ajax.responseText);
			oAjaxUnit.setFocus(id);
		}
	}

	//	Public methods
//	---------------------------------------------------------------------------
	this.serverTalk = function (URL, requestType, requestData) {

		ajax.open(requestType, URL);
		ajax.onreadystatechange = handleServerResponse;

		if (requestType === 'POST') {
			ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		}

		ajax.send(requestData);
	}

//	---------------------------------------------------------------------------
	this.execute = function (thisAction) {
		this.serverTalk('$URL?' + thisAction, 'GET', '');
	};
}

//	---------------------------------------------------------------------------
//									ajaxUnit
//	---------------------------------------------------------------------------
//	Process results returned from XmlHTTPRequest object
//	---------------------------------------------------------------------------
function ajaxUnit(ajax) {
	var postData = '$actionParse';

	postData += '&readyState='		+ ajax.readyState;
	postData += '&status='			+ ajax.status;
	postData += '&responseText='	+ encodeURIComponent(ajax.responseText);

	oAjaxUnit_ajax.serverTalk('$URL', 'POST', postData);
}

//	---------------------------------------------------------------------------
//	Do stuff
//	---------------------------------------------------------------------------
oAjaxUnit		= new C_ajaxUnit();
oAjaxUnit_ajax	= new C_ajaxUnit_AJAX();
