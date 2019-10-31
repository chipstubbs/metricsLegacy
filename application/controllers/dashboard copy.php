<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->client){
			redirect('cprojects');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) {
				if($value->link == "dashboard"){
					$access = TRUE;
				}
			}
			if(!$access && !empty($this->view_data['menu'][0])){
				redirect($this->view_data['menu'][0]->link);
			}elseif(empty($this->view_data['menu'][0])){
				$this->view_data['error'] = "true";
				$this->session->set_flashdata('message', 'error: You have no access to any modules!');
				redirect('login');
			}

		}else{
			redirect('login');
		}

	}

	function index()
	{

		if($this->user->admin == 1){
		$settings = Setting::first();
		$this->load->helper('curl');
		$object = remote_get_contents('http://fc2.luxsys-apps.com/updates/xml.php?code='.$settings->pc);
		$object = json_decode($object);

		$this->view_data['update'] = FALSE;
			if(isset($object->error)) {
				if($object->error == FALSE && $object->lastupdate > $settings->version){
				$this->view_data['update'] = $object->lastupdate;
				}
			}
		}

		$year = date('Y', time());
		$tax = $this->view_data['core_settings']->tax;
		//$this->load->database();
		$sql = "select invoices.paid_date, ROUND(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount)+(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount))/100*$tax,0) as summary FROM invoices, invoice_has_items where invoices.id = invoice_has_items.invoice_id AND invoices.`status` = 'Paid' AND paid_date between '$year-01-01'
AND '$year-12-31' GROUP BY SUBSTR(invoices.paid_date,1,7)";
		//$query = $this->db->query($sql);

		//$this->view_data["stats"] = $query->result();
$result = Invoice::find_by_sql($sql);
		$this->view_data["stats"] = $result;
		$this->view_data["year"] = $year;
		//Projects
			//open
			$this->view_data["projects_open"] = Project::count(array('conditions' => array('progress < ?', 100)));
			//all
			$this->view_data["projects_all"] = Project::count();
		//invoices
			//open
			$this->view_data["invoices_open"] = Invoice::count(array('conditions' => array('status != ?', 'Paid')));
			//all
			$this->view_data["invoices_all"] = Invoice::count();
		//payments open
		$thismonth = date('m');
		$this->view_data["month"] = date('M');
		$sql = "select invoices.paid_date, ROUND(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount)+(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount))/100*IF(invoices.tax != NULL, invoices.tax, $tax),0) as summary FROM invoices, invoice_has_items where invoices.id = invoice_has_items.invoice_id AND invoices.`status` = 'Paid' AND paid_date between '$year-$thismonth-01'
AND '$year-$thismonth-31' ";
		//$query = $this->db->query($sql);
		$result = Invoice::find_by_sql($sql);
		$this->view_data["payments"] = $result;
		//payments outstanding
		$thismonth = date('m');
		$this->view_data["month"] = date('M');
		$sql = "select invoices.paid_date, ROUND(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount)+(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount))/100*IF(invoices.tax != NULL, invoices.tax, $tax),0) as summary FROM invoices, invoice_has_items where invoices.id = invoice_has_items.invoice_id AND invoices.`status` != 'Paid' ";
		//$query = $this->db->query($sql);
		$result = Invoice::find_by_sql($sql);
		$this->view_data["paymentsoutstanding"] = $result;




		//Events
		$events = array();
		$date = date('Y-m-d', time());
		$eventcount = 0;
		foreach ($this->view_data['menu'] as $key => $value) {
				if($value->link == "invoices"){
					$sql = 'SELECT * FROM invoices WHERE status != "Paid" AND due_date < "'.$date.'" ORDER BY due_date';
					$res = Invoice::find_by_sql($sql);
					//$res = $res->result();
					foreach ($res as $key2 => $value2) {
						$eventline = str_replace("{invoice_number}", '<a href="'.base_url().'invoices/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_invoice_overdue'));
						$events[$value2->due_date.".".$value2->id] = $eventline;
						$eventcount = $eventcount+1;
					}

				}
				if($value->link == "projects"){
					$sql = 'SELECT * FROM projects WHERE progress != "100" AND end < "'.$date.'" ORDER BY end';
					$res = Project::find_by_sql($sql);
					//$res = $res->result();

					foreach ($res as $key2 => $value2) {
						if($this->user->admin == 0){
							$sql = "SELECT id FROM `project_has_workers` WHERE project_id = ".$value->id." AND user_id = ".$this->user->id;
							$res = Project::find_by_sql($sql);
							//$res = $query;
							if($res){
								$eventline = str_replace("{project_number}", '<a href="'.base_url().'projects/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_project_overdue'));
								$events[$value2->end.".".$value2->id] = $eventline;
								$eventcount = $eventcount+1;
							}
						}else{
							$eventline = str_replace("{project_number}", '<a href="'.base_url().'projects/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_project_overdue'));
							$events[$value2->end.".".$value2->id] = $eventline;
							$eventcount = $eventcount+1;
						}
					}
				}
				if($value->link == "subscriptions"){
					$sql = 'SELECT * FROM subscriptions WHERE status != "Inactive" AND end_date > "'.$date.'" AND next_payment <= "'.$date.'" ORDER BY next_payment';
					$res = Subscription::find_by_sql($sql);
					//$res = $res->result();
					foreach ($res as $key2 => $value2) {
						$eventline = str_replace("{subscription_number}", '<a href="'.base_url().'subscriptions/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_subscription_new_invoice'));
						$events[$value2->next_payment.".".$value2->id] = $eventline;
						$eventcount = $eventcount+1;
					}

				}
				if ($value->link == "messages") {
					$sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.message, privatemessages.`time`, privatemessages.`recipient`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u  , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
							FROM privatemessages
							LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
							LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender
							GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'"AND privatemessages.status != "deleted" ORDER BY privatemessages.`time` DESC LIMIT 6';
					$query = Privatemessage::find_by_sql($sql);
					$this->view_data["message"] = array_filter($query);
				}
				if ($value->link == "projects") {
					$sql = 'SELECT * FROM project_has_tasks WHERE status != "done" AND user_id = "'.$this->user->id.'" ORDER BY project_id';
					$taskquery = Project::find_by_sql($sql);
					$this->view_data["tasks"] = $taskquery;
				}

		}
		krsort($events);
		$this->view_data["events"] = $events;
		$this->view_data["eventcount"] = $eventcount;


		$this->load->helper('custom');
		$this->load->helper('format');
		// Set Year
				$productionpage = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 1 AND company_id = '.$this->user->company_id);

				if($_POST){
					$this->view_data['form_action'] = 'dashboard';
					$this->session->set_userdata('year_to_view', $_POST['year']);
				}

				if ( $this->session->userdata("year_to_view") !== false ) {
					//$this->session->set_userdata('year_to_view', date("Y"));
					$this->view_data['year_to_view'] = $this->session->userdata('year_to_view');
				}
				elseif( $this->session->userdata("year_to_view") == false ) {
					$this->session->set_userdata('year_to_view', date("Y"));
				}



		// $this->view_data['hot_prospect'] = Client::find_by_sql('SELECT * FROM clients WHERE inactive = 0 AND hot_prospect = 1 AND company_id = '.$this->user->company_id.' AND YEAR(STR_TO_DATE(last_contact, "%m/%d/%Y")) = '.$this->session->userdata("year_to_view") );
		$this->view_data['hot_prospect'] = Client::all(array('conditions' => array('inactive = 0 AND hot_prospect = 1 AND company_id = ?',  $this->user->company_id)));
		// $this->view_data['hot_client'] = Client::find_by_sql('SELECT * FROM clients WHERE inactive = 0 AND hot_client = 1 AND company_id = '.$this->user->company_id.' AND YEAR(STR_TO_DATE(c_last_contact, "%m/%d/%Y")) = '.$this->session->userdata("year_to_view") );
		$this->view_data['hot_client'] = Client::all(array('conditions' => array('inactive = 0 AND hot_client = 1 and company_id = ?', $this->user->company_id)));


		$this->view_data["projects"] = Project::all(array('conditions' => 'company_id ='.$this->user->company_id));
		$this->view_data["clients"] = Client::all(array('conditions' => 'inactive = 0 and company_id ='.$this->user->company_id));
		$this->view_data["production"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND YEAR(production_submitted) = '.$this->session->userdata("year_to_view") ); 
		//YEAR(app_date_received)

		$this->view_data["acatsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 3 AND company_id = '.$this->user->company_id);
		$this->view_data["hotprospectsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 4 AND company_id = '.$this->user->company_id);
		$this->view_data["hotclientsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 5 AND company_id = '.$this->user->company_id);
		$this->view_data["aumsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 9 AND company_id = '.$this->user->company_id);
		$this->view_data["annuitysheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 2 AND company_id = '.$this->user->company_id);
		$this->view_data["lifesheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 7 AND company_id = '.$this->user->company_id);
		$this->view_data["othersheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 8 AND company_id = '.$this->user->company_id);
		$this->view_data["company"] = Company::all(array('conditions' => 'inactive=0 and id='.$this->user->company_id));

		$this->view_data['project'] = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
		$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));


		$this->content_view = 'dashboard/dashboard';
	}
	function filter($year = FALSE){

		$this->view_data['update'] = FALSE;



		if(!$year){ $year = date('Y', time()); }
		$tax = $this->view_data['core_settings']->tax;
		$this->load->database();
		$sql = "select invoices.paid_date, ROUND(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount)+(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount))/100*$tax,0) as summary FROM invoices, invoice_has_items where invoices.id = invoice_has_items.invoice_id AND invoices.`status` = 'Paid' AND paid_date between '$year-01-01'
AND '$year-12-31' GROUP BY SUBSTR(invoices.paid_date,1,7)";
		$query = $this->db->query($sql);
		$this->view_data["stats"] = $query->result();
		$this->view_data["year"] = $year;
		//Projects
			//open
			$this->view_data["projects_open"] = Project::count(array('conditions' => array('progress < ?', 100)));
			//all
			$this->view_data["projects_all"] = Project::count();
		//invoices
			//open
			$this->view_data["invoices_open"] = Invoice::count(array('conditions' => array('status != ?', 'Paid')));
			//all
			$this->view_data["invoices_all"] = Invoice::count();
		//payments open
		$thismonth = date('m');
		$this->view_data["month"] = date('M');
		$sql = "select invoices.paid_date, ROUND(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount)+(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount))/100*$tax,0) as summary FROM invoices, invoice_has_items where invoices.id = invoice_has_items.invoice_id AND invoices.`status` = 'Paid' AND paid_date between '$year-$thismonth-01'
AND '$year-$thismonth-31' ";
		$query = $this->db->query($sql);
		$this->view_data["payments"] = $query->result();
		//payments outstanding
		$thismonth = date('m');
		$this->view_data["month"] = date('M');
		$sql = "select invoices.paid_date, ROUND(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount)+(sum(invoice_has_items.value*invoice_has_items.amount)-if(SUBSTR(invoices.discount,-1)='%',(sum(invoice_has_items.value*invoice_has_items.amount)/100*(SUBSTRING(invoices.discount, 1, CHAR_LENGTH(invoices.discount) - 1))), invoices.discount))/100*$tax,0) as summary FROM invoices, invoice_has_items where invoices.id = invoice_has_items.invoice_id AND invoices.`status` != 'Paid' ";
		$query = $this->db->query($sql);
		$this->view_data["paymentsoutstanding"] = $query->result();




		//Events
		$events = array();
		$date = date('Y-m-d', time());
		$eventcount = 0;
		foreach ($this->view_data['menu'] as $key => $value) {
				if($value->link == "invoices"){
					$sql = 'SELECT * FROM invoices WHERE status != "Paid" AND due_date < "'.$date.'" ORDER BY due_date';
					$res = $this->db->query($sql);
					$res = $res->result();
					foreach ($res as $key2 => $value2) {
						$eventline = str_replace("{invoice_number}", '<a href="'.base_url().'invoices/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_invoice_overdue'));
						$events[$value2->due_date.".".$value2->id] = $eventline;
						$eventcount = $eventcount+1;
					}

				}
				if($value->link == "projects"){
					$sql = 'SELECT * FROM projects WHERE progress != "100" AND end < "'.$date.'" ORDER BY end';
					$res = $this->db->query($sql);
					$res = $res->result();

					foreach ($res as $key2 => $value2) {
						if($this->user->admin == 0){
							$sql = "SELECT id FROM `project_has_workers` WHERE project_id = ".$value->id." AND user_id = ".$this->user->id;
							$query = $this->db->query($sql);
							$res = $query->result();
							if($res){
								$eventline = str_replace("{project_number}", '<a href="'.base_url().'projects/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_project_overdue'));
								$events[$value2->end.".".$value2->id] = $eventline;
								$eventcount = $eventcount+1;
							}
						}else{
							$eventline = str_replace("{project_number}", '<a href="'.base_url().'projects/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_project_overdue'));
							$events[$value2->end.".".$value2->id] = $eventline;
							$eventcount = $eventcount+1;
						}
					}
				}
				if($value->link == "subscriptions"){
					$sql = 'SELECT * FROM subscriptions WHERE status != "Inactive" AND end_date > "'.$date.'" AND next_payment <= "'.$date.'" ORDER BY next_payment';
					$res = $this->db->query($sql);
					$res = $res->result();
					foreach ($res as $key2 => $value2) {
						$eventline = str_replace("{subscription_number}", '<a href="'.base_url().'subscriptions/view/'.$value2->id.'">#'.$value2->reference.'</a>', $this->lang->line('event_subscription_new_invoice'));
						$events[$value2->next_payment.".".$value2->id] = $eventline;
						$eventcount = $eventcount+1;
					}

				}
				if ($value->link == "messages") {
					$sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.message, privatemessages.`time`, privatemessages.`recipient`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u  , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
							FROM privatemessages
							LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
							LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender
							GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'"AND privatemessages.status != "deleted" ORDER BY privatemessages.`time` DESC LIMIT 6';
					$query = $this->db->query($sql);
					$this->view_data["message"] = array_filter($query->result());
				}
				if ($value->link == "projects") {
					$sql = 'SELECT * FROM project_has_tasks WHERE status != "done" AND user_id = "'.$this->user->id.'" ORDER BY project_id';
					$taskquery = $this->db->query($sql);
					$this->view_data["tasks"] = $taskquery->result();
				}

		}
		krsort($events);
		$this->view_data["events"] = $events;
		$this->view_data["eventcount"] = $eventcount;



		$this->content_view = 'dashboard/dashboard';
	}
}
