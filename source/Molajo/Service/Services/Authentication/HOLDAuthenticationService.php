<?php
/**
 * @package     Joomla.Platform
 * @subpackage  User
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2012 Individual Molajo Contributors. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * This is the status code returned when the authentication is success (permit login)
 * @deprecated Use MolajoAuthentication::STATUS_SUCCESS
 */
define('JAUTHENTICATE_STATUS_SUCCESS', 1);

/**
 * Status to indicate cancellation of authentication (unused)
 * @deprecated
 */
define('JAUTHENTICATE_STATUS_CANCEL', 2);

/**
 * This is the status code returned when the authentication failed (prevent login if no success)
 * @deprecated Use MolajoAuthentication::STATUS_FAILURE
 */
define('JAUTHENTICATE_STATUS_FAILURE', 4);

/**
 * Authenthication class, provides an interface for the Joomla authentication system
 *
 * @package     Joomla.Platform
 * @subpackage  User
 * @since       11.1
 */
Class AuthenticationService
{
    // Shared success status
    /**
     * This is the status code returned when the authentication is success (permit login)
     * @const  STATUS_SUCCESS successful response
     * @since  11.2
     */
    const STATUS_SUCCESS = 1;

    // These are for authentication purposes (username and password is valid)
    /**
     * Status to indicate cancellation of authentication (unused)
     * @const  STATUS_CANCEL cancelled request (unused)
     * @since  11.2
     */
    const STATUS_CANCEL = 2;

    /**
     * This is the status code returned when the authentication failed (prevent login if no success)
     * @const  STATUS_FAILURE failed request
     * @since  11.2
     */
    const STATUS_FAILURE = 4;

    // These are for authorisation purposes (can the user login)
    /**
     * This is the status code returned when the account has expired (prevent login)
     * @const  STATUS_EXPIRED an expired account (will prevent login)
     * @since  11.2
     */
    const STATUS_EXPIRED = 8;

    /**
     * This is the status code returned when the account has been denied (prevent login)
     * @const  STATUS_DENIED denied request (will prevent login)
     * @since  11.2
     */
    const STATUS_DENIED = 16;

    /**
     * This is the status code returned when the account doesn't exist (not an error)
     * @const  STATUS_UNKNOWN unknown account (won't permit or prevent login)
     * @since  11.2
     */
    const STATUS_UNKNOWN = 32;

    /**
     * Constructor
     *
     * @return MolajoAuthentication
     *
     * @since   1.0
     */
    public function __construct()
    {
        $isLoaded = MolajoTriggerHelper::importTrigger('authentication');

        if ($isLoaded) {
        } else {
            MolajoError::raiseWarning('SOME_ERROR_CODE', Services::Language()->translate('JLIB_USER_ERROR_AUTHENTICATION_LIBRARIES'));
        }
    }

    /**
     * Returns the global authentication object, only creating it
     * if it doesn't already exist.
     *
     * @return MolajoAuthentication The global MolajoAuthentication object
     *
     * @since   1.0
     */
    public static function getInstance()
    {
        static $instances;

        if (!isset ($instances)) {
            $instances = array();
        }

        if (empty ($instances[0])) {
            $instances[0] = new MolajoAuthentication;
        }

        return $instances[0];
    }

    /**
     * Finds out if a set of login credentials are valid by asking all observing
     * objects to run their respective authentication routines.
     *
     * @param array $credentials Array holding the user credentials
     * @param array $options     Array holding user options
     *
     * @return MolajoAuthenticationResponse Response object with status variable filled
     *                                 in for last trigger or first successful trigger
     * @see     MolajoAuthenticationResponse
     * @since   1.0
     */
    public function authenticate($credentials, $options = Array())
    {
        // Initialise variables.
        $auth = false;

        // Get triggers
        $triggers = MolajoTriggerHelper::getTrigger('authentication');

        // Create authentication response
        $response = new MolajoAuthenticationResponse;

        /*
* Loop through the triggers and check of the credentials can be used to authenticate
* the user
*
* Any errors raised in the trigger should be returned via the MolajoAuthenticationResponse
* and handled appropriately.
*/
        foreach ($triggers as $trigger) {
            $className = 'plg' . $trigger->type . $trigger->name;
            if (class_exists($className)) {
                $trigger = new $className($this, (array) $trigger);
            } else {
                // Bail here if the trigger can't be created
                MolajoError::raiseWarning(50, Services::Language()->sprintf('JLIB_USER_ERROR_AUTHENTICATION_FAILED_LOAD_PLUGIN', $className));
                continue;
            }

            // Try to authenticate
            $trigger->onUserAuthenticate($credentials, $options, $response);

            // If authentication is successful break out of the loop
            if ($response->status === MolajoAuthentication::STATUS_SUCCESS) {
                if (empty($response->type)) {
                    $response->type = isset($trigger->_name) ? $trigger->_name : $trigger->name;
                }
                break;
            }
        }

        if (empty($response->username)) {
            $response->username = $credentials['username'];
        }

        if (empty($response->fullname)) {
            $response->fullname = $credentials['username'];
        }

        if (empty($response->password)) {
            $response->password = $credentials['password'];
        }

        return $response;
    }

    /**
     * onUserLogin
     *
     * @param $user
     * @param  array                   $options
     * @return JAuthenticationResponse
     */
    public function onUserLogin($user, $options = array())
    {
        /** user triggers */
        $triggers = MolajoTriggerHelper::getTrigger('user');

        /*
* Loop through the triggers and check of the creditials can be used to authenticate
* the user
*
* Any errors raised in the trigger should be returned via the JAuthenticationResponse
* and handled appropriately.
*/
        foreach ($triggers as $trigger) {
            $className = 'plg' . ucfirst($trigger->type) . ucfirst($trigger->name);
            if (class_exists($className)) {
                $trigger = new $className($this, (array) $trigger);
            } else {
                // Bail here if the trigger can't be created
                MolajoError::raiseWarning(50, Services::Language()->sprintf('JLIB_USER_ERROR_AUTHENTICATION_FAILED_LOAD_PLUGIN', $className));
                continue;
            }

            $response = $trigger->onUserLogin($user, $options);

            if ($response === true) {
            } else {
                break;
            }
        }

        return $response;
    }

    /**
     * Authorises that a particular user should be able to login
     *
     * @access public
     * @param MolajoAuthenticationResponse username of the user to authorise
     * @param Array list of options
     * @return Array[MolajoAuthenticationResponse] results of authorisation
     * @since  11.1
     */
    public static function authorise($response, $options = Array())
    {

        $authorisations = Services::Event()->schedule('onUserAuthorisation', Array($response, $options));

        $response->status = MolajoAuthentication::STATUS_SUCCESS;
        foreach ($authorisations as $authorisation) {

            if ($authorisation->status === MolajoAuthentication::STATUS_SUCCESS) {
            } else {

                if (isset($options['silent']) && $options['silent']) {

                } else {

                    switch ($authorisation->status) {
                        case MolajoAuthentication::STATUS_EXPIRED:
                            $response->status = STATUS_EXPIRED;

                            return MolajoError::raiseWarning('102002', Services::Language()->translate('JLIB_LOGIN_EXPIRED'));
                            break;
                        case MolajoAuthentication::STATUS_DENIED:
                            $response->status = STATUS_DENIED;

                            return MolajoError::raiseWarning('102003', Services::Language()->translate('JLIB_LOGIN_DENIED'));
                            break;
                        default:
                            $response->status = STATUS_FAILURE;

                            return MolajoError::raiseWarning('102004', Services::Language()->translate('JLIB_LOGIN_AUTHORISATION'));
                            break;
                    }
                }
            }
        }

        return;
    }
}

/**
 * Authentication response class, provides an object for storing user and error details
 *
 * @package    Joomla.Platform
 * @subpackage    User
 * @since    1.0
 */
Class AuthenticationResponse
{
    /**
     * Response status (see status codes)
     *
     * @var    string
     * @since  11.1
     */
    public $status = MolajoAuthentication::STATUS_FAILURE;

    /**
     * The type of authentication that was successful
     *
     * @var    string
     * @since  11.1
     */
    public $type = '';

    /**
     *  The error message
     *
     * @var    string
     * @since  11.1
     */
    public $error_message = '';

    /**
     * Any UTF-8 string that the End User wants to use as a username.
     *
     * @var    string
     * @since  11.1
     */
    public $username = '';

    /**
     * Any UTF-8 string that the End User wants to use as a password.
     *
     * @var    string
     * @since  11.1
     */
    public $password = '';

    /**
     * The email address of the End User as specified in section 3.4.1 of [RFC2822]
     *
     * @var    string
     * @since  11.1
     */
    public $email = '';

    /**
     * UTF-8 string free text representation of the End User's full name.
     *
     * @var    string
     * @since  11.1
     *
     */
    public $fullname = '';

    /**
     * The End User's date of birth as YYYY-MM-DD. Any values whose representation uses
     * fewer than the specified number of digits should be zero-padded. The length of this
     * value MUST always be 10. If the End User user does not want to reveal any particular
     * component of this value, it MUST be set to zero.
     *
     * For instance, if a End User wants to specify that his date of birth is in 1980, but
     * not the month or day, the value returned SHALL be "1980-00-00".
     *
     * @var    string
     * @since  11.1
     */
    public $birthdate = '';

    /**
     * The End User's gender, "M" for male, "F" for female.
     *
     * @var  string
     * @since  11.1
     */
    public $gender = '';

    /**
     * UTF-8 string free text that SHOULD conform to the End User's country's postal system.
     *
     * @var postcode string
     * @since  11.1
     */
    public $postcode = '';

    /**
     * The End User's country of residence as specified by ISO3166.
     *
     * @var string
     * @since  11.1
     */
    public $country = '';

    /**
     * End User's preferred language as specified by ISO639.
     *
     * @var    string
     * @since  11.1
     */
    public $language = '';

    /**
     * ASCII string from TimeZone database
     *
     * @var    string
     * @since  11.1
     */
    public $timezone = '';

    /**
     * Constructor
     *
     * @param string $name The type of the response
     *
     * @return MolajoAuthenticationResponse
     *
     * @since   1.0
     */
    public function __construct()
    {
    }
}
