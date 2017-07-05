<?php

require_once('header.php');

$mysqli = new mysqli($settings['address'], $settings['username'], $settings['password']);

$_GET['quality'] = isset($_GET['quality']) ? $_GET['quality'] : '3';
$_GET['item_class'] = isset($_GET['item_class']) ? $_GET['item_class'] : '2';
$_GET['item_level_min'] = isset($_GET['item_level_min']) ? $_GET['item_level_min'] : '1';
$_GET['item_level_max'] = isset($_GET['item_level_max']) ? $_GET['item_level_max'] : '284';
$_GET['player_level_min'] = isset($_GET['player_level_min']) ? $_GET['player_level_min'] : '0';
$_GET['player_level_max'] = isset($_GET['player_level_max']) ? $_GET['player_level_max'] : '80';
$pg = isset($_GET['pg']) ? $_GET['pg'] : 1;
$offset = ($pg * 100)-100; // Define the offset for the LIMIT clause so we can display the correct page of results.

$columns = array();
$ignore_columns = array(
	'class',
	'Quality',
	'subclass',
	'AllowableClass',
	'AllowableRace',
	'ContainerSlots',
	'itemset',
	'RequiredLevel',
	'unk0',
	'displayid',
	'Flags',
	'Flags2',
	'BuyCount',
	'BuyPrice',
	'SellPrice',
	'InventoryType',
	'MaxDurability',
	'area',
	'Map',
	'BagFamily',
	'startquest',
	'lockid',
	'PageMaterial',
	'PageText',
	'LanguageID',
	'TotemCategory',
	'RequiredDisenchantSkill',
	'Duration',
	'ItemLimitCategory',
	'HolidayId',
	'DisenchantID',
	'FoodType',
	'minMoneyLoot',
	'maxMoneyLoot',
	'ExtraFlags',
	'RequiredSkill',
	'RequiredSkillRank',
	'requiredspell',
	'requiredhonorrank',
	'RequiredCityRank',
	'RequiredReputationRank',
	'maxcount',
	'stackable',
	'StatsCount',
	'stat_type1',
	'stat_type2',
	'stat_type3',
	'stat_type4',
	'stat_type5',
	'stat_type6',
	'stat_type7',
	'stat_type8',
	'stat_type9',
	'stat_type10',
	'stat_value1',
	'stat_value2',
	'stat_value3',
	'stat_value4',
	'stat_value5',
	'stat_value6',
	'stat_value7',
	'stat_value8',
	'stat_value9',
	'stat_value10',
	'holy_res',
	'fire_res',
	'nature_res',
	'frost_res',
	'shadow_res',
	'arcane_res',
	'delay',
	'ammo_type',
	'spellid_1',
	'spelltrigger_1',
	'spellcharges_1',
	'spellppmRate_1',
	'spellcooldown_1',
	'spellcategory_1',
	'spellcategorycooldown_1',
	'spellid_2',
	'spelltrigger_2',
	'spellcharges_2',
	'spellppmRate_2',
	'spellcooldown_2',
	'spellcategory_2',
	'spellcategorycooldown_2',
	'spellid_3',
	'spelltrigger_3',
	'spellcharges_3',
	'spellppmRate_3',
	'spellcooldown_3',
	'spellcategory_3',
	'spellcategorycooldown_3',
	'spellid_4',
	'spelltrigger_4',
	'spellcharges_4',
	'spellppmRate_4',
	'spellcooldown_4',
	'spellcategory_4',
	'spellcategorycooldown_4',
	'spellid_5',
	'spelltrigger_5',
	'spellcharges_5',
	'spellppmRate_5',
	'spellcooldown_5',
	'spellcategory_5',
	'spellcategorycooldown_5',
	'ArmorDamageModifier',
	'socketColor_1',
	'socketContent_1',
	'socketColor_2',
	'socketContent_2',
	'socketColor_3',
	'socketContent_3',
	'socketBonus',
	'GemProperties',
	'RandomSuffix',
	'block',
	'RandomProperty',
	'RangedModRange',
	'bonding',
	'description',
	'sheath',
	'ScalingStatDistribution',
	'ScalingStatValue',
	'dmg_min1',
	'dmg_max1',
	'dmg_type1',
	'dmg_min2',
	'dmg_max2',
	'dmg_type2',
	'armor',
	'RequiredReputationFaction',
	'Material',
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
	5=>'Priest',
	6=>'<span style="color: maroon;">Deathknight</span>',
	7=>'<span style="color: blue;">Shaman</span>',
	8=>'<span style="color: #8af;">Mage</span>',
	9=>'<span style="color: purple;">Warlock</span>',
	11=>'<span style="color: orange;">Druid</span>',
	32=>'<span style="color: maroon;">Death Knight</span>',
	);

$quality = array(
	0=>'<span style="color: grey;">Poor</span>',
	1=>'Common',
	2=>'<span style="color: green;">Uncommon</span>',
	3=>'<span style="color: blue;">Rare</span>',
	4=>'<span style="color: purple;">Epic</span>',
	5=>'<span style="color: orange;">Legendary</span>',
	6=>'Artifact',
	7=>'Heirloom',
	);
	
$item_class = array(
	0=>'Consumable',
	1=>'Container',
	2=>'Weapon',
	3=>'Gem',
	4=>'Armor',
	5=>'Reagent',
	6=>'Projectile',
	7=>'Trade Goods',
	8=>'Generic(OBSOLETE)',
	9=>'Recipe',
	10=>'Money(OBSOLETE)',
	11=>'Quiver',
	12=>'Quest',
	13=>'Key',
	14=>'Permanent(OBSOLETE)',
	15=>'Miscellaneous',
	16=>'Glyph'
	);

$item_subclass = array(
	0=>array(
		0=>'Consumable',
		1=>'Potion',
		2=>'Elixir',
		3=>'Flask',
		4=>'Scroll',
		5=>'Food & Drink',
		6=>'Item Enhancement',
		7=>'Bandage',
		8=>'Other'
		),
	1=>array(
		0=>'Bag',
		1=>'Soul Bag',
		2=>'Herb Bag',
		3=>'Enchanting Bag',
		4=>'Engineering Bag',
		5=>'Gem Bag',
		6=>'Mining Bag',
		7=>'Leatherworking Bag'
		),
	2=>array(
		0=>'One-handed Axe',
		1=>'Two-handed Axe',
		2=>'Bow',
		3=>'Gun',
		4=>'One-handed Mace',
		5=>'Two-handed Mace',
		6=>'Polearm',
		7=>'One-handed Sword',
		8=>'Two-handed Sword',
		9=>'Obsolete',
		10=>'Staff',
		11=>'Exotic',
		12=>'Exotic',
		13=>'Fist Weapon',
		14=>'Miscellaneous',
		15=>'Dagger',
		16=>'Thrown',
		17=>'Spear',
		18=>'Crossbow',
		19=>'Wand',
		20=>'Fishing Pole'
		),
		3=>array(
			0=>'Red',
			1=>'Blue',
			2=>'Yellow',
			3=>'Purple',
			4=>'Green',
			5=>'Orange',
			6=>'Meta',
			7=>'Simple',
			8=>'Prismatic',
			),
		4=>array(
			0=>'Miscellaneous',
			1=>'Cloth',
			2=>'Leather',
			3=>'Mail',
			4=>'Plate',
			5=>'Buckler(OBSOLETE)',
			6=>'Shield',
			7=>'Libram',
			8=>'Idol',
			9=>'Totem',
			),
		5=>array(
			0=>'Reagent',
			),
		6=>array(
			0=>'Wand(OBSOLETE)',
			1=>'Bolt(OBSOLETE)',
			2=>'Arrow',
			3=>'Bullet',
			4=>'Thrown(OBSOLETE)'
			),
		12=>array(
			0=>'Quest'
			),
		13=>array(
			1=>'Lockpick'
			),
	);
	
$stats = array(
	0=>'ITEM_MOD_MANA',
	1=>'ITEM_MOD_HEALTH',
	3=>'ITEM_MOD_AGILITY',
	4=>'ITEM_MOD_STRENGTH',
	5=>'ITEM_MOD_INTELLECT',
	6=>'ITEM_MOD_SPIRIT',
	7=>'ITEM_MOD_STAMINA',
	12=>'ITEM_MOD_DEFENSE_SKILL_RATING',
	13=>'ITEM_MOD_DODGE_RATING',
	14=>'ITEM_MOD_PARRY_RATING',
	15=>'ITEM_MOD_BLOCK_RATING',
	16=>'ITEM_MOD_HIT_MELEE_RATING',
	17=>'ITEM_MOD_HIT_RANGED_RATING',
	18=>'ITEM_MOD_HIT_SPELL_RATING',
	19=>'ITEM_MOD_CRIT_MELEE_RATING',
	20=>'ITEM_MOD_CRIT_RANGED_RATING',
	21=>'ITEM_MOD_CRIT_SPELL_RATING',
	22=>'ITEM_MOD_HIT_TAKEN_MELEE_RATING',
	23=>'ITEM_MOD_HIT_TAKEN_RANGED_RATING',
	24=>'ITEM_MOD_HIT_TAKEN_SPELL_RATING',
	25=>'ITEM_MOD_CRIT_TAKEN_MELEE_RATING',
	26=>'ITEM_MOD_CRIT_TAKEN_RANGED_RATING',
	27=>'ITEM_MOD_CRIT_TAKEN_SPELL_RATING',
	28=>'ITEM_MOD_HASTE_MELEE_RATING',
	29=>'ITEM_MOD_HASTE_RANGED_RATING',
	30=>'ITEM_MOD_HASTE_SPELL_RATING',
	31=>'ITEM_MOD_HIT_RATING',
	32=>'ITEM_MOD_CRIT_RATING',
	33=>'ITEM_MOD_HIT_TAKEN_RATING',
	34=>'ITEM_MOD_CRIT_TAKEN_RATING',
	35=>'ITEM_MOD_RESILIENCE_RATING',
	36=>'ITEM_MOD_HASTE_RATING',
	37=>'ITEM_MOD_EXPERTISE_RATING',
	);

$side = "<form action=\"items.php\" method=\"GET\">\n";
$side .= "Quality: <select name=\"quality\">\n";

foreach ($quality AS $key=>$val) {
	$side .= "<option value=\"".$key."\"".($_GET['quality'] == $key ? ' selected' : '').">".$val."</option>";
}

$side .= "</select><br />\n";
$side .= "Item Class: <select name=\"item_class\">\n";

foreach ($item_class AS $key=>$val) {
	$side .= "<option value=\"".$key."\"".($_GET['item_class'] == $key ? ' selected' : '').">".$val."</option>";
}

$side .= "</select><br />\n";
$side .= "Item Level: <select name=\"item_level_min\">\n";

$ilvls = $mysqli->query('SELECT DISTINCT itemlevel FROM `'.$settings['mangos'].'`.`item_template` ORDER BY `itemlevel` DESC;');
while ($ilvl=$ilvls->fetch_object()->itemlevel) {
	$side .= "<option value=\"".$ilvl."\"".($_GET['item_level_min'] == $ilvl ? ' selected' : '').">".$ilvl."</option>";
}

$side .= "</select> - <select name=\"item_level_max\">\n";

$ilvls = $mysqli->query('SELECT DISTINCT itemlevel FROM `'.$settings['mangos'].'`.`item_template` ORDER BY `itemlevel` DESC;');
while ($ilvl=$ilvls->fetch_object()->itemlevel) {
	$side .= "<option value=\"".$ilvl."\"".($_GET['item_level_max'] == $ilvl ? ' selected' : '').">".$ilvl."</option>";
}

$side .= "</select><br />\n";
$side .= "Player Level: <select name=\"player_level_min\">\n";

for ($i=0;$i<=80;$i++) {
	$side .= "<option value=\"".$i."\"".($_GET['player_level_min'] == $i ? ' selected' : '').">".$i."</option>";
}

$side .= "</select> - <select name=\"player_level_max\">\n";
	
for ($i=0;$i<=80;$i++) {
	$side .= "<option value=\"".$i."\"".($_GET['player_level_max'] == $i ? ' selected' : '').">".$i."</option>";
}
	
$side .= "</select><br />\n";
$side .= "<input type=\"submit\" value=\"Search\" />\n";
$side .= "</form>\n";

$where_clause = '`quality`>='.$_GET['quality'].' and `class`='.$_GET['item_class'].' and `itemlevel`>='.$_GET['item_level_min'].' and `itemlevel`<='.$_GET['item_level_max'].' and `requiredlevel`>='.$_GET['player_level_min'].' and `requiredlevel`<='.$_GET['player_level_max'];
$num_results = $mysqli->query('SELECT count(*) as `count` FROM `'.$settings['mangos'].'`.`item_template` where '.$where_clause.' ORDER BY `itemlevel` DESC;')->fetch_object()->count;

if ($num_results >= 100) {
	echo "\t\t<div class=\"pagination\"><b>Page:</b>\n";

	for ($i=1;$i<=ceil($num_results/100);$i++) {
		if ($pg == $i) {
			echo "\t\t\t".(($pg == $i) ? '<b>['.$i.']</b>' : $i)." \n";
		} else {
			echo "\t\t\t<a href=\"items.php?item_class=".$_GET['item_class']."&quality=".$_GET['quality']."&item_level_min=".$_GET['item_level_min']."&item_level_max=".$_GET['item_level_max']."&player_level_min=".$_GET['player_level_min']."&player_level_max=".$_GET['player_level_max']."&pg=".$i."\">".(($pg == $i) ? '<b>'.$i.'</b>' : $i)."</a> \n";
		}
	}

	echo "\t\t</div>\n";
}

echo "<table>";


echo "<tr>";
$headers = $mysqli->query('SHOW COLUMNS FROM `'.$settings['mangos'].'`.`item_template`;');
while($header=$headers->fetch_assoc()) {
	if (!in_array($header['Field'], $ignore_columns)) {
		echo "<th>".$header['Field']."</th>";
		array_push($columns, $header['Field']);
	}
}
echo "</tr>";

$result = $mysqli->query('SELECT '.join(', ', $columns).' FROM `'.$settings['mangos'].'`.`item_template` where '.$where_clause.' ORDER BY `itemlevel` DESC LIMIT '.$offset.',100;');

while ($row=$result->fetch_assoc()) {
	echo "<tr>";
	
	foreach ($columns AS $column) {
		switch ($column) {
			case 'entry':
				echo "<td><a href=\"item.php?id=".$row['entry']."\">".$row['entry']."</td>";
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
				echo "<td><a href=\"item.php?id=".$row['entry']."\" rel=\"item=".$row['entry']."\">".$row[$column]."</a></td>";
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