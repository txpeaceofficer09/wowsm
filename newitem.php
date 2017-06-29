<?php

$_GET['id'] = isset($_GET['id']) ? $_GET['id'] : '';

$mysqli = new mysqli('10.238.168.134', 'root', 'trinity');

$defaults = array(
		
	);

$columns = array();
	
?>

<form action="newitem.php" method="POST">
<table>
<?php

if ($_GET['id'] != '') {
	$result = $mysqli->query('SELECT * FROM `mangos2`.`item_template` WHERE `entry`='.$_GET['id'].';')->fetch_assoc();
	foreach ($result AS $key=>$value) {
		$defaults[$key] = $value;
	}
}

$newid = $mysqli->query('SELECT MAX(`entry`) AS `newid` FROM `mangos2`.`item_template`;')->fetch_object()->newid + 1;
$result = $mysqli->query('SHOW COLUMNS FROM `mangos2`.`item_template`;');

while ($row=$result->fetch_assoc()) {
	switch ($row['Field']) {
		case 'entry':
			echo "<tr><td>".$row['Field']."</td><td><input type=\"text\" name=\"".$row['Field']."\" value=\"".$newid."\" /></td></tr>";			
			break;
	
		default:
			echo "<tr><td>".$row['Field']."</td><td><input type=\"text\" name=\"".$row['Field']."\" value=\"".(isset($defaults[$row['Field']]) ? $defaults[$row['Field']] : '')."\" /></td></tr>";
	}
	array_push($columns, $row['Field']);
}

?>
</table>
	<input type="submit" value="Create Item" />
</form>

<?php

$keys = array();
$values = array();

/*
print_r($columns);

print_r($keys);
print_r($values);
*/

if ($_SERVER['REQUEST_METHOD'] === "POST") {
	foreach($_POST AS $k=>$v) {
		// print_r($columns);
		if (in_array($k, $columns)) {
			array_push($keys, $k);
			array_push($values, $v);
		}
		// echo $k." :: ".$v."<br />";
	}
	
	// print_r($keys);
	// print_r($values);
	
	$query = 'INSERT INTO `mangos2`.`item_template` (`'.join('`, `', $keys).'`) VALUES ("'.join('", "', $values).'");';
	$mysqli->query($query);
	// echo $query;
}

$mysqli->close();

?>