<?php
// ---------------------------------------------------------------------------
// 								ajaxUnitAPI
// ---------------------------------------------------------------------------
/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link	http://www.dominicsayers.com
 * @version	0.2 - Partially working :-)
 */
interface ajaxUnitAPI {
	/*.public.*/	const	PACKAGE			= 'ajaxUnit',

				ACTION_PARSE		= 'parse',
				ACTION_CONTROL		= 'control',
				ACTION_SUITE		= 'suite',
				ACTION_DUMMY		= 'dummyrun',
				ACTION_JAVASCRIPT	= 'js',
				ACTION_CSS		= 'css',
				ACTION_ABOUT		= 'about',
				ACTION_SOURCECODE	= 'source',

				TAGNAME_ADD		= 'add',
				TAGNAME_CLICK		= 'click',
				TAGNAME_COOKIES		= 'cookies',
				TAGNAME_COPY		= 'copy',
				TAGNAME_DELETE		= 'delete',
				TAGNAME_EXPECTINGCOUNT	= 'expectingCount',
				TAGNAME_FILE		= 'file',
				TAGNAME_FORMFILL	= 'formfill',
				TAGNAME_HEADERS		= 'headers',
				TAGNAME_ID		= 'id',
				TAGNAME_INCLUDEPATH	= 'include_path',
				TAGNAME_INDEX		= 'index',
				TAGNAME_INPROGRESS	= 'inProgress',
				TAGNAME_PARAMETERS	= 'parameters',
				TAGNAME_RESET		= 'reset',
				TAGNAME_RESPONSECOUNT	= 'responseCount',
				TAGNAME_RESPONSELIST	= 'responseList',
				TAGNAME_RESULT		= 'result',
				TAGNAME_RESULTS		= 'results',
				TAGNAME_ROOT		= 'root',
				TAGNAME_SESSION		= 'session',
				TAGNAME_SET		= 'set',
				TAGNAME_STATUS		= 'status',
				TAGNAME_SUITE		= 'suite',
				TAGNAME_TEST		= 'test',
				TAGNAME_UID		= 'uid',

				ATTRNAME_DAYS		= 'days',
				ATTRNAME_DESTINATION	= 'dest',
				ATTRNAME_NAME		= 'name',
				ATTRNAME_SOURCE		= 'src',
				ATTRNAME_UPDATE		= 'update',
				ATTRNAME_VALUE		= 'value',

				STATUS_INPROGRESS	= 'in progress',
				STATUS_FAIL		= '<span style="color:red">FAIL!</span>',
				STATUS_SUCCESS		= 'success',

				TESTS_FOLDER		= 'tests',
				TESTS_EXTENSION		= 'xml',
				LOG_FOLDER		= 'logs',
				LOG_EXTENSION		= 'html',
				CONTEXT_FOLDER		= 'logs';
}
// End of interface ajaxUnitAPI
?>