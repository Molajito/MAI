<?php
/**
 * @package     Molajo
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Extension\Helper;

defined('MOLAJO') or die;

use Molajo\Extension\Helper\ExtensionHelper;

/**
 * Component
 *
 * @package     Molajo
 * @subpackage  Component
 * @since       1.0
 */
abstract class ComponentHelper
{
    /**
     * get
     *
     * @return  array
     * @since   1.0
     */
    static public function get($name)
    {
        $rows = ExtensionHelper::get(
            MOLAJO_ASSET_TYPE_EXTENSION_COMPONENT,
            $name
        );
        if (count($rows) == 0) {
            return array();
        }

        foreach ($rows as $row) {
        }

        return $row;
    }

    /**
     * getPath
     *
     * Return path for selected Component
     *
     * @return bool|string
     * @since 1.0
     */
    static public function getPath($name)
    {
        return MOLAJO_EXTENSIONS_COMPONENTS . '/' . $name;
    }
}
