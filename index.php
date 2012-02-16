<?php
/**
 * @package     Molajo
 * @subpackage  Bootstrap
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
define('MOLAJO', 'Long Live Molajo!');

/** php directives */
@ini_set('magic_quotes_runtime', 0);
@ini_set('zend.ze1_compatibility_mode', 0);
@ini_set('short_open_tag', 1);

/** See if this is still needed before release */
define('DS', DIRECTORY_SEPARATOR);

/** Base Folder and URL */
define('MOLAJO_BASE_FOLDER', strtolower(__DIR__));

/**
 *  OVERRIDE
 *
 * $siteConfig and $appConfig - to pass in configuration data
 *
 * Overriding these will get all folders needed:
 *  - applications - define MOLAJO_APPLICATIONS_CORE
 *  - extensions - define MOLAJO_APPLICATIONS_CORE
 *  - platforms - define PLATFORMS
 *  - sites - define MOLAJO_SITES_BASE_FOLDER
 * _and_ update the sites.xml file folderpath value
 *
 */
$siteConfig = array();
$appConfig = array();
if (file_exists(MOLAJO_BASE_FOLDER . '/defines.php')) {
    include_once MOLAJO_BASE_FOLDER . '/defines.php';
}

/*                                              */
/*  Server Super Globals                        */
/*                                              */
$protocol = 'http';
$siteBase = '';
if (isset($_SERVER['HTTPS'])) {
    $protocol .= 's';
}
$protocol .= '://';
if (isset($_SERVER['SERVER_NAME'])) {
    $siteBase = $_SERVER['SERVER_NAME'];
}
if (isset($_SERVER['SERVER_PORT'])) {
    if ($_SERVER['SERVER_PORT'] == '80') {
    } else {
        $siteBase .= ":" . $_SERVER['SERVER_PORT'];
    }
}
if (strripos($_SERVER['PHP_SELF'], '/index.php')) {
    $folder = substr($_SERVER['PHP_SELF'], 0, strripos($_SERVER['PHP_SELF'], '/index.php')).'/';
} else {
    $folder = '/';
}
$siteBase .= $folder;

/** base url for this site ex. http://localhost/molajo/ */
define('MOLAJO_BASE_URL', strtolower($protocol.$siteBase));

/*                                              */
/*  SITES LAYER                                 */
/*                                              */
if (defined('MOLAJO_SITES_BASE_FOLDER')) {
} else {
    define('MOLAJO_SITES_BASE_FOLDER', MOLAJO_BASE_FOLDER . '/sites');
}
if (defined('MOLAJO_SITES_MEDIA_FOLDER')) {
} else {
    define('MOLAJO_SITES_MEDIA_FOLDER', MOLAJO_SITES_BASE_FOLDER . '/media');
}
if (defined('MOLAJO_SITES_MEDIA_URL')) {
} else {
    define('MOLAJO_SITES_MEDIA_URL', MOLAJO_BASE_URL . 'sites/media');
}
if (defined('MOLAJO_SITES_TEMP_FOLDER')) {
} else {
    define('MOLAJO_SITES_TEMP_FOLDER', MOLAJO_SITES_BASE_FOLDER . '/temp');
}
if (defined('MOLAJO_SITES_TEMP_URL')) {
} else {
    define('MOLAJO_SITES_TEMP_URL', MOLAJO_BASE_URL . 'sites/temp');
}

if (defined('MOLAJO_SITE_BASE_URL')) {
} else {
    $sites = simplexml_load_file(MOLAJO_SITES_BASE_FOLDER . '/sites.xml', 'SimpleXMLElement');
    foreach ($sites->site as $single) {
        if ($single->base == $siteBase) {
            define('MOLAJO_SITE_BASE_URL', $single->base);
            define('MOLAJO_SITE_FOLDER_PATH', $single->folderpath);
            define('MOLAJO_SITE_APPEND_TO_BASE_URL', $single->appendtobaseurl);
            define('MOLAJO_SITE_ID', $single->id);
            break;
        }
    }
    if (defined('MOLAJO_SITE_BASE_URL')) {
    } else {
        echo 'Fatal Error: Cannot identify site for: '.$siteBase;
        die;
    }
}
/*                                              */
/*  APPLICATIONS LAYER                          */
/*                                              */

/**
 * $_SERVER["REQUEST_URI"] everything following host
 *  ex. /molajo/administrator/index.php?option=login
 */
$requestURI = strtolower($_SERVER["REQUEST_URI"]);
/** remove path ex. /molajo/ */
$requestURI = substr($requestURI, strlen($folder), strlen($requestURI) - strlen($folder));
/** extract first node for testing as application name */
if (strpos($requestURI, '/')) {
    $applicationTest = substr($requestURI, 0, strpos($requestURI, '/'));
} else {
    $applicationTest = $requestURI;
}
if (defined('MOLAJO_APPLICATIONS_CORE')) {
} else {
    define('MOLAJO_APPLICATIONS_CORE', MOLAJO_BASE_FOLDER . '/applications');
}
if (defined('MOLAJO_APPLICATION')) {
} else {
    $apps = simplexml_load_file(MOLAJO_APPLICATIONS_CORE . '/applications.xml', 'SimpleXMLElement');
    foreach ($apps->application as $app) {
        if ($app->name == $applicationTest) {
            define('MOLAJO_APPLICATION', $app->name);
            define('MOLAJO_APPLICATION_URL_PATH', MOLAJO_APPLICATION.'/');
            $pageRequest = substr($requestURI, strlen(MOLAJO_APPLICATION) + 1, strlen($requestURI) - strlen(MOLAJO_APPLICATION) + 1);
            break;
        }
    }
    if (defined('MOLAJO_APPLICATION')) {
    } else {
        define('MOLAJO_APPLICATION', $apps->default->name);
        define('MOLAJO_APPLICATION_URL_PATH', '');
        $pageRequest = $requestURI;
    }
}

if (defined('MOLAJO_PAGE_REQUEST')) {
} else {
    if (strripos($pageRequest, '/') == (strlen($pageRequest) - 1)) {
        $pageRequest = substr($pageRequest, 0, strripos($pageRequest, '/'));
    }
    define('MOLAJO_PAGE_REQUEST', $pageRequest);
}

/*                                              */
/*  Lazy Load Classes                           */
/*                                              */
require_once MOLAJO_APPLICATIONS_CORE . '/includes/phpversion.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/defines.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/installcheck.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/platforms-joomla.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/config.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/platforms-mustache.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/applications.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/aliases.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/platforms-molajo.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/platforms-simplepie.php';
require_once MOLAJO_APPLICATIONS_CORE . '/includes/platforms-sfevent.php';

/**
 *  Go Molajo.
 */
Molajo::Site();
