<?php
require_once 'sensordata.php';
require_once 'Highchart.php';

error_reporting(E_ALL);

if (!isset($_GET['interval'])){
    $interval =  '24h';
} else {
    $interval = $_GET['interval'];
}

$sensortdata = new sensordata();
$highChart = new highCHart();

?>
<html>
  <head>

      
<script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<?php echo $highChart->getChart(array("Temperatuur buiten"=>  array($sensortdata->getsensordata($interval,1)),
                                      "Temperatuur de bilt"=>  array($sensortdata->getsensordata($interval,7))
    ), "container","Temperatuur",""," °C","Temperatuur"); ?>

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
      
      <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
      
      
  </body>
</html>