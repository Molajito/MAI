<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
define('MOLAJO', 'Long Live Molajo!');

ini_set('magic_quotes_runtime', 0);
ini_set('zend.ze1_compatibility_mode', 0);

define('BASE_FOLDER', __DIR__);

/** Initialize Optional Parameters */
$override_url_request = null;
$override_catalog_id = null;
$override_sequenceXML = null;
$override_finalXML = null;

$class = 'Molajo\\Application';

/** Autoload, Namespaces and Overrides */
if (file_exists(BASE_FOLDER . '/OverrideAutoload.php')) {
	require_once BASE_FOLDER . '/OverrideAutoload.php';
} else {
	require_once BASE_FOLDER . '/Autoload.php';
}

/** Execute the application */
$app = new $class ();
$app->process(
	$override_url_request, $override_catalog_id,
	$override_sequenceXML, $override_finalXML
);
