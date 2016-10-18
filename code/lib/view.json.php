<?php /* ЙЦУКЕН */

	define('HTTP_HEADER','Content-type: application/json; charset=utf-8');

	function output($c=array()) {
		global $errorMsg;
		
		if (defined('HTTP_HEADER')) {
			header(HTTP_HEADER);
			if (!isset($_REQUEST['-NOGZ'])) {
				header('Content-Encoding: gzip');
			}
			if (!isset($_REQUEST['-NOATTACHMENT'])) {
				header('Content-Disposition: attachment; filename="'.MODULE.'-'.ACTION.'.json"');
			}
//			header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
//			header('Pragma: no-cache');
		}

		if (!$errorMsg) {
			if (isset($c['RET']['DATA']['2JSON'])) {
				$cc = json_encode($c['RET']['DATA']['2JSON'],JSON_UNESCAPED_UNICODE+JSON_PRETTY_PRINT+JSON_NUMERIC_CHECK);
				if (!isset($_REQUEST['-NOGZ'])) {
					$gzipoutput = gzencode($cc,6);
					header('Content-Length: '.strlen($gzipoutput));
					print $gzipoutput;
				} else {
					print $cc;
				}

			} else {
				setError('Data missing in module '.MODULE.'/'.ACTION.'.');
				print "{'error':true,'errorMsg':'".$errorMsg."'}";
			}
		} else {
			print "{'error':true,'errorMsg':'".$errorMsg."'}";
		}
	}
?>
