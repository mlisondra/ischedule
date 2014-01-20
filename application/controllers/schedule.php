<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
                $this->load->model('schedule_model');
                $this->load->model('contacts_model');
	}
	
	public function index(){
		if($this->session->userdata('authenticated') == 1){
			$this->load->view('schedule_view');
		}else{
			$this->load->view('login_view');
		}
	}
	
	/**
	* auth
	* Authenticate user
	* Takes post data via ajax
	*/
	public function auth(){
		
		$this->load->model('schedule_model');
		
		if($this->schedule_model->check_email_exists($this->input->post('user_email'))){ // check that email exists
			// validate password
			if($this->schedule_model->check_password($this->input->post('user_email'),$this->input->post('password'))){
				$user = $this->schedule_model->get_user($this->input->post('user_email'));
				$user->authenticated = 1;
				//$user_array = get_object_vars($user); print_r($user_array);
				$response['status'] = "ok";
				$response['message'] = "User Authenticated";
				// Should place some of the user's info in session
				$this->session->set_userdata($user);

			}else{
				$response['status'] = "error";
				$response['message'] = "Password given is incorrect";				
			}	
		}else{
			$response['status'] = "error";
			$response['message'] = "Email not found";
		}
		print json_encode($response);
		
	}
	
	/*
	* logout
	* Clear all sessions and redirect user accordingly
	*/
	public function logout(){
		$this->load->helper('url');
		$this->session->sess_destroy();
		redirect('../schedule');
	}
	
	/**
	* Retrieve categories for logged in user
	* in addition, gets any calendars which the user is a manager for
	* get the events associated with each calendar, too.
	*/	
	public function get_categories(){
		$calendars = $this->schedule_model->get_calendars($this->session->userdata('id'));
		if($calendars != 0){
			$response['num_calendars'] = count($calendars);
			$response['calendars'] = $calendars;
		}else{
			$response['num_calendars'] = 0;
		}
		print json_encode($response);
	}
	
	public function add_calendar(){
		if($this->input->is_ajax_request()){
			$_POST['user'] = $this->session->userdata('id'); // Must be a better way to do this
			unset($_POST['action']);
                        $response = "";
                        $message = "";
			$check = $this->schedule_model->check_calendar_exists($this->input->post('name'),$this->input->post('user'));
			if($this->schedule_model->check_calendar_exists($this->input->post('name'),$this->input->post('user')) === false){
                            if($this->schedule_model->add_calendar($this->input->post())){
                                    $status = 1;
                            }else{
                                    $status = 0;
                                    $message = "Could not add Calendar.";
                            }                            
                        }else{
                            $status = 0;
                            $message = "Calendar name taken";
                        }
                        print json_encode(array("status"=>$status,"message"=>$message));
		}

	}
	
	public function delete_calendar(){
	
	}
	
	public function edit_calendar(){
	
	}
	
	public function add_event(){
            $_POST['user'] = $this->session->userdata('id'); // Must be a better way to do this
            if($this->schedule_model->add_event($this->input->post()) != false){
                print json_encode(array("status"=>"ok"));
            }else{
                print json_encode(array("status"=>"fail"));
            }
	}
	
	public function delete_event(){
	
	}
	
	public function edit_event(){
	
	}
	
        /**
         * Retrieve data for the calendar
         * Uses the currently logged in user's id
         */
        public function get_events(){
            $this->load->model('schedule_model');
            $info = $this->schedule_model->get_user_events_date_range($this->input->get());
            foreach($info as $item){
                $item->start = $item->begin_date_time;
            }
            if($info != 0){
                print json_encode($info);
            }
        }
        
	public function get_contacts(){
		$this->load->model('contacts_model');
		$contacts = $this->contacts_model->get_contacts($this->session->userdata('id'));
		if($contacts != 0){
			$response['num_contacts'] = count($contacts);
			$response['contacts'] = $contacts;
		}else{
			$response['num_contacts'] = 0;
		}
		print json_encode($response);
	}
	
	/**
	* TODO: figure out an elegant way to segregate forms that need data added to them and those that don't
	*/
	public function get_modal_form(){
		$modal_type = $this->input->post('modal_type');
		$obj_id = $this->input->post('obj_id');
		$view = '/templates/' . $modal_type;
                
		switch($modal_type){
                    case "add_category":
                        $form_content = $this->load->view($view,$data,true);
                        break;
                    case "edit_category":
                        // Get the info for given calendar
                        $calendar = $this->schedule_model->get_calendar($obj_id); 
                        if($calendar != 0){
                            $data = $calendar[0];
                        }
                        // Calendar Managers
                        $managers = $this->schedule_model->get_calendar_managers($obj_id);
                        $managers_list = '<ul><li>No Managers</li></ul>';
                        if($managers != 0){
                            $num_managers = count($managers);
                            $managers_list = '<ul>';
                            foreach($managers as $manager){
                                $user = $this->schedule_model->get_user_by_id($manager->id);
                                $contact_info = $this->contacts_model->get_contact_by_email($user->email);
                                $manager_name = $manager->first_name . " " . $manager->last_name;
                                $managers_list .= '<li><span style="text-align:left;"><a href="" title="Remove Manager" rel="'.$manager->id.'"> X </a></span><span style="padding-left:20px;">' . $manager_name . '</span></li>';
                                $existing_managers .= '<input type="hidden" name="manager_ids[]" value="'.$contact_info->id.'">';
                            }
                            $managers_list .= '</ul>';
                        }else{
                            $num_managers = 0;
                        }

                        $data->calendar_managers = $managers_list;
                       
                        for($i = 1; $i <= 5 - $num_managers; $i++){ // the limit should actually be the 5 minus the number of existing calendar managers
                            $manager_inputs .= '<input type="text" class="calendar_manager" name="calendar_manager[]" size="40">';
                        }
                        $data->manager_inputs = $manager_inputs;
                        $data->existing_managers = $existing_managers;
                        $form_content = $this->load->view($view,$data,true);
                        break;
                    case "add_event":
                        // Get user's calendars
                        $calendars = $this->schedule_model->get_calendars($this->session->userdata('id'));
                        if($calendars != 0){
                            foreach($calendars as $calendar){
                                $user_calendars .= '<input name="category" type="radio" id="'.$calendar->name.'" value="'.$calendar->id.'"><label for="'.$calendar->name.'">'.$calendar->name.'</label><br>';
                            }
                        }
                        // Get reminders
                        $reminder_notifications = array("1hourbefore"=>"1 hour before","1daybefore"=>"1 day before","1weekbefore"=>"1 week before");
                        foreach($reminder_notifications as $key=>$notification){
                                if($key == "none"){
                                        $reminder_notification_list .= '<input type="checkbox" name="reminder_notification[]" id="'.$key.'" value="'.$key.'" checked><label for="'.$key.'">'.$notification.'</label><br/>';
                                }else{
                                        $reminder_notification_list .= '<input type="checkbox" name="reminder_notification[]" id="'.$key.'" value="'.$key.'"><label for="'.$key.'">'.$notification.'</label><br/>';
                                }
                        }       
                        $reminder_notification_list .= '<input type="checkbox" name="reminder_notification_all" id="reminder_notification_all"><label for="reminder_notification_all">All</label><br/>';
                        $data = array("id"=>$obj_id,"reminder_notification_list"=>$reminder_notification_list,"calendars"=>$user_calendars);
                        $form_content = $this->load->view($view,$data,true);
                        break;
                    case "edit_event":
                        break;
                    case "edit_contact":
                        break;
		}      
                
		print_r($form_content);
	}

	/*
	* delete_obj
	* Manages deleting Calendar, Events, Contacts
	*/
	public function delete_obj(){
                $status = 0;
                if($this->input->post('obj_type') == "calendar"){
                    // Delete related events
                    if($this->schedule_model->delete_calendar($this->input->post('obj_id')) == 1){
                        $status = 1;
                    }                    
                }
                
                print json_encode(array("status"=>$status));
	}

        public function update(){
            $status = 0;
            switch($this->input->post('obj_type')){
                case "calendar":
                    $calendar_managers = $this->input->post('calendar_manager');
                    $manager_ids_list = $this->input->post('manager_ids');
                    $id = $this->input->post('id');
                    // Unset certain post variables to keep from passing to model
                    unset($_POST['calendar_manager']);
                    unset($_POST['obj_id']);
                    unset($_POST['obj_type']);
                    unset($_POST['manager_ids']);

                        $this->schedule_model->update_calendar($this->input->post());
                        $status = 1;
                        // At this point application should update the managers list
                        $this->schedule_model->update_calendar_managers(array("id"=>$id,"calendar_managers"=>$manager_ids_list));

                    break;
                case "event":
                    break;
                case "contact":
                    break;
                default:
                    $status = 0;
                    break;
            }
            
            print json_encode(array("status"=>$status));
        }
        
        public function remove_calendar_manager(){ 

            if($this->input->is_ajax_request()){
                $this->schedule_model->delete_calendar_manager($this->input->post());
            }else{
                return json_encode(array("status"=>"invalid request"));
            }
        }
        
        /**
         * Search contacts using given search term
         * Search is performed within currently logged in user's list of contacts
         * @param string $search_term
         */
        public function user_lookup(){
            $search_term = $this->input->get('term');
            $contacts = $this->schedule_model->search_users($search_term, $this->session->userdata('id'));
            if($contacts != 0){
                foreach($contacts as $contact){
                    $full_name = $contact->first_name . ' ' . $contact->last_name;
                    $user = $this->schedule_model->get_user($contact->email);
                    $contacts_array[] = array("label"=>$full_name,"id"=>$contact->id,"account_id"=>$user->id);
                }
                print json_encode($contacts_array);
            }
        
        }
       
}

/* End of file schedule.php */
/* Location: ./application/controller/schedule.php */