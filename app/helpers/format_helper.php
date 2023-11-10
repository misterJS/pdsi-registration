<?php
/**
Helper format
https://pdsionline.org
*/

function format_ribuan($value) {
	return number_format($value, 0, ',' , '.');
}
function format_number($value) 
{
	$value = preg_replace('/\D/', '', $value);
	return number_format($value, 0, ',', '.');
}