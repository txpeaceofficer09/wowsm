<?php

ob_start();
if ( isset($_COOKIE['mmohome_sid']) ) {
  session_id($_COOKIE['mmohome_sid']);
}
session_start();
$sid = session_id();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=utf-8");

/*
$starttime = microtime();
$starttime = substr($starttime, 0, stripos($starttime, " "))+substr($starttime, stripos($starttime, " ")+1);
*/

$file = file('settings.conf');
foreach($file AS $line) {
	$data = explode('=', $line);
	$settings[trim($data[0])] = trim($data[1]);
}

$layout = join('', file('layout.html'));

$activity = "                <h2>RECENTLY<span>ACTIVE</span></h2>\r\n";

$mysqli = new mysqli('localhost', 'root', 'trinity', 'realmd2');

$result = $mysqli->query('SELECT `username`, `last_login` FROM `account` ORDER BY `last_login` DESC LIMIT 4');
while ($row=$result->fetch_assoc()) {
	$activity .= "                    <div class=\"element\">\r\n";
    $activity .= "                      <span>".date("F j, Y", strtotime($row['last_login']))."</span>\r\n";
    $activity .= "                      <a href=\"javascript:void(0);\" onClick=\"ajaxGetPage('#');\">".$row['username']."</a>\r\n";
    $activity .= "                    </div>\r\n";
}

$mysqli->close();

$layout = str_ireplace(array('<!--ACTIVITY-->'), array($activity), $layout);

echo substr($layout, 0, stripos($layout, '<!--CONTENT-->'));

?>