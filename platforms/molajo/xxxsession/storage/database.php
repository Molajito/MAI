<?php
/**
 * @package     Molajo
 * @subpackage  Component
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 * Database session storage handler for PHP
 *
 * @package     Session
 * @subpackage  Storage
 * @since       1.0
 */
class MolajoSessionStorageDatabase extends MolajoSessionStorage
{
    /**
     * $_data
     *
     * @var null
     * @since 1.0
     */
    protected $_data = null;

    /**
     * Open the SessionHandler backend.
     *
     * @param   string   The path to the session object.
     * @param   string   The name of the session.
     * @return  boolean  True on success, false otherwise.
     * @since   1.0
     */
    public function open($save_path, $session_name)
    {
        return true;
    }

    /**
     * Close the SessionHandler backend.
     *
     * @return  boolean  True on success, false otherwise.
     * @since   1.0
     */
    public function close()
    {
        return true;
    }

    /**
     * Read the data for a particular session identifier from the
     * SessionHandler backend.
     *
     * @param   string   The session identifier.
     * @return  string   The session data.
     * @since   1.0
     */
    public function read($id)
    {
        $db = Services::DB();
        if ($db->connected()) {
        } else {
            echo 'READ FAILED IN MolajoSessionStorageDatabase::read';
            die;
        }

        $query = $db->getQuery(true);

        $query->select($db->qn('data'));
        $query->from($db->qn('#__sessions'));
        $query->where($db->qn('session_id') . ' = ' . $db->q($id));

        $db->setQuery($query->__toString());

        return (string)$db->loadResult();
    }

    /**
     * Write session data to the SessionHandler backend.
     *
     * @param   string   The session identifier.
     * @param   string   The session data.
     *
     * @return  boolean  True on success, false otherwise.
     * @since   1.0
     */
    public function write($id, $data)
    {
        $db = Services::DB();
        if ($db->connected()) {
        } else {
            echo 'WRITE FAILED IN MolajoSessionStorageDatabase::write';
            die;
        }

        $query = $db->getQuery(true);

        $query->select($db->qn('session_id'));
        $query->from($db->qn('#__sessions'));
        $query->where($db->qn('session_id') . ' = ' . $db->q($id));

        $db->setQuery($query->__toString());
        $result = $db->loadResult();

        if ($result == $id) {
            return (boolean)$this->update($id, $data);
        } else {
            return (boolean)$this->insert($id, $data);
        }
    }

    /**
     * Update session data to the SessionHandler backend.
     *
     * @param   string   The session identifier.
     * @param   string   The session data.
     *
     * @return  boolean  True on success, false otherwise.
     * @since   1.0
     */
    public function update($id, $data)
    {
        $db = Services::DB();
        if ($db->connected()) {
        } else {
            echo 'READ FAILED IN MolajoSessionStorageDatabase::update';
            die;
        }

        $query = $db->getQuery(true);

        $query->update($db->qn('#__sessions'));
        $query->set($db->qn('data') . ' = ' . $db->q($data));
        $query->set($db->qn('session_time') . ' = ' . (int)time());
        $query->where($db->qn('session_id') . ' = ' . $db->q($id));

        $db->setQuery($query->__toString());

        return (boolean)$db->query();
    }

    /**
     * Insert session data
     *
     * @param   string   The session identifier.
     * @param   string   The session data.
     *
     * @return  boolean  True on success, false otherwise.
     * @since   1.0
     */
    public function insert($id, $data)
    {
        $db = Services::DB();
        if ($db->connected()) {
        } else {
            echo 'READ FAILED IN MolajoSessionStorageDatabase::insert';
            die;
        }

        $query = $db->getQuery(true);

        $query->insert($db->qn('#__sessions'));
        $query->set($db->qn('session_id') . ' = ' . $db->q($id));
        $query->set($db->qn('application_id') . ' = ' . $db->q(MOLAJO_APPLICATION_ID));
        $query->set($db->qn('data') . ' = ' . $db->q($data));
        $query->set($db->qn('session_time') . ' = ' . (int)time());

        $db->setQuery($query->__toString());

        return (boolean)$db->query();
    }

    /**
     * Destroy the data for a particular session identifier in the
     * SessionHandler backend.
     *
     * @param   string   The session identifier.
     *
     * @return  boolean  True on success, false otherwise.
     * @since   1.0
     */
    public function destroy($id)
    {
        $db = Services::DB();
        if ($db->connected()) {
        } else {
            echo 'READ FAILED IN MolajoSessionStorageDatabase::destroy';
            die;
        }

        $query = $db->getQuery(true);

        $query->delete($db->qn('#__sessions'));
        $query->where($db->qn('session_id') . ' = ' . $db->q($id));

        $db->setQuery($query->__toString());

        return (boolean)$db->query();
    }

    /**
     * Garbage collect stale sessions from the SessionHandler backend.
     *
     * @param   integer  The maximum age of a session.
     * @return  boolean  True on success, false otherwise.
     * @since   1.0
     */
    function gc($lifetime = 1440)
    {
        $db = Services::DB();
        if ($db->connected()) {
        } else {
            echo 'READ FAILED IN MolajoSessionStorageDatabase::gc';
            die;
        }

        $past = time() - $lifetime;

        $query = $db->getQuery(true);

        $query->delete($db->qn('#__sessions'));
        $query->where($db->qn('session_time') . ' = ' . (int)$past);

        $db->setQuery($query->__toString());

        return (boolean)$db->query();
    }
}
