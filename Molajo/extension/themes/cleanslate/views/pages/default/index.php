<?php
/**
 * @package     Molajo
 * @subpackage  Theme
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 *
<include:module name=mainmenu wrap=nav />
 */
defined('MOLAJO') or die; ?>
<include:head/>
<include:module name=page-header template=page-header wrap=header />
<include:message />
<include:tag name=sidebar template=sidebar wrap=div/>
<include:request />
<include:module name=page-footer template=page-footer wrap=footer />
<include:defer />
