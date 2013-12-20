<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
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
            //print_r($this->input->get(),true);
            //print json_encode(array("status"=>"hi there"));
            //print json_encode(array("day"=>"what","myid"=>$this->session->userdata('id')));
            // Get the events associated with logged in user
            // within the context of the given month given
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
		$data = array("id"=>$obj_id);
		$form_content = $this->load->view($view,$data,true);
		/**
		switch($modal_type){
			case "add_category":
				$form_content = $this->load->view($view,'',true);
				break;
			case "add_contact":
				$form_content = $this->load->view($view,'',true);
				break;//
		}*/
		print_r($form_content);
	}

	/*
	* delete_obj
	* Manages deleting Calendar, Events, Contacts
	*/
	public function delete_obj(){
		// If obj is Calendar, must delete related events first
                $status = 0;
		if($this->schedule_model->delete_calendar($this->input->post('obj_id')) == 1){
                    $status = 1;
                }
                
                print json_encode(array("status"=>$status));
	}
}

/* End of file schedule.php */
/* Location: ./application/controller/schedule.php */