<?php
/**
 * @package     Molajo
 * @subpackage  Helper
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * Application Helper
 *
 * @package     Molajo
 * @subpackage  File System Helper
 * @since       1.0
 */
class MolajoFilesystemHelper
{
    /**
     * Support Functions; should probably live in a helper?
     */

    /**
     * Remote file size function for streams that don't support it
     *
     * @param   string  $url  TODO Add text
     *
     * @return  mixed
     *
     * @see        http://www.php.net/manual/en/function.filesize.php#71098
     * @since   1.0
     */
    function remotefsize($url)
    {
        $sch = parse_url($url, PHP_URL_SCHEME);

        if (($sch != 'http') && ($sch != 'https') && ($sch != 'ftp') && ($sch != 'ftps')) {
            return false;
        }

        if (($sch == 'http') || ($sch == 'https')) {
            $headers = get_headers($url, 1);

            if ((!array_key_exists('Content-Length', $headers))) {
                return false;
            }

            return $headers['Content-Length'];
        }

        if (($sch == 'ftp') || ($sch == 'ftps')) {
            $server = parse_url($url, PHP_URL_HOST);
            $port = parse_url($url, PHP_URL_PORT);
            $path = parse_url($url, PHP_URL_PATH);
            $user = parse_url($url, PHP_URL_USER);
            $pass = parse_url($url, PHP_URL_PASS);

            if ((!$server) || (!$path)) {
                return false;
            }

            if (!$port) {
                $port = 21;
            }

            if (!$user) {
                $user = 'anonymous';
            }

            if (!$pass) {
                $pass = '';
            }

            switch ($sch)
            {
                case 'ftp':
                    $ftpid = ftp_connect($server, $port);
                    break;

                case 'ftps':
                    $ftpid = ftp_ssl_connect($server, $port);
                    break;
            }

            if (isset($ftpid)) {
            } else {
                return false;
            }

            $login = ftp_login($ftpid, $user, $pass);

            if (!$login) {
                return false;
            }

            $ftpsize = ftp_size($ftpid, $path);
            ftp_close($ftpid);

            if ($ftpsize == -1) {
                return false;
            }

            return $ftpsize;
        }
    }

    /**
     * Quick FTP chmod
     *
     * @param   string   $url    TODO Add text
     * @param   integer  $mode    The new permissions, given as an octal value.
     *
     * @return  mixed
     *
     * @see        http://www.php.net/manual/en/function.ftp-chmod.php
     * @since   1.0
     */
    function ftpChmod($url, $mode)
    {
        $sch = parse_url($url, PHP_URL_SCHEME);

        if (($sch != 'ftp') && ($sch != 'ftps')) {
            return false;
        }

        $server = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $path = parse_url($url, PHP_URL_PATH);
        $user = parse_url($url, PHP_URL_USER);
        $pass = parse_url($url, PHP_URL_PASS);

        if ((!$server) || (!$path)) {
            return false;
        }

        if (!$port) {
            $port = 21;
        }

        if (!$user) {
            $user = 'anonymous';
        }

        if (!$pass) {
            $pass = '';
        }

        switch ($sch)
        {
            case 'ftp':
                $ftpid = ftp_connect($server, $port);
                break;

            case 'ftps':
                $ftpid = ftp_ssl_connect($server, $port);
                break;
        }

        if (!$ftpid) {
            return false;
        }

        $login = ftp_login($ftpid, $user, $pass);

        if (!$login) {
            return false;
        }

        $res = ftp_chmod($ftpid, $mode, $path);
        ftp_close($ftpid);

        return $res;
    }

    /**
     * Modes that require a write operation
     *
     * @return  array
     * @since   1.0
     */
    static function getWriteModes()
    {
        return array('w', 'w+', 'a', 'a+', 'r+', 'x', 'x+');
    }

    /**
     * Stream and Filter Support Operations
     *
     * Returns the supported streams, in addition to direct file access
     * Also includes Molajo streams as well as PHP streams
     *
     * @return  array  Streams
     * @since   1.0
     */
    function getSupported()
    {
        // Really quite cool what php can do with arrays when you let it...
        static $streams;

        if (!$streams) {
            $streams = array_merge(
                stream_get_wrappers(),
                MolajoFilesystemHelper::getJStreams()
            );
        }

        return $streams;
    }

    /**
     * Returns a list of transports
     *
     * @return  array
     * @since   1.0
     */
    function getTransports()
    {
        // Is this overkill?
        return stream_get_transports();
    }

    /**
     * Returns a list of filters
     *
     * @return  array
     * @since   1.0
     */
    function getFilters()
    {
        // Note: This will look like the getSupported() function with J! filters.
        // TODO: add user space filter loading like user space stream loading
        return stream_get_filters();
    }

    /**
     * Returns a list of J! streams
     *
     * @return  array
     * @since   1.0
     */
    function getJStreams()
    {
        static $streams;

        if (!$streams) {
            $streams = array_map(
                array('JFile', 'stripExt'),
                JFolder::files(dirname(__FILE__) . '/streams', '.php')
            );
        }

        return $streams;
    }

    /**
     * Determine if a stream is a Joomla stream.
     *
     * @param   string  $streamname  The name of a stream
     *
     * @return  boolean  True for a Joomla Stream
     * @since   1.0
     */
    function isJoomlaStream($streamname)
    {
        return in_array(
            $streamname,
            MolajoFilesystemHelper::getJStreams()
        );
    }
}