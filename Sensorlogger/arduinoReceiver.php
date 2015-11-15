<?php
include( "dbWriter.php");
include( "logFileWriter.php");

$dbWriter = new dbWriter();
$conn = $dbWriter->connect();
$logFileWriter = new logFileWriter($conn);
$dbWriter->setLogFileWriter($logFileWriter);


$dbWriter->writeSensorData ($argv[1], $argv[2], date("Y-m-d H:i:s"), false);

$dbWriter->disconnect();
?>
