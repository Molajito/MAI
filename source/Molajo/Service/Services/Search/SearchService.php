<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see license folder
 */
namespace Molajo\Service\Services\Search;

use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Search
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
Class SearchService
{
    /**
     * @static
     * @var    object
     * @since  1.0
     */
    protected static $instance;

    /**
     * @static
     * @return bool|object
     * @since   1.0
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new SearchService();
        }

        return self::$instance;
    }

    /**
     * Class constructor.
     *
     * @return boolean
     * @since  1.0
     */
    public function __construct()
    {
    }
}
