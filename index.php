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
<body id="expand" class="debuggg">

	<header role="header" class="row">
<?php include('_header.php') ?>
	</header>

	<div class="row">
		<nav role="navigation">
<?php include('_nav-dl.php') ?>
		</nav>
		<section role="main">
			<a href="#expand" id="expander"></a>
		</section>
	</div>


<?php include('_scripts.php') ?>

</body>
</html>
