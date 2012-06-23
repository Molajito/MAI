<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension\Trigger\Checkout;

use Molajo\Extension\Trigger\Content\ContentTrigger;

defined('MOLAJO') or die;
/**
 * Checkout
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class CheckoutTrigger extends ContentTrigger
{

	/**
	 * Pre-update processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onBeforeUpdate()
	{
		// verify user has rights to update
		// and that no one else has it updated
		// if so, check checkout date and user
		return false;
	}

	/**
	 * Pre-delete processing
	 *
	 * @param   $this->query_results
	 * @param   $model
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onBeforeDelete()
	{
		// verify user has rights to delete
		// and that no one else has it checked out
		// if so, allow, else cancel
		return false;
	}
}
