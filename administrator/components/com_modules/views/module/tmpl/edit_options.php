<?php
/**
 * @version		$Id: edit_options.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

	$fieldSets = $this->form->getFieldsets('params');

	foreach ($fieldSets as $name => $fieldSet) :
		$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_MODULES_'.$name.'_FIELDSET_LABEL';
		echo MolajoHTML::_('sliders.panel',MolajoText::_($label), $name.'-options');
			if (isset($fieldSet->description) && trim($fieldSet->description)) :
				echo '<p class="tip">'.$this->escape(MolajoText::_($fieldSet->description)).'</p>';
			endif;
			?>
		<fieldset class="panelform">
		<?php $hidden_fields = ''; ?>
		<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset($name) as $field) : ?>
			<?php if (!$field->hidden) : ?>
			<li>
				<?php echo $field->label; ?>
				<?php echo $field->input; ?>
			</li>
			<?php else : $hidden_fields.= $field->input; ?>
			<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<?php echo $hidden_fields; ?>
		</fieldset>
	<?php endforeach; ?>
