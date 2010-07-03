<?php
/**
 * Testing PHP and javascript by controlling the interactions automatically
 * 
 * Copyright (c) 2008-2010, Dominic Sayers							<br>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     - Neither the name of Dominic Sayers nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic@sayers.cc>
 * @copyright	2008-2010 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxUnit/
 * @version	0.17.30 - Added 'ignore' tag - just put 'ignore' before any test element to ignore it
 */

// The quality of this code has been improved greatly by using PHPLint
// Copyright (c) 2009 Umberto Salsi
// This is free software; see the license for copying conditions.
// More info: http://www.icosaedro.it/phplint/
/*.
	require_module 'standard';
	require_module 'dom';
	require_module 'session';
	require_module 'pcre';
	require_module 'hash';
.*/

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');

/**
 * Common utility functions
 *
 * @package ajaxUnit
 * @version 1.14 (revision number of this common functions class only)
 */

interface I_ajaxUnit_common {
//	const	PACKAGE				= 'ajaxUnit',
//		VERSION				= '0.17', // Version 1.13: added
// Version 1.14: PACKAGE & VERSION now hard-coded by build process.

	const	HASH_FUNCTION			= 'SHA256',
		URL_SEPARATOR			= '/',

		// Behaviour settings for strleft()
		STRLEFT_MODE_NONE		= 0,
		STRLEFT_MODE_ALL		= 1,

		// Behaviour settings for getURL()
		URL_MODE_PROTOCOL		= 1,
		URL_MODE_HOST			= 2,
		URL_MODE_PORT			= 4,
		URL_MODE_PATH			= 8,
		URL_MODE_ALL			= 15,

		// Behaviour settings for getPackage()
//		PACKAGE_CASE_DEFAULT		= 0,
////		PACKAGE_CASE_LOWER		= 0,
//		PACKAGE_CASE_CAMEL		= 1,
//		PACKAGE_CASE_UPPER		= 2,
// Version 1.14: PACKAGE & VERSION now hard-coded by build process.

		// Extra GLOB constant for safe_glob()
		GLOB_NODIR			= 256,
		GLOB_PATH			= 512,
		GLOB_NODOTS			= 1024,
		GLOB_RECURSE			= 2048,

		// Email validation constants
		ISEMAIL_VALID			= 0,
		ISEMAIL_TOOLONG			= 1,
		ISEMAIL_NOAT			= 2,
		ISEMAIL_NOLOCALPART		= 3,
		ISEMAIL_NODOMAIN		= 4,
		ISEMAIL_ZEROLENGTHELEMENT	= 5,
		ISEMAIL_BADCOMMENT_START	= 6,
		ISEMAIL_BADCOMMENT_END		= 7,
		ISEMAIL_UNESCAPEDDELIM		= 8,
		ISEMAIL_EMPTYELEMENT		= 9,
		ISEMAIL_UNESCAPEDSPECIAL	= 10,
		ISEMAIL_LOCALTOOLONG		= 11,
		ISEMAIL_IPV4BADPREFIX		= 12,
		ISEMAIL_IPV6BADPREFIXMIXED	= 13,
		ISEMAIL_IPV6BADPREFIX		= 14,
		ISEMAIL_IPV6GROUPCOUNT		= 15,
		ISEMAIL_IPV6DOUBLEDOUBLECOLON	= 16,
		ISEMAIL_IPV6BADCHAR		= 17,
		ISEMAIL_IPV6TOOMANYGROUPS	= 18,
		ISEMAIL_TLD			= 19,
		ISEMAIL_DOMAINEMPTYELEMENT	= 20,
		ISEMAIL_DOMAINELEMENTTOOLONG	= 21,
		ISEMAIL_DOMAINBADCHAR		= 22,
		ISEMAIL_DOMAINTOOLONG		= 23,
		ISEMAIL_TLDNUMERIC		= 24,
		ISEMAIL_DOMAINNOTFOUND		= 25;
//		ISEMAIL_NOTDEFINED		= 99;

	// Basic utility functions
	public static /*.string.*/			function strleft(/*.string.*/ $haystack, /*.string.*/ $needle);
	public static /*.mixed.*/			function getInnerHTML(/*.string.*/ $html, /*.string.*/ $tag);
	public static /*.array[string][string]string.*/	function meta_to_array(/*.string.*/ $html);
	public static /*.string.*/			function var_dump_to_HTML(/*.string.*/ $var_dump, $offset = 0);
	public static /*.string.*/			function array_to_HTML(/*.array[]mixed.*/ $source = NULL);

	// Environment functions
//	public static /*.string.*/			function getPackage($mode = self::PACKAGE_CASE_DEFAULT); // Version 1.14: PACKAGE & VERSION now hard-coded by build process.
	public static /*.string.*/			function getURL($mode = self::URL_MODE_PATH, $filename = '');
	public static /*.string.*/			function docBlock_to_HTML(/*.string.*/ $php);

	// File system functions
	public static /*.mixed.*/			function safe_glob(/*.string.*/ $pattern, /*.int.*/ $flags = 0);
	public static /*.string.*/			function getFileContents(/*.string.*/ $filename, /*.int.*/ $flags = 0, /*.object.*/ $context = NULL, /*.int.*/ $offset = -1, /*.int.*/ $maxLen = -1);
	public static /*.string.*/			function findIndexFile(/*.string.*/ $folder);
	public static /*.string.*/			function findTarget(/*.string.*/ $target);

	// Data functions
	public static /*.string.*/			function makeId();
	public static /*.string.*/			function makeUniqueKey(/*.string.*/ $id);
	public static /*.string.*/			function mt_shuffle(/*.string.*/ $str, /*.int.*/ $seed = 0);
//	public static /*.void.*/			function mt_shuffle_array(/*.array.*/ &$arr, /*.int.*/ $seed = 0);
	public static /*.string.*/			function prkg(/*.int.*/ $index, /*.int.*/ $length = 6, /*.int.*/ $base = 34, /*.int.*/ $seed = 0);

	// Validation functions
//	public static /*.boolean.*/			function is_email(/*.string.*/ $email, $checkDNS = false);
	public static /*.mixed.*/			function is_email(/*.string.*/ $email, $checkDNS = false, $diagnose = false); // New parameters from version 1.8
}

/**
 * Common utility functions
 */
abstract class ajaxUnit_common implements I_ajaxUnit_common {
/**
 * Return the beginning of a string, up to but not including the search term.
 *
 * @param string $haystack The string containing the search term
 * @param string $needle The end point of the returned string. In other words, if <var>needle</var> is found then the begging of <var>haystack</var> is returned up to the character before <needle>.
 * @param int $mode If <var>needle</var> is not found then <pre>FALSE</pre> will be returned. */
	public static /*.string.*/ function strleft(/*.string.*/ $haystack, /*.string.*/ $needle, /*.int.*/ $mode = self::STRLEFT_MODE_NONE) {
		$posNeedle = strpos($haystack, $needle);

		if ($posNeedle === false) {
			if ($mode === self::STRLEFT_MODE_ALL)
				return $haystack;
			else
				return (string) $posNeedle;
		} else
			return substr($haystack, 0, $posNeedle);
	}

/**
 * Return the contents of an HTML element, the first one matching the <var>tag</var> parameter.
 *
 * @param string $html The string containing the html to be searched
 * @param string $tag The type of element to search for. The contents of first matching element will be returned. If the element doesn't exist then <var>false</var> is returned.
 */
	public static /*.mixed.*/ function getInnerHTML(/*.string.*/ $html, /*.string.*/ $tag) {
		$pos_tag_open_start	= stripos($html, "<$tag")				; if ($pos_tag_open_start	=== false) return false;
		$pos_tag_open_end	= strpos($html, '>',		$pos_tag_open_start)	; if ($pos_tag_open_end		=== false) return false;
		$pos_tag_close		= stripos($html, "</$tag>",	$pos_tag_open_end)	; if ($pos_tag_close		=== false) return false;
		return substr($html, $pos_tag_open_end + 1, $pos_tag_close - $pos_tag_open_end - 1);
	}

/**
 * Return the <var>meta</var> tags from an HTML document as an array.
 *
 * The array returned will have a 'key' element which is an array of name/value pairs representing all the metadata
 * from the HTML document. If there are any <var>name</var> or <var>http-equiv</var> meta elements
 * these will be in their own sub-array. The 'key' sub-array combines all meta tags.
 *
 * Qualifying attributes such as <var>lang</var> and <var>scheme</var> have their own sub-arrays with the same key
 * as the main sub-array.
 *
 * Here are some example meta tags:
 *
 * <pre>
 * <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
 * <meta name="description" content="Free Web tutorials" />
 * <meta name="keywords" content="HTML,CSS,XML,JavaScript" />
 * <meta name="author" content="Hege Refsnes" />
 * <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" />
 * <META NAME="ROBOTS" CONTENT="NOYDIR">
 * <META NAME="Slurp" CONTENT="NOYDIR">
 * <META name="author" content="John Doe">
 *   <META name ="copyright" content="&copy; 1997 Acme Corp.">
 *   <META name= "keywords" content="corporate,guidelines,cataloging">
 *   <META name = "date" content="1994-11-06T08:49:37+00:00">
 *       <meta name="DC.title" lang="en" content="Services to Government" >
 *     <meta name="DCTERMS.modified" scheme="XSD.date" content="2007-07-22" >
 * <META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 * <META name="geo.position" content="26.367559;-80.12172">
 * <META name="geo.region" content="US-FL">
 * <META name="geo.placename" content="Boca Raton, FL">
 * <META name="ICBM" content="26.367559, -80.12172">
 * <META name="DC.title" content="THE NAME OF YOUR SITE">
 * </pre>
 *
 * Here is a dump of the returned array:
 *
 * <pre>
 * array (
 *   'key' => 
 *   array (
 *     'Content-Type' => 'text/html; charset=iso-8859-1',
 *     'description' => 'Free Web tutorials',
 *     'keywords' => 'corporate,guidelines,cataloging',
 *     'author' => 'John Doe',
 *     'ROBOTS' => 'NOYDIR',
 *     'Slurp' => 'NOYDIR',
 *     'copyright' => '&copy; 1997 Acme Corp.',
 *     'date' => '1994-11-06T08:49:37+00:00',
 *     'DC.title' => 'THE NAME OF YOUR SITE',
 *     'DCTERMS.modified' => '2007-07-22',
 *     'geo.position' => '26.367559;-80.12172',
 *     'geo.region' => 'US-FL',
 *     'geo.placename' => 'Boca Raton, FL',
 *     'ICBM' => '26.367559, -80.12172',
 *   ),
 *   'http-equiv' => 
 *   array (
 *     'Content-Type' => 'text/html; charset=iso-8859-1',
 *   ),
 *   'name' => 
 *   array (
 *     'description' => 'Free Web tutorials',
 *     'keywords' => 'corporate,guidelines,cataloging',
 *     'author' => 'John Doe',
 *     'ROBOTS' => 'NOYDIR',
 *     'Slurp' => 'NOYDIR',
 *     'copyright' => '&copy; 1997 Acme Corp.',
 *     'date' => '1994-11-06T08:49:37+00:00',
 *     'DC.title' => 'THE NAME OF YOUR SITE',
 *     'DCTERMS.modified' => '2007-07-22',
 *     'geo.position' => '26.367559;-80.12172',
 *     'geo.region' => 'US-FL',
 *     'geo.placename' => 'Boca Raton, FL',
 *     'ICBM' => '26.367559, -80.12172',
 *   ),
 *   'lang' => 
 *   array (
 *     'DC.title' => 'en',
 *   ),
 *   'scheme' => 
 *   array (
 *     'DCTERMS.modified' => 'XSD.date',
 *   ),
 * </pre>
 *
 * Note how repeated tags cause the previous value to be overwritten in the resulting array
 * (for example the <var>Content-Type</var> and <var>keywords</var> tags appear twice but the
 * final array only has one element for each - the lowest one in the original list).
 *
 * @param string $html The string containing the html to be parsed
 */
	public static /*.array[string][string]string.*/ function meta_to_array(/*.string.*/ $html) {
		$keyAttributes	= array('name', 'http-equiv', 'charset', 'itemprop');
		$tags		= /*.(array[int][int]string).*/ array();
		$query		= '?';

		preg_match_all("|<meta.+/$query>|i", $html, $tags);

		$meta		= /*.(array[string][string]string).*/ array();
		$key_type	= '';
		$key		= '';
		$content	= '';

		foreach ($tags[0] as $tag) {
			$attributes	= array();
			$wip		= /*.(array[string]string).*/ array();

			preg_match_all('|\\s(\\S+?)\\s*=\\s*"(.*?)"|', $tag, $attributes);


			unset($key_type);
			unset($key);
			unset($content);

			for ($i = 0; $i < count($attributes[1]); $i++) {
				$attribute	= strtolower($attributes[1][$i]);
				$value		= $attributes[2][$i];

				if (in_array($attribute, $keyAttributes)) {
					$key_type		= $attribute;
					$key			= $value;
				} elseif ($attribute === 'content') {
					$content		= $value;
				} else {
					$wip[$attribute]	= $value;
				}
			}

			if (isset($key_type)) {
				$meta['key'][$key]	= $content;
				$meta[$key_type][$key]	= $content;

				foreach ($wip as $attribute => $value) {
					$meta[$attribute][$key] = $value;
				}
			}
		}

		return $meta;
	}

/**
 * Return the contents of a captured var_dump() as HTML. This is a recursive function.
 *
 * @param string $var_dump The captured <var>var_dump()</var>.
 * @param int $offset Whereabouts to start in the captured string. Defaults to the beginning of the string.
 */
	public static /*.string.*/ function var_dump_to_HTML(/*.string.*/ $var_dump, $offset = 0) {
		$indent	= '';
		$value	= '';

		while ((boolean) ($posStart = strpos($var_dump, '(', $offset))) {
			$type	= substr($var_dump, $offset, $posStart - $offset);
			$nests	= strrpos($type, ' ');

			if ($nests === false) $nests = 0; else $nests = intval(($nests + 1) / 2);

			$indent = str_pad('', $nests * 3, "\t");
			$type	= trim($type);
			$offset	= ++$posStart;
			$posEnd	= strpos($var_dump, ')', $offset); if ($posEnd === false) break;
			$offset	= $posEnd + 1;
			$value	= substr($var_dump, $posStart, $posEnd - $posStart);

			switch ($type) {
			case 'string':
				$length	= (int) $value;
				$value	= '<pre>' . htmlspecialchars(substr($var_dump, $offset + 2, $length)) . '</pre>';
				$offset	+= $length + 3;
				break;
			case 'array':
				$elementTellTale	= "\n" . str_pad('', ($nests + 1) * 2) . '['; // Not perfect but the best var_dump will allow
				$elementCount		= (int) $value;
				$value			= "\n$indent<table>\n";

				for ($i = 1; $i <= $elementCount; $i++) {
					$posStart	= strpos($var_dump, $elementTellTale, $offset);	if ($posStart	=== false) break;
					$posStart	+= ($nests + 1) * 2 + 2;
					$offset		= $posStart;
					$posEnd		= strpos($var_dump, ']', $offset);		if ($posEnd	=== false) break;
					$offset		= $posEnd + 4; // Read past the =>\n
					$key		= substr($var_dump, $posStart, $posEnd - $posStart);

					if (!is_numeric($key)) $key = substr($key, 1, strlen($key) - 2); // Strip off the double quotes

					$search		= ($i === $elementCount) ? "\n" . str_pad('', $nests * 2) . '}' : $elementTellTale;
					$posStart	= strpos($var_dump, $search, $offset);		if ($posStart	=== false) break;
					$next		= substr($var_dump, $offset, $posStart - $offset);
					$offset		= $posStart;
					$inner_value	= self::var_dump_to_HTML($next);

					$value		.= "$indent\t<tr>\n";
					$value		.= "$indent\t\t<td>$key</td>\n";
					$value		.= "$indent\t\t<td>$inner_value</td>\n";
					$value		.= "$indent\t</tr>\n";
				}

				$value			.= "$indent</table>\n";
				break;
			case 'object':
				if ($value === '__PHP_Incomplete_Class') {
					$posStart	= strpos($var_dump, '(', $offset);	if ($posStart	=== false) break;
					$offset		= ++$posStart;
echo "$indent Corrected \$offset = $offset\n"; // debug
					$posEnd		= strpos($var_dump, ')', $offset);	if ($posEnd	=== false) break;
					$offset		= $posEnd + 1;
echo "$indent Corrected \$offset = $offset\n"; // debug
					$value		= substr($var_dump, $posStart, $posEnd - $posStart);
				}

				break;
			default:
				break;
			}

		}

		return $value;
	}

/**
 * Return the contents of an array as HTML (like <var>var_dump()</var> on steroids), including object members
 *
 * @param mixed $source The array to export. If it's empty then $GLOBALS is exported.
 */
	public static /*.string.*/ function array_to_HTML(/*.array[]mixed.*/ $source = NULL) {
// If no specific array is passed we will export $GLOBALS to HTML
// Unfortunately, this means we have to use var_dump() because var_export() barfs on $GLOBALS
// In fact var_dump is easier to walk than var_export anyway so this is no bad thing.

		ob_start();
		if (empty($source)) var_dump($GLOBALS); else var_dump($source);
		$var_dump = ob_get_clean();

		return self::var_dump_to_HTML($var_dump);
	}

///**
// * Return the name of this package. By default this will be in lower case for use in Javascript tags etc.
// *
// * @param int $mode One of the <var>PACKAGE_CASE_XXX</var> predefined constants defined in this class
// */
//	public static /*.string.*/ function getPackage($mode = self::PACKAGE_CASE_DEFAULT) {
//		switch ($mode) {
//		case self::PACKAGE_CASE_CAMEL:
//			$package = self::PACKAGE;
//			break;
//		case self::PACKAGE_CASE_UPPER:
//			$package = strtoupper(self::PACKAGE);
//			break;
//		default:
//			$package = strtolower(self::PACKAGE);
//			break;
//		}
//
//		return $package;
//	}

/**
 * Return all or part of the URL of the current script.
 *
 * @param int $mode One of the <var>URL_MODE_XXX</var> predefined constants defined in this class
 * @param string $filename If this is not empty then the returned script name is forced to be this filename.
 */
	public static /*.string.*/ function getURL($mode = self::URL_MODE_PATH, $filename = 'ajaxUnit') {
// Version 1.14: PACKAGE & VERSION now hard-coded by build process.
		$portInteger = array_key_exists('SERVER_PORT', $_SERVER) ? (int) $_SERVER['SERVER_PORT'] : 0;

		if (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] === 'on') {
			$protocolType = 'https';
		} else if (array_key_exists('SERVER_PROTOCOL', $_SERVER)) {
			$protocolType = strtolower(self::strleft($_SERVER['SERVER_PROTOCOL'], self::URL_SEPARATOR, self::STRLEFT_MODE_ALL));
		} else if ($portInteger === 443) {
			$protocolType = 'https';
		} else {
			$protocolType = 'http';
		}

		if ($portInteger === 0) $portInteger = ($protocolType === 'https') ? 443 : 80;

		// Protocol
		if ((boolean) ($mode & self::URL_MODE_PROTOCOL)) {
			$protocol = ($mode === self::URL_MODE_PROTOCOL) ? $protocolType : "$protocolType://";
		} else {
			$protocol = '';
		}

		// Host
		if ((boolean) ($mode & self::URL_MODE_HOST)) {
			$host = array_key_exists('HTTP_HOST', $_SERVER) ? self::strleft($_SERVER['HTTP_HOST'], ':', self::STRLEFT_MODE_ALL) : '';
		} else {
			$host = '';
		}

		// Port
		if ((boolean) ($mode & self::URL_MODE_PORT)) {
			$port = (string) $portInteger;

			if ($mode !== self::URL_MODE_PORT)
				$port = (($protocolType === 'http' && $portInteger === 80) || ($protocolType === 'https' && $portInteger === 443)) ? '' : ":$port";
		} else {
			$port = '';
		}

		// Path
		if ((boolean) ($mode & self::URL_MODE_PATH)) {
			$includePath	= __FILE__;
			$scriptPath	= realpath($_SERVER['SCRIPT_FILENAME']);

			if (DIRECTORY_SEPARATOR !== self::URL_SEPARATOR) {
				$includePath	= (string) str_replace(DIRECTORY_SEPARATOR, self::URL_SEPARATOR , $includePath);
				$scriptPath	= (string) str_replace(DIRECTORY_SEPARATOR, self::URL_SEPARATOR , $scriptPath);
			}

/*
echo "<pre>\n"; // debug
echo "\$_SERVER['SCRIPT_FILENAME'] = " . $_SERVER['SCRIPT_FILENAME'] . "\n"; // debug
echo "\$_SERVER['SCRIPT_NAME'] = " . $_SERVER['SCRIPT_NAME'] . "\n"; // debug
echo "dirname(\$_SERVER['SCRIPT_NAME']) = " . dirname($_SERVER['SCRIPT_NAME']) . "\n"; // debug
echo "\$includePath = $includePath\n"; // debug
echo "\$scriptPath = $scriptPath\n"; // debug
//echo self::array_to_HTML(); // debug
echo "</pre>\n"; // debug
*/

			$start	= strpos(strtolower($scriptPath), strtolower($_SERVER['SCRIPT_NAME']));
			$path	= ($start === false) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(substr($includePath, $start));
			$path	.= self::URL_SEPARATOR . $filename;
		} else {
			$path = '';
		}

		return $protocol . $host . $port . $path;
	}

/**
 * Convert a DocBlock to HTML (see http://java.sun.com/j2se/javadoc/writingdoccomments/index.html)
 *
 * @param string $docBlock Some PHP code containing a valid DocBlock.
 */
	public static /*.string.*/ function docBlock_to_HTML(/*.string.*/ $php) {
// Updated in version 1.12 (bug fixes and formatting)
//		$package	= self::getPackage(self::PACKAGE_CASE_CAMEL); // Version 1.14: PACKAGE & VERSION now hard-coded by build process.
		$eol		= "\r\n";
		$tagStart	= strpos($php, "/**$eol * ");

		if ($tagStart === false) return 'Development version';

		// Get summary and long description
		$tagStart	+= 8;
		$tagEnd		= strpos($php, $eol, $tagStart);
		$summary	= substr($php, $tagStart, $tagEnd - $tagStart);
		$tagStart	= $tagEnd + 7;
		$tagPos		= strpos($php, "$eol * @") + 2;
		$description	= substr($php, $tagStart, $tagPos - $tagStart - 7);
		$description	= (string) str_replace(' * ', '' , $description);

		// Get tags and values from DocBlock
		do {
			$tagStart	= $tagPos + 4;
			$tagEnd		= strpos($php, "\t", $tagStart);
			$tag		= substr($php, $tagStart, $tagEnd - $tagStart);
			$offset		= $tagEnd + 1;
			$tagPos		= strpos($php, $eol, $offset);
			$value		= htmlspecialchars(substr($php, $tagEnd + 1, $tagPos - $tagEnd - 1));
			$tagPos		= strpos($php, " * @", $offset);

//			$$tag		= htmlspecialchars($value); // The easy way. But PHPlint doesn't like it, so...

//			$package	= '';
//			$summary	= '';
//			$description	= '';

			switch ($tag) {
			case 'license':		$license	= $value; break;
			case 'author':		$author		= $value; break;
			case 'link':		$link		= $value; break;
			case 'version':		$version	= $value; break;
			case 'copyright':	$copyright	= $value; break;
			default:		$value		= $value;
			}
		} while ((boolean) $tagPos);

		// Add some links
		// 1. License
		if (isset($license) && (boolean) strpos($license, '://')) {
			$tagPos		= strpos($license, ' ');
			$license	= '<a href="' . substr($license, 0, $tagPos) . '">' . substr($license, $tagPos + 1) . '</a>';
		}

		// 2. Author
		if (isset($author) && preg_match('/&lt;.+@.+&gt;/', $author) > 0) {
			$tagStart	= strpos($author, '&lt;') + 4;
			$tagEnd		= strpos($author, '&gt;', $tagStart);
			$author		= '<a href="mailto:' . substr($author, $tagStart, $tagEnd - $tagStart) . '">' . substr($author, 0, $tagStart - 5) . '</a>';
		}

		// 3. Link
		if (isset($link) && (boolean) strpos($link, '://')) {
			$link		= '<a href="' . $link . '">' . $link . '</a>';
		}

		// Build the HTML
		$html = <<<HTML
	<h1>ajaxUnit</h1>
	<h2>$summary</h2>
	<pre>$description</pre>
	<hr />
	<table>

HTML;
// Version 1.14: PACKAGE & VERSION now hard-coded by build process.

		if (isset($version))	$html .= "\t\t<tr><td>Version</td><td>$version</td></tr>\n";
		if (isset($copyright))	$html .= "\t\t<tr><td>Copyright</td><td>$copyright</td></tr>\n";
		if (isset($license))	$html .= "\t\t<tr><td>License</td><td>$license</td></tr>\n";
		if (isset($author))	$html .= "\t\t<tr><td>Author</td><td>$author</td></tr>\n";
		if (isset($link))	$html .= "\t\t<tr><td>Link</td><td>$link</td></tr>\n";

		$html .= "\t</table>";
		return $html;
	}

/**
 * glob() replacement (in case glob() is disabled).
 *
 * Function glob() is prohibited on some server (probably in safe mode)
 * (Message "Warning: glob() has been disabled for security reasons in
 * (script) on line (line)") for security reasons as stated on:
 * http://seclists.org/fulldisclosure/2005/Sep/0001.html
 *
 * safe_glob() intends to replace glob() using readdir() & fnmatch() instead.
 * Supported flags: GLOB_MARK, GLOB_NOSORT, GLOB_ONLYDIR
 * Additional flags: GLOB_NODIR, GLOB_PATH, GLOB_NODOTS, GLOB_RECURSE
 * (these were not original glob() flags)
 * @author BigueNique AT yahoo DOT ca
 */
	public static /*.mixed.*/ function safe_glob(/*.string.*/ $pattern, /*.int.*/ $flags = 0) {
		$split	= explode('/', (string) str_replace('\\', '/', $pattern));
		$mask	= (string) array_pop($split);
		$path	= (count($split) === 0) ? '.' : implode('/', $split);
		$dir	= @opendir($path);

		if ($dir === false) return false;

		$glob	= /*.(array[int]).*/ array();

		do {
			$filename = readdir($dir);
			if ($filename === false) break;

			$is_dir	= is_dir("$path/$filename");
			$is_dot	= in_array($filename, array('.', '..'));

			// Recurse subdirectories (if GLOB_RECURSE is supplied)
			if ($is_dir && !$is_dot && (($flags & self::GLOB_RECURSE) !== 0)) {
				$sub_glob	= /*.(array[int]).*/ self::safe_glob($path.'/'.$filename.'/'.$mask,  $flags);
//					array_prepend($sub_glob, ((boolean) ($flags & self::GLOB_PATH) ? '' : $filename.'/'));
				$glob		= /*.(array[int]).*/ array_merge($glob, $sub_glob);
			}

			// Match file mask
			if (fnmatch($mask, $filename)) {
				if (	((($flags & GLOB_ONLYDIR) === 0)	|| $is_dir)
				&&	((($flags & self::GLOB_NODIR) === 0)	|| !$is_dir)
				&&	((($flags & self::GLOB_NODOTS) === 0)	|| !$is_dot)
				)
					$glob[] = (($flags & self::GLOB_PATH) !== 0 ? $path.'/' : '') . $filename . (($flags & GLOB_MARK) !== 0 ? '/' : '');
			}
		} while(true);

		closedir($dir);
		if (($flags & GLOB_NOSORT) === 0) sort($glob);

		return $glob;
	}

/**
 * Return file contents as a string. Fail silently if the file can't be opened.
 *
 * The parameters are the same as the built-in PHP function {@link http://www.php.net/file_get_contents file_get_contents}
 */
	public static /*.string.*/ function getFileContents(/*.string.*/ $filename, /*.int.*/ $flags = 0, /*.object.*/ $context = NULL, /*.int.*/ $offset = -1, /*.int.*/ $maxlen = -1) {
		// From the documentation of file_get_contents:
		// Note: The default value of maxlen is not actually -1; rather, it is an internal PHP value which means to copy the entire stream until end-of-file is reached. The only way to specify this default value is to leave it out of the parameter list.
		if ($maxlen === -1) {
			$contents = @file_get_contents($filename, $flags, $context, $offset);
		} else {
			$contents = @file_get_contents($filename, $flags, $context, $offset, $maxlen);
// version 1.9 - remembered the @s
		}

		if ($contents === false) $contents = '';
		return $contents;
	}

/**
 * Return the name of the index file (e.g. <var>index.php</var>) from a folder
 *
 * @param string $folder The folder to look for the index file. If not a folder or no index file can be found then an empty string is returned.
 */
	public static /*.string.*/ function findIndexFile(/*.string.*/ $folder) {
		if (!is_dir($folder)) return '';
		$filelist = array('index.php', 'index.pl', 'index.cgi', 'index.asp', 'index.shtml', 'index.html', 'index.htm', 'default.php', 'default.pl', 'default.cgi', 'default.asp', 'default.shtml', 'default.html', 'default.htm', 'home.php', 'home.pl', 'home.cgi', 'home.asp', 'home.shtml', 'home.html', 'home.htm');

		foreach ($filelist as $filename) {
			$target = $folder . DIRECTORY_SEPARATOR . $filename;
			if (is_file($target)) return $target;
		}

		return '';
	}

/**
 * Return the name of the target file from a string that might be a directory or just a basename without a suffix. If it's a directory then look for an index file in the directory.
 *
 * @param string $target The file to look for or folder to look in. If no file can be found then an empty string is returned.
 */
	public static /*.string.*/ function findTarget(/*.string.*/ $target) {
		// Is it actually a file? If so, look no further
		if (is_file($target)) return $target;

		// Added in version 1.7
		// Is it a basename? i.e. can we find $target.html or something?
		$suffixes = array('shtml', 'html', 'php', 'pl', 'cgi', 'asp', 'htm');

		foreach ($suffixes as $suffix) {
			$filename = "$target.$suffix";
			if (is_file($filename)) return $filename;
		}

		// Otherwise, let's assume it's a directory and try to find an index file in that directory
		return self::findIndexFile($target);
	}

/**
 * Make a unique ID based on the current date and time
 */
	public static /*.string.*/ function makeId() {
// Note could also try this: return md5(uniqid(mt_rand(), true));
		list($usec, $sec) = explode(" ", (string) microtime());
		return base_convert($sec, 10, 36) . base_convert((string) mt_rand(0, 35), 10, 36) . str_pad(base_convert(($usec * 1000000), 10, 36), 4, '_', STR_PAD_LEFT);
	}

/**
 * Make a unique hash key from a string (usually an ID)
 */
	public static /*.string.*/ function makeUniqueKey(/*.string.*/ $id) {
		return hash(self::HASH_FUNCTION, $_SERVER['REQUEST_TIME'] . $id);
	}

// Added in version 1.10
/**
 * Shuffle a string using the Mersenne Twist PRNG (can be deterministically seeded)
 *
 * @param string $str The string to be shuffled
 * @param int $seed The seed for the PRNG means this can be used to shuffle the string in the same order every time
 */
	public static /*.string.*/ function mt_shuffle(/*.string.*/ $str, /*.int.*/ $seed = 0) {
		$count	= strlen($str);
		$result	= $str;

		// Seed the RNG with a deterministic seed
		mt_srand($seed);

		// Shuffle the digits
		for ($element = $count - 1; $element >= 0; $element--) {
			$shuffle		= mt_rand(0, $element);

			$value			= $result[$shuffle];
//			$result[$shuffle]	= $result[$element];
//			$result[$element]	= $value;		// PHPLint doesn't like this syntax, so...

			substr_replace($result, $result[$element], $shuffle, 1);
			substr_replace($result, $value, $element, 1);
		}

		return $result;
	}

// Added in version 1.10
/**
 * Shuffle an array using the Mersenne Twist PRNG (can be deterministically seeded)
 *
 */
	public static /*.void.*/ function mt_shuffle_array(/*.array.*/ &$arr, /*.int.*/ $seed = 0) {
		$count	= count($arr);
		$keys	= array_keys($arr);

		// Seed the RNG with a deterministic seed
		mt_srand($seed);

		// Shuffle the digits
		for ($element = $count - 1; $element >= 0; $element--) {
			$shuffle		= mt_rand(0, $element);

			$key_shuffle		= $keys[$shuffle];
			$key_element		= $keys[$element];

			$value			= $arr[$key_shuffle];
			$arr[$key_shuffle]	= $arr[$key_element];
			$arr[$key_element]	= $value;
		}
	}

// Added in version 1.10
/**
 * The Pseudo-Random Key Generator returns an apparently random key of
 * length $length and comprising digits specified by $base. However, for
 * a given seed this key depends only on $index.
 * 
 * In other words, if you keep the $seed constant then you'll get a
 * non-repeating series of keys as you increment $index but these keys
 * will be returned in a pseudo-random order.
 * 
 * The $seed parameter is available in case you want your series of keys
 * to come out in a different order to mine.
 * 
 * Comparison of bases:
 * <pre>
 * +------+----------------+---------------------------------------------+
 * |      | Max keys       |                                             |
 * |      | (based on      |                                             |
 * | Base | $length = 6)   | Notes                                       |
 * +------+----------------+---------------------------------------------+
 * | 2    | 64             | Uses digits 0 and 1 only                    |
 * | 8    | 262,144        | Uses digits 0-7 only                        |
 * | 10   | 1,000,000      | Good choice if you need integer keys        |
 * | 16   | 16,777,216     | Good choice if you need hex keys            |
 * | 26   | 308,915,776    | Good choice if you need purely alphabetic   |
 * |      |                | keys (case-insensitive)                     |
 * | 32   | 1,073,741,824  | Smallest base that gives you a billion keys |
 * |      |                | in 6 digits                                 |
 * | 34   | 1,544,804,416  | (default) Good choice if you want to        |
 * |      |                | maximise your keyset size but still         |
 * |      |                | generate keys that are unambiguous and      |
 * |      |                | case-insensitive (no confusion between 1, I |
 * |      |                | and l for instance)                         |
 * | 36   | 2,176,782,336  | Same digits as base-34 but includes 'O' and |
 * |      |                | 'I' (may be confused with '0' and '1' in    |
 * |      |                | some fonts)                                 |
 * | 52   | 19,770,609,664 | Good choice if you need purely alphabetic   |
 * |      |                | keys (case-sensitive)                       |
 * | 62   | 56,800,235,584 | Same digits as other URL shorteners         |
 * |      |                | (e.g bit.ly)                                |
 * | 66   | 82,653,950,016 | Includes all legal URI characters           |
 * |      |                | (http://tools.ietf.org/html/rfc3986)        |
 * |      |                | This is the maximum size of keyset that     |
 * |      |                | results in a legal URL for a given length   |
 * |      |                | of key.                                     |
 * +------+----------------+---------------------------------------------+
 * </pre>
 * @param int $index The number to be converted into a key
 * @param int $length The length of key to be returned. Along with the $base this determines the size of the keyset
 * @param int $base The number of distinct characters that can be included in the key to be returned. Along with the $length this determines the size of the keyset
 * @param int $seed The seed for the PRNG means this can be used to generate keys in the same sequence every time
 */
	public static /*.string.*/ function prkg($index, $length = 6, $base = 34, $seed = 0) {
		/*
		To return a pseudo-random key, we will take $index, convert it
		to base $base, then randomize the order of the digits. In
		addition we will give each digit a random offset.

		All the randomization operations are deterministic (based on
		$seed) so each time the function is called we will get the
		same shuffling of digits and the same offset for each digit.
		*/
		$digits	= '0123456789ABCDEFGHJKLMNPQRSTUVWXYZIOabcdefghijklmnopqrstuvwxyz-._~';
		//					    ^ base 34 recommended

		// Is $base in range?
		if ($base < 2)			{die('Base must be greater than or equal to 2');}
		if ($base > 66)			{die('Base must be less than or equal to 66');}

		// Is $length in range?
		if ($length < 1)		{die('Length must be greater than or equal to 1');}
		// Max length depends on arithmetic functions of PHP

		// Is $index in range?
		$max_index = (int) pow($base, $length);
		if ($index < 0)			{die('Index must be greater than or equal to 0');}
		if ($index > $max_index)	{die('Index must be less than or equal to ' . $max_index);}

		// Seed the RNG with a deterministic seed
		mt_srand($seed);

		// Convert to $base
		$remainder	= $index;
		$digit		= 0;
		$result		= '';

		while ($digit < $length) {
			$unit		= (int) pow($base, $length - $digit++ - 1);
			$value		= (int) floor($remainder / $unit);
			$remainder	= $remainder - ($value * $unit);

			// Shift the digit
			$value		= ($value + mt_rand(0, $base - 1)) % $base;
			$result		.= $digits[$value];
		}

		// Shuffle the digits
		$result	= self::mt_shuffle($result, $seed);

		// We're done
		return $result;
	}

// Updated in version 1.8
/**
 * Check that an email address conforms to RFC5322 and other RFCs
 *
 * @param boolean $checkDNS If true then a DNS check for A and MX records will be made
 * @param boolean $diagnose If true then return an integer error number rather than true or false
 */
	public static /*.mixed.*/ function is_email (/*.string.*/ $email, $checkDNS = false, $diagnose = false) {
		// Check that $email is a valid address. Read the following RFCs to understand the constraints:
		// 	(http://tools.ietf.org/html/rfc5322)
		// 	(http://tools.ietf.org/html/rfc3696)
		// 	(http://tools.ietf.org/html/rfc5321)
		// 	(http://tools.ietf.org/html/rfc4291#section-2.2)
		// 	(http://tools.ietf.org/html/rfc1123#section-2.1)

		// the upper limit on address lengths should normally be considered to be 256
		// 	(http://www.rfc-editor.org/errata_search.php?rfc=3696)
		// 	NB I think John Klensin is misreading RFC 5321 and the the limit should actually be 254
		// 	However, I will stick to the published number until it is changed.
		//
		// The maximum total length of a reverse-path or forward-path is 256
		// characters (including the punctuation and element separators)
		// 	(http://tools.ietf.org/html/rfc5321#section-4.5.3.1.3)
		$emailLength = strlen($email);
		if ($emailLength > 256)			if ($diagnose) return self::ISEMAIL_TOOLONG; else return false;	// Too long

		// Contemporary email addresses consist of a "local part" separated from
		// a "domain part" (a fully-qualified domain name) by an at-sign ("@").
		// 	(http://tools.ietf.org/html/rfc3696#section-3)
		$atIndex = strrpos($email,'@');

		if ($atIndex === false)			if ($diagnose) return self::ISEMAIL_NOAT; else return false;	// No at-sign
		if ($atIndex === 0)			if ($diagnose) return self::ISEMAIL_NOLOCALPART; else return false;	// No local part
		if ($atIndex === $emailLength - 1)	if ($diagnose) return self::ISEMAIL_NODOMAIN; else return false;	// No domain part
	// revision 1.14: Length test bug suggested by Andrew Campbell of Gloucester, MA

		// Sanitize comments
		// - remove nested comments, quotes and dots in comments
		// - remove parentheses and dots from quoted strings
		$braceDepth	= 0;
		$inQuote	= false;
		$escapeThisChar	= false;

		for ($i = 0; $i < $emailLength; ++$i) {
			$char = $email[$i];
			$replaceChar = false;

			if ($char === '\\') {
				$escapeThisChar = !$escapeThisChar;	// Escape the next character?
			} else {
				switch ($char) {
				case '(':
					if ($escapeThisChar) {
						$replaceChar = true;
					} else {
						if ($inQuote) {
							$replaceChar = true;
						} else {
							if ($braceDepth++ > 0) $replaceChar = true;	// Increment brace depth
						}
					}

					break;
				case ')':
					if ($escapeThisChar) {
						$replaceChar = true;
					} else {
						if ($inQuote) {
							$replaceChar = true;
						} else {
							if (--$braceDepth > 0) $replaceChar = true;	// Decrement brace depth
							if ($braceDepth < 0) $braceDepth = 0;
						}
					}

					break;
				case '"':
					if ($escapeThisChar) {
						$replaceChar = true;
					} else {
						if ($braceDepth === 0) {
							$inQuote = !$inQuote;	// Are we inside a quoted string?
						} else {
							$replaceChar = true;
						}
					}

					break;
				case '.':	// Dots don't help us either
					if ($escapeThisChar) {
						$replaceChar = true;
					} else {
						if ($braceDepth > 0) $replaceChar = true;
					}

					break;
				default:
				}

				$escapeThisChar = false;
	//			if ($replaceChar) $email[$i] = 'x';	// Replace the offending character with something harmless
	// revision 1.12: Line above replaced because PHPLint doesn't like that syntax
				if ($replaceChar) $email = (string) substr_replace($email, 'x', $i, 1);	// Replace the offending character with something harmless
			}
		}

		$localPart	= substr($email, 0, $atIndex);
		$domain		= substr($email, $atIndex + 1);
		$FWS		= "(?:(?:(?:[ \\t]*(?:\\r\\n))?[ \\t]+)|(?:[ \\t]+(?:(?:\\r\\n)[ \\t]+)*))";	// Folding white space
		// Let's check the local part for RFC compliance...
		//
		// local-part      =       dot-atom / quoted-string / obs-local-part
		// obs-local-part  =       word *("." word)
		// 	(http://tools.ietf.org/html/rfc5322#section-3.4.1)
		//
		// Problem: need to distinguish between "first.last" and "first"."last"
		// (i.e. one element or two). And I suck at regexes.
		$dotArray	= /*. (array[int]string) .*/ preg_split('/\\.(?=(?:[^\\"]*\\"[^\\"]*\\")*(?![^\\"]*\\"))/m', $localPart);
		$partLength	= 0;

		foreach ($dotArray as $element) {
			// Remove any leading or trailing FWS
			$element	= preg_replace("/^$FWS|$FWS\$/", '', $element);
			$elementLength	= strlen($element);

			if ($elementLength === 0)								if ($diagnose) return self::ISEMAIL_ZEROLENGTHELEMENT; else return false;	// Can't have empty element (consecutive dots or dots at the start or end)
	// revision 1.15: Speed up the test and get rid of "unitialized string offset" notices from PHP

			// We need to remove any valid comments (i.e. those at the start or end of the element)
			if ($element[0] === '(') {
				$indexBrace = strpos($element, ')');
				if ($indexBrace !== false) {
					if (preg_match('/(?<!\\\\)[\\(\\)]/', substr($element, 1, $indexBrace - 1)) > 0) {
														if ($diagnose) return self::ISEMAIL_BADCOMMENT_START; else return false;	// Illegal characters in comment
					}
					$element	= substr($element, $indexBrace + 1, $elementLength - $indexBrace - 1);
					$elementLength	= strlen($element);
				}
			}

			if ($element[$elementLength - 1] === ')') {
				$indexBrace = strrpos($element, '(');
				if ($indexBrace !== false) {
					if (preg_match('/(?<!\\\\)(?:[\\(\\)])/', substr($element, $indexBrace + 1, $elementLength - $indexBrace - 2)) > 0) {
														if ($diagnose) return self::ISEMAIL_BADCOMMENT_END; else return false;	// Illegal characters in comment
					}
					$element	= substr($element, 0, $indexBrace);
					$elementLength	= strlen($element);
				}
			}

			// Remove any leading or trailing FWS around the element (inside any comments)
			$element = preg_replace("/^$FWS|$FWS\$/", '', $element);

			// What's left counts towards the maximum length for this part
			if ($partLength > 0) $partLength++;	// for the dot
			$partLength += strlen($element);

			// Each dot-delimited component can be an atom or a quoted string
			// (because of the obs-local-part provision)
			if (preg_match('/^"(?:.)*"$/s', $element) > 0) {
				// Quoted-string tests:
				//
				// Remove any FWS
				$element = preg_replace("/(?<!\\\\)$FWS/", '', $element);
				// My regex skillz aren't up to distinguishing between \" \\" \\\" \\\\" etc.
				// So remove all \\ from the string first...
				$element = preg_replace('/\\\\\\\\/', ' ', $element);
				if (preg_match('/(?<!\\\\|^)["\\r\\n\\x00](?!$)|\\\\"$|""/', $element) > 0)	if ($diagnose) return self::ISEMAIL_UNESCAPEDDELIM; else return false;	// ", CR, LF and NUL must be escaped, "" is too short
			} else {
				// Unquoted string tests:
				//
				// Period (".") may...appear, but may not be used to start or end the
				// local part, nor may two or more consecutive periods appear.
				// 	(http://tools.ietf.org/html/rfc3696#section-3)
				//
				// A zero-length element implies a period at the beginning or end of the
				// local part, or two periods together. Either way it's not allowed.
				if ($element === '')								if ($diagnose) return self::ISEMAIL_EMPTYELEMENT; else return false;	// Dots in wrong place

				// Any ASCII graphic (printing) character other than the
				// at-sign ("@"), backslash, double quote, comma, or square brackets may
				// appear without quoting.  If any of that list of excluded characters
				// are to appear, they must be quoted
				// 	(http://tools.ietf.org/html/rfc3696#section-3)
				//
				// Any excluded characters? i.e. 0x00-0x20, (, ), <, >, [, ], :, ;, @, \, comma, period, "
				if (preg_match('/[\\x00-\\x20\\(\\)<>\\[\\]:;@\\\\,\\."]/', $element) > 0)	if ($diagnose) return self::ISEMAIL_UNESCAPEDSPECIAL; else return false;	// These characters must be in a quoted string
			}
		}

		if ($partLength > 64) if ($diagnose) return self::ISEMAIL_LOCALTOOLONG; else return false;	// Local part must be 64 characters or less

		// Now let's check the domain part...

		// The domain name can also be replaced by an IP address in square brackets
		// 	(http://tools.ietf.org/html/rfc3696#section-3)
		// 	(http://tools.ietf.org/html/rfc5321#section-4.1.3)
		// 	(http://tools.ietf.org/html/rfc4291#section-2.2)
		if (preg_match('/^\\[(.)+]$/', $domain) === 1) {
			// It's an address-literal
			$addressLiteral = substr($domain, 1, strlen($domain) - 2);
			$matchesIP	= array();

			// Extract IPv4 part from the end of the address-literal (if there is one)
			if (preg_match('/\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $addressLiteral, $matchesIP) > 0) {
				$index = strrpos($addressLiteral, $matchesIP[0]);

				if ($index === 0) {
					// Nothing there except a valid IPv4 address, so...
					if ($diagnose) return self::ISEMAIL_VALID; else return true;
				} else {
					// Assume it's an attempt at a mixed address (IPv6 + IPv4)
					if ($addressLiteral[$index - 1] !== ':')	if ($diagnose) return self::ISEMAIL_IPV4BADPREFIX; else return false;	// Character preceding IPv4 address must be ':'
					if (substr($addressLiteral, 0, 5) !== 'IPv6:')	if ($diagnose) return self::ISEMAIL_IPV6BADPREFIXMIXED; else return false;	// RFC5321 section 4.1.3

					$IPv6		= substr($addressLiteral, 5, ($index ===7) ? 2 : $index - 6);
					$groupMax	= 6;
				}
			} else {
				// It must be an attempt at pure IPv6
				if (substr($addressLiteral, 0, 5) !== 'IPv6:')		if ($diagnose) return self::ISEMAIL_IPV6BADPREFIX; else return false;	// RFC5321 section 4.1.3
				$IPv6 = substr($addressLiteral, 5);
				$groupMax = 8;
			}

			$groupCount	= preg_match_all('/^[0-9a-fA-F]{0,4}|\\:[0-9a-fA-F]{0,4}|(.)/', $IPv6, $matchesIP);
			$index		= strpos($IPv6,'::');

			if ($index === false) {
				// We need exactly the right number of groups
				if ($groupCount !== $groupMax)				if ($diagnose) return self::ISEMAIL_IPV6GROUPCOUNT; else return false;	// RFC5321 section 4.1.3
			} else {
				if ($index !== strrpos($IPv6,'::'))			if ($diagnose) return self::ISEMAIL_IPV6DOUBLEDOUBLECOLON; else return false;	// More than one '::'
				$groupMax = ($index === 0 || $index === (strlen($IPv6) - 2)) ? $groupMax : $groupMax - 1;
				if ($groupCount > $groupMax)				if ($diagnose) return self::ISEMAIL_IPV6TOOMANYGROUPS; else return false;	// Too many IPv6 groups in address
			}

			// Check for unmatched characters
			array_multisort($matchesIP[1], SORT_DESC);
			if ($matchesIP[1][0] !== '')					if ($diagnose) return self::ISEMAIL_IPV6BADCHAR; else return false;	// Illegal characters in address

			// It's a valid IPv6 address, so...
			if ($diagnose) return self::ISEMAIL_VALID; else return true;
		} else {
			// It's a domain name...

			// The syntax of a legal Internet host name was specified in RFC-952
			// One aspect of host name syntax is hereby changed: the
			// restriction on the first character is relaxed to allow either a
			// letter or a digit.
			// 	(http://tools.ietf.org/html/rfc1123#section-2.1)
			//
			// NB RFC 1123 updates RFC 1035, but this is not currently apparent from reading RFC 1035.
			//
			// Most common applications, including email and the Web, will generally not
			// permit...escaped strings
			// 	(http://tools.ietf.org/html/rfc3696#section-2)
			//
			// the better strategy has now become to make the "at least one period" test,
			// to verify LDH conformance (including verification that the apparent TLD name
			// is not all-numeric)
			// 	(http://tools.ietf.org/html/rfc3696#section-2)
			//
			// Characters outside the set of alphabetic characters, digits, and hyphen MUST NOT appear in domain name
			// labels for SMTP clients or servers
			// 	(http://tools.ietf.org/html/rfc5321#section-4.1.2)
			//
			// RFC5321 precludes the use of a trailing dot in a domain name for SMTP purposes
			// 	(http://tools.ietf.org/html/rfc5321#section-4.1.2)
			$dotArray	= /*. (array[int]string) .*/ preg_split('/\\.(?=(?:[^\\"]*\\"[^\\"]*\\")*(?![^\\"]*\\"))/m', $domain);
			$partLength	= 0;
			$element	= ''; // Since we use $element after the foreach loop let's make sure it has a value
	// revision 1.13: Line above added because PHPLint now checks for Definitely Assigned Variables

			if (count($dotArray) === 1)					if ($diagnose) return self::ISEMAIL_TLD; else return false;	// Mail host can't be a TLD (cite? What about localhost?)

			foreach ($dotArray as $element) {
				// Remove any leading or trailing FWS
				$element	= preg_replace("/^$FWS|$FWS\$/", '', $element);
				$elementLength	= strlen($element);

				// Each dot-delimited component must be of type atext
				// A zero-length element implies a period at the beginning or end of the
				// local part, or two periods together. Either way it's not allowed.
				if ($elementLength === 0)				if ($diagnose) return self::ISEMAIL_DOMAINEMPTYELEMENT; else return false;	// Dots in wrong place
	// revision 1.15: Speed up the test and get rid of "unitialized string offset" notices from PHP

				// Then we need to remove all valid comments (i.e. those at the start or end of the element
				if ($element[0] === '(') {
					$indexBrace = strpos($element, ')');
					if ($indexBrace !== false) {
						if (preg_match('/(?<!\\\\)[\\(\\)]/', substr($element, 1, $indexBrace - 1)) > 0) {
											if ($diagnose) return self::ISEMAIL_BADCOMMENT_START; else return false;	// Illegal characters in comment
						}
						$element	= substr($element, $indexBrace + 1, $elementLength - $indexBrace - 1);
						$elementLength	= strlen($element);
					}
				}

				if ($element[$elementLength - 1] === ')') {
					$indexBrace = strrpos($element, '(');
					if ($indexBrace !== false) {
						if (preg_match('/(?<!\\\\)(?:[\\(\\)])/', substr($element, $indexBrace + 1, $elementLength - $indexBrace - 2)) > 0)
											if ($diagnose) return self::ISEMAIL_BADCOMMENT_END; else return false;	// Illegal characters in comment

						$element	= substr($element, 0, $indexBrace);
						$elementLength	= strlen($element);
					}
				}

				// Remove any leading or trailing FWS around the element (inside any comments)
				$element = preg_replace("/^$FWS|$FWS\$/", '', $element);

				// What's left counts towards the maximum length for this part
				if ($partLength > 0) $partLength++;	// for the dot
				$partLength += strlen($element);

				// The DNS defines domain name syntax very generally -- a
				// string of labels each containing up to 63 8-bit octets,
				// separated by dots, and with a maximum total of 255
				// octets.
				// 	(http://tools.ietf.org/html/rfc1123#section-6.1.3.5)
				if ($elementLength > 63)				if ($diagnose) return self::ISEMAIL_DOMAINELEMENTTOOLONG; else return false;	// Label must be 63 characters or less

				// Any ASCII graphic (printing) character other than the
				// at-sign ("@"), backslash, double quote, comma, or square brackets may
				// appear without quoting.  If any of that list of excluded characters
				// are to appear, they must be quoted
				// 	(http://tools.ietf.org/html/rfc3696#section-3)
				//
				// If the hyphen is used, it is not permitted to appear at
				// either the beginning or end of a label.
				// 	(http://tools.ietf.org/html/rfc3696#section-2)
				//
				// Any excluded characters? i.e. 0x00-0x20, (, ), <, >, [, ], :, ;, @, \, comma, period, "
				if (preg_match('/[\\x00-\\x20\\(\\)<>\\[\\]:;@\\\\,\\."]|^-|-$/', $element) > 0) {
											if ($diagnose) return self::ISEMAIL_DOMAINBADCHAR; else return false;
				}
			}

			if ($partLength > 255) 						if ($diagnose) return self::ISEMAIL_DOMAINTOOLONG; else return false;	// Domain part must be 255 characters or less (http://tools.ietf.org/html/rfc1123#section-6.1.3.5)

			if (preg_match('/^[0-9]+$/', $element) > 0)			if ($diagnose) return self::ISEMAIL_TLDNUMERIC; else return false;	// TLD can't be all-numeric (http://www.apps.ietf.org/rfc/rfc3696.html#sec-2)

			// Check DNS?
			if ($checkDNS && function_exists('checkdnsrr')) {
				if (!(checkdnsrr($domain, 'A') || checkdnsrr($domain, 'MX'))) {
											if ($diagnose) return self::ISEMAIL_DOMAINNOTFOUND; else return false;	// Domain doesn't actually exist
				}
			}
		}

		// Eliminate all other factors, and the one which remains must be the truth.
		// 	(Sherlock Holmes, The Sign of Four)
		if ($diagnose) return self::ISEMAIL_VALID; else return true;
	}
}
// End of class ajaxUnit_common


/**
 * Browser detection class for ajaxUnit
 *
 * @package ajaxUnit
 */
class ajaxUnit_browser {
	// Can be public if required
	private	/*.string.*/		$Agent;
	public	/*.string.*/		$Name;
	public	/*.string.*/		$Version;

	public /*.void.*/ function __Construct() {
		$browsers	= array("firefox",	"msie",		"opera",	"chrome",	"safari",
					"mozilla",	"seamonkey",	"konqueror",	"netscape",
					"gecko",	"navigator",	"mosaic",	"lynx",		"amaya",
					"omniweb",	"avant",	"camino",	"flock",	"aol");
		$this->Agent	= $_SERVER['HTTP_USER_AGENT'];
		$match		= array();

		foreach ($browsers as $browser) {
			if (preg_match("#($browser)[/ ]?([0-9.]*)#i", $this->Agent, $match) !== 0) {
				$this->Name	= $match[1];
				$this->Version	= $match[2];
				break;
			}
		}
	}
}
// End of class ajaxUnit_browser


class ajaxUnit_cookies {
	// Can be public if required
	private static /*.string.*/ function get(/*.string.*/ $name) {
		return (isset($_COOKIE[$name])) ? (string) $_COOKIE[$name] : "Cookie $name is not set";
	}

	public static /*.string.*/ function set(/*.string.*/ $name, /*.string.*/ $value, $days = '0', $path = '/', $domain = '') {
		$expiry = ($days === '0') ? 0 : time() + 60 * 60 * 24 * (int) $days;

		if ($domain === '')
			setcookie($name, $value, $expiry, $path);
		else
			setcookie($name, $value, $expiry, $path, $domain);

		return "Setting cookie '$name' to [$value] until " . date(DateTime::COOKIE, $expiry);
	}

	public static /*.string.*/ function remove(/*.string.*/ $name) {
		if (!array_key_exists($name, $_COOKIE)) return "Cookie $name doesn't exist";
		return self::set($name, '0', '-1');
	}

	public static /*.string.*/ function toTable($name = '', $tableTop = true, $tableBottom = true) {
		$html = '';

		if ($tableTop) $html .= "<table>\n";

		if ($name === '') {
			$cookieCount	= count($_COOKIE);
			$keys		= array_keys($_COOKIE);
			$html		.= "<tr><td>Cookie ($cookieCount)</td><td>Value</td></tr>\n";

			for ($i = 0; $i < $cookieCount; $i++) {
				$name = $keys[$i];
				$html .= self::toTable($name, false, false);
			}
		} else {
			$value = self::get($name);
			$html .= "<tr><td>$name</td><td>$value</td></tr>\n";
		}

		if ($tableBottom) $html .= "</table>\n";

		return $html;
	}
}
// End of class ajaxUnit_cookies


class ajaxUnit_log {
	public static /*.string.*/ function format(/*.string.*/ $text, $textIndent = 4, $tag = 'p', $htmlIndent = 4) {
		$marginLeft	= ($textIndent === 0) ? '' : ' style="margin-left:' . $textIndent . 'em"';
		$timestamp	= (string) microtime(true);

		if ($tag === 'p') {
			$openTag	= "<p$marginLeft class=\"ajaxunit-testlog\" timestamp =\"$timestamp\">";
			$closeTag	= '</p>';
		} else {
			$openTag	= '';
			$closeTag	= '';
		}

		return str_pad('', $htmlIndent, "\t") . "$openTag$text$closeTag\n";
	}
}

/**
 * All the methods needed to interact with the file system etc.
 *
 * @package ajaxUnit
 */
interface I_ajaxUnit_environment extends I_ajaxUnit_common {
	const	ACTION_ABOUT		= 'about',
		ACTION_CONTROL		= 'control',
		ACTION_CSS		= 'css',
		ACTION_CUSTOMURL	= 'customURL',
		ACTION_DUMMY		= 'dummyrun',
		ACTION_ICON		= 'icon',
		ACTION_JAVASCRIPT	= 'js',
		ACTION_PARSE		= 'parse',
		ACTION_SUITE		= 'suite',
		ACTION_END		= 'end',
		ACTION_SOURCECODE	= 'source',
		ACTION_LOGTIDY		= 'logtidy',

		TAGNAME_ADD		= 'add',
		TAGNAME_BROWSER		= 'browser',
		TAGNAME_BROWSERVERSION	= 'browserversion',
		TAGNAME_CHECKBOX	= 'checkbox',
		TAGNAME_CHECKSIZE	= 'check-size',
		TAGNAME_CLICK		= 'click',
		TAGNAME_COOKIES		= 'cookies',
		TAGNAME_COPY		= 'copy',
		TAGNAME_COUNT		= 'count',
		TAGNAME_CUSTOMURL	= 'customurl',
		TAGNAME_DELETE		= 'delete',
		TAGNAME_EXPECTINGCOUNT	= 'expectingCount',
		TAGNAME_FILE		= 'file',
		TAGNAME_FOLDER_BASE	= 'baseFolder',
		TAGNAME_FOLDER_LOGS	= 'logsFolder',
//-		TAGNAME_FOLDER_PACKAGE	= 'packageFolder',
		TAGNAME_FOLDER_TESTS	= 'testsFolder',
		TAGNAME_FORMFILL	= 'formfill',
		TAGNAME_HEADERS		= 'headers',
		TAGNAME_IGNORE		= 'ignore',
		TAGNAME_INCLUDEPATH	= 'include_path',
		TAGNAME_INDEX		= 'index',
		TAGNAME_LOCATION	= 'location',
		TAGNAME_LOGAPPEND	= 'logAppend',
		TAGNAME_OPEN		= 'open',
		TAGNAME_PARAMETERS	= 'parameters',
		TAGNAME_PARSING		= 'parsing',
		TAGNAME_PROJECT		= 'project',
		TAGNAME_POST		= 'post',
		TAGNAME_RADIO		= 'radio',
		TAGNAME_RUBRIC		= 'rubric',
		TAGNAME_RESET		= 'reset',
		TAGNAME_RESPONSECOUNT	= 'responseCount',
		TAGNAME_RESPONSELIST	= 'responseList',
		TAGNAME_RESULT		= 'result',
		TAGNAME_RESULTS		= 'results',
		TAGNAME_RESULTSNODENAME	= 'resultsnodename',
		TAGNAME_SESSION		= 'session',
		TAGNAME_SET		= 'set',
		TAGNAME_STATUS		= 'status',
		TAGNAME_STOP		= 'stop',
		TAGNAME_SUITE		= 'suite',
		TAGNAME_TEST		= 'test',
		TAGNAME_UID		= 'uid',
		TAGNAME_URL_BASE	= 'baseURL',
		TAGNAME_URL_LOGS	= 'logsURL',
		TAGNAME_URL_TESTS	= 'testsURL',

		ATTRNAME_DAYS		= 'days',
		ATTRNAME_DESTINATION	= 'dest',
		ATTRNAME_ID		= 'id',
		ATTRNAME_NAME		= 'name',
		ATTRNAME_SOURCE		= 'src',
		ATTRNAME_UPDATE		= 'update',
		ATTRNAME_URL		= 'url',
		ATTRNAME_VALUE		= 'value',

		STATUS_INPROGRESS	= 'in progress',
		STATUS_FAIL		= '<span style="color:red;font-weight:bold;">FAIL!</span>',
		STATUS_SUCCESS		= 'success',
		STATUS_FINISHED		= 'finished',
		STATUS_FATALERROR	= '<span style="color:red;font-weight:bold;">fatal error :-(</span>',
		STATUS_UNKNOWN		= 'unknown',

		TESTS_FOLDER		= 'tests',
		TESTS_EXTENSION		= 'xml',
		LOG_FOLDER		= 'logs',
		LOG_EXTENSION		= 'html',
		CONTEXT_FOLDER		= '.context',
		LOG_MAXHOURS		= 12,
		TEST_WAITUSECS		= 50000,	// uSecs to wait for response
		TEST_MAXWAIT		= 10000000;	// Maximum waiting time in uSecs

	public static /*.void.*/	function tidyLogFiles();
}

/**
 * All the methods needed to interact with the file system etc.
 *
 * @package ajaxUnit
 */
abstract class ajaxUnit_environment extends ajaxUnit_common implements I_ajaxUnit_environment {
	public static /*.string.*/ function thisURL() {
		return self::getURL(self::URL_MODE_PATH, 'ajaxunit.php');
	}

	public static /*.void.*/ function sendContent(/*.string.*/ $content, $component = '', $contentType = '') {
		// Send headers first
		if (headers_sent()) {
			echo "<!-- headers already sent -->\n";
		} else {
//			$defaultType	= ($component	=== 'container')	? "text/html"	: "application/ajaxunit"; // Webkit oddity
			$defaultType	= "text/html";
			$contentType	= ($contentType	=== '')			? $defaultType	: $contentType;
			$component	= ($component	=== '')			? 'ajaxunit'	: "ajaxunit-$component";

			header("Cache-Control: no-cache");	// Damn fool Internet Explorer caching feature
			header("Expires: -1");			// Ditto
			header("Pragma: no-cache");		// Ditto
			header("Content-type: $contentType");
			header("Package: ajaxUnit");
			header("ajaxUnit-component: $component");
		}

		// Send content
		echo $content;
	}

	protected static /*.string.*/ function htmlPageTop() {
		$actionCSS		= self::ACTION_CSS;
		$URL			= self::thisURL();

		return <<<HTML
<!doctype html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>ajaxUnit</title>
		<link type="text/css" rel="stylesheet" href="$URL?$actionCSS" title="ajaxUnit"/>
	</head>
	<body>
		<div id="ajaxunit">
			<a id="top" href="#bottom">bottom &raquo;</a>
			<h1>ajaxUnit test log</h1>
HTML;
	}

	private static /*.string.*/ function htmlPageBottom() {
		return <<<HTML
			<p><a id="bottom" href="#top">&laquo; top</a></p>
			<p>ajaxUnit version 0.17.30</p>
		</div>
		<script type="text/javascript">
			function ajaxUnit_toggle_log(control, id) {
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

	private static /*.void.*/ function makeFolder(/*.string.*/ $folder) {
		if (!is_dir($folder)) if (!@mkdir($folder, 0600)) exit("Failed to create folder $folder");
	}

	private static /*.string.*/ function getContextFilename() {
		self::makeFolder(self::CONTEXT_FOLDER);
		return self::CONTEXT_FOLDER . DIRECTORY_SEPARATOR . 'ajaxUnit_' . (string) str_replace(':', '_' , $_SERVER['REMOTE_ADDR']); // str_replace is because of IPv6
	}

	protected static /*.array[string]string.*/ function setTestContext(/*.mixed.*/ $newContext, $value = '') {
		$filename	= self::getContextFilename();
		$context	= /*.(array[string]string).*/ array();

		if (is_file($filename)) {
			$handle		= @fopen($filename, 'r+b'); // Lock the file
			$serial		= @file_get_contents($filename);
			$context	= /*.(array[string]string).*/ unserialize($serial);

			rewind($handle);
			ftruncate($handle, 0);
		} else {
			$handle		= @fopen($filename, 'wb');
		}

		if (is_array($newContext)) {
			$context = /*.(array[string]string).*/ array_merge($context, /*.(array[string]string).*/ $newContext);
		} else {
			$context[(string) $newContext] = $value;
		}

		@fwrite($handle, serialize($context));
		fclose($handle);
		return $context;
	}

	protected static /*.mixed.*/ function getTestContext($key = '') {
		$filename = self::getContextFilename();

		if (!is_file($filename)) {
			$context	= /*.(array[]string).*/ array();
		} else {
			$serial		= @file_get_contents($filename);
			$context	= /*.(array[]string).*/ unserialize($serial);
		}

		if ($key === '') {
			return $context;
		} else if (array_key_exists($key, $context)) {
			return $context[$key];
		} else {
			return '';
		}
	}

	protected static /*.array[string]string.*/ function setInitialContext(DOMElement $test, /*.int.*/ $testIndex) {
		// Any browser-specific results in the test data?
		$browser		= new ajaxUnit_browser();
		$resultsNodeName	= self::TAGNAME_RESULTS . '-' . $browser->Name . '-' . $browser->Version;
		$resultsNodeList	= $test->getElementsByTagName($resultsNodeName); // Browser and version-specific results

		if ($resultsNodeList->length === 0) {
			$resultsNodeName	= self::TAGNAME_RESULTS . '-' . $browser->Name;
			$resultsNodeList	= $test->getElementsByTagName($resultsNodeName); // Just browser-specific results

			if ($resultsNodeList->length === 0) {
				// General (not browser-specific) results
				$resultsNodeName	= self::TAGNAME_RESULTS;
				$resultsNodeList	= $test->getElementsByTagName($resultsNodeName);

				if ($resultsNodeList->length === 0) {
					// No results defined for this test
					$resultsElement		= new DOMElement($resultsNodeName);
					$resultsNode		= $test->appendChild($resultsElement);
					$resultsNodeName	= '';
				} else {
					$resultsNode		= $resultsNodeList->item(0);
				}
			} else {
				$resultsNode		= $resultsNodeList->item(0);
			}
		} else {
			$resultsNode		= $resultsNodeList->item(0);
		}

		// Build a string containing a list of expected responses (1234...n) according to <results> child nodes
		$responseList	= '';

		if ($resultsNodeName === '') {
			$expected	= 0;
		} else {
			/*.object.*/ $resultsObject = $resultsNode;		// PHPLint-compliant typecasting
			$resultsElement	= /*.(DOMElement).*/ $resultsObject;	// PHPLint-compliant typecasting

			$resultsList	= $resultsElement->getElementsByTagName(self::TAGNAME_RESULT);	// Get the expected results
			$expected	= $resultsList->length;
			for ($i = 0; $i < $expected; $i++) $responseList .= (string) $i;
		}

		// Set context data
		$context = /*.(array[string]string).*/ array();
		$context[self::TAGNAME_INDEX]		= (string) $testIndex;
		$context[self::TAGNAME_RESPONSECOUNT]	= '0';
		$context[self::TAGNAME_PARSING]		= '0';
		$context[self::TAGNAME_RESPONSELIST]	= $responseList;
		$context[self::TAGNAME_RESULTSNODENAME]	= $resultsNodeName;
		$context[self::TAGNAME_EXPECTINGCOUNT]	= (string) $expected;

		self::setTestContext($context);
		return $context;
	}

	private static /*.string.*/ function getSuiteFilename(/*.string.*/ $suite) {
		$folder = (string) self::getTestContext(self::TAGNAME_FOLDER_TESTS);
		self::makeFolder($folder);
		return $folder.DIRECTORY_SEPARATOR.$suite.'.'.self::TESTS_EXTENSION;
	}

	protected static /*.DOMDocument.*/ function getDOMDocument(/*.string.*/ $suite) {
		$filename = self::getSuiteFilename($suite);
		$document = new DOMDocument();
		$document->documentURI = $filename;
		@$document->load($filename);
		$document->xinclude();
		return $document;
	}

	protected static /*.void.*/ function updateTestSuite(/*.string.*/ $suite, DOMDocument $document) {
		$document->save(self::getSuiteFilename($suite));
	}

	protected static /*.DOMNodeList.*/ function getTestList(DOMDocument $document) {
		return $document->getElementsByTagName(self::TAGNAME_TEST);
	}

	private static /*.string.*/ function getLogFilename($basename = false) {
		$filename = 'ajaxUnit_' . (string) self::getTestContext(self::TAGNAME_UID) .'.'. self::LOG_EXTENSION;

		if ($basename) {
			return $filename;
		} else {
			$folder = (string) self::getTestContext(self::TAGNAME_FOLDER_LOGS);
			self::makeFolder($folder);
			return $folder.DIRECTORY_SEPARATOR.$filename;
		}
	}

	private static /*.void.*/ function appendLogEntry(/*.string.*/ $html, $dummyRun = false) {
		if ($dummyRun) {
			echo $html;
		} else {
			$handle = @fopen(self::getLogFilename(), 'ab');
			fwrite($handle, $html);
			fclose($handle);
		}
	}

	protected static /*.void.*/ function appendLog(/*.string.*/ $text, $dummyRun = false, $textIndent = 4, $tag = 'p', $htmlIndent = 4) {
		self::appendLogEntry(ajaxUnit_log::format($text, $textIndent, $tag, $htmlIndent), $dummyRun);
	}

	protected static /*.void.*/ function logTestContext($dummyRun = false) {
		$context	= /*.(array[string]string).*/ self::getTestContext();

		self::appendLog("<span class=\"ajaxunit-testlog\" onclick=\"ajaxUnit_toggle_log(this, 'ajaxunit-parameters')\">+</span> Global test parameters", $dummyRun, 0, 'p', 3);
		self::appendLog("<div class=\"ajaxunit-testlog\" id=\"ajaxunit-parameters\">", $dummyRun, 0, '', 3);
		foreach ($context as $key => $value) self::appendLog("$key = " . htmlspecialchars(substr($value, 0, 64)), $dummyRun);
		self::appendLog('</div>', $dummyRun, 0, '', 3);
		self::appendLog('<hr />', $dummyRun, 0, '', 3);
	}

//	private static /*.void.*/ function logTestScript(DOMDocument $document, $dummyRun = false) {
//		self::appendLog("<span class=\"ajaxunit-testlog\" onclick=\"ajaxUnit_toggle_log(this, 'ajaxunit-script')\">+</span> Test script", $dummyRun, 0, 'p', 3);
//		self::appendLog("<div class=\"ajaxunit-testlog\" id=\"ajaxunit-script\">", $dummyRun, 0, '', 3);
//		self::appendLog('<pre>' . htmlspecialchars($document->saveXML()) . '</pre>', $dummyRun);
//		self::appendLog('</div>', $dummyRun, 0, '', 3);
//		self::appendLog('<hr />', $dummyRun, 0, '', 3);
//	}

	protected static /*.void.*/ function logResult(/*.boolean.*/ $success, $dummyRun = false) {
		self::appendLog('</div>', $dummyRun, 0, '', 3);

		if ($success) {
			$status = self::STATUS_SUCCESS;

			// Increment successful test counter
			$count = (int) self::getTestContext(self::TAGNAME_COUNT);
			$count++;

			self::setTestContext(self::TAGNAME_COUNT, (string) $count);
			self::setTestContext(self::TAGNAME_STATUS, $status);
		} else {
			$status = self::STATUS_FAIL;
		}

		self::appendLog("Result: $status", $dummyRun, 0, 'p', 3);
		self::appendLog('<hr />', $dummyRun, 0, '', 3);
	}

	private static /*.string.*/ function getLogLink() {
		return (string) self::getTestContext(self::TAGNAME_URL_LOGS) . '/' . self::getLogFilename(true);
	}

	private static /*.void.*/ function sendLogLink($dummyRun = false) {
		if ($dummyRun) return;
		$attrName	= self::ATTRNAME_URL;
		$URL		= self::getLogLink();
		$xml		= "<test><open $attrName=\"$URL\" /></test>";
		self::sendContent($xml, self::ACTION_PARSE, 'text/xml');
	}

	protected static /*.void.*/ function tidyUp($dummyRun = false) {
		$count = ltrim((string) self::getTestContext(self::TAGNAME_COUNT));
		self::appendLog("$count tests successfully completed", $dummyRun, 0, 'p', 3);
		self::appendLog(self::htmlPageBottom(), $dummyRun, 0, '', 0);
		self::sendLogLink($dummyRun);
	}

	protected static /*.string.*/ function substituteParameters(/*.string.*/ $text) {
		$variables		= /*.(array[string]string).*/ self::getTestContext();
		$variables['URL']	= self::thisURL();

		extract($variables);

		return (string) eval('return "' . (string) str_replace('"', '\\"', $text) . '";');
	}

/**
 * Delete all old log files. "Old" meaning older than LOG_MAXHOURS
 */
	public static /*.void.*/ function tidyLogFiles() {
		$folder		= (string) self::getTestContext(self::TAGNAME_FOLDER_LOGS);
		$extension	= self::LOG_EXTENSION;

		foreach (glob($folder.DIRECTORY_SEPARATOR."*.$extension") as $filename) {
			if (is_file($filename)) {
				$ageInHours = (int) floor((time() - filemtime($filename)) / (60 * 60));
				if ($ageInHours > self::LOG_MAXHOURS) @unlink($filename);
			}
		}
	}

	protected static /*.boolean.*/ function terminate($status = self::STATUS_UNKNOWN, $message = '', $success = false, $dummyRun = false) {
		if ($message !== '')	self::appendLog($message, $dummyRun);
		if (!$success)		self::logResult($success, $dummyRun);

		self::setTestContext(self::TAGNAME_STATUS, $status);
		self::setTestContext(self::TAGNAME_PARSING, '0');
		self::tidyUp($dummyRun);

		return $success;
	}
}
// End of class ajaxUnit_environment


/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 */
abstract class ajaxUnit_Text_Diff_Op {

	public /*.array[int]string.*/ $originalArray;
	public /*.array[int]string.*/ $finalArray;

	public /*.int.*/ function norig()
	{
		return (isset($this->originalArray)) ? count($this->originalArray) : 0;
	}

	public /*.int.*/ function nfinal()
	{
		return (isset($this->finalArray)) ? count($this->finalArray) : 0;
	}

	public /*.object.*/ function reverse()
	{
		return $this;
	}
}

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 */
class ajaxUnit_Text_Diff_Op_copy extends ajaxUnit_Text_Diff_Op {

	public /*.void.*/ function __construct(/*.array[int]string.*/ $originalArray)
	{
		$this->originalArray	= $originalArray;
		$this->finalArray	= $originalArray;
	}

	public /*.ajaxUnit_Text_Diff_Op_copy.*/ function reverse()
	{
		$reverse = new ajaxUnit_Text_Diff_Op_copy($this->finalArray);
		return $reverse;
	}

}

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 */
/*.
forward class ajaxUnit_Text_Diff_Op_delete {
	public void function __construct(array[int]string $lines);
}
.*/
class ajaxUnit_Text_Diff_Op_add extends ajaxUnit_Text_Diff_Op {

	public /*.void.*/ function __construct(/*.array[int]string.*/ $lines)
	{
		$this->finalArray = $lines;
		unset($this->originalArray);
	}

	public /*.ajaxUnit_Text_Diff_Op_delete.*/ function reverse()
	{
		$reverse = new ajaxUnit_Text_Diff_Op_delete($this->finalArray);
		return $reverse;
	}

}
/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 */
class ajaxUnit_Text_Diff_Op_delete extends ajaxUnit_Text_Diff_Op {

	public /*.void.*/ function __construct(/*.array[int]string.*/ $lines)
	{
		$this->originalArray = $lines;
		unset($this->finalArray);
	}

	public /*.ajaxUnit_Text_Diff_Op_add.*/ function reverse()
	{
		$reverse = new ajaxUnit_Text_Diff_Op_add($this->originalArray);
		return $reverse;
	}

}


/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 */
class ajaxUnit_Text_Diff_Op_change extends ajaxUnit_Text_Diff_Op {

	public /*.void.*/ function __construct(/*.array[int]string.*/ $originalArray, /*.array[int]string.*/ $finalArray)
	{
		$this->originalArray = $originalArray;
		$this->finalArray = $finalArray;
	}

	public /*.ajaxUnit_Text_Diff_Op_change.*/ function reverse()
	{
		$reverse = new ajaxUnit_Text_Diff_Op_change($this->finalArray, $this->originalArray);
		return $reverse;
	}

}

/**
 * Class used internally by ajaxUnit_Text_Diff to actually compute the diffs.
 *
 * This class is implemented using native PHP code.
 *
 * The algorithm used here is mostly lifted from the perl module
 * Algorithm::Diff (version 1.06) by Ned Konz, which is available at:
 * http://www.perl.com/CPAN/authors/id/N/NE/NEDKONZ/Algorithm-Diff-1.06.zip
 *
 * More ideas are taken from: http://www.ics.uci.edu/~eppstein/161/960229.html
 *
 * Some ideas (and a bit of code) are taken from analyze.c, of GNU
 * diffutils-2.7, which can be found at:
 * ftp://gnudist.gnu.org/pub/gnu/diffutils/diffutils-2.7.tar.gz
 *
 * Some ideas (subdivision by NCHUNKS > 2, and some optimizations) are from
 * Geoffrey T. Dairiki <dairiki@dairiki.org>. The original PHP version of this
 * code was written by him, and is used/adapted with his permission.
 *
 * $Horde: framework/Text_Diff/Diff/Engine/native.php,v 1.7.2.5 2009/01/06 15:23:41 jan Exp $
 *
 * Copyright 2004-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you did
 * not receive this file, see http://opensource.org/licenses/lgpl-license.php.
 *
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 * @package Text_Diff
 */
class ajaxUnit_Text_Diff_Engine_native {
	private /*.array[int]boolean.*/	$xchanged;
	private /*.array[int]boolean.*/	$ychanged;
	private /*.array[int]string.*/	$xv;
	private /*.array[int]string.*/	$yv;
	private /*.array[int]int.*/	$xind;
	private /*.array[int]int.*/	$yind;
	private /*.array[int]int.*/	$seq;
	private /*.array[int]int.*/	$in_seq;
	private /*.int.*/		$lcs		= 0;

	private /*.int.*/ function _lcsPos(/*.int.*/ $ypos)
	{
		$end = $this->lcs;
		if ($end === 0 || $ypos > $this->seq[$end]) {
			$this->seq[++$this->lcs] = $ypos;
			$this->in_seq[$ypos] = 1;
			return $this->lcs;
		}

		$beg = 1;
		while ($beg < $end) {
			$mid = (int)(($beg + $end) / 2);
			if ($ypos > $this->seq[$mid]) {
				$beg = $mid + 1;
			} else {
				$end = $mid;
			}
		}

		assert($ypos != $this->seq[$end]);

		$this->in_seq[$this->seq[$end]] = 0;
		$this->seq[$end] = $ypos;
		$this->in_seq[$ypos] = 1;
		return $end;
	}

	/**
	 * Divides the Largest Common Subsequence (LCS) of the sequences (XOFF,
	 * XLIM) and (YOFF, YLIM) into NCHUNKS approximately equally sized
	 * segments.
	 *
	 * Returns (LCS, PTS).  LCS is the length of the LCS. PTS is an array of
	 * NCHUNKS+1 (X, Y) indexes giving the diving points between sub
	 * sequences.  The first sub-sequence is contained in (X0, X1), (Y0, Y1),
	 * the second in (X1, X2), (Y1, Y2) and so on.  Note that (X0, Y0) ==
	 * (XOFF, YOFF) and (X[NCHUNKS], Y[NCHUNKS]) == (XLIM, YLIM).
	 *
	 * This function assumes that the first lines of the specified portions of
	 * the two files do not match, and likewise that the last lines do not
	 * match.  The caller must trim matching lines from the beginning and end
	 * of the portions it is going to specify.
	 */
	private /*.array.*/ function _diag (/*.int.*/ $xoff, /*.int.*/ $xlim, /*.int.*/ $yoff, /*.int.*/ $ylim, /*.int.*/ $nchunks)
	{
		$flip		= false;
		$ymatches	= /*.(array[string][int]int).*/ array();

		if ($xlim - $xoff > $ylim - $yoff) {
			/* Things seems faster (I'm not sure I understand why) when the
			 * shortest sequence is in X. */
			$flip = true;
			list ($xoff, $xlim, $yoff, $ylim)
				= array($yoff, $ylim, $xoff, $xlim);
		}

		if ($flip) {
			for ($i = $ylim - 1; $i >= $yoff; $i--) {
				$ymatches[$this->xv[$i]][] = $i;
			}
		} else {
			for ($i = $ylim - 1; $i >= $yoff; $i--) {
				$ymatches[$this->yv[$i]][] = $i;
			}
		}

		$this->lcs	= 0;
		$this->seq[0]	= $yoff - 1;
		$this->in_seq	= /*.(array[int]int).*/ array();
		$ymids[0]	= array();

		$numer = $xlim - $xoff + $nchunks - 1;
		$x = $xoff;
		for ($chunk = 0; $chunk < $nchunks; $chunk++) {
			if ($chunk > 0) {
				for ($i = 0; $i <= $this->lcs; $i++) {
					$ymids[$i][$chunk - 1] = $this->seq[$i];
				}
			}

			$x1 = $xoff + (int)(($numer + ($xlim - $xoff) * $chunk) / $nchunks);
			for (; $x < $x1; $x++) {
				$line = $flip ? $this->yv[$x] : $this->xv[$x];
				if (empty($ymatches[$line])) continue;
				$matches = $ymatches[$line];
				reset($matches);
				$k = 0;

				do {
					$next = each($matches);
					if (is_bool($next)) break;

					$y		= 0;
					list(, $y)	= $next;

					if (empty($this->in_seq[$y])) {
						$k = $this->_lcsPos($y);
						assert($k > 0);
						$ymids[$k] = $ymids[$k - 1];
						break;
					}
				} while (true);

				do {
					$next = each($matches);
					if (is_bool($next)) break;

					$y		= 0;
					list(, $y)	= $next;

					if ($y > $this->seq[$k - 1]) {
						assert($y <= $this->seq[$k]);
						/* Optimization: this is a common case: next match is
						 * just replacing previous match. */
						$this->in_seq[$this->seq[$k]] = 0;
						$this->seq[$k] = $y;
						$this->in_seq[$y] = 1;
					} elseif (empty($this->in_seq[$y])) {
						$k = $this->_lcsPos($y);
						assert($k > 0);
						$ymids[$k] = $ymids[$k - 1];
					}
				} while (true);
			}
		}

		$seps[] = $flip ? array($yoff, $xoff) : array($xoff, $yoff);
		$ymid = $ymids[$this->lcs];
		for ($n = 0; $n < $nchunks - 1; $n++) {
			$x1 = $xoff + (int)(($numer + ($xlim - $xoff) * $n) / $nchunks);
			$y1 = $ymid[$n] + 1;
			$seps[] = $flip ? array($y1, $x1) : array($x1, $y1);
		}
		$seps[] = $flip ? array($ylim, $xlim) : array($xlim, $ylim);

		return array($this->lcs, $seps);
	}

	/**
	 * Finds LCS of two sequences.
	 *
	 * The results are recorded in the vectors $this->{x,y}changed[], by
	 * storing a 1 in the element for each line that is an insertion or
	 * deletion (ie. is not in the LCS).
	 *
	 * The subsequence of file 0 is (XOFF, XLIM) and likewise for file 1.
	 *
	 * Note that XLIM, YLIM are exclusive bounds.  All line numbers are
	 * origin-0 and discarded lines are not counted.
	 */
	private /*.void.*/ function _compareseq (/*.int.*/ $xoff, /*.int.*/ $xlim, /*.int.*/ $yoff, /*.int.*/ $ylim)
	{
		/* Slide down the bottom initial diagonal. */
		while ($xoff < $xlim && $yoff < $ylim
			   && $this->xv[$xoff] === $this->yv[$yoff]) {
			++$xoff;
			++$yoff;
		}

		/* Slide up the top initial diagonal. */
		while ($xlim > $xoff && $ylim > $yoff
			   && $this->xv[$xlim - 1] === $this->yv[$ylim - 1]) {
			--$xlim;
			--$ylim;
		}

		if ($xoff == $xlim || $yoff == $ylim) {
			$lcs			= 0;
			$seps			= /*.(array[int][int]int).*/ array();
		} else {
			/* This is ad hoc but seems to work well.  $nchunks =
			 * sqrt(min($xlim - $xoff, $ylim - $yoff) / 2.5); $nchunks =
			 * max(2,min(8,(int)$nchunks)); */
			$nchunks		= (int) min(7, $xlim - $xoff, $ylim - $yoff) + 1;
			list($lcs, $seps)	= $this->_diag($xoff, $xlim, $yoff, $ylim, $nchunks);
		}

		if ($lcs == 0) {
			/* X and Y sequences have no common subsequence: mark all
			 * changed. */
			while ($yoff < $ylim) {
				$this->ychanged[$this->yind[$yoff++]] = true;
			}
			while ($xoff < $xlim) {
				$this->xchanged[$this->xind[$xoff++]] = true;
			}
		} else {
			/* Use the partitions to split this problem into subproblems. */
			reset($seps);
			$pt1 = $seps[0];

			do {
				$next = next($seps);
				if (is_bool($next)) break;
				$pt2 = /*.(array[int]int).*/ $next;
				$this->_compareseq ($pt1[0], $pt2[0], $pt1[1], $pt2[1]);
				$pt1 = $pt2;
			} while (true);
		}
	}

	/**
	 * Adjusts inserts/deletes of identical lines to join changes as much as
	 * possible.
	 *
	 * We do something when a run of changed lines include a line at one end
	 * and has an excluded, identical line at the other.  We are free to
	 * choose which identical line is included.  `compareseq' usually chooses
	 * the one at the beginning, but usually it is cleaner to consider the
	 * following identical line to be the "change".
	 *
	 * This is extracted verbatim from analyze.c (GNU diffutils-2.7).
	 */
	private /*.void.*/ function _shiftBoundaries(/*.array[int]string.*/ $lines, /*.array[int]boolean.*/ &$changed, /*.array[int]boolean.*/ $other_changed)
	{
		$i = 0;
		$j = 0;

		assert('count($lines) == count($changed)');
		$len = count($lines);
		$other_len = count($other_changed);

		while (true) {
			/* Scan forward to find the beginning of another run of
			 * changes. Also keep track of the corresponding point in the
			 * other file.
			 *
			 * Throughout this code, $i and $j are adjusted together so that
			 * the first $i elements of $changed and the first $j elements of
			 * $other_changed both contain the same number of zeros (unchanged
			 * lines).
			 *
			 * Furthermore, $j is always kept so that $j == $other_len or
			 * $other_changed[$j] == false. */
			while ($j < $other_len && $other_changed[$j]) {
				$j++;
			}

			while ($i < $len && ! $changed[$i]) {
				assert('$j < $other_len && ! $other_changed[$j]');
				$i++; $j++;
				while ($j < $other_len && $other_changed[$j]) {
					$j++;
				}
			}

			if ($i == $len) {
				break;
			}

			$start = $i;

			/* Find the end of this run of changes. */
			while (++$i < $len && $changed[$i]) {
				continue;
			}

			do {
				/* Record the length of this run of changes, so that we can
				 * later determine whether the run has grown. */
				$runlength = $i - $start;

				/* Move the changed region back, so long as the previous
				 * unchanged line matches the last changed one.  This merges
				 * with previous changed regions. */
				while ($start > 0 && $lines[$start - 1] === $lines[$i - 1]) {
					$changed[--$start] = true;
					$changed[--$i] = false;
					while ($start > 0 && $changed[$start - 1]) {
						$start--;
					}
					assert('$j > 0');
					while ($other_changed[--$j]) {
						continue;
					}
					assert('$j >= 0 && !$other_changed[$j]');
				}

				/* Set CORRESPONDING to the end of the changed run, at the
				 * last point where it corresponds to a changed run in the
				 * other file. CORRESPONDING == LEN means no such point has
				 * been found. */
				$corresponding = $j < $other_len ? $i : $len;

				/* Move the changed region forward, so long as the first
				 * changed line matches the following unchanged one.  This
				 * merges with following changed regions.  Do this second, so
				 * that if there are no merges, the changed region is moved
				 * forward as far as possible. */
				while ($i < $len && $lines[$start] === $lines[$i]) {
					$changed[$start++] = false;
					$changed[$i++] = true;
					while ($i < $len && $changed[$i]) {
						$i++;
					}

					assert('$j < $other_len && ! $other_changed[$j]');
					$j++;
					if ($j < $other_len && $other_changed[$j]) {
						$corresponding = $i;
						while ($j < $other_len && $other_changed[$j]) {
							$j++;
						}
					}
				}
			} while ($runlength != $i - $start);

			/* If possible, move the fully-merged run of changes back to a
			 * corresponding run in the other file. */
			while ($corresponding < $i) {
				$changed[--$start] = true;
				$changed[--$i] = false;
				assert('$j > 0');
				while ($other_changed[--$j]) {
					continue;
				}
				assert('$j >= 0 && !$other_changed[$j]');
			}
		}
	}

	public /*.array.*/ function diff(/*.array[int]string.*/ $from_lines, /*.array[int]string.*/ $to_lines)
	{
		array_walk($from_lines, array('ajaxUnit_Text_Diff', 'trimNewlines'));
		array_walk($to_lines, array('ajaxUnit_Text_Diff', 'trimNewlines'));

		$n_from = count($from_lines);
		$n_to = count($to_lines);

		$this->xchanged	= $this->ychanged	= /*.(array[int]boolean).*/	array();
		$this->xv	= $this->yv		= /*.(array[int]string).*/	array();
		$this->xind	= $this->yind		= /*.(array[int]int).*/		array();
		$xhash		= $yhash		= /*.(array[string]int).*/	array();

		unset($this->seq);
		unset($this->in_seq);
		unset($this->lcs);

		// Skip leading common lines.
		for ($skip = 0; $skip < $n_from && $skip < $n_to; $skip++) {
			if ($from_lines[$skip] !== $to_lines[$skip]) {
				break;
			}
			$this->xchanged[$skip] = $this->ychanged[$skip] = false;
		}

		// Skip trailing common lines.
		$xi = $n_from; $yi = $n_to;
		for ($endskip = 0; --$xi > $skip && --$yi > $skip; $endskip++) {
			if ($from_lines[$xi] !== $to_lines[$yi]) {
				break;
			}
			$this->xchanged[$xi] = $this->ychanged[$yi] = false;
		}

		// Ignore lines which do not exist in both files.
		for ($xi = $skip; $xi < $n_from - $endskip; $xi++) {
			$xhash[$from_lines[$xi]] = 1;
		}
		for ($yi = $skip; $yi < $n_to - $endskip; $yi++) {
			$line = $to_lines[$yi];
			if (($this->ychanged[$yi] = empty($xhash[$line]))) {
				continue;
			}
			$yhash[$line] = 1;
			$this->yv[] = $line;
			$this->yind[] = $yi;
		}
		for ($xi = $skip; $xi < $n_from - $endskip; $xi++) {
			$line = $from_lines[$xi];
			if (($this->xchanged[$xi] = empty($yhash[$line]))) {
				continue;
			}
			$this->xv[] = $line;
			$this->xind[] = $xi;
		}

		// Find the LCS.
		$this->_compareseq(0, count($this->xv), 0, count($this->yv));

		// Merge edits when possible.
		$this->_shiftBoundaries($from_lines, $this->xchanged, $this->ychanged);
		$this->_shiftBoundaries($to_lines, $this->ychanged, $this->xchanged);

		// Compute the edit operations.
		$edits = /*.(array[int]object).*/ array();
		$xi = $yi = 0;
		while ($xi < $n_from || $yi < $n_to) {
			assert($yi < $n_to || $this->xchanged[$xi]);
			assert($xi < $n_from || $this->ychanged[$yi]);

			// Skip matching "snake".
			$copy = /*.(array[int]string).*/ array();
			while ($xi < $n_from && $yi < $n_to
				   && !$this->xchanged[$xi] && !$this->ychanged[$yi]) {
				$copy[] = $from_lines[$xi++];
				++$yi;
			}
			if (count($copy) !== 0) {
				$edits[] = new ajaxUnit_Text_Diff_Op_copy($copy);
			}

			// Find deletes & adds.
			$delete = /*.(array[int]string).*/ array();
			while ($xi < $n_from && $this->xchanged[$xi]) {
				$delete[] = $from_lines[$xi++];
			}

			$add = /*.(array[int]string).*/ array();
			while ($yi < $n_to && $this->ychanged[$yi]) {
				$add[] = $to_lines[$yi++];
			}

			if ((count($delete) !== 0) && (count($add) !== 0)) {
				$edits[] = new ajaxUnit_Text_Diff_Op_change($delete, $add);
			} elseif (count($delete) !== 0) {
				$edits[] = new ajaxUnit_Text_Diff_Op_delete($delete);
			} elseif (count($add) !== 0) {
				$edits[] = new ajaxUnit_Text_Diff_Op_add($add);
			}
		}

		return $edits;
	}
}

/**
 * General API for generating and formatting diffs - the differences between
 * two sequences of strings.
 *
 * The original PHP version of this code was written by Geoffrey T. Dairiki
 * <dairiki@dairiki.org>, and is used/adapted with his permission.
 *
 * $Horde: framework/Text_Diff/Diff.php,v 1.11.2.12 2009/01/06 15:23:41 jan Exp $
 *
 * Copyright 2004 Geoffrey T. Dairiki <dairiki@dairiki.org>
 * Copyright 2004-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you did
 * not receive this file, see http://opensource.org/licenses/lgpl-license.php.
 *
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 */
class ajaxUnit_Text_Diff {

	/**
	 * Array of changes.
	 *
	 * @var array[int]object
	 */
	private $_edits;

	/**
	 * Computes diffs between sequences of strings.
	 */
	public /*.void.*/ function __construct(/*.array[int]string.*/ $array1, /*.array[int]string.*/ $array2)
	{
		$params = array($array1, $array2);
		$diff_engine = new ajaxUnit_Text_Diff_Engine_native();

		$this->_edits = /*.(array[int]object).*/ call_user_func_array(array($diff_engine, 'diff'), $params);
	}

	/**
	 * Returns the array of differences.
	 */
	public /*.array[int]object.*/ function getDiff()
	{
		return $this->_edits;
	}

	/**
	 * Computes a reversed diff.
	 *
	 * Example:
	 * <code>
	 * $diff = new Text_Diff($lines1, $lines2);
	 * $rev = $diff->reverse();
	 * </code>
	 *
	 * @return ajaxUnit_Text_Diff  A Diff object representing the inverse of the
	 *					original diff.  Note that we purposely don't return a
	 *					reference here, since this essentially is a clone()
	 *					method.
	 */
	private function reverse()
	{
		$rev		= ((boolean) version_compare(zend_version(), '2', '>')) ? clone($this) : $this;
		$rev->_edits	= /*.(array[int]object).*/ array();

		foreach ($this->_edits as $item) {
			$edit = /*.(ajaxUnit_Text_Diff_Op).*/ $item;
			$rev->_edits[] = $edit->reverse();
		}
		return $rev;
	}

	/**
	 * Computes the length of the Longest Common Subsequence (LCS).
	 *
	 * This is mostly for diagnostic purposes.
	 *
	 * @return integer  The length of the LCS.
	 */
	function lcs()
	{
		$lcs = 0;

		foreach ($this->_edits as $item) {
			$edit = /*.(ajaxUnit_Text_Diff_Op).*/ $item;
			if (is_a($edit, 'ajaxUnit_Text_Diff_Op_copy')) {
				$lcs += count($edit->originalArray);
			}
		}
		return $lcs;
	}

	/**
	 * Gets the original set of lines.
	 *
	 * This reconstructs the $from_lines parameter passed to the constructor.
	 *
	 * @return array  The original sequence of strings.
	 */
	private function getOriginal()
	{
		$lines = array();

		foreach ($this->_edits as $item) {
			$edit = /*.(ajaxUnit_Text_Diff_Op).*/ $item;
			if (isset($edit->originalArray)) {
				array_splice($lines, count($lines), 0, $edit->originalArray);
			}
		}
		return $lines;
	}

	/**
	 * Gets the final set of lines.
	 *
	 * This reconstructs the $to_lines parameter passed to the constructor.
	 *
	 * @return array  The sequence of strings.
	 */
	private function getFinal()
	{
		$lines = array();
		foreach ($this->_edits as $item) {
			$edit = /*.(ajaxUnit_Text_Diff_Op).*/ $item;
			if (isset($edit->finalArray)) {
				array_splice($lines, count($lines), 0, $edit->finalArray);
			}
		}
		return $lines;
	}

	/**
	 * Removes trailing newlines from a line of text. This is meant to be used
	 * with array_walk().
	 *
	 * @param string &$line  The line to trim.
	 * @param integer $key  The index of the line in the array. Not used.
	 */
	public static /*.void.*/ function trimNewlines(&$line, $key)
	{
		// $key is needed because that's the format of the array we're walking
		$line = (string) str_replace(array("\n", "\r"), '', $line);
	}
}

/**
 * A class to render Diffs in different formats.
 *
 * This class renders the diff in classic diff format. It is intended that
 * this class be customized via inheritance, to obtain fancier outputs.
 *
 * $Horde: framework/Text_Diff/Diff/Renderer.php,v 1.5.10.12 2009/07/24 13:26:40 jan Exp $
 *
 * Copyright 2004-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you did
 * not receive this file, see http://opensource.org/licenses/lgpl-license.php.
 *
 * @package Text_Diff
 */
class ajaxUnit_Text_Diff_Renderer {

	/**
	 * Number of leading context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses may want to
	 * set this to other values.
	 */
	private $_leading_context_lines = 0;

	/**
	 * Number of trailing context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses may want to
	 * set this to other values.
	 */
	private $_trailing_context_lines = 0;

	private /*.string.*/ function _startDiff()
	{
		return '';
	}

	private /*.string.*/ function _blockHeader(/*.int.*/ $xbeg, /*.int.*/ $xlen, /*.int.*/ $ybeg, /*.int.*/ $ylen)
	{
		if ($xlen > 1) {
			$xbegString = $xbeg . ',' . ($xbeg + $xlen - 1);
		}
		if ($ylen > 1) {
			$ybegString = $ybeg . ',' . ($ybeg + $ylen - 1);
		}

		// this matches the GNU Diff behaviour
		if (($xlen !== 0) && ($ylen === 0)) {
			$ybeg--;
		} elseif ($xlen === 0) {
			$xbeg--;
		}

		if (!isset($xbegString)) $xbegString = (string) $xbeg;
		if (!isset($ybegString)) $ybegString = (string) $ybeg;

		return $xbegString . (($xlen === 0) ? 'a' : (($ylen === 0) ? 'd' : 'c')) . $ybegString;
	}

	private /*.string.*/ function _startBlock(/*.string.*/ $header)
	{
		return $header . "\n";
	}

	private /*.string.*/ function _lines(/*.array[int]string.*/ $lines, $prefix = ' ')
	{
		return $prefix . implode("\n$prefix", $lines) . "\n";
	}

	private /*.string.*/ function _context(/*.array[int]string.*/ $lines)
	{
		return $this->_lines($lines, '  ');
	}

	private /*.string.*/ function _added(/*.array[int]string.*/ $lines)
	{
		return $this->_lines($lines, '> ');
	}

	private /*.string.*/ function _deleted(/*.array[int]string.*/ $lines)
	{
		return $this->_lines($lines, '< ');
	}

	private /*.string.*/ function _changed(/*.array[int]string.*/ $originalArray, /*.array[int]string.*/ $finalArray)
	{
		return $this->_deleted($originalArray) . "---\n" . $this->_added($finalArray);
	}

	private /*.string.*/ function _endBlock()
	{
		return '';
	}

	private /*.string.*/ function _block(/*.int.*/ $xbeg, /*.int.*/ $xlen, /*.int.*/ $ybeg, /*.int.*/ $ylen, /*.array[int]object.*/ &$edits)
	{
		$output = $this->_startBlock($this->_blockHeader($xbeg, $xlen, $ybeg, $ylen));

		foreach ($edits as $item) {
			$edit = /*.(ajaxUnit_Text_Diff_Op).*/ $item;
			switch (strtolower(get_class($edit))) {
			case 'ajaxunit_text_diff_op_copy':
				$output .= $this->_context($edit->originalArray);
				break;

			case 'ajaxunit_text_diff_op_add':
				$output .= $this->_added($edit->finalArray);
				break;

			case 'ajaxunit_text_diff_op_delete':
				$output .= $this->_deleted($edit->originalArray);
				break;

			case 'ajaxunit_text_diff_op_change':
				$output .= $this->_changed($edit->originalArray, $edit->finalArray);
				break;
			default:
			}
		}

		return $output . $this->_endBlock();
	}

	private /*.string.*/ function _endDiff()
	{
		return '';
	}

	/**
	 * Renders a diff.
	 *
	 * @param ajaxUnit_Text_Diff $diff  A ajaxUnit_Text_Diff object.
	 *
	 * @return string  The formatted output.
	 */
	function render($diff)
	{
		$x0 = $y0 = 0;
		$xi = $yi = 1;
		$context	= /*.(array[int]string).*/ array();
		$block		= /*.(array[int]object).*/ array();

		$nlead = $this->_leading_context_lines;
		$ntrail = $this->_trailing_context_lines;

		$output = $this->_startDiff();

		$diffs = $diff->getDiff();
		foreach ($diffs as $i => $item) {
			$edit = /*.(ajaxUnit_Text_Diff_Op).*/ $item;

			/* If these are unchanged (copied) lines, and we want to keep
			 * leading or trailing context lines, extract them from the copy
			 * block. */
			if (is_a($edit, 'ajaxUnit_Text_Diff_Op_copy')) {
				/* Do we have any diff blocks yet? */
				if (isset($block)) {
					/* How many lines to keep as context from the copy
					 * block. */
					$keep = $i == count($diffs) - 1 ? $ntrail : $nlead + $ntrail;
					if (count($edit->originalArray) <= $keep) {
						/* We have less lines in the block than we want for
						 * context => keep the whole block. */
						$block[] = $edit;
					} else {
						if ($ntrail !== 0) {
							/* Create a new block with as many lines as we need
							 * for the trailing context. */
							$context = /*.(array[int]string).*/ array_slice($edit->originalArray, 0, $ntrail);
							$block[] = new ajaxUnit_Text_Diff_Op_copy($context);
						}
						/* @todo */
						$output .= $this->_block($x0, $ntrail + $xi - $x0,
												 $y0, $ntrail + $yi - $y0,
												 $block);
						unset($block);
					}
				}
				/* Keep the copy block as the context for the next block. */
				$context = $edit->originalArray;
			} else {
				/* Don't we have any diff blocks yet? */
				if (!isset($block)) {
					/* Extract context lines from the preceding copy block. */
					$context = /*.(array[int]string).*/ array_slice($context, count($context) - $nlead);
					$x0 = $xi - count($context);
					$y0 = $yi - count($context);
					$block = /*.(array[int]object).*/ array();
					if (count($context) !== 0) {
						$block[] = new ajaxUnit_Text_Diff_Op_copy($context);
					}
				}
				$block[] = $edit;
			}

			if (isset($edit->originalArray)) {
				$xi += count($edit->originalArray);
			}
			if (isset($edit->finalArray)) {
				$yi += count($edit->finalArray);
			}
		}

		if (isset($block)) {
			$output .= $this->_block($x0, $xi - $x0,
									 $y0, $yi - $y0,
									 $block);
		}

		return $output . $this->_endDiff();
	}
}

class ajaxUnit_compare {
	private static /*.string.*/ function analyze(/*.array[int]string.*/ $array1, /*.array[int]string.*/ $array2) {
		$diff		= new ajaxUnit_Text_Diff($array1, $array2);
		$renderer	= new ajaxUnit_Text_Diff_Renderer();

		return htmlspecialchars($renderer->render($diff));
	}

	public static /*.string.*/ function investigateDifference(/*.string.*/ $results, /*.string.*/ $expected, /*.string.*/ $diffID, $dummyRun = false) {
		$logEntry	= '';

		$resultsMetric	= strlen($results);
		$expectedMetric	= strlen($expected);
		$analysis	= "Response has $resultsMetric characters, expected response has $expectedMetric<br />\n";

		$resultsMetric	= (int) str_word_count($results);
		$expectedMetric	= (int) str_word_count($expected);
		$analysis	.= "\t\t\t\tResponse has $resultsMetric words, expected response has $expectedMetric<br />\n";

		$percentFloat	= 0;
		$metric		= similar_text($results, $expected, $percentFloat);
		$percentString	= number_format($percentFloat, 2);
		$analysis	.= "\t\t\t\tText similarity: $metric characters ($percentString%)";

		$logEntry	.= ajaxUnit_log::format($analysis, 6);

		$diffText	= self::analyze(explode("\n", $expected), explode("\n", $results));

		$logEntry	.= ajaxUnit_log::format("<span class=\"ajaxunit-testlog\" onclick=\"ajaxUnit_toggle_log(this, '$diffID')\">+</span> Actual results compared with expected results", 6);
		$logEntry	.= ajaxUnit_log::format("<pre class=\"ajaxunit-testlog\" id=\"$diffID\">$diffText</pre>", 8, '');

		$logEntry	.= ajaxUnit_log::format("<span class=\"ajaxunit-testlog\" onclick=\"ajaxUnit_toggle_log(this, '$diffID-expected')\">+</span> Expected results", 6);
		$logEntry	.= ajaxUnit_log::format("<pre class=\"ajaxunit-testlog\" id=\"$diffID-expected\">" . bin2hex($expected) . "</pre>", 8, '');

		$logEntry	.= ajaxUnit_log::format("<span class=\"ajaxunit-testlog\" onclick=\"ajaxUnit_toggle_log(this, '$diffID-results')\">+</span> Actual results", 6);
		$logEntry	.= ajaxUnit_log::format("<pre class=\"ajaxunit-testlog\" id=\"$diffID-results\">" . bin2hex($results) . "</pre>", 8, '');

		$logEntry	.= ajaxUnit_log::format('<div style="margin-left:6em">' . ajaxUnit_cookies::toTable() . '</div>');

		return $logEntry;
	}

	private static /*.boolean.*/ function compareHTML($results = '', $expected = '', &$logEntry = '') {
		preg_match_all('/(?<=>|^)[^><\\r\\n]+(?=<|$|\\r|\\n)|<.*>/Um', $results, $matches);
		$resultsArray	= $matches[0];
		preg_match_all('/(?<=>|^)[^><\\r\\n]+(?=<|$|\\r|\\n)|<.*>/Um', $expected, $matches);
		$expectedArray	= $matches[0];
		$resultsLineCount	= count($resultsArray);
		$expectedLineCount	= count($expectedArray);
		$logEntry	.= ajaxUnit_log::format("Trying element-by-element comparison of $resultsLineCount elements...", 6);

		if ($resultsLineCount === $expectedLineCount) {
			$success = true;
		} else {
			$logEntry	.= ajaxUnit_log::format("Different number of lines: expecting $expectedLineCount", 8);
			$success	= false;
		}

		for ($line = 0; $line < $resultsLineCount; $line++) {
			$thisResultsLine	= $resultsArray[$line];
			$thisExpectedLine	= $expectedArray[$line];
			$lineOrdinal		= $line + 1;

			if ($thisResultsLine !== $thisExpectedLine) {
				$logEntry	.= ajaxUnit_log::format("Line $lineOrdinal is different to the expected line", 8);

				if ($thisResultsLine[0] === '<' && $thisExpectedLine[0] === '<') {
					$logEntry	.= ajaxUnit_log::format("Comparing line $lineOrdinal according to its HTML attributes", 8);

					// Compare attributes
					$thisResultsAttributes	= preg_split('/[\\s]+/', substr($thisResultsLine, 1, strlen($thisResultsLine) - 2));
					$thisExpectedAttributes	= preg_split('/[\\s]+/', substr($thisExpectedLine, 1, strlen($thisExpectedLine) - 2));
					$attributeCount		= count($thisResultsAttributes);
					$logEntry		.= ajaxUnit_log::format("Comparing $attributeCount attributes on line $lineOrdinal...", 8);

					if ($attributeCount === count($thisExpectedAttributes)) {
						$allAttributesSame = true;
						sort($thisResultsAttributes);
						sort($thisExpectedAttributes);
						$log = "<pre>|Actual|Expected|<br />\n";

						for ($attribute = 0; $attribute < $attributeCount; $attribute++) {
							$thisResultsAttribute	= $thisResultsAttributes[$attribute];
							$thisExpectedAttribute	= $thisExpectedAttributes[$attribute];
							$log .= '|'. htmlspecialchars($thisResultsAttribute) . "|" . htmlspecialchars($thisExpectedAttribute) . "|<br />\n";

							if ($thisResultsAttribute !== $thisExpectedAttribute) {
								if ($success) $logEntry	.= ajaxUnit_log::format("In line $lineOrdinal, '". htmlspecialchars($thisResultsAttribute) . "' (actual) was not the same as '" . htmlspecialchars($thisExpectedAttribute) . "' (expected)", 8);
								$success = false;
								$allAttributesSame = false;
							}
						}

						if ($allAttributesSame) {
							$logEntry	.= ajaxUnit_log::format('All attributes are identical. Lines must differ in another way (e.g. white space)', 8);
							$log		= "<pre>Actual:<br />\n".htmlspecialchars($thisResultsLine)."<br />\nExpected:<br />\n".htmlspecialchars($thisExpectedLine)."</pre>\n";
							$logEntry	.= ajaxUnit_log::format($log, 8);
						} else if (!$success) {
							$logEntry	.= ajaxUnit_log::format("$log</pre>", 8);
							break;
						}
					} else {
						$logEntry		.= ajaxUnit_log::format("Different number of attributes in line $lineOrdinal", 8);
						$success = false;
						break;
					}
				} else {
					$log				= "<pre>Actual:<br />\n".htmlspecialchars($thisResultsLine)."<br />\nExpected:<br />\n".htmlspecialchars($thisExpectedLine)."</pre>\n";
					$logEntry			.= ajaxUnit_log::format($log, 8);
					$success = false;
				}
			}
		}

		return $success;
	}

	public static /*.boolean.*/ function compare(/*.string.*/ $results, /*.string.*/ $expected, /*.string.*/ &$logEntry = '') {
		$success = ($results === $expected); // This is the whole point of all this!

		if (!$success) {
			$logEntry	= ajaxUnit_log::format('Byte-for-byte match not successful, trying looser EOL definition...', 6);
			// Try substituting \r\n for \n in strings (because bits of either may come from a Windows file)
			$results	= (string) str_replace("\r\n", "\n", $results);
			$expected	= (string) str_replace("\r\n", "\n", $expected);
			$success	= ($results === $expected);
		}

		if (!$success) {
			$logEntry	= ajaxUnit_log::format('Still different even with looser EOL definition', 6);
			$success	= self::compareHTML($results, $expected, $logEntry);
		}

		return $success;
	}
}

/**
 * The methods needed to manage the running of an individual test
 *
 * @package ajaxUnit
 */
interface I_ajaxUnit_test extends I_ajaxUnit_environment {
//	public /*.int.*/	function count();
	public /*.string.*/	function name();
	public /*.string.*/	function description();
	public /*.string.*/	function rubric();
	public /*.string.*/	function resultSet();
	public /*.DOMElement.*/	function results();
	public /*.void.*/	function update();
//	public /*.boolean.*/	function initiate($dummyRun = false);
	public /*.void.*/	function doNextTest($dummyRun = false);
}

/**
 * The methods needed to manage the running of an individual test
 *
 * @package ajaxUnit
 */
class ajaxUnit_test extends ajaxUnit_environment implements I_ajaxUnit_test {
	private /*.string.*/		$suite;
	private /*.int.*/		$testIndex		= -1;
	private /*.DOMDocument.*/	$document;
	private /*.DOMNodeList.*/	$testList;
	private /*.DOMElement.*/	$test;
	private /*.string.*/		$resultsNodeName;

	private /*.int.*/ function count() {
		$count = $this->testList->length;
		if (empty($count)) $count = 0;
		return $count;
	}

	public /*.string.*/ function name() {
		return ($this->test->hasAttribute(self::ATTRNAME_NAME)) ? $this->test->getAttribute(self::ATTRNAME_NAME) : (string) $this->testIndex;
	}

	public /*.string.*/ function description() {
		$numberType	= ($this->test->hasAttribute(self::ATTRNAME_NAME)) ? 'name' : 'index';
		$testName	= $this->name();

		return "Test $numberType is <strong>$testName</strong>";
	}

	public /*.string.*/ function rubric() {
		$rubricList	= $this->test->getElementsByTagName(self::TAGNAME_RUBRIC);
		return (empty($rubricList->length) || ($rubricList->length === 0)) ? '' : $rubricList->item(0)->nodeValue;
	}

	public /*.string.*/ function resultSet() {
		return $this->resultsNodeName;
	}

	public /*.DOMElement.*/ function results() {
		$resultsList = $this->test->getElementsByTagName($this->resultsNodeName);

		if (empty($resultsList->length)) {
			return $this->document->createElement($this->resultsNodeName);
		} else {
			$resultsNode = $resultsList->item(0);
	/*.object.*/	$resultsObject		= $resultsNode;		// PHP-compliant typecasting
			return /*.(DOMElement).*/ $resultsObject;	// PHP-compliant typecasting
		}
	}

	public /*.void.*/ function update() {
		self::updateTestSuite($this->suite, $this->document);
	}

	private /*.void.*/ function getTestFromList() {
		$node		= $this->testList->item($this->testIndex);	// Get this particular test
/*.object.*/	$nodeObject	= $node;					// PHP-compliant typecasting
		$this->test	= /*.(DOMElement).*/ $nodeObject;		// PHP-compliant typecasting
	}

	public /*.void.*/ function __construct() {
		// Get some context for this test
		$context		= /*.(array[string]string).*/ self::getTestContext();
		$this->resultsNodeName	= $context[self::TAGNAME_RESULTSNODENAME];
		$this->suite		= $context[self::TAGNAME_SUITE];
		$this->testIndex	= (int) $context[self::TAGNAME_INDEX];
		$this->document		= self::getDOMDocument($this->suite);		// Get the test suite information
		$this->testList		= self::getTestList($this->document);		// Get list of tests

		if ($this->count() === 0) self::terminate(self::STATUS_FATALERROR, 'There are no tests defined in this test suite');

		$this->getTestFromList();
	}

	private /*.boolean.*/ function next() {
		$this->testIndex++;

		if ($this->testIndex < $this->count()) {
			self::setTestContext(self::TAGNAME_INDEX, (string) $this->testIndex);
			$this->getTestFromList();
			return true;
		} else {
			return false;
		}
	}

	private static /*.boolean.*/ function doClick(DOMElement $element, $dummyRun = false) {
		$id = self::substituteParameters($element->getAttribute(self::ATTRNAME_ID));
		self::appendLog("Click button <strong>$id</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doCookies(DOMNodeList $nodeList, $dummyRun = false) {
		self::appendLog("Update cookies", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				/*.object.*/ $nodeObject = $node;			// PHPLint-compliant typecasting
				$element	= /*.(DOMElement).*/ $nodeObject;	// PHPLint-compliant typecasting

				$action		= $element->nodeName;
				$name		= self::substituteParameters($element->getAttribute(self::ATTRNAME_NAME));

				if ($dummyRun) {
					$value	= $element->getAttribute(self::ATTRNAME_VALUE);
					$days	= $element->getAttribute(self::ATTRNAME_DAYS);
					self::appendLog("$action: $name -> $value ($days)", $dummyRun);
				} else {
					switch ($action) {
					case self::TAGNAME_DELETE:
						self::appendLog(ajaxUnit_cookies::remove($name), $dummyRun);
						break;
					case self::TAGNAME_SET:
						$value	= $element->getAttribute(self::ATTRNAME_VALUE);
						$days	= $element->getAttribute(self::ATTRNAME_DAYS);
						self::appendLog(ajaxUnit_cookies::set($name, $value, $days), $dummyRun);
						break;
					default:
					}
				}
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doCustomURL($dummyRun = false) {
		self::appendLog("Custom URL", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doRubric(DOMElement $element, $dummyRun = false) {
		$log = '<em>' . $element->nodeValue . '</em>';
		self::appendLog($log, $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doFileOps(DOMNodeList $nodeList, $dummyRun = false) {
		self::appendLog("File operations", $dummyRun, 2);

		$success = true; // Assume true for once

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				/*.object.*/ $nodeObject = $node;			// PHPLint-compliant typecasting
				$element	= /*.(DOMElement).*/ $nodeObject;	// PHPLint-compliant typecasting

				$action	= $element->nodeName;

				switch ($action) {
				case self::TAGNAME_COPY:
					$source		= self::substituteParameters($element->getAttribute(self::ATTRNAME_SOURCE));
					$destination	= self::substituteParameters($element->getAttribute(self::ATTRNAME_DESTINATION));

					self::appendLog("Copying <em>$source</em> to <em>$destination</em>", $dummyRun);
					if (!$dummyRun) @copy($source, $destination);
					break;
				case self::TAGNAME_DELETE:
					$name		= self::substituteParameters($element->getAttribute(self::ATTRNAME_NAME));

					self::appendLog("Deleting <em>$name</em>", $dummyRun);

					if (!$dummyRun && is_file($name)) @unlink($name);
					break;
				case self::TAGNAME_CHECKSIZE:
					$name		= self::substituteParameters($element->getAttribute(self::ATTRNAME_NAME));
					$expected	= (int) $element->nodeValue;

					self::appendLog("Checking size of <em>$name</em>, should be " . (string) $expected . ' bytes', $dummyRun);

					if ($dummyRun) break;

					if (is_file($name)) {
						$result	= @filesize($name);

						if ($result === false) {
							self::appendLog('Unexpected problem getting file size', $dummyRun, 6);
							$success	= false;
						} else {
							if ($result === $expected) {
								self::appendLog('File size is correct', $dummyRun, 6);
							} else {
								self::appendLog('File size is ' . (string) $result . ' bytes', $dummyRun, 6);
								$success = false;
							}
						}
					} else {
						self::appendLog('File not found', $dummyRun, 6);
						$success = false;
					}

					break;
				default:
					self::appendLog("Unknown file operation: <em>$action</em>", $dummyRun);
					$success = false;
				}
			}
		}

		return $success;
	}

	private static /*.boolean.*/ function doFormFill(DOMNodeList $nodeList, $dummyRun = false) {
		self::appendLog("Update form fields", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				/*.object.*/ $nodeObject = $node;			// PHPLint-compliant typecasting
				$element	= /*.(DOMElement).*/ $nodeObject;	// PHPLint-compliant typecasting

				$type	= self::substituteParameters($element->nodeName);
				$value	= self::substituteParameters($element->nodeValue);
				$id	= self::substituteParameters($element->getAttribute(self::ATTRNAME_ID));

				self::appendLog("Setting $type control <strong>$id</strong> to <em>$value</em>", $dummyRun);
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doHeaders(DOMNodeList $nodeList, $dummyRun = false) {
		self::appendLog("Send HTTP headers", $dummyRun, 2);
		if (headers_sent()) {
			self::appendLog("<strong>Warning: headers have already been sent</strong>");
			return false;
		}

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$header	= $node->nodeName;
				$value	= $node->nodeValue;

				$value	= self::substituteParameters($value);
				self::appendLog("Setting <strong>$header</strong> to <em>$value</em>", $dummyRun);

				if (!$dummyRun) {
					if (strtolower($header) === 'location') {
						header("Cache-Control: no-cache");
						header("Pragma: no-cache");
						header("$header: $value");
//						echo '.';
//						exit;
					} else {
						header("$header: $value");
					}
				}
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doIncludePath(DOMNodeList $nodeList, $dummyRun = false) {
		self::appendLog("Include path", $dummyRun, 2);
		self::appendLog("Current include path is <em>" . get_include_path() . "</em>", $dummyRun);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				switch ($node->nodeName) {
				case self::TAGNAME_ADD:
					$path = realpath(self::substituteParameters($node->nodeValue));
					self::appendLog("Adding <em>$path</em> to include path", $dummyRun);

					if (!$dummyRun) {
						$newPath = get_include_path() . PATH_SEPARATOR . $path;
						set_include_path($newPath);
					}
					break;
				case self::TAGNAME_RESET:
					self::appendLog("Restoring include path", $dummyRun);
					if (!$dummyRun) restore_include_path();
					break;
				default:
				}
			}
		}

		self::appendLog("New include path is <em>" . get_include_path() . "</em>", $dummyRun);
		return true;
	}

	private static /*.boolean.*/ function doLocation(DOMElement $element, $dummyRun = false) {
		$url = self::substituteParameters($element->getAttribute(self::ATTRNAME_URL));
		self::appendLog("Set location <strong>$url</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doLogAppend(DOMElement $element, $dummyRun = false) {
		$content = self::substituteParameters($element->nodeValue);
		self::appendLog("Adding to browser log: <strong>$content</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doPost(DOMElement $element, $dummyRun = false) {
		$id = self::substituteParameters($element->getAttribute(self::ATTRNAME_ID));
		self::appendLog("Posting contents of element <strong>$id</strong>", $dummyRun, 2);
		return true;
	}

	private /*.boolean.*/ function doRemove(DOMElement $step, $dummyRun = false) {
		$stepType = $step->nodeName;
		$this->test->removeChild($step);
		self::appendLog("Removing element <strong>$stepType</strong> from the instructions to be sent to the browser", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doSession(DOMNodeList $nodeList, $dummyRun = false) {
		self::appendLog("Server session handling", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				switch ($node->nodeName) {
				case self::TAGNAME_RESET:
					self::appendLog("Resetting session...", $dummyRun);

					if (!$dummyRun) {
						session_start();
						session_destroy();
						$_SESSION = /*.(array[string]mixed).*/ array();
					}

					self::appendLog("Session reset", $dummyRun);
					break;
				default:
				}
			}
		}

		return true;
	}

	private /*.boolean.*/ function initiate($dummyRun = false) {
		$context		= self::setInitialContext($this->test, $this->testIndex);
		$testName		= $this->test->hasAttribute(self::ATTRNAME_NAME) ? $this->test->getAttribute(self::ATTRNAME_NAME) : "#" . ($this->testIndex + 1);
		$testId			= htmlspecialchars($testName);
		$sendToBrowser		= false;
		$success		= false;

		self::appendLog("<span class=\"ajaxunit-testlog\" onclick=\"ajaxUnit_toggle_log(this, 'ajaxunit-$testId')\">+</span> Test <strong>$testName</strong>", $dummyRun, 0, 'p', 3);
		self::appendLog("<div class=\"ajaxunit-testlog\" id=\"ajaxunit-$testId\">", $dummyRun, 0, '', 3);

		$childNodes	= $this->test->childNodes;

		// Do any server-based actions
		for ($i = 0; $i < $childNodes->length; $i++) {
			$node = $childNodes->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				/*.object.*/ $nodeObject = $node;		// PHPLint-compliant typecasting
				$step	= /*.(DOMElement).*/ $nodeObject;	// PHPLint-compliant typecasting

				$stepType	= $step->nodeName;
				$stepList	= $step->childNodes;

				if (substr($stepType, 0, strlen(self::TAGNAME_IGNORE))	=== self::TAGNAME_IGNORE)	$stepType = self::TAGNAME_IGNORE;
				if (substr($stepType, 0, strlen(self::TAGNAME_RESULTS))	=== self::TAGNAME_RESULTS)	$stepType = self::TAGNAME_RESULTS;

				switch ($stepType) {
					case self::TAGNAME_CLICK:	$success = self::doClick	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_COOKIES:	$success = self::doCookies	($stepList,	$dummyRun);					break;
					case self::TAGNAME_CUSTOMURL:	$success = self::doCustomURL	(		$dummyRun);					break;
					case self::TAGNAME_FILE:	$success = self::doFileOps	($stepList,	$dummyRun);					break;
					case self::TAGNAME_FORMFILL:	$success = self::doFormFill	($stepList,	$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_HEADERS:	$success = self::doHeaders	($stepList,	$dummyRun);					break;
					case self::TAGNAME_IGNORE:	$success = $this->doRemove	($step,		$dummyRun);					break;
					case self::TAGNAME_INCLUDEPATH:	$success = self::doIncludePath	($stepList,	$dummyRun);					break;
					case self::TAGNAME_LOCATION:	$success = self::doLocation	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_LOGAPPEND:	$success = self::doLogAppend	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_POST:	$success = self::doPost		($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_RESULTS:	$success = $this->doRemove	($step,		$dummyRun);					break;
					case self::TAGNAME_RUBRIC:	$success = self::doRubric	($step,		$dummyRun);					break;
					case self::TAGNAME_SESSION:	$success = self::doSession	($stepList,	$dummyRun);					break;
					case self::TAGNAME_STOP:	$success = false;										break;
					default:			$success = true;										break;
				}

				if (!$success) break;
			}
		}

		if ($success) {
			if ($sendToBrowser) {
				// We've got the next test details so send them to the browser script
				self::appendLog('Sending instructions to browser', false, 2);
				$xml = self::substituteParameters($this->document->saveXML($this->test));
				self::sendContent($xml, self::ACTION_PARSE, 'text/xml');
			} else {
				self::appendLog('Nothing further to send to browser', false, 2);
			}

			if ($context[self::TAGNAME_EXPECTINGCOUNT] === '0') {
				self::appendLog('No results expected from browser', false, 2);
				self::logResult($success, $dummyRun); // $success = true at this point
			} else {
				self::appendLog('Waiting for ' . $context[self::TAGNAME_EXPECTINGCOUNT] . ' results', false, 2);
				exit;
			}
		} else {
			self::terminate(self::STATUS_FATALERROR, '', $success, $dummyRun);
		}

		return $success;
	}

	public /*.void.*/ function doNextTest($dummyRun = false) {
		// If $this->initiate returns then we're not expecting any results, so move on to the next test.
		do {
			if (!$this->next()) {$success = true; break;}
			$success = $this->initiate($dummyRun);
		} while ($success);

		if ($success) {
			// Should only get here if we've successfully completed all the tests
			self::terminate(self::STATUS_FINISHED, '', $success, $dummyRun);
		}
	}
}
// End of class ajaxUnit_test


/**
 * The methods needed to parse some results
 *
 * @package ajaxUnit
 */
interface I_ajaxUnit_results extends I_ajaxUnit_environment {
	public static /*.boolean.*/ function parse(/*.boolean.*/ $dummyRun = false);
}

/**
 * The methods needed to parse some results
 *
 * @package ajaxUnit
 */
class ajaxUnit_results extends ajaxUnit_environment implements I_ajaxUnit_results {
	private static /*.boolean.*/ function doResults(DOMNodeList $nodeList, /*.string.*/ $results, $dummyRun = false) {
		self::appendLog("Compare actual results with expected", $dummyRun);

		$context	= /*.(array[string]string).*/ self::getTestContext();

		if (!array_key_exists(self::TAGNAME_STATUS, $context)) {
			echo 'Unknown testing status';
			return false;
		}

		if (in_array($context[self::TAGNAME_STATUS], array('', self::STATUS_FINISHED, self::STATUS_FAIL, self::STATUS_FATALERROR))) {
			echo 'No testing in progress';
			return false;
		}

		$responseList	= $context[self::TAGNAME_RESPONSELIST];
		$success	= false;
		$resultIndex	= -1;

		// Each <result> element is a potential match for $results
		// If none of them match then it's a FAIL
		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$resultIndex++;

				$indexText = (string) ($resultIndex + 1);

				// Have we already matched this for a previous result?
				if (strpos($responseList, (string) $resultIndex) === false) {
					self::appendLog("Test data set #$indexText has already been matched with a previous result", $dummyRun, 6);
				} else {
					self::appendLog("Comparing result with test data set #$indexText...", $dummyRun, 6);
					$results = htmlspecialchars_decode($results);
					if (get_magic_quotes_gpc() !== 0) $results = stripslashes($results);			// magic_quotes_gpc will go away soon, but for now

					$expected	= htmlspecialchars_decode(self::substituteParameters($node->nodeValue));
					$expected	= (string) str_replace(chr(0xEF).chr(0xBB).chr(0xBF), '', $expected);	// Get rid of stray UTF-8 BOMs from XIncluded files

					if ($dummyRun) 
						$success	= true;
					else {
						$logEntry	= '';
						$success	= ajaxUnit_compare::compare($results, $expected, $logEntry);	// Compare results with expected

						self::appendLog($logEntry, $dummyRun, 0, '');
					}

					if ($success) {
						self::appendLog("Match with test data set #$indexText", $dummyRun, 6);
						// Remove this results set from the list of responses we are expecting
						$indexPos	= strpos($responseList, (string) $resultIndex);
						$responseList	= substr($responseList, 0, $indexPos) . substr($responseList, $indexPos + 1);
						self::setTestContext(self::TAGNAME_RESPONSELIST, $responseList);
						break;
					} else {
						$diffID		= 'ajaxunit-diff-' . $context[self::TAGNAME_INDEX] . '-' . $context[self::TAGNAME_RESPONSECOUNT] . '-' . (string) $i;
						$logEntry	= ajaxUnit_compare::investigateDifference($results, $expected, $diffID, $dummyRun);

						self::appendLog($logEntry, $dummyRun, 0, '');
					}
				}
			}
		}

		$result = ($success) ? "Match found!" : "No match found";
		self::appendLog($result, $dummyRun, 6);
		return $success;
	}

/**
 * If the test suite needs some model results then use these
 */
	private static /*.boolean.*/ function modelRequired(DOMElement $element, /*.boolean.*/ $finalResult = false) {
		if (!$element->hasAttribute(self::ATTRNAME_UPDATE)) return false;

		// Use these as model results
		self::appendLog("Using these as model results");
		$updateStatus = $element->getAttribute(self::ATTRNAME_UPDATE);

		if ($updateStatus !== self::STATUS_INPROGRESS) {
			self::appendLog("Clearing existing model results", false, 6);
			// Clear away the children & set the status
			while ($element->hasChildNodes()) $element->removeChild($element->lastChild);
			$element->setAttribute(self::ATTRNAME_UPDATE, self::STATUS_INPROGRESS);
		}

		self::appendLog("Adding this result", false, 6);
		$element->appendChild(new DOMElement(self::TAGNAME_RESULT, $_POST['responseText']));	// Add model result

		if ($finalResult) {
			self::appendLog("No more results expected", false, 6);
			$element->removeAttribute(self::ATTRNAME_UPDATE);
		}

		return true;
	}
/**
 * Called by the browser when it receives an AJAX responseText
 *
 * @return boolean OK to move on to next test
 */
	public static function parse(/*.boolean.*/ $dummyRun = false) {
		self::appendLog('Results received', $dummyRun, 2);

		// Get some context for this test
		$context = /*.(array[string]string).*/ self::getTestContext();

		// Wait for previous reponse to be parsed
		$totalSleeps	= 0;
		$maxCounter	= (int) (self::TEST_MAXWAIT / self::TEST_WAITUSECS);

		while ($context[self::TAGNAME_PARSING] === '1') {
			if ($totalSleeps++ > $maxCounter) return self::terminate(self::STATUS_FATALERROR, 'Waited too long for another result to be parsed', false, $dummyRun);
			self::appendLog('(Another response is being parsed. Waiting ' . self::TEST_WAITUSECS . ' microseconds)', $dummyRun, 2);
			usleep(self::TEST_WAITUSECS);
			$context = /*.(array[string]string).*/ self::getTestContext();
		}

		self::setTestContext(self::TAGNAME_PARSING, '1');
//		self::appendLog("Test results being parsed at request of browser", $dummyRun, 2);

		// Get some context for this test
		$test = new ajaxUnit_test();
		self::appendLog($test->description(), $dummyRun);

		$expected = (int) $context[self::TAGNAME_EXPECTINGCOUNT];

		if ($expected < 1) {
			self::appendLog("No further responses expected", $dummyRun);
			$success = true;
			self::logResult($success, $dummyRun); // $success = true at this point
			return true;
		}

		// Check response count is (a) valid and (b) all we are expecting on this page
		if (!isset($context[self::TAGNAME_RESPONSECOUNT])) return self::terminate(self::STATUS_FATALERROR, '<strong>No response count set!</strong>', false, $dummyRun);
		$responseCount = (int) $context[self::TAGNAME_RESPONSECOUNT];

		if (++$responseCount < 1)	return self::terminate(self::STATUS_FATALERROR, "<strong>Response count hasn't been set correctly! ($responseCount)</strong>", false, $dummyRun);
		else				self::setTestContext(self::TAGNAME_RESPONSECOUNT, (string) $responseCount);

		self::appendLog("Response count is $responseCount (expecting $expected).", $dummyRun);
		self::appendLog('Using result set <em>' . $test->resultSet() . '</em>', $dummyRun);

		// Examine the results element
		$resultsElement = $test->results();
		$success = self::modelRequired($resultsElement, ($responseCount >= $expected));

		if ($success) {
			// We've added model answers, so write out the test suite
			self::appendLog("Updating test suite", false, 6);
			if (!$dummyRun) $test->update();
		} else {
			if (isset($_POST['responseText'])) {
				// Compare these results against the model results
				$results	= ($dummyRun) ? '' : (string) $_POST['responseText'];
				$success	= self::doResults($resultsElement->childNodes, $results, $dummyRun);	// Is it the same?
			} else {
				self::appendLog("No responseText in results received", $dummyRun, 2);
				$success = false;
			}

			if (!$success) return self::terminate(self::STATUS_FAIL, '', $success, $dummyRun);
		}

		if (!$dummyRun && ($responseCount < $expected)) {
			self::appendLog("Waiting for further responses from browser", $dummyRun, 2);
			self::setTestContext(self::TAGNAME_PARSING, '0');
			return false;
		}

		self::logResult($success, $dummyRun); // $success = true at this point
		return $success;
	}
}
// End of class ajaxUnit_results


/**
 * All the methods needed to control the test run
 *
 * @package ajaxUnit
 */
interface I_ajaxUnit_control extends I_ajaxUnit_environment {
	public static /*.void.*/	function parseResults(/*.boolean.*/ $dummyRun = false);
	public static /*.void.*/	function runTestSuite(/*.string.*/ $suite, $dummyRun = false);
	public static /*.void.*/	function endTestSuite($message = '', $dummyRun = false);
}

/**
 * All the methods needed to control the test run
 *
 * @package ajaxUnit
 */
abstract class ajaxUnit_control extends ajaxUnit_environment implements I_ajaxUnit_control {
	public static /*.void.*/ function parseResults(/*.boolean.*/ $dummyRun = false) {
		if (ajaxUnit_results::parse($dummyRun)) {
			$test = new ajaxUnit_test();
			$test->doNextTest($dummyRun);
		}
	}

/**
 * Initiate a named series of tests
 */
	public static /*.void.*/ function runTestSuite(/*.string.*/ $suite, /*.boolean.*/ $dummyRun = false) {
		$context				= /*.(array[string]string).*/ self::getTestContext();
		$context[self::TAGNAME_UID]		= gmdate('YmdHis');
		$context[self::TAGNAME_SUITE]		= $suite;
		$context[self::TAGNAME_STATUS]		= self::STATUS_INPROGRESS;
		$context[self::TAGNAME_COUNT]		= "0";
		$context[self::TAGNAME_INDEX]		= "-1";

		$browser				= new ajaxUnit_browser();
		$context[self::TAGNAME_BROWSER]		= $browser->Name;
		$context[self::TAGNAME_BROWSERVERSION]	= $browser->Version;

		self::setTestContext($context);

		$document	= self::getDOMDocument($suite);
		$text		= ($dummyRun) ? "Dummy" : "Starting";
		$suiteNode	= $document->getElementsByTagName(self::TAGNAME_SUITE)->item(0);
/*.object.*/	$suiteObject	= $suiteNode;				// PHPLint-compliant typecasting
		$suiteElement	= /*.(DOMElement).*/ $suiteObject;	// PHPLint-compliant typecasting
		$suiteName	= htmlspecialchars($suiteElement->getAttribute(self::ATTRNAME_NAME));
		$suiteVersion	= $suiteElement->getAttribute("version");

		self::appendLog(self::htmlPageTop(), $dummyRun, 0, '', 0);
		self::appendLog("<h3>$text run of test suite \"$suiteName\" version $suiteVersion</h3>", $dummyRun, 0, '', 3);
		self::appendLog("<hr />", $dummyRun, 0, '', 3);

		// Add test script to log
// Not often useful and makes the log a lot bigger		
//		self::logTestScript($document, $dummyRun);

		// Get global parameters
		$nodeList = $document->getElementsByTagName(self::TAGNAME_PARAMETERS);

		if ($nodeList->length !== 0) {
			$node	= $nodeList->item(0);
			$length	= $node->childNodes->length;

			for ($i = 0; $i < $length; $i++) {
				$parameter = $node->childNodes->item($i);
				if ($parameter->nodeType === XML_ELEMENT_NODE) $context[$parameter->nodeName] = self::substituteParameters($parameter->nodeValue);
			}
		}

		// Set the controls for the heart of the sun
		self::setTestContext($context);
		self::logTestContext($dummyRun);

		// Run first test
		$test = new ajaxUnit_test();
		$test->doNextTest($dummyRun);
	}

/**
 * Abandon running tests
 */
	public static /*.void.*/ function endTestSuite(/*.string.*/ $message = '', /*.boolean.*/ $dummyRun = false) {
		self::appendLog("Testing ended at request of browser", $dummyRun);
		self::terminate(self::STATUS_FINISHED, $message, false, $dummyRun);
	}
}
// End of class ajaxUnit_control


/**
 * User interface class
 *
 * @package ajaxUnit
 */
interface I_ajaxUnit extends I_ajaxUnit_control {
//	public static /*.void.*/	function setProject(/*.string.*/ $project);
	public static /*.void.*/	function setRoot(/*.string.*/ $folder);
	public static /*.void.*/	function getCSS();
	public static /*.void.*/	function getJavascript();
	public static /*.string.*/	function getIcon($filename = '', $sendToBrowser = true);
	public static /*.void.*/	function getControlPanel();
}

/**
 * User interface class
 *
 * @package ajaxUnit
 */
class ajaxUnit extends ajaxUnit_control implements I_ajaxUnit {
// ---------------------------------------------------------------------------
// Helper functions
// ---------------------------------------------------------------------------
	private static /*.void.*/ function setProject(/*.string.*/ $project) {
		self::setTestContext(self::TAGNAME_PROJECT, $project);
	}

	public static /*.void.*/ function setRoot(/*.string.*/ $folder) {
		$baseURL = (string) str_replace(DIRECTORY_SEPARATOR, '/', $folder);

		self::setTestContext(self::TAGNAME_FOLDER_BASE,		$folder);
		self::setTestContext(self::TAGNAME_FOLDER_LOGS,		$folder.DIRECTORY_SEPARATOR.self::LOG_FOLDER);
		self::setTestContext(self::TAGNAME_FOLDER_TESTS,	$folder.DIRECTORY_SEPARATOR.self::TESTS_FOLDER);
		self::setTestContext(self::TAGNAME_URL_BASE,		$baseURL);
		self::setTestContext(self::TAGNAME_URL_LOGS,		$baseURL.'/'.self::LOG_FOLDER);
		self::setTestContext(self::TAGNAME_URL_TESTS,		$baseURL.'/'.self::TESTS_FOLDER);
	}

// ---------------------------------------------------------------------------
// UI features
// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlCSS() {
		$css		= <<<GENERATED
@charset "UTF-8";
/**
 * Testing PHP and javascript by controlling the interactions automatically
 * 
 * Copyright (c) 2008-2010, Dominic Sayers							<br>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     - Neither the name of Dominic Sayers nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic@sayers.cc>
 * @copyright	2008-2010 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxUnit/
 * @version	0.17.30 - Added 'ignore' tag - just put 'ignore' before any test element to ignore it
 */

.dummy {} /* Webkit is ignoring the first item so we'll put a dummy one in */

form.ajaxunit-form		{margin:0;}
fieldset.ajaxunit-fieldset	{padding:0;border:0;margin:0 0 1em 0}
input.ajaxunit-buttonstate-0	{background-color:#FFFFFF;color:#444444;border-color:#666666 #333333 #333333 #666666;}
div.ajaxunit-testlog		{display:none;}
span.ajaxunit-testlog		{font-weight:bold;font-size:16px;cursor:pointer;}
p.ajaxunit-testlog		{margin:0;}
p.ajaxunit-footnote		{font-size:9px;color:#666666;}
p.ajaxunit-footnote strong	{font-weight:bold;color:red;}
p.ajaxunit-rubric			{width:640px;font-family:Cambria, "Times New Roman", Times, sans-serif;font-size:12px;font-style:italic;}
pre.ajaxunit-testlog		{margin:0 0 0 6em;background-color:#F0F0F0;display:none;}
iframe.ajaxunit-iframe		{border:1px black solid;}
table				{border: 1px solid #FFFFFF;background-color: #C0C0C0;}

div#ajaxunit {
	font-family:"Segoe UI", Calibri, Arial, Helvetica, sans-serif;
	font-size:11px;
	line-height:16px;
	float:left;
	margin:0;
}

label.ajaxunit-label {
	margin:5px 0 0 0;
	float:left;
}

input.ajaxunit-text {
	float:left;
	font-size:11px;
	width:480px;
	margin:3px 0 0 7px;
}

input.ajaxunit-button {
	float:left;
	padding:2px;
	margin:0 7px 0 7px;
	font-family:"Segoe UI", Calibri, Arial, Helvetica, sans-serif;
	border-style:solid;
	border-width:1px;
	cursor:pointer;
}

GENERATED;
// Generated code - do not modify in built package

		return $css;
	}

	public static /*.void.*/ function getCSS() {
		$html = self::htmlCSS();
		self::sendContent($html, 'CSS', 'text/css');
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlJavascript() {
		$parseURL = $URL	= self::thisURL();
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
		$tagRubric		= self::TAGNAME_RUBRIC;

		$attrID			= self::ATTRNAME_ID;
		$attrURL		= self::ATTRNAME_URL;

		$js = <<<GENERATED
/**
 * Testing PHP and javascript by controlling the interactions automatically
 * 
 * Copyright (c) 2008-2010, Dominic Sayers							<br>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     - Neither the name of Dominic Sayers nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic@sayers.cc>
 * @copyright	2008-2010 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxUnit/
 * @version	0.17.30 - Added 'ignore' tag - just put 'ignore' before any test element to ignore it
 */

/*jslint onevar: true, undef: true, nomen: true, eqeqeq: true, regexp: true, newcap: true, immed: true, strict: true */
/*global window, document, event, ActiveXObject */ // For JSLint
//"use strict";
var ajaxUnitInstances = [];

// ---------------------------------------------------------------------------
//		ajaxUnit
// ---------------------------------------------------------------------------
// The main ajaxUnit client-side class
// ---------------------------------------------------------------------------
function C_ajaxUnit() {
	this.getControl	= function (id)		{return document.getElementById(id);};
	this.getValue	= function (id)		{return this.getControl(id).value;};
	this.setValue	= function (id, value)	{this.getControl(id).value = value;};

	var that = this;

// ---------------------------------------------------------------------------
	function fillContainer(id, html) {
		var container = that.getControl(id);
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
			if (nodeList[i].title === 'ajaxUnit') {
				found = true;
				break;
			}
		}

		if (found === false) {
			node		= document.createElement('link');
			node.type	= 'text/css';
			node.rel	= 'stylesheet';
			node.href	= '$URL?$actionCSS';
			node.title	= 'ajaxUnit';
			htmlHead.appendChild(node);
		}
	}

// ---------------------------------------------------------------------------
	function logAppend(text, fail) {
		var	id		= 'ajaxunit-log',
			container	= that.getControl(id),
			markupStart	= '',
			markupEnd	= '',
			element;

		// if log div doesn't exist then add it to the page
		if (container === null || typeof container === 'undefined') {
			container		= document.createElement('div');
			container.id		= id;
//-			container.style.cssText	= 'width:420px;font-family:Segoe UI, Calibri, Arial, Helvetica, sans-serif;font-size:11px;line-height:16px;margin:0;clear:left;background-color:#FFFF88;';
			container.style.cssText	= 'font-family:Segoe UI, Calibri, Arial, Helvetica, sans-serif;font-size:11px;line-height:16px;margin:0;clear:left;background-color:#FFFF88;';
			document.getElementsByTagName('body')[0].appendChild(container);
		}

		// Log a fail?
		if (arguments.length === 1) {fail = false;}

		if (fail)			{
			markupStart	= '<strong>';
			markupEnd	= '</strong>';
			that.ajax.serverTalk('GET', 'end=' + encodeURI(text));
		}

		// Add to the log
		element			= document.createElement('p');
		element.style.margin	= 0;
		element.innerHTML	= markupStart + text + markupEnd;

		container.appendChild(element);
//		window.alert(text); // Uncomment this to step through the tests
	}

// ---------------------------------------------------------------------------
	function fireEvent(control, eventType, detail) {
		var e, result; // Returned result from dispatchEvent

		switch (eventType.toLowerCase()) {
		case 'keyup':
		case 'keydown':
			if (document.createEventObject) {
				// IE
				e		= document.createEventObject();
				e.keyCode	= detail;
				result		= control.fireEvent('on' + eventType);
			} else if (window.KeyEvent) {
				// Firefox
				e		= document.createEvent('KeyEvents');
				e.initKeyEvent(eventType, true, true, window, false, false, false, false, detail, 0);
				result		= control.dispatchEvent(e);
			} else {
				e		= document.createEvent('UIEvents');
				e.initUIEvent(eventType, true, true, window, 1);
				e.keyCode	= detail;
				result		= control.dispatchEvent(e);
			}

			break;
		case 'focus':
		case 'blur':
		case 'change':
			if (document.createEventObject) {
				// IE
				e		= document.createEventObject();
				result		= control.fireEvent('on' + eventType);
			} else {
				e		= document.createEvent('UIEvents');
				e.initUIEvent(eventType, true, true, window, 1);
				result		= control.dispatchEvent(e);
			}

			break;
		case 'click':
			if (document.createEventObject) {
				// IE
				e		= document.createEventObject();
				result		= control.fireEvent('on' + eventType);
			} else {
				e		= document.createEvent('MouseEvents');
				e.initMouseEvent(eventType, true, true, window, 1);
				result		= control.dispatchEvent(e);
			}

			break;
		}

		return result;
	}

// ---------------------------------------------------------------------------
	function doFormFill(controlNode) {
		var	controlId	= controlNode.getAttribute('$attrID'),
			controlType	= controlNode.nodeName,
			controlValue	= (typeof controlNode.textContent === 'undefined') ? controlNode.text : controlNode.textContent,
			control		= that.getControl(controlId),
			keyCode, doEvent;

		if (control === null) {
			logAppend(' - - No control with id ' + controlId, true);
		} else {
			logAppend(' - - setting ' + controlId + ' (' + controlType + ') to ' + controlValue);

			if (typeof document.activeElement.onBlur === 'function') {fireEvent(document.activeElement, 'blur');}
			if (typeof document.activeElement.onblur === 'function') {fireEvent(document.activeElement, 'blur');}
			if (typeof control.onFocus === 'function') {doEvent = fireEvent(control, 'focus');}
			if (typeof control.onfocus === 'function') {doEvent = fireEvent(control, 'focus');}
			if (doEvent !== false) {control.focus();}

			switch (controlType) {
				case '$tagCheckbox':
					control.checked = (controlValue === 'checked') ? true : false;
					if (typeof control.onClick === 'function') {fireEvent(control, 'click');}
					if (typeof control.onclick === 'function') {fireEvent(control, 'click');}
					break;
				case '$tagRadio':
					control.checked = (controlValue === 'checked') ? true : false;
					if (typeof control.onClick === 'function') {fireEvent(control, 'click');}
					if (typeof control.onclick === 'function') {fireEvent(control, 'click');}
					break;
				default:
					control.defaultValue	= controlValue;
					control.value		= controlValue;

					if (typeof control.onChange	=== 'function') {fireEvent(control, 'change');}
					if (typeof control.onchange	=== 'function') {fireEvent(control, 'change');}

					keyCode = (controlValue.length === 0) ? 0 : controlValue.charCodeAt(controlValue.length - 1);

					if (typeof control.onKeyDown	=== 'function') {fireEvent(control, 'keydown', keyCode);}
					if (typeof control.onkeydown	=== 'function') {fireEvent(control, 'keydown', keyCode);}
					if (typeof control.onKeyUp	=== 'function') {fireEvent(control, 'keyup', keyCode);}
					if (typeof control.onkeyup	=== 'function') {fireEvent(control, 'keyup', keyCode);}
					break;
			}
		}
	}

// ---------------------------------------------------------------------------
	function doStep(step) {
		var url, control, controlId, controlNode, doEvent, element, html, postData, j, controlList;

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
			control		= that.getControl(controlId);

			if (control === null) {
				logAppend(' - No control with id ' + controlId, true);
			} else {
				logAppend(' - clicking button ' + controlId);
				if (typeof document.activeElement.onBlur === 'function') {fireEvent(document.activeElement, 'blur');}
				if (typeof document.activeElement.onblur === 'function') {fireEvent(document.activeElement, 'blur');}
				doEvent = true;
				if (typeof control.onFocus === 'function') {doEvent = fireEvent(control, 'focus');}
				if (typeof control.onfocus === 'function') {doEvent = fireEvent(control, 'focus');}
				if (doEvent !== false) {
					control.focus();
					control.click();
				}
			}

			break;
		case '$tagPost':
			controlId	= step.getAttribute('$attrID');
			control		= that.getControl(controlId);

			if (control === null) {
				logAppend(' - No control with id ' + controlId, true);
			} else {
				element		= document.createElement('dummy');
				element.appendChild(control.cloneNode(true));
				html		= element.innerHTML;
				postData	= '$actionParse&responseText='	+ encodeURIComponent(html);

				logAppend(' - posting element ' + controlId);
				that.ajax.serverTalk('POST', postData);
			}

			break;
		case '$tagFormFill':
			logAppend(' - filling form fields');
			controlList = step.childNodes;

			for (j = 0; j < controlList.length; j++) {
				controlNode = controlList[j];
				if (controlNode.nodeType === 1) {doFormFill(controlNode);}
			}

			break;
		case '$tagLogAppend':
			logAppend(' - ' + step.nodeValue);
			break;
		case '$tagRubric':
			logAppend('<em>' + step.nodeValue + '</em>');
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
// AJAX handling
// ---------------------------------------------------------------------------
	this.ajax = {
		xhr: new window.XMLHttpRequest(),

		handleServerResponse: function () {
			if ((this.readyState === 4) && (this.status === 200)) {
				var id = this.getResponseHeader('ajaxUnit-component');

				switch (id) {
				case 'ajaxunit':
					// Show the test console
					addStyleSheet();
					fillContainer(id, this.responseText);
					break;
				case 'ajaxunit-$actionParse':
					logAppend('Actions received from test controller');
					doActions(this.responseXML);
					break;
				case 'ajaxunit-logmessage':
					logAppend(this.responseText);
					break;
				default:
					logAppend('Response received, but no <em>ajaxUnit-component</em> header.');
					logAppend(this.responseText);
				}
			}
		},

		serverTalk: function (requestType, requestData) {
			var URL = '$parseURL';

			if (requestType === 'POST') {
				this.xhr.open(requestType, URL);
				this.xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			} else {
				this.xhr.open(requestType, URL + '?' + requestData);
				requestData = '';
			}

			this.xhr.onreadystatechange = this.handleServerResponse;
			this.xhr.setRequestHeader('If-Modified-Since', new Date(0));	// Internet Explorer caching 'feature'
			this.xhr.send(requestData);
		}
	};

// ---------------------------------------------------------------------------
// Public methods
// ---------------------------------------------------------------------------
	this.getResponse = function (action) {
		this.ajax.serverTalk('GET', action);
	};

	this.postResponse = function (appAjax) {
		var	postData = '$actionParse';
			postData += '&readyState='	+ appAjax.readyState;
			postData += '&status='		+ appAjax.status;
			postData += '&responseText='	+ encodeURIComponent(appAjax.responseText);

		logAppend('Relaying an XMLHttpRequest response to $parseURL');
		this.ajax.serverTalk('POST', postData);
	};

	this.postString = function(thisString) {
		var appAjax = {readyState: 4, status: 200, responseText: thisString}; // Fake ResponseText object
		this.postResponse(appAjax);
	};

	this.postSelf = function() {this.postString(document.documentElement.innerHTML);};

	this.debug = function(payload) {logAppend(payload);};

	this.click = function (control) {
		var	action	= control.getAttribute('data-ajaxunit-action'),
			iFrame	= document.getElementById('ajaxunit-iframe');

		switch (action) {
		case 'setIFrameSource':
			iFrame.src = document.getElementById('ajaxunit-url').value;
			break;
		case 'post':
			this.postString(iFrame.contentDocument.body.innerHTML);
		}

		return false;
	};

	this.keyUp = function (e) {
		if (!e) {e = window.event;}
		var target = (e.target) ? e.target : e.srcElement;

		// Process Carriage Return and tidy up form
		if (target.form.id === 'ajaxunit-iframe-form' && e.keyCode === 13) {this.click(document.getElementById('ajaxunit-ok'));}
		return false;
	};
}

// ---------------------------------------------------------------------------
//		ajaxUnit
// ---------------------------------------------------------------------------
// Process results returned from XMLHttpRequest object
// ---------------------------------------------------------------------------
function ajaxUnit(payload, debugArgument) {
	var	thisAjaxUnit	= new C_ajaxUnit(),
		debug		= debugArgument || false;

	ajaxUnitInstances.push(thisAjaxUnit);

	if (debug) {
		thisAjaxUnit.debug(payload);
	} else {
		switch (typeof payload) {
		case 'undefined':
			thisAjaxUnit.postSelf();
			break;
		case 'string':
			thisAjaxUnit.postString(payload);
			break;
		default:
			thisAjaxUnit.postResponse(payload);
		}
	}
}

GENERATED;
// Generated code - do not modify in built package

		return $js;
	}

	public static /*.void.*/ function getJavascript() {
		$html = self::htmlJavascript();
		self::sendContent($html, 'Javascript', 'text/javascript');
	}

	public static /*.string.*/ function getIcon($filename = '', $sendToBrowser = true) {
		if (is_file($filename)) {
			$icon = self::getFileContents($filename);
		} else {
			$icon = base64_decode('AAABAAEAEBAAAAAAAABoBQAAFgAAACgAAAAQAAAAIAAAAAEACAAAAAAAAAEAAAAAAAAAAAAAAAEAAAAAAAD+/f4A9/n5AM+SNADerWwAzax4AMuMHADMjBwAwH4KAL97AgD9/vwA/v78APv39wDGlU4AvHYAAPP19QDPkjUA2qFVAMmLIADerGgA+PLlAM+rdAC8dgEA365rANmnXgDHiBkAx6+LAPv+/gD8/v4A/f7+AP7+/gD//v4A9PDsAL99BwDfrmwAy4ocALuXZADQp3AA+vz8AIJqSAD8/PwA+PDkANCZTQB7XTMAfmA7AMSIGgD9//wAm10AAMiKJQDFfAIAv3QAAP7+/wCidSgA//7/ALyHJgD19vUA2q1wAMB6AAD8/foAxq2KALyXZQD6/P0A+fPlAP7//QDi3NQA///9AL2dZQC9dAEAxYAAAOnbxgC9ml0A+PTzAMG1pgD46dgA+vr7AOzd0QDz7eYA1aFZAMKPQgD38+4ArG4LAMWJGQDcp2EA4dW/APfy8QC/fAQAvppmAMWvjgDGiBwA/v/+AMyQNwD68+YAvHQCAP///gC9hCgAqpmDAL53AgDbqF8Af2I4AP39/ADu5toA/v38ALtyAAB9XjMAnFsAAPz//wD9//8A2allAP///wDLix0A4KxlAPz9/QD9/f0As4hIAN6uaADEfgAAx4kbAMmFBQDAcgEAxIMLALqWYwDTmkYAx4QAAMiEAADFgwMAy5I8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAa2trQAA/JmYqXmIea2tra2trazQ1UBhXGHMsSWtra2tra2sODTFkChtPFUtoa2tra2trCy5na2trI2VaHWtra2trXGs6U2tra3d1Ex1ra2trax0ZX1sfHWs7Qj1rGmtrax1WMDg4CE5rVTgTHT5ra2snVHBDByAzAUFyKDZYa2trHTlcenZvY3RFeVJ7Rmtra2sdLWwvawkRIgUGXTxra2traxwCfGtrblkPTSVca2tra2sdeClra2tcDD5ra2tra2traRBMa2trK2FHa2tra2trax0XUScyHWpgSmtra2tra2trNxYSAyFtcR1ra2tra2tra2tIFCQERDQda2trawAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=');	// Generated code - do not modify in built package
		}

		if ($sendToBrowser) {self::sendContent($icon, 'icon', 'image/x-icon'); return '';} else return $icon;
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlControlPanel($project = '') {
		if ($project !== '') self::setProject($project);

		$actionSuite	= self::ACTION_SUITE;
		$actionDummy	= self::ACTION_DUMMY;
		$folder		= (string) self::getTestContext(self::TAGNAME_FOLDER_TESTS);
		$extension	= self::TESTS_EXTENSION;
		$suiteList	= "";

		if (!is_dir($folder)) return "<h2>ajaxUnit</h2>\n<p>Test folder <em>$folder</em> can't be found</p>\n";

		foreach (glob($folder.DIRECTORY_SEPARATOR."*.$extension") as $filename) {
			$suite		= basename($filename, '.' . self::TESTS_EXTENSION);
			$document	= new DOMDocument();
			$document->load($filename);

			$suiteNode	= $document->getElementsByTagName(self::ACTION_SUITE)->item(0);
	/*.object.*/	$suiteObject	= $suiteNode;				// PHPLint-compliant typecasting
			$suiteElement	= /*.(DOMElement).*/ $suiteObject;	// PHPLint-compliant typecasting

			if ($suiteElement->hasAttribute(self::ATTRNAME_NAME)) {
				$suiteName	= $suiteElement->getAttribute(self::ATTRNAME_NAME);

				$suiteList	.= <<<HTML
				<input class="ajaxunit ajaxunit-radio" type="radio" name="$actionSuite" value="$suite" /> $suiteName<br />
HTML;
			}
		}

		if ($suiteList === '') {
			$html = "<h2>ajaxUnit</h2>\n<p>There are are no test suites in the <em>$folder</em> folder</p>\n";
		} else {
			if ($project === '') $project = (string) self::getTestContext(self::TAGNAME_PROJECT);

			// While we're here, let's put a copy of ajaxunit.js in the tests folder
			$js		= self::htmlJavascript();
			$filename	= $folder.DIRECTORY_SEPARATOR.'ajaxunit.js';
			$result		= @file_put_contents($filename, $js);
			$success	= (boolean) $result;

			$message = ($success === false) ? "<strong>Couldn't write ajaxUnit javascript to tests folder - might be out of date or missing!</strong>" : 'ajaxUnit javascript written to tests folder';

			$html = <<<HTML
		<h2>ajaxUnit testing for project $project</h2>
		<form class="ajaxunit-form" action="ajaxunit.php" method="get">
			<fieldset class="ajaxunit-fieldset">
				<h3>Choose test suite to run:</h3>
$suiteList
			</fieldset>
			<fieldset class="ajaxunit-fieldset">
				<input id="ajaxunit-run-tests" value="Run tests"
					type		=	"submit"
					class		=	"ajaxunit-button ajaxunit-buttonstate-0"
				/>
				<div style="float:left;margin:9px 0 0 8px;">
					<p style="margin:7px 0 0 3px;"><input class="ajaxunit ajaxunit-checkbox" type="checkbox" name = "$actionDummy" value="true" /> Dummy run</p>
				</div>
			</fieldset>
		</form>
		<p class="ajaxunit-footnote">ajaxUnit version 0.17.30 - $message</p>
HTML;
		}

		return $html;
	}

	public static /*.void.*/ function getControlPanel() {
		$html = self::htmlControlPanel();
		self::sendContent($html);
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlCustomURL() {
		$URL		= self::thisURL();
		$content	= '';

		// Get some context for this test
		$context = /*.(array[string]string).*/ self::getTestContext();

		// Are we actually running tests at the moment?
		if (!array_key_exists(self::TAGNAME_STATUS, $context) || in_array($context[self::TAGNAME_STATUS], array('', self::STATUS_FINISHED, self::STATUS_FAIL, self::STATUS_FATALERROR))) {
			$content	.= 'No testing in progress';
			$testName	= '';
			$rubric		= '';
		} else {
			// Get some context for this test
			$test		= new ajaxUnit_test();
			$testName	= $test->name();
			$rubric		= $test->rubric();
		}

		$html		= <<<HTML
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>ajaxUnit Custom URL</title>
<link type="text/css" rel="stylesheet" href="$URL?css" title="ajaxUnit">
<script src="$URL?js"></script>
<script type="text/javascript">var ajaxUnit = new C_ajaxUnit()</script>
</head>

<body>
	<div id="ajaxunit">
		<h1>ajaxUnit manual results entry</h1>
		<h2>$testName</h2>
		<p class="ajaxunit-rubric">$rubric<p>
		<br />
		<form id="ajaxunit-iframe-form" class="ajaxunit-form" onsubmit="return false">
			<fieldset class="ajaxunit-fieldset">
				<label for="ajaxunit-url" class="ajaxunit-label">URL:</label>
				<input type="text" id="ajaxunit-url" class="ajaxunit-text" onkeyup = "ajaxUnit.keyUp(event)">
				<input type="button" id="ajaxunit-ok" class="ajaxunit-button" value="Go" data-ajaxunit-action="setIFrameSource" onclick="ajaxUnit.click(this)">
			</fieldset>
		</form>
	</div>
	<iframe width="640" height="480" id="ajaxunit-iframe" class="ajaxunit-iframe"></iframe>
	<form id="ajaxunit-post-form" class="ajaxunit-form" onsubmit="return false">
		<fieldset class="ajaxunit-fieldset">
			<input type="button" id="ajaxunit-post" class="ajaxunit-button" value="Post" data-ajaxunit-action="post" onclick="ajaxUnit.click(this)">
		</fieldset>
	</form>
	<pre>$content</pre>
</body>

</html>
HTML;

		return $html;
	}

	public static /*.void.*/ function getCustomURL() {
		$html = self::htmlCustomURL();
		self::sendContent($html);
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlAddScript() {
		$actionJavascript	= self::ACTION_JAVASCRIPT;
		$URL			= self::thisURL();

/* This doesn't work in Webkit
		return <<<HTML
	<script type="text/javascript">
		if (typeof C_ajaxUnit === 'undefined') {
			var ajaxUnit_node	= document.createElement('script');
			ajaxUnit_node.type	= 'text/javascript';
			ajaxUnit_node.src	= '$URL?$actionJavascript';
			document.getElementsByTagName('head')[0].appendChild(ajaxUnit_node);
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
	private	static /*.string.*/	function htmlAbout()	{
		$php = self::getFileContents('ajaxunit.php', 0, NULL, -1, 4096);
		return self::docBlock_to_HTML($php);
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
}
// End of class ajaxUnit



// Some code to make this all automagic and a bit RESTful
// If you want more control over how ajaxunit works then you might need to amend
// or even remove the code below here

// Is this script included in another page or is it the HTTP target itself?
if (basename($_SERVER['PHP_SELF']) === 'ajaxunit.php') {
	// This script has been called directly by the client
	if (is_array($_GET) && (count($_GET) > 0)) {
		$dummyRun	= (array_key_exists(ajaxUnit::ACTION_DUMMY,	$_GET)) ? (bool)	$_GET[ajaxUnit::ACTION_DUMMY]	: false;

		if (array_key_exists(ajaxUnit::ACTION_SUITE,	$_GET))	ajaxUnit::runTestSuite((string) $_GET[ajaxUnit::ACTION_SUITE], $dummyRun);
		if (array_key_exists(ajaxUnit::ACTION_END,	$_GET))	ajaxUnit::endTestSuite((string) $_GET[ajaxUnit::ACTION_END], $dummyRun);
		if (array_key_exists(ajaxUnit::ACTION_PARSE,	$_GET))	ajaxUnit::parseResults($dummyRun);
		if (array_key_exists(ajaxUnit::ACTION_CONTROL,	$_GET))	ajaxUnit::getControlPanel();
		if (array_key_exists(ajaxUnit::ACTION_CUSTOMURL,	$_GET))	ajaxUnit::getCustomURL();
		if (array_key_exists(ajaxUnit::ACTION_JAVASCRIPT,	$_GET))	ajaxUnit::getJavascript();
		if (array_key_exists(ajaxUnit::ACTION_CSS,	$_GET))	ajaxUnit::getCSS();
		if (array_key_exists(ajaxUnit::ACTION_ICON,	$_GET))	ajaxUnit::getIcon();
		if (array_key_exists(ajaxUnit::ACTION_ABOUT,	$_GET))	ajaxUnit::getAbout();
		if (array_key_exists(ajaxUnit::ACTION_SOURCECODE,	$_GET))	ajaxUnit::getSourceCode();
		if (array_key_exists(ajaxUnit::ACTION_LOGTIDY,	$_GET))	ajaxUnit::tidyLogFiles();
	} else {
		if (is_array($_POST) && (count($_POST) > 0)) {
			ajaxUnit::parseResults();
		} else {
			ajaxUnit::addScript();
		}
	}
}
?>