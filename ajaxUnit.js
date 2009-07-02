/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxunit/
 * @version	0.19 - Now BSD licensed

 */
/*jslint eqeqeq: true, immed: true, nomen: true, strict: true, undef: true*/
/*global window, document, event, ActiveXObject */ // For JSLint
'use strict';
var ajaxUnitInstances = [];

// ---------------------------------------------------------------------------
//		ajaxUnit
// ---------------------------------------------------------------------------
// The main ajaxUnit client-side class
// ---------------------------------------------------------------------------
function C_ajaxUnit() {
	if (!(this instanceof arguments.callee)) {throw Error('Constructor called as a function');}

// ---------------------------------------------------------------------------
// Private methods
// ---------------------------------------------------------------------------
	function getXMLHttpRequest() {
		if (typeof window.XMLHttpRequest === 'undefined') {
			try {return new ActiveXObject('MSXML3.XMLHTTP');}	catch (errMSXML3) {}
			try {return new ActiveXObject('MSXML2.XMLHTTP.3.0');}	catch (errMSXML2) {}
			try {return new ActiveXObject('Msxml2.XMLHTTP');}	catch (errMsxml2) {}
			try {return new ActiveXObject('Microsoft.XMLHTTP');}	catch (errMicrosoft) {}
			return null;
		} else {
			return new window.XMLHttpRequest();
		}
	}

	// Private properties
	var ajax = getXMLHttpRequest(), that = this;

// ---------------------------------------------------------------------------
	function fillContainer(id, html) {
		var container = document.getElementById(id);
		if (container === null || typeof container === 'undefined') {return;}
// IE6		container.class		= id;
		container.className	= id;
		container.innerHTML	= html;
	}

// ---------------------------------------------------------------------------
	function addStyleSheet() {
		var	htmlHead	= document.getElementsByTagName('head')[0],
			nodeList	= htmlHead.getElementsByTagName('link'),
			elementCount	= nodeList.length,
			found		= false,
			i, node;

		for (i = 0; i < elementCount; i++) {
			if (nodeList[i].title === '$package') {
				found = true;
				break;
			}
		}

		if (found === false) {
			node		= document.createElement('link');
			node.type	= 'text/css';
			node.rel	= 'stylesheet';
			node.href	= '$URL?$actionCSS';
			node.title	= '$package';
			htmlHead.appendChild(node);
		}
	}

// ---------------------------------------------------------------------------
	function logAppend(text, fail) {
		var	id		= '$package-log',
			container	= document.getElementById(id),
			markupStart	= '',
			markupEnd	= '';

		// if log div doesn't exist then add it to the page
		if (container === null || typeof container === 'undefined') {
			container		= document.createElement('div');
			container.id		= id;
			container.style.cssText	= 'width:420px;font-family:Segoe UI, Calibri, Arial, Helvetica, sans-serif;font-size:11px;line-height:16px;margin:0;clear:left;background-color:#FFFF88;';
			document.getElementsByTagName('body')[0].appendChild(container);
		}

		// Log a fail?
		if (arguments.length === 1) {fail = false;}

		if (fail)			{
			markupStart	= '<strong>'; 
			markupEnd	= '</strong>'; 
			serverTalk('GET', 'end=' + encodeURI(text));
		}

		// Add to the log
		var element		= document.createElement('p');
		element.style.margin	= 0;
		element.innerHTML	= markupStart + text + markupEnd;

		container.appendChild(element);
//		window.alert(text); // Uncomment this to step through the tests
	}

// ---------------------------------------------------------------------------
	function doFormFill(controlNode) {
		var	controlId	= controlNode.getAttribute('$attrID'),
			controlType	= controlNode.nodeName,
			controlValue	= (typeof controlNode.textContent === 'undefined') ? controlNode.text : controlNode.textContent,
			control		= document.getElementById(controlId),
			e, doEvent;

		if (control === null) {
			logAppend(' - - No control with id ' + controlId, true);
		} else {
			logAppend(' - - setting ' + controlId + ' (' + controlType + ') to ' + controlValue);

			if (typeof document.activeElement.onBlur === 'function') {document.activeElement.onBlur()}
			if (typeof document.activeElement.onblur === 'function') {document.activeElement.onblur()}
			if (typeof control.onFocus === 'function') {doEvent = control.onFocus(control);}
			if (typeof control.onfocus === 'function') {doEvent = control.onfocus(control);}
			if (doEvent !== false) {control.focus();}

			switch (controlType) {
				case '$tagCheckbox':
					control.checked = (controlValue === 'checked') ? true : false;
					if (typeof control.onClick === 'function') {control.onClick(control);}
					if (typeof control.onclick === 'function') {control.onclick(control);}
					break;
				case '$tagRadio':
					control.checked = (controlValue === 'checked') ? true : false;
					if (typeof control.onClick === 'function') {control.onClick(control);}
					if (typeof control.onclick === 'function') {control.onclick(control);}
					break;
				default:
					control.defaultValue	= controlValue;
					control.value		= controlValue;

					if (typeof control.onChange	=== 'function') {control.onChange(control);}
					if (typeof control.onchange	=== 'function') {control.onchange(control);}
					
					if (typeof event === 'undefined') {
						// Non-IE
						function event(which, target) {
							this.which = which;
							this.target = target;
						}

						e = new event(controlValue.charCodeAt(controlValue.length - 1), control);
					} else {
						// IE
						e = event;
						e.keyCode = controlValue.charCodeAt(controlValue.length - 1);
						e.target = control;
					}

					if (typeof control.onKeyDown	=== 'function') {control.onKeyDown(e);}
					if (typeof control.onkeydown	=== 'function') {control.onkeydown(e);}
					if (typeof control.onKeyUp	=== 'function') {control.onKeyUp(e);}
					if (typeof control.onkeyup	=== 'function') {control.onkeyup(e);}
					break;
			}
		}
	}

// ---------------------------------------------------------------------------
	function doStep(step) {
		var url, control, controlId, controlNode;

		switch (step.nodeName) {
		case '$tagLocation':
			url = step.getAttribute('$attrURL');
			logAppend(' - changing location to ' + url);
			window.location.assign(url);
			break;
		case '$tagOpen':
			url = step.getAttribute('$attrURL');
			logAppend(' - popping up ' + url);
			window.open(url);
			break;
		case '$tagClick':
			controlId	= step.getAttribute('$attrID');
			control		= document.getElementById(controlId);

			if (control === null) {
				logAppend(' - No control with id ' + controlId, true);
			} else {
				logAppend(' - clicking button ' + controlId);
				if (typeof document.activeElement.onBlur === 'function') {document.activeElement.onBlur()}
				if (typeof document.activeElement.onblur === 'function') {document.activeElement.onblur()}
				if (typeof control.onFocus === 'function') {doEvent = control.onFocus(control);}
				if (typeof control.onfocus === 'function') {doEvent = control.onfocus(control);}
				if (doEvent !== false) {
					control.focus();
					control.click();
				}
			}

			break;
		case '$tagPost':
			var element, html, postData;

			controlId	= step.getAttribute('$attrID');
			control		= document.getElementById(controlId);

			if (control === null) {
				logAppend(' - No control with id ' + controlId, true);
			} else {
				element		= document.createElement('dummy');
				element.appendChild(control.cloneNode(true));
				html		= element.innerHTML;
				postData	= '$actionParse&responseText='	+ encodeURIComponent(html);

				logAppend(' - posting element ' + controlId);
				serverTalk('POST', postData);
			}

			break;
		case '$tagFormFill':
			logAppend(' - filling form fields');
			var j, controlList = step.childNodes;

			for (j = 0; j < controlList.length; j++) {
				controlNode = controlList[j];
				if (controlNode.nodeType === 1) {doFormFill(controlNode);}
			}

			break;
		case '$tagLogAppend':
			logAppend(' - ' + step.nodeValue);
			break;
		default:
			logAppend(' - unknown action: ' + step.nodeName, true);
			logAppend(' - content is ' + step.nodeValue);
			break;
		}
	}

// ---------------------------------------------------------------------------
	function doActions(actionNode) {
		logAppend('Doing prescribed actions:');

		if (actionNode === null) {
			logAppend(' - nothing to do');
			return;
		}

		// Do whatever the test dictates
		var i, step, stepList = actionNode.firstChild.childNodes;

		for (i = 0; i < stepList.length; i++) {
			step = stepList[i];
			if (step.nodeType === 1) {doStep(step);}
		}
	}

// ---------------------------------------------------------------------------
	function handleServerResponse() {
		if ((ajax.readyState === 4) && (ajax.status === 200)) {
			var id = ajax.getResponseHeader('$componentHeader');

			switch (id) {
			case '$package':
				// Show the test console
				addStyleSheet();
				fillContainer(id, ajax.responseText);
				break;
			case '$package-$actionParse':
				logAppend('Actions received from test controller');
				doActions(ajax.responseXML);
				break;
			case '$package-logmessage':
				logAppend(ajax.responseText);
				break;
			default:
				logAppend('Response received, but no <em>$componentHeader</em> header.');
			}
		}
	}

// ---------------------------------------------------------------------------
	function serverTalk(requestType, requestData) {
		var URL = '$URL';

		if (requestType === 'POST') {
			ajax.open(requestType, URL);
			ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		} else {
			ajax.open(requestType, URL + '?' + requestData);
			requestData = '';
		}

		ajax.onreadystatechange = handleServerResponse;
		ajax.setRequestHeader('If-Modified-Since', new Date(0));	// Internet Explorer caching 'feature'
		ajax.send(requestData);
	}

// ---------------------------------------------------------------------------
// Public methods
// ---------------------------------------------------------------------------
	this.getResponse = function (action) {
		serverTalk('GET', action);
	};

	this.postResponse = function (appAjax) {
		var	postData = '$actionParse';
			postData += '&readyState='	+ appAjax.readyState;
			postData += '&status='		+ appAjax.status;
			postData += '&responseText='	+ encodeURIComponent(appAjax.responseText);

		logAppend('Relaying an XMLHttpRequest response to $URL');
		serverTalk('POST', postData);
	};
}

// ---------------------------------------------------------------------------
//		ajaxUnit
// ---------------------------------------------------------------------------
// Process results returned from XMLHttpRequest object
// ---------------------------------------------------------------------------
function ajaxUnit(ajax) {
	var thisAjaxUnit = new C_ajaxUnit();
	ajaxUnitInstances.push(thisAjaxUnit);
	thisAjaxUnit.postResponse(ajax);
}
