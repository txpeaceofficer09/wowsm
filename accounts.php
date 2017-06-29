<?php

require_once('header.php');

// echo sha1('JAMES:JAM87421');

$columns = array();
$ignore_columns = array(
	'sha_pass_hash',
	'sessionkey',
	'v',
	's',
	'email',
	'joindate',
	'last_ip',
	'failed_logins',
	'locked',
	'last_login',
	'active_realm_id',
	'mutetime',
	'locale',
	'playerBot',
	);

$expansion = array(
	0=>'Vanilla WoW',
	1=>'The Burning Crusades',
	2=>'The Wrath of the Lich King',
	3=>'Cataclysm',
	4=>'Mists of Pandaria',
	);
	
$pg = isset($_GET['pg']) ? $_GET['pg'] : 1;
$offset = ($pg * 100)-100; // Define the offset for the LIMIT clause so we can display the correct page of results.

$mysqli = new mysqli('localhost', 'root', 'trinity');

$where_clause = '1=1';
$num_results = $mysqli->query('SELECT count(*) as `count` FROM `'.$settings['realmd'].'`.`account` where '.$where_clause.' ORDER BY `gmlevel` DESC;')->fetch_object()->count;

if ($num_results >= 100) {
	echo "\t\t<div class=\"pagination\"><b>Page:</b>\n";

	for ($i=1;$i<=ceil($num_results/100);$i++) {
		if ($pg == $i) {
			echo "\t\t\t".(($pg == $i) ? '<b>['.$i.']</b>' : $i)." \n";
		} else {
			echo "\t\t\t<a href=\"accounts.php?pg=".$pg."\">".(($pg == $i) ? '<b>'.$i.'</b>' : $i)."</a> \n";
		}
	}

	echo "\t\t</div>\n";
}

echo "<table>";


echo "<tr>";
$headers = $mysqli->query('SHOW COLUMNS FROM `'.$settings['realmd'].'`.`account`;');
while($header=$headers->fetch_assoc()) {
	if (!in_array($header['Field'], $ignore_columns)) {
		echo "<th>".$header['Field']."</th>";
		array_push($columns, $header['Field']);
	}
}
echo "</tr>";

$result = $mysqli->query('SELECT '.join(', ', $columns).' FROM `'.$settings['realmd'].'`.`account` where '.$where_clause.' ORDER BY `gmlevel` DESC LIMIT '.$offset.',100;');

while ($row=$result->fetch_assoc()) {
	echo "<tr>";
	
	foreach ($columns AS $column) {
		switch ($column) {
			case 'expansion':
				echo "<td>".$expansion[$row['expansion']]."</td>";
				break;
			case 'AllowableClass':
				if (isset($races[$row[$column]])) {
					echo "<td>".$classes[$row[$column]]."</td>";
				} else {
					echo "<td>".$row[$column]."</td>";
				}
				break;
			case 'AllowableRace':
				if (isset($classes[$row[$column]])) {
					echo "<td>".$races[$row[$column]]."</td>";
				} else {
					echo "<td>".$row[$column]."</td>";
				}
				break;
			case 'Quality':
				if (isset($quality[$row[$column]])) {
					echo "<td>".$quality[$row[$column]]."</td>";
				} else {
					echo "<td>".$row[$column]."</td>";
				}
				break;
			case 'class':
				echo "<td>".$item_class[$row[$column]]."</td>";
				break;
			case 'subclass':
				if (isset($item_subclass[$row['class']]) && isset($item_subclass[$row['class']][$row[$column]])) {
					echo "<td>".$item_subclass[$row['class']][$row[$column]]."</td>";
				} else {
					echo "<td>".$row[$column]."</td>";
				}
				break;
			case 'name':
				echo "<td><a href=\"http://www.wowhead.com/item=".$row['entry']."\">wowhead</a></td>";
				break;
			default:
				echo "<td>".$row[$column]."</td>";	
		}
	}
	
	echo "</tr>";
}

$mysqli->close();

echo "</table>";

require_once('footer.php');

?>