<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Extension;

use Molajo\Extension\Helpers;
use Molajo\Service\Services;
use Molajo\Controller\ReadController;

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
	 * __construct
	 *
	 * Class constructor.
	 *
	 * @param string $name
	 * @param string $type
	 *
	 * @return null
	 * @since   1.0
	 */
	public function __construct($name = null, $type = null)
	{
		$this->name = $name;
		$this->type = $type;

		Services::Registry()->createRegistry('Include');

		Services::Registry()->set('Parameters', 'includer_name', $this->name);
		Services::Registry()->set('Parameters', 'includer_type', $this->type);
		Services::Registry()->copy('RouteParameters', 'Parameters', 'Request*');
		Services::Registry()->copy('RouteParameters', 'Parameters', 'Theme*');
		Services::Registry()->copy('RouteParameters', 'Parameters', 'Page*');

//		Services::Registry()->get('Parameters', '*');

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
	 * @return mixed
	 * @since   1.0
	 */
	public function process($attributes = array())
	{

		/** attributes from <include:type */
		$this->attributes = $attributes;

		$this->getAttributes();
		/**
		if (Services::Registry()->get('Parameters', 'extension_primary') == true) {
		} else {
		Services::Registry()->copy('RouteParameters', 'Parameters', 'theme*');
		Services::Registry()->copy('RouteParameters', 'Parameters', 'page*');
		}


		echo 'Includer Extension: ' . $this->name . ' Type: ' . $this->type . '<br />';
		echo 'Attributes from Parsing<pre>';
		var_dump($this->attributes);
		echo '</pre>';
		 */
		/** retrieve the extension that will be used to generate the MVC request */
		$this->getExtension();

		/** initialises and populates the MVC request */
		$results = $this->setRenderCriteria();
		//if ($results == false) {
		//	return '';
		//}

		/** language must be there before the extension runs */
		$this->loadLanguage();

		/** instantiate MVC and render output */
		$rendered_output = $this->invokeMVC();

		/** only load media if there was rendered output */
		if ($rendered_output == ''
			&& Services::Registry()->get('Parameters', 'criteria_display_view_on_no_results') == 0
		) {
		} else {
			$this->loadMedia();
			$this->loadViewMedia();
		}

		/** further processing of rendered output - move mustache here? */
		$rendered_output = $this->postMVCProcessing($rendered_output);

		return $rendered_output;
	}

	/**
	 * getAttributes
	 *
	 * Use the view and/or wrap criteria ife specified on the <include statement
	 *
	 * @return null
	 * @since   1.0
	 */
	protected function getAttributes()
	{
		if (count($this->attributes) > 0) {
		} else {
			return;
		}

		//todo filter input appropriately
		foreach ($this->attributes as $name => $value) {

			/** Name used by includer for extension */
			if ($name == 'name' || $name == 'title') {
				Services::Registry()->set('Parameters', 'extension_title', $value);

				/** Used to extract a list of extensions for inclusion */
			} elseif ($name == 'tag') {
				$this->tag = $value;

				/** Template */
			} elseif ($name == 'template' || $name == 'template_view_title'
				|| $name == 'template_view' || $name == 'template_view'
			) {
				$template_id = Helpers::Extension()
					->getInstanceID(CATALOG_TYPE_EXTENSION_TEMPLATE_VIEW, $value);

				if ((int)$template_id == 0) {
				} else {
					Services::Registry()->set('Parameters', 'template_view_id', $template_id);
				}

			} elseif ($name == 'template_view_css_id' || $name == 'template_css_id'
				|| $name == 'template_id'
			) {
				Services::Registry()->set('Parameters', 'template_view_css_id', $value);

			} elseif ($name == 'template_view_css_class' || $name == 'template_css_class'
				|| $name == 'template_class'
			) {
				Services::Registry()->set('Parameters', 'template_view_css_class', str_replace(',', ' ', $value));

				/** Wrap */
			} elseif ($name == 'wrap' || $name == 'wrap_view_title'
				|| $name == 'wrap_view' || $name == 'wrap_view'
			) {
				$wrap_id = Helpers::Extension()
					->getInstanceID(CATALOG_TYPE_EXTENSION_WRAP_VIEW, $value);

				if ((int)$wrap_id == 0) {
				} else {
					Services::Registry()->set('Parameters', 'wrap_view_id', $wrap_id);
				}

			} elseif ($name == 'wrap_view_css_id' || $name == 'wrap_css_id'
				|| $name == 'wrap_id'
			) {
				Services::Registry()->set('Parameters', 'wrap_view_css_id', $value);

			} elseif ($name == 'wrap_view_css_class' || $name == 'wrap_css_class'
				|| $name == 'wrap_class'
			) {
				Services::Registry()->set('Parameters', 'wrap_view_css_class', str_replace(',', ' ', $value));

				/** Model */
			} elseif ($name == 'value') {
				Services::Registry()->set('Parameters', 'model_parameter', $value);

			} elseif ($name == 'model_name' || $name == 'model_type' || $name == 'model_query_object') {
				Services::Registry()->set('Parameters', $name, $value);

			} else {
				/** For security reasons, other parameters must override and match defined parameter values */
				Services::Registry()->set('Parameters', $name, $value, true);
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
	protected function getExtension()
	{
		return;
	}

	/**
	 * setRenderCriteria
	 *
	 * Use the view and/or wrap criteria ife specified on the <include statement
	 * Retrieve View and wrap criteria and path information
	 *
	 * @return bool
	 * @since   1.0
	 */
	protected function setRenderCriteria()
	{
		Services::Registry()->merge('Configuration', 'Parameters', true);

		/** Template  */
		Helpers::View()->get(Services::Registry()->get('Parameters', 'template_view_id'), 'Template');

		/** Wrap  */
		Helpers::View()->get(Services::Registry()->get('Parameters', 'wrap_view_id'), 'Wrap');

		Services::Registry()->delete('Parameters', 'item*');
		Services::Registry()->delete('Parameters', 'list*');
		Services::Registry()->delete('Parameters', 'form*');

		Services::Registry()->sort('Parameters');

		return true;
	}

	/**
	 * loadLanguage
	 *
	 * Loads Language Files for extension
	 *
	 * @return null
	 * @since   1.0
	 */
	protected function loadLanguage()
	{

		Helpers::Extension()->loadLanguage(
			Services::Registry()->get('Parameters', 'extension_path')
		);
		Helpers::Extension()->loadLanguage(
			Services::Registry()->get('Parameters', 'template_view_path')
		);
		Helpers::Extension()->loadLanguage(
			Services::Registry()->get('Parameters', 'wrap_view_path')
		);

		return;
	}

	/**
	 * loadMedia
	 *
	 * Loads Media CSS and JS files for extension and related content
	 *
	 * @return null
	 * @since   1.0
	 */
	protected function loadMedia()
	{
		return $this;
	}

	/**
	 * loadViewMedia
	 *
	 * Loads Media CSS and JS files for Template and Wrap Views
	 *
	 * @return null
	 * @since   1.0
	 */
	protected function loadViewMedia()
	{
		$priority = Services::Registry()->get('Parameters', 'criteria_media_priority_other_extension', 400);

		$file_path = Services::Registry()->get('Parameters', 'template_view_path');
		$url_path = Services::Registry()->get('Parameters', 'template_view_path_url');

		$css = Services::Asset()->addCssFolder($file_path, $url_path, $priority);
		$js = Services::Asset()->addJsFolder($file_path, $url_path, $priority, 0);
		$defer = Services::Asset()->addJsFolder($file_path, $url_path, $priority, 1);

		$file_path = Services::Registry()->get('Parameters', 'wrap_view_path');
		$url_path = Services::Registry()->get('Parameters', 'wrap_view_path_url');

		$css = Services::Asset()->addCssFolder($file_path, $url_path, $priority);
		$js = Services::Asset()->addJsFolder($file_path, $url_path, $priority, 0);
		$defer = Services::Asset()->addJsFolder($file_path, $url_path, $priority, 1);

		return $this;
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

		/**
		echo '<br /><br /><br />';
		echo Services::Registry()->get('Parameters', 'extension_title');
		echo '<br />';
		Services::Registry()->sort('Parameters');
		Services::Registry()->get('Parameters', '*');
		echo '<br /><br /><br />';
		 */
		$controller = new ReadController();

		$controller->set('id', (int)Services::Registry()->get('Parameters', 'source_id'));

		/** Set Parameters */
		Services::Registry()->sort('Parameters');

		$parms = Services::Registry()->getArray('Parameters');

		if (count($parms) > 0) {
			foreach ($parms as $key => $value) {
				$controller->set($key, $value);
			}
		}

		$results = $controller->Display();

		return $results;
	}

	/**
	 * postMVCProcessing
	 *
	 * @return string
	 * @since   1.0
	 */
	protected function postMVCProcessing($rendered_output)
	{
		return $rendered_output;
	}
}
