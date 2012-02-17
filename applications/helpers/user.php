<?php
/**
 * @package     Molajo
 * @subpackage  Helper
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * User
 *
 * @package     Molajo
 * @subpackage  Helper
 * @since       1.0
 */
abstract class MolajoUserHelper
{
    /**
     * getId
     *
     * Retrieves User ID given the ID or Username
     *
     * @param   $id
     *
     * @return  bool|mixed
     * @since   1.0
     */
    public static function getId($id)
    {
        if ((int)$id == 0 && trim($id)) {
            return false;
        }

        $db = Services::DB();
        $query = $db->getQuery(true);
        $now = Services::Date()->getDate()->toSql();
        $nullDate = $db->getNullDate();

        $query->select('a.' . $db->nq('id') . ' as extension_instance_id');
        $query->from($db->nq('#__users') . ' as a');
        $query->where('(a.' . $db->nq('id') . ' = ' . (int)$id .
            ' OR a.username = ' . $db->q($id).')');
        $db->setQuery($query->__toString());
        $userid = $db->loadResult();

        if ($error = $db->getErrorMsg()) {
            MolajoError::raiseWarning(500, $error);
            return false;
        }
        return $userid;
    }
}

