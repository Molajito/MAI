<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Extension\Trigger\Lists;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class ListsTrigger extends ContentTrigger
{

    /**
     * Before-read processing
     *
     * Prepares the filter selections for the Grid Query
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeRead()
    {

        return true;
        /** Initialize Filter Registry */
        Services::Registry()->createRegistry('Lists');

        /** Retrieve Filters from Parameters for Resource */
        $createLists = explode(',', $this->get('grid_lists'));

        $lists = array();

        if (is_array($createLists) && count($createLists) > 0) {

            /** Build each list and store in registry along with current selection */
            foreach ($createLists as $list) {

                $fieldValue = Services::Text()->getList($list, $this->parameters);

                if ($fieldValue == false) {
                } else {

                    ksort($fieldValue);

                    Services::Registry()->set('Lists', 'list_' . $list, $fieldValue);

                    /** todo: Retrieves the user selected field from the session */
                    $selectedValue = null;

                    if (strtolower($list) == 'created_by') {
                        $selectedValue = 100;

                    } elseif (strtolower($list) == 'catalog_type_id') {
                        $selectedValue = 10000;

                    } elseif (strtolower($list) == 'featured') {
                        $selectedValue = 1;

                    } elseif (strtolower($list) == 'protected') {
                        $selectedValue = 1;
                    }

                    Services::Registry()->set('Lists', 'list_' . $list . '_selected', $selectedValue);

                    $lists[] = strtolower($list);
                }
            }
        }

        Services::Registry()->set('Lists', 'list', $lists);

        return true;
    }
}
