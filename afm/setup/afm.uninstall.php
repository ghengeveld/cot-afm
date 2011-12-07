<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('simpleorm');

function rrmdir($path)
{
	return is_file($path) ?
		@unlink($path) :
		array_map('rrmdir', glob($path.'/*')) == @rmdir($path);
}

rrmdir('./datas/afm');

File::dropTable();
Folder::dropTable();

?>