<?php

require_once('header.php');

// echo sha1('JAMES:JAM87421');

$columns = array();
$ignore_columns = array(
	'sha_pass_hash',
	'sessionkey',
	'v',
	's',
	'joindate',
	'last_ip',
	'failed_logins',
	'last_login',
	'active_realm_id',
	'mutetime',
	'locale',
	'playerBot',
	'email',
	'locked',
	);

$expansion = array(
	0=>'Vanilla WoW',
	1=>'The Burning Crusades',
	2=>'The Wrath of the Lich King',
	3=>'Cataclysm',
	4=>'Mists of Pandaria',
	);
	
$gmlevel = array(
	0=>'Player',
	1=>'Moderator',
	2=>'Game Master',
	3=>'Administrator',
	);
	
$pg = isset($_GET['pg']) ? $_GET['pg'] : 1;
$offset = ($pg * 100)-100; // Define the offset for the LIMIT clause so we can display the correct page of results.

$mysqli = new mysqli($settings['address'], $settings['username'], $settings['password']);

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
			case 'username':
				echo "<td><a href=\"account.php?id=".$row['id']."\">".$row[$column]."</a></td>";
				break;
			case 'gmlevel':
				echo "<td>".$gmlevel[$row[$column]]."</td>";
				break;
			case 'expansion':
				echo "<td>".$expansion[$row['expansion']]."</td>";
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