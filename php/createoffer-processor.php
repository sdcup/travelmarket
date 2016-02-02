<?php
	$retString = "";
	foreach($_POST as $key => $value) {
		$retString .= "\n$key : $value";
	}
	print "got parameters: \n".$retString;
?>