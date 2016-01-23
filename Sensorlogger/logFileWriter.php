<?php

class logFileWriter {
    
    private $conn;
    
    function __construct($conn){
        $this->conn = $conn;
    }
    
    public function log ($message){
       
       $date = date("Y-m-d H:i:s");
        
       echo $date . " " . $message . "\r\n";
       
       $this->conn->select_db("zwave");
       $stmt = $this->conn->prepare("insert into log (datetime, message) values (?,?)");
       $stmt->bind_param("ss",$date,$message);
       $stmt->execute();       

   }
    
    
}

?>
