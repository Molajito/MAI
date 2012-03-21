<?php
/**
 * @package	 	Molajo
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license	 	GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Application;

use Molajo\Application\Service\RequestService;
use Molajo\Extension\Helper;

defined('MOLAJO') or die;

/**
 * Molajo
 *
 * Creates instances of base classes
 */
class TestMolajo
{
    /**
     * Molajo::Application
     *
     * @var	object Application
     * @since  1.0
     */
    protected static $application = null;

    /**
     * Molajo::Request
     *
     * @var	object Request
     * @since  1.0
     */
    protected static $request = null;

    /**
     * Molajo::Service
     *
     * @var	object Service
     * @since  1.0
     */
    protected static $services = null;

    /**
     * Molajo::Parse
     *
     * @var	object Parse
     * @since  1.0
     */
    protected static $parse = null;

    /**
     * Molajo::Helper
     *
     * @var	object Helper
     * @since  1.0
     */
    protected static $helper = null;

    /**
     * Molajo::RequestService
     *
     * @var	object Parse
     * @since  1.0
     */
    protected static $request_service = null;

    /**
     * Molajo::Application
     *
     * @static
     * @return  Application
     * @since   1.0
     */
    public static function Application()
    {
        if (self::$application) {
        } else {
            self::$application = Application::getInstance();
        }

        return self::$application;
    }

    /**
     * Molajo::Services
     *
     * @static
     * @return  Services
     * @since   1.0
     */
    public static function Services()
    {
        if (self::$services) {
        } else {
            self::$services = Services::getInstance();
        }
        return self::$services;
    }

    /**
     * Molajo::Request
     *
     * @static
     * @param null $request
     * @param string $override_request_url
     * @param string $override_asset_id
     *
     * @return Request
     * @since 1.0
     */
    public static function Request()
    {
        if (self::$request) {
        } else {
            self::$request = Request::getInstance();
        }
        return self::$request;
    }

    /**
     * Molajo::Parse
     *
     * @static
     * @return  Parse
     * @since   1.0
     */
    public static function Parse()
    {
        if (self::$parse) {
        } else {
            self::$parse = Parse::getInstance();
        }
        return self::$parse;
    }

    /**
     * Molajo::Helper
     *
     * @static
     * @return  Parse
     * @since   1.0
     */
    public static function Helper()
    {
        if (self::$helper) {
        } else {
            self::$helper = Helper::getInstance();
        }
        return self::$helper;
    }

    /**
     * Molajo::RequestService
     *
     * @static
     * @return  Parse
     * @since   1.0
     */
    public static function RequestService()
    {
        if (self::$request_service) {
        } else {
            self::$request_service = RequestService::getInstance();
        }
        return self::$request_service;
    }
}