<?php
/**
 * @package     Molajo
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Sessions
 *
 * @package     Molajo
 * @subpackage  Model
 * @since       1.0
 */
class MolajoSessionsModel extends MolajoCrudModel
{
    /**
     * __construct
     *
     * @param  $id
     *
     * $return object
     * @since  1.0
     */
    public function __construct($id = null)
    {
        $this->name = get_class($this);
        $this->table = '#__sessions';
        $this->primary_key = 'session_id';

        parent::__construct($id);
    }
}
