<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension\Trigger\UserFields;

use Molajo\Extension\Trigger\Content\ContentTrigger;

defined('MOLAJO') or die;

/**
 * User Fields
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class UserFieldsTrigger extends ContentTrigger
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
			self::$instance = new UserFieldsTrigger();
		}
		return self::$instance;
	}

	/**
	 * Pre-create processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onBeforeCreate()
	{
		return false;
	}

	/**
	 * Post-create processing
	 *
	 * @param $this->query_results, $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onAfterCreate()
	{
		return false;
	}

	/**
	 * Pre-read processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onBeforeRead()
	{
		return false;
	}

	/**
	 * Post-read processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onAfterRead()
	{
		return false;
	}

	/**
	 * Pre-update processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onBeforeUpdate()
	{
		return false;
	}

	/**
	 * Post-update processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onAfterUpdate()
	{
		return false;
	}

	/**
	 * Pre-delete processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onBeforeDelete()
	{
		return false;
	}

	/**
	 * Post-read processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onAfterDelete()
	{
		return false;
	}
}
