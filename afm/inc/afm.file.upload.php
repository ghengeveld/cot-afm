<?php

defined('COT_CODE') or die('Wrong URL');

if ($_FILES && $_FILES['file'] && is_array($_FILES['file']['name']))
{
	$titles = cot_import('title', 'P', 'ARR');
	$descriptions = cot_import('description', 'P', 'ARR');
	$folderid = (int)cot_import('folderid', 'P', 'INT');
	
	if (!$folderid || Folder::findByPk($folderid))
	{
		$newfiles = 0;
		foreach ($_FILES['file']['name'] as $i => $name)
		{
			$tmp_name = $_FILES['file']['tmp_name'][$i];
			$size = $_FILES['file']['size'][$i];
			$type = strtolower(substr($name, strrpos($name, '.')+1));

			if (is_uploaded_file($tmp_name) && $size > 0)
			{
				if ($cfg['afm']['extensioncheck'])
				{
					foreach ($cot_extensions as $k => $v)
					{
						$found = ($v[0] == $type);
						if ($found) break;
					}
					if (!$found)
					{
						cot_error('invalid_filetype');
					}
				}
				if (!cot_error_found())
				{
					$file = new File(array(
						'folderid' => $folderid,
						'ownerid' => $usr['id'],
						'name' => $name,
						'size' => $size,
						'type' => $type,
						'metadata' => array(
							'title' => cot_import($titles[$i], 'D', 'TXT'),
							'description' => cot_import($descriptions[$i], 'D', 'TXT'),
							'mimetype' => $_FILES['file']['type'][$i]
						)
					));
					if ($file->store($tmp_name) && $file->insert())
					{
						$newfiles++;
					}
					else
					{
						cot_message('upload_failed', 'warning');
					}
				}
			}
		}
		if ($newfiles)
		{
			cot_message('upload_success');
		}
	}
}

cot_redirect(cot_url('afm', "m=folder&a=list&id=$folderid", '', true));

?>