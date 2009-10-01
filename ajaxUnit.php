<?php
/**
 * Testing PHP and javascript by controlling the interactions automatically
 * 
 * Copyright (c) 2008-2009, Dominic Sayers							<br>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 * 	- Redistributions of source code must retain the above copyright notice,
 * 	  this list of conditions and the following disclaimer.
 * 	- Redistributions in binary form must reproduce the above copyright notice,
 * 	  this list of conditions and the following disclaimer in the documentation
 * 	  and/or other materials provided with the distribution.
 * 	- Neither the name of Dominic Sayers nor the names of its contributors may be
 * 	  used to endorse or promote products derived from this software without
 * 	  specific prior written permission.
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
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxUnit/
 * @version	0.05 - Tests a major project successfully in my new dev environment
 */

// The quality of this code has been improved greatly by using PHPLint
// Copyright (c) 2009 Umberto Salsi
// This is free software; see the license for copying conditions.
// More info: http://www.icosaedro.it/phplint/
/*.
	require_module 'standard';
	require_module 'dom';
.*/

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

interface ajaxUnit_API {
	const	ACTION_ABOUT		= 'about',
		ACTION_CONTROL		= 'control',
		ACTION_CSS		= 'css',
		ACTION_CUSTOMURL	= 'customURL',
		ACTION_DUMMY		= 'dummyrun',
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
		TAGNAME_FOLDER_PACKAGE	= 'packageFolder',
		TAGNAME_FOLDER_TESTS	= 'testsFolder',
		TAGNAME_FORMFILL	= 'formfill',
		TAGNAME_HEADERS		= 'headers',
		TAGNAME_INCLUDEPATH	= 'include_path',
		TAGNAME_INDEX		= 'index',
		TAGNAME_INPROGRESS	= 'inProgress',
		TAGNAME_LOCATION	= 'location',
		TAGNAME_LOGAPPEND	= 'logAppend',
		TAGNAME_OPEN		= 'open',
		TAGNAME_PARAMETERS	= 'parameters',
		TAGNAME_PROJECT		= 'project',
		TAGNAME_POST		= 'post',
		TAGNAME_RADIO		= 'radio',
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
		TAGNAME_TEXT		= 'text',
		TAGNAME_UID		= 'uid',
		TAGNAME_URL_BASE	= 'baseURL',
		TAGNAME_URL_LOGS	= 'logsURL',
		TAGNAME_URL_PACKAGE	= 'packageURL',
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
		STATUS_FAIL		= '<span style="color:red">FAIL!</span>',
		STATUS_SUCCESS		= 'success',
		STATUS_FINISHED		= 'finished',

		TESTS_FOLDER		= 'tests',
		TESTS_EXTENSION		= 'xml',
		LOG_FOLDER		= 'logs',
		LOG_EXTENSION		= 'html',
		CONTEXT_FOLDER		= '.context',
		LOG_MAXHOURS		= 12;
}
// End of interface ajaxUnit_API

/**
 * Common utility functions
 *
 * @package ajaxUnit
 * @version 1.3 (revision number of common functions class only)
 */
interface I_ajaxUnit_common {
	const	PACKAGE			= 'ajaxUnit',

		HASH_FUNCTION		= 'SHA256',
		URL_SEPARATOR		= '/',

		// Behaviour settings for strleft()
		STRLEFT_MODE_NONE	= 0,
		STRLEFT_MODE_ALL	= 1,

		// Behaviour settings for getURL()
		URL_MODE_PROTOCOL	= 1,
		URL_MODE_HOST		= 2,
		URL_MODE_PORT		= 4,
		URL_MODE_PATH		= 8,
		URL_MODE_ALL		= 15,

		// Behaviour settings for getPackage()
		PACKAGE_CASE_DEFAULT	= 0,
		PACKAGE_CASE_LOWER	= 0,
		PACKAGE_CASE_CAMEL	= 1,
		PACKAGE_CASE_UPPER	= 2;

	// Basic utility functions
	public static /*.string.*/	function strleft(/*.string.*/ $haystack, /*.string.*/ $needle);
	public static /*.string.*/	function getInnerHTML(/*.string.*/ $html, /*.string.*/ $tag);
	public static /*.string.*/	function array_to_HTML(/*.array[mixed]mixed.*/ $source = NULL);

	// Environment functions
	public static /*.string.*/	function getPackage($mode = self::PACKAGE_CASE_DEFAULT);
	public static /*.string.*/	function getURL($mode = self::URL_MODE_PATH, $filename = '');
	public static /*.string.*/	function docBlock_to_HTML(/*.string.*/ $php);

	// File system functions
	public static /*.string.*/	function getFileContents(/*.string.*/ $filename, /*.int.*/ $flags = 0, /*.object.*/ $context = NULL, /*.int.*/ $offset = -1, /*.int.*/ $maxLen = -1);
	public static /*.string.*/	function findIndexFile(/*.string.*/ $folder);

	// Data functions
	public static /*.string.*/	function makeId();
	public static /*.string.*/	function makeUniqueKey(/*.string.*/ $id);

	// Validation functions
	public static /*.boolean.*/	function is_email(/*.string.*/ $email, $checkDNS = false);
}

/**
 * Common utility functions
 */
abstract class ajaxUnit_common implements I_ajaxUnit_common {
/**
 * Return the beginning of a string, up to but not including the search term.
 *
 * @param string $haystack The string containing the search term
 * @param string $needle The end point of the returned string. In other words, if <var>needle<var> is found then the begging of <var>haystack</var> is returned up to the character before <needle>.
 * @param int $mode If <var>needle</var> is not found then <pre>FALSE</pre> will be returned. */
	public static /*.string.*/ function strleft(/*.string.*/ $haystack, /*.string.*/ $needle, /*.int.*/ $mode = self::STRLEFT_MODE_NONE) {
		$posNeedle = strpos($haystack, $needle);

		if ($posNeedle === false) {
			switch ($mode) {
			case self::STRLEFT_MODE_NONE:
				return $posNeedle;
				break;
			case self::STRLEFT_MODE_ALL:
				return $haystack;
				break;
			}
		} else {
			return substr($haystack, 0, $posNeedle);
		}
	}

/**
 * Return the contents of an HTML element, the first one matching the <var>tag</var> parameter.
 *
 * @param string $html The string containing the html to be searched
 * @param string $tag The type of element to search for. The contents of first matching element will be returned. If the element doesn't exist then <var>false</var> is returned.
 */
	public static /*.string.*/ function getInnerHTML(/*.string.*/ $html, /*.string.*/ $tag) {
		$pos_tag_open_start	= stripos($html, "<$tag");
		$pos_tag_open_end	= strpos($html, '>',		$pos_tag_open_start);
		$pos_tag_close		= stripos($html, "</$tag>",	$pos_tag_open_end);
		return substr($html, $pos_tag_open_end + 1, $pos_tag_close - $pos_tag_open_end - 1);
	}

/**
 * Return the contents of a captured var_dump() as HTML. This is a recursive function.
 *
 * @param string $var_dump The captured <var>var_dump()</var>.
 * @param int $offset Whereabouts to start in the captured string. Defaults to the beginning of the string.
 */
	public static /*.string.*/ function var_dump_to_HTML(/*.string.*/ $var_dump, $offset = 0) {
		$html	= '';
		$indent	= '';

		while ($posStart = strpos($var_dump, '(', $offset)) {
			$type	= substr($var_dump, $offset, $posStart - $offset);
			$nests	= strrpos($type, ' ');

			if ($nests === false) $nests = 0; else $nests = ($nests + 1) / 2;

			$indent = str_pad('', $nests * 3, "\t");
			$type	= trim($type);
			$offset	= ++$posStart;
			$posEnd	= strpos($var_dump, ')', $offset); if ($posEnd === false) break;
			$offset	= $posEnd + 1;
			$value	= substr($var_dump, $posStart, $posEnd - $posStart);

			switch ($type) {
			case 'string':
				$length	= $value;
				$value	= htmlspecialchars(substr($var_dump, $offset + 2, $length));
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
 * Return the contents of an array as HTML (like <var>var_dump()</var> on steroids)
 *
 * @param mixed $source The array to export. If it's empty then $GLOBALS is exported.
 */
	public static /*.string.*/ function array_to_HTML(/*.array[mixed]mixed.*/ $source = NULL) {
// If no specific array is passed we will export $GLOBALS to HTML
// Unfortunately, this means we have to use var_dump() because var_export() barfs on $GLOBALS
// In fact var_dump is easier to walk than var_export anyway so this is no bad thing.

		ob_start();
		if (empty($source)) var_dump($GLOBALS); else var_dump($source);
		$var_dump = ob_get_clean();

		return self::var_dump_to_HTML($var_dump);
	}

/**
 * Return the name of this package. By default this will be in lower case for use in Javascript tags etc.
 *
 * @param int $mode One of the <var>PACKAGE_CASE_XXX</var> predefined constants defined in this class
 */
	public static /*.string.*/ function getPackage($mode = self::PACKAGE_CASE_DEFAULT) {
		switch ($mode) {
		case self::PACKAGE_CASE_CAMEL:
			$package = self::PACKAGE;
			break;
		case self::PACKAGE_CASE_UPPER:
			$package = strtoupper(self::PACKAGE);
			break;
		default:
			$package = strtolower(self::PACKAGE);
			break;
		}

		return $package;
	}

/**
 * Return all or part of the URL of the current script.
 *
 * @param int $mode One of the <var>URL_MODE_XXX</var> predefined constants defined in this class
 * @param string $filename If this is not empty then the returned script name is forced to be this filename.
 */
	public static /*.string.*/ function getURL($mode = self::URL_MODE_PATH, $filename = self::PACKAGE) {
		$portInteger = array_key_exists('SERVER_PORT', $_SERVER) ? (int) $_SERVER['SERVER_PORT'] : 0;

		if (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] === 'on') {
			$protocolType = 'https';
		} else if (array_key_exists('SERVER_PROTOCOL', $_SERVER)) {
			$protocolType = strtolower(self::strleft($_SERVER['SERVER_PROTOCOL'], self::URL_SEPARATOR, self::STRLEFT_MODE_ALL));
		} else if ($portInteger = 443) {
			$protocolType = 'https';
		} else {
			$protocolType = 'http';
		}

		if ($portInteger === 0) $portInteger = ($protocolType === 'https') ? 443 : 80;

		// Protocol
		if ($mode & self::URL_MODE_PROTOCOL) {
			$protocol = ($mode === self::URL_MODE_PROTOCOL) ? $protocolType : "$protocolType://";
		} else {
			$protocol = '';
		}

		// Host
		if ($mode & self::URL_MODE_HOST) {
			$host = array_key_exists('HTTP_HOST', $_SERVER) ? self::strleft($_SERVER['HTTP_HOST'], ':', self::STRLEFT_MODE_ALL) : '';
		} else {
			$host = '';
		}

		// Port
		if ($mode & self::URL_MODE_PORT) {
			$port = (string) $portInteger;

			if ($mode !== self::URL_MODE_PORT)
				$port = (($protocolType === 'http' && $portInteger === 80) || ($protocolType === 'https' && $portInteger === 443)) ? '' : ":$port";
		} else {
			$port = '';
		}

		// Path
		if ($mode & self::URL_MODE_PATH) {
			$includePath	= __FILE__;
			$scriptPath	= realpath($_SERVER['SCRIPT_FILENAME']);

			if (DIRECTORY_SEPARATOR !== self::URL_SEPARATOR) {
				$includePath	= (string) str_replace(DIRECTORY_SEPARATOR, self::URL_SEPARATOR , $includePath);
				$scriptPath	= (string) str_replace(DIRECTORY_SEPARATOR, self::URL_SEPARATOR , $scriptPath);
			}

			$path = dirname(substr($includePath, strpos(strtolower($scriptPath), strtolower($_SERVER['SCRIPT_NAME'])))) . self::URL_SEPARATOR . $filename;
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
		$package	= self::getPackage(self::PACKAGE_CASE_CAMEL);
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
		$description	= str_replace(' * ', '' , $description);

		// Get tags and values from DocBlock
		do {
			$tagStart	= $tagPos + 4;
			$tagEnd		= strpos($php, "\t", $tagStart);
			$tag		= substr($php, $tagStart, $tagEnd - $tagStart);
			$offset		= $tagEnd + 1;
			$tagPos		= strpos($php, $eol, $offset);		
			$value		= substr($php, $tagEnd + 1, $tagPos - $tagEnd - 1);
			$$tag		= htmlspecialchars($value);
			$tagPos		= strpos($php, " * @", $offset);		
		} while ($tagPos);

		// Add some links
		// 1. License
		if (isset($license) && strpos($license, '://')) {
			$tagPos	= strpos($license, ' ');
			$license	= '<a href="' . substr($license, 0, $tagPos) . '">' . substr($license, $tagPos + 1) . '</a>';
		}

		// 2. Author
		if (isset($author) && preg_match('/&lt;.+@.+&gt;/', $author) > 0) {
			$tagStart	= strpos($author, '&lt;') + 4;
			$tagEnd		= strpos($author, '&gt;', $tagStart);
			$author		= '<a href="mailto:' . substr($author, $tagStart, $tagEnd - $tagStart) . '">' . substr($author, 0, $tagStart - 5) . '</a>';
		}

		// 3. Link
		if (isset($link) && strpos($link, '://')) {
			$link		= '<a href="' . $link . '">' . $link . '</a>';
		}

		// Build the HTML
		$html = <<<HTML
	<h1>$package</h1>
	<h2>$summary</h2>
	<pre>$description</pre>
	<hr />
	<table>

HTML;

		if (isset($version))	$html .= "\t\t<tr><td>Version</td><td>$version</td></tr>\n";
		if (isset($copyright))	$html .= "\t\t<tr><td>Copyright</td><td>$copyright</td></tr>\n";
		if (isset($license))	$html .= "\t\t<tr><td>License</td><td>$license</td></tr>\n";
		if (isset($author))	$html .= "\t\t<tr><td>Author</td><td>$author</td></tr>\n";
		if (isset($link))	$html .= "\t\t<tr><td>Link</td><td>$link</td></tr>\n";

		$html .= "	</table>\n";
		return $html;
	}

/**
 * Return file contents as a string. Fail silently if the file can't be opened.
 *
 * The parameters are the same as the built-in PHP function {@link http://www.php.net/file_get_contents file_get_contents}
 */
	public static /*.string.*/ function getFileContents(/*.string.*/ $filename, /*.int.*/ $flags = 0, /*.object.*/ $context = NULL, /*.int.*/ $offset = -1, /*.int.*/ $maxlen = -1) {
		$contents = @file_get_contents($filename, $flags, $context, $offset, $maxlen);
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
 * Return the name of the target file from a string that might be a directory. If it's a directory then look for an index file in the directory.
 *
 * @param string $target The file to look for or folder to look in. If no file can be found then an empty string is returned.
 */
	public static /*.string.*/ function findTarget(/*.string.*/ $target) {
		if (is_file($target)) return $target;
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

/**
 * Check that an email address conforms to RFC5322 and other RFCs
 *
 * @param boolean $checkDNS If true then a DNS check for A and MX records will be made
 */
	public static /*.boolean.*/ function is_email(/*.string.*/ $email, $checkDNS = false) {
		// Check that $email is a valid address. Read the following RFCs to understand the constraints:
		//	(http://tools.ietf.org/html/rfc5322)
		//	(http://tools.ietf.org/html/rfc3696)
		//	(http://tools.ietf.org/html/rfc5321)
		//	(http://tools.ietf.org/html/rfc4291#section-2.2)
		//	(http://tools.ietf.org/html/rfc1123#section-2.1)

		// the upper limit on address lengths should normally be considered to be 256
		//	(http://www.rfc-editor.org/errata_search.php?rfc=3696)
		//	NB I think John Klensin is misreading RFC 5321 and the the limit should actually be 254
		//	However, I will stick to the published number until it is changed.
		//
		// The maximum total length of a reverse-path or forward-path is 256
		// characters (including the punctuation and element separators)
		//	(http://tools.ietf.org/html/rfc5321#section-4.5.3.1.3)
		$emailLength = strlen($email);
		if ($emailLength > 256)	return false;	// Too long

		// Contemporary email addresses consist of a "local part" separated from
		// a "domain part" (a fully-qualified domain name) by an at-sign ("@").
		//	(http://tools.ietf.org/html/rfc3696#section-3)
		$atIndex = strrpos($email,'@');

		if ($atIndex === false)		return false;	// No at-sign
		if ($atIndex === 0)		return false;	// No local part
		if ($atIndex === $emailLength)	return false;	// No domain part

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
//				if ($replaceChar) $email[$i] = 'x';	// Replace the offending character with something harmless
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
		//	(http://tools.ietf.org/html/rfc5322#section-3.4.1)
		//
		// Problem: need to distinguish between "first.last" and "first"."last"
		// (i.e. one element or two). And I suck at regexes.
		$dotArray	= /*. (array[int]string) .*/ preg_split('/\\.(?=(?:[^\\"]*\\"[^\\"]*\\")*(?![^\\"]*\\"))/m', $localPart);
		$partLength	= 0;

		foreach ($dotArray as $element) {
			// Remove any leading or trailing FWS
			$element = preg_replace("/^$FWS|$FWS\$/", '', $element);

			// Then we need to remove all valid comments (i.e. those at the start or end of the element
			$elementLength = strlen($element);

			if ($element[0] === '(') {
				$indexBrace = strpos($element, ')');
				if ($indexBrace !== false) {
					if (preg_match('/(?<!\\\\)[\\(\\)]/', substr($element, 1, $indexBrace - 1)) > 0) {
														return false;	// Illegal characters in comment
					}
					$element	= substr($element, $indexBrace + 1, $elementLength - $indexBrace - 1);
					$elementLength	= strlen($element);
				}
			}

			if ($element[$elementLength - 1] === ')') {
				$indexBrace = strrpos($element, '(');
				if ($indexBrace !== false) {
					if (preg_match('/(?<!\\\\)(?:[\\(\\)])/', substr($element, $indexBrace + 1, $elementLength - $indexBrace - 2)) > 0) {
														return false;	// Illegal characters in comment
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
				if (preg_match('/(?<!\\\\|^)["\\r\\n\\x00](?!$)|\\\\"$|""/', $element) > 0)	return false;	// ", CR, LF and NUL must be escaped, "" is too short
			} else {
				// Unquoted string tests:
				//
				// Period (".") may...appear, but may not be used to start or end the
				// local part, nor may two or more consecutive periods appear.
				//	(http://tools.ietf.org/html/rfc3696#section-3)
				//
				// A zero-length element implies a period at the beginning or end of the
				// local part, or two periods together. Either way it's not allowed.
				if ($element === '')								return false;	// Dots in wrong place

				// Any ASCII graphic (printing) character other than the
				// at-sign ("@"), backslash, double quote, comma, or square brackets may
				// appear without quoting.  If any of that list of excluded characters
				// are to appear, they must be quoted
				//	(http://tools.ietf.org/html/rfc3696#section-3)
				//
				// Any excluded characters? i.e. 0x00-0x20, (, ), <, >, [, ], :, ;, @, \, comma, period, "
				if (preg_match('/[\\x00-\\x20\\(\\)<>\\[\\]:;@\\\\,\\."]/', $element) > 0)	return false;	// These characters must be in a quoted string
			}
		}

		if ($partLength > 64) return false;	// Local part must be 64 characters or less

		// Now let's check the domain part...

		// The domain name can also be replaced by an IP address in square brackets
		//	(http://tools.ietf.org/html/rfc3696#section-3)
		//	(http://tools.ietf.org/html/rfc5321#section-4.1.3)
		//	(http://tools.ietf.org/html/rfc4291#section-2.2)
		if (preg_match('/^\\[(.)+]$/', $domain) === 1) {
			// It's an address-literal
			$addressLiteral = substr($domain, 1, strlen($domain) - 2);
			$matchesIP	= array();

			// Extract IPv4 part from the end of the address-literal (if there is one)
			if (preg_match('/\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $addressLiteral, $matchesIP) > 0) {
				$index = strrpos($addressLiteral, $matchesIP[0]);

				if ($index === 0) {
					// Nothing there except a valid IPv4 address, so...
					return true;
				} else {
					// Assume it's an attempt at a mixed address (IPv6 + IPv4)
					if ($addressLiteral[$index - 1] !== ':')	return false;	// Character preceding IPv4 address must be ':'
					if (substr($addressLiteral, 0, 5) !== 'IPv6:')	return false;	// RFC5321 section 4.1.3

					$IPv6		= substr($addressLiteral, 5, ($index ===7) ? 2 : $index - 6);
					$groupMax	= 6;
				}
			} else {
				// It must be an attempt at pure IPv6
				if (substr($addressLiteral, 0, 5) !== 'IPv6:')		return false;	// RFC5321 section 4.1.3
				$IPv6 = substr($addressLiteral, 5);
				$groupMax = 8;
			}

			$groupCount	= preg_match_all('/^[0-9a-fA-F]{0,4}|\\:[0-9a-fA-F]{0,4}|(.)/', $IPv6, $matchesIP);
			$index		= strpos($IPv6,'::');

			if ($index === false) {
				// We need exactly the right number of groups
				if ($groupCount !== $groupMax)				return false;	// RFC5321 section 4.1.3
			} else {
				if ($index !== strrpos($IPv6,'::'))			return false;	// More than one '::'
				$groupMax = ($index === 0 || $index === (strlen($IPv6) - 2)) ? $groupMax : $groupMax - 1;
				if ($groupCount > $groupMax)				return false;	// Too many IPv6 groups in address
			}

			// Check for unmatched characters
			array_multisort($matchesIP[1], SORT_DESC);
			if ($matchesIP[1][0] !== '')					return false;	// Illegal characters in address

			// It's a valid IPv6 address, so...
			return true;
		} else {
			// It's a domain name...

			// The syntax of a legal Internet host name was specified in RFC-952
			// One aspect of host name syntax is hereby changed: the
			// restriction on the first character is relaxed to allow either a
			// letter or a digit.
			//	(http://tools.ietf.org/html/rfc1123#section-2.1)
			//
			// NB RFC 1123 updates RFC 1035, but this is not currently apparent from reading RFC 1035.
			//
			// Most common applications, including email and the Web, will generally not
			// permit...escaped strings
			//	(http://tools.ietf.org/html/rfc3696#section-2)
			//
			// the better strategy has now become to make the "at least one period" test,
			// to verify LDH conformance (including verification that the apparent TLD name
			// is not all-numeric)
			//	(http://tools.ietf.org/html/rfc3696#section-2)
			//
			// Characters outside the set of alphabetic characters, digits, and hyphen MUST NOT appear in domain name
			// labels for SMTP clients or servers
			//	(http://tools.ietf.org/html/rfc5321#section-4.1.2)
			//
			// RFC5321 precludes the use of a trailing dot in a domain name for SMTP purposes
			//	(http://tools.ietf.org/html/rfc5321#section-4.1.2)
			$dotArray	= /*. (array[int]string) .*/ preg_split('/\\.(?=(?:[^\\"]*\\"[^\\"]*\\")*(?![^\\"]*\\"))/m', $domain);
			$partLength	= 0;
			$element	= ''; // Since we use $element after the foreach loop let's make sure it has a value

			if (count($dotArray) === 1)					return false;	// Mail host can't be a TLD

			foreach ($dotArray as $element) {
				// Remove any leading or trailing FWS
				$element = preg_replace("/^$FWS|$FWS\$/", '', $element);

				// Then we need to remove all valid comments (i.e. those at the start or end of the element
				$elementLength = strlen($element);

				if ($element[0] === '(') {
					$indexBrace = strpos($element, ')');
					if ($indexBrace !== false) {
						if (preg_match('/(?<!\\\\)[\\(\\)]/', substr($element, 1, $indexBrace - 1)) > 0) {
											return false;	// Illegal characters in comment
						}
						$element	= substr($element, $indexBrace + 1, $elementLength - $indexBrace - 1);
						$elementLength	= strlen($element);
					}
				}

				if ($element[$elementLength - 1] === ')') {
					$indexBrace = strrpos($element, '(');
					if ($indexBrace !== false) {
						if (preg_match('/(?<!\\\\)(?:[\\(\\)])/', substr($element, $indexBrace + 1, $elementLength - $indexBrace - 2)) > 0) {
											return false;	// Illegal characters in comment
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

				// The DNS defines domain name syntax very generally -- a
				// string of labels each containing up to 63 8-bit octets,
				// separated by dots, and with a maximum total of 255
				// octets.
				//	(http://tools.ietf.org/html/rfc1123#section-6.1.3.5)
				if ($elementLength > 63)				return false;	// Label must be 63 characters or less

				// Each dot-delimited component must be atext
				// A zero-length element implies a period at the beginning or end of the
				// local part, or two periods together. Either way it's not allowed.
				if ($elementLength === 0)				return false;	// Dots in wrong place

				// Any ASCII graphic (printing) character other than the
				// at-sign ("@"), backslash, double quote, comma, or square brackets may
				// appear without quoting.  If any of that list of excluded characters
				// are to appear, they must be quoted
				//	(http://tools.ietf.org/html/rfc3696#section-3)
				//
				// If the hyphen is used, it is not permitted to appear at
				// either the beginning or end of a label.
				//	(http://tools.ietf.org/html/rfc3696#section-2)
				//
				// Any excluded characters? i.e. 0x00-0x20, (, ), <, >, [, ], :, ;, @, \, comma, period, "
				if (preg_match('/[\\x00-\\x20\\(\\)<>\\[\\]:;@\\\\,\\."]|^-|-$/', $element) > 0) {
											return false;
				}
			}

			if ($partLength > 255)						return false;	// Local part must be 64 characters or less

			if (preg_match('/^[0-9]+$/', $element) > 0)			return false;	// TLD can't be all-numeric

			// Check DNS?
			if ($checkDNS && function_exists('checkdnsrr')) {
				if (!(checkdnsrr($domain, 'A') || checkdnsrr($domain, 'MX'))) {
											return false;	// Domain doesn't actually exist
				}
			}
		}

		// Eliminate all other factors, and the one which remains must be the truth.
		//	(Sherlock Holmes, The Sign of Four)
		return true;
	}
}
// End of class ajaxUnit_common

/**
 * Browser detection class for ajaxUnit
 *
 * @package ajaxUnit
 */
class ajaxUnit_browser {
	private $values = array(	"Version"	=> "0.0.0",
					"Name"		=> "unknown",
					"Agent"		=> "unknown");

	public function __Construct() {
		$browsers = array(	"firefox",	"msie",		"opera",	"chrome",	"safari",
					"mozilla",	"seamonkey",	"konqueror",	"netscape",
					"gecko",	"navigator",	"mosaic",	"lynx",		"amaya",
					"omniweb",	"avant",	"camino",	"flock",	"aol");

		$this->Agent = $_SERVER['HTTP_USER_AGENT'];

		foreach ($browsers as $browser) {
			if (preg_match("#($browser)[/ ]?([0-9.]*)#i", $this->Agent, $match)) {
				$this->Name	= $match[1];
				$this->Version	= $match[2];
				break;
			}
		}
	}

	public function __Get($key) {
		if (!array_key_exists($key, $this->values)) die("No such property or function $key");
		return $this->values[$key];
	}

	public function __Set($key, $value) {
		if (!array_key_exists($key, $this->values)) die("No such property or function $key");
		$this->values[$key] = $value;
	}
}
// End of class ajaxUnit_browser

class ajaxUnit_cookies {
	public static function get($name) {return (isset($_COOKIE[$name])) ? $_COOKIE[$name] : '';}

	public static function set($name, $value, $days = 0, $path = '/', $domain = '') {
		$expiry = ($days === 0) ? 0 : time() + 60 * 60 * 24 * $days;
		if ($domain = '') setcookie($name, $value, $expiry, $path); else setcookie($name, $value, $expiry, $path, $domain);
		return "Setting cookie '$name' to [$value] until " . date(DateTime::COOKIE, $expiry);
	}

	public static function remove($name) {
		if (!isset($_COOKIE[$name])) return "Cookie $name doesn't exist";
		return self::set($name, false, -1);
	}

	public static function show($name = '', $tableTop = true, $tableBottom = true) {
		if ($tableTop) echo "<table>\n";

		if ($name === '') {
			$cookieCount	= count($_COOKIE);
			$keys		= array_keys($_COOKIE);

			for ($i = 0; $i < $cookieCount; $i++) {
				$name = $keys[$i];
				self::show($name, false, false);
			}
		} else {
			$value = self::get($name);
			echo "<tr><td>$name</td><td>$value</td></tr>\n";
		}

		if ($tableBottom) echo "</table>\n";
	}
}
// End of class ajaxUnit_cookies

class ajaxUnit_runtime extends ajaxUnit_common implements ajaxUnit_API {
// ---------------------------------------------------------------------------
// Private methods
// ---------------------------------------------------------------------------
	private static /*.void.*/ function makeFolder(/*.string.*/ $folder) {
		if (!is_dir($folder)) mkdir($folder, 0600);
	}

	private static /*.string.*/ function getContextFilename() {
		self::makeFolder(self::CONTEXT_FOLDER);
		return self::CONTEXT_FOLDER . DIRECTORY_SEPARATOR . self::getPackage() . "_" . str_replace(':', '_' , $_SERVER['REMOTE_ADDR']); // str_replace is because of IPv6
	}

	protected static /*.void.*/ function setTestContext(/*.mixed.*/ $newContext, $value = '') {
		$filename = self::getContextFilename();

		if (is_file($filename)) {
			$handle		= fopen($filename, 'r+b'); // Lock the file

			$serial		= file_get_contents($filename);
			$context	= /*.(array[string]string).*/ unserialize($serial);

			rewind($handle);
			ftruncate($handle, 0);
		} else {
			$handle		= fopen($filename, 'wb');
			$context	= /*.(array[string]string).*/ array();
		}

		if (is_array($newContext)) {
			$context = (is_array($context)) ? array_merge($context, $newContext): $newContext;
		} else {
			$context[$newContext] = $value;
		}

		fwrite($handle, serialize($context));
		fclose($handle);
	}

	protected static /*.mixed.*/ function getTestContext($key = '') {
		$filename = self::getContextFilename();

		if (!is_file($filename)) {
			$context	= /*.(array[string]string).*/ array();
		} else {
			$serial		= file_get_contents($filename);
			$context	= /*.(array[string]string).*/ unserialize($serial);
			if (!is_array($context)) $context = array();
		}

		if ($key === '') {
			return $context;
		} else {
			return (isset($context[$key])) ? $context[$key] : '';
		}
	}

	private static /*.void.*/ function setInitialContext(/*.DOMElement.*/ $test, /*.int.*/ $testIndex, $dummyRun = false) {
		// Any browser-specific results in the test data?
		$browser		= new ajaxUnit_browser();
		$resultsNodeName	= self::TAGNAME_RESULTS . '-' . $browser->Name . '-' . $browser->Version;
		$resultsNodeList	= $test->getElementsByTagName($resultsNodeName); // Browser and version-specific results

		if ($resultsNodeList->length) {
			$resultsNode		= $resultsNodeList->item(0);
		} else {
			$resultsNodeName	= self::TAGNAME_RESULTS . '-' . $browser->Name;
			$resultsNodeList	= $test->getElementsByTagName($resultsNodeName); // Just browser-specific results

			if ($resultsNodeList->length) {
				$resultsNode		= $resultsNodeList->item(0);
			} else {
				// General (not browser-specific) results
				$resultsNodeName	= self::TAGNAME_RESULTS;
				$resultsNodeList	= $test->getElementsByTagName($resultsNodeName);

				if ($resultsNodeList->length) {
					$resultsNode		= $resultsNodeList->item(0);
				} else {
					// No results defined for this test
					$resultsNodeName	= '';
				}
			}
		}

		// Build a string containing a list of expected responses (1234...n) according to <results> child nodes
		$responseList	= '';

		if ($resultsNodeName === '') {
			$expected	= 0;
		} else {
			$resultsList	= $resultsNode->getElementsByTagName(self::TAGNAME_RESULT);	// Get the expected results
			$expected	= $resultsList->length;
			for ($i = 0; $i < $expected; $i++) $responseList .= (string) $i;
		}

		// Set context data
		$context = /*.(array[string]string).*/ array();
		$context[self::TAGNAME_INDEX]		= (string) $testIndex;
		$context[self::TAGNAME_RESPONSECOUNT]	= (string) 0;
		$context[self::TAGNAME_INPROGRESS]	= (string) false;
		$context[self::TAGNAME_RESPONSELIST]	= $responseList;
		$context[self::TAGNAME_BROWSER]		= $browser->Name;
		$context[self::TAGNAME_BROWSERVERSION]	= $browser->Version;
		$context[self::TAGNAME_RESULTSNODENAME]	= $resultsNodeName;
		$context[self::TAGNAME_EXPECTINGCOUNT]	= $expected;

		self::setTestContext($context);
	}

	private static /*.string.*/ function getSuiteFilename(/*.string.*/ $suite) {
		$folder = self::getTestContext(self::TAGNAME_FOLDER_TESTS);
		self::makeFolder($folder);
		return $folder.DIRECTORY_SEPARATOR.$suite.'.'.self::TESTS_EXTENSION;
	}

	private static /*.DOMDocument.*/ function getDOMDocument(/*.string.*/ $suite) {
		$filename = self::getSuiteFilename($suite);
		$document = new DOMDocument();
		$document->documentURI = $filename;
		$document->load($filename);
		$document->xinclude();
		return $document;
	}

	private static /*.void.*/ function updateTestSuite(/*.string.*/ $suite, /*.DOMDocument.*/ &$document) {
		$document->save(self::getSuiteFilename($suite));
	}

	private static /*.DOMNodeList.*/ function getTestList(/*.DOMDocument.*/ &$document) {
		return $document->getElementsByTagName(self::TAGNAME_TEST);
	}

	private static /*.string.*/ function getLogFilename($basename = false) {
		$uid = self::getTestContext(self::TAGNAME_UID);

		if ($basename) {
			return self::getPackage()."_$uid.".self::LOG_EXTENSION;
		} else {
			$folder = self::getTestContext(self::TAGNAME_FOLDER_LOGS);
			self::makeFolder($folder);
			return $folder.DIRECTORY_SEPARATOR.self::getPackage()."_$uid.".self::LOG_EXTENSION;
		}
	}

	private static /*.void.*/ function appendLog(/*.string.*/ $text, $dummyRun = false, $textIndent = 4, $tag = 'p', $htmlIndent = 4) {
		$package	= self::getPackage();
		$marginLeft	= ($textIndent === 0) ? '' : " style=\"margin-left:{$textIndent}em\"";

		if ($tag === 'p') {
			$openTag	= "<p$marginLeft class=\"$package-testlog\">";
			$closeTag	= '</p>';
		} else {
			$openTag	= '';
			$closeTag	= '';
		}

		$html		= str_pad('', $htmlIndent, "\t") . "$openTag$text$closeTag\n";

		if ($dummyRun) {
			echo $html;
		} else {
			$handle = fopen(self::getLogFilename(), 'ab');
			fwrite($handle, $html);
			fclose($handle);
		}
	}

	private static /*.void.*/ function logTestContext($dummyRun = false) {
		$package	= self::getPackage();
		$context	= self::getTestContext();

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$package-parameters')\">+</span> Global test parameters", $dummyRun, 0, 'p', 3);
		self::appendLog("<div class=\"$package-testlog\" id=\"$package-parameters\">", $dummyRun, 0, '', 3);
		foreach ($context as $key => $value) self::appendLog("$key = " . htmlspecialchars(substr($value, 0, 64)), $dummyRun);
		self::appendLog('</div>', $dummyRun, 0, '', 3);
		self::appendLog('<hr />', $dummyRun, 0, '', 3);
	}

	private static /*.void.*/ function logTestScript(/*.DOMDocument.*/ $document, $dummyRun = false) {
		$package	= self::getPackage();

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$package-script')\">+</span> Test script", $dummyRun, 0, 'p', 3);
		self::appendLog("<div class=\"$package-testlog\" id=\"$package-script\">", $dummyRun, 0, '', 3);
		self::appendLog('<pre>' . htmlspecialchars($document->saveXML()) . '</pre>', $dummyRun);
		self::appendLog('</div>', $dummyRun, 0, '', 3);
		self::appendLog('<hr />', $dummyRun, 0, '', 3);
	}

	private static /*.void.*/ function logResult($success, $dummyRun = false) {
		$successText = ($success) ? self::STATUS_SUCCESS : self::STATUS_FAIL;
		self::setTestContext(self::TAGNAME_STATUS, $successText);
		self::appendLog('</div>', $dummyRun, 0, '', 3);
		self::appendLog("Result: <strong>$successText</strong>", $dummyRun, 0, 'p', 3);
		self::appendLog('<hr />', $dummyRun, 0, '', 3);

		if ($success) {
			// Increment successful test counter
			$count = (int) self::getTestContext(self::TAGNAME_COUNT);
			self::setTestContext(self::TAGNAME_COUNT, (string) ++$count);
		}
	}

	private static /*.string.*/ function getLogLink() {
		return self::getTestContext(self::TAGNAME_URL_LOGS) . '/' . self::getLogFilename(true);
	}

	private static /*.void.*/ function sendLogLink($dummyRun = false) {
		if ($dummyRun) return;
		$attrName	= self::ATTRNAME_URL;
		$URL		= self::getLogLink();
		$xml		= "<test><open $attrName=\"$URL\" /></test>";
		ajaxUnit::sendContent($xml, self::ACTION_PARSE, 'text/xml');
	}

	private static /*.void.*/ function tidyUp($dummyRun = false) {
		$count = ltrim(self::getTestContext(self::TAGNAME_COUNT));
		self::appendLog("$count tests successfully completed", $dummyRun, 0, 'p', 3);
		self::appendLog(ajaxUnit::htmlPageBottom(), $dummyRun, 0, '', 0);
		self::sendLogLink($dummyRun);
	}

	private static /*.string.*/ function substituteParameters($text) {
		$package	= self::getPackage();
		$URL		= ajaxUnit::thisURL();
		extract(self::getTestContext());
		return eval('return "' . str_replace('"', '\\"', $text) . '";');
	}

	private static /*.void.*/ function investigateDifference(/*.string.*/ $results, /*.string.*/ $expected, /*.string.*/ $unique, $dummyRun = false) {
		$package	= self::getPackage();
		$context	= self::getTestContext();
		$diffID		= $package . '-diff-' . $context[self::TAGNAME_INDEX] . '-' . $context[self::TAGNAME_RESPONSECOUNT] . '-' . $unique;

		$analysis	= 'Browser is ' . $context[self::TAGNAME_BROWSER] . '  ' . $context[self::TAGNAME_BROWSERVERSION] . "<br />\n";

		$resultsMetric	= strlen($results);
		$expectedMetric	= strlen($expected);
		$analysis	.= "Response has $resultsMetric characters, expected response has $expectedMetric<br />\n";

		$resultsMetric	= str_word_count($results);
		$expectedMetric	= str_word_count($expected);
		$analysis	.= "\t\t\t\tResponse has $resultsMetric words, expected response has $expectedMetric<br />\n";

		$percentage	= 0;
		$metric		= similar_text($results, $expected, $percentage);
		$percentage	= number_format($percentage, 2);
		$analysis	.= "\t\t\t\tText similarity: $metric characters ($percentage%)";

		self::appendLog($analysis, $dummyRun, 6);

		// If we happen to have PEAR Text_Diff available then use it
		@include_once 'Text/Diff.php';
		@include_once 'Text/Diff/Renderer.php';

		if (class_exists('Text_Diff') && class_exists('Text_Diff_Renderer')) {
			$originalLevel	= error_reporting(E_ALL); // Somebody teach these PEAR guys to write code, please.</hubris>
			$diff		= new Text_Diff(explode("\n", $expected), explode("\n", $results));
			$renderer	= new Text_Diff_Renderer();
			$diffText	= htmlspecialchars($renderer->render($diff));
			error_reporting($originalLevel); // Back to E_STRICT
		} else {
			$diffText	= "Results:\n" . htmlspecialchars($results) . "\nExpected:\n" . htmlspecialchars($expected);
		}

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$diffID')\">+</span> Actual results compared with expected results", $dummyRun, 6);
		self::appendLog("<pre class=\"$package-testlog\" id=\"$diffID\">$diffText</pre>", $dummyRun, 8, '');

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$diffID-expected')\">+</span> Expected results", $dummyRun, 6);
		self::appendLog("<pre class=\"$package-testlog\" id=\"$diffID-expected\">" . bin2hex($expected) . "</pre>", $dummyRun, 8, '');

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$diffID-results')\">+</span> Actual results", $dummyRun, 6);
		self::appendLog("<pre class=\"$package-testlog\" id=\"$diffID-results\">" . bin2hex($results) . "</pre>", $dummyRun, 8, '');
	}

	private static /*.boolean.*/ function compareHTML($results = '', $expected = '', $dummyRun = false) {
		preg_match_all('/(?<=>|^)[^><\r\n]+(?=<|$|\r|\n)|<.*>/Um', $results, $matches);
		$resultsArray	= $matches[0];
		preg_match_all('/(?<=>|^)[^><\r\n]+(?=<|$|\r|\n)|<.*>/Um', $expected, $matches);
		$expectedArray	= $matches[0];
		$lineCount	= count($resultsArray);
		self::appendLog("Trying element-by-element comparison of $lineCount elements...", $dummyRun, 6);

		if ($lineCount === count($expectedArray)) {
			$success = true;

			for ($line = 0; $line < $lineCount; $line++) {
				$thisResultsLine	= $resultsArray[$line];
				$thisExpectedLine	= $expectedArray[$line];
				$lineOrdinal		= $line + 1;

				if ($thisResultsLine !== $thisExpectedLine) {
					if ($thisResultsLine[0] === '<' && $thisExpectedLine[0] === '<') {
						// Compare attributes
						$thisResultsAttributes	= preg_split("/[\s]+/", substr($thisResultsLine, 1, strlen($thisResultsLine) - 2));
						$thisExpectedAttributes	= preg_split("/[\s]+/", substr($thisExpectedLine, 1, strlen($thisExpectedLine) - 2));
						$attributeCount		= count($thisResultsAttributes);
						self::appendLog("Trying attribute-by-attribute comparison of $attributeCount attributes on line $lineOrdinal...", $dummyRun, 8);

						if ($attributeCount === count($thisExpectedAttributes)) {
							sort($thisResultsAttributes);
							sort($thisExpectedAttributes);
							$log = "<pre>Actual\t|\tExpected<br />\n";

							for ($attribute = 0; $attribute < $attributeCount; $attribute++) {
								$thisResultsAttribute	= $thisResultsAttributes[$attribute];
								$thisExpectedAttribute	= $thisExpectedAttributes[$attribute];
								$log .= htmlspecialchars($thisResultsAttribute) . "\t|\t" . htmlspecialchars($thisExpectedAttribute) . "<br />\n";

								if ($thisResultsAttribute !== $thisExpectedAttribute) {
									if ($success) self::appendLog("In line $lineOrdinal, '". htmlspecialchars($thisResultsAttribute) . "' (actual) was not the same as '" . htmlspecialchars($thisExpectedAttribute) . "' (expected)", $dummyRun, 8);
									$success = false;
								}
							}

							if (!$success) {
								self::appendLog("$log</pre>", $dummyRun, 8);
								break;
							}
						} else {
							self::appendLog("Different number of attributes in line $lineOrdinal", $dummyRun, 8);
							$success = false;
							break;
						}
					} else {
						self::appendLog("Line $lineOrdinal not the same as expected, and not an HTML element", $dummyRun, 8);
						$success = false;
					}
				}
			}
		} else {
			self::appendLog("Different number of lines", $dummyRun, 8);
			$success	= false;
		}

		return $success;
	}

	private static /*.boolean.*/ function compareLineByLine($results = '', $expected = '') {
		$resultsArray	= explode("\n", $results);
		$expectedArray	= explode("\n", $expected);
		$lineCount	= count($resultsArray);
		self::appendLog("Trying line-by-line comparison of $lineCount lines...", $dummyRun, 6);

		if ($lineCount === count($expectedArray)) {
			$success = true;

			for ($line = 0; $line < $lineCount; $line++) {
				$thisResultsLine	= $resultsArray[$line];
				$thisExpectedLine	= $expectedArray[$line];
				$lineOrdinal		= $line + 1;

				if ($thisResultsLine !== $thisExpectedLine) {
					$thisResultsElements	= preg_split("/[\s]+/", $thisResultsLine);
					$thisExpectedElements	= preg_split("/[\s]+/", $thisExpectedLine);
					$elementCount		= count($thisResultsElements);
					self::appendLog("Trying element-by-element comparison of $elementCount elements on line $lineOrdinal...", $dummyRun, 8);

					if ($elementCount === count($thisExpectedElements)) {
						sort($thisResultsElements);
						sort($thisExpectedElements);
						$log = "<pre>Actual\t|\tExpected<br />\n";

						for ($element = 0; $element < $elementCount; $element++) {
							$thisResultsElement	= $thisResultsElements[$element];
							$thisExpectedElement	= $thisExpectedElements[$element];
							$log .= htmlspecialchars($thisResultsElement) . "\t|\t" . htmlspecialchars($thisExpectedElement) . "<br />\n";

							if ($thisResultsElement !== $thisExpectedElement) {
								if ($success) self::appendLog("In line $lineOrdinal, '". htmlspecialchars($thisResultsElement) . "' (actual) was not the same as '" . htmlspecialchars($thisExpectedElement) . "' (expected)", $dummyRun, 8);
								$success = false;
							}
						}

						if (!$success) {
							self::appendLog("$log</pre>", $dummyRun, 8);
							break;
						}
					} else {
						self::appendLog("Different number of elements in line $lineOrdinal", $dummyRun, 8);
						$success = false;
						break;
					}
				}
			}
		} else {
			self::appendLog("Different number of lines", $dummyRun, 8);
			$success	= false;
		}

		return $success;
	}

	private static /*.boolean.*/ function compareXMLNode(/*.DOMNode.*/ $nodeResults, /*.DOMNode.*/ $nodeExpected) {
		$nameResults		= $nodeResults->nodeName;
//		$valueResults		= $nodeResults->nodeValue;
		$typeResults		= $nodeResults->nodeType;
		$childrenResults	= $nodeResults->childNodes;
		$textContentResults	= $nodeResults->textContent;
		$attributesResults	= $nodeResults->attributes;
		$nameExpected		= $nodeExpected->nodeName;
//		$valueExpected		= $nodeExpected->nodeValue;
		$typeExpected		= $nodeExpected->nodeType;
		$childrenExpected	= $nodeExpected->childNodes;
		$textContentExpected	= $nodeExpected->textContent;
		$attributesExpected	= $nodeExpected->attributes;

		if ($nameResults !== $nameExpected) {
			self::appendLog("Expecting node name '$nameExpected', got '$nameResults'", false, 8);
			return false;
		}

		if ($typeResults !== $typeExpected) {
			self::appendLog("Expecting node type $typeExpected, got $typeResults", false, 8);
			return false;
		}

		// Check for extra attributes
		foreach ($nodeResults->attributes as $attributeNameResult => $attributeNodeResult) {
			$attributeNodeExpected = $nodeExpected->attributes->getNamedItem($attributeNameResult);

			if (is_null($attributeNodeExpected)) {
				self::appendLog("Found attribute $attributeNameResult in node $nameResults which I wasn't expecting", false, 8);
				return false;
			}

			$attributeValueResults	= $attributeNodeResults->nodeValue;
			$attributeValueExpected	= $attributeNodeExpected->nodeValue;

			if ($attributeValueResults !== $attributeValueExpected) {
				self::appendLog("Attribute $attributeNameResult in node $nameResults has value '$attributeValueResults'. Expecting '$attributeValueExpected'", false, 8);
				return false;
			}
		}

		// Check for missing attributes
		foreach ($nodeExpected->attributes as $attributeNameExpected => $attributeNodeExpected) {
			$attributeNodeResults = $nodeResults->attributes->getNamedItem($attributeNameExpected);

			if (is_null($attributeNodeResults)) {
				self::appendLog("Attribute $attributeNameExpected in node $nameExpected is missing", false, 8);
				return false;
			}

			$attributeValueResults	= $attributeNodeResults->nodeValue;
			$attributeValueExpected	= $attributeNodeExpected->nodeValue;

			if ($attributeValueResults !== $attributeValueExpected) {
				self::appendLog("Attribute $attributeNameResult in node $nameResults has value '$attributeValueResults'. Expecting '$attributeValueExpected'", false, 8);
				return false;
			}
		}

		// If by some miracle the textContents are equal then we are done
		if ($textContentResults === $textContentExpected) {
//self::appendLog("Node $nameResults: $textContentResults === $textContentExpected", false, 8); // debug
			return true;
		} else {
			// Examine the children
			$childCountResults	= $childrenResults->length;
			$childCountExpected	= $childrenExpected->length;

			if ($childCountResults === $childCountExpected) {
				for ($i = 0; $i < $childCountResults; $i++) {
					$success = self::compareXMLNode($childrenResults->item($i), $childrenExpected->item($i));
					if (!$success) return false;
				}

				return true;
			} else {
				self::appendLog("Node $nameResults has $childCountResults children. Expecting $childCountExpected", false, 8);
				return false;
			}
		}
	}

	private static /*.boolean.*/ function compareXML($results = '', $expected = '') {
		self::appendLog("Trying XML comparison...", $dummyRun, 6);
		$documentExpected = new DOMDocument();

		if (!$documentExpected->loadXML("<xml>$expected</xml>")) {
			self::appendLog("Expected results were not well-formed", $dummyRun, 8);
			return false;
		}

		$documentResults = new DOMDocument();

		if (!$documentResults->loadXML("<xml>$results</xml>")) {
			self::appendLog("Actual results were not well-formed", $dummyRun, 8);
			return false;
		}

		return self::compareXMLNode($documentResults->firstChild, $documentExpected->firstChild);
	}

// ---------------------------------------------------------------------------
// Actions
// ---------------------------------------------------------------------------
	private static /*.boolean.*/ function doClick(/*.DOMElement.*/ $element, $dummyRun = false) {
		$id = $element->getAttribute(self::ATTRNAME_ID);
		self::appendLog("Click button <strong>$id</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doCookies(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		$package = self::getPackage();
		self::appendLog("Update cookies", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$action	= $node->nodeName;
				$name	= self::substituteParameters($node->getAttribute(self::ATTRNAME_NAME));

				if ($dummyRun) {
					$value	= $node->getAttribute(self::ATTRNAME_VALUE);
					$days	= $node->getAttribute(self::ATTRNAME_DAYS);
					self::appendLog("$action: $name -> $value ($days)", $dummyRun);
				} else {
					switch ($action) {
					case self::TAGNAME_DELETE:
						self::appendLog(ajaxUnit_cookies::remove($name), $dummyRun);
						break;
					case self::TAGNAME_SET:
						$value	= $node->getAttribute(self::ATTRNAME_VALUE);
						$days	= $node->getAttribute(self::ATTRNAME_DAYS);
						self::appendLog(ajaxUnit_cookies::set($name, $value, $days), $dummyRun);
						break;
					}
				}
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doCustomURL(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("Custom URL", $dummyRun, 2);
	}

	private static /*.boolean.*/ function doFileOps(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("File operations", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$action	= $node->nodeName;

				switch ($action) {
				case self::TAGNAME_COPY:
					$source		= self::substituteParameters($node->getAttribute(self::ATTRNAME_SOURCE));
					$destination	= self::substituteParameters($node->getAttribute(self::ATTRNAME_DESTINATION));

					self::appendLog("Copying <em>$source</em> to <em>$destination</em>", $dummyRun);
					if (!$dummyRun) copy($source, $destination);
					break;
				case self::TAGNAME_DELETE:
					$name		= self::substituteParameters($node->getAttribute(self::ATTRNAME_NAME));

					self::appendLog("Deleting <em>$name</em>", $dummyRun);

					if (!$dummyRun && is_file($name)) unlink($name);
					break;
				}
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doFormFill(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("Update form fields", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$type	= $node->nodeName;
				$value	= self::substituteParameters($node->nodeValue);
				$id	= $node->getAttribute(self::ATTRNAME_ID);

				self::appendLog("Setting $type control <strong>$id</strong> to <em>$value</em>", $dummyRun);
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doHeaders(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("Send HTTP headers", $dummyRun, 2);
		if (headers_sent()) self::appendLog("<strong>Warning: headers have already been sent</strong>");

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
						echo '.';
						exit;
					} else {
						header("$header: $value");
					}
				}
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doIncludePath(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
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
				}
			}
		}

		self::appendLog("New include path is <em>" . get_include_path() . "</em>", $dummyRun);
		return true;
	}

	private static /*.boolean.*/ function doLocation(/*.DOMElement.*/ $element, $dummyRun = false) {
		$url = self::substituteParameters($element->getAttribute(self::ATTRNAME_URL));
		self::appendLog("Set location <strong>$url</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doLogAppend(/*.DOMElement.*/ $element, $dummyRun = false) {
		$content = self::substituteParameters($element->nodeValue);
		self::appendLog("Adding to browser log: <strong>$content</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doPost(/*.DOMElement.*/ $element, $dummyRun = false) {
		$id = $element->getAttribute(self::ATTRNAME_ID);
		self::appendLog("Posting contents of element <strong>$id</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doResults(/*.DOMNodeList.*/ $nodeList, /*.string.*/ $results, $dummyRun = false) {
		self::appendLog("Compare actual results with expected", $dummyRun);

		// Each <result> element is a potential match for $results
		// If none of them match then it's a FAIL
		$responseList	= self::getTestContext(self::TAGNAME_RESPONSELIST);
		$success	= false;
		$resultIndex	= -1;

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
					if (get_magic_quotes_gpc()) $results = stripslashes($results);	// magic_quotes_gpc will go away soon, but for now

					$expected	= htmlspecialchars_decode(self::substituteParameters($node->nodeValue));
					$expected	= str_replace(chr(0xEF).chr(0xBB).chr(0xBF), '', $expected); // Get rid of stray UTF-8 BOMs from XIncluded files

					if ($dummyRun) {
						$success = true;
					} else {
						$success = ($results === $expected); // This is the whole point of all this!

						if (!$success) {
							self::appendLog("Byte-for-byte match not successful, trying looser EOL definition...", $dummyRun, 6);
							// Try substituting \r\n for \n in strings (because bits of either may come from a Windows file)
							$results	= str_replace("\r\n", "\n", $results);
							$expected	= str_replace("\r\n", "\n", $expected);
							$success	= ($results === $expected);
						}

						if (!$success) {
							$success = self::compareHTML($results, $expected, $dummyRun);
						}
					}

					if ($success) {
						self::appendLog("Match with test data set #$indexText", $dummyRun, 6);
						// Remove this results set from the list of responses we are expecting
						$indexPos	= strpos($responseList, (string) $resultIndex);
						$responseList	= substr($responseList, 0, $indexPos) . substr($responseList, $indexPos + 1);
						self::setTestContext(self::TAGNAME_RESPONSELIST, $responseList);
						break;
					} else {
						self::investigateDifference($results, $expected, (string) $i, $dummyRun);
					}
				}
			}
		}

		$result = ($success) ? "Match found!" : "No match found";
		self::appendLog($result, $dummyRun, 6);
		return $success;
	}

	private static /*.boolean.*/ function doSession(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
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
						$_SESSION = array();
					}

					self::appendLog("Session reset", $dummyRun);
					break;
				}
			}
		}

		return true;
	}

	private static /*.boolean.*/ function initiateTest(/*.DOMDocument.*/ $document, /*.DOMElement.*/ $test, /*.int.*/ $testIndex, $dummyRun = false) {
		self::setInitialContext($test, $testIndex, $dummyRun);

		$package	= self::getPackage();
		$testName	= $test->hasAttribute(self::ATTRNAME_NAME) ? $test->getAttribute(self::ATTRNAME_NAME) : "#" . ($testIndex + 1);
		$testId		= htmlspecialchars($testName);

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$package-$testId')\">+</span> Test <strong>$testName</strong>", $dummyRun, 0, 'p', 3);
		self::appendLog("<div class=\"$package-testlog\" id=\"$package-$testId\">", $dummyRun, 0, '', 3);

		$childNodes	= $test->childNodes;
		$sendToBrowser	= false;

		// Do any server-based actions
		for ($i = 0; $i < $childNodes->length; $i++) {
			$step = $childNodes->item($i);

			if ($step->nodeType === XML_ELEMENT_NODE) {
				$stepType	= $step->nodeName;
				$stepList	= $step->childNodes;

				if (!$dummyRun && (strpos($stepType, self::TAGNAME_RESULTS) === 0)) $test->removeChild($step);

				switch ($stepType) {
					case self::TAGNAME_CLICK:	$success = self::doClick	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_COOKIES:	$success = self::doCookies	($stepList,	$dummyRun);					break;
					case self::TAGNAME_CUSTOMURL:	$success = self::doCustomURL	($stepList,	$dummyRun);					break;
					case self::TAGNAME_FILE:	$success = self::doFileOps	($stepList,	$dummyRun);					break;
					case self::TAGNAME_FORMFILL:	$success = self::doFormFill	($stepList,	$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_HEADERS:	$success = self::doHeaders	($stepList,	$dummyRun);					break;
					case self::TAGNAME_INCLUDEPATH:	$success = self::doIncludePath	($stepList,	$dummyRun);					break;
					case self::TAGNAME_LOCATION:	$success = self::doLocation	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_LOGAPPEND:	$success = self::doLogAppend	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_POST:	$success = self::doPost		($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_SESSION:	$success = self::doSession	($stepList,	$dummyRun);					break;
					case self::TAGNAME_STOP:	$success = false;										break;
				}

				if (!$success) break;
			}
		}

		if ($success) {
			if ($sendToBrowser) {
				// We've got the next test details so send them to the browser script
				self::appendLog("Sending instructions to browser", false, 2);
				$xml = self::substituteParameters($document->saveXML($test));
				ajaxUnit::sendContent($xml, self::ACTION_PARSE, 'text/xml');
			} else {
				self::parseTest($dummyRun); // Move on to the next test
			}
		}

		return $success;
	}

// ---------------------------------------------------------------------------
// Public methods
// ---------------------------------------------------------------------------
//	runTestSuite - called to initiate a named series of tests
// ---------------------------------------------------------------------------
	public static /*.void.*/ function runTestSuite(/*.string.*/ $suite, $dummyRun = false) {
		$context				= self::getTestContext();
		$context[self::TAGNAME_UID]		= gmdate('YmdHis');
		$context[self::TAGNAME_SUITE]		= $suite;
		$context[self::TAGNAME_STATUS]		= self::STATUS_INPROGRESS;
		$context[self::TAGNAME_COUNT]		= (string) 0;

		self::setTestContext($context);

		$document	= self::getDOMDocument($suite);
		$text		= ($dummyRun) ? "Dummy" : "Starting";
		$suiteNode	= $document->getElementsByTagName(self::TAGNAME_SUITE)->item(0);
		$suiteName	= htmlspecialchars($suiteNode->getAttribute(self::ATTRNAME_NAME));
		$suiteVersion	= $suiteNode->getAttribute("version");

		self::appendLog(ajaxUnit::htmlPageTop(), $dummyRun, 0, '', 0);
		self::appendLog("<h3>$text run of test suite \"$suiteName\" version $suiteVersion</h3>", $dummyRun, 0, '', 3);
		self::appendLog("<hr />", $dummyRun, 0, '', 3);

		// Add test script to log
		self::logTestScript($document, $dummyRun);

		// Get global parameters
		$nodeList	= $document->getElementsByTagName(self::TAGNAME_PARAMETERS);
		$node		= ($nodeList->length > 0) ? $nodeList->item(0) : new DOMElement(self::TAGNAME_PARAMETERS);

		if ($node->hasChildNodes()) {
			for ($i = 0; $i < $node->childNodes->length; $i++) {
				$parameter = $node->childNodes->item($i);
				if ($parameter->nodeType === XML_ELEMENT_NODE) $context[$parameter->nodeName] = self::substituteParameters($parameter->nodeValue);
			}
		}

		self::setTestContext($context);
		self::logTestContext($dummyRun);

		// Run tests
		$testList = self::getTestList($document);
		$testNodes = $testList->length;

		if (empty($testNodes)) {
			self::appendLog("There are no tests defined in this test suite", $dummyRun, 0, 'p', 3);
			self::setTestContext(self::TAGNAME_STATUS, self::STATUS_FINISHED);
			self::tidyUp($dummyRun);
			return;
		}

		$success = true;

		// Run the first test (it will advance the tests by itself from then on)
		for ($i = 0; $i < $testNodes; $i++) {
			$test = $testList->item($i);

			if ($test->nodeType === XML_ELEMENT_NODE) {
				$success = self::initiateTest($document, $test, $i, $dummyRun);
				break;
			}
		}

//		self::appendLog(ajaxUnit::htmlPageBottom(), $dummyRun, 0, '', 0);
	}

// ---------------------------------------------------------------------------
//	parseTest - called by the browser when it receives AJAX responseText
// ---------------------------------------------------------------------------
	public static /*.void.*/ function parseTest($dummyRun = false) {
		// Get some context for this test
		$context = self::getTestContext();

		// Are we actually running tests at the moment?
		if (!isset($context[self::TAGNAME_STATUS]) || in_array($context[self::TAGNAME_STATUS], array('', self::STATUS_FINISHED, self::STATUS_FAIL))) {
			self::appendLog('No testing in progress', $dummyRun);
			ajaxUnit::sendContent('No testing in progress', 'logmessage');
			exit;
		}

		// Wait for previous reponse to be parsed
		$totalSleep = 0;

		while ((bool) $context[self::TAGNAME_INPROGRESS]) {
			if ($totalSleep++ > 2) die('Waited too long for another test to be parsed');
			self::appendLog("(Another response is being parsed. Waiting for 1 second)", $dummyRun, 2);
			sleep(1);
			$context = self::getTestContext();
		}

		self::setTestContext(self::TAGNAME_INPROGRESS, (string) true);
//		self::appendLog("Test results being parsed at request of browser", $dummyRun, 2);

		// Get some context for this test
		$suite		= $context[self::TAGNAME_SUITE];
		$testIndex	= (int) $context[self::TAGNAME_INDEX];
		$document	= self::getDOMDocument($suite);		// Get the test suite information
		$testList	= self::getTestList($document);		// Get list of tests
		$test		= $testList->item($testIndex);		// Get this particular test
		$contextText	= ($test->hasAttribute(self::ATTRNAME_NAME)) ? "Test name is <strong>" . $test->getAttribute(self::ATTRNAME_NAME) : "Test index is <strong>$testIndex";

		self::appendLog("$contextText</strong>", $dummyRun);

		$expected = $context[self::TAGNAME_EXPECTINGCOUNT];

		if ($expected < 1) {
			self::appendLog("No responses expected", $dummyRun);
			$success = true;
			self::logResult($success, $dummyRun);
		} else {
			// Check response count is (a) valid and (b) all we are expecting on this page
			if (!isset($context[self::TAGNAME_RESPONSECOUNT])) {
				self::appendLog("<strong>No response count set!</strong>", $dummyRun);
				exit; // Invalid response count
			}

			$responseCount = (int) $context[self::TAGNAME_RESPONSECOUNT];

			if (++$responseCount < 1) {
				self::appendLog("<strong>Response count hasn't been set correctly! ($responseCount)</strong>", $dummyRun);
				exit; // Invalid response count
			} else {
				self::setTestContext(self::TAGNAME_RESPONSECOUNT, (string) $responseCount);
			}

			$resultsNodeName	= $context[self::TAGNAME_RESULTSNODENAME];
			$resultsNode		= $test->getElementsByTagName($resultsNodeName)->item(0);

			self::appendLog("Response count is $responseCount (expecting $expected).", $dummyRun);
			self::appendLog("Using results node '$resultsNodeName'", $dummyRun);

			// Examine the results node
			if ($resultsNode->hasAttribute(self::ATTRNAME_UPDATE)) {
				// Use these as model results
				self::appendLog("Using these as model results", $dummyRun);
				$updateStatus = $resultsNode->getAttribute(self::ATTRNAME_UPDATE);

				if ($updateStatus !== self::STATUS_INPROGRESS) {
					self::appendLog("Clearing existing model results", $dummyRun, 6);
					// Clear away the children & set the status
					while ($resultsNode->hasChildNodes()) $resultsNode->removeChild($resultsNode->lastChild);
					$resultsNode->setAttribute(self::ATTRNAME_UPDATE, self::STATUS_INPROGRESS);
				}

				self::appendLog("Adding this result", $dummyRun, 6);
				$resultsNode->appendChild(new DOMElement(self::TAGNAME_RESULT, $_POST['responseText']));	// Add model result

				if ($responseCount >= $expected) {
					self::appendLog("No more results expected", $dummyRun, 6);
					$resultsNode->removeAttribute(self::ATTRNAME_UPDATE);
				}

				// Write out the test suite
				self::appendLog("Updating test suite", $dummyRun, 6);
				if (!$dummyRun) self::updateTestSuite($suite, $document);
				$success = true;
			} else {
				// Compare these results against the model results
				$results	= ($dummyRun) ? '' : $_POST['responseText'];
				$success	= self::doResults($resultsNode->childNodes, $results, $dummyRun);	// Is it the same?
			}

			if (!$success) {
				self::logResult($success, $dummyRun);
				self::tidyUp($dummyRun);
				self::setTestContext(self::TAGNAME_INPROGRESS, (string) false);
				return;
			}

			if (!$dummyRun && ($responseCount < $expected)) {
				self::appendLog("Waiting for further responses from browser", $dummyRun, 2);
				self::setTestContext(self::TAGNAME_INPROGRESS, (string) false);
				exit;
			}

			self::logResult($success, $dummyRun);
		}

		// Get the next test and send it to the client for form filling etc.
		do {
			$success = (++$testIndex < $testList->length);
			if (!$success) break;
			$test = $testList->item($testIndex);
			if ($test->nodeType === XML_ELEMENT_NODE) break;
		} while (true);

		if ($success) {
			$success = self::initiateTest($document, $test, $testIndex, $dummyRun);
		} else {
			// No more tests
			self::appendLog("No more tests found", $dummyRun, 0, 'p', 3);
			self::setTestContext(self::TAGNAME_STATUS, self::STATUS_FINISHED);
			self::tidyUp($dummyRun);
		}
	}

	public static /*.void.*/ function endTestSuite($message = '', $dummyRun = false) {
		self::appendLog("Testing ended at request of browser", $dummyRun);
		if ($message !== '') self::appendLog($message, $dummyRun);
		self::logResult(false, $dummyRun);
		self::setTestContext(self::TAGNAME_STATUS, self::STATUS_FINISHED);
		self::tidyUp($dummyRun);
	}
}
// End of class ajaxUnit

class ajaxUnit extends ajaxUnit_runtime implements ajaxUnit_API {
// ---------------------------------------------------------------------------
// Helper functions
// ---------------------------------------------------------------------------
	public static /*.string.*/ function thisURL() {
		return self::getURL(self::URL_MODE_PATH, self::getPackage() . '.php');
	}

	private static /*.string.*/ function componentHeader() {return self::getPackage() . "-component";}

	public static /*.void.*/ function sendContent(/*.string.*/ $content, /*.string.*/ $component, $contentType = '') {
		// Send headers first
		if (!headers_sent()) {
			$package	= self::getPackage();

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
		return self::getPackage();
	}

	public static /*.void.*/ function setProject(/*.string.*/ $project) {
		self::setTestContext(self::TAGNAME_PROJECT, $project);
	}

	public static /*.void.*/ function setRoot(/*.string.*/ $folder) {
		$baseURL = str_replace(DIRECTORY_SEPARATOR, '/', $folder);

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
		$package = self::getPackage();

		$css = <<<CSS
@charset "UTF-8";
/**
 * Testing PHP and javascript by controlling the interactions automatically
 * 
 * Copyright (c) 2008-2009, Dominic Sayers							<br>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 * 	- Redistributions of source code must retain the above copyright notice,
 * 	  this list of conditions and the following disclaimer.
 * 	- Redistributions in binary form must reproduce the above copyright notice,
 * 	  this list of conditions and the following disclaimer in the documentation
 * 	  and/or other materials provided with the distribution.
 * 	- Neither the name of Dominic Sayers nor the names of its contributors may be
 * 	  used to endorse or promote products derived from this software without
 * 	  specific prior written permission.
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
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxUnit/
 * @version	0.05 - Tests a major project successfully in my new dev environment
 */

.dummy {} /* Webkit is ignoring the first item so we'll put a dummy one in */

form.$package-form		{margin:0;}
fieldset.$package-fieldset	{padding:0;border:0;margin:0 0 1em 0}
input.$package-buttonstate-0	{background-color:#FFFFFF;color:#444444;border-color:#666666 #333333 #333333 #666666;}
div.$package-testlog		{display:none;}
span.$package-testlog		{font-weight:bold;font-size:16px;cursor:pointer;}
p.$package-testlog		{margin:0;}
pre.$package-testlog		{margin:0;background-color:#F0F0F0;display:none;}
iframe.$package-iframe		{border:1px black solid;}

div#$package {
	font-family:Segoe UI, Calibri, Arial, Helvetica, sans-serif;
	font-size:11px;
	line-height:16px;
	float:left;
	margin:0;
}

label.$package-label {
	margin:5px 0 0 0;
	float:left;
}

input.$package-text {
	float:left;
	font-size:11px;
	width:480px;
	margin:3px 0 0 7px;
}

input.$package-button {
	float:left;
	padding:2px;
	margin:0 7px 0 7px;
	font-family:Segoe UI, Calibri, Arial, Helvetica, sans-serif;
	border-style:solid;
	border-width:1px;
	cursor:pointer;
}

CSS;
		return $css;
	}

	public static /*.void.*/ function getCSS() {
		$html = self::htmlCSS();
		self::sendContent($html, 'CSS', 'text/css');
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlJavascript() {
		$package		= self::getPackage();
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

		$js = <<<JAVASCRIPT
/**
 * Testing PHP and javascript by controlling the interactions automatically
 * 
 * Copyright (c) 2008-2009, Dominic Sayers							<br>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 * 	- Redistributions of source code must retain the above copyright notice,
 * 	  this list of conditions and the following disclaimer.
 * 	- Redistributions in binary form must reproduce the above copyright notice,
 * 	  this list of conditions and the following disclaimer in the documentation
 * 	  and/or other materials provided with the distribution.
 * 	- Neither the name of Dominic Sayers nor the names of its contributors may be
 * 	  used to endorse or promote products derived from this software without
 * 	  specific prior written permission.
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
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxUnit/
 * @version	0.05 - Tests a major project successfully in my new dev environment
 */

/*jslint eqeqeq: true, immed: true, nomen: true, onevar: true, regexp: true, undef: true */
/*global window, document, event, ActiveXObject */ // For JSLint
var ajaxUnitInstances = [];

// ---------------------------------------------------------------------------
//		ajaxUnit
// ---------------------------------------------------------------------------
// The main ajaxUnit client-side class
// ---------------------------------------------------------------------------
function C_ajaxUnit() {
	if (!(this instanceof arguments.callee)) {throw Error('Constructor called as a function');}

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
			container	= that.getControl(id),
			markupStart	= '',
			markupEnd	= '',
			element;

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
				var id = this.getResponseHeader('$componentHeader');

				switch (id) {
				case '$package':
					// Show the test console
					addStyleSheet();
					fillContainer(id, this.responseText);
					break;
				case '$package-$actionParse':
					logAppend('Actions received from test controller');
					doActions(this.responseXML);
					break;
				case '$package-logmessage':
					logAppend(this.responseText);
					break;
				default:
					logAppend('Response received, but no <em>$componentHeader</em> header.');
					logAppend(this.responseText);
				}
			}
		},

		serverTalk: function (requestType, requestData) {
			var URL = '$URL';

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

		logAppend('Relaying an XMLHttpRequest response to $URL');
		this.ajax.serverTalk('POST', postData);
	};

	this.click = function (control) {
		var	action	= control.getAttribute('data-$package-action'),
			iFrame	= document.getElementById('$package-iframe'),
			appAjax;

		switch (action) {
		case 'setIFrameSource':
			iFrame.src = document.getElementById('$package-url').value;
			break;
		case 'post':
			appAjax = {readyState: 4, status: 200, responseText: iFrame.contentDocument.body.innerHTML};
			this.postResponse(appAjax);
		}

		return false;
	};

	this.keyUp = function (e) {
		if (!e) {e = window.event;}
		var target = (e.target) ? e.target : e.srcElement;

		// Process Carriage Return and tidy up form
		if (target.form.id === '$package-iframe-form' && e.keyCode === 13) {this.click(document.getElementById('$package-OK'));}
		return false;
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

JAVASCRIPT;
		return $js;
	}

	public static /*.void.*/ function getJavascript() {
		$html = self::htmlJavascript();
		self::sendContent($html, 'Javascript', 'text/javascript');
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlControlPanel() {
		$actionSuite	= self::ACTION_SUITE;
		$actionDummy	= self::ACTION_DUMMY;
		$package	= self::getPackage();
		$folder		= self::getTestContext(self::TAGNAME_FOLDER_TESTS);
		$extension	= self::TESTS_EXTENSION;
		$suiteList	= "";

		foreach (glob($folder.DIRECTORY_SEPARATOR."*.$extension") as $filename) {
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
			$project = self::getTestContext(self::TAGNAME_PROJECT);

			$html = <<<HTML
		<h2>ajaxUnit testing for project $project</h2>
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

			// While we're here, let's put a copy of ajaxunit.js in the tests folder
			$js		= self::htmlJavascript();
			$filename	= $folder.DIRECTORY_SEPARATOR.$package.'.js';
			file_put_contents($filename, $js);
		}

		return $html;
	}

	public static /*.void.*/ function getControlPanel() {
		$html = self::htmlControlPanel();
		self::sendContent($html, self::getPackage());
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlCustomURL() {
		$package	= self::getPackage();
		$packageCamel	= self::getPackage(self::PACKAGE_CASE_CAMEL);
		$URL		= self::thisURL();
		$html		= <<<HTML
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>$packageCamel Custom URL</title>
<link type="text/css" rel="stylesheet" href="$URL?css" title="$packageCamel">
<script src="$URL?js"></script>
<script type="text/javascript">var $package = new C_$packageCamel()</script>
</head>

<body>
	<div id="$package">
		<form id="$package-iframe-form" class="$package-form" onsubmit="return false">
			<fieldset class="$package-fieldset">
				<label for="$package-url" class="$package-label">URL:</label>
				<input type="text" id="$package-url" class="$package-text" onkeyup = "$package.keyUp(event)">
				<input type="button" id="$package-OK" class="$package-button" value="Go" data-$package-action="setIFrameSource" onclick="$package.click(this)">
				<input type="button" id="$package-post" class="$package-button" value="Post" data-$package-action="post" onclick="$package.click(this)">
			</fieldset>
		</form>
	</div>
	<iframe width="640" height="480" id="$package-iframe" class="$package-iframe"></iframe>
</body>

</html>
HTML;

		return $html;
	}

	public static /*.void.*/ function getCustomURL() {
		$html = self::htmlCustomURL();
		self::sendContent($html, self::getPackage());
	}

// ---------------------------------------------------------------------------
	private static /*.string.*/ function htmlAddScript() {
//		$package		= self::getPackage();
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
//		$package	= self::getPackage();
		$packageCamel	= self::getPackage(self::PACKAGE_CASE_CAMEL);
		$instance	= self::getInstance($action);
		$addScript	= self::htmlAddScript();

		return <<<HTML
	<div id="$instance"></div>
$addScript
	<script type="text/javascript">var obj = new C_$packageCamel; obj.getResponse('$action');</script>
HTML;
	}

	public static /*.void.*/ function getContainer($action = self::ACTION_PARSE) {
		$html = self::htmlContainer($action);
		self::sendContent($html, 'container');
	}

// ---------------------------------------------------------------------------
	private	static /*.string.*/	function htmlAbout()	{
		$php = self::getFileContents(self::getPackage() . '.php', 0, NULL, -1, 4096);
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

// ---------------------------------------------------------------------------
	public static /*.string.*/ function htmlPageTop() {
		$package		= self::getPackage();
		$packageCamel		= self::getPackage(self::PACKAGE_CASE_CAMEL);
		$actionCSS		= self::ACTION_CSS;
		$URL			= self::thisURL();

		return <<<HTML
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>$packageCamel</title>
		<link type="text/css" rel="stylesheet" href="$URL?$actionCSS" title="ajaxUnit"/>
	</head>
	<body>
		<div id="$package">
			<a id="top" href="#bottom">bottom &raquo;</a>
HTML;
	}

	public static /*.string.*/ function htmlPageBottom() {
		$package = self::getPackage();

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
		self::sendContent($html, self::getPackage());
	}

	public	static /*.void.*/ function getPageBottom() {
		$html = self::htmlPageBottom();
		self::sendContent($html, self::getPackage());
	}

// ---------------------------------------------------------------------------
	public static /*.void.*/ function tidyLogFiles() {
		$folder		= self::getTestContext(self::TAGNAME_FOLDER_LOGS);
		$extension	= self::LOG_EXTENSION;

		foreach (glob($folder.DIRECTORY_SEPARATOR."*.$extension") as $filename) {
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


// Some code to make this all automagic and a bit RESTful
// If you want more control over how ajaxUnit works then you might need to amend
// or even remove the code below here

// Is this script included in another page or is it the HTTP target itself?
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
	// This script has been called directly by the client
	if (is_array($_GET) && (count($_GET) > 0)) {
		$dummyRun = (isset($_GET[ajaxUnit::ACTION_DUMMY])) ? (bool) $_GET[ajaxUnit::ACTION_DUMMY] : false;
		if (isset($_GET[ajaxUnit::ACTION_SUITE]))	ajaxUnit_runtime::runTestSuite($_GET[ajaxUnit::ACTION_SUITE], $dummyRun);
		if (isset($_GET[ajaxUnit::ACTION_END]))		ajaxUnit_runtime::endTestSuite($_GET[ajaxUnit::ACTION_END], $dummyRun);
		if (isset($_GET[ajaxUnit::ACTION_PARSE]))	ajaxUnit_runtime::parseTest();
		if (isset($_GET[ajaxUnit::ACTION_CONTROL]))	ajaxUnit::getControlPanel();
		if (isset($_GET[ajaxUnit::ACTION_CUSTOMURL]))	ajaxUnit::getCustomURL();
		if (isset($_GET[ajaxUnit::ACTION_JAVASCRIPT]))	ajaxUnit::getJavascript();
		if (isset($_GET[ajaxUnit::ACTION_CSS]))		ajaxUnit::getCSS();
		if (isset($_GET[ajaxUnit::ACTION_ABOUT]))	ajaxUnit::getAbout();
		if (isset($_GET[ajaxUnit::ACTION_SOURCECODE]))	ajaxUnit::getSourceCode();
		if (isset($_GET[ajaxUnit::ACTION_LOGTIDY]))	{ajaxUnit::tidyLogFiles(); ajaxUnit::getControlPanel();}
	} else {
		if (is_array($_POST) && (count($_POST) > 0)) {
			ajaxUnit::parseTest();
		} else {
			ajaxUnit::addScript();
		}
	}
}
?>