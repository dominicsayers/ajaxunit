<!DOCTYPE html>
<html>

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link rel="shortcut icon" href="ajaxunit.php?icon" />
	<link rel="stylesheet"    href="ajaxunit.php?css"  type="text/css" title="ajaxUnit" />
	<title>ajaxUnit - run tests</title>
</head>

<body>
<div id="ajaxunit">
<?php
	require_once 'ajaxunit.php';

	if (isset($_GET) && array_key_exists('project',$_GET)) {
		ajaxUnit::tidyLogFiles();
		ajaxUnit::setRoot('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.(string) $_GET['project']);
		ajaxUnit::getControlPanel();
	} else {
		echo 'Please identify the project to test (e.g. "?project=xxx")';
	}
?>
</div>
</body>

</html>
