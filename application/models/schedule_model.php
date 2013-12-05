<?php

class Schedule_model extends CI_Model {

	protected $accounts = 'users';
	protected $calendars = 'user_categories';
        protected $events = 'user_events';
	
	/**
	* add_calendar
	* Add calendar for user
	* @param array $args
	*/
	public function add_calendar($data){
            $data['created_datetime'] = time();
            $data['modified_datetime'] = time();
            if($this->check_calendar_exists($data['name'],$data['user']) === false){
                $this->db->insert($this->calendars, $data);
                if($this->db->affected_rows() == 1){
                    return true;
                }else{
                    return false;
                }                    
            }else{
                return false;
            }
	}
	
	
	/**
	* Retrieve calendars for given user id
	* @param int $user_id
	*/
	public function get_calendars($user_id){
		$this->db->where('user',$user_id);
		$query = $this->db->get($this->calendars);
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return 0;
		}		
		//print __METHOD__; // returns class name and function name
		//print __FUNCTION__; // returns function name only
	}

        /**
         * check_calendar_exists
         * Check if given calendar name exists for given user id
         * @param string $name
         * @param int $user_id
         */
        public function check_calendar_exists($name,$user_id){
            $user_calendars = $this->get_calendars($user_id);
            if(count($user_calendars) > 0){
                foreach($user_calendars as $calendar){
                    $names_array[] = $calendar->name;
                }
                if(in_array($name,$names_array)){
                    return true;
                }else{
                    return false;                    
                }
            }else{
                return false;
            }
        }
        
	/**
	* check_account_exists
	* @param string $email
	*/
	public function check_email_exists($email){
		$this->db->where('email',$email);
		$query = $this->db->get($this->accounts);
		if($query->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	* check_password
	* @param string $password
	* @param string $email
	* Checks to see if the given password already exists for the given user email
	*/
	public function check_password($email,$password){
		$hashed_password = md5($password);
		$this->db->select('id','first_name','last_name','password','phone','phone_carrier','status','subscription');
		$this->db->where('email',$email);
		$this->db->where('password',$hashed_password);
		$query = $this->db->get($this->accounts);
		if($query->num_rows() == 1){
			return true;
		}else{
			return false;
		}		
		
	}

	/**
	* get_user
	* Retrieve user info
	* $email string User email
	* @return object $user
	*/
	public function get_user($email){
		$this->db->select('id,first_name,last_name,phone,phone_carrier,status,subscription');
		$this->db->where('email',$email);
		$query = $this->db->get($this->accounts);
		if($query->num_rows() == 1){
			$user = $query->row();
			return $user;
		}else{
			return false;
		}
	}
	
	/**
	* delete_calendar
	* Delete Calendar using given id
	*/
	public function delete_calendar($id){
            $this->delete_calendar_events($id); // Delete events associated with Calendar
            $this->db->delete($this->calendars, array("id"=>$id));
            return $this->db->affected_rows();
	}
        
        /*
         * Delete calendar events associated with given calendar id
         * @param int $id - Calendar id
         */
        public function delete_calendar_events($id){
            $this->db->delete($this->events, array("category"=>$id));
            return $this->db->affected_rows();            
        }
	
}




