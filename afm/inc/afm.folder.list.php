<?php

defined('COT_CODE') or die('Wrong URL');

$id = (int)cot_import('id', 'G', 'INT');
$sort = cot_import('s', 'G', 'ALP');
$way = cot_import('w', 'G', 'ALP');
$way = ($way == 'desc') ? 'DESC' : 'ASC';
$ownerid = $usr['id'];

if (COT_AJAX)
{
	$folders = ($id > 0) ?
		Folder::find("parentid = $id", 0, 0, $sort, $way) : 
		Folder::find(array("parentid = 0", "ownerid = $ownerid"), 0, 0, $sort, $way);
	$rows = array();
	if ($folders)
	{
		foreach ($folders as $folder)
		{
			$rows[] = $folder->data();
		}
	}
	header('Content-type: application/json');
	echo json_encode(array(
		'rowcount' => count($folders),
		'rows' => $rows
	));
	exit;
}

list($page, $offset, $urlnum) = cot_import_pagenav('p', $cfg['afm']['filesperpage']);

if ($id > 0)
{
	$folder = Folder::findByPk($id);
	$folder || cot_redirect(cot_url('afm', 'm=folder&a=list', '', true));
	$ownerid = $folder->data('ownerid');
}

$subfolders = Folder::find(array(
	"parentid = $id", 
	"ownerid = $ownerid"
), 0, 0, 'name');
$numfiles = File::count(array(
	"folderid = $id",
	"ownerid = $ownerid"
));
$files = File::find(array(
	"folderid = $id",
	"ownerid = $ownerid"
), $cfg['afm']['filesperpage'], $offset, $sort, $way);

$pagenav = cot_pagenav('afm', 'm=folder&a=list&id=$id', $page, $numfiles, $cfg['afm']['filesperpage'], 'p');

require_once $cfg['system_dir'] . '/header.php';

$t = new XTemplate(cot_tplfile('afm.folder.list'));
cot_display_messages($t);

if ($id > 0)
{
	foreach ($folder->data() as $key => $value)
	{
		if (is_array($value))
		{
			foreach ($value as $k => $v)
			{
				$t->assign(strtoupper($key).'_'.strtoupper($k), $v, 'FOLDER_');
			}
			$t->parse('MAIN.'.strtoupper($key));
		}
		else
		{
			$t->assign(strtoupper($key), $value, 'FOLDER_');
		}
	}
}

if ($subfolders)
{
    foreach ($subfolders as $subfolder)
    {
        foreach ($subfolder->data() as $key => $value)
        {
			if (is_array($value))
			{
				foreach ($value as $k => $v)
				{
					$t->assign(strtoupper($key).'_'.strtoupper($k), $v, 'SUBFOLDER_');
				}
				$t->parse('MAIN.SUBFOLDERS.ROW.'.strtoupper($key));
			}
			else
			{
				$t->assign(strtoupper($key), $value, 'SUBFOLDER_');
			}
        }
        $t->parse('MAIN.SUBFOLDERS.ROW');
    }
    $t->parse('MAIN.SUBFOLDERS');
}

if ($files)
{
	foreach ($files as $file)
    {
        foreach ($file->data() as $key => $value)
        {
			if (is_array($value))
			{
				foreach ($value as $k => $v)
				{
					$t->assign(array(
						'FIELD' => ($L[$key.'_'.$k]) ? $L[$key.'_'.$k] : ($L[$k]) ? $L[$k] : $k,
						'VALUE' => $v
					));
					$t->parse('MAIN.FILES.ROW.'.strtoupper($key));
					$t->assign(strtoupper($key).'_'.strtoupper($k), $v, 'FILE_');
				}
			}
			else
			{
				if ($key == 'size') $value /= 1024;
				$t->assign(strtoupper($key), $value, 'FILE_');
			}
        }
        $t->parse('MAIN.FILES.ROW');
    }
    $t->parse('MAIN.FILES');
}

$t->assign(array(
	'ACTION' => cot_url('files', 'm=file&a=upload'),
	'' => ''
), NULL, 'FORM_UPLOAD_');
$t->parse('MAIN.FORM_UPLOAD');
$t->parse('MAIN.FORM_CREATEFOLDER');

$t->parse('MAIN');
$t->out('MAIN');

require_once $cfg['system_dir'] . '/footer.php';

?>