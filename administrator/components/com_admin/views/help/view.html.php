<?php
/**
 * @version		$Id: view.html.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
/**
 * HTML View class for the Admin component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_admin
 * * * @since		1.0
 */
class AdminViewHelp extends JView
{
	/**
	 * @var string the search string
	 */
	protected $help_search=null;
	/**
	 * @var string the page to be viewed
	 */
	protected $page=null;
	/**
	 * @var string the iso language tag
	 */
	protected $lang_tag=null;
	/**
	 * @var array Table of contents
	 */
	protected $toc=null;
	/**
	 * @var string url for the latest version check
	 */
	protected $latest_version_check= 'http://molajo.org/download.html';
	/**
	 * @var string url for the start here link.
	 */
	protected $start_here = null;

	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		// Initialise variables.
		$this->help_search			= $this->get('HelpSearch');
		$this->page					= $this->get('Page');
		$this->toc					= $this->get('Toc');
		$this->lang_tag				= $this->get('LangTag');
		$this->latest_version_check	= $this->get('LatestVersionCheck');

		$this->addToolbar();
		parent::display($tpl);
	}
	/**
	 * Setup the Toolbar
	 *
	 * @since	1.0
	 */
	protected function addToolbar()
	{
		MolajoToolbarHelper::title(MolajoText::_('COM_ADMIN_HELP'), 'help_header.png');
	}
}

