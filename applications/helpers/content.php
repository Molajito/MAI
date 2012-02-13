<?php
/**
 * @package     Molajo
 * @subpackage  Helper
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * Content
 *
 * @package     Molajo
 * @subpackage  Helper
 * @since       1.0
 */
abstract class MolajoContentHelper
{
    /**
     * get
     *
     * Get the content data for the id specified
     *
     * @return  mixed    An object containing an array of data
     * @since   1.0
     */
    static public function get($id, $content_table)
    {
        $db = Services::DB();
        $query = $db->getQuery(true);
        $now = Services::Date()->getDate()->toSql();
        $nullDate = $db->getNullDate();
        $table_name = Services::Configuration()->get('dbprefix').$content_table;

        $query->select('a.' . $db->namequote('id'));
        $query->select('a.' . $db->namequote('extension_instance_id'));
        $query->select('a.' . $db->namequote('asset_type_id'));
        $query->select('a.' . $db->namequote('title'));
        $query->select('a.' . $db->namequote('subtitle'));
        $query->select('a.' . $db->namequote('path'));
        $query->select('a.' . $db->namequote('alias'));
        $query->select('a.' . $db->namequote('status'));
        $query->select('a.' . $db->namequote('start_publishing_datetime'));
        $query->select('a.' . $db->namequote('stop_publishing_datetime'));
        $query->select('a.' . $db->namequote('modified_datetime'));
        $query->select('a.' . $db->namequote('custom_fields'));
        $query->select('a.' . $db->namequote('parameters'));
        $query->select('a.' . $db->namequote('metadata'));
        $query->select('a.' . $db->namequote('language'));
        $query->select('a.' . $db->namequote('translation_of_id'));
        $query->select('a.' . $db->namequote('ordering'));
        $query->from('#__content as a ');
        $query->where('a.' . $db->namequote('id') .
            ' = ' . (int)$id);
        $query->where('a.' . $db->namequote('status') .
            ' = ' . MOLAJO_STATUS_PUBLISHED);

        $query->where('(a.start_publishing_datetime = ' .
                $db->quote($nullDate) .
                ' OR a.start_publishing_datetime <= ' .
                $db->quote($now) . ')'
        );
        $query->where('(a.stop_publishing_datetime = ' .
                $db->quote($nullDate) .
                ' OR a.stop_publishing_datetime >= ' .
                $db->quote($now) . ')'
        );

        /** Assets Join and View Access Check */
        MolajoAccessService::setQueryViewAccess(
            $query,
            array('join_to_prefix' => 'a',
                'join_toprimary_key' => 'id',
                'asset_prefix' => 'b_assets',
                'select' => true
            )
        );

        //$db->setQuery($query->__toString());
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        if ($db->getErrorNum() == 0) {

        } else {
            Services::Message()
                ->set(
                $message = Services::Language()->_('ERROR_DATABASE_QUERY') . ' ' .
                    $db->getErrorNum() . ' ' .
                    $db->getErrorMsg(),
                $type = MOLAJO_MESSAGE_TYPE_ERROR,
                $code = 500,
                $debug_location = 'ContentHelper::_get',
                $debug_object = $db
            );
            return $this->request->set('status_found', false);
        }

        if (count($rows) == 0) {
            return array();
        }

        foreach ($rows as $row) {
        }

        return $row;
    }
}
