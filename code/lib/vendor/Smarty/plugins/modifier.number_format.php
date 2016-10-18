<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty number_format modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     number_format<br>
 * Purpose:  format number, show &nbsp; if empty<br>
 * Input: the same with number_format php function;
 */
 
function smarty_modifier_number_format($number, $decimals=2)
{
    if ($number > 0) {
        $ret = number_format($number, $decimals, ',', "");
    } else {
        $ret = '&nbsp;';
    } 
    return $ret;
} 

?>
