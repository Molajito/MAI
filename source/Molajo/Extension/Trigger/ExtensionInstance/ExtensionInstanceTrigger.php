<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension\Trigger\Extensioninstance;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Controller\CreateController;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Extension Instances
 *
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class ExtensioninstanceTrigger extends ContentTrigger
{

	/**
	 * Pre-create processing
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onBeforeCreate()
	{

		if ($this->data->catalog_type_id >= CATALOG_TYPE_EXTENSION_BEGIN
			AND $this->data->catalog_type_id <= CATALOG_TYPE_EXTENSION_END
		) {
		} else {
			return true;
		}

		/** Ensure no other entry exists for this specific Extension Name/Catalog Type combination */
		$controllerClass = 'Molajo\\Controller\\ModelController';
		$m = new $controllerClass();
		$m->connect('Table', 'ExtensionInstances');

		$primary_prefix = $m->get('primary_prefix', 'a');

		$m->set('get_customfields', '0');
		$m->set('get_item_children', '0');
		$m->set('use_special_joins', '0');
		$m->set('check_view_level_access', '0');

		$m->model->query->select($m->model->db->qn($primary_prefix) . '.' . $m->model->db->qn('id'));
		$m->model->query->where($m->model->db->qn($primary_prefix) . '.' . $m->model->db->qn('title')
			. ' = ' . $m->model->db->q($this->data->title));
		$m->model->query->where($m->model->db->qn($primary_prefix) . '.' . $m->model->db->qn('catalog_type_id')
			. ' = ' . (int)$this->data->catalog_type_id);

		$id = $m->getData('result');

		if ((int)$id > 0) {
			//name already exists
			return false;
		}

		/** Next, see if the Extension base exists */
		$controllerClass = 'Molajo\\Controller\\ModelController';
		$m = new $controllerClass();
		$m->connect('Table', 'Extensions');

		$m->model->query->select($m->model->db->qn('a.id'));
		$m->model->query->where($m->model->db->qn('a.name')
			. ' = ' . $m->model->db->q($this->data->title));
		$m->model->query->where($m->model->db->qn('a.catalog_type_id')
			. ' = ' . (int) $this->data->catalog_type_id);

		$id = $m->getData('result');

		if ((int)$id > 0) {
			$field = $this->getField('extension_id');
			$this->saveField($field, $field->name, $id);
			return true;
		}

		/** If not existing, create  */
		$controller = new CreateController();

		$data = new \stdClass();
		$data->name = $this->data->title;
		$data->catalog_type_id = $this->data->catalog_type_id;
		$data->model_name = 'Extensions';

		$controller->data = $data;

		$id = $controller->create();

		if ($id === false) {
			//error
			return false;
		} else {
			if ((int)$id > 0) {
				$field = $this->getField('extension_id');
				$this->saveField($field, $field->name, $id);
				var_dump($this->data);
				return true;
			}
		}

		return true;
	}

	/**
	 * Post-create processing
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onAfterCreate()
	{

		if ($this->data->catalog_type_id >= CATALOG_TYPE_EXTENSION_BEGIN
			AND $this->data->catalog_type_id <= CATALOG_TYPE_EXTENSION_END
		) {
		} else {
			return true;
		}

		/** Extension ID */
		$id = $this->data->id;

		if ((int) $id == 0) {
			return false;
		}

		/** Check ACL */

		/** Site Extension Instances */
		$controller = new CreateController();

		$data = new \stdClass();
		$data->site_id = SITE_ID;
		$data->extension_instance_id = $id;
		$data->model_name = 'SiteExtensionInstances';

		$controller->data = $data;

		$results = $controller->create();
		if ($results === false) {
			//install failed
			return false;
		}

		/** Application Extension Instances */
		$controller = new CreateController();

		$data = new \stdClass();
		$data->application_id = APPLICATION_ID;
		$data->extension_instance_id = $id;
		$data->model_name = 'ApplicationExtensionInstances';

		$controller->data = $data;

		$results = $controller->create();
		if ($results === false) {
			//install failed
			return false;
		}

		/** Catalog */
		$controller = new CreateController();

		$data = new \stdClass();
		$data->catalog_type_id = Services::Registry()->get($this->table_registry_name, 'catalog_type_id');
		$data->source_id = $id;
		$data->view_group_id = 1;
		$data->extension_instance_id = $id;
		$data->model_name = 'Catalog';

		$controller->data = $data;

		$catalog_id = $controller->create();
		if ($results === false) {
			//install failed
			return false;
		}

		return true;
	}

	/**
	 * Pre-read processing
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onBeforeRead()
	{
		return true;
	}

	/**
	 * Post-read processing
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onAfterRead()
	{
		return true;
	}

	/**
	 * On after route
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onAfterRoute()
	{
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
		return true;
	}

	/**
	 * Post-update processing
	 *
	 * @param   $this->data
	 * @param   $model
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onAfterUpdate()
	{
		return true;
	}

	/**
	 * Pre-delete processing
	 *
	 * @param   $this->data
	 * @param   $model
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onBeforeDelete()
	{

		// ACL
		$results = Services::Authorisation()->authoriseTask('Delete', $this->data->catalog_id);

		// if there is content - no delete (must unpublish or trash)

		// remove view group permissions

		// remove group permissions

		// remove catalog entries

		// remove catalog categories

		// remove orphan categories tht were associated with the catalog

		// mremove catalog activity

		// remove user activity

		//remove application extension instances

		// remove site extension instances

		// then it can delete


		return true;
	}

	/**
	 * Post-delete processing
	 *
	 * @param   $this->data
	 * @param   $model
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onAfterDelete()
	{
		// remove teh extension node if there are no other extension instances for that extension
		return true;
	}
}
