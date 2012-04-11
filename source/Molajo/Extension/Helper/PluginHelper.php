<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Application\Helper;


defined('MOLAJO') or die;

/**
 * Plugin
 *
 * @package   Molajo
 * @subpackage  Helper
 * @since       1.0
 */
abstract class PluginHelper
{
    /**
     * get
     *
     * Retrieve plugin data
     *
     * @return  array
     * @since   1.0
     */
    static public function get($name)
    {
        $rows = ExtensionHelper::get(
            ASSET_TYPE_EXTENSION_PLUGIN,
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
     * Return path for selected plugin
     *
     * @return bool|string
     * @since 1.0
     */
    static public function getPath($name)
    {
        return EXTENSIONS_PLUGINS . '/' . $name;
    }
}
