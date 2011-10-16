<?php
/**
 * @version		$Id: modules.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!MolajoFactory::getUser()->authorise('core.manage', 'com_modules')) {
	return JError::raiseWarning(404, MolajoText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Modules');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
