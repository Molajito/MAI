<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Extension\Helper;

use Molajo\Service\Services;
use Molajo\Extension\Helpers;

defined('MOLAJO') or die;

/**
 * PageView Helper
 *
 * @package       Molajo
 * @subpackage    Helper
 * @since         1.0
 */
Class PageViewHelper
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
			self::$instance = new PageViewHelper();
		}

		return self::$instance;
	}

	/**
	 * get
	 *
	 * Get requested page_view data
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function get($page_view_id = 0, $namespace)
	{
		if ($page_view_id == 0) {
			$page_view_id = $this->setDefault();
		}

		Services::Registry()->set($namespace, 'page_view_id', (int)$page_view_id);

		$node = Helpers::Extension()->getExtensionNode((int)$page_view_id);

		Services::Registry()->set($namespace, 'page_view_path_node', $node);

		Services::Registry()->set($namespace, 'page_view_path', $this->getPath($node, $namespace));
		Services::Registry()->set($namespace, 'page_view_path_include',
			$this->getPath($node, $namespace) . '/index.php');
		Services::Registry()->set($namespace, 'page_view_path_url', $this->getPathURL($node, $namespace));

		/** Retrieve the query results */
		$row = Helpers::Extension()->get($page_view_id, 'PageViews', 'Table');

		/** 500: not found */
		if (count($row) == 0) {

			/** System Default */
			$page_view_id = Helpers::Extension()->getInstanceID(CATALOG_TYPE_EXTENSION_PAGE_VIEW, 'Default');

			/** System default */
			Services::Registry()->set($namespace, 'page_view_id', (int)$page_view_id);

			$node = Helpers::Extension()->getExtensionNode((int)$page_view_id);

			Services::Registry()->set($namespace, 'page_view_path_node', $node);

			Services::Registry()->set($namespace, 'page_view_path', $this->getPath($node, $namespace));
			Services::Registry()->set($namespace, 'page_view_path_include',
				$this->getPath($node, $namespace) . '/index.php');
			Services::Registry()->set($namespace, 'page_view_path_url', $this->getPathURL($node, $namespace));

			$row = Helpers::Extension()->get($page_view_id, 'PageView');

			if (count($row) == 0) {
				Services::Error()->set(500, 'View not found');
				return false;
			}
		}

		Services::Registry()->set($namespace, 'page_view_translation_of_id', (int)$row['translation_of_id']);
		Services::Registry()->set($namespace, 'page_view_language', $row['language']);
		Services::Registry()->set($namespace, 'page_view_view_group_id', $row['view_group_id']);
		Services::Registry()->set($namespace, 'page_view_catalog_id', $row['catalog_id']);
		Services::Registry()->set($namespace, 'page_view_catalog_type_id', (int)$row['catalog_type_id']);
		Services::Registry()->set($namespace, 'page_view_catalog_type_title', $row['catalog_types_title']);

		return;
	}

	/**
	 * getPath
	 *
	 * Return path for selected View
	 *
	 * Expects known path for Theme and Extension
	 *
	 * @param $node
	 * @return bool|string
	 */
	public function getPath($node, $namespace)
	{
		$plus = '/View/Page/' . ucfirst(strtolower($node));

		/** 1. Theme */
		if (file_exists(Services::Registry()->get($namespace, 'theme_path') . $plus . '/Manifest.xml')) {
			return Services::Registry()->get($namespace, 'theme_path') . $plus;
		}

		/** 2. Extension */
		if (file_exists(Services::Registry()->get($namespace, 'extension_path') . $plus . '/Manifest.xml')) {
			return Services::Registry()->get($namespace, 'extension_path') . $plus;
		}

		/** 3. View */
		if (file_exists(EXTENSIONS_VIEWS . '/Page/' . ucfirst(strtolower($node)) . '/Manifest.xml')) {
			return EXTENSIONS_VIEWS . '/Page/' . ucfirst(strtolower($node));
		}

		/** 4. MVC */
		if (file_exists(MVC . $plus . '/Manifest.xml')) {
			return MVC . $plus;
		}

		return false;
	}

	/**
	 * getURLPath
	 *
	 * Return URL path for selected Template View
	 *
	 * @return bool|string
	 * @since 1.0
	 */
	public function getPathURL($node = false, $namespace)
	{
		$plus = '/View/Page/' . ucfirst(strtolower($node));

		/** 1. Theme */
		if (file_exists(Services::Registry()->get($namespace, 'theme_path') . $plus . '/Manifest.xml')) {
			return Services::Registry()->get($namespace, 'theme_path_url') . $plus;
		}

		/** 2. Extension */
		if (file_exists(Services::Registry()->get($namespace, 'extension_path') . $plus . '/Manifest.xml')) {
			return Services::Registry()->get($namespace, 'extension_path_url') . $plus;
		}

		/** 3. View */
		if (file_exists(EXTENSIONS_VIEWS . '/Page/' . ucfirst(strtolower($node)) . '/Manifest.xml')) {
			return EXTENSIONS_VIEWS_URL . '/Page/' . ucfirst(strtolower($node));
		}

		/** 4. MVC */
		if (file_exists(MVC . $plus . '/Manifest.xml')) {
			return MVC_URL . $plus;
		}

		return false;
	}
}
