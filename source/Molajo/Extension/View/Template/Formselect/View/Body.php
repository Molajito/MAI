<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
use Molajo\Service\Services;
defined('MOLAJO') or die;
?>
<option value="<?php echo $this->row->id; ?>"<?php echo $this->row->selected; ?>><?php echo $this->row->value; ?></option>
