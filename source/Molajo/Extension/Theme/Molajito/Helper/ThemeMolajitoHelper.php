<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Extension\Theme\Molajito\Helper;

use Mustache\Mustache;
use Molajo\Service\Services;

/**
 * Theme Helper
 *
 * @since       1.0
 */
Class ThemeMolajitoHelper extends Mustache
{
    /**
     * Parameters
     *
     * @var    object
     * @since  1.0
     */
    public $parameters;

    /**
     * loadMedia - automatically loaded in last step of Rendering process
     *     Method can be used to load external media, special metadata or links
     *
     * @since  1.0
     */
    public function loadMedia()
    {
        /** Theme Folder */
        $theme = Services::Registry()->get('Parameters', 'theme_path_node');

        /** IE */
        Services::Metadata()->set('X-UA-Compatible', 'IE=EmulateIE7; IE=EmulateIE9', 'http-equiv');

        /** Mobile Specific Meta: Sets the viewport width to device width for mobile */
        Services::Metadata()->set(
            'viewport', 'width=device-width, initial-scale=1.0', 'name'
        );

        /** Favicons */

        /** For non-Retina iPhone, iPod Touch, and Android 2.1+ devices */
        Services::Asset()->addLink(
            $url = EXTENSIONS_THEMES_URL . '/' . $theme
                . '/' . 'images/favicons/apple-touch-icon-precomposed.png',
            $relation = 'apple-touch-icon-precomposed',
            $relation_type = 'rel',
            $attributes = array()
        );
        /** For first- and second-generation iPad */
        Services::Asset()->addLink(
            $url = EXTENSIONS_THEMES_URL . '/' . $theme
                . '/' . 'images/favicons/apple-touch-icon-72x72-precomposed.png',
            $relation = 'apple-touch-icon-precomposed',
            $relation_type = 'rel',
            $attributes = array('sizes,72x72')
        );
        /** For iPhone with high-resolution Retina display */
        Services::Asset()->addLink(
            $url = EXTENSIONS_THEMES_URL . '/' . $theme
                . '/' . 'images/favicons/apple-touch-icon-114x114-precomposed.png',
            $relation = 'apple-touch-icon-precomposed',
            $relation_type = 'rel',
            $attributes = array('sizes,114x114')
        );
        /** For third-generation iPad with high-resolution Retina display */
        Services::Asset()->addLink(
            $url = EXTENSIONS_THEMES_URL . '/' . $theme
                . '/' . 'images/favicons/apple-touch-icon-144x144-precomposed.png',
            $relation = 'apple-touch-icon-precomposed',
            $relation_type = 'rel',
            $attributes = array('sizes,144x144')
        );

        /** Media Queries to load CSS
        Services::Asset()->addCss(
        $url = EXTENSIONS_THEMES_URL . '/' . $theme . '/' . 'css/grid/base.css',
        $priority=1000,
        $mimetype='test/css',
        $media='all',
        $conditional='',
        $attributes=array());
         */

        /** jQuery CDN and fallback */
//        Services::Asset()->addJs('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', 1);

        /** Modernizer */
//        Services::Asset()->addJs('http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.5.3/modernizr.min.js', 10000);

        /** HTML5 Shiv */
//		Services::Asset()->addJs('http://html5shiv.googlecode.com/svn/trunk/html5.js', 10000);

//        $url = EXTENSIONS_THEMES_URL . '/' . $theme  . '/' . 'js/fallback/jquery-1.7.2.min.js';

//        $fallback = "
//        if (typeof jQuery == 'undefined') {
//            document.write(unescape(" . '"' . "%3Cscript src='" . $url . "' type='text/javascript'%3E%3C/script%3E" . '"' . "));
//         }";

//        Services::Asset()->addJSDeclarations($fallback, 'text/javascript', 10000);

        return;
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
        Services::Asset()->addJSDeclarations($analytics, 'text/javascript', 1);
    }

    /**
     * placeholder
     *
     * @return mixed
     */
    public function placeholder()
    {
        return Services::Text()->getPlaceHolderText(106, array('html', 'lorem'));
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
        if (isset($this->items['content_text'])) {
            return Services::Text()->smilies($this->items['content_text']);
        }
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
        return;
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
