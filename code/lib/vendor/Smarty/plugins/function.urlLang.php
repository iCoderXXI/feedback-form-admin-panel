<?php

/*

 * Smarty plugin

 * -------------------------------------------------------------

 * File:     function.urlLang.php

 * Type:     function

 * Name:     ulrLang

 * Purpose:  modify current url to set different language

 * -------------------------------------------------------------

 */

function smarty_function_urlLang($params, $template)

{

 return "/".$params['lang'];

}

?>