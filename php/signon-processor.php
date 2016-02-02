<?php
	$retString = "";
	// header('Content-Type: text/plain; charset=utf-8');
	foreach($_POST as $key => $value) {
		$retString .= "<br>$key : $value";
	}
	print "got parameters: \n".$retString;
?>