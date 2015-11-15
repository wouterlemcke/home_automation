<?php

class toon {
    
    private $username = "";
    private $password = "";
      
    private $clientId;
    private $clientIdChecksum;
    private $agreementId;
    private $agreementIdChecksum;
    
    private $toonState;
    
    private $logFileWriter;
    
    public $gasUsage = null;
    public $powerUsage = null;
    public $temperature = null;
    
    public function __construct($logFileWriter) {
        $this->logFileWriter = $logFileWriter;
    }
    

    public function getMeasures(){
        $this->login();
        
        if (!empty($this->clientId)){
        
        
            while (is_null($this->gasUsage) or is_null($this->powerUsage) or is_null($this->temperature)){

                $this->toonState = $this->getToonState();

                if ($this->powerUsage == null && property_exists($this->toonState,'powerUsage')){
                    $this->powerUsage = $this->toonState->powerUsage->value;

                }

               if ($this->gasUsage == null && property_exists($this->toonState,'gasUsage') ){
                    $this->gasUsage = $this->toonState->gasUsage->value;
                }

                if ($this->temperature == null && property_exists($this->toonState,'thermostatInfo')){
                    $this->temperature = $this->toonState->thermostatInfo->currentTemp / 100;
                }


            }

             $this->logFileWriter->log("power:" . $this->powerUsage);
             $this->logFileWriter->log("gas:" . $this->gasUsage);
             $this->logFileWriter->log("temp:" . $this->temperature);

            $this->logout();
            return true;
        } else {
            return false;
        }
    }
    
    private function getToonState(){
        
        $this->logFileWriter->log("Getting toonState");
        
        $toonstate = json_decode(file_get_contents("https://toonopafstand.eneco.nl/toonMobileBackendWeb/client/auth/retrieveToonState"
                 . "?clientId=" . $this->clientId
                 . "&clientIdChecksum=" .$this->clientIdChecksum 
                 . "&random=" .  uniqid()));
        
        return $toonstate;
        
    }
    
    
    private function login(){
        
        $this->logFileWriter->log("Toon login phase 1");
        
        $response = json_decode(file_get_contents("https://toonopafstand.eneco.nl/toonMobileBackendWeb/client/login?username=" . $this->username . "&password=" . $this->password));
        
        if (!empty($response)){
        
            $this->clientId = $response->clientId;
            $this->clientIdChecksum = $response->clientIdChecksum;
            $this->agreementId = $response->agreements[0]->agreementId;
            $this->agreementIdChecksum = $response->agreements[0]->agreementIdChecksum;

            $this->logFileWriter->log("Toon login phase 2");

            $response2 = json_decode(file_get_contents("https://toonopafstand.eneco.nl/toonMobileBackendWeb/client/auth/start"
                     . "?clientId=" . $this->clientId 
                     . "&clientIdChecksum=" . $this->clientIdChecksum
                     . "&agreementId=" . $this->agreementId
                     . "&agreementIdChecksum=" . $this->agreementIdChecksum
                     . "&random=" . uniqid()));
        } else {
             $this->logFileWriter->log("Toon login failed");
        }
    }
    
    private function logout(){
        $this->logFileWriter->log("Toon logout");
        
         $response = file_get_contents("https://toonopafstand.eneco.nl/toonMobileBackendWeb/client/auth/logout"
                 . "?clientId=" . $this->clientId 
                 . "&clientIdChecksum=" . $this->clientIdChecksum
                 . "&random=" . uniqid());
         
    }
    
    
}

?>
