<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Extension\Trigger\Catalogtypeid;

use Molajo\Extension\Trigger\Content\ContentTrigger;

defined('MOLAJO') or die;

/**
 * CatalogtypeId
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class CatalogtypeidTrigger extends ContentTrigger
{
    /**
     * After-read processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRead()
    {
        return true;
    }
}
