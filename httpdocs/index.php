<?
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$_REQUEST['start'] = $time;

session_start();

$ApplicationFolder = "/app";
$EngineFolder = "/engine";

include($_SERVER['DOCUMENT_ROOT']."/engine/engine.php");
