<?php
/**
 * @package     Molajo
 * @subpackage  Model
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Assets
 *
 * @package     Molajo
 * @subpackage  Model
 * @since       1.0
 * @link
 */
class MolajoAssetsModel extends MolajoModel
{
    /**
     * The primary key of the asset.
     *
     * @var int
     */
    public $id = null;

    /**
     * content table associated with the asset.
     *
     * @var string
     */
    public $content_table = null;

    /**
     * @param database A database connector object
     */
    public function __construct($db)
    {
        parent::__construct('#__assets', 'id', $db);
    }

    /**
     * Save
     *
     * Method to provide a shortcut to binding, checking and storing a MolajoModel
     *
     * @param   mixed   An associative array or object to bind to the MolajoModel instance.
     * @param   string  Filter for the order updating
     * @param   mixed   An optional array or space separated list of properties
     *                    to ignore while binding.
     *
     * @return  boolean  True on success.
     * @since   1.0
     */
    public function save($source, $orderingFilter = '', $ignore = '')
    {
        if ($this->check()) {
        } else {
            return false;
        }

        if ($this->store()) {
        } else {
            return false;
        }

        $this->setError('');

        return true;
    }

    /**
     * check
     *
     * Check for necessary data
     *
     * @return  bool  True if the instance is sane and able to be stored in the database.
     *
     * @link    http://docs.molajo.org/MolajoModel/check
     * @since   1.0
     */
    public function check()
    {
        if ($this->content_table == null) {
            $this->setError(Services::Language()->_('ASSET_TABLE_MUST_HAVE_VALUE_FOR_CONTENT_TABLE'));
            return false;
        }
        return true;
    }
}
