<?php
/**
 * @package     Molajo
 * @copyright   2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die; ?>
<include:head/>
<include:module name=PageHeader template=Header wrap=Header wrap_class="span16"/>
<include:message/>
<body class="cf">
<div id="container" class="cf">
<include:request template="section" wrap="article" wrap_class="span11"/>
<include:module name="ArticleList" template="aside" wrap="aside" wrap_class="span5 col"/>
</div> <!--! end of #container -->
<include:module name=PageFooter template=PageFooter wrap=Footer/>
<include:defer/>

<div class="banner-wrap">
	<div class="banner">
		<h1 id="logo"><span class="logo"><b>Simple</b>Grid</span></h1>
		<p class="intro">Molajito. Infinite nesting. One class per element. <strong>Simple</strong>.</p>
	</div>
</div>
<div class="grid">
<div class="row">
	<div class="slot-0-1 feature">
		<h2>Molajito</h2>
		<img src="Image/responsive.png" alt="Illustration of Molajito" />
		<p><span class="logo"><b>S</b>G</span> is prepared for 4 distinct ranges of screen size: screens  720px, screens  720px, screens  than 985px, and screens  than 1235px. This means that people visiting your site will receive a layout that's tuned to the size of their browser window. Say goodbye to horizontal scrollbars.</p>
	</div>
	<div class="slot-2-3 feature">
		<h2>Self-Aware</h2>
		<img src="Image/self-aware.png" alt="Illustration of Self-Aware" />
		<p>Most grid systems use one class for all units of a single size, leaving units oblivious to where they fit into the grid. That leaves you with <code>.first</code> and <code>.last</code> classes. With <span class="logo"><b>S</b>G</span> each slot knows whether it's first, middle, or last in its respective row; no extra classes needed.</p>
	</div>
	<div class="slot-4-5 feature">
		<h2>Sensible</h2>
		<img src="Image/sensible.png" alt="Illustration of Sensible" />
		<p>Creating the code for your grid should be the least of your problems when building a site. That's why <span class="logo"><b>S</b>G</span> keeps things simple and straightforward with as little markup and classes as possible. Even nesting grid slots doesn't require extra classes.</p>
	</div>
</div>
<hr />
<div class="row">
	<div class="slot-6">
		<h2>How to Use It</h2>
		<p>Start with the <code>.grid</code> wrapper. Inside the wrapper Create <code>div</code>s with a class of <code>.row</code>. Inside each row, create slots for content. Apply the associated <code>.slot-#</code> class, and voil&agrave;!</p>
	</div>
	<div class="slot-7">
		<h2>See It In Action</h2>
		<p>This very site is built on <span class="logo"><b>S</b>G</span>. But to give you a better idea of it's capabilities, I've created an example grid below. It demonstrates a variety of the combinations possible.</p>
	</div>
	<div class="slot-7">
		<h2>Who Made It?</h2>
		<p>Hi, I'm <a href="http://conor.cc">Conor Muirhead</a>. The idea and foundation of <span class="logo"><b>S</b>G</span> was originally developed by the great team at <a href="http://crowdfavorite">Crowd Favorite</a>. Since then, I've continued to work on a fork of the project.</p>
	</div>
	<div class="slot-9 mod mod-download">
		<h2>Download</h2>
		<p>Usage permitted under the <a href="http://opensource.org/licenses/mit-license.php">MIT License</a>.</p>
		<p><a class="bttn" href="/downloads/SimpleGrid.zip">Download <span class="logo"><b>Simple</b>Grid</span></a></p>
	</div>
</div>

<hr />

<div class="example-grids">
	<h2>Basic 6-Column Grid Slots</h2>
	<p>Slots 0-5 represent the 6-column grid. These slots can be combined to create a 3-column grid too.</p>
	<div class="row">
		<div class="slot-0">
			<p>
				.slot-0
			</p>
		</div>

		<div class="slot-1">
			<p>
				.slot-1
			</p>
		</div>

		<div class="slot-2">
			<p>
				.slot-2
			</p>
		</div>

		<div class="slot-3">
			<p>
				.slot-3
			</p>
		</div>

		<div class="slot-4">
			<p>
				.slot-4
			</p>
		</div>

		<div class="slot-5">
			<p>
				.slot-5
			</p>
		</div>
	</div><!-- / .row -->

	<h2>Basic 4-Column Grid Slots</h2>
	<p>Slots 6-9 represent the 4-column grid.
	<div class="row">
		<div class="slot-6">
			<p>
				.slot-6
			</p>
		</div>

		<div class="slot-7">
			<p>
				.slot-7
			</p>
		</div>

		<div class="slot-8">
			<p>
				.slot-8
			</p>
		</div>

		<div class="slot-9">
			<p>
				.slot-9
			</p>
		</div>
	</div><!-- /.row -->

	<h2>Combining Slots to Create Super Slots</h2>
	<p>Combining slots is as simple as connecting their slot numbers with dashes.</p>
	<div class="row">
		<div class="slot-0">
			<p>
				.slot-0
			</p>
		</div>

		<div class="slot-1-2">
			<p>
				.slot-1-2
			</p>
		</div>

		<div class="slot-3-4-5">
			<p>
				.slot-3-4-5
			</p>
		</div>
	</div><!-- /.row -->

	<h2>Using Both 4- &amp; 6-Column Slots In a Row</h2>
	<p>In some situations, you might want to combine the 4- &amp; 6-column grids in a single row. Just use the slot numbers that best represent each slots position in that row.</p>
	<div class="row">
		<div class="slot-0">
			<p>
				.slot-0
			</p>
		</div>

		<div class="slot-7">
			<p>
				.slot-7
			</p>
		</div>

		<div class="slot-8">
			<p>
				.slot-8
			</p>
		</div>

		<div class="slot-4-5">
			<p>
				.slot-4-5
			</p>
		</div>
	</div><!-- /.row -->

	<h2>Nesting Slots &amp; Rows</h2>
	<p>Just add <code>.row</code>s within <code>.row</code>s, and then assemble the slots in each row as per usual.</p>
	<div class="row">
		<div class="slot-0">
			<p>
				.slot-0
			</p>
		</div>
		<!-- end .slot-0 -->

		<div class="slot-1-2-3">
			<p>
				.slot-1-2-3
			</p>
			<div class="row">
				<div class="slot-1">
					<p>
						.slot-1
					</p>
					<div class="row">
						<div class="slot-1">
							<p>
								.slot-1
							</p>
						</div>
					</div>
				</div>
				<div class="slot-2-3">
					<p>
						.slot-2-3
					</p>
					<div class="row">
						<div class="slot-2">
							<p>
								.slot-2
							</p>
						</div>
						<div class="slot-3">
							<p>
								.slot-3
							</p>
						</div>
					</div>
					<div class="row">
						<div class="slot-2-3">
							<p>
								.slot-2-3
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end .slot-1-2-3 -->

		<div class="slot-4-5">
			<p>
				.slot-4-5
			</p>
			<div class="row">
				<div class="slot-4">
					<p>
						.slot-4
					</p>
				</div>
				<div class="slot-5">
					<p>
						.slot-5
					</p>
				</div>
			</div>
		</div>
		<!-- end .slot-5 -->
	</div><!-- /.row -->
</div><!-- / .example-grids -->
<div class="row footer">
	<p>&copy; <a href="http://conor.cc">Conor Muirhead</a></p>
</div>
</div><!-- / .grid -->
