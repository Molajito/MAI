<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Extension\Trigger\Email;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Email
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class EmailTrigger extends ContentTrigger
{
	/**
	 * After-read processing
	 *
	 * Retrieves Author Information for Item
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onAfterRead()
	{
		$fields = $this->retrieveFieldsByType('email');

		if (is_array($fields) && count($fields) > 0) {

			/** @noinspection PhpWrongForeachArgumentTypeInspection */
			foreach ($fields as $field) {

				$name = $field->name;
				$new_name = $name . '_' . 'obfuscated';

				/** Retrieves the actual field value from the 'normal' or special field */
				$fieldValue = $this->getFieldValue($field);

				if ($fieldValue == false) {
				} else {

					$newFieldValue = Services::Url()->obfuscateEmail($fieldValue);

					if ($newFieldValue == false) {
					} else {

						/** Creates the new 'normal' or special field and populates the value */
						$fieldValue = $this->saveField($field, $new_name, $newFieldValue);
					}
				}
			}
		}

		return true;
	}

	/**
	 * Pre-update processing
	 *
	 * @param   $this->data
	 * @param   $model
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onBeforeUpdate()
	{
		return false;
	}
}
