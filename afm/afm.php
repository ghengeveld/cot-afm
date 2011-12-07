<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

/**
 * Advanced File Manager
 *
 * @package afm
 * @version 0.1
 * @author Gert Hengeveld
 * @copyright Copyright (c) Cotonti Team
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

// Configuration
$cfg['afm_dir'] = 'datas/afm';

// Environment setup
$env['location'] = 'afm';

// Additional API requirements
require_once cot_incfile('simpleorm');
require_once cot_incfile('uploads');
require_once './datas/extensions.php';

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('afm', 'any');

$incfile = cot_incfile('afm', 'module', "$m");
$m && file_exists($incfile) && include $incfile;

?>