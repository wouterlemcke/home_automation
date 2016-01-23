<?php

class dbWriter {

    
    private $servername ;
    private $username ;
    private $password;
    
    private $conn;
    private $logFileWriter;
    
    function __construct(){
        if (gethostname() == 'q9300')    {
            $this->servername = "localhost";
            $this->username = "root";   
            $this->password = "";
        } else {
            $this->servername = "localhost";
            $this->username = "zwave";   
            $this->password = "Panda50!";
        }
    }

   public function setLogFileWriter($logFileWriter){
       $this->logFileWriter = $logFileWriter;
   }
    

   /**
    * Creates a connection
    *
    * @author       Erik Lemcke
    * @since        2014-12-15
    * @todo         
    */
    public function connect (){
        //$this->logFileWriter->log('Setting up database connection');
        $this->conn = new mysqli($this->servername, $this->username, $this->password);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } 
        
        return $this->conn;
        
    }
    
    /**
    * Disconnects from the db
    *
    * @author       Erik Lemcke
    * @since        2014-12-15
    * @todo         
    */
    public function disconnect(){
        $this->logFileWriter->log('Disconnection from database');
        $this->conn->close();
    }
    
  /**
   * writes data for a sensor to the db
   *
   * @author       Erik Lemcke
   * @since        2014-12-15
   * @todo         
   */
   function writeSensorData ($sensorId, $value, $dateTime, $isLight = false){
       
       $this->logFileWriter->log("Writing value $value for sensor $sensorId");
       
       //Sometimes vera return 0, wich is not ok. To prevent this, check if the last value was close to 0
       //if it was not, take the last value as the current value
       //Don't do it for light switches...
       if ($value == 0 && !isLight) {
           $this->logFileWriter->log("Possible errorous value, checking last value.");
           $lastvalue = $this->getLastValue($sensorId);
           
           $this->logFileWriter->log("LastValue was $lastvalue");
           
           if ($lastvalue > 1 || $lastvalue < -1){
               $value = $lastvalue;
           }
           
           $this->logFileWriter->log("using $value");
           
       }
       
       $this->conn->select_db("zwave");
       $stmt = $this->conn->prepare("insert into sensor_data (sd_s_id, sd_datetime, sd_value) values (?,?,?)");
       $stmt->bind_param("sss",$sensorId,$dateTime,$value);
       $stmt->execute();       
       
       $this->writeAverages5m($sensorId, $dateTime);
       $this->writeAverages30m($sensorId, $dateTime);
       $this->writeAverages120m($sensorId, $dateTime);
       $this->writeAverages24h($sensorId, $dateTime);
       
   }
   
   /**
   * gets the last value for a sensor
   *
   * @author       Erik Lemcke
   * @since        2015-10-14
   * @todo         
   */
   function getLastValue($sensorId){
       
       $query = "SELECT sd_value FROM sensor_data where sd_s_id = $sensorId ORDER BY sd_id DESC LIMIT 0, 1";
       $stmt = $this->conn->prepare($query); 
       $stmt->execute();
       $stmt->bind_result($value);
       $stmt->fetch();
       $stmt->close();
       
       return $value;
       
   }
   
   
  /**
   * writes a average for 5 minutes to the db
   *
   * @author       Erik Lemcke
   * @since        2014-12-15
   * @todo         
   */
   function writeAverages5m ($SensorId,$dateTime){
    $currentMinute = date('i',strtotime($dateTime));

    $startdate = date('Y-m-d H',strtotime($dateTime)) . ':' . sprintf( '%02d',5*(floor(abs($currentMinute/5)))) . ':00';
    $enddate   = date("Y-m-d H:i:s",strtotime('+5 minutes', strtotime($startdate)));
    
    $this->logFileWriter->log("Writing 5 minute average for sensor $SensorId using startdate $startdate and enddate $enddate");
    
    //Check if there's already data for this timeslot
    $q = "select sa_id from  sensor_avg5m where sa_datetime between '$startdate' and '$enddate' and sa_s_id = $SensorId";
    $result = $this->conn->query($q);
   
    //No data, insert new data
    if ($result->num_rows == 0){
        //calculate average and insert it
        $q = "select sd_value from  sensor_data where sd_datetime between '$startdate' and '$enddate' and sd_s_id = $SensorId";
        
        $result = $this->conn->query($q);
        
        $total = null;
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $result->num_rows,2);
        
        $stmt = $this->conn->prepare("insert into sensor_avg5m (sa_s_id, sa_datetime, sa_value) values (?,?,?)");
        $stmt->bind_param("sss",$SensorId,$startdate,$avg);
        $stmt->execute();       
        
        echo $this->conn->error;
        
    } else {

        //get id fro avg record
        $row = $result->fetch_assoc();
        $avg_id = $row['sa_id'];
    
        //calculate new averages
        $q = "select sd_value from  sensor_data where sd_datetime between '$startdate' and '$enddate' and sd_s_id = $SensorId";
        
        $result = $this->conn->query($q);
        
        $total = null;
        $rows = $result->num_rows;
        
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $rows,2);
        
        //update avg row
        $stmt = $this->conn->prepare("update sensor_avg5m set sa_value = ? where sa_id = ?");
        $stmt->bind_param("ss",$avg,$avg_id );
        $stmt->execute(); 
        
    }
}
/**
   * writes a average for 30 minutes to the db
   *
   * @author       Erik Lemcke
   * @since        2014-12-15
   * @todo         
   */
   function writeAverages30m ($SensorId,$dateTime){
    $currentMinute = date('i',strtotime($dateTime));

    $startdate = date('Y-m-d H',strtotime($dateTime)) . ':' . sprintf( '%02d',30*(floor(abs($currentMinute/30)))) . ':00';
    $enddate   = date("Y-m-d H:i:s",strtotime('+30 minutes', strtotime($startdate)));
    
    $this->logFileWriter->log("Writing 30 minute average for sensor $SensorId using startdate $startdate and enddate $enddate");
    
    //Check if there's already data for this timeslot
    $q = "select sa_id from  sensor_avg30m where sa_datetime between '$startdate' and '$enddate' and sa_s_id = $SensorId";
    $result = $this->conn->query($q);
   
    //No data, insert new data
    if ($result->num_rows == 0){
        //calculate average and insert it
        $q = "select sd_value from  sensor_data where sd_datetime between '$startdate' and '$enddate' and sd_s_id = $SensorId";
        $result = $this->conn->query($q);
        
        $total = null;
        $rows = $result->num_rows;
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $rows,2);
        
        $stmt = $this->conn->prepare("insert into sensor_avg30m (sa_s_id, sa_datetime, sa_value) values (?,?,?)");
        $stmt->bind_param("sss",$SensorId,$startdate,$avg);
        $stmt->execute();       
        
        echo $this->conn->error;
        
    } else {

        //get id for avg record
        $row = $result->fetch_assoc();
        $avg_id = $row['sa_id'];
    
        //calculate new averages
        $q = "select sd_value from  sensor_data where sd_datetime between '$startdate' and '$enddate' and sd_s_id = $SensorId";
        $result = $this->conn->query($q);
        
        $total = null;
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $result->num_rows,2);
        
        //update avg row
        $stmt = $this->conn->prepare("update sensor_avg30m set sa_value = ? where sa_id = ?");
        $stmt->bind_param("ss",$avg,$avg_id );
        $stmt->execute(); 
        
    }
 
 
} 

   /**
   * writes a average for 120 minutes to the db
   *
   * @author       Erik Lemcke
   * @since        2014-12-15
   * @todo         
   */
   function writeAverages120m ($SensorId,$dateTime){
    
    if (date('H',strtotime($dateTime)) % 2 != 0){
        $hour = date('H',strtotime($dateTime)) - 1;
    } else {
        $hour = date('H',strtotime($dateTime));
    }
    
    $startdate = date('Y-m-d ' . $hour .  ':00:00',strtotime($dateTime));
    $enddate   = date("Y-m-d H:i:s",strtotime('+120 minutes', strtotime($startdate)));

    $this->logFileWriter->log("Writing 2hour average for sensor $SensorId using startdate $startdate and enddate $enddate");
    
    //Check if there's already data for this timeslot
    $q = "select sa_id from  sensor_avg120m where sa_datetime between '$startdate' and '$enddate' and sa_s_id = $SensorId";
    $result = $this->conn->query($q);
   
    //No data, insert new data
    if ($result->num_rows == 0){
        //calculate average and insert it
        $q = "select sd_value from  sensor_data where sd_datetime between '$startdate' and '$enddate' and sd_s_id = $SensorId";
        $result = $this->conn->query($q);
        
        $total = null;
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $result->num_rows,2);
        
        $stmt = $this->conn->prepare("insert into sensor_avg120m (sa_s_id, sa_datetime, sa_value) values (?,?,?)");
        $stmt->bind_param("sss",$SensorId,$startdate,$avg);
        $stmt->execute();       
        
        echo $this->conn->error;
        
    } else {

        //get id for avg record
        $row = $result->fetch_assoc();
        $avg_id = $row['sa_id'];
    
        //calculate new averages
        $q = "select sd_value from  sensor_data where sd_datetime between '$startdate' and '$enddate' and sd_s_id = $SensorId";
        $result = $this->conn->query($q);
        
        $total = null;
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $result->num_rows,2);
        
        //update avg row
        $stmt = $this->conn->prepare("update sensor_avg120m set sa_value = ? where sa_id = ?");
        $stmt->bind_param("ss",$avg,$avg_id );
        $stmt->execute(); 
        
    }
} 
    
 /**
   * writes a average for 24h to the db
   *
   * @author       Erik Lemcke
   * @since        2014-12-15
   * @todo         
   */
   function writeAverages24h ($SensorId,$dateTime){
       
    $date = date('Y-m-d',strtotime($dateTime));

    $this->logFileWriter->log("Writing 24 hour average for sensor $SensorId using $date");
    
    //Check if there's already data for this timeslot
    $q = "select sa_id from  sensor_avg24h where date(sa_datetime) = '$date' and sa_s_id = $SensorId";
    $result = $this->conn->query($q);
   
    //No data, insert new data
    if ($result->num_rows == 0){
        //calculate average and insert it
        $q = "select sd_value from  sensor_data where date(sd_datetime) = '$date' and sd_s_id = $SensorId";
        $result = $this->conn->query($q);
        
        $total = null;
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $result->num_rows,2);
        
        $stmt = $this->conn->prepare("insert into sensor_avg24h (sa_s_id, sa_datetime, sa_value) values (?,?,?)");
        $stmt->bind_param("sss",$SensorId,$date,$avg);
        $stmt->execute();       
        
        echo $this->conn->error;
        
    } else {

        //get id for avg record
        $row = $result->fetch_assoc();
        $avg_id = $row['sa_id'];
    
        //calculate new averages
        $q = "select sd_value from  sensor_data where date(sd_datetime) = '$date' and sd_s_id = $SensorId";
        $result = $this->conn->query($q);
        
        echo $this->conn->error;
        
        $total = null;
        
        while ($row = $result->fetch_assoc()) {
            $total += $row['sd_value'];
        }
        
        $avg = round($total / $result->num_rows,2);
        
        //update avg row
        $stmt = $this->conn->prepare("update sensor_avg24h set sa_value = ? where sa_id = ?");
        $stmt->bind_param("ss",$avg,$avg_id );
        $stmt->execute(); 
        
        
    }
} 


   
    }



?>

