<?php
/**
 * @package     Molajo
 * @subpackage  Application
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2012 Individual Molajo Contributors. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Joomla! Library Manifest File
 *
 * @package     Joomla.Platform
 * @subpackage  Installer
 * @since       11.1
 */
class MolajoInstallerLibrarymanifest extends JObject
{
    /**
     * @var string name Name of Library
     */
    var $name = '';

    /**
     * @var string libraryname File system name of the library
     */
    var $libraryname = '';

    /**
     * @var string version Version of the library
     */
    var $version = '';

    /**
     * @var string description Description of the library
     */
    var $description = '';

    /**
     * @var date create_date Creation Date of the extension
     */
    var $create_date = '';

    /**
     * @var string copyright Copyright notice for the extension
     */
    var $copyright = '';

    /**
     * @var string license License for the extension
     */
    var $license = '';

    /**
     * @var string author Author for the extension
     */
    var $author = '';

    /**
     * @var string authoremail Author email for the extension
     */
    var $authoremail = '';

    /**
     * @var string authorurl Author url for the extension
     */
    var $authorurl = '';

    /**
     * @var string packager Name of the packager for the library (may also be porter)
     */
    var $packager = '';

    /**
     * @var string packagerurl URL of the packager for the library (may also be porter)
     */
    var $packagerurl = '';

    /**
     * @var string update URL of the update site
     */
    var $update = '';

    /**
     * @var string[] filelist List of files in the library
     */
    var $filelist = array();

    /**
     * @var string manifest_file Path to manifest file
     */
    var $manifest_file = '';

    /**
     * Constructor
     *
     * @param   string  $xmlpath  Path to an XML file to load the manifest from.
     *
     * @return  MolajoInstallerLibrarymanifest
     *
     * @since   1.0
     */
    function __construct($xmlpath = '')
    {
        if (strlen($xmlpath)) {
            $this->loadManifestFromXML($xmlpath);
        }
    }

    /**
     * Load a manifest from a file
     *
     * @param   string  $xmlfile  Path to file to load
     *
     * @return  boolean
     *
     * @since   1.0
     */
    function loadManifestFromXML($xmlfile)
    {
        $this->manifest_file = JFile::stripExt(basename($xmlfile));

        $xml = simplexml_load_file($xmlfile);
        if (!$xml) {
            $this->_errors[] = Services::Language()->sprintf('JLIB_INSTALLER_ERROR_LOAD_XML', $xmlfile);
            return false;
        }
        else
        {
            $this->name = (string)$xml->name;
            $this->libraryname = (string)$xml->libraryname;
            $this->version = (string)$xml->version;
            $this->description = (string)$xml->description;
            $this->creationdate = (string)$xml->creationdate;
            $this->author = (string)$xml->author;
            $this->authoremail = (string)$xml->author_email;
            $this->authorurl = (string)$xml->author_url;
            $this->packager = (string)$xml->packager;
            $this->packagerurl = (string)$xml->packagerurl;
            $this->update = (string)$xml->update;

            if (isset($xml->files) && isset($xml->files->file) && count($xml->files->file)) {
                foreach ($xml->files->file as $file)
                {
                    $this->filelist[] = (string)$file;
                }
            }
            return true;
        }
    }
}
