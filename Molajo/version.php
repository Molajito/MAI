<?php
/**
 * @package     Molajo
 * @subpackage  Version
 * @copyright   Copyright (C) 2012 Individual Molajo Contributors. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Version information class for Molajo
 *
 * @package  Molajo
 * @since    1.0
 */
final ClassVersion
{
    /** @var  string  Product name. */
    public $PRODUCT = 'Molajo';

    /** @var  string  Release version. */
    public $RELEASE = '1.0';

    /** @var  string  Maintenance version. */
    public $MAINTENANCE = '0';

    /** @var  string  Development STATUS. */
    public $STATUS = 'Prealpha';

    /** @var  string  Build number. */
    public $BUILD = 0;

    /** @var  string  Code name. */
    public $CODE_NAME = 'Phoenix';

    /** @var  string  Release date. */
    public $RELEASE_DATE = '11-Nov-2011';

    /** @var  string  Release time. */
    public $RELEASE_TIME = '11:11';

    /** @var  string  Release timezone. */
    public $RELEASE_TIME_ZONE = 'GMT';

    /** @var  string  Copyright Notice. */
    public $COPYRIGHT = 'Copyright (C) 2012 Individual Molajo Contributors. All rights reserved.';

    /** @var  string  Link text. */
    public $LINK_TEXT = '<a href="http://molajo.org">Molajo</a> is Free Software released under the GNU General Public License.';

    /**
     * Compares two a "PHP standardized" version number against the current Joomla version.
     *
     * @param   string  $minimum  The minimum version of the Joomla which is compatible.
     *
     * @return  bool    True if the version is compatible.
     *
     * @see     http://www.php.net/version_compare
     * @since   1.0
     */
    public function isCompatible($minimum)
    {
        return (version_compare(MOLAJOVERSION, $minimum, 'eq') == 1);
    }

    /**
     * Method to get the help file version.
     *
     * @return  string  Version suffix for help files.
     *
     * @since   1.0
     */
    public function getHelpVersion()
    {
        if ($this->RELEASE > '1.0') {
            return '.' . str_replace('.', '', $this->RELEASE);
        }
        else {
            return '';
        }
    }

    /**
     * Gets a "PHP standardized" version string for the current Joomla.
     *
     * @return  string  Version string.
     *
     * @since   1.5
     */
    public function getShortVersion()
    {
        return $this->RELEASE . '.' . $this->MAINTENANCE;
    }

    /**
     * Gets a version string for the current Joomla with all release information.
     *
     * @return  string  Complete version string.
     *
     * @since   1.5
     */
    public function getLongVersion()
    {
        return $this->PRODUCT . ' ' . $this->RELEASE . '.' . $this->MAINTENANCE . ' '
               . $this->STATUS . ' [ ' . $this->CODE_NAME . ' ] ' . $this->RELEASE_DATE . ' '
               . $this->RELEASE_TIME . ' ' . $this->RELEASE_TIME_ZONE;
    }

    /**
     * Returns the user agent.
     *
     * @param   string  $component    Name of the component.
     * @param   bool    $mask         Mask as Mozilla/5.0 or not.
     * @param   bool    $add_version  Add version afterwards to component.
     *
     * @return  string  User Agent.
     *
     * @since   1.0
     */
    public function getUserAgent($component = null, $mask = false, $add_version = true)
    {
        if ($component === null) {
            $component = 'Framework';
        }

        if ($add_version) {
            $component .= '/' . $this->RELEASE;
        }

        // If masked pretend to look like Mozilla 5.0 but still identify ourselves.
        if ($mask) {
            return 'Mozilla/5.0 ' . $this->PRODUCT . '/' . $this->RELEASE . '.' . $this->DEV_LEVEL . ($component
                    ? ' ' . $component : '');
        }
        else {
            return $this->PRODUCT . '/' . $this->RELEASE . '.' . $this->DEV_LEVEL . ($component ? ' ' . $component
                    : '');
        }
    }
}

// Define the Joomla version if not already defined.
if (!defined('MOLAJOVERSION')) {
    $molajoversion = new MolajoVersion;
    define('MOLAJOVERSION', $molajoversion->getShortVersion());
}
