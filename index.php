<!DOCTYPE html>
<html>

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link rel="shortcut icon" href="ajaxunit.ico" />
	<title>ajaxUnit - run tests</title>
</head>

<body>
<?php
	require_once 'ajaxunit.php';
	ajaxUnit::setProject($_GET['project']);
//	ajaxUnit::setRoot('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'projects'.DIRECTORY_SEPARATOR.''.$_GET['project']);
	ajaxUnit::setRoot('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.''.$_GET['project']);
	ajaxUnit::getContainer(ajaxUnit::ACTION_LOGTIDY);
?>
</body>

</html>
