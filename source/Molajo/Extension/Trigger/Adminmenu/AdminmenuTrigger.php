<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see license folder
 */
namespace Molajo\Extension\Trigger\Adminmenu;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class AdminmenuTrigger extends ContentTrigger
{
	/**
	 * Before-read processing
	 *
	 * Prepares data for the Administrator Grid  - position AdminmenuTrigger last
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function onAfterRoute()
	{
		/** Data Source Connection */
		$model_type = $this->get('model_type');
		$model_name = $this->get('model_name');

		$controllerClass = 'Molajo\\Controller\\ReadController';
		$connect = new $controllerClass();

		$results = $connect->connect($model_type, $model_name);
		if ($results == false) {
			return false;
		}

		$table_name = $connect->get('table_name');

		$primary_prefix = $connect->get('primary_prefix');
		$primary_key = $connect->get('primary_key');
		$name_key = $connect->get('name_key');

		/** URL */
		$url = Services::Registry()->get('Configuration', 'application_base_url');

		if (Services::Registry()->get('Configuration', 'url_sef') == 1) {
			$url .= '/' . $this->get('catalog_url_sef_request');
			$connector = '?';
		} else {
			$url .= '/' . $this->get('catalog_url_request');
			$connector = '&';
		}

		Services::Registry()->set('Triggerdata', 'PageURL', $url);
		Services::Registry()->set('Triggerdata', 'PageURLConnector', $connector);

		/** Create Admin Menus, verifying ACL */
		$this->setMenu();
		$this->setPageTitle();

		return true;
	}

	/**
	 * Retrieve an array of values that represent the active menuitem ids for a specific menu
	 *
	 * @return void
	 * @since  1.0
	 */
	protected function setMenu()
	{
		/** Detail rows are not defined as menu items but rather tied to a parent menuitem id */
		$current_menuitem_id = Services::Registry()->get('Parameters', 'parent_menuitem', '0');

		/** Normal menu item is current */
		if ($current_menuitem_id == 0) {
			$current_menuitem_id = Services::Registry()->get('Parameters', 'catalog_source_id');
			$item_id = 0;
		} else {
			$item_id = Services::Registry()->get('Parameters', 'catalog_id');
		}

		/** Breadcrumbs */
		$bread_crumbs = Services::Menu()->getMenuBreadcrumbIds($current_menuitem_id, $item_id);

		$activeCatalogID = array();
		foreach ($bread_crumbs as $item) {
			$activeCatalogID[] = $item->catalog_id;
		}
		if ($item_id > 0) {
			$activeCatalogID[] = $item_id;
		}

		Services::Registry()->get('Triggerdata', 'AdminBreadcrumbs', $bread_crumbs);

		$menuArray = array();

		// 1. Home
		$menuArray[] = 'Adminnavigationbar';
		$menuArray[] = 'Adminsectionmenu';
		$menuArray[] = 'Admincomponentmenu';
		$menuArray[] = 'Adminitemmenu';

		$i = 0;
		foreach ($bread_crumbs as $item) {

			$extension_instance_id = $item->extension_instance_id;
			$lvl = $item->lvl + 1;
			$parent_id = $item->id;

			$query_results = Services::Menu()->runMenuQuery(
				$extension_instance_id, $lvl, $lvl, $parent_id, $activeCatalogID
			);

			Services::Registry()->set('Triggerdata', $menuArray[$i++], $query_results);

			if ($i > 3) {
				break;
			}
		}

		return;
	}

	/**
	 * Set the Title, given the Breadcrumb values
	 *
	 * @param   int $extension_instance_id - menu
	 *
	 * @return  object
	 * @since   1.0
	 */
	public function setPageTitle()
	{
		$bread_crumbs = Services::Registry()->get('Triggerdata', 'AdminBreadcrumbs');

		$title = '';
		foreach ($bread_crumbs as $item) {
			$title = $item->title;
		}

		Services::Registry()->set('Triggerdata', 'AdminTitle', $title);

		return $this;
	}
}
