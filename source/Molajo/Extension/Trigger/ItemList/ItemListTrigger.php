<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension\Trigger\ItemList;

use Molajo\Application;
use Molajo\Extension\Trigger\Content\ContentTrigger;

defined('MOLAJO') or die;

/**
 * Item List
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class ItemListTrigger extends ContentTrigger
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
			self::$instance = new ItemListTrigger();
		}
		return self::$instance;
	}

	/**
	 * After-read processing
	 *
	 * Retrieves Author Information for Item
	 *
	 * @param   $data
	 * @param   $model
	 *
	 * @return  $data
	 * @since   1.0
	 */
	public function onAfterRead($data, $model)
	{
		$lists = Services::Registry()->get('ExtensionParameters', 'Lists');

		if ($lists === null || count($lists) == 0) {
			return;
		}

		foreach ($lists as $item) {

			$list = Services::Configuration()->loadFile($item);

			$name = (string)$list->name;
			$table = (string)$list->table;
			$key = (string)$list->key;
			$value = (string)$list->value;
			$published = (string)$list->published;
			$catalog_type_id = (string)$list->catalog_type_id;
			$view_access = (string)$list->view_access;
			$registry = (string)$list->registry;
			$created_by = (string)$list->created_by;

			$trigger = (string)$list->trigger;

			if (trim($trigger) == '') {

				$m = Application::Controller()->connect($table);

				$m->model->set('id', $data->created_by);

				$m->model->set('get_special_fields', 0);
				$m->model->set('use_special_joins', false);
				$m->model->set('add_acl_check', false);
				$m->model->set('get_item_children', false);

				$m->model->query->select($m->model->db->qn('a.' . $key));
				$m->model->query->select($m->model->db->qn('a.' . $value));
				$m->model->query->order($m->model->db->qn('a.' . $value));

				if ((int)$catalog_type_id > 0) {
					$m->model->query->where($m->model->db->qn('a.catalog_type_id') . ' > ' . $catalog_type_id);
				}

				if ((int)$published == 1) {
					$m->model->query->where($m->model->db->qn('a.status') . ' > ' . STATUS_UNPUBLISHED);

					$m->model->query->where('(a.start_publishing_datetime = ' .
							$m->model->db->q($m->model->nullDate) .
							' OR a.start_publishing_datetime <= ' . $m->model->db->q($m->model->now) . ')'
					);
					$m->model->query->where('(a.stop_publishing_datetime = ' .
							$m->model->db->q($m->model->nullDate) .
							' OR a.stop_publishing_datetime >= ' . $m->model->db->q($m->model->now) . ')'
					);
				}

				if ((int)$view_access == 1) {
					Services::Authorisation()
						->setQueryViewAccess(
						$m->model->query,
						$m->model->db,
						array('join_to_prefix' => 'a',
							'join_to_primary_key' => 'id',
							'catalog_prefix' => 'b_catalog',
							'select' => true
						)
					);
				}
				$results = $m->getData('loadRowList');

			} else {
				$results = Services::Trigger()->get($trigger);
			}

			Services::Registry()->set('Lists', $registry, $results);

		}

		return;
	}
}
