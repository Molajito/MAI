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

				<ul class="suckerfish settings">
					<li class="search">
						<a href="<?php echo $pageURL ?>#search"><i>=</i><span>Search</span></a>
						<form role="search">
							<fieldset>
								<input type="search" placeholder="Search Resources">
							</fieldset>
						</form>
					</li
					><li class="user">
						<a href="<?php echo $pageURL ?>#user">
							<img src="/source/Molajo/Extension/Theme/Mai/Images/smile.png" alt="" with="40" height="40" />
							<span>Babs G&ouml;sgens</span>
						</a>
						<ul id="user" class="right">
							<li><a href="<?php echo $pageURL ?>#">Dropdown Item</a></li>
							<li><a href="<?php echo $pageURL ?>#">Another Dropdown Item</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo $pageURL ?>#">Last Item</a></li>
						</ul>
					</li
					><li class="config last">
						<a href="<?php echo $pageURL ?>#molajo-settings"><i>a</i><span>Settings</span></a>
					</li>
				</ul>

			</nav>
		</div>
    </header>
