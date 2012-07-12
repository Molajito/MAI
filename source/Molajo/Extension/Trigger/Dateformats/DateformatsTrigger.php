<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Extension\Trigger\Dateformats;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Date Formats
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class DateformatsTrigger extends ContentTrigger
{
    /**
     * Pre-create processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeCreate()
    {
        $fields = $this->retrieveFieldsByType('datetime');

        if (is_array($fields) && count($fields) > 0) {

            foreach ($fields as $field) {

                $name = $field->name;
                $fieldValue = $this->getFieldValue($field);

                if ($name == 'modified_datetime') {

                    $this->saveField($field, $name, $this->now);

                    $modifiedByField = $this->getField('modified_by');
                    $modifiedByValue = $this->getFieldValue($modifiedByField);
                    if ($modifiedByValue == false) {
                        $this->saveField($modifiedByField, 'modified_by', Services::Registry()->get('User', 'id'));
                    }

                } elseif ($fieldValue == false
                    || $fieldValue == '0000-00-00 00:00:00'
                ) {

                    $this->saveField($field, $name, $this->now);

                    if ($name == 'created_datetime') {
                        $createdByField = $this->getField('created_by');
                        $createdByValue = $this->getFieldValue($createdByField);
                        if ($createdByValue == false) {
                            $this->saveField($createdByField, 'created_by', Services::Registry()->get('User', 'id'));
                        }

                    } elseif ($name == 'activity_datetime') {
                        $createdByField = $this->getField('user_id');
                        $createdByValue = $this->getFieldValue($createdByField);
                        if ($createdByValue == false) {
                            $this->saveField($createdByField, 'user_id', Services::Registry()->get('User', 'id'));
                        }

                    }
                }
            }
        }

        return true;
    }

    /**
     * After-read processing
     *
     * Adds formatted dates to 'normal' or special fields recordset
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRead()
    {
        $fields = $this->retrieveFieldsByType('datetime');

        try {
            Services::Date()->convertCCYYMMDD('2011-11-11');
            /** Date Service is not available (likely startup) */
        } catch (\Exception $e) {
            return true;
        }

        if (is_array($fields) && count($fields) > 0) {

            foreach ($fields as $field) {

                $name = $field->name;

                /** Retrieves the actual field value from the 'normal' or special field */
                $fieldValue = $this->getFieldValue($field);

                if ($fieldValue == false
                    || $fieldValue == '0000-00-00 00:00:00'
                ) {

                } else {

                    /** formats the date for CCYYMMDD */
                    $newFieldValue = Services::Date()->convertCCYYMMDD($fieldValue);

                    if ($newFieldValue == false) {
                    } else {

                        /** Creates the new 'normal' or special field and populates the value */
                        $new_name = $name . '_ccyymmdd';
                        $this->saveField($field, $new_name, $newFieldValue);
                        $fieldValue = $newFieldValue;
                    }

                    /** Using newly formatted date, calculate NN days ago */
                    $newFieldValue = Services::Date()->differenceDays($fieldValue);

                    if ($newFieldValue == false) {
                    } else {

                        /** Creates the new 'normal' or special field and populates the value */
                        $new_name = $name . '_n_days_ago';
                        $this->saveField($field, $new_name, $newFieldValue);
                    }

                    /** Pretty Date */
                    $newFieldValue = Services::Date()->prettydate($fieldValue);

                    if ($newFieldValue == false) {
                    } else {

                        /** Creates the new 'normal' or special field and populates the value */
                        $new_name = $name . '_pretty_date';
                        $this->saveField($field, $new_name, $newFieldValue);
                    }
                }
            }
        }

        return true;
    }
}
