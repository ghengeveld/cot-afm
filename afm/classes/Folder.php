<?php

/**
 * Folder
 */
class Folder extends SimpleORM
{
	protected $table_name = 'afm_folders';
	protected $columns = array(
		'id' => array(
			'type' => 'int',
			'primary_key' => true,
			'auto_increment' => true,
			'locked' => true
		),
		'parentid' => array(
			'type' => int,
			'foreign_key' => 'folders:id',
			'default_value' => 0
		),
		'ownerid' => array(
			'type' => 'int',
			'foreign_key' => 'users:user_id',
			'locked' => true
		),
		'name' => array(
			'type' => 'varchar',
			'length' => 255
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
}

?>