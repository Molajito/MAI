<?php
/**
 * @package     Molajo
 * @subpackage  Service
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * This class has influences and some method logic from the Horde Auth package
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * User
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
abstract class MolajoUserService extends JObject
{
    /**
     * getUserId
     *
     * Returns user_id if a user exists
     *
     * @static
     * @param   $username
     * @return  mixed
     */
    public static function getUserId($username)
    {
        $db = Molajo::Services()->connect('jdb');
        $query = $db->getQuery(true);

        $query->select($db->quoteName('id'));
        $query->from($db->quoteName('#__users'));
        $query->where($db->quoteName('username').' = ' . $db->quote($username));

        $db->setQuery($query->__toString());

        echo $db->loadResult();
    }

    /**
     * addUserToGroup
     *
     * Method to add a user to a group.
     *
     * @param   integer  $user_id    The id of the user.
     * @param   integer  $group_id   The id of the group.
     *
     * @return  mixed    Boolean true on success, exception on error.
     * @since   1.0
     */
    public static function addUserToGroup($user_id, $group_id)
    {
        $user = new MolajoUser((int)$user_id);

        if (in_array($group_id, $user->groups)) {
        } else {

            $db = Molajo::Services()->connect('jdb');
            $db->setQuery(
                'SELECT `title`' .
                ' FROM `#__content`' .
                ' WHERE `id` = ' . (int)$group_id
            );
            $title = $db->loadResult();

            if ($db->getErrorNum()) {
                return new MolajoException($db->getErrorMsg());
            }

            if ($title) {
            } else {
                return new MolajoException(
                    TextService::_('MOLAJO_ERROR_GROUP_INVALID')
                );
            }

            $user->groups[$title] = $group_id;
            if ($user->save()) {
            } else {
                return new MolajoException($user->getError());
            }
        }

        return true;
    }

    /**
     * Method to remove a user from a group.
     *
     * @param   integer  $user_id        The id of the user.
     * @param   integer  $group_id    The id of the group.
     * @return  mixed  Boolean true on success, Exception on error.
     * @since   1.0
     */
    public static function removeUserFromGroup($user_id, $group_id)
    {
        // Get the user object.
        $user = MolajoUser::getInstance((int)$user_id);

        // Remove the user from the group if necessary.
        if (in_array($group_id, $user->groups)) {
            // Remove the user from the group.
            unset($user->groups[$group_id]);

            // Store the user object.
            if (!$user->save()) {
                return new MolajoException($user->getError());
            }
        }

        // Set the group data for any preloaded user objects.
        $temp = Molajo::User((int)$user_id);
        $temp->groups = $user->groups;

        // Set the group data for the user object in the session.
        $temp = Molajo::Application()->get('User', '', 'services');
        if ($temp->id == $user_id) {
            $temp->groups = $user->groups;
        }

        return true;
    }

    /**
     * Method to set the groups for a user.
     *
     * @param   integer  $user_id        The id of the user.
     * @param   array    $groups        An array of group ids to put the user in.
     *
     * @return  mixed  Boolean true on success, Exception on error.
     * @since   1.0
     */
    public static function setUserGroups($user_id, $groups)
    {
        // Get the user object.
        $user = MolajoUser::getInstance((int)$user_id);

        // Set the group ids.
        JArrayService::toInteger($groups);
        $user->groups = $groups;

        // Get the titles for the user groups.
        $db = Molajo::Services()->connect('jdb');
        $db->setQuery(
            'SELECT `id`, `title`' .
            ' FROM `#__content`' .
            ' WHERE `id` = ' . implode(' OR `id` = ', $user->groups)
        );
        $results = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            return new MolajoException($db->getErrorMsg());
        }

        // Set the titles for the user groups.
        for ($i = 0, $n = count($results); $i < $n; $i++) {
            $user->groups[$results[$i]->id] = $results[$i]->title;
        }

        // Store the user object.
        if (!$user->save()) {
            return new MolajoException($user->getError());
        }

        // Set the group data for any preloaded user objects.
        $temp = Molajo::User((int)$user_id);
        $temp->groups = $user->groups;

        // Set the group data for the user object in the session.
        $temp = Molajo::Application()->get('User', '', 'services');
        if ($temp->id == $user_id) {
            $temp->groups = $user->groups;
        }

        return true;
    }

    /**
     * Method to activate a user
     *
     * @param   string   $activated    Activation string
     *
     * @return  boolean  True on success
     * @since   1.0
     */
    public static function activateUser($activation)
    {
        // Initialize some variables.
        $db = Molajo::Services()->connect('jdb');

        // Let's get the id of the user we want to activate
        $query = 'SELECT id'
                 . ' FROM #__users'
                 . ' WHERE activated = ' . $db->quote($activation)
                 . ' AND block = 1'
                 . ' AND last_visit_datetime = ' . $db->quote('0000-00-00 00:00:00');

        $db->setQuery($query->__toString());
        $id = intval($db->loadResult());

        // Is it a valid user to activate?
        if ($id) {
            $user = MolajoUser::getInstance((int)$id);

            $user->set('block', '0');
            $user->set('activated', '');

            // Time to take care of business.... store the user.
            if (!$user->save()) {
                MolajoError::raiseWarning("SOME_ERROR_CODE", $user->getError());
                return false;
            }
        } else {
            MolajoError::raiseWarning("SOME_ERROR_CODE", TextService::_('MOLAJO_USER_ERROR_UNABLE_TO_FIND_USER'));
            return false;
        }

        return true;
    }

    /**
     * Formats a password using the current encryption.
     *
     * @param   string   $plaintext    The plaintext password to encrypt.
     * @param   string   $salt        The salt to use to encrypt the password. []
     *                                If not present, a new salt will be
     *                                generated.
     * @param   string   $encryption    The kind of pasword encryption to use.
     *                                Defaults to md5-hex.
     * @param   boolean  $show_encrypt  Some password systems prepend the kind of
     *                                encryption to the crypted password ({SHA},
     *                                etc). Defaults to false.
     *
     * @return  string  The encrypted password.
     */
    public static function getCryptedPassword($plaintext, $salt = '', $encryption = 'md5-hex', $show_encrypt = false)
    {
        // Get the salt to use.
        $salt = UserService::getSalt($encryption, $salt, $plaintext);

        // Encrypt the password.
        switch ($encryption)
        {
            case 'plain' :
                return $plaintext;

            case 'sha' :
                $encrypted = base64_encode(mhash(MHASH_SHA1, $plaintext));
                return ($show_encrypt) ? '{SHA}' . $encrypted : $encrypted;

            case 'crypt' :
            case 'crypt-des' :
            case 'crypt-md5' :
            case 'crypt-blowfish' :
                return ($show_encrypt ? '{crypt}' : '') . crypt($plaintext, $salt);

            case 'md5-base64' :
                $encrypted = base64_encode(mhash(MHASH_MD5, $plaintext));
                return ($show_encrypt) ? '{MD5}' . $encrypted : $encrypted;

            case 'ssha' :
                $encrypted = base64_encode(mhash(MHASH_SHA1, $plaintext . $salt) . $salt);
                return ($show_encrypt) ? '{SSHA}' . $encrypted : $encrypted;

            case 'smd5' :
                $encrypted = base64_encode(mhash(MHASH_MD5, $plaintext . $salt) . $salt);
                return ($show_encrypt) ? '{SMD5}' . $encrypted : $encrypted;

            case 'aprmd5' :
                $length = strlen($plaintext);
                $context = $plaintext . '$apr1$' . $salt;
                $binary = UserService::_bin(md5($plaintext . $salt . $plaintext));

                for ($i = $length; $i > 0; $i -= 16) {
                    $context .= substr($binary, 0, ($i > 16 ? 16 : $i));
                }
                for ($i = $length; $i > 0; $i >>= 1) {
                    $context .= ($i & 1) ? chr(0) : $plaintext[0];
                }

                $binary = UserService::_bin(md5($context));

                for ($i = 0; $i < 1000; $i++) {
                    $new = ($i & 1) ? $plaintext : substr($binary, 0, 16);
                    if ($i % 3) {
                        $new .= $salt;
                    }
                    if ($i % 7) {
                        $new .= $plaintext;
                    }
                    $new .= ($i & 1) ? substr($binary, 0, 16) : $plaintext;
                    $binary = UserService::_bin(md5($new));
                }

                $p = array();
                for ($i = 0; $i < 5; $i++) {
                    $k = $i + 6;
                    $j = $i + 12;
                    if ($j == 16) {
                        $j = 5;
                    }
                    $p[] = UserService::_toAPRMD5((ord($binary[$i]) << 16) | (ord($binary[$k]) << 8) | (ord($binary[$j])), 5);
                }

                return '$apr1$' . $salt . '$' . implode('', $p) . UserService::_toAPRMD5(ord($binary[11]), 3);

            case 'md5-hex' :
            default :
                $encrypted = ($salt) ? md5($plaintext . $salt) : md5($plaintext);
                return ($show_encrypt) ? '{MD5}' . $encrypted : $encrypted;
        }
    }

    /**
     * Returns a salt for the appropriate kind of password encryption.
     * Optionally takes a seed and a plaintext password, to extract the seed
     * of an existing password, or for encryption types that use the plaintext
     * in the generation of the salt.
     *
     * @param   string   $encryption  The kind of pasword encryption to use.
     *                            Defaults to md5-hex.
     * @param   string   $seed        The seed to get the salt from (probably a
     *                            previously generated password). Defaults to
     *                            generating a new seed.
     * @param   string   $plaintext    The plaintext password that we're generating
     *                            a salt for. Defaults to none.
     *
     * @return  string  The generated or extracted salt.
     */
    public static function getSalt($encryption = 'md5-hex', $seed = '', $plaintext = '')
    {
        // Encrypt the password.
        switch ($encryption)
        {
            case 'crypt' :
            case 'crypt-des' :
                if ($seed) {
                    return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 2);
                } else {
                    return substr(md5(mt_rand()), 0, 2);
                }
                break;

            case 'crypt-md5' :
                if ($seed) {
                    return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 12);
                } else {
                    return '$1$' . substr(md5(mt_rand()), 0, 8) . '$';
                }
                break;

            case 'crypt-blowfish' :
                if ($seed) {
                    return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 16);
                } else {
                    return '$2$' . substr(md5(mt_rand()), 0, 12) . '$';
                }
                break;

            case 'ssha' :
                if ($seed) {
                    return substr(preg_replace('|^{SSHA}|', '', $seed), -20);
                } else {
                    return mhash_keygen_s2k(MHASH_SHA1, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
                }
                break;

            case 'smd5' :
                if ($seed) {
                    return substr(preg_replace('|^{SMD5}|', '', $seed), -16);
                } else {
                    return mhash_keygen_s2k(MHASH_MD5, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
                }
                break;

            case 'aprmd5' :
                /* 64 characters that are valid for APRMD5 passwords. */
                $APRMD5 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

                if ($seed) {
                    return substr(preg_replace('/^\$apr1\$(.{8}).*/', '\\1', $seed), 0, 8);
                } else {
                    $salt = '';
                    for ($i = 0; $i < 8; $i++) {
                        $salt .= $APRMD5{
                        rand(0, 63)
                        };
                    }
                    return $salt;
                }
                break;

            default :
                $salt = '';
                if ($seed) {
                    $salt = $seed;
                }
                return $salt;
                break;
        }
    }

    /**
     * Generate a random password
     *
     * @param   integer  $length    Length of the password to generate
     * @return  string  Random Password
     * @since   1.0
     */
    public static function genRandomPassword($length = 8)
    {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $len = strlen($salt);
        $makepass = '';

        $stat = @stat(__FILE__);
        if (empty($stat) || !is_array($stat)) $stat = array(php_uname());

        mt_srand(crc32(microtime() . implode('|', $stat)));

        for ($i = 0; $i < $length; $i++) {
            $makepass .= $salt[mt_rand(0, $len - 1)];
        }

        return $makepass;
    }

    /**
     * Converts to allowed 64 characters for APRMD5 passwords.
     *
     * @param   string  $value
     * @param   integer  $count
     *
     * @return  string  $value converted to the 64 MD5 characters.
     * @since   1.0
     */
    protected static function _toAPRMD5($value, $count)
    {
        /* 64 characters that are valid for APRMD5 passwords. */
        $APRMD5 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $aprmd5 = '';
        $count = abs($count);
        while (--$count) {
            $aprmd5 .= $APRMD5[$value & 0x3f];
            $value >>= 6;
        }
        return $aprmd5;
    }

    /**
     * Converts hexadecimal string to binary data.
     *
     * @param   string   $hex  Hex data.
     *
     * @return  string  Binary data.
     * @since   1.0
     */
    private static function _bin($hex)
    {
        $bin = '';
        $length = strlen($hex);
        for ($i = 0; $i < $length; $i += 2) {
            $tmp = sscanf(substr($hex, $i, 2), '%x');
            $bin .= chr(array_shift($tmp));
        }
        return $bin;
    }
}
