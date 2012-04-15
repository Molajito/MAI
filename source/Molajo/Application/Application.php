<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Application;

use Molajo\Application\MVC\Model\TableModel;

defined('MOLAJO') or die;

/**
 * Application
 *
 * @package     Molajo
 * @subpackage  Application
 * @since       1.0
 */
Class Application
{
	/**
	 * $instance
	 *
	 * @var        object
	 * @since      1.0
	 */
	protected static $instance = null;

	/**
	 * $rendered_output
	 *
	 * @var        string
	 * @since      1.0
	 */
	protected $rendered_output = null;

	/**
	 * getInstance
	 *
	 * Returns the global site object, creating if not existing
	 *
	 * @return  Application  object
	 * @since   1.0
	 */
	public static function getInstance()
	{
		if (self::$instance) {
		} else {
			self::$instance = new Application();
		}
		return self::$instance;
	}

	/**
	 * initialise
	 *
	 * Retrieves the configuration information,
	 * loads language files, editor, triggers onAfterInitialise
	 *
	 * @param    array
	 *
	 * @return null
	 * @since 1.0
	 */
	public function initialise()
	{
		if (version_compare(PHP_VERSION, '5.3', '<')) {
			die('Your host needs to use PHP 5.3 or higher to run Molajo.');
		}

		/** HTTP Class */
		$this->setBaseURL();

		/** PHP Constants */
		$this->setDefines();

		/** Site determination and paths */
		$this->setSite();

		/** Application determination and paths */
		$this->setApplication();

		/** Application installation check */
		$continue = $this->installCheck();
		if ($continue == false) {
			return $this;
		}

		/** Connect Application Services */
		Molajo::Services()->startServices();

		Services::Debug()
			->set('Molajo::Services()->startServices() complete');

		/** Secure Access Check */
		if (Services::Registry()->get('Configuration\\force_ssl') > 0) {

			if ((Services::Request()->connection->isSecure() === true)) {

			} else {

				$redirectTo = (string)'https' .
					substr(BASE_URL, 4, strlen(BASE_URL) - 4) .
					APPLICATION_URL_PATH .
					'/' . PAGE_REQUEST;

				return Services::Redirect()
					->set($redirectTo, 301);
			}
		}

		/** establish the session */
		//Services::Session()->create(
		//        Services::Session()->getHash(get_class($this))
		//  );

		Services::Debug()
			->set('Services::Session()->create complete');

		/** Site Paths, Custom Fields, and Authorisation */
		$this->setSitePaths();

		/** Retrieve Site data and save in registry */
		$this->setSiteData();

		/** Verify that this site is authorised to access this application */
		$this->getSiteApplicationAuthorisation();

		Services::Debug()
			->set('Molajo::Application()->initialise() complete');

		return $this;
	}

	/**
	 * route application
	 *
	 * @param null $override_request_url
	 * @param null $override_asset_id
	 *
	 * @return Application
	 * @since  1.0
	 */
	public function route($override_request_url = null, $override_asset_id = null)
	{
		Molajo::Route()
			->process(
				$override_request_url = null,
				$override_asset_id = null
			);

		return $this;
	}

	/**
	 * Executes a display or action task
	 *
	 * Display Task
	 *
	 * 1. Parse: recursively parses theme and then rendered output
	 *      for <include:type statements
	 *
	 * 2. Includer: each include statement is processed by the
	 *      associated extension includer in order, collecting
	 *      rendering data needed by the MVC
	 *
	 * 3. MVC: executes controller task, invoking model processing and
	 *    rendering of template and wrap views
	 *
	 * Steps 1-3 continue until no more <include:type statements are
	 *    found in the Theme and rendered output
	 *
	 * Action Task
	 *
	 * @param string $override_sequenceXML
	 * @param string $override_finalXML
	 * @return Application
	 */
	public function execute($override_sequenceXML = null, $override_finalXML = null)
	{
		if (Services::Redirect()->url === null
			&& (int)Services::Redirect()->code == 0
		) {
		} else {
			return $this;
		}

		if (Services::Registry()->get('Request\\mvc_controller') == 'display') {
			$this->rendered_output =
				Molajo::Parse()
					->process($override_sequenceXML = null,
					$override_finalXML = null
				);
			Services::Debug()
				->set('Molajo::Parse() complete');

		} else {

			/**
			 * Action Task
			 */
			//$this->processTask();
		}

		Services::Debug()
			->set('Molajo::Application()->process() Complete');

		return $this;
	}

	/**
	 * response
	 *
	 * @return mixed
	 */
	public function response()
	{
		if (Services::Redirect()->url === null
			&& (int)Services::Redirect()->code == 0
		) {

			Services::Debug()
				->set('Services::Response()->setContent() for ' . $this->rendered_output . ' Code: 200');

			Services::Response()
				->setContent($this->rendered_output)
				->setStatusCode(200)
				->prepare(Services::Request()->request)
				->send();

		} else {

			Services::Debug()
				->set('Services::Redirect()->redirect()->send() for ' . Services::Redirect()->url . ' Code: ' . Services::Redirect()->code);

			Services::Redirect()
				->redirect()
				->send();
		}

		Services::Debug()
			->set('Molajo::Application()->response End');

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
	protected function setBaseURL()
	{
		$baseURL = Molajo::Request()->request->getScheme()
			. '://'
			. Molajo::Request()->request->getHttpHost()
			. Molajo::Request()->request->getBaseUrl();

		if (defined('BASE_URL')) {
		} else {
			define('BASE_URL', $baseURL . '/');
		}
		return;
	}

	/**
	 * The APPLICATIONS, EXTENSIONS and VENDOR
	 * folders and subfolders can be relocated outside of the
	 * Apache htdocs folder for increased security. To do so:
	 *
	 * - create a defines.php file placed in the root of this site
	 * that defines the location of those files (except VENDOR)
	 *
	 * - create an autoloadoverride.php file to replace the
	 * Molajo/Common/Autoload.php file defining the namespaces
	 *
	 * SITES contains content that must be accessible by the
	 * Website and thus cannot be moved
	 */
	protected function setDefines()
	{
		if (file_exists(BASE_FOLDER . '/defines.php')) {
			include_once BASE_FOLDER . '/defines.php';
		}

		if (defined('EXTENSIONS')) {
		} else {
			define('EXTENSIONS', BASE_FOLDER . '/Molajo/Extension');
		}
		if (defined('SITES')) {
		} else {
			define('SITES', BASE_FOLDER . '/site');
		}
		if (defined('CONFIGURATION_FOLDER')) {
		} else {
			define('CONFIGURATION_FOLDER', BASE_FOLDER . '/Molajo/Application/Configuration');
		}

		/** Define PHP constants for application variables */
		$defines = simplexml_load_file(CONFIGURATION_FOLDER . '/defines.xml');

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
		if (defined('APPLICATIONS_MVC')) {
		} else {
			define('APPLICATIONS_MVC', APPLICATIONS . '/MVC');
		}
		if (defined('APPLICATIONS_MVC_URL')) {
		} else {
			define('APPLICATIONS_MVC_URL', BASE_URL . 'Molajo/Application/MVC');
		}

		/**
		 *  Extensions
		 */
		if (defined('EXTENSIONS_COMPONENTS')) {
		} else {
			define('EXTENSIONS_COMPONENTS', EXTENSIONS . '/Component');
		}
		if (defined('EXTENSIONS_FORMFIELDS')) {
		} else {
			define('EXTENSIONS_FORMFIELDS', EXTENSIONS . '/Formfield');
		}
		if (defined('EXTENSIONS_LANGUAGES')) {
		} else {
			define('EXTENSIONS_LANGUAGES', EXTENSIONS . '/Language');
		}
		if (defined('EXTENSIONS_MODULES')) {
		} else {
			define('EXTENSIONS_MODULES', EXTENSIONS . '/Module');
		}
		if (defined('EXTENSIONS_PLUGINS')) {
		} else {
			define('EXTENSIONS_PLUGINS', EXTENSIONS . '/Plugin');
		}
		if (defined('EXTENSIONS_THEMES')) {
		} else {
			define('EXTENSIONS_THEMES', EXTENSIONS . '/Theme');
		}
		if (defined('EXTENSIONS_VIEWS')) {
		} else {
			define('EXTENSIONS_VIEWS', EXTENSIONS . '/View');
		}

		if (defined('EXTENSIONS_COMPONENTS_URL')) {
		} else {
			define('EXTENSIONS_COMPONENTS_URL', BASE_URL . 'Molajo/Extension/Component');
		}
		if (defined('EXTENSIONS_FORMFIELDS_URL')) {
		} else {
			define('EXTENSIONS_FORMFIELDS_URL', BASE_URL . 'Molajo/Extension/Formfield');
		}
		if (defined('EXTENSIONS_MODULES_URL')) {
		} else {
			define('EXTENSIONS_MODULES_URL', BASE_URL . 'Molajo/Extension/Module');
		}
		if (defined('EXTENSIONS_PLUGINS_URL')) {
		} else {
			define('EXTENSIONS_PLUGINS_URL', BASE_URL . 'Molajo/Extension/Plugin');
		}
		if (defined('EXTENSIONS_THEMES_URL')) {
		} else {
			define('EXTENSIONS_THEMES_URL', BASE_URL . 'Molajo/Extension/Theme');
		}
		if (defined('EXTENSIONS_VIEWS_URL')) {
		} else {
			define('EXTENSIONS_VIEWS_URL', BASE_URL . 'Molajo/Extension/View');
		}

		/**
		 *  Allows for quoting in language .ini files.
		 */
		if (defined('LANGUAGE_QUOTE_REPLACEMENT')) {
		} else {
			define('LANGUAGE_QUOTE_REPLACEMENT', '"');
		}

		/**
		 *  EXTENSION OPTIONS
		 *
		 *  TO BE REMOVED
		 */
		define('EXTENSION_OPTION_ID_MIMES_AUDIO', 400);
		define('EXTENSION_OPTION_ID_MIMES_IMAGE', 410);
		define('EXTENSION_OPTION_ID_MIMES_TEXT', 420);
		define('EXTENSION_OPTION_ID_MIMES_VIDEO', 430);

		return;
	}

	/**
	 * Identifies the specific site and sets site paths
	 * for use in the application
	 *
	 * @return  void
	 * @since   1.0
	 */
	protected function setSite()
	{
		if (defined('SITES')) {
		} else {
			define('SITES', BASE_FOLDER . '/Site');
		}
		if (defined('SITES_MEDIA_FOLDER')) {
		} else {
			define('SITES_MEDIA_FOLDER', SITES . '/media');
		}
		if (defined('SITES_MEDIA_URL')) {
		} else {
			define('SITES_MEDIA_URL', BASE_URL . 'site/media');
		}
		if (defined('SITES_TEMP_FOLDER')) {
		} else {
			define('SITES_TEMP_FOLDER', SITES . '/temp');
		}
		if (defined('SITES_TEMP_URL')) {
		} else {
			define('SITES_TEMP_URL', BASE_URL . 'site/temp');
		}

		$scheme = Molajo::Request()->request->getScheme() . '://';
		$siteBase = substr(BASE_URL, strlen($scheme), 999);

		if (defined('SITE_BASE_URL')) {
		} else {
			$sites = simplexml_load_file(CONFIGURATION_FOLDER . '/sites.xml');

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
	 * Identify current application and page request
	 *
	 * @return  void
	 * @since   1.0
	 */
	protected function setApplication()
	{
		/** ex. /molajo/administrator/index.php?option=login    */
		$p1 = Molajo::Request()->request->getPathInfo();
		$t2 = Molajo::Request()->request->getQueryString();
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
		if (defined('APPLICATION')) {
			/* must also define PAGE_REQUEST */
		} else {
			$apps = simplexml_load_file(CONFIGURATION_FOLDER . '/applications.xml');

			foreach ($apps->application as $app) {

				if ($app->name == $applicationTest) {

					define('APPLICATION', $app->name);
					define('APPLICATION_URL_PATH', APPLICATION . '/');

					$pageRequest = substr(
						$requestURI,
						strlen(APPLICATION) + 1,
						strlen($requestURI) - strlen(APPLICATION) + 1
					);
					break;
				}
			}

			if (defined('APPLICATION')) {
			} else {
				define('APPLICATION', $apps->default->name);
				define('APPLICATION_URL_PATH', '');
				$pageRequest = $requestURI;
			}
		}

		/*  Page Request used in Molajo::Request                */
		if (defined('PAGE_REQUEST')) {
		} else {
			if (strripos($pageRequest, '/') == (strlen($pageRequest) - 1)) {
				$pageRequest = substr($pageRequest, 0, strripos($pageRequest, '/'));
			}
			define('PAGE_REQUEST', $pageRequest);
		}
	}

	/**
	 * Determine if the site has already been installed
	 *
	 * return  boolean
	 * @since  1.0
	 */
	protected function installCheck()
	{
		if (defined('SKIP_INSTALL_CHECK')) {
			return true;
		}

		if (APPLICATION == 'installation') {
			return true;
		}

		if (file_exists(SITE_FOLDER_PATH . '/configuration.php')
			&& filesize(SITE_FOLDER_PATH . '/configuration.php') > 10
		) {
			return true;
		}

		/** Redirect to Installation Application */
		$redirect = BASE_URL . 'installation/';
		header('Location: ' . $redirect);
		exit();
	}

	/**
	 * Establish media, cache, log, etc., locations for site for application use
	 *
	 * @return mixed
	 * @since  1.0
	 */
	protected function setSitePaths()
	{
		if (defined('SITE_NAME')) {
		} else {
			define('SITE_NAME', Services::Registry()->get('Configuration\\site_name', SITE_ID));
		}

		if (defined('SITE_CACHE_FOLDER')) {
		} else {
			define('SITE_CACHE_FOLDER', Services::Registry()->get('Configuration\\cache_path', SITE_FOLDER_PATH . '/cache'));
		}

		if (defined('SITE_LOGS_FOLDER')) {
		} else {
			define('SITE_LOGS_FOLDER', Services::Registry()->get('Configuration\\logs_path', SITE_FOLDER_PATH . '/logs'));
		}

		/** following must be within the web document folder */
		if (defined('SITE_MEDIA_FOLDER')) {
		} else {
			define('SITE_MEDIA_FOLDER', Services::Registry()->get('Configuration\\media_path', SITE_FOLDER_PATH . '/media'));
		}

		if (defined('SITE_MEDIA_URL')) {
		} else {
			define('SITE_MEDIA_URL', BASE_URL . Services::Registry()->get('Configuration\\media_url', BASE_URL . 'sites/' . SITE_ID . '/media'));
		}

		if (defined('SITE_TEMP_FOLDER')) {
		} else {
			define('SITE_TEMP_FOLDER', Services::Registry()->get('Configuration\\temp_path', SITE_FOLDER_PATH . '/temp'));
		}

		if (defined('SITE_TEMP_URL')) {
		} else {
			define('SITE_TEMP_URL', BASE_URL . Services::Registry()->get('Configuration\\temp_url', BASE_URL . 'sites/' . SITE_ID . '/temp'));
		}

		return;
	}

	/**
	 * Retrieve Site data and save in registry
	 *
	 * @return mixed
	 * @since  1.0
	 * @throws \RuntimeException
	 */
	protected function setSiteData()
	{
		$m = new TableModel ('Sites', SITE_ID);

		$m->query->where($m->db->qn('id') . ' = ' . (int)SITE_ID);

		$results = $m->loadAssoc();
		if ($results === false) {
			throw new \RuntimeException ('setSiteData query problem');
		}

		/** Registry for Custom Fields and Metadata */
		$xml = simplexml_load_file( APPLICATIONS_MVC . '/Model/Table/Sites.xml');
		Services::Registry()->loadField('SiteCustomFields\\', 'custom_fields', $results['custom_fields'], $xml->custom_fields);
		Services::Registry()->loadField('SiteMetadata\\', 'meta', $results['metadata'], $xml->metadata);

		$this->base_url = $results['base_url'];

		return;
	}

	/**
	 * Verify that this site is authorised to access this application
	 *
	 * @returns boolean
	 * @since   1.0
	 */
	protected function getSiteApplicationAuthorisation()
	{
		$authorise = Services::Access()->authoriseSiteApplication();
		if ($authorise === false) {
			$message = '304: ' . BASE_URL;
			echo $message;
			die;
		}
		return true;
	}
}
