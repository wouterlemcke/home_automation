<?php
//error_reporting(E_NONE);
error_reporting(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//ini_set('display_errors', '0');

require_once 'sensordata.php';
require_once 'GoogleChart.php';

if (!isset($_GET['interval'])){
    $interval =  '24h';
} else {
    $interval = $_GET['interval'];
}

$sensortdata = new sensordata();
$googleChart = new GoogleChart();

?>


<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,1),  'Temperatuur buiten', 'temperature_div'); ?>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,100),  'Arduino temperatuur', 'arduino_temperature_div'); ?>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,101),  'Arduino luchtvochtigheid', 'arduino_humidity_div'); ?>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,2),  'Luchtvochtigheid', 'humidity_div'); ?>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,3),  'Lichtsterkte', 'luminesense_div'); ?>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,7),  'Temperatuur de bilt', 'temperature_debilt_div'); ?>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,8),  'Windsnelheid de bilt', 'windspeed_debilt_div'); ?>
    <?=$googleChart->getChart($sensortdata->getsensordata($interval,24),  'Bedlampjes', 'bedlampjes_div'); ?>

    <!-- $googleChart->getChart3(array($sensortdata->getsensordata($interval,1),$sensortdata->getsensordata($interval,4),$sensortdata->getsensordata($interval,7)),array('Temperatuur buiten','Temperatuur binnen','Temperatuur de bilt'), 'Temperatuur', 'something'); -->
    
    
    <?=$googleChart->getChart3(array($sensortdata->getsensordata($interval,1),
                                     $sensortdata->getsensordata($interval,7)),
            array('Temperatuur buiten','Temperatuur de bilt'), 'Temperatuur', 'multiple_temp'); ?>
    
    
    <?=$googleChart->getChart3(array($sensortdata->getsensordata($interval,20),
                                     $sensortdata->getsensordata($interval,21),
                                     $sensortdata->getsensordata($interval,22),
                                     $sensortdata->getsensordata($interval,23),
                                     $sensortdata->getsensordata($interval,24),
                                     $sensortdata->getsensordata($interval,25),
                                     $sensortdata->getsensordata($interval,26),
                                     $sensortdata->getsensordata($interval,27),
                                     $sensortdata->getsensordata($interval,28),
                                     $sensortdata->getsensordata($interval,29),
        ),
            array('wandlamp achter',
                  'wandlamp voor',
                  'plafondlamp achter',
                  'plafondlamp voor',
                  'bedlampjes',
                  'Badkamer verlichting',
                  'lampjes boekenkast',
                  'lampjes aanrecht',
                  'spotjes hal',
                  'spotjes keuken'
                ), 'Lampen', 'lights_div'); ?>

    

  </head>
  <body>
      <form onchange="submit();">
          <select name="interval">
              <option value="24h" <? if($interval == '24h') echo 'selected'; ?>Afgelopen 24 uur</option>
              <option value="week" <? if($interval == 'week') echo 'selected'; ?>Afgelopen week</option>
              <option value="month" <? if($interval == 'month') echo 'selected'; ?>Afgelopen maand</option>
              <option value="year" <? if($interval == 'year') echo 'selected'; ?>Afgelopen jaar</option>
          </select>
      </form>
         
      
      
    <div id="temperature_div" style="width: 900px; height: 500px;"></div>
    <div id="arduino_temperature_div" style="width: 900px; height: 500px;"></div>
    <div id="arduino_humidity_div" style="width: 900px; height: 500px;"></div>
    <!--<div id="temperature_inside_div" style="width: 900px; height: 500px;"></div>-->
    <!--<div id="powerusage_div" style="width: 900px; height: 500px;"></div>
    <div id="gasusage_div" style="width: 900px; height: 500px;"></div>-->
    <div id="humidity_div" style="width: 900px; height: 500px;"></div>
    <div id="luminesense_div" style="width: 900px; height: 500px;"></div>
    <div id="temperature_debilt_div" style="width: 900px; height: 500px;"></div>
    <div id="windspeed_debilt_div" style="width: 900px; height: 500px;"></div>
    <div id="multiple_temp" style="width: 900px; height: 500px;"></div>
    <div id="lights_div" style="width: 900px; height: 500px;"></div>
    <div id="bedlampjes_div" style="width: 900px; height: 500px;"></div>
    
  </body>
</html>

