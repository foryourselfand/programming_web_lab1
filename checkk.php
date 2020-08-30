<?php
error_reporting(0);

function checkData($x, $y, $r)
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


function checkCoordinates($x, $y, $r)

{

    return (atQuarterCircle($x, $y, $r) || atTriangle($x, $y, $r) || atRectangle($x, $y, $r))
        ?
        "Yes"
        :
        "No";
}

session_start();
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