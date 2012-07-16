<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
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
     * @param   $this->data
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
        return true;
    }

    /**
     * Pre-delete processing
     *
     * @param   $this->data
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
        return true;
    }

    /**
     * verifyCheckout
     *
     * Checks that the current user is the checked_out user for item
     *
     * @return boolean
     * @since   1.0
     */
    public function verifyCheckout()
    {
        if ($this->get('id') == 0) {
            return true;
        }

        if (property_exists($this->model, 'checked_out')) {
        } else {
            return true;
        }
// or super admin
        if ($this->model->checked_out == Services::Registry()->get('User', 'id')) {

        } else {
            // redirect error
            return false;
        }

        return true;
    }

    /**
     * checkoutItem
     *
     * method to set the checkout_time and checked_out values of the item
     *
     * @return boolean
     * @since    1.0
     */
    public function checkoutItem()
    {
        if ($this->get('id') == 0) {
            return true;
        }

        if (property_exists($this->model, 'checked_out')) {
        } else {
            return true;
        }

        $results = $this->model->checkout($this->get('id'));
        if ($results === false) {
            // redirect error
            return false;
        }

        return true;
    }

}
