<?php
//	---------------------------------------------------------------------------
//									ajaxUnit
//	---------------------------------------------------------------------------
/**
 * @package		ajaxUnit
 * @author		Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license		http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link		http://www.dominicsayers.com
 * @version		0.1 - First attempt
 */
class ajaxUnit {
	/*.public.*/	const	TESTS_FOLDER	= 'tests',
							TESTS_EXTENSION	= 'xml';

//	---------------------------------------------------------------------------
//	Private methods
//	---------------------------------------------------------------------------
	private static /*.boolean.*/ function doCookies(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		foreach ($nodeList as $node) {
			if ($node->nodeType === XML_ELEMENT_NODE) {
				$action	= $node->nodeName;
				$name	= $node->getAttribute("name");
				switch ($action) {
				case 'delete':
					$value	= '';
					$days	= -1;
					break;
				case 'set':
					$value	= $node->getAttribute("value");
					$days	= $node->getAttribute("days");
					break;
				}

				if ($dummyRun) {
					$actionText = ($action === 'set') ? ", value = <em>$value</em>, expires in $days days." : '';
					echo "<p style=\"margin-left:4em\">$action cookie <strong>$name</strong>$actionText</p>\n";
				} else {
					setcookie($name, $value, time() + 60 * 60 * 24 * $days);
				}
			}
		}
		return true;
	}

	private static /*.boolean.*/ function doFormFill(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		foreach ($nodeList as $node) {
			if ($node->nodeType === XML_ELEMENT_NODE) {
				$type	= $node->nodeName;
				$value	= $node->nodeValue;
				$id		= $node->getAttribute("id");				

				if ($dummyRun) {
					echo "<p style=\"margin-left:4em\">Setting $type control <strong>$id</strong> to <em>$value</em></p>\n";
				} else {
// to do
				}
			}
		}
		return true;
	}

	private static /*.boolean.*/ function doHeaders(/*.DOMNodeList.*/ $nodeList, $dummyRun = false) {
		foreach ($nodeList as $node) {
			if ($node->nodeType === XML_ELEMENT_NODE) {
				$header	= $node->nodeName;
				$value	= $node->nodeValue;				

				if ($dummyRun) {
					echo "<p style=\"margin-left:4em\">Setting <strong>$header</strong> to <em>$value</em></p>\n";
				} else {
					header("$header: $value");
				}
			}
		}
		return true;
	}

	private static /*.boolean.*/ function doResults(/*.DOMNodeList.*/ $nodeList, $results = false) {
		//	$results is overloaded as follows:
		$dummyRun = (is_bool($results)) ? $results : false;

		if ($dummyRun) {
			echo "<p style=\"margin-left:4em\">Assuming success</p>\n";
			return true;
		} else {
			//	Each <result> element is a potential match for $results
			//	If none of them match then it's a FAIL
			$success = false;

			foreach ($nodeList as $node) {
				if ($node->nodeType === XML_ELEMENT_NODE) {
					$success = ($results === $node->nodeValue) ? true : false;
					if ($success) break;	
				}
			}

			return $success;
		}
	}

	private static /*.boolean.*/ function initiateTest(/*.DOMNode.*/ $test, $dummyRun = false) {
		if ($dummyRun) {
			$testName	= $test->getAttribute("name");
			echo "<p>Test <strong>$testName</strong></p>\n";
		}

		$childNodes = $test->childNodes;

		foreach ($childNodes as $node) {
			if ($node->nodeType === XML_ELEMENT_NODE) {
				$stepType	= $node->nodeName;
				$stepList	= $node->childNodes;

				if ($dummyRun) echo "<p style=\"margin-left:2em\">Step type: $stepType</p>\n";

				switch ($stepType) {
					case 'cookies':		$success = self::doCookies	($stepList, $dummyRun);	break;
					case 'formfill':	$success = self::doFormFill	($stepList, $dummyRun);	break;
					case 'headers':		$success = self::doHeaders	($stepList, $dummyRun);	break;
					case 'results':		$success = self::doResults	($stepList, $dummyRun);	break;
				}

				if (!$success) break;
			}
		}

		if ($dummyRun) {
			if (!$success) echo "<p><strong>Test failed!</strong></p>\n";
			echo "<hr />\n";
		}

		return $success;
	}

	private static /*.DOMDocument.*/ function getDOMDocument(/*.string.*/ $filename) {
		$document	= new DOMDocument();
		$document->load($filename);
		return $document;
	}

	private static /*.DOMDocument.*/ function getTestSuite(/*.string.*/ $suite) {
		return self::getDOMDocument(self::TESTS_FOLDER . "/$suite." . self::TESTS_EXTENSION);
	}

	private static /*.DOMNodeList.*/ function getTestList(/*.DOMDocument.*/ &$document) {
		return $document->getElementsByTagName("tests")->item(0)->childNodes;
	}

	private static /*.void.*/ function setTestContext(/*.string.*/ $attribute, /*.string.*/ $value) {
		$filename	= ajaxUnitAPI::PACKAGE . "_log.xml";

		if (!file_exists($filename)) {
			$handle	= fopen($filename, 'wb');
			$xml	= "<current />";
		} else {
			$handle	= fopen($filename, 'r+b');
			$xml	= fread($handle, 8192);

			rewind($handle);
			ftruncate($handle, 0);
		}

		$document	= new DOMDocument();

		$document->loadXML($xml);

		$current	= $document->getElementsByTagName("current");
		$current	= (empty($current->length)) ? $document->appendChild($document->createElement("current")) : $current->item(0);
		$thisUser	= $current->getElementsByTagName('IP' . $_SERVER['REMOTE_ADDR']);
		$thisUser	= (empty($thisUser->length)) ? $current->appendChild($document->createElement('IP' . $_SERVER['REMOTE_ADDR'])) : $thisUser->item(0);

		$thisUser->setAttribute($attribute, $value);

		$xml		= $document->saveXML();

		fwrite($handle, $xml);
		fclose($handle);
	}

	

//	---------------------------------------------------------------------------
//	Public methods
//	---------------------------------------------------------------------------
	public static /*.void.*/ function runTestSuite(/*.string.*/ $suite, $dummyRun = false) {
		$document		= self::getTestSuite($suite);
		$text			= ($dummyRun) ? "Dummy" : "Starting";
		$suiteNode 		= $document->getElementsByTagName("suite")->item(0);
		$suiteName		= $suiteNode->getAttribute("name");
		$suiteVersion	= $suiteNode->getAttribute("version");
		
		ajaxUnitUI::getPageTop();
		echo "<h3>$text run of test suite \"$suiteName\" version $suiteVersion</h3>\n<hr />\n";

		$testList = self::getTestList($document);

		if (empty($testList->length)) {
			echo "<p>There are no tests defined in this test suite</p>\n";
			ajaxUnitUI::getPageBottom();
			return;
		}

		self::setTestContext("suite", $suite);
		self::setTestContext("status", "running");
		$testIndex	= 0;
		$success	= true;

		foreach ($testList as $test) {
			if ($test->nodeType === XML_ELEMENT_NODE) {
				self::setTestContext("index", $testIndex++);
				$success = self::initiateTest($test, $dummyRun);
				if (!$success) break;
			}
		}

		$successText = ($success) ? "Success" : "FAIL";
		self::setTestContext("status", $successText);
		ajaxUnitUI::getPageBottom();
	}

	public static /*.void.*/ function parseTest() {
		//	Get some context for this test
		$document		= self::getDOMDocument(ajaxUnitAPI::PACKAGE . "_log.xml");
		$statusNode		= $document->getElementsByTagName('IP' . $_SERVER['REMOTE_ADDR'])->item(0);
		$suite			= $statusNode->getAttribute("suite");
		$testIndex		= $statusNode->getAttribute("index");
		$testList		= self::getTestList(self::getTestSuite($suite));
		$test			= $testList->item($testIndex);
		$resultsList	= $test->getElementsByTagName("results");
		$success		= self::doResults($resultsList, $_POST['responseText']);
		$successText 	= ($success) ? "Success" : "FAIL";

		self::setTestContext("status", $successText);
		
		$content = "<p>| $suite | $testIndex | $success |</p>";
		ajaxUnitUI::sendContent($content, 'parseTest');
	}

}
//	End of class ajaxUnit
?>