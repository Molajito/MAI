<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Service\Services\Text;

use LoremIpsumGenerator\LoremIpsumGenerator;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Text
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
Class TextService
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
			self::$instance = new TextService();
		}

		return self::$instance;
	}

	/**
	 * addLineBreaks
	 *
	 * changes line breaks to br tags
	 *
	 * @param  string $text
	 * @return string
	 * @since  1.0
	 */
	public function addLineBreaks($text)
	{
		return nl2br($text);
	}

	/**
	 * replaceBuffer
	 *
	 * todo: add event after rendering and change this approach
	 *
	 * Change a value in the buffer
	 *
	 * @param  string $text
	 * @return string
	 * @since  1.0
	 */
	public function replaceBuffer($change_from, $change_to)
	{
		$buffer = preg_replace(
			$change_from,
			$change_to,
			Services::Response()->getBody()
		);
		Services::Response()->setContent($buffer);
	}

	/**
	 * splitReadMoreText - search for the system-readmore break and split the text at that point into two text fields
	 *
	 * @param  $text
	 *
	 * @return array
	 * @since   1.0
	 */
	public function splitReadMoreText($text)
	{
		$pattern = '#{readmore}#';

		$tagPos = preg_match($pattern, $text);

		$introductory_text = '';
		$fulltext = '';

		if ($tagPos == 0) {
			$introductory_text = $text;
		} else {
			list($introductory_text, $fulltext) = preg_split($pattern, $text, 2);
		}

		return (array($introductory_text, $fulltext));
	}

	/**
	 * pullquotes - searches for and returns pullquotes
	 *
	 * @param  $text
	 *
	 * @return array
	 * @since   1.0
	 */
	public function pullquotes($text)
	{
		$pattern = '/{pullquote}(.*){\/pullquote}/';

		preg_match_all($pattern, $text, $matches);

		$pullquote = array();
		if (count($matches) == 0) {
		} else {

			/** add wrap for each */
			foreach ($matches[1] as $match) {
				$temp = strip_tags($match);
				if (trim($temp) == '') {
				} else {
					$pullquote[] = $temp;
				}
			}
		}

		$text = str_replace($matches[0], $matches[1], $text);

		return array($pullquote, $text);
	}

	/**
	 * snippet - strip HTML and return a short value of text field
	 *
	 * @param  $text
	 *
	 * @return array
	 * @since   1.0
	 */
	public function snippet($text)
	{
		return substr(strip_tags($text), 0, Services::Registry()->get('Parameters', 'criteria_snippet_length', 200));
	}

	/**
	 * smilies - change text smiley values into icons
	 *
	 * @param  string $text
	 * @return string
	 * @since  1.0
	 */
	public function smilies($text)
	{
		$smile = array(
			':mrgreen:' => 'icon_mrgreen.gif',
			':neutral:' => 'icon_neutral.gif',
			':twisted:' => 'icon_twisted.gif',
			':arrow:' => 'icon_arrow.gif',
			':shock:' => 'icon_eek.gif',
			':smile:' => 'icon_smile.gif',
			':???:' => 'icon_confused.gif',
			':cool:' => 'icon_cool.gif',
			':evil:' => 'icon_evil.gif',
			':grin:' => 'icon_biggrin.gif',
			':idea:' => 'icon_idea.gif',
			':oops:' => 'icon_redface.gif',
			':razz:' => 'icon_razz.gif',
			':roll:' => 'icon_rolleyes.gif',
			':wink:' => 'icon_wink.gif',
			':cry:' => 'icon_cry.gif',
			':eek:' => 'icon_surprised.gif',
			':lol:' => 'icon_lol.gif',
			':mad:' => 'icon_mad.gif',
			':sad:' => 'icon_sad.gif',
			'8-)' => 'icon_cool.gif',
			'8-O' => 'icon_eek.gif',
			':-(' => 'icon_sad.gif',
			':-)' => 'icon_smile.gif',
			':-?' => 'icon_confused.gif',
			':-D' => 'icon_biggrin.gif',
			':-P' => 'icon_razz.gif',
			':-o' => 'icon_surprised.gif',
			':-x' => 'icon_mad.gif',
			':-|' => 'icon_neutral.gif',
			';-)' => 'icon_wink.gif',
			'8)' => 'icon_cool.gif',
			'8O' => 'icon_eek.gif',
			':(' => 'icon_sad.gif',
			':)' => 'icon_smile.gif',
			':?' => 'icon_confused.gif',
			':D' => 'icon_biggrin.gif',
			':P' => 'icon_razz.gif',
			':o' => 'icon_surprised.gif',
			':x' => 'icon_mad.gif',
			':|' => 'icon_neutral.gif',
			';)' => 'icon_wink.gif',
			':!:' => 'icon_exclaim.gif',
			':?:' => 'icon_question.gif',
		);

		if (count($smile) > 0) {
			foreach ($smile as $key => $val) {
				$text = str_ireplace($key,
					'<span><img src="' . SITES_MEDIA_URL . '/images/smilies/'
						. $val
						. '" alt="smiley" class="smiley-class" /></span>',
					$text);
			}
		}

		return $text;
	}

	/**
	 * Add rows to model
	 *
	 * @param $extension_name
	 * @param $model_name
	 * @param $source_path
	 * @param $destination_path
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function extension($model_name, $source_path = null, $destination_path = null)
	{
		$controller = new CreateController();

		//http://www.youtube.com/watch?v=dRjE1JwdDLI
		$table_registry_name = ucfirst(strtolower($model_name)) . 'Table';

		$data = new \stdClass();
		$data->title = $extension_name;
		$data->model_name = $model_name;
		//http://placekitten.com/200/300
		$controller->data = $data;

		$id = $controller->create();
		if ($id === false) {
			//install failed
			return false;
		}
	}

	/**
	 * Retrieves Lorem Ipsum text
	 *
	 * Usage:
	 * Services::Text()->getPlaceHolderText(4, 20, 'html', 1);
	 *
	 * @param  int  $paragraph_word_count - number of words per paragraph
	 * @param  int  $paragraph_count
	 * @param  char $format
	 * @param  $start_with_lorem_ipsum 0 or 1
	 *
	 * @return string
	 * @since   1.0
	 */
	public function getPlaceHolderText($paragraph_word_count, $paragraph_count, $format, $start_with_lorem_ipsum)
	{
		$generator = new LoremIpsumGenerator($paragraph_word_count);
		return ucfirst($generator->getContent($paragraph_word_count * $paragraph_count, $format, $start_with_lorem_ipsum));
	}

	/**
	 * Add images to text
	 *
	 * Usage:
	 * Services::Text()->addImages(2);
	 *
	 * @param  int  $image_count - number of images
	 * @param  int  $width
	 * @param  int  $height
	 * @param  int  $type (box, cat)
	 *
	 * @return string
	 * @since   1.0
	 */
	public function addImage($width, $height, $type)
	{
		if ($type == 'cat') {
			$source = '<img src="http://placekitten.com/' . (int)$width . '/'. (int) $height . '"/>';
		} else {
			$source = '<img src="http://placehold.it/' . (int)$width . 'x'. (int) $height . '"/>';
		}
		return $source;

	}

	/**
	 * getList retrieves values called from listsTrigger
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function getList($filter, $parameters)
	{
		$controllerClass = 'Molajo\\Controller\\ModelController';
		$m = new $controllerClass();
		$results = $m->connect('Listbox', $filter);
		if ($results == false) {
			return false;
		}

		if ($m->get('data_source', 'JDatabase') == 'JDatabase') {

			$primary_prefix = $m->get('primary_prefix');
			$primary_key = $m->get('primary_key');
			$name_key = $m->get('name_key');

			$m->model->set('model_offset', 0);
			$m->model->set('model_count', 999999);

			/** Select */
			$fields = Services::Registry()->get($filter . 'Listbox', 'Fields');

			$first = true;

			if (count($fields) < 2) {

				$m->model->query->select('DISTINCT ' . $m->model->db->qn($primary_prefix . '.' . $primary_key) . ' as id');
				$m->model->query->select($m->model->db->qn($primary_prefix . '.' . $name_key) . ' as value');
				$m->model->query->order($m->model->db->qn($primary_prefix . '.' . $name_key) . ' ASC');

			} else {

				$ordering = '';

				foreach ($fields as $field) {

					if (isset($field['alias'])) {
						$alias = $field['alias'];
					} else {
						$alias = $primary_prefix;
					}

					$name = $field['name'];

					if ($first) {
						$first = false;
						$as = 'id';
						$distinct = 'DISTINCT';
					} else {
						$as = 'value';
						$distinct = '';
						$ordering = $alias . '.' . $name;
					}

					$m->model->query->select($distinct . ' ' . $m->model->db->qn($alias . '.' . $name) . ' as ' . $as);
				}

				$m->model->query->order($m->model->db->qn($ordering) . ' ASC');
			}

			/** Where */

			$this->setWhereCriteria (
				'catalog_type_id',
				$m->get('filter_catalog_type_id'),
				$primary_prefix,
				$m
			);

			$this->setWhereCriteria (
				'status',
				$m->get('filter_check_published_status'),
				$primary_prefix,
				$m
			);

			$this->setWhereCriteria (
				'extension_instance_id',
				$m->get('filter_extension_instance_id'),
				$primary_prefix,
				$m
			);

			/** Where: Menu ID */
			$menu_id = null;
			if (isset($parameters['menu_extension_catalog_type_id'])
				&& (int)$parameters['menu_extension_catalog_type_id'] == 1300
			) {
				$this->setWhereCriteria (
					'menu_id',
					$m->get('menu_extension_instance_id'),
					$primary_prefix,
					$m
				);
			}

			$query_object = 'distinct';

		} else {

			$m->set('model_parameter', $filter);

			$query_object = 'getListdata';
		}


		$query_results = $m->getData($query_object);

		/**
		echo '<br /><br /><br />';
		echo $m->model->query->__toString($query_object);
		echo '<pre>';
		var_dump($query_results);
		echo '</pre>';
		echo '<br /><br /><br />';
		 **/

		return $query_results;
	}

	protected function setWhereCriteria ($field, $value, $alias, $connection)
	{

		if (strrpos($value, ',') > 0) {
			$connection->model->query->where(
				$connection->model->db->qn($alias . '.' . $field)
				. ' IN (' . $value . ')'
			);

		} elseif ((int)$value == 0) {

		} else {
			$connection->model->query->where(
				$connection->model->db->qn($alias . '.' . $field) . ' = ' . (int)$value
				);
		}
	}

	/**
	 * add publishedStatus information to list query
	 *
	 * @return  void
	 * @since   1.0
	 */
	protected function publishedStatus($m)
	{
		$primary_prefix = Services::Registry()->get($m->table_registry_name, 'primary_prefix', 'a');

		$m->model->query->where($m->model->db->qn($primary_prefix)
			. '.' . $m->model->db->qn('status')
			. ' > ' . STATUS_UNPUBLISHED);

		$m->model->query->where('(' . $m->model->db->qn($primary_prefix)
				. '.' . $m->model->db->qn('start_publishing_datetime')
				. ' = ' . $m->model->db->q($m->model->null_date)
				. ' OR ' . $m->model->db->qn($primary_prefix) . '.' . $m->model->db->qn('start_publishing_datetime')
				. ' <= ' . $m->model->db->q($m->model->now) . ')'
		);

		$m->model->query->where('(' . $m->model->db->qn($primary_prefix)
				. '.' . $m->model->db->qn('stop_publishing_datetime')
				. ' = ' . $m->model->db->q($m->model->null_date)
				. ' OR ' . $m->model->db->qn($primary_prefix) . '.' . $m->model->db->qn('stop_publishing_datetime')
				. ' >= ' . $m->model->db->q($m->model->now) . ')'
		);

		return;
	}

	/**
	 * buildSelectlist - build select list for insertion into webpage
	 *
	 * @param  $listname
	 * @param  $items
	 * @param  int $multiple
	 * @param  int $size
	 *
	 * @return  array
	 * @since   1.0
	 */
	public function buildSelectlist($listname, $items, $multiple = 0, $size = 5)
	{
		ksort($items);

		/** todo: Retrieve selected field from request */
		$selected = '';

		$query_results = array();
		foreach ($items as $item) {

			$row = new \stdClass();

			$row->listname = $listname;

			$row->id = $item->id;
			$row->value = $item->value;

			if ($row->id == $selected) {
				$row->selected = ' selected ';
			} else {
				$row->selected = '';
			}

			$row->multiple = '';

			if ($multiple == 1) {
				$row->multiple = ' multiple ';
				if ((int)$size == 0) {
					$row->multiple .= 'size=5 ';
				} else {
					$row->multiple .= 'size=' . (int)$size;
				}
			}

			$query_results[] = $row;
		}

		return $query_results;
	}


	/**
	 *     Dummy functions to pass service off as a DBO to interact with model
	 */
	public function get($option = null)
	{
		if ($option == 'db') {
			return $this;
		}
	}

	public function getNullDate()
	{
		return $this;
	}

	public function getQuery()
	{
		return $this;
	}

	public function toSql()
	{
		return $this;
	}

	public function clear()
	{
		return $this;
	}

	/**
	 * getData - simulates DBO - interacts with the Model getTextlist method
	 *
	 * @param $registry
	 * @param $element
	 * @param $single_result
	 *
	 * @return array
	 * @since    1.0
	 */
	public function getData($list)
	{
		$query_results = array();

		if (strtolower($list) == 'featured') {
			$row = new \stdClass();
			$row->id = 1;
			$row->value = Services::Language()->translate('FEATURED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = 0;
			$row->value = Services::Language()->translate('NOT_FEATURED');
			$query_results[] = $row;

		} elseif (strtolower($list) == 'stickied') {
			$row = new \stdClass();
			$row->id = 1;
			$row->value = Services::Language()->translate('STICKIED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = 0;
			$row->value = Services::Language()->translate('NOT_STICKIED');
			$query_results[] = $row;

		} elseif (strtolower($list) == 'protected') {
			$row = new \stdClass();
			$row->id = 1;
			$row->value = Services::Language()->translate('PROTECTED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = 0;
			$row->value = Services::Language()->translate('REMOVEABLE');
			$query_results[] = $row;

		} elseif (strtolower($list) == 'status') {

			$row = new \stdClass();
			$row->id = STATUS_ARCHIVED;
			$row->value = Services::Language()->translate('STATUS_ARCHIVED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = STATUS_PUBLISHED;
			$row->value = Services::Language()->translate('STATUS_PUBLISHED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = STATUS_UNPUBLISHED;
			$row->value = Services::Language()->translate('STATUS_UNPUBLISHED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = STATUS_TRASHED;
			$row->value = Services::Language()->translate('STATUS_TRASHED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = STATUS_SPAMMED;
			$row->value = Services::Language()->translate('STATUS_SPAMMED');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = STATUS_DRAFT;
			$row->value = Services::Language()->translate('STATUS_DRAFT');
			$query_results[] = $row;

			$row = new \stdClass();
			$row->id = STATUS_VERSION;
			$row->value = Services::Language()->translate('STATUS_VERSION');
			$query_results[] = $row;
		}

		/** Return results to Model */
		return $query_results;
	}
}
