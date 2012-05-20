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
 * TemplateView Helper
 *
 * @package       Molajo
 * @subpackage    Helper
 * @since         1.0
 */
Class TemplateViewHelper
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
			self::$instance = new TemplateViewHelper();
		}

		return self::$instance;
	}

	/**
	 * get
	 *
	 * Get requested template_view data
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function get($template_view_id = 0)
	{
		if ($template_view_id == 0) {
			$template_view_id = $this->setDefault();
		}

		Services::Registry()->set('Parameters', 'template_view_id', (int)$template_view_id);

		$node = Helpers::Extension()->getExtensionNode((int)$template_view_id);

		Services::Registry()->set('Parameters', 'template_view_path_node', $node);

		Services::Registry()->set('Parameters', 'template_view_path', $this->getPath($node));
		Services::Registry()->set('Parameters', 'template_view_path_include', $this->getPath($node) . '/index.php');
		Services::Registry()->set('Parameters', 'template_view_path_url', $this->getPathURL($node));

		/** Retrieve the query results */
		$row = Helpers::Extension()->get($template_view_id, 'TemplateViews', 'Table');

		/** 500: not found */
		if (count($row) == 0) {

			/** System Default */
			$template_view_id = Helpers::Extension()->getInstanceID(CATALOG_TYPE_EXTENSION_TEMPLATE_VIEW, 'Default');

			/** System default */
			Services::Registry()->set('Parameters', 'template_view_id', (int)$template_view_id);

			$node = Helpers::Extension()->getExtensionNode((int)$template_view_id);

			Services::Registry()->set('Parameters', 'template_view_path_node', $node);

			Services::Registry()->set('Parameters', 'template_view_path', $this->getPath($node));
			Services::Registry()->set('Parameters', 'template_view_path_include', $this->getPath($node) . '/index.php');
			Services::Registry()->set('Parameters', 'template_view_path_url', $this->getPathURL($node));

			$row = Helpers::Extension()->get($template_view_id, 'TemplateView');

			if (count($row) == 0) {
				Services::Error()->set(500, 'View not found');
				return false;
			}
		}

		Services::Registry()->set('Parameters', 'template_view_translation_of_id', (int)$row['translation_of_id']);
		Services::Registry()->set('Parameters', 'template_view_language', $row['language']);
		Services::Registry()->set('Parameters', 'template_view_view_group_id', $row['view_group_id']);
		Services::Registry()->set('Parameters', 'template_view_catalog_id', $row['catalog_id']);
		Services::Registry()->set('Parameters', 'template_view_catalog_type_id', (int)$row['catalog_type_id']);
		Services::Registry()->set('Parameters', 'template_view_catalog_type_title', $row['catalog_types_title']);

		return;
	}

	/**
	 *  setDefault
	 *
	 *  Determine the default template_view value, given system default sequence
	 *
	 * @return  string
	 * @since   1.0
	 */
	public function setDefault()
	{
		$template_view_id = Services::Registry()->get('Parameters', 'template_view_id', 0);
		if ((int)$template_view_id == 0) {
		} else {
			return $template_view_id;
		}

		$template_view_id = Services::Registry()->get('UserParameters', 'template_view_id', 0);
		if ((int)$template_view_id == 0) {
		} else {
			return $template_view_id;
		}

		$template_view_id = Services::Registry()->get('Configuration', 'template_view_id', 0);
		if ((int)$template_view_id == 0) {
		} else {
			return $template_view_id;
		}

		return Helpers::Extension()->getInstanceID(CATALOG_TYPE_EXTENSION_TEMPLATE_VIEW, 'System'); //99
	}

	/**
	 * getPath
	 *
	 * Return path for selected TemplateView
	 *
	 * @param $template_view_name
	 * @return bool|string
	 */
	public function getPath($node)
	{
		if (file_exists(EXTENSIONS_VIEWS . '/Template/' . ucfirst(strtolower($node)) . '/' . 'index.php')) {
			return EXTENSIONS_VIEWS . '/Template/' . ucfirst(strtolower($node));
		}

		return false;
	}

	/**
	 * getPathURL
	 *
	 * Return path for selected TemplateView
	 *
	 * @return bool|string
	 * @since 1.0
	 */
	public function getPathURL($node)
	{
		if (file_exists(EXTENSIONS_VIEWS . '/Template/' . ucfirst(strtolower($node)) . '/' . 'index.php')) {
			return EXTENSIONS_VIEWS_URL . '/Template/' . ucfirst(strtolower($node));
		}

		return false;
	}
}
