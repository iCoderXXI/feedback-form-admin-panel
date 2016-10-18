<?php /* ЙЦУКЕН */

//	define('HTTP_HEADER','Content-type: text/javascript; charset=utf-8');

	function output($appData) {
		global $errorMsg;
		
		if (empty($errorMsg)) {
			if (!isset($appData['RET']['DATA']['IMG_NAME']) || !isset($appData['RET']['DATA']['IMG'])) {
				throw new Exception('IMG_NAME or IMG for out.img.php '.
									'missing in `'.MODULE.'`/`'.ACTION.'`.');
			}
			$type = imgTypeByFileExt($appData['RET']['DATA']['IMG_NAME']);
			if (!$type) {
				throw new Exception('Wrong image type ['.$appData['RET']['DATA']['IMG_NAME'].'] '.
									'in `'.MODULE.'`/`'.ACTION.'`.');
			} else {
				if (isset($appData['RET']['DATA']['HEADER'])) {
					foreach($appData['RET']['DATA']['HEADER'] as $hdr) header($hdr);
				} else {
					header('Content-type: image/'.$type);
				}
				echo $appData['RET']['DATA']['IMG'];
			}
		} else {
			throw new Exception($errorMsg);
		}
	}
?>