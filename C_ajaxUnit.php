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

class ajaxUnit {
	/*.public.*/	const	TESTS_FOLDER		= 'tests',
				TESTS_EXTENSION		= 'xml',
				LOG_FOLDER		= 'logs',
				LOG_EXTENSION		= 'html',
				CONTEXT_FOLDER		= 'logs',
				CONTEXT_EXTENSION	= 'txt',

				TAGNAME_CLICK		= 'click',
				TAGNAME_COOKIES		= 'cookies',
				TAGNAME_DELETE		= 'delete',
				TAGNAME_EXPECTINGCOUNT	= 'expectingCount',
				TAGNAME_FORMFILL	= 'formfill',
				TAGNAME_HEADERS		= 'headers',
				TAGNAME_ID		= 'id',
				TAGNAME_INDEX		= 'index',
				TAGNAME_INPROGRESS	= 'inProgress',
				TAGNAME_RESPONSECOUNT	= 'responseCount',
				TAGNAME_RESPONSELIST	= 'responseList',
				TAGNAME_RESULT		= 'result',
				TAGNAME_RESULTS		= 'results',
				TAGNAME_SET		= 'set',
				TAGNAME_STATUS		= 'status',
				TAGNAME_SUITE		= 'suite',
				TAGNAME_TESTS		= 'tests',
				TAGNAME_UID		= 'uid',

				ATTRNAME_DAYS		= 'days',
				ATTRNAME_NAME		= 'name',
				ATTRNAME_UPDATE		= 'update',
				ATTRNAME_VALUE		= 'value',

				STATUS_INPROGRESS	= 'in progress',
				STATUS_FAIL		= 'FAIL!',
				STATUS_SUCCESS		= 'success';

// ---------------------------------------------------------------------------
// Private methods
// ---------------------------------------------------------------------------
	private static /*.string.*/ function getSuiteFilename(/*.string.*/ $suite) {
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
		return $document->getElementsByTagName(self::TAGNAME_TESTS)->item(0)->childNodes;
	}

	private static /*.string.*/ function getContextFile() {
		return self::CONTEXT_FOLDER . "/" . ajaxUnitAPI::PACKAGE . "_" . $_SERVER['REMOTE_ADDR'] . "." . self::CONTEXT_EXTENSION;
	}

	private static /*.void.*/ function setTestContext(/*.mixed.*/ $newContext, $value = '') {
		$filename = self::getContextFile();

		if (!file_exists($filename)) {
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

	private static /*.array[string]string.*/ function getTestContext($key = '') {
		$filename = self::getContextFile();

		if (!file_exists($filename)) {
			$context	= /*.(array[string]string).*/ array();
		} else {
			$handle		= fopen($filename, 'rb');
			$serial		= fread($handle, 8192);
			$context	= /*.(array[string]string).*/ unserialize($serial);
		}

		return ($key === '') ? $context : $context[$key];
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
		return self::LOG_FOLDER . "/" . ajaxUnitAPI::PACKAGE . "_$uid." . self::LOG_EXTENSION;
	}

	private static /*.void.*/ function appendLog(/*.string.*/ $html, $dummyRun = false) {
		if ($dummyRun) {
			echo $html;
		} else {
			$handle		= fopen(self::getLogFilename(), 'ab');

			fwrite($handle, $html);
			fclose($handle);
		}
	}

	private static /*.void.*/ function logTestContext($dummyRun = false) {
		$context	= self::getTestContext();
		$html		= '<p>';

		foreach ($context as $key => $value) $html .= "$key: $value<br />\n";

		$html .= "</p><hr id=\"logTestContext\" />\n";
		self::appendLog($html, $dummyRun);
	}

	private static /*.void.*/ function logResult($success) {
		$successText = ($success) ? self::STATUS_SUCCESS : self::STATUS_FAIL;
		self::setTestContext(self::TAGNAME_STATUS, $successText);
		self::appendLog("</div>\n<p>Result: <strong>$successText</strong></p>\n", $dummyRun);
	}

	private static /*.void.*/ function sendLogLink() {
		$URL	= dirname($_SERVER['SCRIPT_NAME']) . "/" . self::getLogFilename();
		$xml	= "<test><open url=\"$URL\" /></test>";
		ajaxUnitUI::sendContent($xml, ajaxUnitAPI::ACTION_PARSE, 'text/xml');
	}

// ---------------------------------------------------------------------------
// Actions
// ---------------------------------------------------------------------------
	private static /*.boolean.*/ function doClick(/*.DOMElement.*/ $element, $dummyRun = false) {
		$id = $element->getAttribute(self::TAGNAME_ID);
		self::appendLog("<p style=\"margin-left:2em\">Click button <strong>$id</strong></p>\n", $dummyRun);
		return true;
	}

	private static /*.boolean.*/ function doCookies(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("<p style=\"margin-left:2em\">Update cookies</p>\n", $dummyRun);

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
				self::appendLog("<p style=\"margin-left:4em\">$action cookie <strong>$name</strong>$actionText</p>\n", $dummyRun);

				if (!$dummyRun) setcookie($name, $value, time() + 60 * 60 * 24 * $days);
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doFormFill(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("<p style=\"margin-left:2em\">Update form fields</p>\n", $dummyRun);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$type	= $node->nodeName;
				$value	= $node->nodeValue;
				$id	= $node->getAttribute(self::TAGNAME_ID);				

				self::appendLog("<p style=\"margin-left:4em\">Setting $type control <strong>$id</strong> to <em>$value</em></p>\n", $dummyRun);
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doHeaders(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		self::appendLog("<p style=\"margin-left:2em\">Send HTTP headers</p>\n", $dummyRun);

		for ($i = 0; $i < $nodeList->length; $i++) {
			$node = $nodeList->item($i);

			if ($node->nodeType === XML_ELEMENT_NODE) {
				$header	= $node->nodeName;
				$value	= $node->nodeValue;				

				self::appendLog("<p style=\"margin-left:4em\">Setting <strong>$header</strong> to <em>$value</em></p>\n", $dummyRun);

				if (!$dummyRun) {
					header("$header: $value");
					if ($header === 'location') die;
				}
			}
		}

		return true;
	}

	private static /*.boolean.*/ function doResults(/*.DOMNodeList.*/ $nodeList, /*.string.*/ &$results, $dummyRun = false) {
		self::appendLog("<p style=\"margin-left:4em\">Compare actual results with expected</p>\n", $dummyRun);

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
					self::appendLog("<p style=\"margin-left:6em\">Test data set #$indexText has already been matched with a previous result</p>\n", $dummyRun);
				} else {
					self::appendLog("<p style=\"margin-left:6em\">Comparing result with test data set #$indexText...</p>\n", $dummyRun);
					$success = ($results === $node->nodeValue) ? true : false;
	
					if ($success) {
						self::appendLog("<p style=\"margin-left:6em\">Match with test data set #$indexText</p>\n", $dummyRun);
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
		self::appendLog("<p style=\"margin-left:6em\">$result</p>\n", $dummyRun);
		return $success;
	}

	private static /*.boolean.*/ function initiateTest(/*.DOMDocument.*/ &$document, /*.DOMElement.*/ &$test, $dummyRun = false) {
		$package	= ajaxUnitAPI::PACKAGE;
		$testName	= $test->getAttribute(self::ATTRNAME_NAME);
		$testId		= htmlspecialchars($testName);
		$html		= <<<HTML
<p><span class="$package-testlog" onclick="{$package}_toggle_log(this, '$package-$testId')">+</span> Test <strong>$testName</strong></p>
<div class="$package-testlog" id="$package-$testId">

HTML;

		self::appendLog($html, $dummyRun);
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
					case self::TAGNAME_FORMFILL:	$success = self::doFormFill	($stepList,	$dummyRun);	$sendToBrowser = !$dummyRun;	break;
					case self::TAGNAME_HEADERS:	$success = self::doHeaders	($stepList,	$dummyRun);					break;
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
				self::appendLog("<p style=\"margin-left:2em\">Sending instructions to browser</p>\n");
				ajaxUnitUI::sendContent($document->saveXML($test), ajaxUnitAPI::ACTION_PARSE, 'text/xml');
			} else {
				self::appendLog("</div>\n<hr id=\"initiateTest\" />\n", $dummyRun);
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
		
		self::appendLog(ajaxUnitUI::htmlPageTop(), $dummyRun);
		self::appendLog("<h3>$text run of test suite \"$suiteName\" version $suiteVersion</h3>\n<hr id=\"runTestSuite\" />\n", $dummyRun);

		$testList = self::getTestList($document);
		$testNodes = $testList->length;

		if (empty($testNodes)) {
			self::appendLog("<p>There are no tests defined in this test suite</p>\n", $dummyRun);
			self::appendLog(ajaxUnitUI::htmlPageBottom(), $dummyRun);
			return;
		}

		$testIndex	= 0;
		$success	= true;

		for ($testIndex = 0; $testIndex < $testNodes; $testIndex++) {
			$test = $testList->item($testIndex);

			if ($test->nodeType === XML_ELEMENT_NODE) {
				self::setInitialContext($test, $testIndex);
				$success = self::initiateTest($document, $test, $dummyRun);
				if (!$success) break;
			}
		}

		self::logResult($success);
		self::appendLog(ajaxUnitUI::htmlPageBottom(), $dummyRun);
	}

	public static /*.void.*/ function parseTest() {
		do {
			// Get some context for this test
			$context = self::getTestContext();
		
			// Wait for previous reponse to be parsed
			if ((bool) $context[self::TAGNAME_INPROGRESS]) {
				self::appendLog("<p style=\"margin-left:2em\">(Another response is being parsed. Waiting for 1 second)</p>\n");
				sleep(1);
			} else {
				break;
			}
		} while (true);

		self::setTestContext(self::TAGNAME_INPROGRESS, (string) true);
		self::appendLog("<p style=\"margin-left:2em\">Test results being parsed at request of browser</p>\n");

		// Get some context for this test
		$context	= self::getTestContext();
		$suite		= $context[self::TAGNAME_SUITE];
		$testIndex	= (int) $context[self::TAGNAME_INDEX];
		$document	= self::getDOMDocument($suite);		// Get the test suite information
		$testList	= self::getTestList($document);		// Get list of tests
		$test		= $testList->item($testIndex);		// Get this particular test
		$contextText	= ($test->hasAttribute(self::ATTRNAME_NAME)) ? "Test name is <strong>" . $test->getAttribute(self::ATTRNAME_NAME) : "Test index is <strong>$testIndex";

		self::appendLog("<p style=\"margin-left:4em\">$contextText</strong></p>\n");

		// Check response count is (a) valid and (b) all we are expecting on this page
		if (!isset($context[self::TAGNAME_RESPONSECOUNT])) {
			self::appendLog("<p style=\"margin-left:4em\"><strong>No response count set!</strong></p>\n");			
			die; // Invalid response count
		}

		$responseCount = (int) $context[self::TAGNAME_RESPONSECOUNT];

		if (++$responseCount < 1) {
			self::appendLog("<p style=\"margin-left:4em\"><strong>Response count hasn't been set correctly! ($responseCount)</strong></p>\n");			
			die; // Invalid response count
		} else {
			self::setTestContext(self::TAGNAME_RESPONSECOUNT, (string) $responseCount);
		}

		$expected = self::getTestContext(self::TAGNAME_EXPECTINGCOUNT);
		self::appendLog("<p style=\"margin-left:4em\">Response count is $responseCount (expecting $expected).</p>\n");			

		// Compare the results to the expected results
		$resultsNode = $test->getElementsByTagName(self::TAGNAME_RESULTS)->item(0);

		if ($resultsNode->hasAttribute(self::ATTRNAME_UPDATE)) {
			// Use these as model results
			self::appendLog("<p style=\"margin-left:4em\">Using these as model results</p>\n");			
			$updateStatus = $resultsNode->getAttribute(self::ATTRNAME_UPDATE);

			if ($updateStatus !== self::STATUS_INPROGRESS) {
				self::appendLog("<p style=\"margin-left:6em\">Clearing existing model results</p>\n");			
				// Clear away the children & set the status
				while ($resultsNode->hasChildNodes()) $resultsNode->removeChild($resultsNode->lastChild);
				$resultsNode->setAttribute(self::ATTRNAME_UPDATE, self::STATUS_INPROGRESS);
			}

			self::appendLog("<p style=\"margin-left:6em\">Adding this result</p>\n");			
			$resultsNode->appendChild(new DOMElement(self::TAGNAME_RESULT, $_POST['responseText']));	// Add model result

			if ($responseCount === $expected) {
				self::appendLog("<p style=\"margin-left:6em\">No more results expected</p>\n");			
				$resultsNode->removeAttribute(self::ATTRNAME_UPDATE);
			}

			// Write out the test suite
			self::appendLog("<p style=\"margin-left:6em\">Updating test suite</p>\n");			
			self::updateTestSuite($suite, $document);
		} else {
			// Compare these results against the model results
			$success = self::doResults($resultsNode->childNodes, $_POST['responseText']);	// Is it the same?
			self::logResult($success);

			if (!$success) {
				self::sendLogLink();
				return;
			}
		}

		if ($responseCount < $expected) {
			self::appendLog("<p style=\"margin-left:2em\">Waiting for further responses from browser</p>\n");			
			self::setTestContext(self::TAGNAME_INPROGRESS, (string) false);
			die;
		} else {
			self::appendLog("<p>Looking for next test</p>\n");			
		}

		// End of this test
		self::appendLog("<hr id=\"parseTest\" />\n");

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
			self::appendLog("<p>No more tests found</p>\n");			
			$content = 'Finished';
			ajaxUnitUI::sendContent($content, ajaxUnitAPI::ACTION_PARSE);
			self::setTestContext(self::TAGNAME_STATUS, self::STATUS_SUCCESS);
		}
	}

}
// End of class ajaxUnit
?>