<?php
/**
 * @package   Molajo
 * @subpackage  Application
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Plugin Class
 *
 * @package   Molajo
 * @subpackage  Plugin
 * @since       11.1
 */
abstract ClassPluginHelper extends JEvent
{
    /**
     * Plugin Parameters
     *
     * @var    string
     */
    public $parameters = null;

    /**
     * The name of the plugin
     *
     * @var    sring
     */
    protected $_name = null;

    /**
     * The plugin type
     *
     * @var    string
     */
    protected $_type = null;

    /**
     * Constructor
     *
     * @param   object  $subject  The object to observe
     * @param   array  $config  An optional associative array of configuration settings.
     * Recognized key values include 'name', 'group', 'parameters', 'language'
     * (this list is not meant to be comprehensive).
     *
     * @since   1.0
     */
    public function __construct(&$subject, $config = array())
    {
        if (isset($config['parameters'])) {
            if ($config['parameters'] instanceof Registry) {
                $this->parameters = $config['parameters'];
            } else {
                $this->parameters = Services::Registry()->initialise();
                $this->parameters->loadString($config['parameters']);
            }
        }

        if (isset($config['name'])) {
            $this->_name = $config['name'];
        }

        if (isset($config['type'])) {
            $this->_type = $config['type'];
        }

        parent::__construct($subject);
    }

    /**
     * loadLanguage
     *
     * Loads the plugin language file
     *
     * @param   string   $extension    The extension for which a language file should be loaded
     * @param   string   $basePath    The basepath to use
     *
     * @return  boolean  True, if the file has successfully loaded.
     * @since   1.0
     */
    public function loadLanguage($extension = '', $basePath = MOLAJO_EXTENSIONS_PLUGINS)
    {
        if (empty($extension)) {
            $extension = 'plg' . ucfirst($this->_type) . ucfirst($this->_name);
        }
        Services::Language()->load(strtolower($extension), MOLAJO_EXTENSIONS_PLUGINS . '/' . $this->_type . '/' . $extension, null, false, false);
    }
}
