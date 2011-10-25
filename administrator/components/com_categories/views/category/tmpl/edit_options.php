<?php
/**
 * @version		$Id: edit_options.php 21503 2011-06-09 22:58:13Z dextercowley $
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die; ?>

<?php echo MolajoHTML::_('sliders.panel',MolajoText::_('JGLOBAL_FIELDSET_PUBLISHING'), 'publishing-details'); ?>

	<fieldset class="panelform">
		<ul class="adminformlist">

			<li><?php echo $this->form->getLabel('created_user_id'); ?>
			<?php echo $this->form->getInput('created_user_id'); ?></li>

			<?php if (intval($this->item->created_time)) : ?>
				<li><?php echo $this->form->getLabel('created_time'); ?>
				<?php echo $this->form->getInput('created_time'); ?></li>
			<?php endif; ?>

			<?php if ($this->item->modified_user_id) : ?>
				<li><?php echo $this->form->getLabel('modified_user_id'); ?>
				<?php echo $this->form->getInput('modified_user_id'); ?></li>

				<li><?php echo $this->form->getLabel('modified_time'); ?>
				<?php echo $this->form->getInput('modified_time'); ?></li>
			<?php endif; ?>

		</ul>
	</fieldset>

<?php $fieldSets = $this->form->getFieldsets('params');

foreach ($fieldSets as $name => $fieldSet) :
	$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_CATEGORIES_'.$name.'_FIELDSET_LABEL';
	echo MolajoHTML::_('sliders.panel',MolajoText::_($label), $name.'-options');
	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<p class="tip">'.$this->escape(MolajoText::_($fieldSet->description)).'</p>';
	endif;
	?>
	<fieldset class="panelform">
	<ul class="adminformlist">

		<?php foreach ($this->form->getFieldset($name) as $field) : ?>
			<li><?php echo $field->label; ?>
			<?php echo $field->input; ?></li>
		<?php endforeach; ?>

		<li><?php echo $this->form->getLabel('note'); ?>
		<?php echo $this->form->getInput('note'); ?></li>
	</ul>

	</fieldset>
<?php endforeach; ?>