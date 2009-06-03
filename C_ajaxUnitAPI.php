<?php
// ---------------------------------------------------------------------------
// 		ajaxUnitAPI
// ---------------------------------------------------------------------------
/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link	http://code.google.com/p/ajaxunit/
 * @version	0.11 - Browser can now report local errors and terminate testing
 */
interface ajaxUnitAPI {
	/*.public.*/	const	PACKAGE			= 'ajaxUnit',

				ACTION_ABOUT		= 'about',
				ACTION_CONTROL		= 'control',
				ACTION_CSS		= 'css',
				ACTION_DUMMY		= 'dummyrun',
				ACTION_JAVASCRIPT	= 'js',
				ACTION_PARSE		= 'parse',
				ACTION_SUITE		= 'suite',
				ACTION_END		= 'end',
				ACTION_SOURCECODE	= 'source',
				ACTION_LOGTIDY		= 'logtidy',

				TAGNAME_ADD		= 'add',
				TAGNAME_CHECKBOX	= 'checkbox',
				TAGNAME_CLICK		= 'click',
				TAGNAME_COOKIES		= 'cookies',
				TAGNAME_COPY		= 'copy',
				TAGNAME_DELETE		= 'delete',
				TAGNAME_WEBROOT		= 'webRoot',
				TAGNAME_EXPECTINGCOUNT	= 'expectingCount',
				TAGNAME_FILE		= 'file',
				TAGNAME_FORMFILL	= 'formfill',
				TAGNAME_HEADERS		= 'headers',
				TAGNAME_INCLUDEPATH	= 'include_path',
				TAGNAME_INDEX		= 'index',
				TAGNAME_INPROGRESS	= 'inProgress',
				TAGNAME_LOCATION	= 'location',
				TAGNAME_OPEN		= 'open',
				TAGNAME_PARAMETERS	= 'parameters',
				TAGNAME_POST		= 'post',
				TAGNAME_RADIO		= 'radio',
				TAGNAME_RESET		= 'reset',
				TAGNAME_RESPONSECOUNT	= 'responseCount',
				TAGNAME_RESPONSELIST	= 'responseList',
				TAGNAME_RESULT		= 'result',
				TAGNAME_RESULTS		= 'results',
				TAGNAME_SESSION		= 'session',
				TAGNAME_SET		= 'set',
				TAGNAME_STATUS		= 'status',
				TAGNAME_STOP		= 'stop',
				TAGNAME_SUITE		= 'suite',
				TAGNAME_TEST		= 'test',
				TAGNAME_TESTSFOLDER	= 'testsFolder',
				TAGNAME_TESTPATH	= 'testPath',
				TAGNAME_TESTROOT	= 'testRoot',
				TAGNAME_TEXT		= 'text',
				TAGNAME_UID		= 'uid',

				ATTRNAME_DAYS		= 'days',
				ATTRNAME_DESTINATION	= 'dest',
				ATTRNAME_ID		= 'id',
				ATTRNAME_NAME		= 'name',
				ATTRNAME_SOURCE		= 'src',
				ATTRNAME_UPDATE		= 'update',
				ATTRNAME_URL		= 'url',
				ATTRNAME_VALUE		= 'value',

				STATUS_INPROGRESS	= 'in progress',
				STATUS_FAIL		= '<span style="color:red">FAIL!</span>',
				STATUS_SUCCESS		= 'success',
				STATUS_FINISHED		= 'finished',

				TESTS_FOLDER		= 'tests',
				TESTS_EXTENSION		= 'xml',
				LOG_FOLDER		= 'logs',
				LOG_EXTENSION		= 'html',
				CONTEXT_FOLDER		= 'logs',
				LOG_MAXHOURS		= 12;
}
// End of interface ajaxUnitAPI
?>