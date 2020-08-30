<?php
error_reporting(0);
session_start();

if (isset($_SESSION['tableRows'])) {
    $_SESSION['tableRows'] = array();
}

foreach ($_SESSION["tableRows"] as $tableRow) {
    echo $tableRow;
}