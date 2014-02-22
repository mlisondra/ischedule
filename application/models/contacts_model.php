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
         * @param int $contact_id
         */
        public function contact_categories($contact_id){
            // $sql_query = "SELECT `contact`,`category` FROM `$this->contacts_categories` WHERE `contact`= '$contact'";
            
            $this->db->where('contact',$contact_id);
            $query = $this->db->get($this->contacts_calendars);
            if($query->num_rows() > 0){
                    return $query->result();
            }else{
                    return 0;
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
                $contact_id = $this->db->insert_id();
                Contacts_model::calendar_contacts(array("contact_id"=>$contact_id,"calendars"=>$categories));
                return $contact_id;
            }else{
                return false;
            }   
        }
        
        /**
         * Update contact
         * @param array $args
         */
        public function update($args){
            $contact_id = $args['id'];
            $calendars = $args['category'];
            unset($args['category']);
            unset($args['obj_type']);
            
            $this->db->where("id",$args['id']);
            $this->db->update($this->contacts,$args); 
            Contacts_model::calendar_contacts(array("contact_id"=>$args['id'],"calendars"=>$calendars),1);
            return true;
           
        }
        
        /**
         * Delete contact with given id
         * @param int $contact_id - Contact id
         * 
         */
	public function delete($contact_id){
            $this->db->delete($this->contacts, array("id"=>$contact_id));
            if($this->db->affected_rows() == 1){
                // delete relationships between this contact and calendars
                Contacts_model::calendar_contacts(array("contact_id"=>$contact_id),1);
                return true;
            }else{
                return false;
            }
	}        
	
        /**
         * Updates the relationship between a calendar and contacts
         * @param array $args - contains id
         * @param int $update - set to true to delete existing relationships
         */
        public function calendar_contacts($args, $update){
            extract($args);
            if($update){
                $this->db->where('contact',$contact_id);
                $this->db->delete($this->contacts_calendars); 
            }
            if(count($calendars) > 0){
                foreach($calendars as $calendar){
                    $data[] = array('contact'=>$contact_id,'category'=>$calendar);
                }            
                $this->db->insert_batch($this->contacts_calendars,$data);                
            }
            log_message('debug',$this->db->last_query());            
        }

        // Reminders because I'm very forgetful
        // call method in another Model
	//Schedule_model::test_test();
}