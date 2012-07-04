<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 *
 * <include:module name=blockquote template=blockquote wrap=aside/>
 */
defined('MOLAJO') or die; ?>
{{# items }}
<header>
    <h2>{{title}}</h2>

    <h3>{{hello}}</h3>
</header>

    <img src="{{gravatar}}" alt="{{name}}" class="alignright"/>
    {{{intro}}}
    {{{fulltext}}}
    <footer>
        {{start_publishing_datetime}}
    </footer>
{{/ items }}
    {{{dashboard}}}
    {{{placeholder}}}
