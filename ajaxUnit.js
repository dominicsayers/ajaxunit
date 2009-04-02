/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link	http://code.google.com/p/ajaxunit/
 * @version	0.4 - More test parameters added
 */
/*global window, document, event, ActiveXObject */ // For JSLint
var oAjaxUnit;

// ---------------------------------------------------------------------------
// 		C_ajaxUnit
// ---------------------------------------------------------------------------
// The main ajaxUnit client-side class
// ---------------------------------------------------------------------------
function C_ajaxUnit() {
// ---------------------------------------------------------------------------
	this.setFocus = function () {
		var textBox = document.getElementById('$package-suite');

		if (textBox !== null) {
			if (textBox.disabled !== 'disabled') {
				textBox.focus();
				textBox.select();
			}
		}
	};

// ---------------------------------------------------------------------------
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
				// Put the CSS in the CSS place
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

				that.replaceHTML(id, ajax.responseText);
				that.setFocus(id);
				break;
			case '$package-$actionParse':
				if (ajax.responseXML === null) {
					break;
				}

				// Do whatever the test dictates
				var controlId, step, stepList = ajax.responseXML.firstChild.childNodes;

				for (i = 0; i < stepList.length; i++) {
					step = stepList[i];

					if (step.nodeType === window.Node.ELEMENT_NODE) {
						switch (step.nodeName) {
						case 'open':
							window.open(step.getAttribute('url'));
							break;
						case 'click':
							controlId = step.getAttribute('id');
							document.getElementById(controlId).click();
							break;
						case 'formfill':
							var j, controlNode, control, controlType, controlValue, controlList = step.childNodes;

							for (j = 0; j < controlList.length; j++) {
								controlNode	= controlList[j];

								if (controlNode.nodeType === window.Node.ELEMENT_NODE) {
									controlId	= controlNode.getAttribute('id');
									control		= document.getElementById(controlId);
									controlType	= controlNode.nodeName;
									controlValue	= controlNode.textContent;
	
									switch (controlType) {
										case 'checkbox':
											control.checked = (controlValue === 'checked') ? true : false;
											break;
										case 'radio':
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

				break;
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
// Process results returned from XmlHTTPRequest object
// ---------------------------------------------------------------------------
function ajaxUnit(ajax) {
	var postData = '$actionParse';

	postData += '&readyState='	+ ajax.readyState;
	postData += '&status='		+ ajax.status;
	postData += '&responseText='	+ encodeURIComponent(ajax.responseText);

	oAjaxUnit.serverTalk('$URL', 'POST', postData);
}

// ---------------------------------------------------------------------------
// Do stuff
// ---------------------------------------------------------------------------
oAjaxUnit	= new C_ajaxUnit();
