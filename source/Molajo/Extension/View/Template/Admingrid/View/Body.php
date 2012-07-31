<?php
use Molajo\Service\Services;
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
defined('MOLAJO') or die; ?>
<tr<?php echo $this->row->grid_row_class; ?>><?php
    $columnCount = 1;
	$nowrap = ' nowrap ';
	$first = 1;
    $columnArray = Services::Registry()->get('Triggerdata', 'AdminGridTableColumns');
    foreach ($columnArray as $column) {       ?>

        <td<?php echo $this->row->css_class; ?><?php echo $nowrap; ?>><?php
            if ($column == 'title') {
                echo '<a href="' . $this->row->catalog_id_url . '">';
            }
            echo $this->row->$column;
            if ($column == 'title') {
                echo '</a>';
            } ?>
        </td><?php

		if ($first == 1) {
			$first = 0;
			$nowrap = '';
		}

		$columnCount++;
    }
    ?>
    <td class="center last">
        <input type=checkbox value="<?php echo $checked; ?>">
    </td>
</tr>
