<?php
/**
 * @package     Molajo
 * @subpackage  API
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Application\Helper;


defined('MOLAJO') or die;

/**
 * File
 *
 * @package     Molajo
 * @subpackage  Helper
 * @since       1.0
 */
class FileloaderHelper
{

    /**
     * requireClassFile
     *
     * @param string $file
     * @param string $class
     *
     * @return Boolean
     */
    function requireClassFile($file, $class)
    {
        echo JPATH_PLATFORM . '/loader.php';

        if (substr(basename($file), 0, 4) == 'HOLD') {
            return true;
        }
        if (class_exists($class)) {
            return true;
        }

        /** Dashes/Underscores removed, next word uppercased for class name */
        if (strpos($class, '_') === false) {
        } else {
            $classArray = explode('_', $class);
            $class = '';
            foreach ($classArray as $item) {
                $class .= ucfirst($item);
            }
        }

        if (file_exists($file)) {
            JLoader::register($class, $file);
        } else {
            echo 'LoadHelper Error: File not found ' . $file . ' for Class: ' . $class;
            exit;
        }

        if (class_exists($class)) {
            //            $api = MOLAJO_BASE_FOLDER.'/api.txt';
            //            $apiFile = fopen($api, 'a') or die("Cannot find API file.");
            //            $apiData = $class.chr(10);
            //            fwrite($apiFile, $apiData);
            //            fclose($apiFile);
            return true;
        } else {
            echo 'LoadHelper Error: Class not found ' . $class;
            exit;
        }
    }
}
