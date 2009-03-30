<?php
/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link	http://www.dominicsayers.com
 * @version	0.2 - Partially working :-)
 */

/*.
	require_module 'standard';
.*/

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__)));
if (!function_exists('__autoload')) {/*.void.*/ function __autoload(/*.string.*/ $className) {require "C_$className.php";}}

// ---------------------------------------------------------------------------
// 								ajaxUnit.php
// ---------------------------------------------------------------------------
// Some code to make this all automagic and a bit RESTful
// If you want more control over how ajaxUnit works then you might need to amend
// or even remove the code below here

// Is this script included in another page or is it the HTTP target itself?
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
	// This script has been called directly by the client
	if (is_array($_GET) && (count($_GET) > 0)) {
		$dummyRun = (isset($_GET[ajaxUnitAPI::ACTION_DUMMY])) ? (bool) $_GET[ajaxUnitAPI::ACTION_DUMMY] : false;
		if (isset($_GET[ajaxUnitAPI::ACTION_SUITE]))		ajaxUnit::runTestSuite($_GET[ajaxUnitAPI::ACTION_SUITE], $dummyRun);
		if (isset($_GET[ajaxUnitAPI::ACTION_PARSE]))		ajaxUnit::parseTest();
		if (isset($_GET[ajaxUnitAPI::ACTION_CONTROL]))		ajaxUnitUI::getControlPanel();
		if (isset($_GET[ajaxUnitAPI::ACTION_JAVASCRIPT]))	ajaxUnitUI::getJavascript();
		if (isset($_GET[ajaxUnitAPI::ACTION_CSS]))		ajaxUnitUI::getCSS();
		if (isset($_GET[ajaxUnitAPI::ACTION_ABOUT]))		ajaxUnitUI::getAbout();
		if (isset($_GET[ajaxUnitAPI::ACTION_SOURCECODE]))	ajaxUnitUI::getSourceCode();
	} else {
		if (is_array($_POST) && (count($_POST) > 0)) {
			ajaxUnit::parseTest();
		} else {
			ajaxUnitUI::addScript();
		}
	}
}
?>