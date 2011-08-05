<?php
/**
 * @version		$Id: controller.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Templates manager master display controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * @since		1.6
 */
class TemplatesController extends JController
{
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $DefaultView = 'styles';

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/templates.php';

		// Load the submenu.
		TemplatesHelper::addSubmenu(JRequest::getCmd('view', 'styles'));

		$view		= JRequest::getCmd('view', 'styles');
		$layout 	= JRequest::getCmd('layout', 'default');
		$id			= JRequest::getInt('id');

		// Check for edit form.
		if ($view == 'style' && $layout == 'edit' && !$this->checkEditId('com_templates.edit.style', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('MOLAJO_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_templates&view=styles', false));

			return false;
		}

		parent::display();
	}

	/**
	* Preview Template
	*/
	function preview()
	{
		JRequest::setVar('view', 'prevuuw');
		parent::display();
	}
}