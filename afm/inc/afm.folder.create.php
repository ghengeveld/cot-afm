<?php

defined('COT_CODE') or die('Wrong URL');

$parentid = (int)cot_import('parentid', 'P', 'INT');
$name = cot_import('name', 'P', 'TXT');
$description = cot_import('description', 'P', 'TXT');

if ($name)
{
	$folder = new Folder(array(
		'parentid' => $parentid,
        'ownerid' => $usr['id'],
        'name' => $name,
        'metadata' => array(
			'description' => $description
		)
    ));
    if ($folder->insert())
    {
		$folderid = $folder->data('id');
		cot_message('createfolder_success');
        cot_redirect(cot_url('afm', "m=folder&a=list&id=$folderid", '', true));
    }
}

cot_redirect(cot_url('afm', "m=folder&a=list&id=$parentid", '', true));

?>