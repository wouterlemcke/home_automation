<?php

class zwaveSensor {
   
    private $logFileWriter;
    
   public function setLogFileWriter($logFileWriter){
       $this->logFileWriter = $logFileWriter;
   }
    
    public function getMeasure($sensorId,$serviceId, $variable){
        
        $url = "http://192.168.2.3:3480/data_request?id=variableget&DeviceNum=" . $sensorId .  
                                 "&serviceId=".$serviceId ."&Variable=" . $variable;
        
        $this->logFileWriter->log("$url");
        
        $value = file_get_contents($url);
        
         //Set value to 100 for binary switches
         if ($serviceId == 'urn:upnp-org:serviceId:SwitchPower1' && $value == 1){
             $value = 100;
         }
        
        
        return $value;
        
        
        
        
  
        
    }
    
    
}

?>
