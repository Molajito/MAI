<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Application\Helper;

use Molajo\Extension\Helper\ExtensionHelper;

defined('MOLAJO') or die;

/**
 * Module
 *
 * @package   Molajo
 * @subpackage  Helper
 * @since       1.0
 */
abstract class ModuleHelper
{
    /**
     * get
     *
     * Retrieve module data
     *
     * @return  array
     * @since   1.0
     */
    static public function get($name)
    {
        $rows = ExtensionHelper::get(
            MOLAJO_ASSET_TYPE_EXTENSION_MODULE,
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
     * Return path for selected Module
     *
     * @return bool|string
     * @since 1.0
     */
    static public function getPath($name)
    {
        return MOLAJO_EXTENSIONS_MODULES . '/' . $name;
    }
}
