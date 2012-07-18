<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8" />

	<!-- Set the viewport width to device width for mobile -->
	<meta name="viewport" content="width=device-width" />

	<title>Welcome to MAI (Molajo Administrator Interface)</title>

	<!-- Included CSS Files -->
	<link rel="stylesheet" href="stylesheets/app.css">

	<script src="javascripts/foundation/modernizr.foundation.js"></script>

	<!-- IE Fix for HTML5 Tags -->
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</head>
<body id="expand">

	<header role="header" class="row">
		<div>
			<h1><strong>Molajo</strong> Admin Interface</h1>
			<nav>
				<dl class="settings">
					<dt><a href="#search"><i>=</i><span>Search</span></a></dt
					><dd id="search">
						<form role="search">
							<fieldset>
								<input type="search" placeholder="Search Resources">
							</fieldset>
						</form>
					</dd
					><dt class="user"><a href="#user"><img src="media/smile.png" width="40" height="40" /><span>Babs G&ouml;sgens</span></a></dt
					><dd id="user">
						<ul>
							<li><a href="#">Dropdown Item</a></li>
							<li><a href="#">Another Dropdown Item</a></li>
							<li class="divider"></li>
							<li><a href="#">Last Item</a></li>
						</ul>
					</dd
					><dt class="last"><a href="#settings"><i>a</i><span>Settings</span></a></dt
					><dd id="settings">
						<a href="" class="close"><i></i><span>Close</span></a>
					Settings</dd>
				</dl>
			</nav>
		</div>
	</header>

	<div class="row">
		<nav role="navigation">
			<?php include('nav-dl.php') ?>
		</nav>
		<section role="main">
			<a href="#expand" id="expander"></a>
		</section>
	</div>


	<!-- Included JS Files -->
	<script src="javascripts/jquery.min.js"></script>
	<script src="javascripts/foundation/jquery.reveal.js"></script>
	<script src="javascripts/foundation/jquery.orbit-1.4.0.js"></script>
	<script src="javascripts/foundation/jquery.customforms.js"></script>
	<script src="javascripts/foundation/jquery.placeholder.min.js"></script>
	<script src="javascripts/foundation/jquery.tooltips.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
	<script src="javascripts/molajo/navigation.js"></script>
	<!--script src="javascripts/jquery.flip.min.js"></script-->

</body>
</html>
