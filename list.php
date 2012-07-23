<!DOCTYPE html>
<?php
// Fake app
$_columns = array(
		'title'	=> array('title'=>'Title', 	'show'=>true, 'data_type'=>'text', 	'filter'=>true),
		'owner' => array('title'=>'Owner', 	'show'=>true, 'data_type'=>'fk',		'filter'=>true),
		'three' => array('title'=>'Column 3', 'show'=>true, 'data_type'=>'text', 	'filter'=>false),
		'four' 	=> array('title'=>'Column 4', 'show'=>true, 'data_type'=>'text', 	'filter'=>false),
		'five' 	=> array('title'=>'Column 5', 'show'=>false, 'data_type'=>'text', 	'filter'=>false),
		'six' 	=> array('title'=>'State', 	'show'=>true, 'data_type'=>'state', 	'filter'=>true),
		'seven' => array('title'=>'Column', 'show'=>true, 'data_type'=>'text', 	'filter'=>false),
		'id' 	=> array('title'=>'Id', 	'show'=>true, 'data_type'=>'int',  	'filter'=>true),
	);

// We will need to know the number of visible columns
$numCols = 0;
foreach ($_columns as $data) {
	if($data['show']) {
		$numCols ++;
	}
}

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

	<header role="banner" class="row">
<?php include('_header.php') ?>
	</header>

	<div class="row">
		<nav role="navigation">
<?php include('_nav-dl.php') ?>
		</nav>
		<section role="main">

			<a href="#expand" id="expander"></a>
			<!-- <a href="list.php#articles" id="collapser">Show Menu</a> -->

			<h1>Articles</h1>

			<nav class="pagination">
				<ul>
					<li>Page <input type="number" min="1" max="9" /> of 9 pages</li>
					<li>View <select><?php for($i=1; $i<=10; $i++): echo '<option value="'.($i*10).'">'.($i*10).'</option>'; endfor ?></select> per page</li>
					<li>Total number of records: 84</li>
				</ul>
			</nav>

			<dl id="table_config">
				<dt><a href="#table_config"><i>a</i><span>Configure Table Columns</span></a></dt>
				<dd>
					<a href="list.php#articles" class="dismiss"><i>g</i><span>Close</span></a>
					<table>
						<thead>
							<tr>
								<th>Column</th>
								<th>Show</th>
								<th>Use as Filter</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($_columns as $column => $data): ?>
							<tr>
								<td><?php echo $data['title'] ?></td>
								<td><select><option value="1"<?php if($data['show']): ?> selected="selected"<?php endif ?>>Yes</option><option value="0"<?php if(!$data['show']): ?> selected="selected"<?php endif ?>>No</option></select></td>
								<td><select><option value="1"<?php if($data['filter']): ?> selected="selected"<?php endif ?>>Yes</option><option value="0"<?php if(!$data['filter']): ?> selected="selected"<?php endif ?>>No</option></select></td>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
					<button>Apply</button>
				</dd>
			</dl>
			<table class="responsive grid">
				<tbody>
					<?php foreach ($_rowset as $row): ?>
					<tr>
						<td><input type="checkbox"></td>
						<?php foreach ($_columns as $column => $data): if($data['show']): ?>
						<td><?php echo $row[$column] ?></td>
						<?php endif; endforeach ?>
					</tr>
					<?php endforeach ?>
				</tbody>
				<thead>
					<tr>
						<th></th>
						<?php foreach ($_columns as $column => $data): if($data['show']): ?>
						<th id="<?php echo $column ?>">
							<span><?php echo $data['title'] ?></span>
							<?php if($data['filter']) {
								switch ($data['data_type']) {
									case 'text':
										echo '<input type="text" />';
										break;
									case 'int':
										echo '<input type="number" min="1" />';
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
						<?php endif; endforeach ?>
					</tr>
					<tr id="batch-actions">
						<th colspan="<?php echo $numCols + 1 ?>">
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
