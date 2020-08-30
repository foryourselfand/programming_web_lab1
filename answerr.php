<?php
error_reporting(0);

function checkData($x, $y, $r)
{
    return true;
}

function checkCoordinates($x, $y, $r)
{
    return "Да";
}

@session_start();
if (!isset($_SESSION["tableRows"])) {
    $_SESSION["tableRows"] = array();
}
date_default_timezone_set($_GET["timezone"]);
$x = (float)$_GET["x"];
$y = (float)$_GET["y"];
$r = (float)$_GET["r"];

if (checkData($x, $y, $r)) {
    $coordsStatus = checkCoordinates($x, $y, $r);
    $currentTime = date("H : i : s");
    $benchmarkTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

    $_SESSION["tableRows"][] = "
    <tr>
    <td>$x</td>
    <td>$y</td>
    <td>$r</td>
    <td>$coordsStatus</td>
    <td>$currentTime</td>
    <td>$benchmarkTime</td>
    </tr>
    ";

    foreach ($_SESSION["tableRows"] as $tableRow) {
        echo $tableRow;
    }
} else {
    http_response_code(400);
}