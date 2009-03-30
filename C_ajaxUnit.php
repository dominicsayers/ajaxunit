<?php
// ---------------------------------------------------------------------------
// 		ajaxUnit
// ---------------------------------------------------------------------------
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
		$document = new DOMDocument();
		$document->load(self::getSuiteFilename($suite));
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

		if (!is_file($filename)) {
			$handle		= fopen($filename, 'wb');
			$context	= /*.(array[string]string).*/ array();
		} else {
			$handle		= fopen($filename, 'r+b');
			$serial		= fread($handle, 8192);
			$context	= /*.(array[string]string).*/ unserialize($serial);

			rewind($handle);
			ftruncate($handle, 0);
		}

		if (is_array($newContext)) {
			$context = array_merge($context, $newContext);
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
			$handle		= fopen($filename, 'rb');
			$serial		= fread($handle, 8192);
			$context	= /*.(array[string]string).*/ unserialize($serial);
		}

		if ($key === '') {
			return $context;
		} else {
			return (isset($context[$key])) ? $context[$key] : '';
		}
	}

	private static /*.void.*/ function setInitialContext(/*.DOMElement.*/ &$test, /*.int.*/ $testIndex) {
		// Build a string contianing a list of expected responses (1234...n) according to <results> child nodes
		$resultsList	= $test->getElementsByTagName(self::TAGNAME_RESULT);	// Get the expected results
		$expected = $resultsList->length;
		$responseList = '';
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
		$marginLeft = ($textIndent === 0) ? '' : " style=\"margin-left:{$textIndent}em\"";

		if ($tag === 'p') {
			$openTag = "<p$marginLeft>"	; $closeTag = '</p>';
		} else {
			$openTag = ''			; $closeTag = '';
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
		$context	= self::getTestContext();
		$html		= '';

		foreach ($context as $key => $value) $html .= "$key: $value<br />\n";
		self::appendLog($html, $dummyRun);
		self::appendLog('', $dummyRun, 'hr');
	}

	private static /*.void.*/ function logResult($success, $dummyRun = false) {
		$successText = ($success) ? self::STATUS_SUCCESS : self::STATUS_FAIL;
		self::setTestContext(self::TAGNAME_STATUS, $successText);
		self::appendLog('</div>', $dummyRun, 0, '', 3);
		self::appendLog("Result: <strong>$successText</strong>", $dummyRun, 0, 'p', 3);
		self::appendLog('<hr />', $dummyRun, 0, '', 3);
	}

	private static /*.void.*/ function sendLogLink() {
		$URL	= dirname($_SERVER['SCRIPT_NAME']) . "/" . self::getLogFilename();
		$xml	= "<test><open url=\"$URL\" /></test>";
		ajaxUnitUI::sendContent($xml, self::ACTION_PARSE, 'text/xml');
	}

	private static /*.string.*/ function addPath($name, $file = false) {
		$parameters	= self::getTestContext(self::TAGNAME_PARAMETERS);
		$root		= (isset($parameters['root'])) ? $parameters['root'] : '';
		$delim		= (substr($name, 0, 1) === '/') ? '': '';

		if ($file) {
			$base	= (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? (string) str_replace("\\", '/' , __FILE__) : __FILE__;
			$base	= substr($base, 0, strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME']));
		} else {
			$base	= '';
		}

		return "$base$root$delim$name";
	}

// ---------------------------------------------------------------------------
// Actions
// ---------------------------------------------------------------------------
	private static /*.boolean.*/ function doClick(/*.DOMElement.*/ $element, $dummyRun = false) {
		$id = $element->getAttribute(self::TAGNAME_ID);
		self::appendLog("Click button <strong>$id</strong>", $dummyRun, 2);
		return true;
	}

	private static /*.boolean.*/ function doCookies(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("Update cookies", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$action	= $node->nodeName;
				$name	= $node->getAttribute(self::ATTRNAME_NAME);

				switch ($action) {
				case self::TAGNAME_DELETE:
					$value	= '';
					$days	= -1;
					break;
				case self::TAGNAME_SET:
					$value	= $node->getAttribute(self::ATTRNAME_VALUE);
					$days	= $node->getAttribute(self::ATTRNAME_DAYS);
					break;
				}

				$actionText = ($action === self::TAGNAME_SET) ? ", value = <em>$value</em>, expires in $days days." : '';
				self::appendLog("$action cookie <strong>$name</strong>$actionText", $dummyRun);

				if (!$dummyRun) setcookie($name, $value, time() + 60 * 60 * 24 * $days);
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
					$source		= self::addPath($node->getAttribute(self::ATTRNAME_SOURCE)	, true);
					$destination	= self::addPath($node->getAttribute(self::ATTRNAME_DESTINATION)	, true);

					self::appendLog("Copying <em>$source</em> to <em>$destination</em>", $dummyRun);
					if (!$dummyRun) copy($source, $destination);
					break;
				case self::TAGNAME_DELETE:
					$name		= self::addPath($node->getAttribute(self::ATTRNAME_NAME)	, true);

					self::appendLog("Deleting <em>$name</em>", $dummyRun);
					if (!$dummyRun) unlink($name);
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
				$value	= $node->nodeValue;
				$id	= $node->getAttribute(self::TAGNAME_ID);				

				self::appendLog("Setting $type control <strong>$id</strong> to <em>$value</em>", $dummyRun);
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doHeaders(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("Send HTTP headers", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$header	= $node->nodeName;
				$value	= $node->nodeValue;				

				if ($header === 'location') {
					$path = self::addPath($value);
					self::appendLog("Setting <strong>$header</strong> to <em>$path</em>", $dummyRun);

					if (!$dummyRun) {
						header("$header: $path");
						die;
					}
				} else {
					self::appendLog("Setting <strong>$header</strong> to <em>$value</em>", $dummyRun);
					if (!$dummyRun) header("$header: $path");
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
				$action	= $node->nodeName;
				$value	= $node->nodeValue;

				switch ($action) {
				case self::TAGNAME_ADD:
					$path = self::addPath($value, true);
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

	private static /*.boolean.*/ function doResults(/*.DOMNodeList.*/ $nodeList, /*.string.*/ &$results, $dummyRun = false) {
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
					$success = ($results === $node->nodeValue) ? true : false;
	
					if ($success) {
						self::appendLog("Match with test data set #$indexText", $dummyRun, 6);
						// Remove this results set from the list of responses we are expecting
						$indexPos	= strpos($responseList, (string) $resultIndex);
						$responseList	= substr($responseList, 0, $indexPos) . substr($responseList, $indexPos + 1);
						self::setTestContext(self::TAGNAME_RESPONSELIST, $responseList);
						break;
					}
				}
			}
		}

		$result = ($success) ? "Match found!" : "No match found";
		self::appendLog("$result", $dummyRun, 6);
		return $success;
	}

	private static /*.boolean.*/ function doSession(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("Server session handling", $dummyRun, 2);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$action	= $node->nodeName;

				switch ($action) {
				case self::TAGNAME_RESET:
					self::appendLog("Resetting session...", $dummyRun);

					if (!$dummyRun) {
						session_start();
						session_unset();
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

	private static /*.boolean.*/ function initiateTest(/*.DOMDocument.*/ &$document, /*.DOMElement.*/ &$test, $dummyRun = false) {
		$package	= self::PACKAGE;
		$testName	= $test->getAttribute(self::ATTRNAME_NAME);
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
					case self::TAGNAME_INCLUDEPATH:	$success = self::doIncludePath	($stepList,	$dummyRun);					break;
					case self::TAGNAME_HEADERS:	$success = self::doHeaders	($stepList,	$dummyRun);					break;
					case self::TAGNAME_SESSION:	$success = self::doSession	($stepList,	$dummyRun);					break;
					case self::TAGNAME_RESULTS:
						if ($dummyRun) {
							$results = '';
							self::doResults($stepList, $results, $dummyRun);
							$success = true;
							break;
						} else {
							// Remove bulky results node before we send to the browser
							$test->removeChild($step);
						}
				}

				if (!$success) break;
			}
		}

		if ($success) {
			if ($sendToBrowser) {
				// We've got the next test details so send them to the browser script
				self::appendLog("Sending instructions to browser", false, 2);
				ajaxUnitUI::sendContent($document->saveXML($test), self::ACTION_PARSE, 'text/xml');
			} else {
				self::logResult($success, $dummyRun);
			}
		}

		return $success;
	}

// ---------------------------------------------------------------------------
// Public methods
// ---------------------------------------------------------------------------
	public static /*.void.*/ function runTestSuite(/*.string.*/ $suite, $dummyRun = false) {
		$context = /*.(array[string]string).*/ array();
		$context[self::TAGNAME_UID]		= date('YmdHis');
		$context[self::TAGNAME_SUITE]		= $suite;
		$context[self::TAGNAME_STATUS]		= self::STATUS_INPROGRESS;
		self::setTestContext($context);

		$document	= self::getDOMDocument($suite);
		$text		= ($dummyRun) ? "Dummy" : "Starting";
		$suiteNode 	= $document->getElementsByTagName(self::TAGNAME_SUITE)->item(0);
		$suiteName	= htmlspecialchars($suiteNode->getAttribute(self::ATTRNAME_NAME));
		$suiteVersion	= $suiteNode->getAttribute("version");
		
		self::appendLog(ajaxUnitUI::htmlPageTop(), $dummyRun, 0, '', 0);
		self::appendLog("<h3>$text run of test suite \"$suiteName\" version $suiteVersion</h3>", $dummyRun, 0, '', 3);
		self::appendLog("<hr />", $dummyRun, 0, '', 3);

		// Get global parameters
		$nodeList	= $document->getElementsByTagName(self::TAGNAME_PARAMETERS);
		$node		= ($nodeList->length > 0) ? $nodeList->item(0) : new DOMElement(self::TAGNAME_PARAMETERS);

		if ($node->hasChildNodes()) {
			self::appendLog("<strong>Global test parameters</strong>", $dummyRun, 0, 'p', 3);
			$context = /*.(array[string]string).*/ array();

			for ($i = 0; $i < $node->childNodes->length; $i++) {
				$parameter = $node->childNodes->item($i);

				if ($parameter->nodeType === XML_ELEMENT_NODE) {
					self::appendLog("\${$parameter->nodeName} = {$parameter->nodeValue}", $dummyRun, 0, 'p', 3);
					$context[self::TAGNAME_PARAMETERS][$parameter->nodeName] = $parameter->nodeValue;
				}
			}

			self::setTestContext($context);
			self::appendLog("<hr />", $dummyRun, 0, '', 3);
		}		

		// Run tests
		$testList = self::getTestList($document);
		$testNodes = $testList->length;

		if (empty($testNodes)) {
			self::appendLog("There are no tests defined in this test suite", $dummyRun, 0);
			self::appendLog(ajaxUnitUI::htmlPageBottom(), $dummyRun, 0, '', 0);
			return;
		}

		$success = true;

		for ($i = 0; $i < $testNodes; $i++) {
			$test = $testList->item($i);

			if ($test->nodeType === XML_ELEMENT_NODE) {
				self::setInitialContext($test, $i);
				$success = self::initiateTest($document, $test, $dummyRun);
				if (!$success) break;
			}
		}

		self::appendLog(ajaxUnitUI::htmlPageBottom(), $dummyRun, 0, '', 0);
	}

	public static /*.void.*/ function parseTest() {
		do {
			// Get some context for this test
			$context = self::getTestContext();
		
			// Wait for previous reponse to be parsed
			if ((bool) $context[self::TAGNAME_INPROGRESS]) {
				self::appendLog("(Another response is being parsed. Waiting for 1 second)", false, 2);
				sleep(1);
			} else {
				break;
			}
		} while (true);

		self::setTestContext(self::TAGNAME_INPROGRESS, (string) true);
		self::appendLog("Test results being parsed at request of browser", false, 2);

		// Get some context for this test
		$context	= self::getTestContext();
		$suite		= $context[self::TAGNAME_SUITE];
		$testIndex	= (int) $context[self::TAGNAME_INDEX];
		$document	= self::getDOMDocument($suite);		// Get the test suite information
		$testList	= self::getTestList($document);		// Get list of tests
		$test		= $testList->item($testIndex);		// Get this particular test
		$contextText	= ($test->hasAttribute(self::ATTRNAME_NAME)) ? "Test name is <strong>" . $test->getAttribute(self::ATTRNAME_NAME) : "Test index is <strong>$testIndex";

		self::appendLog("$contextText</strong>");

		// Check response count is (a) valid and (b) all we are expecting on this page
		if (!isset($context[self::TAGNAME_RESPONSECOUNT])) {
			self::appendLog("<strong>No response count set!</strong>");			
			die; // Invalid response count
		}

		$responseCount = (int) $context[self::TAGNAME_RESPONSECOUNT];

		if (++$responseCount < 1) {
			self::appendLog("<strong>Response count hasn't been set correctly! ($responseCount)</strong>");			
			die; // Invalid response count
		} else {
			self::setTestContext(self::TAGNAME_RESPONSECOUNT, (string) $responseCount);
		}

		$expected = self::getTestContext(self::TAGNAME_EXPECTINGCOUNT);
		self::appendLog("Response count is $responseCount (expecting $expected).");			

		// Compare the results to the expected results
		$resultsNode = $test->getElementsByTagName(self::TAGNAME_RESULTS)->item(0);

		if ($resultsNode->hasAttribute(self::ATTRNAME_UPDATE)) {
			// Use these as model results
			self::appendLog("Using these as model results");			
			$updateStatus = $resultsNode->getAttribute(self::ATTRNAME_UPDATE);

			if ($updateStatus !== self::STATUS_INPROGRESS) {
				self::appendLog("Clearing existing model results", false, 6);			
				// Clear away the children & set the status
				while ($resultsNode->hasChildNodes()) $resultsNode->removeChild($resultsNode->lastChild);
				$resultsNode->setAttribute(self::ATTRNAME_UPDATE, self::STATUS_INPROGRESS);
			}

			self::appendLog("Adding this result", false, 6);			
			$resultsNode->appendChild(new DOMElement(self::TAGNAME_RESULT, $_POST['responseText']));	// Add model result

			if ($responseCount === $expected) {
				self::appendLog("No more results expected", false, 6);			
				$resultsNode->removeAttribute(self::ATTRNAME_UPDATE);
			}

			// Write out the test suite
			self::appendLog("Updating test suite", false, 6);			
			self::updateTestSuite($suite, $document);
		} else {
			// Compare these results against the model results
			$success = self::doResults($resultsNode->childNodes, $_POST['responseText']);	// Is it the same?
		}

		if ($responseCount < $expected) {
			self::appendLog("Waiting for further responses from browser", false, 2);			
			self::setTestContext(self::TAGNAME_INPROGRESS, (string) false);
			die;
		}

		self::logResult($success);

		if (!$success) {
			self::appendLog(ajaxUnitUI::htmlPageBottom(), false, 0, '', 0);
			self::sendLogLink();
			return;
		}

		// Get the next test and send it to the client for form filling etc.
		do {
			$success = (++$testIndex < $testList->length);
			if (!$success) break;
			$test = $testList->item($testIndex);
			if ($test->nodeType === XML_ELEMENT_NODE) break;
		} while (true);

		if ($success) {
			self::setInitialContext($test, $testIndex);
			$success = self::initiateTest($document, $test);
		} else {
			// No more tests
			self::appendLog("No more tests found", false, 'p', 0, 2);			
			$content = 'Finished';
			ajaxUnitUI::sendContent($content, self::ACTION_PARSE);
			self::setTestContext(self::TAGNAME_STATUS, self::STATUS_SUCCESS);
			self::sendLogLink();
		}
	}

}
// End of class ajaxUnit
?>