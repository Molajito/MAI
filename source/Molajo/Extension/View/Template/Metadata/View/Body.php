<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see license folder
 */
defined('MOLAJO') or die;

$html5 = $this->row->html5;
$end = $this->row->end; ?>
	<meta <?php echo $this->row->label; ?>="<?php echo $this->row->name; ?>" content="<?php echo $this->row->content; ?>"<?php echo $end; ?>
