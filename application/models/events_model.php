<?php

class Events_model extends CI_Model {

	//protected $accounts = 'users';
	//protected $calendars = 'user_categories';
        //protected $calendar_managers = 'calendar_managers';
        protected $events = 'user_events';
        //protected $contacts = 'user_contacts';
        protected $cdt = "";

        public function __construct(){
            parent::__construct();
            $this->cdt = mktime();
        }
        
        /**
         * Get a single event or multiple events
         * @param int $id Event id
         */
        public function get($id){
            //print_r($id);
            $this->db->where('id',$id);
            $query = $this->db->get($this->events);
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return 0;
		}
        }
        
        public function delete($id){
            $this->db->delete($this->events, array("id"=>$id));
            if($this->db->affected_rows() == 1){
                return true;
            }else{
                return false;
            }            
        }
        
}