<?php
/**
 * @package     Molajo
 * @subpackage  Installation Check
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

if (defined('MOLAJO_INSTALL_CHECK')) {
} else {
    define('MOLAJO_INSTALL_CHECK', false);
}

if (MOLAJO_APPLICATION == 'installation'
    || (MOLAJO_INSTALL_CHECK === false
        && file_exists(SITE_FOLDER_PATH . '/configuration.php'))
) {

} else {
    if (!file_exists(SITE_FOLDER_PATH . '/configuration.php')
        || filesize(SITE_FOLDER_PATH . '/configuration.php' < 10)) {

        $redirect = MOLAJO_BASE_URL . 'installation/';
        header('Location: ' . $redirect);
        exit();
    }
}
