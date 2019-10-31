<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DashboardApp extends MY_Controller
{
	function __construct()
	{
        parent::__construct();
        // $this->user = User::validate_login($_POST['username'], $_POST['password']);
		
		// if(!$this->user){
        //     setcookie("lasturl", uri_string());
        //     setcookie("error", 'fuck');
		// }
		
	}
	
	function index()
	{        
        $this->theme_view = false;
        $year = date("Y");
        $this->view_data["selected_year"] = $year;

        if($this->user->userpic != 'no-pic.png'){
            $userimage = base_url()."files/media/".$this->user->userpic;
        }else{
            $userimage = 'https://www.gravatar.com/avatar/96e65d53c7259adb3b2429bfbda631dd?s=40&d=mm&r=g';
        }
        $this->view_data["userimage"] = $userimage;

        $this->view_data["acatsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 3 AND company_id = '.$this->user->company_id);
        $this->view_data["production"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id );
        $this->view_data["production_by_paid"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND YEAR(prem_paid_month) = '.$year );

        $company = Company::find(array('conditions' => 'inactive=0 and id='.$this->user->company_id));
        $this->view_data["companyName"] = $company->name;
        $this->view_data["fullName"] = $this->user->firstname.' '.$this->user->lastname;
        $this->view_data["annualGoal"] = ($company->annual_goal2019)/100;
        // $this->view_data["userdata"] = $this->session;
        // $this->view_data["app"] = true;

        $this->theme_view = 'json';
        // $this->view_data['form_action'] = 'agent/';
        $this->content_view = 'dashboard/dashboardapp';
           
        
        
    }

    function login()
    {
        if($_POST)
		{
			$user = User::validate_login($_POST['username'], $_POST['password']);
            if($user){
                redirect('dashboardapp');
            } else {
                $this->theme_view = 'errorRequest';
                $this->content_view = 'dashboard/dashboardappFail';
            }
        }
    }
    
    function getACAT()
    {

    }

    function logout()
	{
        if($this->user){ 
        $update = User::find($this->user->id); 
            $update->last_active = 0;
            $update->save();
        }
        $this->session->sess_destroy();
		User::logout();
	}

	function error_404(){
 		$this->content_view = 'error/404';
	}

	
}