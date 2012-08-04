<?php
use Molajo\Service\Services;
/**
 * @package       Molajo
 * @copyright     2012 Babs Gösgens. All rights reserved.
 * @license       GNU GPL v 2, or later and MIT, see License folder
 */
defined('MOLAJO') or die;

$pageURL = Services::Registry()->get('Triggerdata', 'full_page_url');

$title = Services::Registry()->get('Triggerdata', 'PageTitle');
if ($title == '') {
    $title = $this->row->criteria_title;
} else {
    $title .= '-' . $this->row->criteria_title;
}
$homeURL = Services::Registry()->get('Configuration', 'application_base_url');
$pageURL = Services::Registry()->get('Triggerdata', 'full_page_url');
?>
    <header role="banner">
		<div>
			<h1><a href="<?php echo $homeURL ?>"><i>"</i><span><strong>Molajo</strong> Admin Interface</span></a></h1>
			<nav>
				<dl class="settings">
					<dt><a href="<?php echo $pageURL ?>#search"><i>=</i><span>Search</span></a></dt
					><dd id="search">
						<a href="<?php echo $pageURL ?>#" class="dismiss"><i>g</i><span>Close</span></a>
						<form role="search">
							<fieldset>
								<input type="search" placeholder="Search Resources">
							</fieldset>
						</form>
					</dd
					><dt class="user"><a href="<?php echo $pageURL ?>#user"><img src="/source/Molajo/Extension/Theme/Mai/Images/smile.png" alt="" width="40" height="40" /><span>Babs G&ouml;sgens</span></a></dt
					><dd id="user">
						<a href="<?php echo $pageURL ?>#" class="dismiss"><i>g</i><span>Close</span></a>
						<ul>
							<li><a href="<?php echo $pageURL ?>#">Dropdown Item</a></li>
							<li><a href="<?php echo $pageURL ?>#">Another Dropdown Item</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo $pageURL ?>#">Last Item</a></li>
						</ul>
					</dd
					><dt class="last"><a href="<?php echo $pageURL ?>#molajo-settings"><i>a</i><span>Settings</span></a></dt
					><dd id="molajo-settings">
						<a href="<?php echo $pageURL ?>#" class="dismiss"><i>g</i><span>Close</span></a>


					</dd>
				</dl>
			</nav>
		</div>
    </header>
