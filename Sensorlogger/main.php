<?php
echo gethostname();

include( "logFileWriter.php");
include( "dbWriter.php");
include ("zwaveSensor.php");
include( "toon.php");

/*
 * 1 = temperatuur buiten
 * 2 = luchtvochtigheid
 * 3 = licht
 * 4 = temperatuur binnen (fibaro)
 * 7 = temp de bilt
 * 8 = wind de bilt
 * 
 * 20 = wandlamp achter
 * 21 = wandlamp voor
 * 22 = plafondlamp achter
 * 23 = plafondlamp voor
 * 24 = bedlampjes
 * 
 * 25 = badkamer verlichting
 * 26 = lampjes boekenkast
 * 27 = lampjes aanrecht
 * 28 = spotjes hal
 * 29 = spotjes keuken
 * 
 */

error_reporting(-1);
ini_set('display_errors', 'On');

$currentDateTime = date('Y-m-d H:i:s');

$dbWriter = new dbWriter();
$conn = $dbWriter->connect();
$logFileWriter = new logFileWriter($conn);
$dbWriter->setLogFileWriter($logFileWriter);

/*
$toon = new toon($logFileWriter);
if ($toon->getMeasures()){
    $dbWriter->writeSensorData(4, $toon->temperature,$currentDateTime);
    $dbWriter->writeSensorData(5, $toon->powerUsage,$currentDateTime);
    $dbWriter->writeSensorData(6, $toon->gasUsage,$currentDateTime);
}
*/

$logFileWriter->log("getting measures from aeon labs sensor");

$zwaveSensor = new zwaveSensor();
$zwaveSensor->setLogFileWriter($logFileWriter);
$TemperatureLevel = round($zwaveSensor->getMeasure(55,'urn:upnp-org:serviceId:TemperatureSensor1', 'CurrentTemperature'),2);
$HumidityLevel = $zwaveSensor->getMeasure(57, 'urn:micasaverde-com:serviceId:HumiditySensor1',   'CurrentLevel');
$LuminescenceLevel = $zwaveSensor->getMeasure(56, 'urn:micasaverde-com:serviceId:LightSensor1' , 'CurrentLevel');

$logFileWriter->log("TemperatureLevel: " . $TemperatureLevel);
$dbWriter->writeSensorData(1, $TemperatureLevel,$currentDateTime);
$logFileWriter->log("HumidityLevel: " . $HumidityLevel);
$dbWriter->writeSensorData(2, $HumidityLevel,$currentDateTime);
$logFileWriter->log("LuminescenceLevel: " . $LuminescenceLevel);
$dbWriter->writeSensorData(3, $LuminescenceLevel,$currentDateTime);

//Lights
$Wandlamp_achter = $zwaveSensor->getMeasure(39, 'urn:upnp-org:serviceId:Dimming1' , 'LoadLevelStatus');
$logFileWriter->log("Wandlamp achter: " . $Wandlamp_achter);
$dbWriter->writeSensorData(20, $Wandlamp_achter,$currentDateTime,true);

$Wandlamp_voor = $zwaveSensor->getMeasure(41, 'urn:upnp-org:serviceId:Dimming1' , 'LoadLevelStatus');
$logFileWriter->log("Wandlamp voor: " . $Wandlamp_voor);
$dbWriter->writeSensorData(21, $Wandlamp_voor,$currentDateTime,true);

$plafondlamp_achter = $zwaveSensor->getMeasure(9, 'urn:upnp-org:serviceId:Dimming1' , 'LoadLevelStatus');
$logFileWriter->log("plafondlamp achter: " . $plafondlamp_achter);
$dbWriter->writeSensorData(22, $plafondlamp_achter,$currentDateTime,true);

$plafondlamp_voor = $zwaveSensor->getMeasure(40, 'urn:upnp-org:serviceId:Dimming1' , 'LoadLevelStatus');
$logFileWriter->log("plafondlamp voor: " . $plafondlamp_voor);
$dbWriter->writeSensorData(23, $plafondlamp_voor,$currentDateTime,true);

$bedlampjes = $zwaveSensor->getMeasure(25, 'urn:upnp-org:serviceId:Dimming1' , 'LoadLevelStatus');
$logFileWriter->log("bedlampjes: " . $bedlampjes);
$dbWriter->writeSensorData(24, $bedlampjes,$currentDateTime,true);

//Binary lights
$badkamer = $zwaveSensor->getMeasure(4, 'urn:upnp-org:serviceId:SwitchPower1' , 'Status');
$logFileWriter->log("badkamer: " . $badkamer);
$dbWriter->writeSensorData(25, $badkamer,$currentDateTime,true);

$lampjes_boekenkast = $zwaveSensor->getMeasure(18, 'urn:upnp-org:serviceId:SwitchPower1' , 'Status');
$logFileWriter->log("lampjes boekenkast: " . $lampjes_boekenkast);
$dbWriter->writeSensorData(26, $lampjes_boekenkast,$currentDateTime,true);

$lampjes_aanrecht = $zwaveSensor->getMeasure(45, 'urn:upnp-org:serviceId:SwitchPower1' , 'Status');
$logFileWriter->log("lampjes aanrecht: " . $lampjes_aanrecht);
$dbWriter->writeSensorData(27, $lampjes_aanrecht,$currentDateTime,true);

$Spotjes_hal = $zwaveSensor->getMeasure(13, 'urn:upnp-org:serviceId:SwitchPower1' , 'Status');
$logFileWriter->log("Spotjes hal: " . $Spotjes_hal);
$dbWriter->writeSensorData(28, $Spotjes_hal,$currentDateTime,true);

$Spotjes_keuken = $zwaveSensor->getMeasure(44, 'urn:upnp-org:serviceId:SwitchPower1' , 'Status');
$logFileWriter->log("Spotjes keuken: " . $Spotjes_keuken);
$dbWriter->writeSensorData(29, $Spotjes_keuken,$currentDateTime,true);

//$logFileWriter->log("getting data from fibaro");
//$TemperatureLevel = round($zwaveSensor->getMeasure(53,'urn:schemas-micasaverde-com:device:TemperatureSensor:1', 'CurrentTemperature'),2);
//$logFileWriter->log("TemperatureLevel: " . $TemperatureLevel);
//$dbWriter->writeSensorData(4, $TemperatureLevel,$currentDateTime);


$logFileWriter->log("getting measures from buienradar");
$buienradarData  = simplexml_load_file('http://xml.buienradar.nl/');
$debilt = $buienradarData->xpath('/buienradarnl/weergegevens/actueel_weer/weerstations/weerstation[@id=6260]');
$logFileWriter->log("Temperatuur de bilt: " . $debilt[0]->temperatuurGC);

$dbWriter->writeSensorData(7, $debilt[0]->temperatuurGC,$currentDateTime);

$logFileWriter->log("Windsnelheid de bilt: " . $debilt[0]->windsnelheidBF);

$dbWriter->writeSensorData(8, $debilt[0]->windsnelheidBF,$currentDateTime);



$dbWriter->disconnect();


?>
