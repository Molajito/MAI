<?php
use Molajo\Service\Services;

/**
 * @package   Molajo
 * @subpackage  Views
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

$date = Services::Date()
	->getDate()
	->format('Y-m-d-H-i-s');

$current_year = Services::Date()
	->getDate()
	->format('Y');
?>
<p>
	<?php echo '&#169;' . $current_year . ' ' . Services::Registry()->get('Configuration', 'site_title'); ?>
	<a href="<?php echo $this->row->link; ?>">
		<?php echo $this->row->linked_text; ?> v.<?php echo MOLAJOVERSION; ?></a>
	<?php echo ' ' . $this->row->remaining_text; ?>.
</p>
