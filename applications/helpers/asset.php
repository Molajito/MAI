<?php
/**
 * @package     Molajo
 * @subpackage  Helper
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * Asset
 *
 * @package     Molajo
 * @subpackage  Helper
 * @since       1.0
 */
abstract class MolajoAssetHelper
{
    /**
     * getAsset
     *
     * Retrieve Asset and Asset Type for specific id or query request
     *
     * View Access is verified in Molajo::Request to identify 403 errors
     *
     * @param    int  $asset_id
     * @param    null $query_request
     *
     * @results  object
     * @since    1.0
     */
    public static function get($asset_id = 0, $query_request = null)
    {
        $m = new MolajoAssetsModel();

        $m->query->select('a.' . $m->db->qn('id') . ' as asset_id');
        $m->query->select('a.' . $m->db->qn('asset_type_id'));
        $m->query->select('a.' . $m->db->qn('source_id'));
        $m->query->select('a.' . $m->db->qn('routable'));
        $m->query->select('a.' . $m->db->qn('sef_request'));
        $m->query->select('a.' . $m->db->qn('request'));
        $m->query->select('a.' . $m->db->qn('request_option'));
        $m->query->select('a.' . $m->db->qn('request_model'));
        $m->query->select('a.' . $m->db->qn('redirect_to_id'));
        $m->query->select('a.' . $m->db->qn('view_group_id'));
        $m->query->select('a.' . $m->db->qn('primary_category_id'));
        $m->query->select('b.' . $m->db->qn('source_table'));

        $m->query->from($m->db->qn('#__assets') . ' as a');
        $m->query->from($m->db->qn('#__asset_types') . ' as b');

        $m->query->where('a.' . $m->db->qn('asset_type_id') .
            ' = b.' . $m->db->qn('id'));

        if ((int)$asset_id == 0) {
            $m->query->where('(a.' . $m->db->qn('sef_request') .
                    ' = ' . $m->db->q($query_request) .
                    ' OR a.' . $m->db->qn('request') . ' = ' .
                    $m->db->q($query_request) . ')'
            );
        } else {
            $m->query->where('a.' . $m->db->qn('id') . ' = ' .
                    (int)$asset_id
            );
        }

        $rows = $m->runQuery();

        if (count($rows) == 0) {
            return array();
        }

        $row = array();
        foreach ($rows as $row) {

            if ((int)$asset_id == 0) {

                if (Services::Configuration()->get('sef', 1) == 1) {
                    if ($row->sef_request == $query_request) {

                    } else {
                        $row->redirect_to_id = (int)$row->asset_id;
                    }

                } else {
                    if ($row->request == $query_request) {

                    } else {
                        $row->redirect_to_id = (int)$row->asset_id;
                    }
                }

                if ($row->asset_id ==
                    Services::Configuration()->get('home_asset_id', 0)
                ) {
                    if ($query_request == '') {
                    } else {
                        $row->redirect_to_id =
                            Services::Configuration()->get('home_asset_id', 0);
                    }
                }
            }
        }

        return $row;
    }

    /**
     * getID
     *
     * Retrieves Asset ID
     *
     * @param  null $asset_type_id
     * @param  null $source_id
     *
     * @return bool|mixed
     * @since  1.0
     */
    public static function getID($asset_type_id, $source_id)
    {
        $m = new MolajoAssetsModel();

        $m->query->select('a.' . $m->db->qn('id') . ' as asset_id');
        $m->query->where('a.' . $m->db->qn('asset_type_id') .
            ' = ' . (int)$asset_type_id);
        $m->query->where('a.' . $m->db->qn('source_id') .
            ' = ' . (int)$source_id);
        $m->query->where('a.' . $m->db->qn('view_group_id') .
                ' IN (' .
                implode(',', Services::User()->get('view_groups')) . ')'
        );

        return $m->loadResult();
    }

    /**
     * getURL
     *
     * Retrieves URL based on Asset ID
     *
     * @param  null $asset_id
     *
     * @return string
     * @since  1.0
     */
    public static function getURL($asset_id)
    {
        /** home */
        if ($asset_id == Services::Configuration()->get('home_asset_id', 0)) {
            return '';
        }

        $m = new MolajoAssetsModel();

        if (Services::Configuration()->get('sef', 1) == 1) {
            $m->query->select('a.' . $m->db->qn('sef_request'));
        } else {
            $m->query->select('a.' . $m->db->qn('request'));
        }
        $m->query->where('a.' . $m->db->qn('id') . ' = ' . (int)$asset_id);
        $m->query->where('a.' . $m->db->qn('view_group_id') .
                ' IN (' .
                implode(',', Services::User()->get('view_groups')) . ')'
        );

        return $m->loadResult();
    }
}
