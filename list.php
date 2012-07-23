<!DOCTYPE html>
<?php
// Fake app
$_columns = array(
		'title'	=> array('title'=>'Title', 	'data_type'=>'text', 	'filter'=>true),
		'owner' => array('title'=>'Owner', 	'data_type'=>'fk',		'filter'=>true),
		'three' => array('title'=>'Column', 'data_type'=>'text', 	'filter'=>false),
		'four' 	=> array('title'=>'Column', 'data_type'=>'text', 	'filter'=>false),
		'five' 	=> array('title'=>'Column', 'data_type'=>'text', 	'filter'=>false),
		'six' 	=> array('title'=>'State', 'data_type'=>'state', 	'filter'=>true),
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
		else if($column == 'title') {
			$_rowset[$i][$column] = 'Lorem ipsum dolor sit amet';
		}
		else if($column == 'owner') {
			$_rowset[$i][$column] = 'Babs G&ouml;sgens';
		}
		else if($column == 'six') {
			$_rowset[$i][$column] = 'Enabled';
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

			<h1>Articles</h1>

			<dl id="table_config">
				<dt><a href="#table_config"><i>a</i><span>Configure Table Columns</span></a></dt>
				<dd>
					<a href="list.php#articles" class="dismiss"><i>g</i><span>Close</span></a>
				</dd>
			</dl>
			<table class="responsive grid">
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
										echo '<select><option>All</option><option>Amy Stephen</option><option>Babs Gösgens</option><option>Cristina Solana</option></select>';
										break;
									case 'state':
										echo '<select><option>All</option><option>Enabled</option><option>Disabled</option><option>Archived</option><option>Etc.</option></select>';
										break;
								}
							}
							?>
						</th>
						<?php endforeach ?>
					</tr>
					<tr id="batch-actions">
						<th colspan="<?php echo count($_columns) + 1 ?>">
							With selected: <select id="batch-options"><option>Enable</option><option>Disable</option><option>Archive</option><option>Delete</option><option value="more">More options...</option></select>
							<a href="list.php#articles" class="dismiss"><i>g</i><span>Close</span></a>
							<div>
								<h2>More options ...</h2>
							</div>
						</th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
			</table>

		</section>
	</div>


<?php include('_scripts.php') ?>

<script>
<!--
	jQuery(document).ready(function($){
		$('#batch-options').change(function(){
			if($(this).val()==='more') {
				location.href = 'list.php#batch-actions';
			}
		});
	});
-->
</script>

</body>
</html>
