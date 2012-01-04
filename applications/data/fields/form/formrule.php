<?php
/**
 * @package     Molajo
 * @subpackage  Form
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

if (!defined('JCOMPAT_UNICODE_PROPERTIES')) {
    define('JCOMPAT_UNICODE_PROPERTIES', (bool)@preg_match('/\pL/u', 'a'));
}

/**
 * Abstract Form Field class
 *
 * @package     Molajo
 * @subpackage  Form
 * @since       1.0
 */
class MolajoFormRule
{
    /**
     * The regular expression to use in testing a form field value.
     *
     * @var    string
     * @since  1.0
     */
    protected $regex;

    /**
     * The regular expression modifiers to use when testing a form field value.
     *
     * @var    string
     * @since  1.0
     */
    protected $modifiers;

    /**
     * Method to test the value.
     *
     * @param   object  $element  The SimpleXMLElement object representing the <field /> tag for the
     *                            form field object.
     * @param   mixed   $value    The form field value to validate.
     * @param   string  $group    The field name group control value. This acts as as an array
     *                            container for the field. For example if the field has name="foo"
     *                            and the group value is set to "bar" then the full field name
     *                            would end up being "bar[foo]".
     * @param   object  $calendar    An optional JRegistry object with the entire data set to validate
     *                            against the entire form.
     * @param   object  $form     The form object for which the field is being tested.
     *
     * @return  boolean  True if the value is valid, false otherwise.
     *
     * @since   1.0
     * @throws  Exception on invalid rule.
     */
    public function test(& $element, $value, $group = null, & $calendar = null, & $form = null)
    {
        // Initialize variables.
        $name = (string)$element['name'];

        // Check for a valid regex.
        if (empty($this->regex)) {
            throw new MolajoException(MolajoTextHelper::sprintf('MOLAJO_FORM_INVALID_FORM_RULE', get_class($this)));
        }

        // Add unicode property support if available.
        if (JCOMPAT_UNICODE_PROPERTIES) {
            $this->modifiers = (strpos($this->modifiers, 'u') !== false) ? $this->modifiers : $this->modifiers . 'u';
        }

        // Test the value against the regular expression.
        if (preg_match(chr(1) . $this->regex . chr(1) . $this->modifiers, $value)) {
            return true;
        }

        return false;
    }
}