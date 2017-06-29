<?php

if (isset($side)) {
	$layout = str_ireplace(array('<!--RIGHT-->'), array($side), $layout);
}

echo substr($layout, stripos($layout, '<!--CONTENT-->')+strlen('<!--CONTENT-->'), strlen($layout)-stripos($layout, '<!--CONTENT-->')+strlen('<!--CONTENT-->'));

/*
$endtime = microtime();
$endtime = substr($endtime, 0, stripos($endtime, " "))+substr($endtime, stripos($endtime, " ")+1);
echo "                <i>This page loaded in <b>".round(($endtime-$starttime), 4)."</b> second(s).</i>\r\n";
*/

// if (isset($mysqli)) $mysqli->close();
ob_end_flush();

?>