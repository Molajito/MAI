<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension;

use Molajo\Application;
use Molajo\Extension\Helpers;
use Molajo\Service\Services;
use Molajo\MVC\Controller\DisplayController;

defined('MOLAJO') or die;

/**
 * Includer
 *
 * @package     Molajo
 * @subpackage  Extension
 * @since       1.0
 */
class Includer
{
	/**
	 * $name
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $name = null;

	/**
	 * $type
	 * Examples: head, module, message, tag, request, module, defer
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = null;

	/**
	 * $tag
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $tag = null;

	/**
	 * $attributes
	 *
	 * Extracted in Parser Class from Theme/Rendered output
	 *
	 * <include:extension statement attr1=x attr2=y attrN="and-so-on" />
	 * template, template_view_css_id, template_view_css_class
	 * wrap, wrap_view_css_id, wrap_view_css_class
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $attributes = array();

	/**
	 * $extension_required
	 *
	 * Some includes (ex. head, messages, defer), do not require
	 * an extension for further processing. In those cases, this
	 * indicator is set to false.
	 *
	 * @var    bool
	 * @since  1.0
	 */
	protected $extension_required = true;

	/**
	 * $rendered_output
	 *
	 * Rendered output resulting from MVC processing
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $rendered_output;

	/**
	 * $items
	 *
	 * Used only for event processing and will be passed into the
	 * MVC to serve as the Model data source
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $items;

	/**
	 * $normal
	 *
	 * Used to pre-retrieve parameter information for request and prevent requery
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $normal = false;

	/**
	 * __construct
	 *
	 * Class constructor.
	 *
	 * @param  string $name
	 * @param  string $type
	 * @param  array  $items (used for event processing includes, only)
	 *
	 * @return  null
	 * @since   1.0
	 */
	public function __construct($name = null, $type = null, $items = null)
	{
		$this->name = $name;
		$this->type = $type;
		$this->items = $items;

		return;
	}

	/**
	 * process
	 *
	 * - Loads Metadata (only Theme Includer)
	 * - Loads Language files for Extension
	 * - Loads Assets for Extension
	 * - Activates Controller for Task
	 * - Captures Rendered Output
	 * - Returns Rendered Output to Molajo::Parse to use for replacing with <include:type />
	 *
	 * @param   $attributes <include:type attr1=x attr2=y attr3=z ... />
	 *
	 * @return  mixed
	 * @since   1.0
	 */
	public function process($attributes = array())
	{
		$normal = true;

		/** attributes from <include:type */
		$this->attributes = $attributes;

		$this->getAttributes();

		/** retrieve the extension that will be used to generate the MVC request */
		$this->getExtension();

		/** initialises and populates the MVC request */
		$this->setRenderCriteria();

		if ($this->extension_required === true) {
			if (Services::Registry()->get('Include', 'extension_id', 0) == 0) {
				Services::Registry()->set('Parameters', 'status_found', false);
				return false;
			}
		}

		/** language must be there before the extension runs */
		$this->loadLanguage();

		/** instantiate MVC and render output */
		$this->rendered_output = $this->invokeMVC();

		/** only load media if there was rendered output */
		if ($this->rendered_output == ''
			&& Services::Registry()->get('Parameters', 'display_view_on_no_results') == 0
		) {
		} else {
			$this->loadMedia();
			$this->loadViewMedia();
		}

		/** used by events to update $items, if necessary */
		$this->postMVCProcessing();

		return $this->rendered_output;
	}

	/**
	 * getAttributes
	 *
	 * Use the view and/or wrap criteria ife specified on the <include statement
	 *
	 * @return  null
	 * @since   1.0
	 */
	protected function getAttributes()
	{
		if (count($this->attributes) > 0) {

			foreach ($this->attributes as $name => $value) {

				if ($name == 'name' || $name == 'title') {
					Services::Registry()->set('Include', 'extension_title', $value);


				} else if ($name == 'tag') {
					$this->tag = $value;


				} else if ($name == 'template') {
					Services::Registry()->set('Parameters', 'template_view_title', $value);

				} else if ($name == 'template_view_css_id ') {
					Services::Registry()->set('Parameters', 'template_view_css_id', $value);

				} else if ($name == 'template_view_css_class') {
					Services::Registry()->set('Parameters', 'template_view_css_class', $value);


				} else if ($name == 'wrap') {
					Services::Registry()->set('Parameters', 'wrap_view_title', $value);

				} else if ($name == 'wrap_view_css_id') {
					Services::Registry()->set('Parameters', 'wrap_view_css_id', $value);

				} else if ($name == 'wrap_view_css_class') {
					Services::Registry()->set('Parameters', 'wrap_view_css_id', $value);
				}
			}
		}
	}

	/**
	 * getExtension
	 *
	 * Retrieve extension information after looking up the ID in the extension-specific includer
	 *
	 * @return bool
	 * @since 1.0
	 */
	protected function getExtension($extension_id = null)
	{
		$results = Helpers::Extension()->getIncludeExtension(
			$extension_id,
			Services::Registry()->get('Include', 'extension_title'),
			$this->type
		);

		if ($results == false) {
			return Services::Registry()->set('Parameter', 'status_found', false);
		}

		return;
	}

	/**
	 * setRenderCriteria
	 *
	 * Use the view and/or wrap criteria ife specified on the <include statement
	 * Retrieve View and wrap criteria and path information
	 *
	 * @return  bool
	 * @since   1.0
	 */
	protected function setRenderCriteria()
	{
		/** Final Template and Wrap selections */
		Services::Registry()->merge('Configuration', 'Parameters', true);

		Helpers::Extension()->finalizeParameters(
			Services::Registry()->get('Include', 'content_id', 0),
			Services::Registry()->get('Include', 'request_action', 'display')
		);

		/** Sort */
		Services::Registry()->sort('Include');
		Services::Registry()->sort('Parameters');

		return;

		//????

		/** mvc parameters */
		Services::Registry()->set('Parameters', 'controller',
			Services::Registry()->get('Request', 'mvc_controller'));
		Services::Registry()->set('Parameters', 'action',
			Services::Registry()->get('Route', 'request_action'));
		Services::Registry()->set('Parameters', 'model',
			Services::Registry()->get('Request', 'mvc_model'));
		Services::Registry()->set('Parameters', 'table',
			Services::Registry()->get('Request', 'source_table'));
		Services::Registry()->set('Parameters', 'id',
			(int)Services::Registry()->get('Request', 'mvc_id'));
		Services::Registry()->set('Parameters', 'category_id',
			(int)Services::Registry()->get('Request', 'mvc_category_id'));
		Services::Registry()->set('Parameters', 'display_view_on_no_results',
			(bool)Services::Registry()->get('Request', 'mvc_suppress_no_results'));

		return;
	}

	/**
	 * loadLanguage
	 *
	 * Loads Language Files for extension
	 *
	 * @return  null
	 * @since   1.0
	 */
	protected function loadLanguage()
	{
		return Helpers::Extension()->loadLanguage(Services::Registry()->get('Include', 'extension_path'));
	}

	/**
	 * loadMedia
	 *
	 * Loads Media CSS and JS files for extension and related content
	 *
	 * @return  null
	 * @since   1.0
	 */
	protected function loadMedia()
	{
	}

	/**
	 * loadViewMedia
	 *
	 * Loads Media CSS and JS files for Template and Wrap Views
	 *
	 * @return  null
	 * @since   1.0
	 */
	protected function loadViewMedia()
	{
		$priority = Services::Registry()->get('Parameters', 'criteria_media_priority_other_extension', 400);

		$file_path = Services::Registry()->get('Parameters', 'template_view_path');
		$url_path = Services::Registry()->get('Parameters', 'template_view_path_url');

		$css = Services::Document()->add_css_folder($file_path, $url_path, $priority);
		$js = Services::Document()->add_js_folder($file_path, $url_path, $priority, 0);
		$defer = Services::Document()->add_js_folder($file_path, $url_path, $priority, 1);

		$file_path = Services::Registry()->get('Parameters', 'wrap_view_path');
		$url_path = Services::Registry()->get('Parameters', 'wrap_view_path_url');

		$css = Services::Document()->add_css_folder($file_path, $url_path, $priority);
		$js = Services::Document()->add_js_folder($file_path, $url_path, $priority, 0);
		$defer = Services::Document()->add_js_folder($file_path, $url_path, $priority, 1);
	}

	/**
	 * invokeMVC
	 *
	 * Instantiate the Controller and fire off the action, returns rendered output
	 *
	 * @return mixed
	 */
	protected function invokeMVC()
	{
		$controller = new DisplayController();
		//$m = Application::Controller()->connect('Messages', 'Table');
		//Services::Registry()->set('Parameters', 'query_object', 'getData');
		$results = $controller->Display();

		if (Services::Registry()->get('Configuration', 'debug', 0) == 1) {
			Services::Debug()->set(' ');
			Services::Debug()->set('Includer::invokeMVC');
			//Services::Debug()->set('Controller: ' . $cc . ' Action: ' . $action . ' Model: ' . $model . ' ');
			Services::Debug()->set('Extension: ' . Services::Registry()->get('Include', 'extension_title') . ' ID: ' . Services::Registry()->get('Parameters', 'id') . '');
			Services::Debug()->set('Template: ' . Services::Registry()->get('Parameters', 'template_view_path') . '');
			Services::Debug()->set('Wrap: ' . Services::Registry()->get('Parameters', 'wrap_view_path') . '');
		}

		/** html display filters


		echo '<br /><br /><pre>';
		var_dump(Services::Registry()->get('Parameters'));
		echo '</pre>';

		echo '<br /><br /><pre>';
		var_dump(Services::Registry()->get('Include'));
		echo '</pre>';

		 */
		return $results;
	}

	/**
	 * postMVCProcessing
	 * @return bool
	 */
	protected function postMVCProcessing()
	{

	}
}
