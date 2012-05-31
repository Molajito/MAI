<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension\Trigger\Createdby;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Createdby
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class CreatedbyTrigger extends ContentTrigger
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
            self::$instance = new CreatedbyTrigger();
        }

        return self::$instance;
    }

    /**
     * After-read processing
     *
     * Retrieves Createdby Information for Item
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRead()
    {
        /** Retrieve created_by field definition */
		$field = $this->getField('created_by');
		if ($field == false) {
			return;
		}

		/** Retrieve the current value for created by field */
		$fieldValue = $this->getFieldValue($field);
		if ((int) $fieldValue == 0) {
			return;
		}

        /** Using the created_by value, retrieve the Createdby Profile Data */
        $controllerClass = 'Molajo\\MVC\\Controller\\ModelController';
        $m = new $controllerClass();
        $m->connect('Users');

        $m->set('id', (int) $fieldValue);
		$m->set('get_item_children', 0);

		$item = $m->getData('item');
		if ($item == false || count($item) == 0) {
			throw new \RuntimeException ('User load() query problem');
		}

		/** Save each field */
		foreach (get_object_vars($item) as $key => $value) {

            if (substr($key, 0, strlen('item_')) == 'item_'
                || substr($key, 0, strlen('form_')) == 'form_'
                || substr($key, 0, strlen('list_')) == 'list_'
                || substr($key, 0, strlen('password')) == 'password') {
            } else {
				$new_field_name = 'author' . '_' . $key;
				$this->saveField($field, $new_field_name, $value);
            }
        }

        return true;
    }
}
