<?php
/**
 * @package     Molajo
 * @subpackage  Helper
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * Extension
 *
 * @package     Molajo
 * @subpackage  Helper
 * @since       1.0
 */
abstract class MolajoExtensionHelper
{
    /**
     * get
     *
     * Retrieves Extension data from the extension and extension instances
     * Verifies access for user, application and site
     *
     * @param   $asset_type_id
     * @param   $extension
     *
     * @return  bool|mixed
     * @since   1.0
     */
    public static function get($asset_type_id = 0, $extension = null)
    {
        $m = new MolajoDisplayModel();

        /**
         *  a. Extensions Instances Table
         */
        $m->query->select('a.' . $m->db->nq('id') . ' as extension_instance_id');
        $m->query->select('a.' . $m->db->nq('asset_type_id'));
        $m->query->select('a.' . $m->db->nq('title'));
        $m->query->select('a.' . $m->db->nq('subtitle'));
        $m->query->select('a.' . $m->db->nq('alias'));
        $m->query->select('a.' . $m->db->nq('content_text'));
        $m->query->select('a.' . $m->db->nq('protected'));
        $m->query->select('a.' . $m->db->nq('featured'));
        $m->query->select('a.' . $m->db->nq('stickied'));
        $m->query->select('a.' . $m->db->nq('status'));
        $m->query->select('a.' . $m->db->nq('custom_fields'));
        $m->query->select('a.' . $m->db->nq('parameters'));
        $m->query->select('a.' . $m->db->nq('metadata'));
        $m->query->select('a.' . $m->db->nq('ordering'));
        $m->query->select('a.' . $m->db->nq('language'));

        $m->query->from($m->db->nq('#__extension_instances') . ' as a');
        $m->query->where('a.' . $m->db->nq('extension_id') . ' > 0 ');

        /** extension specified by id, title or request for list */
        if ((int)$extension > 0) {
            $m->query->where('(a.' . $m->db->nq('id') .
                    ' = ' . (int)$extension . ')'
            );
        } else if ($extension == null) {
        } else {
            $m->query->where('(a.' . $m->db->nq('title') .
                    ' = ' . $m->db->q($extension) . ')'
            );

        }
        if ((int)$asset_type_id > 0) {
            $m->query->where('a.' . $m->db->nq('asset_type_id') .
                    ' = ' . (int)$asset_type_id
            );
        }

        $m->query->where('a.' . $m->db->nq('status') .
                ' = ' . MOLAJO_STATUS_PUBLISHED
        );
        $m->query->where('(a.start_publishing_datetime = ' .
                $m->db->q($m->nullDate) .
                ' OR a.start_publishing_datetime <= ' . $m->db->q($m->now) . ')'
        );
        $m->query->where('(a.stop_publishing_datetime = ' .
                $m->db->q($m->nullDate) .
                ' OR a.stop_publishing_datetime >= ' . $m->db->q($m->now) . ')'
        );

        /** Assets Join and View Access Check */
        Services::Access()
            ->setQueryViewAccess(
            $m->query,
            array('join_to_prefix' => 'a',
                'join_to_primary_key' => 'id',
                'asset_prefix' => 'b_assets',
                'select' => true
            )
        );

        /** b_asset_types. Asset Types Table  */
        $m->query->select($m->db->nq('b_asset_types.title') . ' as asset_type_title');
        $m->query->from($m->db->nq('#__asset_types') . ' as b_asset_types');
        $m->query->where('b_assets.asset_type_id = b_asset_types.id');
        $m->query->where('b_asset_types.' .
                $m->db->nq('component_option') .
                ' = ' . $m->db->q('extensions')
        );

        /**
         *  c. Application Table
         *      Extension Instances must be enabled for the Application
         */
        $m->query->from($m->db->nq('#__application_extension_instances') .
            ' as c');
        $m->query->where('c.' . $m->db->nq('extension_instance_id') .
            ' = a.' . $m->db->nq('id'));
        $m->query->where('c.' . $m->db->nq('application_id') .
            ' = ' . MOLAJO_APPLICATION_ID);

        /**
         *  d. Site Table
         *      Extension Instances must be enabled for the Site
         */
        $m->query->from($m->db->nq('#__site_extension_instances') .
            ' as d');
        $m->query->where('d.' . $m->db->nq('extension_instance_id') .
            ' = a.' . $m->db->nq('id'));
        $m->query->where('d.' . $m->db->nq('site_id') .
            ' = ' . SITE_ID);

        /**
         *  Run Query
         */
        $extensions = $m->runQuery();

        return $extensions;
    }

    /**
     * getInstanceID
     *
     * Retrieves Extension ID, given title
     *
     * @static
     *
     * @param  $asset_type_id
     * @param  $title
     *
     * @return  bool|mixed
     * @since   1.0
     */
    public static function getInstanceID($asset_type_id, $title)
    {
        $m = new MolajoExtensionInstancesModel();

        $m->query->select('a.' . $m->db->nq('id'));
        $m->query->where('a.' . $m->db->nq('title') . ' = ' .
            $m->db->q($title));
        $m->query->where('a.' . $m->db->nq('asset_type_id') .
            ' = ' . (int)$asset_type_id);

        return $m->loadResult();
    }

    /**
     * getInstanceTitle
     *
     * Retrieves Extension Name, given the extension_instance_id
     *
     * @static
     * @param   $extension_instance_id
     *
     * @return  bool|mixed
     * @since   1.0
     */
    public static function getInstanceTitle($extension_instance_id)
    {
        $m = new MolajoExtensionInstancesModel();

        $m->query->select('a.' . $m->db->nq('title'));
        $m->query->where('a.' . $m->db->nq('id') .
            ' = ' . (int)$extension_instance_id);
        return $m->loadResult();
    }

    /**
     * formatNameForClass
     *
     * Extension names can include dashes (or underscores). This method
     * prepares the name for use as a component of a classname.
     *
     * @param $extension_name
     *
     * @return string
     * @since  1.0
     */
    public function formatNameForClass($extension_name)
    {
        return ucfirst(str_replace(array('-', '_'), '', $extension_name));
    }

    /**
     * mergeParameters
     *
     * Page Request object that will be populated by this class
     * with overall processing requirements for the page
     *
     * Access via Molajo::Request()->get('property')
     *
     * @param   Registry $parameters
     *
     * @return  null
     * @since  1.0
     */
    public function mergeParameters($merge_in_parameters, $merged_parameters)
    {
        $mergeIn = $merge_in_parameters->toArray();

        while (list($name, $value) = each($mergeIn)) {
            if (isset($this->merged_parameters[$name])) {
            } else if (substr($name, 0, strlen('extension')) == 'extension') {
            } else if (substr($name, 0, strlen('extension')) == 'source') {
            } else if (substr($name, 0, strlen('theme')) == 'theme') {
            } else if (substr($name, 0, strlen('page')) == 'page') {
            } else if (substr($name, 0, strlen('template')) == 'template') {
            } else if (substr($name, 0, strlen('wrap')) == 'wrap') {
            } else if (substr($name, 0, strlen('default')) == 'default') {
            } else if ($name == 'controller'
                || $name == 'task'
                || $name == 'model'
            ) {
            } else {
                $merged_parameters[$name] = $value;
            }
        }

        return $merged_parameters;
    }

    /**
     * getPath
     *
     * Return path for Extension
     *
     * @return bool|string
     * @since 1.0
     */
    static public function getPath($asset_type_id, $name)
    {
        if ($asset_type_id == MOLAJO_ASSET_TYPE_EXTENSION_COMPONENT) {
            return ComponentHelper::getPath($name);
        } else if ($asset_type_id == MOLAJO_ASSET_TYPE_EXTENSION_MODULE) {
            return ModuleHelper::getPath($name);
        } else if ($asset_type_id == MOLAJO_ASSET_TYPE_EXTENSION_THEME) {
            return ThemeHelper::getPath($name);
        } else if ($asset_type_id == MOLAJO_ASSET_TYPE_EXTENSION_PLUGIN) {
            return PluginHelper::getPath($name);
        }
    }

    /**
     * loadLanguage
     *
     * Loads Language Files for extension
     *
     * @return  null
     * @since   1.0
     */
    public static function loadLanguage($path)
    {
        $path .= '/language';

        if (JFolder::exists($path)) {
        } else {
            return false;
        }

        Services::Language()
            ->load($path,
            Services::Language()->get('tag'),
            false,
            false
        );
    }
}
