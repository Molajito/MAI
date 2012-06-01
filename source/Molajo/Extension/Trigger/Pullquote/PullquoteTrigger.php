<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension\Trigger\Pullquote;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Pullquote
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class PullquoteTrigger extends ContentTrigger
{
    /**
     * Static instance
     *
     * @var    object
     * @since  1.0
     */
    protected static $instance;

    /**
     * getInstance
     *
     * @static
     * @return bool|object
     * @since  1.0
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new PullquoteTrigger();
        }

        return self::$instance;
    }

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
        $fields = $this->retrieveFieldsByType('text');

        if (is_array($fields) && count($fields) > 0) {

			/** @noinspection PhpWrongForeachArgumentTypeInspection */
			foreach ($fields as $field) {

				/** retrieve each text field */
                $name = $field->name;
                $fieldValue = $this->getFieldValue($field);

                if ($fieldValue == false) {
                } else {

					/** search for pullquotes statements, remove from text */
					$results = Services::Text()->pullquotes($fieldValue);
					if ($results == false) {
						return true;
					}

					/** Save new pullquote array */
					$pullquote = $results[0];
					$newName = $name . '_pullquote';
					$this->saveField($field, $newName, $pullquote);

					/** Save to Registry - pullquote */
					$temp = '';
					foreach ($pullquote as $quote) {
						if ($temp == '') {
						} else {
							$temp .= '<br />';
						}
						$temp .= $quote;
					}
					Services::Registry()->set('Trigger', 'Pullquote', $temp);

					/** Replace existing text */
					$fieldValue = $results[1];
					$this->saveField($field, $name, $fieldValue);
				}
            }
        }

        return true;
    }
}
