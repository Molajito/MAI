<?php
/**
 * @package     Molajo
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Application;

defined('MOLAJO') or die;

use Molajo\Common\Version;
use Molajo\Application;
use Molajo\Application\Services;
use Molajo\Application\MVC\Model;

/**
 * Site
 *
 * @package     Molajo
 * @subpackage  Application
 * @since       1.0
 */
Class Site
{
    /**
     * $instance
     *
     * @var    object
     * @since  1.0
     */
    protected static $instance = null;

    /**
     * $config
     *
     * @var    integer
     * @since  1.0
     */
    protected $config = null;

    /**
     * $parameters
     *
     * @var    array
     * @since  1.0
     */
    protected $parameters = null;

    /**
     * $custom_fields
     *
     * @var    array
     * @since  1.0
     */
    protected $custom_fields = null;

    /**
     * getInstance
     *
     * Returns the global site object, creating if not existing
     *
     * @return  site  object
     * @since   1.0
     */
    public static function getInstance()
    {
        if (self::$instance) {
        } else {
            self::$instance = new Site ();
        }
        return self::$instance;
    }

    /**
     * __construct
     *
     * Class constructor.
     *
     * @since  1.0
     */
    public function __construct()
    {
        if (version_compare(PHP_VERSION, '5.3', '<')) {
            die('Your host needs to use PHP 5.3 or higher to run Molajo.');
        }

        $molajo = new Version();
        //        echo $molajo->VERSION;

        Molajo::RequestService();

        $this->_setBaseURL();

        $this->_setDefines();

        $this->_setSite();

        $this->_setApplication();

        $sv = Molajo::Services()->startServices();

        if (Service::Configuration()->get('debug', 0) == 1) {
            debug('Application::initialize Start Services');
        }

        /** offline */
        if (Service::Configuration()->get('offline', 0) == 1) {
            $this->_error(503);
        }

        /** verify application secure access configuration */
        if (Service::Configuration()->get('force_ssl') >= 1) {
            if ((Service::Request()->isSecure() === true)) {
            } else {

                $redirectTo = (string)'https' .
                    substr(MOLAJO_BASE_URL, 4, strlen(MOLAJO_BASE_URL) - 4) .
                    MOLAJO_APPLICATION_URL_PATH .
                    '/' . MOLAJO_PAGE_REQUEST;

                Service::Response()
                    ->setStatusCode(301)
                    ->isRedirect($redirectTo);
            }
        }

        /** establish the session */
        //Service::Session()->create(
        //        Service::Session()->getHash(get_class($this))
        //  );
        if (Service::Configuration()->get('debug', 0) == 1) {
            debug('Application::initialize Service::Session()');
        }

        /** return to Molajo::Site */
        return;

    }

    /**
     * load
     *
     * Retrieves the configuration information, loads language files, editor, triggers onAfterInitialise
     *
     * @param    array
     *
     * @return null
     * @since 1.0
     */
    protected function _load()
    {
        /** Instantiate Application */
        Molajo::Application()->initialize();

        /** Set Site Paths */
        $this->_setSitePaths();

        /** Site Parameters */
        $m = new SitesModel ();
        $m->query->where($m->db->qn('id') . ' = ' . (int)SITE_ID);
        $results = $m->runQuery();
        foreach ($results as $info)
        {
        }
        if ($info === false) {
            return;
        }

        /** is site authorised for this Application? */
        $authorise = Service::Access()
            ->authoriseSiteApplication();
        if ($authorise === false) {
            $message = '304: ' . MOLAJO_BASE_URL;
            echo $message;
            die;
        }

        $this->custom_fields = new Registry;
        $this->custom_fields->loadString($info->custom_fields);

        $this->parameters = new Registry;
        $this->parameters->loadString($info->parameters);

        $this->metadata = new Registry;
        $this->metadata->loadString($info->metadata);

        $this->base_url = $info->base_url;

        /** Primary Application Logic Flow */
        $results = Molajo::Application()->process();

        /** Application Complete */
        if (Service::Configuration()->get('debug', 0) == 1) {
            debug('MolajoSite::load End');
        }

        exit(0);
    }

    /**
     * _setBaseURL
     *
     * Class constructor.
     *
     * @return  void
     * @since   1.0
     */
    public function _setBaseURL()
    {
        $baseURL = Molajo::RequestService()->request->getScheme()
            . '://'
            . Molajo::RequestService()->request->getHttpHost()
            . Molajo::RequestService()->request->getBaseUrl();

        if (defined('MOLAJO_BASE_URL')) {
        } else {
            define('MOLAJO_BASE_URL', $baseURL);
        }
        return;
    }

    /**
     *  _setDefines
     *
     * The MOLAJO_APPLICATIONS, MOLAJO_EXTENSIONS and VENDOR
     * folders and subfolders can be relocated outside of the
     * Apache htdocs folder for increased security. To do so:
     *
     * - create a defines.php file placed in the root of this site
     * that defines the location of those files (except VENDOR)
     *
     * - create an autoloadoverride.php file to replace the
     * Molajo/Common/Autoload.php file defining the namespaces
     * and the splClassLoader in these locations and the location
     * of VENDOR
     *
     * SITES contains content that must be accessible by the
     * Website and thus cannot be moved
     */
    protected function _setDefines()
    {
        if (file_exists(MOLAJO_BASE_FOLDER . '/defines.php')) {
            include_once MOLAJO_BASE_FOLDER . '/defines.php';
        }

        if (defined('MOLAJO_EXTENSIONS')) {
        } else {
            define('MOLAJO_EXTENSIONS', MOLAJO_BASE_FOLDER . '/Molajo/Extension');
        }
        if (defined('SITES')) {
        } else {
            define('SITES', MOLAJO_BASE_FOLDER . '/site');
        }

        /** Define PHP constants for application variables */
        $defines = simplexml_load_file(MOLAJO_APPLICATIONS
            . '/Configuration/defines.xml', 'SimpleXMLElement');

        foreach ($defines->define as $item) {
            if (defined((string)$item['name'])) {
            } else {
                $value = (string)$item['value'];
                define((string)$item['name'], $value);
            }
        }

        /**
         *  Applications
         */
        if (defined('MOLAJO_APPLICATIONS_MVC')) {
        } else {
            define('MOLAJO_APPLICATIONS_MVC', MOLAJO_APPLICATIONS . '/MVC');
        }
        if (defined('MOLAJO_APPLICATIONS_MVC_URL')) {
        } else {
            define('MOLAJO_APPLICATIONS_MVC_URL', MOLAJO_BASE_URL . '/Molajo/Application/MVC');
        }

        /**
         *  Extensions
         */
        if (defined('MOLAJO_EXTENSIONS_COMPONENTS')) {
        } else {
            define('MOLAJO_EXTENSIONS_COMPONENTS', MOLAJO_EXTENSIONS . '/Component');
        }
        if (defined('MOLAJO_EXTENSIONS_FORMFIELDS')) {
        } else {
            define('MOLAJO_EXTENSIONS_FORMFIELDS', MOLAJO_EXTENSIONS . '/Formfield');
        }
        if (defined('MOLAJO_EXTENSIONS_LANGUAGES')) {
        } else {
            define('MOLAJO_EXTENSIONS_LANGUAGES', MOLAJO_EXTENSIONS . '/Language');
        }
        if (defined('MOLAJO_EXTENSIONS_MODULES')) {
        } else {
            define('MOLAJO_EXTENSIONS_MODULES', MOLAJO_EXTENSIONS . '/Module');
        }
        if (defined('MOLAJO_EXTENSIONS_PLUGINS')) {
        } else {
            define('MOLAJO_EXTENSIONS_PLUGINS', MOLAJO_EXTENSIONS . '/Plugin');
        }
        if (defined('MOLAJO_EXTENSIONS_THEMES')) {
        } else {
            define('MOLAJO_EXTENSIONS_THEMES', MOLAJO_EXTENSIONS . '/Theme');
        }
        if (defined('MOLAJO_EXTENSIONS_VIEWS')) {
        } else {
            define('MOLAJO_EXTENSIONS_VIEWS', MOLAJO_EXTENSIONS . '/View');
        }

        if (defined('MOLAJO_EXTENSIONS_COMPONENTS_URL')) {
        } else {
            define('MOLAJO_EXTENSIONS_COMPONENTS_URL', MOLAJO_BASE_URL . '/Molajo/Extension/Component');
        }
        if (defined('MOLAJO_EXTENSIONS_FORMFIELDS_URL')) {
        } else {
            define('MOLAJO_EXTENSIONS_FORMFIELDS_URL', MOLAJO_BASE_URL . '/Molajo/Extension/Formfield');
        }
        if (defined('MOLAJO_EXTENSIONS_MODULES_URL')) {
        } else {
            define('MOLAJO_EXTENSIONS_MODULES_URL', MOLAJO_BASE_URL . '/Molajo/Extension/Module');
        }
        if (defined('MOLAJO_EXTENSIONS_PLUGINS_URL')) {
        } else {
            define('MOLAJO_EXTENSIONS_PLUGINS_URL', MOLAJO_BASE_URL . '/Molajo/Extension/Plugin');
        }
        if (defined('MOLAJO_EXTENSIONS_THEMES_URL')) {
        } else {
            define('MOLAJO_EXTENSIONS_THEMES_URL', MOLAJO_BASE_URL . '/Molajo/Extension/Theme');
        }
        if (defined('MOLAJO_EXTENSIONS_VIEWS_URL')) {
        } else {
            define('MOLAJO_EXTENSIONS_VIEWS_URL', MOLAJO_BASE_URL . '/Molajo/Extension/View');
        }

        /**
         *  Allows for quoting in language .ini files.
         */
        if (defined('MOLAJO_LANGUAGE_QUOTE_REPLACEMENT')) {
        } else {
            define('MOLAJO_LANGUAGE_QUOTE_REPLACEMENT', '"');
        }

        /**
         *  EXTENSION OPTIONS
         *
         *  TO BE REMOVED
         */
        define('MOLAJO_EXTENSION_OPTION_ID_MIMES_AUDIO', 400);
        define('MOLAJO_EXTENSION_OPTION_ID_MIMES_IMAGE', 410);
        define('MOLAJO_EXTENSION_OPTION_ID_MIMES_TEXT', 420);
        define('MOLAJO_EXTENSION_OPTION_ID_MIMES_VIDEO', 430);

        return;
    }

    /**
     * _setSite
     *
     * Identifies the specific site and sets site paths
     * for use in the application
     *
     * @return  void
     * @since   1.0
     */
    protected function _setSite()
    {
        if (defined('SITES')) {
        } else {
            define('SITES', MOLAJO_BASE_FOLDER . '/Site');
        }
        if (defined('SITES_MEDIA_FOLDER')) {
        } else {
            define('SITES_MEDIA_FOLDER', SITES . '/media');
        }
        if (defined('SITES_MEDIA_URL')) {
        } else {
            define('SITES_MEDIA_URL', MOLAJO_BASE_URL . 'site/media');
        }
        if (defined('SITES_TEMP_FOLDER')) {
        } else {
            define('SITES_TEMP_FOLDER', SITES . '/temp');
        }
        if (defined('SITES_TEMP_URL')) {
        } else {
            define('SITES_TEMP_URL', MOLAJO_BASE_URL . 'site/temp');
        }

        $scheme = '://' . Molajo::RequestService()->request->getScheme();
        $siteBase = substr(MOLAJO_BASE_URL, strlen($scheme), 999);

        if (defined('SITE_BASE_URL')) {
        } else {
            $sites = simplexml_load_file(MOLAJO_APPLICATIONS
                . '/Configuration/sites.xml', 'SimpleXMLElement');
            foreach ($sites->site as $single) {
                if ($single->base == $siteBase) {
                    define('SITE_BASE_URL', $single->base);
                    define('SITE_FOLDER_PATH', $single->folderpath);
                    define('SITE_APPEND_TO_BASE_URL', $single->appendtobaseurl);
                    define('SITE_ID', $single->id);
                    break;
                }
            }
            if (defined('SITE_BASE_URL')) {
            } else {
                echo 'Fatal Error: Cannot identify site for: ' . $siteBase;
                die;
            }
        }
    }

    /**
     *  _setApplication
     *
     *  Identify current application and page request
     *
     * @return  void
     * @since   1.0
     */
    protected function _setApplication()
    {
        /** ex. /molajo/administrator/index.php?option=login    */
        $p1 = Molajo::RequestService()->request->getPathInfo();
        $t2 = Molajo::RequestService()->request->getQueryString();
        if (trim($t2) == '') {
            $requestURI = $p1;
        } else {
            $requestURI = $p1 . '?' . $t2;
        }

        /** remove the first /  */
        $requestURI = substr($requestURI, 1, 9999);

        /** extract first node for testing as application name  */
        if (strpos($requestURI, '/')) {
            $applicationTest = substr($requestURI, 0, strpos($requestURI, '/'));
        } else {
            $applicationTest = $requestURI;
        }

        $pageRequest = '';
        if (defined('MOLAJO_APPLICATION')) {
            /* must also define MOLAJO_PAGE_REQUEST */
        } else {
            $apps = simplexml_load_file(MOLAJO_APPLICATIONS
                . '/Configuration/applications.xml', 'SimpleXMLElement');

            foreach ($apps->application as $app) {

                if ($app->name == $applicationTest) {

                    define('MOLAJO_APPLICATION', $app->name);
                    define('MOLAJO_APPLICATION_URL_PATH', MOLAJO_APPLICATION . '/');

                    $pageRequest = substr(
                        $requestURI,
                        strlen(MOLAJO_APPLICATION) + 1,
                        strlen($requestURI) - strlen(MOLAJO_APPLICATION) + 1
                    );
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

        /*  Page Request used in Molajo::Request                */
        if (defined('MOLAJO_PAGE_REQUEST')) {
        } else {
            if (strripos($pageRequest, '/') == (strlen($pageRequest) - 1)) {
                $pageRequest = substr($pageRequest, 0, strripos($pageRequest, '/'));
            }
            define('MOLAJO_PAGE_REQUEST', $pageRequest);
        }
    }

    /**
     * _installCheck
     *
     * Determine if the site has already been installed
     *
     * return  void
     * @since  1.0
     */
    protected function _installCheck()
    {
        if (defined('MOLAJO_INSTALL_CHECK')) {
        } else {
            define('MOLAJO_INSTALL_CHECK', false);
        }

        if (MOLAJO_APPLICATION == 'installation'
            || (MOLAJO_INSTALL_CHECK === false
                && file_exists(SITE_FOLDER_PATH . '/configuration.php'))
        ) {

        } else {
            if (!file_exists(SITE_FOLDER_PATH . '/configuration.php')
                || filesize(SITE_FOLDER_PATH . '/configuration.php' < 10)
            ) {
                //todo: use HTTPFoundation redirect
                $redirect = MOLAJO_BASE_URL . 'installation/';
                header('Location: ' . $redirect);
                exit();
            }
        }
    }

    /**
     * _setSitePaths
     *
     * Establish media, cache, log, etc., locations for site for application use
     *
     * @return mixed
     * @since  1.0
     */
    protected function _setSitePaths()
    {

        if (defined('SITE_NAME')) {
        } else {
            define('SITE_NAME', Service::Configuration()->get('site_name', SITE_ID));
        }

        if (defined('SITE_CACHE_FOLDER')) {
        } else {
            define('SITE_CACHE_FOLDER', Service::Configuration()->get('cache_path', SITE_FOLDER_PATH . '/cache'));
        }

        if (defined('SITE_LOGS_FOLDER')) {
        } else {
            define('SITE_LOGS_FOLDER', Service::Configuration()->get('logs_path', SITE_FOLDER_PATH . '/logs'));
        }

        /** following must be within the web document folder */
        if (defined('SITE_MEDIA_FOLDER')) {
        } else {
            define('SITE_MEDIA_FOLDER', Service::Configuration()->get('media_path', SITE_FOLDER_PATH . '/media'));
        }

        if (defined('SITE_MEDIA_URL')) {
        } else {
            define('SITE_MEDIA_URL', MOLAJO_BASE_URL . Service::Configuration()->get('media_url', MOLAJO_BASE_URL . 'sites/' . SITE_ID . '/media'));
        }

        if (defined('SITE_TEMP_FOLDER')) {
        } else {
            define('SITE_TEMP_FOLDER', Service::Configuration()->get('temp_path', SITE_FOLDER_PATH . '/temp'));
        }
        if (defined('SITE_TEMP_URL')) {
        } else {
            define('SITE_TEMP_URL', MOLAJO_BASE_URL . Service::Configuration()->get('temp_url', MOLAJO_BASE_URL . 'sites/' . SITE_ID . '/temp'));
        }
        return;
    }
}
