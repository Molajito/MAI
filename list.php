<!DOCTYPE html>
<?php
// Fake app
$_columns = array(
		'title'	=> array('title'=>'Title', 	'data_type'=>'text', 	'filter'=>true),
		'owner' => array('title'=>'Owner', 	'data_type'=>'fk',		'filter'=>true),
		'three' => array('title'=>'Column', 'data_type'=>'text', 	'filter'=>false),
		'four' 	=> array('title'=>'Column', 'data_type'=>'text', 	'filter'=>false),
		'five' 	=> array('title'=>'Column', 'data_type'=>'text', 	'filter'=>false),
		'six' 	=> array('title'=>'Column', 'data_type'=>'text', 	'filter'=>true),
		'seven' => array('title'=>'Column', 'data_type'=>'text', 	'filter'=>false),
		'id' 	=> array('title'=>'Id', 	'data_type'=>'int',  	'filter'=>true),
	);

$_rowset = array();
for($i=1; $i<=10; $i++) {
	$array = array();

	foreach ($_columns as $column => $data) {
		if($column == 'id') {
			$_rowset[$i][$column] = $i;
		}
		else if($column == 'owner') {
			$_rowset[$i][$column] = 'Babs G&ouml;sgens';
		}
		else {
			$_rowset[$i][$column] = "$column";
		}
	}
}
?>
<?php include('_head.php') ?>
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
			<a href="list.php#articles" id="collapser">Show Menu</a>

			<table class="responsive grid">
				<caption>Articles</caption>
				<tbody>
					<?php foreach ($_rowset as $row): ?>
					<tr>
						<td><input type="checkbox"></td>
						<?php foreach ($_columns as $column => $data): ?>
						<td><?php echo $row[$column] ?></td>
					<?php endforeach ?>
					</tr>
					<?php endforeach ?>
				</tbody>
				<thead>
					<tr>
						<th></th>
						<?php foreach ($_columns as $column => $data): ?>
						<th id="<?php echo $column ?>">
							<span><?php echo $data['title'] ?></span>
							<?php if($data['filter']) {
								switch ($data['data_type']) {
									case 'text':
										echo '<input type="text" />';
										break;
									case 'int':
										echo '<input type="number" />';
										break;
									case 'fk':
										echo '<select><option>...</option><option>Amy Stephen</option><option>Babs Gösgens</option><option>Cristina Solana</option></select>';
										break;
								}
							}
							?>
						</th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
			</table>

		</section>
	</div>


<?php include('_scripts.php') ?>

</body>
</html>
