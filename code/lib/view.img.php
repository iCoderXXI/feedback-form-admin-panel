<?php /* ЙЦУКЕН */

function viewRender($appData) {

	if (!isset($appData['RET']['DATA']['IMG_NAME']) || !isset($appData['RET']['DATA']['IMG'])) {
		throw new Exception('IMG_NAME or IMG for view.img.php '.
							'missing in `'.MODULE.'`/`'.CTRL.'`.');
	}
	$type = imgTypeByFileExt($appData['RET']['DATA']['IMG_NAME']);
	if (!$type) {
		throw new Exception('Wrong image type ['.$appData['RET']['DATA']['IMG_NAME'].'] '.
							'in `'.MODULE.'`/`'.CTRL.'`.');
	} else {
		if (isset($appData['RET']['DATA']['HEADER'])) {
			foreach($appData['RET']['DATA']['HEADER'] as $hdr) header($hdr);
		} else {
			header('Content-type: image/'.$type);
		}
		echo $appData['RET']['DATA']['IMG'];
	}

}
