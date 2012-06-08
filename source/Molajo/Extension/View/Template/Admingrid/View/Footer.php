<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
use Molajo\Service\Services;
defined('MOLAJO') or die;
$columnCount = Services::Registry()->get('Trigger', 'GridTableColumns'); ?>
</tbody>
	<tfoot>
		<tr>
			<td colspan="<?php echo $columnCount; ?>">
				<?php //echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
</table>
