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
		$this->load->helper('url');
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
		// Set Year on login
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
		$allClients = Client::all(array('conditions' => 'inactive = 0 and company_id ='.$this->user->company_id));
		$this->view_data["clients"] = $allClients;
		// $this->view_data["production"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND YEAR(production_submitted) = '.$this->session->userdata("year_to_view") );

		$this->view_data['selected_year'] = $this->session->userdata("year_to_view");

		$this->view_data["production"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id );

		$this->view_data["production_by_received"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND YEAR(app_date_received) = '.$this->session->userdata("year_to_view") );

		$this->view_data["production_by_submitted"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND YEAR(production_submitted) = '.$this->session->userdata("year_to_view") );

		$this->view_data["production_by_paid"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND YEAR(prem_paid_month) = '.$this->session->userdata("year_to_view") );
		//YEAR(app_date_received)
		$this->view_data["acat_complete_past_2017"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND production.production_type = "acat" AND YEAR(prem_paid_month) >= 2017 ' );

		$this->view_data["acatsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 3 AND company_id = '.$this->user->company_id);
		$this->view_data["hotprospectsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 4 AND company_id = '.$this->user->company_id);
		$this->view_data["hotclientsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 5 AND company_id = '.$this->user->company_id);
		$this->view_data["aumsheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 9 AND company_id = '.$this->user->company_id);
		$this->view_data["annuitysheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 2 AND company_id = '.$this->user->company_id);
		$this->view_data["lifesheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 7 AND company_id = '.$this->user->company_id);
		$this->view_data["othersheet"] = Project::find_by_sql('SELECT projects.`id` FROM projects WHERE projects.name = 8 AND company_id = '.$this->user->company_id);
		$this->view_data["company"] = Company::find(array('conditions' => 'inactive=0 and id='.$this->user->company_id));
		
		$allcompanies = Company::find('all',array('conditions' => 'inactive=0'));
		foreach ($allcompanies as $ak => $vals){ $acs[] = $vals->name;}
		$this->view_data["allcompany"] = $acs;

		$this->view_data['project'] = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
		
		$this->view_data['users'] = User::all(array('conditions' => array('status != ?', 'deleted')));

		
		if ( isset($_POST['updater']) ){
			$this->apiGet();
			// 	$allClients = Client::all(array('conditions' => 'inactive = 0 and company_id ='.$this->user->company_id));
			// $companies = Company::find('all',array('conditions' => array('inactive=?','0')));
			// 		$allProjects = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
			// 		$this->view_data['user'] = $this->user;
			// 		$this->view_data['last_sync'] = $this->user->last_sync;
					
			// 		$this->load->library('PHPRequests');
			// 		$this->load->helper('custom');
						
			// 		$sesh = new Requests_Session('https://app.leadjig.com/api/v1');
			// 		$sesh->headers['authentication-token'] = 'iyGjPbPRfV4qWEKhdBkaZbGmVWH4N2VrzS8VGS5fBQqPDCKt';
			// 		$sesh->useragent = 'Metrics';

			// 		$userResponse  = $sesh->get('https://app.leadjig.com/api/v1/users');
					
			// 		//Decode JSON
			// 		$obj = json_decode($userResponse->body);

			// 		//Get company Name
			// 		foreach ($companies as $k => $val) {
			// 			($this->user->company_id === $val->id) ? $companyName =  $val->name : '';
			// 		}
			// 		$metricsName = $this->user->firstname." ".$this->user->lastname;
			// 		$usersUID = $this->user->uniq_id;

			// 		//Loop through users and put their Events into an Array $ljEvents
			// 		foreach ($obj->users as $user) 
			// 		{
			// 			$ljCompanies[] = $user->company_name;
			// 			$leadjigName = $user->{'first_name'}.' '.$user->{'last_name'};
			// 			if ($user->id == $usersUID || $user->company_name == $companyName || $metricsName === $leadjigName)
			// 			{
			// 				$uniqID = $user->id;
			// 				// echo $user->first_name.' '.$user->last_name."\r\n"."LJ Event IDs to Call:\r\n";
			// 				$counter = 0;
							
			// 				foreach ($user->_links->campaigns as $c => $campaign) 
			// 				{
			// 					$campaignURLarray = preg_split("#/#", $campaign->_self);
			// 					$campaignID = $campaignURLarray[4];
			// 					$campaignResponse = $sesh->get('https://app.leadjig.com/api/v1/campaigns/'.$campaignID);
			// 					$campaignObjBody = json_decode($campaignResponse->body);
			// 					$campaignObj = $campaignObjBody->campaign;
			// 					// $allCampaign[] = array(
			// 					// 	'ages_targeted' => implode( ", ", $campaignObj->ages_targeted ),
			// 					// 	'rsvps' => $campaignObj->registration_count,
			// 					// 	'mailer_size' => $campaignObj->reach,
			// 					// 	'email_invites' => $campaignObj->emails_sent
			// 					// );
								
			// 					foreach ($campaign->events as $eKey => $ljEvent) 
			// 					{
			// 						$counter++;
			// 						$eventURLarray = preg_split("#/#", $ljEvent); 
			// 						$ljEventID = $eventURLarray[4];
			// 						$ljEvents[] = $ljEventID;
			// 						$eventResponse = $sesh->get('https://app.leadjig.com/api/v1/events/'.$ljEventID);
			// 						$eventObjBody = json_decode($eventResponse->body);
			// 						$eventObj = $eventObjBody->event;
			// 						// $topics[] = $eventObj->event->topic;
			// 						$projs = Project::find('all', array('conditions' => array('company_id=?', $this->user->company_id)));
			// 						$ljEdate = date("m/d/Y", strtotime($eventObj->start_time));
			// 						$convertljTime = new DateTime($eventObj->start_time);
			// 							$ljEtime = $convertljTime->format("h:i A");
			// 						$eOption = getEventOptions();
									

			// 						//See if its in events already
			// 						foreach ($projs as $proj) {
			// 							// if ( $proj->event_date === $ljEdate && ((compare($eventObj->topic, $proj->event) !== 0) || (!empty($proj->leadjig_id))) )	{
			// 								if ( $proj->event_date === $ljEdate && ((compare($eventObj->topic, $proj->event) !== 0) || $proj->leadjig_id == $eventObj->id ) )	{
			// 								//The ones in here are matched to a pre-existing event inside metrics
			// 								//Need to leadjig ID to them and then add Prospects here

											
											
			// 								$eventsinMetrics[] = array(
			// 									'id' => $proj->id,
			// 									'leadjig_id' => $eventObj->id,
			// 									'event' => $proj->event, 
			// 									'event_date' => $ljEdate,
			// 									'event_time' => $ljEtime,
			// 									'location' => $eventObj->venue,
			// 									'description' => $eventObj->topic,
			// 									'age_targeted' => implode( " - ", $campaignObj->ages_targeted ),
			// 									'rsvps' => $campaignObj->total_attendee_count,
			// 									'mailer_size' => $campaignObj->reach,
			// 									'email_invites' => $campaignObj->emails_sent
			// 								);
			// 								// foreach ($eventObj->prospect_records as $ljProspects) {
			// 									// 	$addressarray = array_map('ltrim', explode(",", $ljProspects->prospect->address_full));
			// 									// 	$stateZip = explode(" ", $addressarray[2]);
			// 									// 	$addressarray[2] = $stateZip[0];
			// 									// 	$addressarray[3] = $stateZip[1];

			// 									// 	$prospectsForEventsAlreadyIn[] = array(
			// 									// 		'event_id' => $proj->id,
			// 									// 		'leadjig_id' => $eventObj->id,
			// 									// 		'prospect_id' => $ljProspects->prospect->id,
			// 									// 		'firstname' => $ljProspects->prospect->first_name,
			// 									// 		'lastname' => $ljProspects->prospect->last_name,
			// 									// 		'fullname' => $ljProspects->prospect->first_name.' '.$ljProspects->prospect->last_name,
			// 									// 		'email' => $ljProspects->prospect->email,
			// 									// 		'address' => $addressarray[0],
			// 									// 		'city' => $addressarray[1],
			// 									// 		'state' => $addressarray[2],
			// 									// 		'zipcode' => $addressarray[3],
			// 									// 		'matched' => 0
			// 									// 	);
			// 								// }
			// 							}
			// 						}

			// 						foreach ($eventObj->prospect_records as $ljProspects) {
			// 							// $addressarray = array_map('ltrim', explode(",", $ljProspects->prospect->address_full));
			// 							// $stateZip = explode(" ", $addressarray[2]);
			// 							// $addressarray[2] = $stateZip[0];
			// 							// $addressarray[3] = $stateZip[1];
			// 							$street_address = $ljProspects->prospect->address_components->street_number.' '.$ljProspects->prospect->address_components->street;
			// 							$city_address   = $ljProspects->prospect->address_components->city;
			// 							$state_address  = $ljProspects->prospect->address_components->state;
			// 							$zip_address    = $ljProspects->prospect->address_components->zip;
										

			// 							$prospectsForEventsAlreadyIn[] = array(
			// 								'leadjig_id'   => $eventObj->id,
			// 								'prospect_id'  => $ljProspects->prospect->id,
			// 								'created'      => date("m/d/Y", strtotime($ljProspects->created_at)),
			// 								'firstname'    => $ljProspects->prospect->first_name,
			// 								'lastname'     => $ljProspects->prospect->last_name,
			// 								'fullname'     => $ljProspects->prospect->first_name.' '.$ljProspects->prospect->last_name,
			// 								'email'        => $ljProspects->prospect->email,
			// 								'address'      => $street_address,
			// 								'city'         => $city_address,
			// 								'state'        => $state_address,
			// 								'zipcode'      => $zip_address,
			// 								'phone'        => $ljProspects->prospect->phone,
			// 								'matched'      => 0,
			// 								'source_media' => $ljProspects->lure->type_to_s,
			// 								'income'       => ($ljProspects->prospect->income) ? $ljProspects->prospect->income : 0,
			// 								'assets'       => ($ljProspects->prospect->worth) ? $ljProspects->prospect->worth : 0,
			// 								'age'          => ($ljProspects->prospect->age) ? $ljProspects->prospect->age : 0
			// 							);
			// 						}

			// 						if( compareSingle($eventObj->topic) !== 0 ) {
			// 							$alltopic = compareSingle($eventObj->topic);
			// 						}else { $alltopic = "other1"; } 
			// 						$allLeadJigEvents[] = array(
			// 							'leadjig_id' => $eventObj->id,
			// 							'name' => '6',
			// 							'company_id' => $this->user->company_id,
			// 							'event' => $alltopic, 
			// 							'event_date' => $ljEdate,
			// 							'event_time' => $ljEtime,
			// 							'location' => $eventObj->venue,
			// 							'description' => $eventObj->topic,
			// 							'age_targeted' => implode( " - ", $campaignObj->ages_targeted ),
			// 							'rsvps' => $campaignObj->total_attendee_count,
			// 							'mailer_size' => $campaignObj->reach,
			// 							'email_invites' => $campaignObj->emails_sent,
			// 							'zip_codes' => implode( ", ", $campaignObj->zips_targeted )
			// 						);

			// 					}
			// 				}
			// 			}
			// 		}
					
			// 		// $ljCompanies is all company names from LeadJig
			// 		$this->view_data['ljCompanies'] = $ljCompanies;

			// 		// $allLeadJigEvents = array of all events 
						
			// 			$prospectsMatched = array();
			// 			foreach($prospectsForEventsAlreadyIn as $pKey => $pVal) {
			// 				$prospectsMatched[$pKey] = $pVal;
			// 				foreach($allClients as $aClient) {
			// 					$fn = trim($aClient->firstname).' '.trim($aClient->lastname);
			// 					if( $fn == trim($pVal['fullname']) )
			// 					{
			// 						unset( $prospectsMatched[ $pKey ] );
			// 					}
			// 				}
			// 			}
						
			// 			// $this->view_data['ac'] = $allCampaign;
			// 			// $this->view_data['prospects'] = $prospectsForEventsAlreadyIn;
			// 			$this->view_data['prospectsMatched'] = $prospectsMatched;
			// 			// $this->view_data['eventArray'] = $ljEvents;
			// 			$this->view_data['allLeadJigEvents'] = $allLeadJigEvents;
			// 			$this->view_data['matchedEvents'] = $eventsinMetrics;

						
			// 			$unmatchedArray = getUnmatchedEvents($allLeadJigEvents, $eventsinMetrics, $this->user->last_sync);
			// 			$this->view_data['unmatchedEvents'] = $unmatchedArray;

			// 			// Add leadjig_id to the events we already have and 
			// 			// update their information with source_media, 
			// 			// income, assets, age, etc
			// 			if ( !empty($eventsinMetrics) ) {
			// 				foreach($eventsinMetrics as $eventinMetrics) {
			// 					$projUpdate = Project::find($eventinMetrics['id']);
			// 					if ( empty($projUpdate->leadjig_id) ){
			// 						$projUpdate->leadjig_id = $eventinMetrics['leadjig_id'];
			// 						//update matched 
			// 						// $projUpdate->save();
			// 					}
			// 						$projUpdate->update_attributes($eventinMetrics);
			// 						$projUpdate->save();
								
			// 					// if ($eventinMetrics === end($eventsinMetrics)) {
									
									
			// 					// }
			// 				}
			// 			}
			// 			//Create events we do not have
			// 			//This section will be turned off. Andrew made decision to not create events if we have not already made them in metrics. Only to bring in future events. Section will be redone to only create if date is after first date of production version
			// 			if (empty($unmatchedArray)) {
			// 				$this->addLeadJigProspects($prospectsMatched);
			// 			} else {
			// 				foreach ($unmatchedArray as $unMatched) {
			// 					//if event date from LJ is > production date then..
			// 					//create unmatched 
			// 					$project = Project::create($unMatched);
			// 					if ($unMatched === end($unmatchedArray)) {
			// 						$this->addLeadJigProspects($prospectsMatched);
			// 					}
			// 				}
			// 			}

						
						
						


			// 			//add new prospects
			// 			//dont forget to add $allProjects and allClients
			// 			// $this->addLeadJigProspects($prospectsMatched);
						
							
						
						
					
			// 		$this->view_data['sync'] = 1;
			// 		//Update users sync
			// 		$update = User::find($this->user->id);
			// 		$update->last_sync = time();
			// 		$update->uniq_id = $uniqID;
			// 		$update->save();

			// 		$this->view_data['last_sync'] = time();
		}
		else { 
			$this->view_data['sync'] = 0;
		}
		
		

		$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));
		// $this->view_data['excel'] = $this->user;
		$this->content_view = 'dashboard/dashboard';
	}

	function addLeadJigProspects($pMatches) {
		$everyProject = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
		foreach($pMatches as $aPkey => $addProspect) {
			foreach($everyProject as $projToBeAddedTo) {
				if ($projToBeAddedTo->leadjig_id == $addProspect['leadjig_id']) {
					$addProspect["event_id"] = $projToBeAddedTo->id;
				}
			}
			unset( $addProspect[ 'leadjig_id' ] );
			unset( $addProspect[ 'matched' ] );
			unset( $addProspect[ 'fullname' ] );
			$addProspect['company_id'] = $this->user->company_id;
			if ( !isset($addProspect['zipcode']) )
				$addProspect['zipcode'] = 0;
			// Check to see when prospect was created and only make them if created past earliestProspectDate
			$prospCreated = new DateTime($addProspect[ 'created' ]);
			$earliestProspectDate = new DateTime("02/01/2017");
			if ( $prospCreated >= $earliestProspectDate ) {
				unset( $addProspect[ 'created' ] );
				$addedClient = Client::create($addProspect);							
			}
		}
	}

	function getCSV()
	{
		$excel = Client::find_by_sql("CALL GetExcel(?)", array($this->user->company_id));
		ob_end_clean();
		$lastname = $this->user->lastname;
		$lastactive = date('m-d-Y', $this->user->last_active);
		header("Content-type: text/x-csv");
		header("Content-Disposition: attachment; filename=".$lastname.$lastactive.".csv");
		$csv_export = "Full Name, Production Type, Event Lead Source, Status, Date Received, Date Submitted, Date Paid, Amount";
		//New Line
		$csv_export.= '
		';
		foreach ($excel as $e){
			$csv_export.= $e->{'full name'}.',"'.$e->{'production type'}.'","'.$e->{'event lead source'}.'","'.$e->{'status'}.'","'.$e->{'date received'}.'","'.$e->{'date submitted'}.'","'.$e->{'date paid'}.'","'.$e->{'amount'}.'"';
			$csv_export.= '
			';
		}
		
		echo($csv_export);
		exit();
	}

	function apiGet(){
		//API Stuff
		$allClients = Client::all(array('conditions' => 'inactive = 0 and company_id ='.$this->user->company_id));
		$companies = Company::find('all',array('conditions' => array('inactive=?','0')));
				$allProjects = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
				$this->view_data['user'] = $this->user;
				$this->view_data['last_sync'] = $this->user->last_sync;
				
				$this->load->library('PHPRequests');
				$this->load->helper('custom');
					
				$sesh = new Requests_Session('https://app.leadjig.com/api/v1');
				$sesh->headers['authentication-token'] = 'iyGjPbPRfV4qWEKhdBkaZbGmVWH4N2VrzS8VGS5fBQqPDCKt';
				$sesh->useragent = 'Metrics';

				$userResponse  = $sesh->get('https://app.leadjig.com/api/v1/users');
				
				//Decode JSON
				$obj = json_decode($userResponse->body);

				//Get company Name
				foreach ($companies as $k => $val) {
					($this->user->company_id === $val->id) ? $companyName =  $val->name : '';
				}
				$metricsName = $this->user->firstname." ".$this->user->lastname;
				$usersUID = $this->user->uniq_id;

				//Loop through users and put their Events into an Array $ljEvents
				foreach ($obj->users as $user) 
				{
					$ljCompanies[] = $user->company_name;
					$leadjigName = $user->{'first_name'}.' '.$user->{'last_name'};
					if ($user->id == $usersUID || $user->company_name == $companyName || $metricsName === $leadjigName)
					{
						$uniqID = $user->id;
						// echo $user->first_name.' '.$user->last_name."\r\n"."LJ Event IDs to Call:\r\n";
						$counter = 0;
						
						foreach ($user->_links->campaigns as $c => $campaign) 
						{
							$campaignURLarray = preg_split("#/#", $campaign->_self);
							$campaignID = $campaignURLarray[4];
							$campaignResponse = $sesh->get('https://app.leadjig.com/api/v1/campaigns/'.$campaignID);
							$campaignObjBody = json_decode($campaignResponse->body);
							$campaignObj = $campaignObjBody->campaign;
							// $allCampaign[] = array(
							// 	'ages_targeted' => implode( ", ", $campaignObj->ages_targeted ),
							// 	'rsvps' => $campaignObj->registration_count,
							// 	'mailer_size' => $campaignObj->reach,
							// 	'email_invites' => $campaignObj->emails_sent
							// );
							
							foreach ($campaign->events as $eKey => $ljEvent) 
							{
								$counter++;
								$eventURLarray = preg_split("#/#", $ljEvent); 
								$ljEventID = $eventURLarray[4];
								$ljEvents[] = $ljEventID;
								$eventResponse = $sesh->get('https://app.leadjig.com/api/v1/events/'.$ljEventID);
								$eventObjBody = json_decode($eventResponse->body);
								$eventObj = $eventObjBody->event;
								// $topics[] = $eventObj->event->topic;
								$projs = Project::find('all', array('conditions' => array('company_id=?', $this->user->company_id)));
								$ljEdate = date("m/d/Y", strtotime($eventObj->start_time));
								$convertljTime = new DateTime($eventObj->start_time);
									$ljEtime = $convertljTime->format("h:i A");
								$eOption = getEventOptions();
								

								//See if its in events already
								foreach ($projs as $proj) {
									// if ( $proj->event_date === $ljEdate && ((compare($eventObj->topic, $proj->event) !== 0) || (!empty($proj->leadjig_id))) )	{
										if ( $proj->event_date === $ljEdate && ((compare($eventObj->topic, $proj->event) !== 0) || $proj->leadjig_id == $eventObj->id ) )	{
										//The ones in here are matched to a pre-existing event inside metrics
										//Need to leadjig ID to them and then add Prospects here

										
										
										$eventsinMetrics[] = array(
											'id' => $proj->id,
											'leadjig_id' => $eventObj->id,
											'event' => $proj->event, 
											'event_date' => $ljEdate,
											'event_time' => $ljEtime,
											'location' => $eventObj->venue,
											'description' => $eventObj->topic,
											'age_targeted' => implode( " - ", $campaignObj->ages_targeted ),
											'rsvps' => $campaignObj->total_attendee_count,
											'mailer_size' => $campaignObj->reach,
											'email_invites' => $campaignObj->emails_sent,
											'zip_codes' => ($proj->zip_codes) ? $proj->zip_codes : implode( ", ", $campaignObj->zips_targeted ),
											'assets_targeted' => ($campaignObj->assets_targeted) ? implode( ", ", $campaignObj->assets_targeted) : 0,
											'incomes_targeted' => ($campaignObj->incomes_targeted) ? implode(", ", $campaignObj->incomes_targeted) : 0
										);
										// foreach ($eventObj->prospect_records as $ljProspects) {
											// 	$addressarray = array_map('ltrim', explode(",", $ljProspects->prospect->address_full));
											// 	$stateZip = explode(" ", $addressarray[2]);
											// 	$addressarray[2] = $stateZip[0];
											// 	$addressarray[3] = $stateZip[1];

											// 	$prospectsForEventsAlreadyIn[] = array(
											// 		'event_id' => $proj->id,
											// 		'leadjig_id' => $eventObj->id,
											// 		'prospect_id' => $ljProspects->prospect->id,
											// 		'firstname' => $ljProspects->prospect->first_name,
											// 		'lastname' => $ljProspects->prospect->last_name,
											// 		'fullname' => $ljProspects->prospect->first_name.' '.$ljProspects->prospect->last_name,
											// 		'email' => $ljProspects->prospect->email,
											// 		'address' => $addressarray[0],
											// 		'city' => $addressarray[1],
											// 		'state' => $addressarray[2],
											// 		'zipcode' => $addressarray[3],
											// 		'matched' => 0
											// 	);
										// }
									}
								}

								foreach ($eventObj->prospect_records as $ljProspects) {
									// $addressarray = array_map('ltrim', explode(",", $ljProspects->prospect->address_full));
									// $stateZip = explode(" ", $addressarray[2]);
									// $addressarray[2] = $stateZip[0];
									// $addressarray[3] = $stateZip[1];
									$street_address = $ljProspects->prospect->address_components->street_number.' '.$ljProspects->prospect->address_components->street;
									$city_address   = $ljProspects->prospect->address_components->city;
									$state_address  = $ljProspects->prospect->address_components->state;
									$zip_address    = $ljProspects->prospect->address_components->zip;
									

									$prospectsForEventsAlreadyIn[] = array(
										'leadjig_id'   => $eventObj->id,
										'prospect_id'  => $ljProspects->prospect->id,
										'created'      => date("m/d/Y", strtotime($ljProspects->created_at)),
										'firstname'    => $ljProspects->prospect->first_name,
										'lastname'     => $ljProspects->prospect->last_name,
										'fullname'     => $ljProspects->prospect->first_name.' '.$ljProspects->prospect->last_name,
										'email'        => $ljProspects->prospect->email,
										'address'      => $street_address,
										'city'         => $city_address,
										'state'        => $state_address,
										'zipcode'      => $zip_address,
										'phone'        => $ljProspects->prospect->phone,
										'matched'      => 0,
										'source_media' => $ljProspects->lure->type_to_s,
										'income'       => ($ljProspects->prospect->income) ? $ljProspects->prospect->income : 0,
										'assets'       => ($ljProspects->prospect->worth) ? $ljProspects->prospect->worth : 0,
										'age'          => ($ljProspects->prospect->age) ? $ljProspects->prospect->age : 0
									);
								}

								//Add $campaignObj->category as event type here with the current one as backup
								if ( !empty($campaignObj->category) ) {
									$alltopic = $campaignObj->category;
								}
								else {
									if ( compareSingle($eventObj->topic) !== 0 ) {
										$alltopic = compareSingle($eventObj->topic);
									} else { $alltopic = "other1"; }
								}	 
								$allLeadJigEvents[] = array(
									'leadjig_id' => $eventObj->id,
									'name' => '6',
									'company_id' => $this->user->company_id,
									'event' => $alltopic, 
									'event_date' => $ljEdate,
									'event_time' => $ljEtime,
									'location' => $eventObj->venue,
									'description' => $eventObj->topic,
									'age_targeted' => implode( " - ", $campaignObj->ages_targeted ),
									'rsvps' => $campaignObj->total_attendee_count,
									'mailer_size' => $campaignObj->reach,
									'email_invites' => $campaignObj->emails_sent,
									'zip_codes' => implode( ", ", $campaignObj->zips_targeted ),
									'assets_targeted' => ($campaignObj->assets_targeted) ? implode( ", ", $campaignObj->assets_targeted) : 0,
									'incomes_targeted' => ($campaignObj->incomes_targeted) ? implode(", ", $campaignObj->incomes_targeted) : 0
								);

							}
						}
					}
				}
				
				// $ljCompanies is all company names from LeadJig
				$this->view_data['ljCompanies'] = $ljCompanies;

				// $allLeadJigEvents = array of all events 
					
					$prospectsMatched = array();
					foreach($prospectsForEventsAlreadyIn as $pKey => $pVal) {
						$prospectsMatched[$pKey] = $pVal;
						foreach($allClients as $aClient) {
							$fn = trim($aClient->firstname).' '.trim($aClient->lastname);
							if( $fn == trim($pVal['fullname']) )
							{
								unset( $prospectsMatched[ $pKey ] );
							}
						}
					}
					
					// $this->view_data['ac'] = $allCampaign;
					// $this->view_data['prospects'] = $prospectsForEventsAlreadyIn;
					$this->view_data['prospectsMatched'] = $prospectsMatched;
					// $this->view_data['eventArray'] = $ljEvents;
					$this->view_data['allLeadJigEvents'] = $allLeadJigEvents;
					$this->view_data['matchedEvents'] = $eventsinMetrics;

					
					$unmatchedArray = getUnmatchedEvents($allLeadJigEvents, $eventsinMetrics, $this->user->last_sync);
					$this->view_data['unmatchedEvents'] = $unmatchedArray;

					// Add leadjig_id to the events we already have and 
					// update their information with source_media, 
					// income, assets, age, etc
					if ( !empty($eventsinMetrics) ) {
						foreach($eventsinMetrics as $eventinMetrics) {
							$projUpdate = Project::find($eventinMetrics['id']);
							if ( empty($projUpdate->leadjig_id) ){
								$projUpdate->leadjig_id = $eventinMetrics['leadjig_id'];
							}
							$projUpdate->update_attributes($eventinMetrics);
							$projUpdate->save();
							
							// if ($eventinMetrics === end($eventsinMetrics)) {
								
								
							// }
						}
					}
					//Create events we do not have
					//This section will be turned off. Andrew made decision to not create events if we have not already made them in metrics. Only to bring in future events. Section will be redone to only create if date is after first date of production version
					if (empty($unmatchedArray)) {
						$this->addLeadJigProspects($prospectsMatched);
					} else {
						foreach ($unmatchedArray as $unMatched) {
							//if event date from LJ is > production date then..
							//create unmatched 
							$project = Project::create($unMatched);
							if ($unMatched === end($unmatchedArray)) {
								$this->addLeadJigProspects($prospectsMatched);
							}
						}
					}

					
					
					


					//add new prospects
					//dont forget to add $allProjects and allClients
					// $this->addLeadJigProspects($prospectsMatched);
					
						
					
					
				
				$this->view_data['sync'] = 1;
				//Update users sync
				$update = User::find($this->user->id);
				$update->last_sync = time();
				if(!empty($uniqID)){$update->uniq_id = $uniqID;}
				$update->save();

				$this->view_data['last_sync'] = time();
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

	function companyswitch($id = FALSE)
	{		
		$user = User::find($id);
		if($_POST){
			$_POST = array_map('htmlspecialchars', $_POST);
			// error_log("user var is: ".print_r($user, true).print_r($_POST, true), 1, "cstubbs@advisorsacademy.com");
			$user->company_id = $_POST["company_id"];
			$user->save();
			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_user_success'));
			redirect('dashboard');
		}
		else{
			error_log("Company Switch did not work", 1, "cstubbs@advisorsacademy.com");
			// $this->content_view = 'dashboard/dashboard'.$user->id;
			$this->session->set_flashdata('message', 'failed: Error while switching Company');
			redirect('dashboard');
		}
	}


}
