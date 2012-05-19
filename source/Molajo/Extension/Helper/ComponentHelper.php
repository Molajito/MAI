<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Extension\Helper;

use Molajo\Extension\Helpers;

defined('MOLAJO') or die;

/**
 * ComponentHelper
 *
 * @package       Molajo
 * @subpackage    Helper
 * @since         1.0
 */
Class ComponentHelper
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
			self::$instance = new ComponentHelper();
		}
		return self::$instance;
	}

	/**
	 * Get Component ID with the Name
	 *
	 * @param $name
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function get($name)
	{
		$row = Helpers::Extension()->getInstanceID(CATALOG_TYPE_EXTENSION_COMPONENT, $name);

		if (count($row) == 0) {
			return array();
		}
		return $row;
	}

	/**
	 * getPath
	 *
	 * Return path for selected Component
	 *
	 * @return bool|string
	 * @since 1.0
	 */
	public function getPath($name)
	{
		return EXTENSIONS_COMPONENTS . '/' . $name;
	}
}
