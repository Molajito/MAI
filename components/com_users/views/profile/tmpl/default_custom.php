<?php
/**
 * @version		$Id: default_custom.php 20211 2011-01-09 17:51:47Z chdemko $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */
defined('_JEXEC') or die;

JLoader::register('MolajoHTMLUsers', JPATH_COMPONENT . '/helpers/html/users.php');
MolajoHTML::register('users.spacer', array('MolajoHTMLUsers','spacer'));

$fieldsets = $this->form->getFieldsets();
if (isset($fieldsets['core']))   unset($fieldsets['core']);
if (isset($fieldsets['params'])) unset($fieldsets['params']);

foreach ($fieldsets as $group => $fieldset): // Iterate through the form fieldsets
	$fields = $this->form->getFieldset($group);
	if (count($fields)):
?>
<fieldset id="users-profile-custom" class="users-profile-custom-<?php echo $group;?>">
	<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
	<legend><?php echo JText::_($fieldset->label); ?></legend>
	<?php endif;?>
	<dl>
	<?php foreach ($fields as $field):
		if (!$field->hidden) :?>
		<dt><?php echo $field->title; ?></dt>
		<dd>
			<?php if (MolajoHTML::isRegistered('users.'.$field->id)):?>
				<?php echo MolajoHTML::_('users.'.$field->id, $field->value);?>
			<?php elseif (MolajoHTML::isRegistered('users.'.$field->fieldname)):?>
				<?php echo MolajoHTML::_('users.'.$field->fieldname, $field->value);?>
			<?php elseif (MolajoHTML::isRegistered('users.'.$field->type)):?>
				<?php echo MolajoHTML::_('users.'.$field->type, $field->value);?>
			<?php else:?>
				<?php echo MolajoHTML::_('users.value', $field->value);?>
			<?php endif;?>
		</dd>
		<?php endif;?>
	<?php endforeach;?>
	</dl>
</fieldset>
	<?php endif;?>
<?php endforeach;?>
