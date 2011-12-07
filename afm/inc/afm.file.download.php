<?php

defined('COT_CODE') or die('Wrong URL');

$id = cot_import('id', 'G', 'INT');
$view = cot_import('view', 'G', 'BOL');

$file = File::findByPk($id);
if ($file)
{
	$view && $isimg = in_array($file->data('type'), array('jpg', 'jpeg', 'bmp', 'gif', 'png'));
	$file->output(!$isimg);
}

cot_redirect(cot_url('afm', 'm=folder&a=list', '', true));

?>