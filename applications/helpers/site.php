<?php
/**
 * @package     Molajo
 * @subpackage  Helper
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * Site
 *
 * @package     Molajo
 * @subpackage  Helper
 * @since       1.0
 */
class MolajoSiteHelper
{
    /**
     * @var null $_sites
     *
     * @since 1.0
     */
    protected static $_sites = null;

    /**
     * get
     *
     * Retrieves Site info from database
     *
     * This method will return a site information array if called
     * with no arguments which can be used to add custom site information.
     *
     * @param   integer  $id        A site identifier, can be ID or Name
     * @param   boolean  $byName    If True, find the site by its name
     *
     * @return  boolean  True if the information is added. False on error
     * @since   1.0
     */
    public static function get($id = null)
    {
        if ($id == null) {
            $id = MOLAJO_SITE_ID;
        }

        if (self::$_sites === null) {

            $obj = new stdClass();

            $db = Molajo::Services()->connect('jdb');
            $query = $db->getQuery(true);
            $now = Services::Date()->toMySQL();
            $nullDate = $db->getNullDate();

            $query->select($db->quoteName('id'));
            $query->select($db->quoteName('name'));
            $query->select($db->quoteName('description'));
            $query->select($db->quoteName('path'));
            $query->select($db->quoteName('parameters'));
            $query->select($db->quoteName('custom_fields'));
            $query->select($db->quoteName('metadata'));
            $query->select($db->quoteName('base_url'));
            $query->from($db->quoteName('#__sites'));

            $db->setQuery($query->__toString());

            $results = $db->loadObjectList();

            if ($db->getErrorNum()) {
                return new MolajoException($db->getErrorMsg());
            }

            foreach ($results as $result) {

                $obj->id = $result->id;
                $obj->name = $result->name;
                $obj->description = $result->description;
                $obj->path = $result->path;
                $obj->parameters = $result->parameters;
                $obj->metadata = $result->metadata;
                $obj->custom_fields = $result->custom_fields;
                $obj->base_url = $result->base_url;

                self::$_sites[$result->id] = clone $obj;
            }
        }

        if (isset(self::$_sites[$id])) {
            return self::$_sites[$id];
        }

        return null;
    }

    /**
     * getApplications
     *
     * Retrieves Applications for which the site is authorized to see
     *
     * @param   integer  $id        A site id
     *
     * @return  array
     * @since   1.0
     */
    public static function getApplications($id = null)
    {
        $db = Molajo::Services()->connect('jdb');
        $query = $db->getQuery(true);
        $now = Services::Date()->toMySQL();
        $nullDate = $db->getNullDate();

        if ($id == null) {
            $id = MOLAJO_SITE_ID;
        }

        $query->select($db->quoteName('application_id'));
        $query->from($db->quoteName('#__site_applications'));
        $query->where($db->quoteName('site_id') . ' = ' . (int) $id);

        $db->setQuery($query->__toString());

        $results = $db->loadObjectList();

        if ($db->getErrorNum()) {
            return new MolajoException($db->getErrorMsg());
        }

        return $results;
    }
}
