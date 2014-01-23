<?php

class Contacts_model extends CI_Model {

	protected $contacts = 'user_contacts';
	
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
            
            if($this->db->affected_rows() == 1){
                // in here, need to call local method manage_contacts_categories with the user id, 
                return $this->db->insert_id();
            }else{
                return false;
            }   
        }
	
        /**
         * Updates the categories that the given contact id is to be associated with
         */
        public function manage_contacts_categories($args){
            print_r($args);
        }

	
}