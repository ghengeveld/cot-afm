<?php

defined('COT_CODE') or die('Wrong URL');

$incfile = cot_incfile('afm', 'module', "$m.$a");
$a && file_exists($incfile) && include $incfile;

?>