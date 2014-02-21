<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilities extends CI_Controller {

	public function __construct(){
		parent::__construct();
                // load necessary models and libraries
		$this->load->library('session');
                $this->load->model('schedule_model');
                $this->load->model('utilities_model');
	}
        
        public function checktime(){
            $seed_time = 1392188400;
            print date("m/d/Y",$seed_time);
        }
}