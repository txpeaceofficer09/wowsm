<?php

require_once('header.php');

function gold($money) {
	$c = ($money%100).'<img style="vertical-align: bottom;" src="/images/Copper.png" />';
	$s = ($money > 100) ? floor(($money%10000)/100).'<img style="vertical-align: bottom;" src="/images/Silver.png" /> ' : '';
	$g = ($money > 10000) ? substr($money, 0, strlen($money)-4).'<img style="vertical-align: bottom;" src="/images/Gold.png" /> ' : '';
	return $g.$s.$c;
}

$columns = array();
$ignore_columns = array(
	'playerBytes',
	'playerBytes2',
	'playerFlags',
	'position_x',
	'position_y',
	'position_z',
	'map',
	'dungeon_difficulty',
	'taximask',
	'orientation',
	'taximask',
	'cinematic',
	'logout_time',
	'is_logout_resting',
	'rest_bonus',
	'resettalents_cost',
	'resettalents_time',
	'trans_x',
	'trans_y',
	'trans_z',
	'trans_o',
	'transguid',
	'extra_flags',
	'stable_slots',
	'at_login',
	'zone',
	'death_expire_time',
	'taxi_path',
	'arenaPoints',
	'totalHonorPoints',
	'todayHonorPoints',
	'yesterdayHonorPoints',
	'totalKills',
	'todayKills',
	'yesterdayKills',
	'chosenTitle',
	'knownCurrencies',
	'watchedFaction',
	'drunk',
	'health',
	'power1',
	'power2',
	'power3',
	'power4',
	'power5',
	'power6',
	'power7',
	'specCount',
	'activeSpec',
	'exploredZones',
	'equipmentCache',
	'ammoId',
	'knownTitles',
	'actionBars',
	'deleteInfos_Account',
	'deleteInfos_Name',
	'deleteDate',
	'gender',
	'xp',
	'online',
	'totaltime',
	'leveltime',
	);

$races = array(
	-1=>'Any',
	1=>'Human',
	2=>'Orc',
	3=>'Dwarf',
	4=>'Night Elf',
	5=>'Undead',
	6=>'Tauren',
	7=>'Gnome',
	8=>'Troll',
	10=>'Blood Elf',
	11=>'Draenei'
	);

$classes = array(
	-1=>'Any',
	1=>'<span style="color: brown;">Warrior</span>',
	2=>'<span style="color: pink;">Paladin</span>',
	3=>'<span style="color: green;">Hunter</span>',
	4=>'<span style="color: yellow;">Rogue</span>',
	5=>'<span style="color: white;">Priest</span>',
	6=>'<span style="color: maroon;">Deathknight</span>',
	7=>'<span style="color: blue;">Shaman</span>',
	8=>'<span style="color: #8af;">Mage</span>',
	9=>'<span style="color: purple;">Warlock</span>',
	11=>'<span style="color: orange;">Druid</span>',
	32=>'<span style="color: maroon;">Death Knight</span>',
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

$mysqli = new mysqli($settings['address'], $settings['username'], $settings['password']);

if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
//	print_r($_POST);
	$mysqli->query('UPDATE `'.$settings['realmd'].'`.`account` SET `username`="'.$_POST['username'].'", `gmlevel`='.$_POST['gmlevel'].', `email`="'.$_POST['email'].'", `locked`='.$_POST['locked'].', `expansion`='.$_POST['expansion'].' WHERE `id`='.$_GET['id'].' LIMIT 1;');
//	print_r($mysqli->error);
}

// print_r($_SERVER);

echo "\t\t<form action=\"account.php?id=".$_GET['id']."\" method=\"POST\">\n\t\t\t<dl>\n";

$row = $mysqli->query('SELECT * FROM `'.$settings['realmd'].'`.`account` WHERE `id`='.$_GET['id'].' LIMIT 1;')->fetch_assoc();
foreach($row AS $k=>$v) {
	switch ($k) {
		case 'playerBot':
		case 'os':
		case 'locale':
		case 'mutetime':
		case 'last_login':
		case 'failed_logins':
		case 'joindate':
		case 'sha_pass_hash':
		case 'sessionkey':
		case 'v':
		case 's':
		case 'active_realm_id':
			// Echo nothing.
			break;
		case 'id':
		case 'last_ip':
			echo "\t\t\t\t<dt>".$k."</dt>\n\t\t\t\t<dd>".$v."</dd>\n";
			break;
		case 'locked':
			// Change this to show a drop-down menu with True or False.
			echo "\t\t\t\t<dt>".$k."</dt>\n\t\t\t\t<dd><input type=\"text\" name=\"".$k."\" value=\"".$v."\"></dd>\n";
			break;
		case 'expansion':
			// Change this to show a drop-down menu.
			echo "\t\t\t\t<dt>".$k."</dt>\n\t\t\t\t<dd><input type=\"text\" name=\"".$k."\" value=\"".$v."\"></dd>\n";
			break;
		case 'gmlevel':
			// Change this to show a drop-down menu.
			echo "\t\t\t\t<dt>".$k."</dt>\n\t\t\t\t<dd><input type=\"text\" name=\"".$k."\" value=\"".$v."\"></dd>\n";
			break;
		default:
			echo "\t\t\t\t<dt>".$k."</dt>\n\t\t\t\t<dd><input type=\"text\" name=\"".$k."\" value=\"".$v."\"></dd>\n";
			break;
	}
}

echo "\t\t\t</dl>\n\t\t\t<input type=\"submit\" value=\"Save\" />\n\t\t</form>\n";
	
echo "<table>";

echo "<tr>";
$headers = $mysqli->query('SHOW COLUMNS FROM `'.$settings['character'].'`.`characters`;');
while($header=$headers->fetch_assoc()) {
	if (!in_array($header['Field'], $ignore_columns)) {
		echo "<th>".$header['Field']."</th>";
		array_push($columns, $header['Field']);
	}
}
echo "</tr>";

$where_clause = '`'.$settings['character'].'`.`characters`.`account`='.$_GET['id'];
$result = $mysqli->query('SELECT '.join(', ', $columns).', `'.$settings['realmd'].'`.`account`.`username` FROM `'.$settings['character'].'`.`characters` join (`'.$settings['realmd'].'`.`account`) on (`'.$settings['realmd'].'`.`account`.`id`=`'.$settings['character'].'`.`characters`.`account`) where '.$where_clause.' ORDER BY `account` DESC;');

while ($row=$result->fetch_assoc()) {
	echo "<tr>";
	
	foreach ($columns AS $column) {
		switch ($column) {
			case 'username':
				// don't display this column on this page.
				break;
			case 'account':
				echo "<td>".$row['username']."</td>";
				break;
			case 'name':
				echo "<td><a href=\"character.php?guid=".$row['guid']."\">".$row[$column]."</a></td>";
				break;
			case 'race':
				echo "<td>".$races[$row[$column]]."</td>";
				break;
			case 'class':
				echo "<td>".$classes[$row[$column]]."</td>";
				break;
			case 'level':
				echo "<td style=\"text-align: right;\">".$row[$column]."</td>";
				break;
			case 'money':
				echo "<td style=\"text-align: right;\">".gold($row[$column])."</td>";
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