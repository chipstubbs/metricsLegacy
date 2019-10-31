<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->client){
			redirect('cprojects');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) {
				if($value->link == "clients"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}else{
			redirect('login');
		}
		$this->load->database();
		$this->load->library('csvimport');
	}
	function index()
	{
		$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));
		$this->load->helper('format');
		if ($this->user->admin == '1'){
			$this->content_view = 'clients/all';
		}
		else {
			redirect('clients/view/'.$this->user->company_id);
		}

	}
	function create($company_id = FALSE)
	{
		if($_POST){
			$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_width'] = '180';
					$config['max_height'] = '180';

					$this->load->library('upload', $config);

					if ( $this->upload->do_upload())
						{
							$data = array('upload_data' => $this->upload->data());

							$_POST['userpic'] = $data['upload_data']['file_name'];
						}else{
							$error = $this->upload->display_errors('', ' ');
							if($error != "You did not select a file to upload. "){
								$this->session->set_flashdata('message', 'error:'.$error);
								redirect('clients');
							}
						}

			unset($_POST['send']);
			unset($_POST['userfile']);
			unset($_POST['file-name']);
			if(isset($_POST["access"])){ $_POST["access"] = implode(",", $_POST["access"]); }else{unset($_POST["access"]);}
			$_POST = array_map('htmlspecialchars', $_POST);
			$_POST["company_id"] = $company_id;
			$_POST['phone'] = preg_replace( '/\D/', '', $_POST['phone'] );
			//Update Checkbox Posts
				$_POST['hot_client'] = (! empty($_POST['hot_client'])) ? 1 : 0;
				$_POST['hot_prospect'] = (! empty($_POST['hot_prospect'])) ? 1 : 0;
				$_POST['sched_appt_check'] = (! empty($_POST['sched_appt_check'])) ? 1 : 0;
				$_POST['kept_appt'] = (! empty($_POST['kept_appt'])) ? 1 : 0;
				$_POST['has_assets'] = (! empty($_POST['has_assets'])) ? 1 : 0;
				$_POST['life_submitted'] = (! empty($_POST['life_submitted'])) ? 1 : 0;
				$_POST['annuity_app'] = (! empty($_POST['annuity_app'])) ? 1 : 0;
				$_POST['annuity_paid'] = (! empty($_POST['annuity_paid'])) ? 1 : 0;
				$_POST['acat'] = (! empty($_POST['acat'])) ? 1 : 0;
				$_POST['aum'] = (! empty($_POST['aum'])) ? 1 : 0;
				$_POST['other'] = (! empty($_POST['other'])) ? 1 : 0;
				$_POST['referral'] = (! empty($_POST['referral'])) ? 1 : 0;
				$_POST['client_prospect'] = (! empty($_POST['client_prospect'])) ? 1 : 0;
			//Strip commas from numbers
//				function stripNums($n) {
//					return $n = strtr($n, array('.' => '' , ',' => ''));
//				}
//				$_POST = array_map('stripNums', $_POST);
            foreach($_POST as $key => $value){
                if($key !== 'email'){
                    $_POST[$key] = strtr($value, array('.' => '' , ',' => ''));
                }
            }
			$client = Client::create($_POST);
       		if(!$client){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_client_add_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_client_add_success'));
       		$company = Company::find($company_id);
       			if(!isset($company->client->id)){
       				$client = Client::last();
       				$company->update_attributes(array('client_id' => $client->id));
       			}
       		}
			$production_page = Project::find('all', array('conditions' => array('name = 1 AND company_id = ?', $this->user->company_id)));
			//Session Variables
				$s_life = $this->session->userdata('s_life');
				$s_acat = $this->session->userdata('s_acat');
				$s_annuity = $this->session->userdata('s_annuity');
				$s_other = $this->session->userdata('s_other');
				$s_aum   = $this->session->userdata('s_aum');

				$this->session->set_userdata('last_client', $client->id);

				if($s_life == 0 && $_POST['life_submitted'] == 1){
					$this->session->set_userdata('production_type', 'life');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_acat == 0 && $_POST['acat'] == 1){
					$this->session->set_userdata('production_type', 'acat');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_aum == 0 && $_POST['aum'] == 1){
					$this->session->set_userdata('production_type', 'aum');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_annuity == 0 && $_POST['annuity_app'] == 1){
					$this->session->set_userdata('production_type', 'annuity');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_other == 0 && $_POST['other'] == 1){
					$this->session->set_userdata('production_type', 'other');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				else {
					redirect('clients/view/'.$client->company->id);
				}
			//End Session Variables
			// redirect('clients/view/'.$company_id);

		}else
		{
			// $this->view_data['clients'] = Client::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['client'] = Client::find($id);
			$this->view_data['modules'] = Module::find('all', array('order' => 'sort asc', 'conditions' => array('type = ?', 'client')));
			$this->view_data['next_reference'] = Client::last();
			$this->load->helper('custom');
			$this->load->helper('format');
			$this->theme_view = 'modal';
			$this->view_data['project'] = Project::find('all', array('order' => 'event_date desc', 'conditions' => array('company_id = ?', $this->user->company_id)));
			$this->view_data['uri'] = $this->uri->segment(4,0);
			$this->view_data['title'] = "Add New Client / Prospect";
			$this->view_data['form_action'] = 'clients/create/'.$company_id;
			$this->content_view = 'clients/_clients';

		}
	}
	function update($id = FALSE, $getview = FALSE)
	{
		if($_POST){
					$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_width'] = '180';
					$config['max_height'] = '180';

					$this->load->library('upload', $config);

					if ( $this->upload->do_upload())
						{
							$data = array('upload_data' => $this->upload->data());

							$_POST['userpic'] = $data['upload_data']['file_name'];
						}else{
							$error = $this->upload->display_errors('', ' ');
							if($error != "You did not select a file to upload. "){
								$this->session->set_flashdata('message', 'error:'.$error);
								redirect('clients');
							}
						}

			unset($_POST['send']);
			unset($_POST['userfile']);
			unset($_POST['file-name']);
			if(empty($_POST["password"])){unset($_POST['password']);}
			if(!empty($_POST["access"])){$_POST["access"] = implode(",", $_POST["access"]);}
			$id = $_POST['id'];
			if(isset($_POST['view'])){
				$view = $_POST['view'];
				unset($_POST['view']);
			}
			$_POST = array_map('htmlspecialchars', $_POST);
			//$_POST['phone'] = preg_replace( '/\D/', '', $_POST['phone'] );
			$client = Client::find($id);
			//Update Checkbox Posts
				$_POST['hot_client'] = (! empty($_POST['hot_client'])) ? 1 : 0;
				$_POST['hot_prospect'] = (! empty($_POST['hot_prospect'])) ? 1 : 0;
				$_POST['sched_appt_check'] = (! empty($_POST['sched_appt_check'])) ? 1 : 0;
				$_POST['kept_appt'] = (! empty($_POST['kept_appt'])) ? 1 : 0;
				$_POST['has_assets'] = (! empty($_POST['has_assets'])) ? 1 : 0;
				$_POST['life_submitted'] = (! empty($_POST['life_submitted'])) ? 1 : 0;
				$_POST['annuity_app'] = (! empty($_POST['annuity_app'])) ? 1 : 0;
				$_POST['annuity_paid'] = (! empty($_POST['annuity_paid'])) ? 1 : 0;
				$_POST['acat'] = (! empty($_POST['acat'])) ? 1 : 0;
				$_POST['aum'] = (! empty($_POST['aum'])) ? 1 : 0;
				$_POST['other'] = (! empty($_POST['other'])) ? 1 : 0;
				$_POST['referral'] = (! empty($_POST['referral'])) ? 1 : 0;
				$_POST['client_prospect'] = (! empty($_POST['client_prospect'])) ? 1 : 0;
			//Strip commas from numbers
//				function stripNums($n) {
////					// return $n = strtr($n, array('.' => '' , ',' => '', '-' => ''));
//					return $n = strtr($n, array('.' => '' , ',' => ''));
//				}
//				$_POST = array_map('stripNums', $_POST);

            //Strip commas from numbers
				foreach($_POST as $key => $value){
                    if($key !== 'email'){
                        $_POST[$key] = strtr($value, array('.' => '' , ',' => ''));
                    }
                }
				//Currently Strips from all
				// $_POST['life_amount'] = strtr($_POST['life_amount'], array('.' => '' , ',' => ''));
				// $_POST['aum_amount'] = strtr($_POST['aum_amount'], array('.' => '' , ',' => ''));
				// $_POST['acat_xfered'] = strtr($_POST['acat_xfered'], array('.' => '' , ',' => ''));
				// $_POST['annuity_prem_app'] = strtr($_POST['annuity_prem_app'], array('.' => '' , ',' => ''));
				// $_POST['annuity_paid_prem'] = strtr($_POST['annuity_paid_prem'], array('.' => '' , ',' => ''));
				// $_POST['other_deposit'] = strtr($_POST['other_deposit'], array('.' => '' , ',' => ''));
				// $_POST['client_opportunity'] = strtr($_POST['client_opportunity'], array('.' => '' , ',' => ''));
				// $_POST['prospect_opportunity'] = strtr($_POST['prospect_opportunity'], array('.' => '' , ',' => ''));
				// $_POST['c_probable_acat_size'] = strtr($_POST['c_probable_acat_size'], array('.' => '' , ',' => ''));
				// $_POST['p_probable_acat_size'] = strtr($_POST['p_probable_acat_size'], array('.' => '' , ',' => ''));
			$client->update_attributes($_POST);
       		if(!$client){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_client_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_client_success'));}

			$this->load->library('session');
			$production_page = Project::find('all', array('conditions' => array('name = 1 AND company_id = ?', $this->user->company_id)));
			//Session Variables
				$s_life = $this->session->userdata('s_life');
				$s_acat = $this->session->userdata('s_acat');
				$s_annuity = $this->session->userdata('s_annuity');
				$s_other = $this->session->userdata('s_other');
				$s_aum   = $this->session->userdata('s_aum');

				$this->session->set_userdata('last_client', $client->id);

				if($s_life == 0 && $_POST['life_submitted'] == 1){
					$this->session->set_userdata('production_type', 'life');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_acat == 0 && $_POST['acat'] == 1){
					$this->session->set_userdata('production_type', 'acat');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_aum == 0 && $_POST['aum'] == 1){
					$this->session->set_userdata('production_type', 'aum');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_annuity == 0 && $_POST['annuity_app'] == 1){
					$this->session->set_userdata('production_type', 'annuity');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				elseif($s_other == 0 && $_POST['other'] == 1){
					$this->session->set_userdata('production_type', 'other');
					$this->session->set_userdata('refer_from', 'clientpage');
					redirect('projects/productionentry/'.$production_page[0]->id.'/add');
				}
				else {
					//redirect('clients/view/'.$client->company->id);
					redirect($this->session->userdata('refer_from'));
				}
			//End Session Variables
		}else
		{
			$this->view_data['client'] = Client::find($id);
			// For dropdown menu of events within specific company
			$this->view_data['project'] = Project::find('all', array('order' => 'event_date desc', 'conditions' => array('company_id = ?', $this->user->company_id)));
			$this->load->helper('custom');
			$this->view_data['modules'] = Module::find('all', array('order' => 'sort asc', 'conditions' => array('type = ?', 'client')));
			if($getview == "view"){$this->view_data['view'] = "true";}
			$this->theme_view = 'modal';
			$this->load->helper('format');
			$this->view_data['title'] = 'Edit Client / Prospect';
			$this->view_data['uri'] = $this->uri->segment(4,0);
			$this->view_data['form_action'] = 'clients/update';
			$this->content_view = 'clients/_clients';
		}
	}
	function appointmentCheck($id = FALSE)
	{
		// if($_POST){
			// unset($_POST['send']);
			// $_POST = array_map('htmlspecialchars', $_POST);
			$daclient = Client::find($id);
			$_POST['kept_appt'] = 1;
			$daclient->update_attributes($_POST);
		// }

		if(!$id){$this->session->set_flashdata('message', 'error: Didn\'t save appointment kept.');}
		else{$this->session->set_flashdata('message', 'success: Appointment kept!');}
		redirect($this->session->userdata('refer_from'));
	}
	function removeScheduledAppointment($id = FALSE)
	{
		$theclient = Client::find($id);
		$_POST['appt_checked_no'] = 1;
		$theclient->update_attributes($_POST);

		if(!$id){$this->session->set_flashdata('message', 'error: Appointment could not be turned off.');}
		else{$this->session->set_flashdata('message', 'success: Appointment no longer scheduled.');}
		redirect($this->session->userdata('refer_from'));
	}
	function notes($id = FALSE)
	{
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$project = Company::find($id);
			$project->update_attributes($_POST);
		}
		$this->theme_view = 'ajax';
	}
	function company($condition = FALSE, $id = FALSE)
	{
		switch ($condition) {
			case 'create':
				if($_POST){
					unset($_POST['send']);
					$_POST = array_map('htmlspecialchars', $_POST);
					$company = Company::create($_POST);
					$companyid = Company::last();
					$new_company_reference = $_POST['reference']+1;
					$company_reference = Setting::first();
					//Strip commas from numbers
//						function stripNums($n) {
//							return $n = strtr($n, array('.' => '' , ',' => '', '-' => ''));
//						}
//						$_POST = array_map('stripNums', $_POST);
                    $dont_strip = array("website","email");
                    foreach($_POST as $key => $value){
                        if(!in_array($key,$dont_strip)){
                            $_POST[$key] = strtr($value, array('.' => '' , ',' => ''));
                        }
                    }
					$company_reference->update_attributes(array('company_reference' => $new_company_reference));
					if(!$company){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_company_add_error'));}
					else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_company_add_success'));}
					redirect('clients/view/'.$companyid->id);
				}else
				{
					//If admin unset company
					if($this->user->admin == 1){ unset($this->view_data["company"]); }
					$this->view_data['clients'] = Company::find('all',array('conditions' => array('inactive=?','0')));
					$this->view_data['next_reference'] = Company::last();
					$this->theme_view = 'modal';
					$this->view_data['title'] = $this->lang->line('application_add_new_company');
					$this->view_data['form_action'] = 'clients/company/create';
					$this->content_view = 'clients/_company';
				}
			break;
			case 'update':
				if($_POST){
					unset($_POST['send']);
					$id = $_POST['id'];
					if(isset($_POST['view'])){
						$view = $_POST['view'];
						unset($_POST['view']);
					}
					$_POST = array_map('htmlspecialchars', $_POST);
					$company = Company::find($id);
					//Strip commas from numbers


                    $dont_strip = array("website","email");
                    foreach($_POST as $key => $value){
                        if(!in_array($key,$dont_strip)){
                            $_POST[$key] = strtr($value, array('.' => '' , ',' => ''));
                        }
                    }
					$company->update_attributes($_POST);
					if(!$company){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_company_error'));}
					else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_company_success'));}
					// redirect('clients/view/'.$id);
					$refer_from = $this->session->userdata('refer_from');
					redirect($refer_from);

				}else
				{
					$this->view_data['company'] = Company::find($id);
					$this->theme_view = 'modal';
					$this->view_data['title'] = $this->lang->line('application_edit_company');
					$this->view_data['form_action'] = 'clients/company/update';
					$this->content_view = 'clients/_company';
				}
			break;
			case 'delete':
				$company = Company::find($id);
				$company->inactive = '1';
				$company->save();
				foreach ($company->clients as $value) {
				$client = Client::find($value->id);
				$client->inactive = '1';
				$client->save();
				}
				$this->content_view = 'clients/all';
				if(!$company){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_company_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_company_success'));}
					redirect('clients');
			break;

		}

	}
	function delete($id = FALSE)
	{
		$client = Client::find($id);
		$client->inactive = '1';
		$client->save();
		$this->content_view = 'clients/all';
		if(!$client){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_client_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_client_success'));}
			redirect('dashboard');
	}
	function view($id = FALSE)
	{
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'clients',

				 		);
		$this->load->helper('custom');
		$this->load->helper('format');
		$this->view_data['camefrom'] = $this->session->userdata('comingfrom');
		$this->view_data["projects"] = Project::all(array('conditions' => 'company_id ='.$this->user->company_id));
		$this->view_data['company'] = Company::find($id);
		$this->view_data["clients"] = Client::all(array('conditions' => 'inactive = 0 and company_id ='.$this->user->company_id));
		$this->view_data["production"] = Production::all(array('conditions' => 'company_id ='.$this->user->company_id));



		$this->content_view = 'clients/view';
	}

	function importcsv() {

        $this->view_data['error'] = '';    //initialize image upload error array to empty

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';//'text/plain|text/csv|csv|text/comma-separated-values|application/csv';
        $config['max_size'] = '1000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            // $data['error'] = $this->upload->display_errors();
            // $this->load->view('csvindex', $data);
            $this->content_view = 'clients/csvindex';
			$file_path = './uploads/'.$file_data['file_name'];
			$this->view_data['path'] = $file_path;
            $this->view_data['error'] = $this->upload->display_errors();
        } else {
            $file_data = $this->upload->data();
            $file_path = './uploads/'.$file_data['file_name'];

			/*
			** NEW PARSER **
			**
			*/
			ini_set("auto_detect_line_endings", true);
			$handle = fopen($file_path, "r");
			if ($handle) {
				// if(!$handle){echo 'failed to open';}
				$i = 1;  // for test output
				$column_headers = array();
				$person_array = array();
				$email_array = array();
				$firstname_array = array();
				$lastname_array = array();
				$final_array['email'] = '';

				while (($buffer = fgets($handle)) !== false) {
					$fields = explode(",", $buffer);  // assuming that fields are separated with semicolons

					$j = 1;

					// Test output
					// echo "Line $i: ";
					// This is just for show, you can use a regular for loop instead
					foreach ($fields as $key => $field) {
						// echo "Field ".$j.": ".$key.'=>'.$field.' ';
						// echo $key.'=>'.$field.' ';
						if($i == 1)
						{
							$column_headers[$key] = trim($field);
						}
						else if($i > 1)
						{

							// array_push($person_array[$column_headers[$key]], $field);

							if (strpos($column_headers[$key],'fname')  !== false || strpos($column_headers[$key],'first name')  !== false || strpos($column_headers[$key],'First Name')  !== false) {
								$person_array[$i]['firstname'] = trim($field);
							}
							elseif (strpos($column_headers[$key],'lname')  !== false && $column_headers[$key] != 'fullname' || strpos($column_headers[$key],'last name')  !== false || strpos($column_headers[$key],'Last Name')  !== false) {
								$person_array[$i]['lastname'] = trim($field);
							}
							elseif (strpos($column_headers[$key],'email')  !== false || strpos($column_headers[$key],'Email')  !== false) {
								$person_array[$i]['email'] = trim($field);
							}
							elseif (strpos($column_headers[$key],'@')  !== false) {
								$this->view_data['error'] = 'No Header Columns';
							}
							else {
								$person_array[$i][$column_headers[$key]] = trim($field);
							}
							// if (strpos($column_headers[$key],'email') !== false) {
							// 	//array_push($email_array, $field);// Instead of pushing to array, push to database
							// 	// $final_array['email'] = $field;
							// }
							// if (strpos($column_headers[$key],'firstname')  !== false || strpos($column_headers[$key],'fname')  !== false) {
							// 	array_push($firstname_array, $field); // Instead of pushing to array, push to database
							// 	// $final_array['firstname'] = $field;
							// }
							// if (strpos($column_headers[$key],'lastname')  !== false || strpos($column_headers[$key],'lname')  !== false) {
							// 	array_push($lastname_array, $field); // Instead of pushing to array, push to database
							// 	// $final_array['lastname'] = $field;
							// }
						}

						$j++;
					}


					$i++;
					// echo "\n";
				}

				fclose($handle);
			/*
			** END NEW PARSER **
			**
			*/

				foreach ($person_array as $row) {
					$insert_data[] = array(
	                        'firstname'=>$row['firstname'],
	                        'lastname'=>$row['lastname'],
	                        'email'=>$row['email'],
							'company_id'=>$this->user->company_id
                    );
				}
				$this->view_data['wut'] = $insert_data;
				if ($insert_data[0]['firstname'] != NULL || $insert_data[0]['lastname'] != NULL || $insert_data[0]['email'] != NULL) {
					//Client::insert_csv($insert_data);
					$this->db->insert_batch('clients', $insert_data);
	                redirect(base_url().'clients/view/'.$this->user->company_id);
				}
				else {
					$this->view_data['error'] = '<span class="db-error">Your CSV file must have these column headers:<ul><li>firstname or fname or first name</li><li>lastname or lname or last name</li><li>email or Email.</li></ul></span>';
					$this->content_view = 'clients/csvindex';
				}

            } else {
				// $data['error'] = "Error occured";
                // $this->load->view('csvindex', $data);
                $this->content_view = 'clients/csvindex';
				$this->view_data['wut'] = $fields;
                $this->view_data['error'] = '';
			}

        }

    }

	function credentials($id = FALSE, $email = FALSE)
	{
		if($email){
			$this->load->helper('file');
			$client = Client::find($id);
			$setting = Setting::first();
			$this->email->from($setting->email, $setting->company);
			$this->email->to($client->email);
			$this->email->subject($setting->credentials_mail_subject);
			$this->load->library('parser');
			$parse_data = array(
            					'client_contact' => $client->firstname.' '.$client->lastname,
            					'client_link' => $setting->domain,
            					'company' => $setting->company,
            					'username' => $client->email,
            					'password' => $client->password,
            					'logo' => '<img src="'.base_url().''.$setting->logo.'" alt="'.$setting->company.'"/>',
            					'invoice_logo' => '<img src="'.base_url().''.$setting->invoice_logo.'" alt="'.$setting->company.'"/>'
            					);

			$message = read_file('./application/views/'.$setting->template.'/templates/email_credentials.html');
  			$message = $this->parser->parse_string($message, $parse_data);
			$this->email->message($message);
			if($this->email->send()){$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_send_login_details_success'));}
       		else{$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_send_login_details_error'));}
			redirect('clients/view/'.$client->company_id);

		} else {
		$this->view_data['client'] = Client::find($id);
		$this->theme_view = 'modal';
		$this->view_data['title'] = $this->lang->line('application_login_details');
		$this->view_data['form_action'] = 'clients/credentials';
		$this->content_view = 'clients/_credentials';
		}
	}
}
