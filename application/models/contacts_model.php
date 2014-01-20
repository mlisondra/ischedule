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
            //print $this->db->last_query();
            if($query->num_rows() == 1){
                return $query->row(); // return contact object
            }
        }

        /**
         * Get contact using given contact id
         *  @param int $id Contact id
         */
        public function get_contact_by_email($email){
            $this->db->where('email',$email);
            $query = $this->db->get($this->contacts);
            //print $this->db->last_query();
            if($query->num_rows() == 1){
                return $query->row(); // return contact object
            }
        }        
	
}