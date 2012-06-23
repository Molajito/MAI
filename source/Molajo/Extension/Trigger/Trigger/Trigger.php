<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension\Trigger\Trigger;

defined('MOLAJO') or die;

/**
 * Trigger
 *
 * Base class for Model Triggers
 *
 * @package     Molajo
 * @subpackage  Model
 * @since       1.0
 */
class Trigger
{

    /**
     * Pre-create processing
     *
     * @param   $this->query_results
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeCreate()
    {
        return true;
    }

    /**
     * Post-create processing
     *
     * @param $this->query_results, $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterCreate()
    {
        return true;
    }

    /**
     * Pre-read processing
     *
     * @param   $this->query_results
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeRead()
    {
        return true;
    }

    /**
     * Post-read processing
     *
     * @param   $this->query_results
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRead()
    {
        return true;
    }

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
        return true;
    }

    /**
     * Post-update processing
     *
     * @param   $this->query_results
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterUpdate()
    {
        return true;
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
        return true;
    }

    /**
     * Post-read processing
     *
     * @param   $this->query_results
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterDelete()
    {
        return true;
    }
}
