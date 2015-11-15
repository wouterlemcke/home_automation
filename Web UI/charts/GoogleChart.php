 <?php

error_reporting(E_ALL);
//ini_set('display_errors', '1');

/**
 * Description of GoogleChart
 *
 * @author erik
 */
class GoogleChart {
    
    public function getChart($chartData,$chartTitle,$div_name)
    {
        $chartscript = '<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Time\', \''.$chartTitle.'\'],';

    foreach ($chartData as $chartdate){
           $chartscript .=  "['" . $chartdate['datetime'] . "'," . $chartdate['value'] . "],";
    }

    $chartscript .='
        ]);

        var options = {
          title: \''. $chartTitle .'\'
	 
        };

        var chart = new google.visualization.AreaChart(document.getElementById(\''. $div_name .'\'));
        chart.draw(data, options);
      }
      </script>';
        
        return $chartscript; 
}
    



    public function getChart2($chartData, $rangeNames,  $chartTitle, $chartDiv){
        $x = 0;
        
        $chartscript = '<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Time\', \''.$rangeNames[0].'\',\'' . $rangeNames[1] . '\'],';
        
        foreach ($chartData[0] as $row){
            $chartscript .=  "['{$row['datetime']}',{$row['value']},{$chartData[1][$x]['value']}], ";
            $x++;
        }
            
        
        
        $chartscript .='
        ]);

        var options = {
          title: \''. $chartTitle .'\'
	 
        };

        var chart = new google.visualization.AreaChart(document.getElementById(\''. $chartDiv .'\'));
        chart.draw(data, options);
      }
      </script>';
         return $chartscript; 

    }

    public function getChart3($chartData, $rangeNames,  $chartTitle, $chartDiv){
        $chartscript = '<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Time\',';
        
        //Range names
        foreach ($rangeNames as $rangeName){
            $chartscript .= "'$rangeName',";
        }
        $chartscript = rtrim($chartscript, ",");
        $chartscript .= '],'; 
        
        $counter2 = 0;
        
        //Transform data into easier to use array
        foreach ($chartData as $range){
            
            
            $counter1 = 0;
            foreach ($range as $value){
                $dates[] = $range[$counter1]["datetime"];
                $data[$counter1][$counter2] =  $value["value"];
                $counter1++;
            }
            $counter2++;
        }
        
        echo '<PRE>';
            //var_dump($dates);
        echo '</PRE>';
        
        
        $rangescount =  count($data[0]);
        $datecount = 0;
        
        foreach ($data as $datarow){
           $chartscript .=  "['$dates[$datecount]',";
           
           $datecount++;
           
           for ($z = 0;$z < $rangescount;$z++){
               $rowdata = $datarow[$z];
               //If no data is available for a specific sensor, assume it's 0
               if (empty($rowdata)){
                   $rowdata = 0;
               }
               $chartscript .= $rowdata . ',';
           }
           $chartscript = rtrim($chartscript, ",");
           $chartscript .= '],';
        }
        $chartscript = rtrim($chartscript, ",");
        
        $chartscript .='
        ]);

        var options = {
          title: \''. $chartTitle .'\'
	 
        };

        var chart = new google.visualization.AreaChart(document.getElementById(\''. $chartDiv .'\'));
        chart.draw(data, options);
      }
      </script>';
         return $chartscript; 

    }
    

}

?>
