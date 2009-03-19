<?php
//	---------------------------------------------------------------------------
//									ajaxUnitUI
//	---------------------------------------------------------------------------
/**
 * @package		ajaxUnit
 * @author		Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license		http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link		http://www.dominicsayers.com
 * @version		0.1 - First attempt
 */
class ajaxUnitUI implements ajaxUnitAPI {
//	---------------------------------------------------------------------------
//	Configuration settings
//	---------------------------------------------------------------------------
	private static /*.string.*/ function thisURL() {
		$package	= self::PACKAGE;

		//	Find out the URL of this script so we can call it later
		$file = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? (string) str_replace("\\", '/' , __FILE__) : __FILE__;
		return dirname(substr($file, strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME']))) . "/$package.php";
	}

//	---------------------------------------------------------------------------
//	Helper functions
//	---------------------------------------------------------------------------
	private static /*.string.*/ function componentHeader() {return self::PACKAGE . "-component";}

	public static /*.void.*/ function sendContent(/*.string.*/ &$content, /*.string.*/ $component, $contentType = '') {
		//	Send headers first
		if (!headers_sent()) {
			$package	= self::PACKAGE;
			$component	= ($component === $package) ? $component : "$package-$component";
			header("Package: $package");
			header(self::componentHeader() . ": $component");
			if ($contentType !== '') header("Content-type: $contentType");
		}

		//	Send content
		echo $content;
	}

	private static /*.string.*/ function getDivId(/*.string.*/ $action) {
		return self::PACKAGE;
	}

//	---------------------------------------------------------------------------
//	UI features
//	---------------------------------------------------------------------------
	private static /*.string.*/ function htmlControlPanel() {
		$actionSuite	= self::ACTION_SUITE;
		$actionDummy	= self::ACTION_DUMMY;
		$package		= self::PACKAGE;
		$folder			= ajaxUnit::TESTS_FOLDER;
		$suiteList		= "";

		foreach (glob("$folder/*.xml") as $filename) {
			$suite		= basename($filename, '.' . ajaxUnit::TESTS_EXTENSION);
			$document	= new DOMDocument();
			$document->load($filename);

			$suiteNode	= $document->getElementsByTagName("suite")->item(0);
			$suiteName	= $suiteNode->getAttribute("name");
			$suiteList .= "\t\t\t\t<input class=\"$package $package-radio\" type=\"radio\" name=\"$actionSuite\" value=\"$suite\" />\n\t\t\t\t<p class=\"$package\">$suiteName</p>\n";
		}


		return <<<HTML
		<form class="$package-form" action="$package.php" method="get">
			<fieldset class="$package-fieldset">
				<h3>Choose test suite to run:</h3>
$suiteList
			</fieldset>
			<fieldset class="$package-fieldset">
				<input id="$package-run-tests" value="Run tests"
					type		=	"submit"
					class		=	"$package-button $package-buttonstate-0"
				/>
				<div style="float:left;margin:14px 0 0 8px;">
					<input class="$package $package-checkbox" type="checkbox" name = "$actionDummy" value="true" />
					<p class="$package">Dummy run</p>
				</div>
			</fieldset>
		</form>
HTML;
	}

	public static /*.void.*/ function getControlPanel() {self::sendContent(self::htmlControlPanel(), self::PACKAGE);}

//	---------------------------------------------------------------------------
	private static /*.string.*/ function htmlCSS() {
		$package	= self::PACKAGE;

		eval('$css = "' . addslashes(file_get_contents("$package.css")) . "\";");
		return $css;
	}

	public static /*.void.*/ function getCSS() {self::sendContent(self::htmlCSS(), 'CSS', 'text/css');}

//	---------------------------------------------------------------------------
	private static /*.string.*/ function htmlJavascript() {
		$package			= self::PACKAGE;
		$componentHeader	= self::componentHeader();
		$URL				= self::thisURL();

		$actionCSS			= self::ACTION_CSS;
		$actionParse		= self::ACTION_PARSE;

		eval('$js = "' . file_get_contents("$package.js") . "\";");
		return $js;
	}

	public static /*.void.*/ function getJavascript() {self::sendContent(self::htmlJavascript(), 'Javascript', 'text/javascript');	}

//	---------------------------------------------------------------------------
	private static /*.string.*/ function htmlAddScript() {
		$package			= self::PACKAGE;
		$actionJavascript	= self::ACTION_JAVASCRIPT;
		$URL				= self::thisURL();

		return <<<HTML
	<script type="text/javascript">
		if (typeof C_{$package} === 'undefined') {
			var {$package}_node	= document.createElement('script');
			{$package}_node.type	= 'text/javascript';
			{$package}_node.src	= '$URL?$actionJavascript';
			document.getElementsByTagName('head')[0].appendChild({$package}_node);
		}
	</script>
HTML;
	}

	public static /*.void.*/ function addScript() {self::sendContent(self::htmlAddScript(), 'addScript');}

//	---------------------------------------------------------------------------
	private static /*.string.*/ function htmlContainer($action = self::ACTION_PARSE) {
		$package			= self::PACKAGE;
		$divId				= self::getDivId($action);
		$addScript			= self::htmlAddScript();

		return <<<HTML
	<div id="$divId"></div>
$addScript
	<script type="text/javascript">oAjaxUnit_ajax.execute('$action');</script>
HTML;
	}

	public static /*.void.*/ function getContainer($action = self::ACTION_PARSE) {self::sendContent(self::htmlContainer($action), 'container');}

//	---------------------------------------------------------------------------
	private	static /*.string.*/	function htmlAbout() {
		$matches = /*.(array[int][int]string).*/ array();
		preg_match_all("!(?<=^ \\* @)(?:.)+(?=$)!m", file_get_contents(__FILE__, 0, NULL, -1, 1024), $matches);
		echo "<pre>\n";
		foreach ($matches[0] as $match) {echo htmlspecialchars($match);}
		echo "</pre>\n";
	}

	public	static /*.void.*/	function getAbout()	{self::sendContent(self::htmlAbout(), 'about');}

	private	static /*.string.*/	function htmlSourceCode()	{return (string) highlight_file(__FILE__, 1);}
	public	static /*.void.*/	function getSourceCode()	{self::sendContent(self::htmlSourceCode(), 'sourceCode');}

//	---------------------------------------------------------------------------
	private static /*.string.*/ function htmlPageTop() {
		$package			= self::PACKAGE;
		$actionCSS			= self::ACTION_CSS;
		$URL				= self::thisURL();

		return <<<HTML
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>ajaxUnit</title>
		<link type="text/css" rel="stylesheet" href="$URL?$actionCSS" title="ajaxUnit"/>
	</head>
	<body>
		<div id="$package">
HTML;
	}

	public	static /*.void.*/	function getPageTop()	{self::sendContent(self::htmlPageTop(), self::PACKAGE);}

	private static /*.string.*/ function htmlPageBottom() {
		return <<<HTML
		</div>
	</body>
</html>
HTML;
	}

	public	static /*.void.*/	function getPageBottom()	{self::sendContent(self::htmlPageBottom(), self::PACKAGE);}
}
//	End of class ajaxUnit
?>
