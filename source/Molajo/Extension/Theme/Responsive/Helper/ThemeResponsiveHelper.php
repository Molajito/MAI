<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\Extension\Theme\Responsive\Helper;

use Molajo\Extension\Helper\MustacheHelper;
use Molajo\Service\Services;

/**
 * Helper
 *
 * @package     Molajo
 * @subpackage  Helper
 * @since       1.0
 */
Class ThemeResponsiveHelper extends MustacheHelper
{
    /**
     * Parameters
     *
     * @var    object
     * @since  1.0
     */
    public $parameters;

    /**
     * loadMedia
     *
     * If exists, automatically called in the Theme Rendering process
     * Use this method to load external media, special metadata or links
     *
     * http://weblogs.asp.net/jgalloway/archive/2010/01/21/
     *  using-cdn-hosted-jquery-with-a-local-fall-back-copy.aspx
     *
     * Uses https://github.com/filamentgroup/Responsive-Images
     * Need image service to <img src="small.jpg?full=large.jpg" >
     *
     * http://coding.smashingmagazine.com/2012/01/16/resolution-independence-with-svg/
     *
     * http://24ways.org/2011/displaying-icons-with-fonts-and-data-attributes
     *
     * http://keyamoon.com/icomoon/#toHome
     * adapt.js
     * http://responsivepx.com/
     * http://mattkersley.com/responsive/
     * http://www.responsinator.com/
     * http://quirktools.com/screenfly/
     *
     * @since  1.0
     */
    public function loadMedia()
    {
        /** Load after doctype and before head */
//<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
//<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
//<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
//<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
//<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

        /** Mobile Specific Meta */
        Services::Registry()->set('Metadata', 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1');

        /** Favicons */
        Services::Document()->add_link(
            $url = EXTENSIONS_THEMES_URL
                . '/' . Services::Registry()->get('Theme', 'title')
                . '/' . 'images/apple-touch-icon.png',
            $relation = 'apple-touch-icon-precomposed',
            $relation_type = 'rel',
            $attributes = array()
        );
        Services::Document()->add_link(
            $url = EXTENSIONS_THEMES_URL
                . '/' . Services::Registry()->get('Theme', 'title')
                . '/' . 'images/apple-touch-icon-72x72.png',
            $relation = 'apple-touch-icon-precomposed',
            $relation_type = 'rel',
            $attributes = array('sizes,72x72')
        );
        Services::Document()->add_link(
            $url = EXTENSIONS_THEMES_URL
                . '/' . Services::Registry()->get('Theme', 'title')
                . '/' . 'images/apple-touch-icon-114x114.png',
            $relation = 'apple-touch-icon-precomposed',
            $relation_type = 'rel',
            $attributes = array('sizes,114x114')
        );

        /** jQuery CDN and fallback */
        Services::Document()->add_js
        ('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', 1000);

        /** Modernizer */
        Services::Document()->add_js
        ('http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.5.3/modernizr.min.js', 1000);

        $url = EXTENSIONS_THEMES_URL
            . '/' . Services::Registry()->get('Theme', 'title')
            . '/' . 'js/fallback/jquery-1.7.1.min.js';

        $fallback = "
        if (typeof jQuery == 'undefined') {
            document.write(unescape(" . '"' . "%3Cscript src='" . $url . "' type='text/javascript'%3E%3C/script%3E" . '"' . "));
         }";

        Services::Document()->add_js_declaration
        ($fallback, 'text/javascript', 1000);

        $image_breakpoint = "
        var rwd_images = {
            //set the width breakpoint to 600px instead of 480px
            widthBreakPoint: 600
         }";

        Services::Document()->add_js_declaration
        (rwd_images, 'text/javascript', 1000);
    }
}
