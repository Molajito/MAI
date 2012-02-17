<?php
/**
 * @package     Molajo
 * @subpackage  Load Joomla Framework
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Joomla Defines
 */
if (defined('JPATH_PLATFORM')) {
} else {
    define('JPATH_PLATFORM', PLATFORMS . '/jplatform/');
}

require_once JPATH_PLATFORM . '/platform.php';
require_once JPATH_PLATFORM . '/loader.php';
require_once MOLAJO_APPLICATIONS . '/helpers/load.php';

if (defined('_JEXEC')) {
} else {
    define('_JEXEC', 1);
}
if (defined('JPATH_BASE')) {
} else {
    define('JPATH_BASE', MOLAJO_BASE_FOLDER);
}
if (defined('JPATH_ROOT')) {
} else {
    define('JPATH_ROOT', MOLAJO_BASE_FOLDER);
}
if (defined('JPATH_CONFIGURATION')) {
} else {
    define('JPATH_CONFIGURATION', SITE_FOLDER_PATH);
}
if (defined('JOOMLA_LIBRARY')) {
} else {
    define('JOOMLA_LIBRARY', PLATFORMS . '/jplatform/joomla');
}
if (defined('JPATH_LIBRARIES')) {
} else {
    define('JPATH_LIBRARIES', JOOMLA_LIBRARY);
}
if (defined('JPATH_SITE')) {
} else {
    define('JPATH_SITE', MOLAJO_BASE_FOLDER);
}
if (defined('JPATH_ADMINISTRATOR')) {
} else {
    define('JPATH_ADMINISTRATOR', MOLAJO_BASE_FOLDER);
}
if (defined('JPATH_PLUGINS')) {
} else {
    define('JPATH_PLUGINS', MOLAJO_EXTENSIONS_PLUGINS);
}
if (defined('JPATH_CACHE')) {
} else {
    define('JPATH_CACHE', SITE_FOLDER_PATH . '/cache');
}
if (defined('JPATH_THEMES')) {
} else {
    define('JPATH_THEMES', MOLAJO_EXTENSIONS_THEMES);
}
if (defined('JPATH_COMPONENT')) {
} else {
    define('JPATH_COMPONENT', MOLAJO_EXTENSIONS_COMPONENTS);
}

/**
 * File Subsystem
 */
$load = new MolajoLoadHelper();

require_once JOOMLA_LIBRARY . '/registry/registry.php';
$load->requireClassFile(MOLAJO_APPLICATIONS . '/base/base.php', 'MolajoBase');
$load->requireClassFile(MOLAJO_APPLICATIONS_MVC . '/controllers/controller.php', 'MolajoController');

require_once PLATFORM_MOLAJO . '/exceptions/error.php';
require_once PLATFORM_MOLAJO . '/exceptions/exception.php';
require_once MOLAJO_APPLICATIONS . '/services/text.php';
if (class_exists('JText')) {
} else {
    class JText extends MolajoTextService
    {
    }
}
$load->requireClassFile(JOOMLA_LIBRARY . '/filesystem/path.php', 'JPath');
$load->requireClassFile(JOOMLA_LIBRARY . '/filesystem/file.php', 'JFile');
$load->requireClassFile(JOOMLA_LIBRARY . '/filesystem/folder.php', 'JFolder');

/**
 *  Base
 */
$load->requireClassFile(JOOMLA_LIBRARY . '/base/object.php', 'JObject');

/**
 *  Input
 */
$load->requireClassFile(JOOMLA_LIBRARY . '/input/input.php', 'JInput');
$load->requireClassFile(JOOMLA_LIBRARY . '/input/cli.php', 'JInputCli');
$load->requireClassFile(JOOMLA_LIBRARY . '/input/cookie.php', 'JInputCookie');
$load->requireClassFile(JOOMLA_LIBRARY . '/input/files.php', 'JInputFiles');

/**
 *  Client
 *
 */
$files = JFolder::files(JOOMLA_LIBRARY . '/client', '\.php$', false, false);
foreach ($files as $file) {
    if ($file == 'ftp.php') {
        /** babs cannot run this require statement - not sure why yet */
    } else if ($file == 'helper.php') {
        $load->requireClassFile(JOOMLA_LIBRARY . '/client/' . $file, 'JClientHelper');
    } else {
        $load->requireClassFile(JOOMLA_LIBRARY . '/client/' . $file, 'J' . ucfirst(substr($file, 0, strpos($file, '.'))));
    }
}

/**
 *  Database
 */
JLoader::register('JDatabaseMySQL', JOOMLA_LIBRARY . '/database/database/mysql.php');
JLoader::register('JDatabaseMySQLi', JOOMLA_LIBRARY . '/database/database/mysqli.php');
JLoader::register('JDatabaseSQLSrv', JOOMLA_LIBRARY . '/database/database/sqlsrv.php');
JLoader::register('JDatabaseSQLAzure', JOOMLA_LIBRARY . '/database/database/sqlazure.php');

JLoader::register('JDatabaseInterface', JOOMLA_LIBRARY . '/database/database.php');
JLoader::register('JDatabase', JOOMLA_LIBRARY . '/database/database.php');
JLoader::register('JDatabaseQueryElement', JOOMLA_LIBRARY . '/database/query.php');
JLoader::register('JDatabaseQuery', JOOMLA_LIBRARY . '/database/query.php');

/**
 *  Error - JError deprecated; Exception classes loaded in Molajo; Log moved
 */
$load->requireClassFile(JOOMLA_LIBRARY . '/error/profiler.php', 'JProfiler');

/**
 *  Filesystem (continued)
 */
$files = JFolder::files(JOOMLA_LIBRARY . '/filesystem', '\.php$', false, false);
foreach ($files as $file) {
    if ($file == 'helper.php') {
    } elseif ($file == 'path.php' || $file == 'file.php' || $file == 'folder.php') {
    } elseif ($file == 'stream.php') {
    } else {
        $load->requireClassFile(JOOMLA_LIBRARY . '/filesystem/' . $file, 'J' . ucfirst(substr($file, 0, strpos($file, '.'))));
    }
}
$files = JFolder::files(JOOMLA_LIBRARY . '/filesystem/archive', '\.php$', false, false);
foreach ($files as $file) {
    $load->requireClassFile(JOOMLA_LIBRARY . '/filesystem/archive/' . $file, 'JArchive' . ucfirst(substr($file, 0, strpos($file, '.'))));
}
$files = JFolder::files(JOOMLA_LIBRARY . '/filesystem/streams', '\.php$', false, false);
foreach ($files as $file) {
    $load->requireClassFile(JOOMLA_LIBRARY . '/filesystem/streams/' . $file, 'JStream' . ucfirst(substr($file, 0, strpos($file, '.'))));
}
$files = JFolder::files(JOOMLA_LIBRARY . '/filesystem/support', '\.php$', false, false);
foreach ($files as $file) {
    $load->requireClassFile(JOOMLA_LIBRARY . '/filesystem/support/' . $file, 'J' . ucfirst(substr($file, 0, strpos($file, '.'))));
}

/**
 *  Filter
 */
$load->requireClassFile(JOOMLA_LIBRARY . '/filter/filterinput.php', 'JFilterInput');
$load->requireClassFile(JOOMLA_LIBRARY . '/filter/filteroutput.php', 'JFilterOutput');

/**
 *  Log
 */
$files = JFolder::files(JOOMLA_LIBRARY . '/log', '\.php$', false, false);
foreach ($files as $file) {
    if ($file == 'logexception.php') {
    } else {
        $load->requireClassFile(JOOMLA_LIBRARY . '/log/' . $file, 'J' . ucfirst(substr($file, 0, strpos($file, '.'))));
    }
}
$files = JFolder::files(JOOMLA_LIBRARY . '/log/loggers', '\.php$', false, false);
foreach ($files as $file) {
    $load->requireClassFile(JOOMLA_LIBRARY . '/log/loggers/' . $file, 'JLogger' . ucfirst(substr($file, 0, strpos($file, '.'))));
}

/**
 *  Registry
 */
$load->requireClassFile(JOOMLA_LIBRARY . '/registry/format.php', 'JRegistryFormat');
$files = JFolder::files(JOOMLA_LIBRARY . '/registry/format', '\.php$', false, false);
foreach ($files as $file) {
    $load->requireClassFile(JOOMLA_LIBRARY . '/registry/format/' . $file, 'JRegistryFormat' . strtoupper(substr($file, 0, strpos($file, '.'))));
}

/**
 *  String
 */
$load->requireClassFile(JOOMLA_LIBRARY . '/string/string.php', 'JString');
$load->requireClassFile(JOOMLA_LIBRARY . '/string/stringnormalize.php', 'JStringNormalize');

/**
 *  Utilities
 */
$load->requireClassFile(JOOMLA_LIBRARY . '/utilities/arrayhelper.php', 'JArrayHelper');
$load->requireClassFile(JOOMLA_LIBRARY . '/utilities/buffer.php', 'JBuffer');
$load->requireClassFile(JOOMLA_LIBRARY . '/utilities/date.php', 'JDate');

/**
 *  PHPMailer
 */
$load->requireClassFile(PLATFORMS . '/jplatform/phpmailer/phpmailer.php', 'PHPMailer');
