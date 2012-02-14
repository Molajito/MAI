<?php
/**
 * @package     Molajo
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Display
 *
 * @package     Molajo
 * @subpackage  Model
 * @since       1.0
 */
class MolajoDisplayModel extends MolajoModel
{
    /**
     * __construct
     *
     * Constructor.
     *
     * @param  $id
     * @since  1.0
     */
    public function __construct($id = null)
    {
        if (isset($this->name)) {
        } else {
            $this->name = get_class($this);
        }

        return parent::__construct($id);
    }
}
