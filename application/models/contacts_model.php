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
	
	
}