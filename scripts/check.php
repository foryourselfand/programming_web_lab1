<?php
error_reporting(0);

function isDataValid($x, $y, $r)
{
    return
        in_array($x, array(-3, -2, -1, 0, 1, 2, 3, 4, 5), false) &&
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

function json_encode_for_helios($a = false)
{
    if (is_null($a)) {
        return 'null';
    }
    if ($a === false) {
        return 'false';
    }
    if ($a === true) {
        return 'true';
    }
    if (is_scalar($a)) {
        if (is_float($a)) {
            return (float)str_replace(',', '.', (string)$a);
        }
        if (is_string($a)) {
            static $jsonReplaces = array(array('\\', '/', "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
            return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
        }

        return $a;
    }
    for ($i = 0, reset($a); true; $i++) {
        if (key($a) !== $i) {
            $isList = false;
            break;
        }
    }

    $result = array();
    if ($isList) {
        foreach ($a as $v) {
            $result[] = json_encode_for_helios($v);
        }
        return '[' . implode(',', $result) . ']';
    }

    foreach ($a as $k => $v) {
        $result[] = json_encode_for_helios((string)$k) . ':' . json_encode_for_helios($v);
    }
    return '{' . implode(',', $result) . '}';
}

session_start();
date_default_timezone_set('Europe/Moscow');

if (!isset($_SESSION["tableRows"])) {
    $_SESSION["tableRows"] = array();
}

$x = isset($_GET["x"]) ? $_GET["x"] : 0;
$y = isset($_GET["y"]) ? str_replace(",", ".", $_GET["y"]) : 0;
$r = isset($_GET["r"]) ? $_GET["r"] : 3;

if (!isDataValid($x, $y, $r)) {
    http_response_code(400);
    return;
}

$coordsStatus = atArea($x, $y, $r);
$currentTime = date("H : i : s");
$benchmarkTime = number_format(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 10, ".", "") * 1000000;

$_SESSION["tableRows"][] = array(
    'x' => $x,
    'y' => $y,
    'r' => $r,
    'coordsStatus' => $coordsStatus,
    'currentTime' => $currentTime,
    'benchmarkTime' => $benchmarkTime
);

echo json_encode_for_helios($_SESSION["tableRows"]);