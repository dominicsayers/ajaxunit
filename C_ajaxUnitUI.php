<?php
// ---------------------------------------------------------------------------
//		ajaxUnitUI
// ---------------------------------------------------------------------------
/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxunit/
 * @version	0.19 - Now BSD licensed

 */
class ajaxUnitUI implements ajaxUnitAPI {
// ---------------------------------------------------------------------------
// Helper functions
// ---------------------------------------------------------------------------
	private static /*.string.*/ function thisURL() {
		$package = self::PACKAGE;

		// Find out the URL of this script so we can call it later
		$file = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? (string) str_replace("\\", '/' , __FILE__) : __FILE__;
		return dirname(substr($file, strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME']))) . "/$package.php";
	}

	private static /*.string.*/ function componentHeader() {return self::PACKAGE . "-component";}

	public static /*.void.*/ function sendContent(/*.string.*/ $content, /*.string.*/ $component, $contentType = '') {
		// Send headers first
		if (!headers_sent()) {
			$package	= self::PACKAGE;

//			$defaultType	= ($component	=== 'container')	? "text/html"	: "application/$package"; // Webkit oddity
			$defaultType	= "text/html";
			$contentType	= ($contentType	=== '')			? $defaultType	: $contentType;
			$component	= ($component	=== $package)		? $package	: "$package-$component";

			header("Cache-Control: no-cache");	// Damn fool Internet Explorer caching feature
			header("Expires: -1");			// Ditto
			header("Pragma: no-cache");		// Ditto
			header("Content-type: $contentType");
			header("Package: $package");
			header(self::componentHeader() . ": $component");
		}

		// Send content
		echo $content;
	}

	private static /*.string.*/ function getInstance(/*.string.*/ $action) {
		return self::PACKAGE;
	}

// ---------------------------------------------------------------------------
// UI features
// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlControlPanel() {
		$actionSuite	= self::ACTION_SUITE;
		$actionDummy	= self::ACTION_DUMMY;
		$package	= self::PACKAGE;
		$folder		= self::TESTS_FOLDER;
		$extension	= self::TESTS_EXTENSION;
		$suiteList	= "";

		foreach (glob("$folder/*.$extension") as $filename) {
			$suite		= basename($filename, '.' . self::TESTS_EXTENSION);
			$document	= new DOMDocument();
			$document->load($filename);

			$suiteNode	= $document->getElementsByTagName(self::ACTION_SUITE)->item(0);
			
			if ($suiteNode->hasAttribute(self::ATTRNAME_NAME)) {
				$suiteName	= $suiteNode->getAttribute(self::ATTRNAME_NAME);

				$suiteList	.= <<<HTML
				<input class="$package $package-radio" type="radio" name="$actionSuite" value="$suite" /> $suiteName<br />
HTML;
			}
		}

		if ($suiteList === '') {
			$html = "<h2>ajaxUnit</h2>\n<p>There are are no test suites in the <em>$folder</em> folder</p>\n";
		} else {
			$html = <<<HTML
		<h2>ajaxUnit</h2>
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
				<div style="float:left;margin:9px 0 0 8px;">
					<p style="margin:7px 0 0 3px;"><input class="$package $package-checkbox" type="checkbox" name = "$actionDummy" value="true" /> Dummy run</p>
				</div>
			</fieldset>
		</form>
HTML;
		}

		return $html;
	}

	public static /*.void.*/ function getControlPanel() {
		$html = self::htmlControlPanel();
		self::sendContent($html, self::PACKAGE);
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlCSS() {
		$package = self::PACKAGE;

		eval('$css = "' . addslashes(file_get_contents("$package.css")) . "\";");
		return $css;
	}

	public static /*.void.*/ function getCSS() {
		$html = self::htmlCSS();
		self::sendContent($html, 'CSS', 'text/css');
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlJavascript() {
		$package		= self::PACKAGE;
		$componentHeader	= self::componentHeader();
		$URL			= self::thisURL();

		$actionCSS		= self::ACTION_CSS;
		$actionParse		= self::ACTION_PARSE;

		$tagCheckbox		= self::TAGNAME_CHECKBOX;
		$tagClick		= self::TAGNAME_CLICK;
		$tagFormFill		= self::TAGNAME_FORMFILL;
		$tagOpen		= self::TAGNAME_OPEN;
		$tagLocation		= self::TAGNAME_LOCATION;
		$tagLogAppend		= self::TAGNAME_LOGAPPEND;
		$tagPost		= self::TAGNAME_POST;
		$tagRadio		= self::TAGNAME_RADIO;
		$tagText		= self::TAGNAME_TEXT;

		$attrID			= self::ATTRNAME_ID;
		$attrURL		= self::ATTRNAME_URL;

		eval('$js = "' . file_get_contents("$package.js") . "\";");
		return $js;
	}

	public static /*.void.*/ function getJavascript() {
		$html = self::htmlJavascript();
		self::sendContent($html, 'Javascript', 'text/javascript');
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlAddScript() {
//		$package		= self::PACKAGE;
		$actionJavascript	= self::ACTION_JAVASCRIPT;
		$URL			= self::thisURL();

/* This doesn't work in Webkit
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
*/

		return <<<HTML
	<script type="text/javascript">
		document.write(unescape('%3Cscript src="$URL?$actionJavascript" type="text/javascript"%3E%3C/script%3E'));
	</script>
HTML;
	}

	public static /*.void.*/ function addScript() {
		$html = self::htmlAddScript();
		self::sendContent($html, 'addScript');
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlContainer($action = self::ACTION_PARSE) {
		$package	= self::PACKAGE;
		$instance	= self::getInstance($action);
		$addScript	= self::htmlAddScript();

		return <<<HTML
	<div id="$instance"></div>
$addScript
	<script type="text/javascript">var obj = new C_ajaxUnit; obj.getResponse('$action');</script>
HTML;
	}

	public static /*.void.*/ function getContainer($action = self::ACTION_PARSE) {
		$html = self::htmlContainer($action);
		self::sendContent($html, 'container');
	}

// ---------------------------------------------------------------------------
	private	static /*.string.*/	function htmlAbout()	{
		$matches = /*.(array[int][int]string).*/ array();
		preg_match_all("!(?<=^ \\* @)(?:.)+(?=$)!m", file_get_contents(__FILE__, 0, NULL, -1, 1024), $matches);
		$html = "<pre>\n";
		foreach ($matches[0] as $match) {$html .= "    " . htmlspecialchars($match) . "\n";}
		$html .= "<hr />\n";
		$html .= "</pre>\n";
		return $html;
	}

	public	static /*.void.*/	function getAbout()		{
		$html = self::htmlAbout();
		self::sendContent($html, 'about', 'text/html');
	}

	private	static /*.string.*/	function htmlSourceCode()	{return (string) highlight_file(__FILE__, 1);}

	public	static /*.void.*/	function getSourceCode()	{
		$html = self::htmlSourceCode();
		self::sendContent($html, 'sourceCode', 'text/html');
	}

// ---------------------------------------------------------------------------
	public static /*.string.*/ function htmlPageTop() {
		$package		= self::PACKAGE;
		$actionCSS		= self::ACTION_CSS;
		$URL			= self::thisURL();

		return <<<HTML
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>$package</title>
		<link type="text/css" rel="stylesheet" href="$URL?$actionCSS" title="ajaxUnit"/>
	</head>
	<body>
		<div id="$package">
			<a id="top" href="#bottom">bottom &raquo;</a>
HTML;
	}

	public static /*.string.*/ function htmlPageBottom() {
		$package = self::PACKAGE;

		return <<<HTML
			<a id="bottom" href="#top">&laquo; top</a>
		</div>
		<script type="text/javascript">
			function {$package}_toggle_log(control, id) {
				if (control.innerHTML === '+') {
					control.innerHTML = '&ndash;';
					document.getElementById(id).style.display = 'block';
				} else {
					control.innerHTML = '+';
					document.getElementById(id).style.display = 'none';
				}
			}

			document.getElementById('bottom').scrollIntoView(true);
		</script>
	</body>
</html>
HTML;
	}

	public	static /*.void.*/ function getPageTop() {
		$html = self::htmlPageTop();
		self::sendContent($html, self::PACKAGE);
	}

	public	static /*.void.*/ function getPageBottom() {
		$html = self::htmlPageBottom();
		self::sendContent($html, self::PACKAGE);
	}

// ---------------------------------------------------------------------------
	public static /*.void.*/ function tidyLogFiles() {
		$folder		= self::LOG_FOLDER;
		$extension	= self::LOG_EXTENSION;

		foreach (glob("$folder/*.$extension") as $filename) {
			if (is_file($filename)) {
				$ageInHours = floor((time() - filemtime($filename))/(60*60));
				if ($ageInHours > self::LOG_MAXHOURS) {
//					echo "Deleting $filename\n";
					unlink($filename);
//				} else {
//					echo "Not deleting $filename because it's too recent\n";
				}
			}
		}
	}
}
// End of class ajaxUnit
?>
