<?php

/**
 * File
 */
class File extends SimpleORM
{
	protected $table_name = 'afm_files';
	protected $columns = array(
		'id' => array(
			'type' => 'int',
			'primary_key' => true,
			'auto_increment' => true,
			'locked' => true
		),
		'folderid' => array(
			'type' => int,
			'foreign_key' => 'folders:id',
			'default_value' => 0
		),
		'ownerid' => array(
			'type' => 'int',
			'foreign_key' => 'users:user_id',
			'locked' => true
		),
		'fullpath' => array(
			'type' => 'varchar',
			'length' => 255,
			'unique' => true,
			'locked' => true,
			'hidden' => true
		),
		'name' => array(
			'type' => 'varchar',
			'length' => 100
		),
		'size' => array(
			'type' => 'int',
			'locked' => true
		),
		'type' => array(
			'type' => 'varchar',
			'length' => 10,
			'locked' => true
		),
		'metadata' => array(
			'type' => 'object'
		),
		'created' => array(
			'type' => 'int',
			'on_insert' => 'NOW()',
			'locked' => true
		),
		'updated' => array(
			'type' => 'int',
			'on_insert' => 'NOW()',
			'on_update' => 'NOW()',
			'locked' => true
		)
	);

	protected function generate_path()
	{
		global $cfg;
		$path = $cfg['files_dir'].'/'.cot_unique();
		for ($i = 0; $i < 2; $i++)
		{
			$path = substr_replace($path, '/', strrpos($path, '/') + 2, 0);
			$dir = substr($path, 0, strrpos($path, '/'));
			if (!is_dir($dir))
			{
				mkdir($dir, $cfg['dir_perms']);
			}
		}
		return file_exists($path) ? $this->generate_path() : $path;
	}

	public function store($tmp_name)
	{
		global $cfg;
		$this->data['fullpath'] = $this->generate_path();
		$res = move_uploaded_file($tmp_name, $this->data['fullpath']);
		@chmod($this->data['fullpath'], $cfg['file_perms']);
		$this->data['size'] = filesize($this->data['fullpath']);
		return $res;
	}

	public function output($attachment = true)
	{
		$info = $this->data;
		$metadata = unserialize($info['metadata']);
		if (file_exists($info['fullpath']))
		{
			$mtime = filemtime($info['fullpath']);
			$size = filesize($info['fullpath']);
			
			set_time_limit(300);
			
			header("Content-Type: {$metadata['mimetype']}");
			header("Content-Length: $size");
			if ($attachment)
			{
				$filename = urlencode($info['name']);
				header("Content-Disposition: attachment; filename=\"$filename\"; modification-date=\"".date('r', $mtime).'";');
			}
			$chunksize = 1024 * 1024; // 1MB
			if ($size > $chunksize)
			{
				$buffer = '';
				$handle = fopen($info['fullpath'], 'rb');
				while (!feof($handle))
				{
					$buffer = fread($handle, $chunksize);
					echo $buffer;
					ob_flush();
					flush();
				}
				fclose($handle);
			}
			else
			{
				readfile($info['fullpath']);
			}
			exit;
		}
	}

	public function delete($condition, $params = array())
	{
		@unlink($this->data['fullpath']);
		return parent::delete($condition, $params);
	}
}

?>