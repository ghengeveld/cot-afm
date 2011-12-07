<?php

defined('COT_CODE') or die('Wrong URL');

$folderid = (int)cot_import('folderid', 'G', 'INT');
$ownerid = $usr['id'];

if (COT_AJAX)
{
	$files = File::find(array(
		"folderid = $folderid",
		"ownerid = $ownerid"
	));
	$rows = array();
	if ($files)
	{
		foreach ($files as $file)
		{
			$rows[] = $file->data();
		}
	}
	header('Content-type: application/json');
	echo json_encode(array(
		'rowcount' => count($files),
		'rows' => $rows
	));
	exit;
}

list($page, $offset, $urlnum) = cot_import_pagenav('page', $cfg['afm']['filesperpage']);

$count = File::count(array(
	"folderid = $folderid",
	"ownerid = $ownerid"
));

$files = File::find(array(
	"folderid = $folderid",
	"ownerid = $ownerid"
), $cfg['afm']['filesperpage'], $offset);

$pagenav = cot_pagenav('afm', 'm=folder&a=list&id=$id', $page, $count, $cfg['afm']['filesperpage'], 'page');

require_once $cfg['system_dir'] . '/header.php';

$t = new XTemplate(cot_tplfile('afm.folder.list'));
cot_display_messages($t);

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
	'ACTION' => cot_url('afm', 'm=file&a=upload'),
	'' => ''
), NULL, 'FORM_UPLOAD_');
$t->parse('MAIN.FORM_UPLOAD');
$t->parse('MAIN.FORM_CREATEFOLDER');

$t->parse('MAIN');
$t->out('MAIN');

require_once $cfg['system_dir'] . '/footer.php';

?>