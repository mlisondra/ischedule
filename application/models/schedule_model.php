<?php

class Schedule_model extends CI_Model {

	protected $accounts = 'users';
	protected $calendars = 'user_categories';
        protected $calendar_managers = 'calendar_managers';
        protected $events = 'user_events';
        protected $contacts = 'user_contacts';
        protected $cdt = "";

        public function __construct(){
            parent::__construct();
            $this->cdt = mktime();
        }
       
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
                    log_message('info','number of affected rows on insert in calendar ' . $this->db->affected_rows());
                    return true;
                }else{
                    return false;
                }                    
            }else{
                return false;
            }
	}
        
        public function update_calendar($args){
            $this->db->where("id",$args['id']);
            unset($args['id']);
            $this->db->update($this->calendars,$args);
            if($this->db->affected_rows() == 1){
                return true;
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
        
        /*
         * Retrieve calendar with given id
         * @param int $id
         */
        public function get_calendar($id){
            $this->db->where('id',$id);
            $query = $this->db->get($this->calendars);
            if($query->num_rows() == 1){
                    return $query->result();
            }else{
                    return 0;
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
	* get_calendar_managers
        * @param int $calendar_id
	*/
	public function get_calendar_managers($calendar_id){
            $this->db->select('manager_id');
            $this->db->where('calendar_id',$calendar_id);
            $query = $this->db->get($this->calendar_managers);
            if($query->num_rows() > 0){
                foreach($query->result() as $manager){
                    $managers_array[] = $manager->manager_id;
                }
                // Get info for each manager
                $this->db->select('first_name,last_name,id');
                $this->db->where_in('id',$managers_array);
                $managers_query = $this->db->get($this->accounts);

                return $managers_query->result();
                
            }else{
                return 0;
            }
	}
        
        /*
         * update_calendar_managers
         * Update managers for given Calendar
         * Each manager must have an account; a new account for a Manager is created, if needed
         * @param array $args ($id - calendar id; $calendar_managers - array of manager ids)
         */
        public function update_calendar_managers($args){
            $this->load->model('contacts_model');
            // Delete all existing manager ids
            $this->db->where('calendar_id',$args['id']);
            $this->db->delete($this->calendar_managers);
           
            // Add each given manager id
            foreach($args['calendar_managers'] as $manager){
                //check to see that each manager has a valid account
                // get manager's information from user_contacts - need email address
                $contact_info = $this->contacts_model->get_contact($manager);
                
                if($contact_info != 0){
                    // see if contact has an account
                    if($this->check_email_exists($contact_info->email)){
                        $user = $this->get_user($contact_info->email);
                        $user_id = $user->id;
                        
                    }else{
                        $new_user['first_name'] = $contact_info->first_name;
                        $new_user['last_name'] = $contact_info->last_name;
                        $new_user['email'] = $contact_info->email;
                        $new_user['password'] = md5($this->get_random_string("abcdefABCDEF_0123456789",5));
                        $new_user['phone'] = $contact_info->phone;
                        $new_user['phone_carrier'] = $contact_info->phone_carrier;
                        $user_id = $this->create_user($new_user);
                        
                        // Send new user email regarding their new account
                    }
                }
                
                $this->db->insert($this->calendar_managers, array('calendar_id'=>$args['id'],'manager_id'=>$user_id));
            }
        }
        
	/**
	* check_account_exists
	* @param string $email
        * @return boolean
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
         * @return boolean
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
         * Create a new user
         * @param $data array
         * @return mixed - user id on success; false on failure
         */
        public function create_user($data){
            $this->db->insert($this->accounts,$data);
            return $this->db->insert_id();
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
	
        
        /*
         * Delete calendar events associated with given calendar id
         * @param int $id - Calendar id
         * @return int - number of affected rows
         */
        public function delete_calendar_events($id){
            $this->db->delete($this->events, array("category"=>$id));
            return $this->db->affected_rows();            
        }

	/**
	* get_user_events_date_range
	* @param array $args: int user, int $start, int $end
	* @return mixed array $results on Success; 0 on Failure or 0 records
	* Retrieves events for given user id within the datetime range of $start and $end
	*/
	public function get_user_events_date_range($args){
            $this->load->library('session');
            
		extract($args);
		
                $this->db->select('e.*, cat.color, cat.name as category_name');
                $this->db->from('user_events e');
                $this->db->join('user_categories cat','e.category = cat.id');
		$this->db->where('e.user',$this->session->userdata('id'));
                $this->db->where('e.begin_date_time >=',$args['start']);
                $this->db->where('e.end_date_time <=',$args['end']);
                $query = $this->db->get();
                
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return 0;
		}
	}
        
        /**
         * add_event
         * Add an event
         * @param array $args
         */
        public function add_event($args){
            extract($args);
            //change date into correct mysql datetime format		
		//build begin date time
		$month = substr($begin_date,0,2);
		$day = substr($begin_date,3,2);
		$year = substr($begin_date,6,4);
		$begin_time_array = explode(":",$begin_time);
		$begin_hour = $begin_time_array[0];
		if(substr($begin_time,-2) == 'PM' && $begin_hour < 12){
			$begin_hour = $begin_hour + 12;
		}
		if(substr($end_time,-2) == 'AM' && $begin_hour == 12){
			$begin_hour = 24;
		}	

		$begin_minute = substr($begin_time_array[1],0,2);
		if($begin_minute < 10 && $begin_minute != "00"){
			$begin_minute = substr($begin_minute,1,1);
		}
		$event_begin_date_time = mktime($begin_hour,$begin_minute,0,$month,$day,$year);
		$insert_values['begin_date_time'] = $event_begin_date_time;
                
		//build end_date_time
		$month = substr($end_date,0,2);
		$day = substr($end_date,3,2);
		$year = substr($end_date,6,4);
		$end_time_array = explode(":",$end_time);
		$end_hour = $end_time_array[0];
		if(substr($end_time,-2) == 'PM' && $end_hour < 12){
			$end_hour = $end_hour + 12;
		}
		if(substr($end_time,-2) == 'AM' && $end_hour == 12){
			$end_hour = 24;
		}
		$end_minute = substr($end_time_array[1],0,2);
		if($end_minute < 10){
			$end_minute = substr($end_minute,1,1);
		}		
		$event_end_date_time = mktime($end_hour,$end_minute,0,$month,$day,$year);
		$insert_values['end_date_time'] = $event_end_date_time;

                $insert_values['title'] = $args['title'];
                $insert_values['description'] = $args['description'];
                $insert_values['category'] = $args['category'];
                $insert_values['user'] = $args['user'];
                $insert_values['created'] = $this->cdt;
                $insert_values['modified'] = $this->cdt;
                
                // TODO: reminder notifications need to be added database
                
                $this->db->insert($this->events, $insert_values);
                log_message('info',$this->db->last_query());
                if($this->db->affected_rows() == 1){
                    return $this->db->insert_id();
                }else{
                    return false;
                }                    
                
        }
        
        /**
         * update_event
         */
        public function update_event(){
            
        }
        
        public function search_users($search_term,$user_id){
           
            $this->db->select('id,first_name,last_name');
            $this->db->where("(`first_name` LIKE '$search_term%' OR `last_name` LIKE '$search_term%')");
            $this->db->where('user',$user_id);
            $query = $this->db->get($this->contacts);
            log_message('debug',$search_term);
            log_message('debug',$this->db->last_query());
            if($query->num_rows() > 0){
                return $query->result();
            }else{
                return 0;
            }
        }
        
        public function get_random_string($valid_chars, $length){
            // start with an empty random string
            $random_string = "";

            // count the number of chars in the valid chars string so we know how many choices we have
            $num_valid_chars = strlen($valid_chars);

            // repeat the steps until we've created a string of the right length
            for ($i = 0; $i < $length; $i++){
                // pick a random number from 1 up to the number of valid chars
                $random_pick = mt_rand(1, $num_valid_chars);

                // take the random character out of the string of valid chars
                // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
                $random_char = $valid_chars[$random_pick-1];

                // add the randomly-chosen char onto the end of our string so far
                $random_string .= $random_char;
            }

            // return our finished random string
            return $random_string;
        }

}




