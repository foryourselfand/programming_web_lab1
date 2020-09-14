<?php
error_reporting(0);

function isDataValid($x, $y, $r)
{
    return in_array($x, array(-3, -2, -1, 0, 1, 2, 3, 4, 5), false) &&
        is_numeric($y) && $y > -5 && $y < 5 &&
        in_array($r, array(1, 2, 3, 4, 5), false);
}

function atQuarterCircle($x, $y, $r)
{
    return (($x <= 0) && ($y >= 0) && (($x * $x + $y * $y) <= $r * $r));
}

function atTriangle($x, $y, $r)
{
    return (($x >= 0) && ($y >= 0) && ($x <= $r) && ($y <= $r) && ($y <= -$x + $r));
}

function atRectangle($x, $y, $r)
{
    return (($x <= 0) && ($y <= 0) && ($y >= -$r / 2) && ($x >= -$r));
}


function atArea($x, $y, $r)
{
    return (atQuarterCircle($x, $y, $r) || atTriangle($x, $y, $r) || atRectangle($x, $y, $r));
}

session_start();
if (!isset($_SESSION["tableRows"])) {
    $_SESSION["tableRows"] = array();
}

date_default_timezone_set('Europe/Moscow');
$x = (float)$_GET["x"];
$y = (float)$_GET["y"];
$r = (float)$_GET["r"];

if (!isDataValid($x, $y, $r)) {
    http_response_code(418);
    return;
}

$coordsStatus = atArea($x, $y, $r);
$currentTime = date("H : i : s");
$benchmarkTime = number_format(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 10, ".", "") * 1000000;

$arr = array(
    'x' => $x,
    'y' => $y,
    'r' => $r,
    'coordsStatus' => $coordsStatus,
    'currentTime' => $currentTime,
    'benchmarkTime' => $benchmarkTime
);

$_SESSION["tableRows"][] = $arr;

echo json_encode($_SESSION["tableRows"]);