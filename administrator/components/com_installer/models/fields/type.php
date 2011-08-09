<?php
/**
 * @version		$Id: type.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Form Field Place class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * * * @since		1.0
 */
class JFormFieldType extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Type';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 * @since	1.0
	 */
	protected function getInput()
	{
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		$options = array();
		foreach ($this->element->children() as $option) {
			$options[] = JHtml::_('select.option', $option->attributes('value'), JText::_(trim($option->data())));
		}

		$db = MolajoFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('type')->from('#__extensions');
		$db->setQuery($query);
		$types = array_unique($db->loadResultArray());
		foreach($types as $type)
		{
			$options[] = JHtml::_('select.option', $type, JText::_('COM_INSTALLER_TYPE_'. strtoupper($type)));
		}

		$return = JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $this->value, $this->id);

		return $return;
	}
}