<?php /* ЙЦУКЕН */

define('HTTP_HEADER','Content-type: application/json; charset=utf-8');

function viewRender($c=array()) {
	global $R;

	if (defined('HTTP_HEADER')) {
		header(HTTP_HEADER);
		if (!isset($R['-NOGZ'])) {
			header('Content-Encoding: gzip');
		}
		if (!isset($R['-NOFILE'])) {
			header('Content-Disposition: attachment; filename="'.MODULE.'-'.CTRL.'.json"');
		}
	}

	if (isset($c['data'])) {
		$cc = prettyJSON($c['data']);
		if (!isset($R['-NOGZ'])) {
			$gzipped = gzencode($cc,6);
			header('Content-Length: '.strlen($gzipped));
			print $gzipped;
		} else {
			print $cc;
		}

	} else {
		$errorMsg = 'Data missing in module '.MODULE.'/'.CTRL.'.';
		print "{'error':true,'errorMsg':'".$errorMsg."'}";
	}
}
