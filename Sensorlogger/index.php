<?php
include( "logFileWriter.php");
include( "dbWriter.php");
include ("zwaveSensor.php");
include( "toon.php");

error_reporting(-1);
ini_set('display_errors', 'On');

$currentDateTime = date('Y-m-d H:i:s');

$logFileWriter = new logFileWriter();
$dbWriter = new dbWriter();

$toon = new toon($logFileWriter);
$toon->getMeasures();

$dbWriter->writeSensorData(4, $toon->temperature,$currentDateTime);
$dbWriter->writeSensorData(5, $toon->powerUsage,$currentDateTime);
$dbWriter->writeSensorData(6, $toon->gasUsage,$currentDateTime);



$logFileWriter->log("getting measures from aeon labs sensor");

$zwaveSensor = new zwaveSensor();
$TemperatureLevel = round($zwaveSensor->getMeasure(23, 'TemperatureLevel'),2);
$HumidityLevel = $zwaveSensor->getMeasure(23, 'HumidityLevel');
$LuminescenceLevel = $zwaveSensor->getMeasure(23, 'LuminescenceLevel');

$logFileWriter->log("TemperatureLevel: " . $TemperatureLevel);
$dbWriter->writeSensorData(1, $TemperatureLevel,$currentDateTime);
$logFileWriter->log("HumidityLevel: " . $HumidityLevel);
$dbWriter->writeSensorData(2, $HumidityLevel,$currentDateTime);
$logFileWriter->log("LuminescenceLevel: " . $LuminescenceLevel);
$dbWriter->writeSensorData(3, $LuminescenceLevel,$currentDateTime);




?>
