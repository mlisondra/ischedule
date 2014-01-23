<?php

class Contacts_model extends CI_Model {

	protected $contacts = 'user_contacts';
        protected $contacts_calendars = 'user_contacts_categories';
	
	/**
	* get_contacts
	* @param int $user_id
	* @return array query results (array which contains object as elements)
	*
	*/
	public function get_contacts($user_id){
		$this->db->where('user',$user_id);
		$query = $this->db->get($this->contacts);
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return 0;
		}
	}
        
        /**
         * Get contact using given contact id
         *  @param int $id Contact id
         */
        public function get_contact($id){
            $this->db->where('id',$id);
            $query = $this->db->get($this->contacts);
            if($query->num_rows() == 1){
                return $query->row(); // return contact object
            }
        }
        
        /**
         * 
         * @param array $args
         * @return returns the new record's id on success; false on failure
         */
        public function add($args){
            $categories = $args['category'];
            unset($args['category']);
            $this->db->insert($this->contacts,$args);
            //print $this->db->last_query();
            
            if($this->db->affected_rows() == 1){
                $contact_id = $this->db->insert_id();
                Contacts_model::calendar_contacts(array("contact_id"=>$contact_id,"calendars"=>$categories));
                return $contact_id;
            }else{
                return false;
            }   
        }
	
        /**
         * Updates the relationship between a calendar and contacts
         */
        public function calendar_contacts($args){
            extract($args);
            
            foreach($calendars as $calendar){
                $data[] = array('contact'=>$contact_id,'category'=>$calendar);
            }
            
            $this->db->insert_batch($this->contacts_calendars,$data);
            
        }

	
}