<?php

$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime("+1 day"));
$dayAfterTomorrow = date(strtotime("+2 day"));
//$todayStr = date("l M, d", $today);
//$tomorrowStr = date("l M, d", strtotime("+1 day"));
//$dayAfterTomorrowStr = date("l M, d", strtotime("+2 day"));

echo $today;
echo $tomorrow;
?>