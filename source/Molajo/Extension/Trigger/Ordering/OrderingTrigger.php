<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Extension\Trigger\Ordering;

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
class OrderingTrigger extends ContentTrigger
{
	//todo reorder on delete, too

	/**
	 * Pre-create processing
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onBeforeCreate()
	{

		$field = $this->getField('ordering');

		if ($field == false) {
			$fieldValue = false;
		} else {
			$fieldValue = $this->getFieldValue($field);
		}

		if ((int)$fieldValue > 0) {
			return true;
		}

		$newFieldValue = '';

		if ($fieldValue == false
			|| (int)$fieldValue == 0
		) {

			$controllerClass = 'Molajo\\Controller\\ReadController';
			$m = new $controllerClass();
			$results = $m->connect($this->get('model_type'), $this->get('model_name'));
			if ($results == false) {
				return false;
			}

			$primary_prefix = $this->get('primary_prefix');

			$catalog_type_idField = $this->getField('catalog_type_id');
			$catalog_type_id = $this->getFieldValue($catalog_type_idField);

			$m->model->query->select('max(' . $this->db->qn($primary_prefix) . '.' . $this->db->qn('ordering') . ')');
			$m->model->query->where($this->db->qn($primary_prefix) . '.' . $this->db->qn('catalog_type_id')
				. ' = ' . (int)$catalog_type_id);

			$m->set('use_special_joins', 0);
			$m->set('check_view_level_access', 0);
			$m->set('process_triggers', 0);
			$m->set('get_customfields', 0);

			$ordering = $m->getData('result');

			$newFieldValue = (int)$ordering + 1;

			$this->saveField($field, 'ordering', $newFieldValue);

		}

		return true;
	}
}
