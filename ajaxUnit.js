/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link	http://code.google.com/p/ajaxunit/
 * @version	0.7 - Now logs actions performed on each page
 */
/*global window, document, event, ActiveXObject */ // For JSLint
//var oAjaxUnit;
var oAjaxUnit = [];

// ---------------------------------------------------------------------------
// 		ajaxUnit
// ---------------------------------------------------------------------------
// The main ajaxUnit client-side class
// ---------------------------------------------------------------------------
function C_ajaxUnit() {
// ---------------------------------------------------------------------------
	this.addStyleSheet = function () {
		var i, sheetsCount = document.styleSheets.length, found = false;
		
		for (i=0; i<sheetsCount; i++) {
			if (document.styleSheets[i].title === '$package') {
				found = true;
			}
		}
		
		if (!found) {
			var css_node	= document.createElement('link');
			css_node.type	= 'text/css';
			css_node.rel	= 'stylesheet';
			css_node.href	= '$URL?$actionCSS';
			css_node.title	= '$package';
			document.getElementsByTagName('head')[0].appendChild(css_node);
		}
	};

	this.fillContainer = function (id, html) {
		var container = document.getElementById(id);

		if (container === null || typeof(container) === 'undefined') {
			return;
		}

// IE6		container.class		= id;
		container.className	= id;
		container.innerHTML	= html;
	};

	this.logAppend = function(text) {
		var	id		= '$package-log',
			container	= document.getElementById(id);

		if (container === null || typeof(container) === 'undefined') {
			container		= document.createElement('style');
			container.type		= 'text/css';
			container.innerHTML	= '.$package-log {font-family:\"Segoe UI\", Calibri, Arial, Helvetica, \"sans serif\";font-size:11px;line-height:16px;margin:0;clear:left;background-color:#FFFF88;}';

			document.getElementsByTagName('head')[0].appendChild(container);

			container		= document.createElement('div');
			container.id		= id;
			container.className	= id;

			document.getElementsByTagName('body')[0].appendChild(container);
		}

		element			= document.createElement('p');
		element.className	= id;
		element.innerHTML	= text;

		container.appendChild(element);
	};

	this.doActions = function (actionNode) {
		this.logAppend('Doing prescribed actions:');

		if (actionNode === null) {
			this.logAppend(' - nothing to do');
			return;
		}

		// Do whatever the test dictates
		var i, url, controlId, step, stepList = actionNode.firstChild.childNodes;

		for (i = 0; i < stepList.length; i++) {
			step = stepList[i];

			if (step.nodeType === 1) {	// IE doesn't understand Node.ELEMENT_NODE
				switch (step.nodeName) {
				case '$tagLocation':
					url = step.getAttribute('$attrURL');
					this.logAppend(' - changing location to ' + url);
					window.location.assign(url);
					break;
				case '$tagOpen':
					url = step.getAttribute('$attrURL');
					this.logAppend(' - popping up ' + url);
					window.open(url);
					break;
				case '$tagClick':
					controlId = step.getAttribute('$attrID');
					this.logAppend(' - clicking button ' + controlId);
					document.getElementById(controlId).click();
					break;
				case '$tagFormFill':
					this.logAppend(' - filling form fields');
					var j, controlNode, control, controlType, controlValue, controlList = step.childNodes;

					for (j = 0; j < controlList.length; j++) {
						controlNode	= controlList[j];

						if (controlNode.nodeType === 1) {	// IE doesn't understand Node.ELEMENT_NODE
							controlId	= controlNode.getAttribute('$attrID');
							controlType	= controlNode.nodeName;
							controlValue	= (typeof controlNode.textContent === 'undefined') ? controlNode.text : controlNode.textContent;
							control		= document.getElementById(controlId);

							this.logAppend(' - - setting ' + controlId + ' (' + controlType + ') to ' + controlValue);


							switch (controlType) {
								case '$tagCheckbox':
									control.checked = (controlValue === 'checked') ? true : false;
									break;
								case '$tagRadio':
									control.checked = (controlValue === 'checked') ? true : false;
									break;
								default:
									control.value = controlValue;
							}
						}
					}

					break;
				}
			}
		}
	};

// ---------------------------------------------------------------------------
// Private methods
// ---------------------------------------------------------------------------
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

	// Private properties
	var ajax = getXMLHttpRequest(), that = this;

// ---------------------------------------------------------------------------
	function handleServerResponse() {
		if ((ajax.readyState === 4) && (ajax.status === 200)) {
			var id = ajax.getResponseHeader('$componentHeader');

			switch (id) {
			case '$package':
				// Show the test console
				that.addStyleSheet();
				that.fillContainer(id, ajax.responseText);
				break;
			case '$package-$actionParse':
				that.logAppend('Actions received from test controller');
				that.doActions(ajax.responseXML);
				break;
			default:
				that.logAppend('Response received, but no <em>$componentHeader</em> header.');
			}
		}
	}

// ---------------------------------------------------------------------------
// Public methods
// ---------------------------------------------------------------------------
	this.serverTalk = function (URL, requestType, requestData) {

		ajax.open(requestType, URL);
		ajax.onreadystatechange = handleServerResponse;

		if (requestType === 'POST') {
			ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		}

		ajax.setRequestHeader('If-Modified-Since', new Date(0));	// Damn fool Internet Explorer caching feature
		ajax.send(requestData);
	};

// ---------------------------------------------------------------------------
	this.execute = function (thisAction) {
		this.serverTalk('$URL?' + thisAction, 'GET', '');
	};
}

// ---------------------------------------------------------------------------
// 		ajaxUnit
// ---------------------------------------------------------------------------
// Process results returned from XMLHttpRequest object
// ---------------------------------------------------------------------------
function ajaxUnit(ajax) {
	var postData = '$actionParse';

	postData += '&readyState='	+ ajax.readyState;
	postData += '&status='		+ ajax.status;
	postData += '&responseText='	+ encodeURIComponent(ajax.responseText);

	var thisAjaxUnit = new C_ajaxUnit();
	oAjaxUnit.push(thisAjaxUnit);

	thisAjaxUnit.logAppend('Sending some results to $URL');
	thisAjaxUnit.serverTalk('$URL', 'POST', postData);
}

// ---------------------------------------------------------------------------
// Do stuff
// ---------------------------------------------------------------------------
//oAjaxUnit	= new C_ajaxUnit();
