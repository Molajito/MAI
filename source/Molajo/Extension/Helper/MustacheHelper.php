<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Extension\Helper;

use Molajo\Service\Services;
use Mustache\Mustache;

defined('MOLAJO') or die;

/**
 * MustacheHelper
 *
 * @package       Molajo
 * @subpackage    Helper
 * @since         1.0
 */
Class MustacheHelper extends Mustache
{
	/**
	 * Static instance
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected static $instance;

	/**
	 * Parameters passed in by Controller when class instantiated
	 *
	 * @var    object
	 * @since  1.0
	 */
	public $parameters;

	/**
	 * $data
	 *
	 * Allows collection of any set of data for a single $item
	 *
	 * @var    array
	 * @since  1.0
	 */
	public $data = array();

	/**
	 * $rows
	 *
	 * Retains pointer to current row contained within the $data array
	 *
	 * @var    int
	 * @since  1.0
	 */
	protected $rows = 0;

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
			self::$instance = new MustacheHelper();
		}
		return self::$instance;
	}

	/**
	 * items
	 *
	 * Returns a single row of information to mustache
	 * around the {# item } {/ item } controlbreak
	 *
	 * tracks row number in #this->rows so that resultset can be exploited
	 *
	 * @return ArrayIterator
	 * @since  1.0
	 */
	public function items()
	{
		$this->rows++;
		return new \ArrayIterator($this->data);
	}

	/**
	 * gravatar
	 *
	 * Using the $this->row value, the data element introtext can be
	 * printed for this specific article.
	 *
	 * @return string
	 * @since  1.0
	 */
	public function gravatar()
	{
		$this->analytics();

		return Services::Url()->getGravatar(
			$email = 'AmyStephen@gmail.com',
			$s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()
		);
	}

	/**
	 * analytics
	 *
	 * Google Analytics
	 *
	 * @return mixed
	 */
	public function analytics()
	{
		$code = Services::Registry()->get('Configuration', 'google_analytics_code', 'UA-1682054-15');
		if (trim($code) == '') {
			return;
		}
		$analytics = "
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '" . $code . "']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
        ";
		Services::Document()->add_js_declaration($analytics, 'text/javascript', 1);
	}

	/**
	 * placeholder
	 *
	 * @return mixed
	 */
	public function placeholder()
	{
		return Services::Text()->getPlaceHolderText(55, array('html', 'lorem'));
	}

	/**
	 * intro
	 *
	 * Using the $this->row value, the data element introtext can be
	 * printed for this specific article.
	 *
	 * @return string
	 * @since  1.0
	 */
	public function intro()
	{
		return Services::Text()->smilies($this->items['content_text']);
	}

	/**
	 * hello
	 *
	 * Returns hello for {{ hello }}
	 * Template example overrides for different result
	 *
	 * @return string
	 * @since  1.0
	 */
	public function hello()
	{
		return 'Hello!';
	}

	/**
	 * profile
	 *
	 * Renders the Author Profile Module for this article
	 *
	 * $results  text
	 * $since    1.0
	 */
	public function profile()
	{
		$class = 'Molajo\\Extension\\Includer\\ModuleIncluder';
        $rc = new $class ('profile', '');

		$attributes = array();
		$attributes['name'] = 'dashboard';
		$attributes['template'] = 'dashboard';
		$attributes['wrap'] = 'section';
		$attributes['id'] = $this->items['id'];

        return $rc->process($attributes);
	}
}
