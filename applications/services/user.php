<?php
/**
 * @package     Molajo
 * @subpackage  Service
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * User Class
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
class MolajoUserService
{
    /**
     * Instance
     *
     * @var    object
     * @since  1.0
     */
    protected static $instances = array();

    /**
     * $storage
     *
     * @since  1.0
     * @var object
     */
    protected $storage;

    /**
     * $model
     *
     * @since  1.0
     * @var object
     */
    protected $model = 'MolajoUsersModel';

    /**
     * getInstance
     *
     * @param   string $identifier  Requested User (id or username)
     *
     * @return  object  User
     * @since   1.0
     */
    public static function getInstance($id = 0)
    {
        return;
        if (is_numeric($id)) {
        } else {
            $id = MolajoUserHelper::getId($id);
        }
        if (empty(self::$instances[$id])) {
            $user = new MolajoUserService($id);
            self::$instances[$id] = $user;
        }
        return self::$instances[$id];
    }

    /**
     * __construct
     *
     * @param   integer  $identifier
     *
     * @return  object
     * @since   1.0
     */
    protected function __construct($id = 0)
    {
        return;
        $this->id = (int) $id;
        $this->storage = Services::Request()->getSession();

        if ((int) $id == 0) {
            return $this->_loadGuest();
        } else {
            return $this->_load();
        }
    }

    /**
     * get
     *
     * Retrieves values, or establishes the value with a default,
     * if not available
     *
     * @param  string  $key
     * @param  string  $default
     *
     * @return  mixed
     * @since   1.0
     */
    public function get($key, $default)
    {
        $this->storage->get($key, $default);
    }

    /**
     * set
     *
     * Modifies a property, creating it and establishing
     * a default if not existing
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return  mixed
     * @since   1.0
     */
    public function set($key, $value = null)
    {
        return $this->storage->set($key, $value);
    }

    /**
     * load
     *
     * Retrieve User or Guest Information
     *
     * @param   mixed  $id either the numeric userid or character username
     *
     * @return  boolean
     * @since   1.0
     */
    protected function _load()
    {
        $this->model = new MolajoUsersModel ($this->id);

        $results = $this->model->load();
        if ($results == false) {
            $this->guest = true;
            return $this->_loadGuest();
        }
        $columns = $this->model->getFields();

        foreach ($results as $name => $value) {
            $this->set($name, $value);
        }

        $custom_fields = new Registry;
        $custom_fields->loadString($this->custom_fields);
        $this->set('custom_fields', $custom_fields);

        $metadata = new Registry;
        $metadata->loadString($this->metadata);
        $this->set('metadata', $metadata);

        $parameters = new Registry;
        $parameters->loadString($this->parameters);
        $this->set('parameters', $parameters);
/**
        echo '<pre>';
        var_dump($this->parameters);
        echo '</pre>';
*/
        return $this;
    }

    /**
     * _loadGuest
     *
     * Set Guest values for visitor not logged on
     *
     * @return  boolean
     * @since   1.0
     */
    protected function _loadGuest()
    {
        $this->model = new MolajoUsersModel (0);
        var_dump($this->model);

        $columns = $this->model->getFields();

        foreach ($columns as $name => $value) {
            $this->set($name, '');
        }
        $this->set('id', 0);
        $this->set('asset_type_id', MOLAJO_ASSET_TYPE_USER);

        $parameters = new Registry;
        $parameters->loadString(
            Service::Configuration()->get('guest_parameters', '{}')
        );

        $this->set('applications', array());
        $this->set('groups', array(MOLAJO_SYSTEM_GROUP_PUBLIC, MOLAJO_SYSTEM_GROUP_GUEST));
        $this->set('view_groups', array(MOLAJO_SYSTEM_GROUP_PUBLIC, MOLAJO_SYSTEM_GROUP_GUEST));
        $this->set('public', 1);
        $this->set('guest', 1);
        $this->set('registered', 0);
        $this->set('administrator', 0);
    }
}
