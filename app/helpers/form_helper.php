<?php
/**
PHP *	Year		: 2022 
Author	: Agus Prawoto Hadi
Year	: 2021
*/

function set_select($name, $value) 
{
	if (@$_REQUEST['name'] == $value) {
		return 'selected="selected"';
	}
	return '';
}