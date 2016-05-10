<?php
function sanitize_input($arg, $type) {
	$cleanArg;
	switch($type) {
		case 'name':
			$cleanArg = filter_var($arg, FILTER_SANITIZE_STRING);
		break;
		case 'email':
			$cleanArg = filter_var($arg, FILTER_SANITIZE_EMAIL);
		break;
		case 'message':
			$cleanArg = filter_var($arg, FILTER_SANITIZE_STRING);
		break;
	}
	return $cleanArg;
}