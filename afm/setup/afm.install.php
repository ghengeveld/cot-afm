<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('simpleorm');

@mkdir('./datas/afm', $cfg['dir_perms']);

Folder::createTable();
File::createTable();

?>