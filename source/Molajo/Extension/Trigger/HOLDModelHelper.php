<?php
/**
 * @package   Molajo
 * @subpackage  API
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Application\Helper;

use Molajo\Extension\Helper\CatalogHelper;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Model
 *
 * @package   Molajo
 * @subpackage  Helper
 * @since       1.0
 */
class ModelHelper
{

	/**
	 * Table row
	 *
	 * @var    object
	 * @since  1.0
	 */
	public $row = '';

	/**
	 * __construct
	 *
	 * Class constructor
	 *
	 * @since  1.0
	 */
	public function __construct()
	{
	}

	/**
	 * queryPrimaryCategory
	 *
	 * Note: Assumes a join is in place to the catalog table on a_catalog
	 *
	 * sets the select, table, and where clause to retrieve
	 * the primary category and description with content
	 *
	 * @param array $query
	 *
	 * @return  object
	 * @since   1.0
	 */
	public function queryPrimarycategory(
		$query = array(),
		$prefix = 'a',
		$db)
	{
		$query->select($db->qn('a_catalog') . '.' . $db->qn('primary_category_id'));
		$query->select($db->qn('pcat') . '.title' . ' as ' . $db->qn('category_title'));
		$query->from($db->qn('#__content') . ' as ' . $db->qn('pcat'));
		$query->where($db->qn('pcat') . '.' . $db->qn('id')
			. ' = ' . $db->qn('a_catalog') . '.' . $db->qn('primary_category_id'));

		return $query;

	}

	/**
	 * itemUserPermission
	 *
	 * Validate task-level user permissions on each row
	 *
	 * Note: Must request content catalog_id in order to use this method
	 *
	 * @param array $item
	 * @param array $parameters
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function itemUserPermission($item = array(), $parameters = array())
	{
		if (isset($item->catalog_id)) {
		} else {
			return $item;
		}

		/** Component Buttons */
		$tasks =
			Application::Request()
				->parameters
				->get('toolbar_buttons');

		$tasksArray = explode(',', $tasks);

		/** User Permissions */
		$permissions = Services::Authorisation()
			->authoriseTaskList($tasksArray, $item->catalog_id);

		/** Append onto row */
		foreach ($tasksArray as $task) {
			if ($permissions[$task] === true) {
				$fieldname = $task . 'Permission';
				$item->$fieldname = $permissions[$task];
			}
		}

		return $item;
	}

	/**
	 * itemSplittext
	 *
	 * splits the content_text field into intro and full text on readmore
	 *
	 * @param array $item
	 * @param array $parameters
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function itemSplittext(
		$item = array(),
		$parameters = array())
	{
		if (isset($item->content_text)) {
		} else {
			$item->introtext = '';
			$item->fulltext = '';
			return $item;
		}

		$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

		$tagPos = preg_match($pattern, $item->content_text);

		if ($tagPos == 0) {
			$introtext = $item->content_text;
			$fulltext = '';
		} else {
			list($introtext, $fulltext) = preg_split($pattern, $item->content_text, 2);
		}

		$item->introtext = $introtext;
		$item->fulltext = $fulltext;

		return $item;
	}

	/**
	 * itemSnippet
	 *
	 * Splits content_text field into intro and full text on readmore
	 *
	 * @param array $item
	 * @param array $parameters
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function itemSnippet(
		$item = array(),
		$parameters = array())
	{
		if (isset($item->content_text)) {
		} else {
			$item->snippet = '';
			return $item;
		}

		$item->snippet =
			substr(
				strip_tags($item->content_text),
				0,
				$parameters->get('snippet_length', 200)
			);

		return $item;
	}

	/**
	 * itemURL
	 *
	 * Determines the item URL
	 *
	 * @param array $item
	 * @param array $parameters
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function itemURL($item = array(), $parameters = array())
	{
		if (isset($item->catalog_id)) {
		} else {
			$item->url = '';
			return $item;
		}

		$item->url = Helpers::Catalog()->getURL($item->catalog_id);

		return $item;
	}

	/**
	 * itemDateformats
	 *
	 * Adds formatted dates to $item
	 *
	 * @param array $item
	 * @param array $parameters
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function itemDateformats(
		$item = array(),
		$parameters = array())
	{
		if (isset($item->created_datetime)) {
			if ($item->created_datetime == '0000-00-00 00:00:00') {
			} else {
				$item =
					ModelHelper::itemDateRoutine(
						'created_datetime',
						$item
					);
			}
		}

		if (isset($item->modified_datetime)) {
			if ($item->modified_datetime == '0000-00-00 00:00:00') {
			} else {
				$item =
					ModelHelper::itemDateRoutine(
						'modified_datetime',
						$item
					);
			}
		}

		if (isset($item->start_publishing_datetime)) {
			if ($item->start_publishing_datetime == '0000-00-00 00:00:00') {
			} else {
				$item =
					ModelHelper::itemDateRoutine(
						'start_publishing_datetime',
						$item
					);
			}
		}

		if (isset($item->stop_publishing_datetime)) {
			if ($item->stop_publishing_datetime == '0000-00-00 00:00:00') {
			} else {
				$item =
					ModelHelper::itemDateRoutine(
						'stop_publishing_datetime',
						$item
					);
			}
		}

		return $item;
	}

	/**
	 * itemDateRoutine
	 *
	 * Creates formatted date fields based on a named field
	 *
	 * @param $fieldname
	 * @param $item
	 *
	 * @return array
	 * @since 1.0
	 */
	public function itemDateRoutine(
		$fieldname,
		$item)
	{
		if ($item->$fieldname == '0000-00-00 00:00:00') {
			return $item;
		}

		$newField = $fieldname . '_ccyymmdd';
		$item->$newField =
			Services::Date()
				->convertCCYYMMDD($item->$fieldname);
		$item->$newField =
			str_replace('-', '', $item->$newField);

		$newField = $fieldname . '_n_days_ago';
		$item->$newField =
			Services::Date()
				->differenceDays(date('Y-m-d'), $item->$fieldname);

		$newField = $fieldname . '_pretty_date';
		$item->$newField =
			Services::Date()
				->prettydate($item->$fieldname);

		return $item;
	}

	/**
	 * itemExpandjsonfields
	 *
	 * Expands the json-encoded fields into normal fields
	 *
	 * @param array $item
	 * @param array $parameters
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function itemExpandjsonfields(
		$item = array(),
		$parameters = array())
	{
		$jsonfields[] = 'custom_fields';
		$jsonfields[] = 'parameters';
		$jsonfields[] = 'metadata';

		foreach ($jsonfields as $name) {
			$name = trim($name);
			if (property_exists($item, $name)) {
				$registry = Services::Registry()->initialise();
				$registry->loadString($item->$name);
				$fields = $registry->toArray();

				while (list($jsonfield, $jsonfieldvalue) = each($fields)) {
					if (property_exists($item, $jsonfield)) {
					} else {
						$item->$jsonfield = $jsonfieldvalue;
					}
				}
				unset($item->$name);
			}
		}
		return $item;
	}

	/**
	 * getList
	 *
	 * @param $model
	 * @param string $name
	 *
	 * @return list
	 * @since  1.0
	 */
	public function getList($field)
	{

		$lists = Services::Configuration()->loadFile('lists');

		if (count($lists) == 0) {
			return false;
		}

		/** list definitions */
		$found = false;
		foreach ($lists->list as $l) {

			if (trim((string)$l['name']) == trim($field)) {

				$found = true;

				if (isset($l['method'])) {
					$method = (string)$l['method'];
				} else {
					$method = '';
				}
				if (isset($l['model'])) {
					$model = 'Molajo\\MVC\\Model\\';
					$model .= (string)$l['model'];
				} else {
					$model = '';
				}
				if (isset($l['key'])) {
					$key = (string)$l['key'];
				} else {
					$key = '';
				}
				if (isset($l['value'])) {
					$value = (string)$l['value'];
				} else {
					$value = '';
				}
				if (isset($l['ordering'])) {
					$ordering = (string)$l['ordering'];
				} else {
					$ordering = '';
				}
				if (isset($l['catalogtypes'])) {
					$catalogtypes = (string)$l['catalogtypes'];
				} else {
					$catalogtypes = '';
				}
				if (isset($l['viewaccess'])) {
					$viewaccess = (string)$l['viewaccess'];
				} else {
					$viewaccess = '';
				}
				if (isset($l['status'])) {
					$status = (string)$l['status'];
				} else {
					$status = '';
				}
				if (isset($l['site'])) {
					$site = (string)$l['site'];
				} else {
					$site = '';
				}
				if (isset($l['application'])) {
					$application = (string)$l['application'];
				} else {
					$application = '';
				}
			}
		}

		if ($found === false) {
			echo $field . ' not found (in ModelHelper::getList)';
			return false;
		}

		if ($method == '') {
		} else {
			return $this->$method();
		}

		$m = new $model();

		$m->query->select($m->db->qn('a') . '.' . $m->db->qn($key)
			. ' as ' . $m->db->qn('key'));
		$m->query->select($m->db->qn('a') . '.' . $m->db->qn($value)
			. ' as ' . $m->db->qn('value'));

		$m->query->from($m->db->qn(trim($m->table_name))
			. ' as ' . $m->db->qn('a'));

		if ((int)$catalogtypes == '0') {
		} else {
			$m->query->where($m->db->qn('a')
				. '.'
				. $m->db->qn('catalog_type_id')
				. ' IN (' . $catalogtypes . ')');
		}

		if ((int)$status == 1) {
			$m->query->where($m->db->qn('status')
				. ' > ' . (int)STATUS_UNPUBLISHED);
		}

		if ((int)$viewaccess == 1) {
			Services::Authorisation()->setQueryViewAccess(
				$this->query,
				$this->db,
				array('join_to_prefix' => 'a',
					'join_to_primary_key' => 'id',
					'catalog_prefix' => 'a_catalog',
					'select' => false
				)
			);
		}

		if (trim($ordering) == '') {
			$ordering = $value;
		}
		$m->query->order($ordering . ' ASC');

		return $m->runQuery();
	}

	/**
	 * getLanguageList
	 *
	 * @return  list
	 * @since   1.0
	 */
	public function getLanguageList()
	{
		return Services::Language()->createLanguageList();
	}

	/**
	 * getStatusList
	 *
	 * @return  list
	 * @since   1.0
	 */
	public function getStatusList()
	{
		$query_results = array();

		$obj = new \stdClass();
		$obj->key = STATUS_ARCHIVED;
		$obj->value = Services::Language()->translate('STATUS_ARCHIVED');
		$query_results[] = $obj;

		$obj = new \stdClass();
		$obj->key = STATUS_PUBLISHED;
		$obj->value = Services::Language()->translate('STATUS_PUBLISHED');
		$query_results[] = $obj;

		$obj = new \stdClass();
		$obj->key = STATUS_UNPUBLISHED;
		$obj->value = Services::Language()->translate('STATUS_UNPUBLISHED');
		$query_results[] = $obj;

		$obj = new \stdClass();
		$obj->key = STATUS_TRASHED;
		$obj->value = Services::Language()->translate('STATUS_TRASHED');
		$query_results[] = $obj;

		$obj = new \stdClass();
		$obj->key = STATUS_SPAMMED;
		$obj->value = Services::Language()->translate('STATUS_SPAMMED');
		$query_results[] = $obj;

		$obj = new \stdClass();
		$obj->key = STATUS_DRAFT;
		$obj->value = Services::Language()->translate('STATUS_DRAFT');
		$query_results[] = $obj;

		$obj = new \stdClass();
		$obj->key = STATUS_VERSION;
		$obj->value = Services::Language()->translate('STATUS_VERSION');
		$query_results[] = $obj;

		return $query_results;
	}

	/**
	 * updateAlias
	 *
	 * Verify that the alias is unique
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateAlias()
	{

	}


	/**
	 * updateContentText
	 *
	 * Verify and set defaults for dates
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateContentText()
	{

	}

	/**
	 * updateDates
	 *
	 * Verify and set defaults for dates
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateDates()
	{

	}

	/**
	 * updateLanguage
	 *
	 * Verify language setting
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateLanguage()
	{

	}

	/**
	 * updateUser
	 *
	 * Verify and set defaults for dates
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateUser()
	{

	}

	/**
	 * updateStatus
	 *
	 * Verify and set values for Status
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateStatus()
	{

	}

	/**
	 * updateCustomfields
	 *
	 * Verify and set values for Custom Fields
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateCustomfields()
	{

	}

	/**
	 * updateMetadata
	 *
	 * Verify and set values for Custom Fields
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateMetadata()
	{

	}


	/**
	 * updateParameters
	 *
	 * Verify and set values for Parameters
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	public function updateParameters()
	{

	}
}
