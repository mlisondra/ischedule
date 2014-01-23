<?php

class Utilities_model extends CI_Model {
   
    public function __construct(){
        parent::__construct();
    }
        
    public function get_phone_carriers(){
        $carriers_array = array("AT&T","Metro PCS","Sprint","T-Mobile","Verizon","Virgin Mobile");
	return $carriers_array; 
    }
}