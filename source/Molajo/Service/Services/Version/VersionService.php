<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Service\Services\Version;

defined('MOLAJO') or die;

/**
 * Version information class for Molajo
 *
 * @package  Molajo
 * @since    1.0
 */
class VersionService
{
    /**
     * Static instance
     *
     * @var    object
     * @since  1.0
     */
    protected static $instance;

    /**
     * Current Molajo Version
     */
    const VERSION = '1.0-DEV';

    /**
     * getInstance
     *
     * @static
     * @return bool|object
     * @since  1.0
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new VersionService();
        }

        if (defined('MOLAJOVERSION')) {
        } else {
            define('MOLAJOVERSION', self::VERSION);
        }

        return self::$instance;
    }

    /**
     * Compares a Molajo version with the current one.
     *
     * @param  string $version Molajo version to compare.
     * @return int    Returns -1 if older, 0 if it is the same, 1 if version
     * passed as argument is newer.
     */
    public static function compare($version)
    {
        $currentVersion = str_replace(' ', '', strtolower(self::VERSION));
        $version = str_replace(' ', '', $version);

        return version_compare($version, $currentVersion);
    }
}
