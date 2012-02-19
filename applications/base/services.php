<?php
/**
 * @package     Molajo
 * @subpackage  Services
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Services
 *
 * @package     Molajo
 * @subpackage  Services
 * @since       1.0
 */
class MolajoServices
{
    /**
     * Static instance
     *
     * @var    object
     * @since  1.0
     */
    protected static $instance;

    /**
     * Service Connections
     *
     * @var object
     * @since 1.0
     */
    protected $service_connection;

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
            self::$instance = new MolajoServices();
        }
        return self::$instance;
    }

    /**
     * __construct
     *
     * Class constructor.
     *
     * @return boolean
     * @since  1.0
     */
    protected function __construct()
    {

    }

    /**
     * get
     *
     * Retrieves service key value pair
     *
     * @param  string  $key
     * @param  string  $default
     *
     * @return  mixed
     *
     * @since   1.0
     */
    public function get($key, $default = null)
    {
        return $this->service_connection->get($key, $default);
    }

    /**
     * set
     *
     * Stores the service connection
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return  mixed
     * @since   1.0
     */
    public function set($key, $value = null)
    {
        $this->service_connection->set($key, $value);
    }

    /**
     * initiateServices
     *
     * loads all services defined in the services.xml file
     *
     * @param null|Registry $config
     *
     * @return mixed
     * @since 1.0
     */
    public function initiateServices()
    {
        $services = simplexml_load_file(
            MOLAJO_APPLICATIONS . '/options/services.xml'
        );
        if (count($services) == 0) {
            return;
        }
        $this->service_connection = new Registry();

        foreach ($services->service as $s) {
            $serviceName = (string)$s->name;
            $connection = $this->_connectService($s);
            $this->set($serviceName, $connection);
        }

        return;
    }

    /**
     * connectService
     *
     * @param   $service
     * @return  bool
     * @since   1.0
     */
    protected function _connectService($service)
    {
        $serviceName = (string)$service->name;

        if (trim($serviceName) == '') {
            return false;
        }
        if (substr($serviceName, 0, 4) == 'HOLD') {
            return false;
        }

        $serviceClass = (string)$service->serviceClass;
        if (trim($serviceClass == '')) {
            $serviceClass = 'Molajo' . ucfirst($serviceName) . 'Service';
        }

        /** execute the getInstance method */
        $getInstanceConnection = false;
        if (method_exists($serviceClass, 'getInstance')) {

            /** connect Method Parameters */
            $getInstanceParameters = array();
            if (isset($service->getInstance->parameters->parameter)) {
                foreach ($service->getInstance->parameters->parameter as $p) {
                    $name = (string)$p['key'];
                    $value = (string)$p['value'];
                    $getInstanceParameters[$name] = $value;
                }
            }

            $getInstanceConnection = $this->_connectServiceMethod(
                null,
                $serviceClass,
                'getInstance',
                $getInstanceParameters
            );

            if ($getInstanceConnection == false) {
                return false;
            }
        }

        /** execute the connect method */
        if (method_exists($serviceClass, 'connect')) {

            /** connect Method Parameters */
            $connectParameters = array();
            if (isset($service->connect->parameters->parameter)) {
                foreach ($service->connect->parameters->parameter as $p) {
                    $name = (string)$p['key'];
                    $value = (string)$p['value'];
                    $connectParameters[$name] = $value;
                }
            }

            $connection = $this->_connectServiceMethod(
                $getInstanceConnection,
                $serviceClass,
                'connect',
                $connectParameters
            );

            if ($connection == false) {
                return false;
            } else {
                return $connection;
            }
        } else {
            return $getInstanceConnection;
        }
    }

    /**
     * _connectServiceMethod
     *
     * Execute the Service Method
     *
     * $param $objectContext
     * @param $serviceClass
     * @param $serviceMethod
     * @param $connectParameters
     *
     * @since 1.0
     */
    protected function _connectServiceMethod(
        $objectContext = null,
        $serviceClass,
        $serviceMethod,
        $connectParameters)
    {
        /** parameters from array to string */
        $parms = '';
        if (count($connectParameters) == 0) {
        } else {
            foreach ($connectParameters as $key => $value) {
                if ($parms !== '') {
                    $parms .= ',';
                }
                if ($value == '{{userid}}') {
                    $value = 42;
                }
                $parms .= '$' . $key . '="' . $value . '"';
            }
        }

        /** execute method */
        $connection = '';
        if ($serviceMethod == 'getInstance') {
            $execute = '$connection = $serviceClass::getInstance '.
                  '(' . $parms . ');';
        } else {
            $execute = '$connection = $objectContext->' .
                $serviceMethod .
                '(' . $parms . ');';
        }
        eval($execute);

        return $connection;
    }
}
