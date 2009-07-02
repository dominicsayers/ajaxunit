<?php
// ---------------------------------------------------------------------------
//		ajaxUnit
// ---------------------------------------------------------------------------
/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link	http://code.google.com/p/ajaxunit/
 * @version	0.19 - Now BSD licensed

 */

/*.
	require_module 'dom';
.*/

class ajaxUnit implements ajaxUnitAPI {
// ---------------------------------------------------------------------------
// Private methods
// ---------------------------------------------------------------------------
	private static /*.void.*/ function makeFolder(/*.string.*/ $folder) {
		if (!is_dir($folder)) mkdir($folder, 0600);
	}

	private static /*.string.*/ function getSuiteFilename(/*.string.*/ $suite) {
		self::makeFolder(self::TESTS_FOLDER);
		return self::TESTS_FOLDER . "/$suite." . self::TESTS_EXTENSION;
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

	private static /*.string.*/ function getContextFilename() {
		self::makeFolder(self::CONTEXT_FOLDER);
		return self::CONTEXT_FOLDER . "/." . self::PACKAGE . "_" . $_SERVER['REMOTE_ADDR'];
	}

	private static /*.void.*/ function setTestContext(/*.mixed.*/ $newContext, $value = '') {
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

	private static /*.mixed.*/ function getTestContext($key = '') {
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

	private static /*.void.*/ function setInitialContext(/*.DOMElement.*/ &$test, /*.int.*/ $testIndex, $dummyRun = false) {
		// Build a string containing a list of expected responses (1234...n) according to <results> child nodes
		$resultsList	= $test->getElementsByTagName(self::TAGNAME_RESULT);	// Get the expected results
		$expected	= $resultsList->length;
		$responseList	= '';

		for ($i = 0; $i < $expected; $i++) $responseList .= (string) $i;

		$context = /*.(array[string]string).*/ array();
		$context[self::TAGNAME_INDEX]		= (string) $testIndex;
		$context[self::TAGNAME_RESPONSECOUNT]	= (string) 0;
		$context[self::TAGNAME_INPROGRESS]	= (string) false;
		$context[self::TAGNAME_RESPONSELIST]	= $responseList;
		$context[self::TAGNAME_EXPECTINGCOUNT]	= $expected;

		self::setTestContext($context);
	}

	private static /*.string.*/ function getLogFilename() {
		$uid = self::getTestContext(self::TAGNAME_UID);
		self::makeFolder(self::LOG_FOLDER);
		return self::LOG_FOLDER . "/" . self::PACKAGE . "_$uid." . self::LOG_EXTENSION;
	}

	private static /*.void.*/ function appendLog(/*.string.*/ $text, $dummyRun = false, $textIndent = 4, $tag = 'p', $htmlIndent = 4) {
		$package	= self::PACKAGE;
		$marginLeft	= ($textIndent === 0) ? '' : " style=\"margin-left:{$textIndent}em\"";

		if ($tag === 'p') {
			$openTag	= "<p$marginLeft class=\"$package-testlog\">";
			$closeTag	= '</p>';
		} else {
			$openTag	= '';
			$closeTag	= '';
		}

		$html = str_pad('', $htmlIndent, "\t") . "$openTag$text$closeTag\n";

		if ($dummyRun) {
			echo $html;
		} else {
			$handle = fopen(self::getLogFilename(), 'ab');
			fwrite($handle, $html);
			fclose($handle);
		}
	}

	private static /*.void.*/ function logTestContext($dummyRun = false) {
		$package	= self::PACKAGE;
		$context	= self::getTestContext();

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$package-parameters')\">+</span> Global test parameters", $dummyRun, 0, 'p', 3);
		self::appendLog("<div class=\"$package-testlog\" id=\"$package-parameters\">", $dummyRun, 0, '', 3);
		foreach ($context as $key => $value) self::appendLog("$key = " . htmlspecialchars(substr($value, 0, 64)), $dummyRun);
		self::appendLog('</div>', $dummyRun, 0, '', 3);
		self::appendLog('<hr />', $dummyRun, 0, '', 3);
	}

	private static /*.void.*/ function logTestScript(/*.DOMDocument.*/ $document, $dummyRun = false) {
		$package	= self::PACKAGE;

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
		return dirname($_SERVER['SCRIPT_NAME']) . "/" . self::getLogFilename();
	}

	private static /*.void.*/ function sendLogLink($dummyRun = false) {
		if ($dummyRun) return;
		$attrName	= self::ATTRNAME_URL;
		$URL		= self::getLogLink();
		$xml		= "<test><open $attrName=\"$URL\" /></test>";
		ajaxUnitUI::sendContent($xml, self::ACTION_PARSE, 'text/xml');
	}

	private static /*.void.*/ function tidyUp($dummyRun = false) {
		$count = ltrim(self::getTestContext(self::TAGNAME_COUNT));
		self::appendLog("$count tests successfully completed", $dummyRun, 0, 'p', 3);
		self::appendLog(ajaxUnitUI::htmlPageBottom(), $dummyRun, 0, '', 0);
		self::sendLogLink($dummyRun);
	}

	private static /*.string.*/ function substituteParameters($text) {
		extract(self::getTestContext());
		return eval('return "' . str_replace('"', '\\"', $text) . '";');
	}

	private static /*.void.*/ function investigateDifference(/*.string.*/ $results, /*.string.*/ $expected, /*.string.*/ $unique, $dummyRun = false) {
		$package	= self::PACKAGE;
		$context	= self::getTestContext();
		$diffID		= $package . '-diff-' . $context[self::TAGNAME_INDEX] . '-' . $context[self::TAGNAME_RESPONSECOUNT] . '-' . $unique;

		$resultsMetric	= strlen($results);
		$expectedMetric	= strlen($expected);
		$analysis	= "Response has $resultsMetric characters, expected response has $expectedMetric<br />\n";

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

		self::appendLog("<span class=\"$package-testlog\" onclick=\"{$package}_toggle_log(this, '$diffID-results')\">+</span> Actuals results", $dummyRun, 6);
		self::appendLog("<pre class=\"$package-testlog\" id=\"$diffID-results\">" . bin2hex($results) . "</pre>", $dummyRun, 8, '');
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
		$package	= self::PACKAGE;
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
						self::appendLog(ajaxUnitCookies::remove($name), $dummyRun);
						break;
					case self::TAGNAME_SET:
						$value	= $node->getAttribute(self::ATTRNAME_VALUE);
						$days	= $node->getAttribute(self::ATTRNAME_DAYS);
						self::appendLog(ajaxUnitCookies::set($name, $value, $days), $dummyRun);
						break;
					}
				}
			}
		}

		return true;
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
					$path = self::substituteParameters($node->nodeValue);
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
					$results	= htmlspecialchars_decode($results);
					$expected	= htmlspecialchars_decode(self::substituteParameters($node->nodeValue));
					$expected	= str_replace(chr(0xEF).chr(0xBB).chr(0xBF), '', $expected); // Get rid of stray UTF-8 BOMs from XIncluded files
//					if (get_magic_quotes_gpc()) $expected = addslashes($expected);	// magic_quotes_gpc will go away soon, but for now

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

	private static /*.boolean.*/ function initiateTest(/*.DOMDocument.*/ &$document, /*.DOMElement.*/ &$test, /*.int.*/ $testIndex, $dummyRun = false) {
		self::setInitialContext($test, $testIndex, $dummyRun);

		$package	= self::PACKAGE;
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

				switch ($stepType) {
					case self::TAGNAME_CLICK:	$success = self::doClick	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_COOKIES:	$success = self::doCookies	($stepList,	$dummyRun);					break;
					case self::TAGNAME_FILE:	$success = self::doFileOps	($stepList,	$dummyRun);					break;
					case self::TAGNAME_FORMFILL:	$success = self::doFormFill	($stepList,	$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_HEADERS:	$success = self::doHeaders	($stepList,	$dummyRun);					break;
					case self::TAGNAME_INCLUDEPATH:	$success = self::doIncludePath	($stepList,	$dummyRun);					break;
					case self::TAGNAME_LOCATION:	$success = self::doLocation	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_LOGAPPEND:	$success = self::doLogAppend	($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_POST:	$success = self::doPost		($step,		$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_SESSION:	$success = self::doSession	($stepList,	$dummyRun);					break;
					case self::TAGNAME_STOP:	$success = false;										break;
					case self::TAGNAME_RESULTS:	if (!$dummyRun) $test->removeChild($step);
				}

				if (!$success) break;
			}
		}

		if ($success) {
			if ($sendToBrowser) {
				// We've got the next test details so send them to the browser script
				self::appendLog("Sending instructions to browser", false, 2);
				$xml = self::substituteParameters($document->saveXML($test));
				ajaxUnitUI::sendContent($xml, self::ACTION_PARSE, 'text/xml');
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
		$context	= /*.(array[string]string).*/ array();
		$testRoot	= dirname(__FILE__);

		$context[self::TAGNAME_UID]		= gmdate('YmdHis');
		$context[self::TAGNAME_SUITE]		= $suite;
		$context[self::TAGNAME_STATUS]		= self::STATUS_INPROGRESS;
		$context[self::TAGNAME_TESTSFOLDER]	= self::TESTS_FOLDER;
		$context[self::TAGNAME_TESTROOT]	= $testRoot;
		$context[self::TAGNAME_COUNT]		= (string) 0;

		self::setTestContext($context);

		$document	= self::getDOMDocument($suite);
		$text		= ($dummyRun) ? "Dummy" : "Starting";
		$suiteNode	= $document->getElementsByTagName(self::TAGNAME_SUITE)->item(0);
		$suiteName	= htmlspecialchars($suiteNode->getAttribute(self::ATTRNAME_NAME));
		$suiteVersion	= $suiteNode->getAttribute("version");

		self::appendLog(ajaxUnitUI::htmlPageTop(), $dummyRun, 0, '', 0);
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
				if ($parameter->nodeType === XML_ELEMENT_NODE) $context[$parameter->nodeName] = $parameter->nodeValue;
			}
		}

		if (isset($context[self::TAGNAME_TESTPATH])) {
			$scriptDir	= dirname($_SERVER['SCRIPT_FILENAME']);
			$webRoot	= substr($scriptDir, 0 , strpos($scriptDir, $context[self::TAGNAME_TESTPATH]) - 1);
		} else {
			$context[self::TAGNAME_TESTPATH] = '';
			$webRoot	= $_SERVER['DOCUMENT_ROOT'];
		}

		$context[self::TAGNAME_WEBROOT] = $webRoot;

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

//		self::appendLog(ajaxUnitUI::htmlPageBottom(), $dummyRun, 0, '', 0);
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
			ajaxUnitUI::sendContent('No testing in progress', 'logmessage');
			exit;
		}

		// Wait for previous reponse to be parsed
		while ((bool) $context[self::TAGNAME_INPROGRESS]) {
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

			self::appendLog("Response count is $responseCount (expecting $expected).", $dummyRun);

			// Compare the results to the expected results
			$resultsNode = $test->getElementsByTagName(self::TAGNAME_RESULTS)->item(0);

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
?>