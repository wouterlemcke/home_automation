<?php
 error_reporting(E_ALL);
class highCHart {
    
   /**
   * Retreieves a full data array and creates the JSON string used by highchart for the X axis
   *
   * @author       Erik Lemcke
   * @since        2015-11-15
   * @todo         
   */
    function getXaxis($chartData){
        reset($chartData);
        $first = current($chartData);
        
        foreach ($first[0] as $data){
            $value .=  "'" . $data['datetime'] . "',";
        }
        $value = rtrim($value, ",");
        
        return $value;
        
    }
    
   /**
   * Retreieves a full data array and creates the JSON string used by highchart for the series
   *
   * @author       Erik Lemcke
   * @since        2015-11-15
   * @todo         
   */
    function getSeries ($chartData){
        
        //loop door elke data serie die aangeleverd wordt
        foreach ($chartData as $key => $serie){
            
            $seriestring .= "
            {
                name: '$key',
                data: [";
                    
            //loop door elke waarde die aangeleverd wordt
            foreach ($serie[0] as $data){
                $seriestring .= $data['value'] . ',';
                
            }
                    
           $seriestring .= "]
            },";
        }
        $seriestring = rtrim($seriestring, ",");
        return $seriestring;
        
    }
    
   /**
   * Creates javascript for a highchart chart
   *
   * @author       Erik Lemcke
   * @since        2015-11-15
   * @todo         
   */
    function getChart($chartData, $divName, $chartTitle,$chartSubtitle , $valueSuffix, $chartYaxisLabel){
    
       
        $Xaxis = $this->getXaxis($chartData);
        $series = $this->getSeries($chartData);
        
        
        $chartScript = "<script type=\"text/javascript\">
$(function () {
    $('#$divName').highcharts({
        title: {
            text: '$chartTitle',
            x: -20 //center
        },
        subtitle: {
            text: '$chartSubtitle',
            x: -20
        },
        xAxis: {
            categories: [$Xaxis]
        },
        yAxis: {
            title: {
                text: '$chartYaxisLabel'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '$valueSuffix'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [$series]
    });
});
</script>";
        
        return $chartScript;
        
        
        
        
    }
    
    
}