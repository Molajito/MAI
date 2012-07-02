<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see license folder
 */
namespace Molajo\Service\Services\Password;

use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Password
 *
 * @package     Molajo
 * @subpackage  Services
 * @since       1.0
 */
Class PasswordService
{
    /**
     * Static instance
     *
     * @var    object
     * @since  1.0
     */
    protected static $instance;

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
            self::$instance = new PasswordService();
        }

        return self::$instance;
    }

    /**
     * Class constructor.
     *
     * @since  1.0
     */
    public function __construct()
    {

    }
}
