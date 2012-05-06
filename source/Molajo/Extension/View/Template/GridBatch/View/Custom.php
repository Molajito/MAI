<?php
/**
 * @package   Molajo
 * @subpackage  Views
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die; ?>
<fieldset class="batch">
	<legend><?php echo Services::Language()->translate('GRID_BATCH_OPTIONS');?></legend>
	<?php echo "change acl";?>

	<?php echo "change categories"; ?>

	<button type="submit">
		<?php echo Services::Language()->translate('GRID_SUBMIT'); ?>
	</button>
	<button type="button">
		<?php echo Services::Language()->translate('GRID_CLEAR'); ?>
	</button>
</fieldset>
</form>
