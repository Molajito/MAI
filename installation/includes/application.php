<?php
/**
 * @package     Molajo
 * @subpackage  Installation
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2011 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Application class
 *
 * Provide many supporting API functions
 *
 * @package		Molajo
 * @subpackage  Installation
 * @since       1.0
 *
 */
class MolajoInstallation extends MolajoApplication
{
	/**
	 * The url of the site
	 *
	 * @var string
	 */
	protected $_siteURL = null;

	/**
	* Class constructor
	*
	* @param	array $config	An optional associative array of configuration settings.
	* Recognized key values include 'applicationId' (this list is not meant to be comprehensive).
	*
	* @return	void
	*/
	public function __construct(array $config = array())
	{
		$config['applicationId'] = 2;
		parent::__construct($config);
		JError::setErrorHandling(E_ALL, 'Ignore');
		$this->_createConfiguration();

		// Set the root in the URI based on the application name.
		JURI::root(null, str_replace('/'.$this->getName(), '', JURI::base(true)));
	}

    /**
     * Initialise the application.
     *
     * @param	array	$options
     *
     * @return	void
     */
    public function initialise($options = array())
    {
        //Get the localisation information provided in the localise.xml file.
        $forced = $this->getLocalise();

        // Check the request data for the language.
        if (empty($options['language'])) {
            $requestLang = JRequest::getCmd('lang', null);
            if (!is_null($requestLang)) {
                $options['language'] = $requestLang;
            }
        }

        // Check the session for the language.
        if (empty($options['language'])) {
            $sessionLang = MolajoFactory::getSession()->get('setup.language');
            if (!is_null($sessionLang)) {
                $options['language'] = $sessionLang;
            }
        }

        // This could be a first-time visit - try to determine what the application accepts.
        if (empty($options['language'])) {
            if (!empty($forced['language'])) {
                $options['language'] = $forced['language'];
            } else {
                $options['language'] = JLanguageHelper::detectLanguage();
                if (empty($options['language'])) {
                    $options['language'] = 'en-GB';
                }
            }
        }

        // Give the user English
        if (empty($options['language'])) {
            $options['language'] = 'en-GB';
        }

        // Set the language in the class
        $conf = MolajoFactory::getConfig();
        $conf->set('language', $options['language']);
        $conf->set('debug_lang', $forced['debug']);
        $conf->set('sampledata', $forced['sampledata']);
    }

	/**
     * render
     *
	 * Render the application
	 *
	 * @return	void
	 */
	public function render()
	{
		$document = MolajoFactory::getDocument();
		$config = MolajoFactory::getConfig();
		$user = MolajoFactory::getUser();

		switch($document->getType())
		{
			case 'html' :
				$document->setTitle(MolajoText::_('INSTL_PAGE_TITLE'));
				break;
			default :
				break;
		}

		// Import the controller.
		require_once MOLAJO_PATH_BASE.'/controller.php';
		$controller	= JController::getInstance('MolajoInstallation');

		// Start the output buffer.
		ob_start();
 
		// Execute the task.
		$controller->execute(JRequest::getVar('task'));
		$controller->redirect();

		// Get output from the buffer and clean it.
		$contents = ob_get_contents();

		ob_end_clean();

		$file = JRequest::getCmd('tmpl', 'index');

		$params = array(
			'template'	=> 'template',
			'file'		=> $file.'.php',
			'directory' => MOLAJO_PATH_THEMES,
			'params'	=> '{}'
		);

		$document->setBuffer($contents, 'installation');
		$document->setTitle(MolajoText::_('INSTL_PAGE_TITLE'));
		$data = $document->render(false, $params);
		JResponse::setBody($data);
		if (MolajoFactory::getConfig()->get('debug_lang')) {
			$this->debugLanguage();
		}
	}

	/**
     * debugLanguage
     *
	 * @return	void
	 */
	public static function debugLanguage()
	{
		ob_start();
		$lang = MolajoFactory::getLanguage();
		echo '<h4>Parsing errors in language files</h4>';
		$errorfiles = $lang->getErrorFiles();

		if (count($errorfiles)) {
			echo '<ul>';

			foreach ($errorfiles as $file => $error)
			{
				echo "<li>$error</li>";
			}
			echo '</ul>';
		}
		else {
			echo '<pre>None</pre>';
		}

		echo '<h4>Untranslated Strings</h4>';
		echo '<pre>';
		$orphans = $lang->getOrphans();

		if (count($orphans)) {
			ksort($orphans, SORT_STRING);

			foreach ($orphans as $key => $occurance)
			{
				$guess = str_replace('_', ' ', $key);

				$parts = explode(' ', $guess);
				if (count($parts) > 1) {
					array_shift($parts);
					$guess = implode(' ', $parts);
				}

				$guess = trim($guess);


				$key = trim(strtoupper($key));
				$key = preg_replace('#\s+#', '_', $key);
				$key = preg_replace('#\W#', '', $key);

				// Prepare the text
				$guesses[] = $key.'="'.$guess.'"';
			}

			echo "\n\n# ".$file."\n\n";
			echo implode("\n", $guesses);
		}
		else {
			echo 'None';
		}
		echo '</pre>';
		$debug = ob_get_clean();
		JResponse::appendBody($debug);
	}

	/**
	 * Set configuration values
	 *
	 * @param	array	$vars		Array of configuration values
	 * @param	string	$namespace	The namespace
	 *
	 * @return	void
	 */
	public function setCfg(array $vars = array(), $namespace = 'config')
	{
		$this->_registry->loadArray($vars, $namespace);
	}

	/**
	 * Create the configuration registry
	 *
	 * @return	void
	 */
	public function _createConfiguration($file = null)
	{
		$this->_registry = new JRegistry('config');
	}

	/**
	* Get the template
	*
	* @return string The template name
	*/
	public function getTemplate($params = false)
	{
		if ((bool) $params) {
			$template = new stdClass();
			$template->template = 'template';
			$template->params = new JRegistry;
			return $template;
		}
		return 'template';
	}

	/**
	 * Create the user session
	 *
	 * @param	string	$name	The sessions name
	 *
	 * @return	object	JSession
	 */
	public function & _createSession($name)
	{
		$options = array();
		$options['name'] = $name;

		$session = MolajoFactory::getSession($options);
		if (!is_a($session->get('registry'), 'JRegistry')) {
			$session->set('registry', new JRegistry('session'));
		}

		return $session;
	}

	/**
     * getLocalise
     *
	 * Returns the language code and help url set in the localise.xml file.
	 * Used for forcing a particular language in localised releases.
	 *
	 * @return	bool|array	False on failure, array on success.
	 */
	public function getLocalise()
	{
		$xml = MolajoFactory::getXML(MOLAJO_PATH_SITE . '/installation/localise.xml');

		if (!$xml) {
			return false;
		}

		// Check that it's a localise file
		if ($xml->getName() != 'localise') {
			return false;
		}

		$ret = array();

		$ret['language'] = (string)$xml->forceLang;
		$ret['helpurl'] = (string)$xml->helpurl;
		$ret['debug'] = (string)$xml->debug;
		$ret['sampledata'] = (string)$xml->sampledata;

		return $ret;
	}

    /**
     * getLocaliseAdmin
     *
     * Returns the installed language files in the administrative and
     * front-end area.
     *
     * @param	boolean	$db
     *
     * @return array Array with installed language packs in admin and site area
     */
 	public function getLocaliseAdmin($db=false)
 	{
 		// Read the files in the admin area
 		$path = JLanguage::getLanguagePath(MOLAJO_PATH_SITE . '/administrator');
 		$langfiles['admin'] = JFolder::folders($path);

 		// Read the files in the site area
 		$path = JLanguage::getLanguagePath(MOLAJO_PATH_SITE);
 		$langfiles['site'] = JFolder::folders($path);

 		if ($db) {
 			$langfiles_disk = $langfiles;
 			$langfiles = Array();
 			$langfiles['admin'] = Array();
 			$langfiles['site'] = Array();
 			$query = $db->getQuery(true);
 			$query->select('element, application_id');
 			$query->from('#__extensions');
 			$query->where('type = '.$db->quote('language'));
 			$db->setQuery($query);
 			$langs = $db->loadObjectList();
 			foreach ($langs as $lang)
 			{
 				switch($lang->application_id)
 				{
 					case 0: // site
 						if (in_array($lang->element, $langfiles_disk['site'])) {
 							$langfiles['site'][] = $lang->element;
 						}
 						break;
 					case 1: // administrator
 						if (in_array($lang->element, $langfiles_disk['admin'])) {
 							$langfiles['admin'][] = $lang->element;
 						}
 						break;
 				}
 			}
 		}

 		return $langfiles;
 	}
}