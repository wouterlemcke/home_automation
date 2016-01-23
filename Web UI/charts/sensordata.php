<?php

class sensordata {

    public function getMinMax ($interval,$sensor_id){
        //Bepaal begin / einddatum
        $enddate = date('Y-m-d H:i:s');
        
        if ($interval == '24h'){
            $begindate = date('Y-m-d H:i:s',(strtotime ( '-1 day' , strtotime ( $enddate) ) ));
        } else if ($interval = 'week'){
            $begindate = date('Y-m-d H:i:s',(strtotime ( '-7 day' , strtotime ( $enddate) ) ));
        } else {
            $begindate = date('Y-m-d H:i:s',(strtotime ( '-31 day' , strtotime ( $enddate) ) ));
        }
        
        // Create connection
        $con = mysqli_connect("localhost", "zwave", "Panda50!", "zwave");

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {

            $query = "select max(temperature) maxtemperature , min(temperature) mintemperature from sensor_log
            WHERE DATETIME between '".$begindate."' and '".$enddate."'
            AND sensor_id = ". $sensor_id ."
            ";
        
if ($result = $con->query($query)) {

                /* fetch associative array */
                while ($row = $result->fetch_assoc()) {
                    $resultset[] = array ('mintemperature' => $row['mintemperature'],
                        'maxtemperature' => $row['maxtemperature']
                   );
                }
                
                
                
                /* free result set */
                $result->free();            
            
    }}
    
    print_r($resultset);
    
    return $resultset ;
    
                }
    
    public function getsensordata($interval,$sensor_id) {

        //Bepaal begin / einddatum
        $enddate = date('Y-m-d H:i:s');
        
        if ($interval == '24h'){
            $begindate = date('Y-m-d H:i:s',(strtotime ( '-1 day' , strtotime ( $enddate) ) ));
        } else if ($interval == 'week'){
            $begindate = date('Y-m-d H:i:s',(strtotime ( '-7 day' , strtotime ( $enddate) ) ));
        } else if ($interval == 'month'){
            $begindate = date('Y-m-d H:i:s',(strtotime ( '-1 month' , strtotime ( $enddate) ) ));
        } else {
            $begindate = date('Y-m-d H:i:s',(strtotime ( '-1 year' , strtotime ( $enddate) ) ));
        }

        // Create connection
        $con = mysqli_connect("localhost", "zwave", "Panda50!", "zwave");

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {

            
            if ($interval == '24h'){
                $table = 'sensor_avg5m';
            } else if ($interval == 'week'){
                $table = 'sensor_avg30m';
            } else if ($interval == 'month'){
                $table = 'sensor_avg120m';
            } else {
                 $table = 'sensor_avg24h';
            }
            
            $query = "select sa_dateTime, sa_value 
                  from $table
                  where sa_s_id = $sensor_id
                  and sa_datetime between '$begindate' and '$enddate'
                  order by sa_datetime";

            //echo $query;
            
            
            if ($result = $con->query($query)) {

                /* fetch associative array */
                while ($row = $result->fetch_assoc()) {
                    if ($interval == '24h'){
                        $resultset[] = array ('datetime' => substr($row['sa_dateTime'], 10,6) , 
                                              'value' => $row['sa_value']);
                    } else {
                        $resultset[] = array ('datetime' => substr($row['sa_dateTime'], 0,16) , 
                                              'value' => $row['sa_value']);
                    }
                            
                            
                            
                            
                            
                }
                /* free result set */
                $result->free();
            }
        }
        
        //echo '<PRE>';
        //print_r($resultset);
        ///echo '</PRE>';
        
        $con->close();
        
        return $resultset;
        
        
        
        
        }

}



?>
