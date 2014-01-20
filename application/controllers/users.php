<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {
    
    protected $userid;
    
    public function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->load->model('schedule_model');
    }
    
    public function index(){
        $this->userid = $this->uri->segment(2);
        if($this->uri->segment(3) == "calendars"){
            $this->calendars();
        }else{
            $users = array("fname"=>"Milder","lname"=>"Lisondra","email"=>"milder.lisondra@yahoo.com","calendars"=>array("1","2"));
            echo json_encode($users);
        }
    }
    
    // Get users for given calendar
    public function calendars(){
        echo json_encode(array(1,2,3));
    }
        
}

