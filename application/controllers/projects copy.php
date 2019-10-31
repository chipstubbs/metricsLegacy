<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->client){
			if($this->input->cookie('fc2_link') != ""){
					$link = $this->input->cookie('fc2_link');
					$link = str_replace("/tickets/", "/ctickets/", $link);
					redirect($link);
			}else{
				redirect('cprojects');
			}

		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) {
				if($value->link == "projects"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}else{
			redirect('login');
		}
		$this->view_data['submenu'] = array(
				 		$this->lang->line('application_all') => 'projects',
				 		$this->lang->line('application_open') => 'projects/filter/open',
				 		$this->lang->line('application_closed') => 'projects/filter/closed'
				 		);
		$this->load->database();

	}
	function index()
	{
		$this->session->set_userdata('refer_from', '');
		// $this->view_data['project'] = Project::all();
		$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));
		$this->content_view = 'projects/all';
		$this->view_data['projects_assigned_to_me'] = ProjectHasWorker::find_by_sql('select count(distinct(projects.id)) AS "amount" FROM projects, project_has_workers WHERE projects.progress != "100" AND (projects.id = project_has_workers.project_id AND project_has_workers.user_id = "'.$this->user->id.'") ');
		$this->view_data['tasks_assigned_to_me'] = ProjectHasTask::count(array('conditions' => 'user_id = '.$this->user->id.' and status = "open"'));

		$now = time();
		$beginning_of_week = strtotime('last Monday', $now); // BEGINNING of the week
		$end_of_week = strtotime('next Sunday', $now) + 86400; // END of the last day of the week
		$this->view_data['projects_opened_this_week'] = Project::find_by_sql('select count(id) AS "amount", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%w") AS "date_day", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%Y-%m-%d") AS "date_formatted" from projects where datetime >= "'.$beginning_of_week.'" AND datetime <= "'.$end_of_week.'" ');

		$this->load->helper('custom');
		$this->load->helper('format');
		$productionTable = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND YEAR(prem_paid_month) = '.$this->session->userdata("year_to_view") );

		$this->view_data['project'] = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
		$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));



		//Avgs
		$eventoptions = array(
			'' => '-',
			'ss' => 'Social Security',
			'rmd' => 'RMD',
			'estate' => 'Estate',
			'taxpro' => 'TaxPro',
			'college' => 'College Planning',
			'federalemployee' => 'Federal Employee Benefits Specialist',
			'teacherpro' => 'Teacher Pro',
			'radio' => 'Radio',
			'cpa-attorney' => 'CPA / Attorney',
			'pcpartnership' => 'P&C Partnership',
			'financialliteracy' => 'Financial Literacy',
			'guestspeaker' => 'Guest Speaker',
			'platinumreferral' => 'Platinum Referrals',
			'advisoryboard' => 'Advisory Board',
			'lunchnlink' => 'Lunch &amp; Link',
			'clientparty' => 'Client Appreciation Party',
			'selectclub' => 'Select Club',
			'bday' => 'Birthday',
			'retirement' => 'Retirement',
			'manipedi' => 'Mani / Pedi',
			'dinnerseminar' => 'Dinner Seminar',
			'other1' => 'Other 1',
			'other2' => 'Other 2',
			'other3' => 'Other 3'
		);
		//
		//Responses & Response Ratios
		//
		//$eventprojectsoverall = Project::find('all', array( 'conditions' => array( 'name = 6 AND company_id=? ORDER BY event', $this->user->company_id ) ));
		//If we use the switch for referral we'd use this query. But we are just grabbing all events. $eventprojects = Project::find_by_sql('SELECT * FROM projects JOIN clients ON projects.id = clients.event_id WHERE projects.name = 6 AND projects.company_id = '.$this->user->company_id.' AND clients.referral = 0');
		//$referral_eventprojects = Project::find_by_sql('SELECT * FROM projects LEFT JOIN clients ON projects.id = clients.event_id WHERE projects.name = 6 AND projects.company_id = '.$this->user->company_id.' AND clients.referral = 1');
		// $eventprojects = Project::find('all', array( 'conditions' => array( 'name = 6 AND company_id=? ORDER BY event', $this->user->company_id ) ));
		//Event projects that filter by year
			$eventprojects = Project::find('all', array( 'conditions' => array( '(name = 6 AND company_id=? ) AND YEAR(STR_TO_DATE(event_date, "%m/%d/%Y")) = '.$this->session->userdata("year_to_view").' ORDER BY event', $this->user->company_id ) ));
		$clientstable = Client::all(array('conditions' => 'inactive = 0 and company_id ='.$this->user->company_id));
		$avg_nums = array();
		$avg_ratio = array();
		$AVG = new stdClass();
		$AVG_referrals = new stdClass();
		$AVG_overall = new stdClass();
		foreach ($eventoptions as $key => $value) { $count = $count_referrals = $count_overall = $sum = $mailers = $BU = $response_ratio = $attendance_ratio = $appointments = $appointment_ratio = $keptappointments = $appointment_kept_ratio = $prospectsclosed = $closingratio = $eventcost = $mailercost = $adcost = $othercost = $totaleventcost = $acatproduction = $annuityproduction = $annuitycom = $otherproduction = $othercom = $annuitypercent = $avgtoannuity = 0;
			//For non-referrals
			foreach($eventprojects as $ev)
			{
				if ($ev->event == $key)
				{
					$count++;
					// If event is platinum referral then $sum is referral_attendee++
					if ($ev->event === 'platinumreferral') {
						$sum += $ev->referral_attendee;
						$mailers += $ev->referral_response;
					}
					else {
						$sum += $ev->total_responses;
						$mailers += $ev->number_mailers;
					}

					$BU += $ev->bu_attended;
					foreach($clientstable as $ct){
						($ct->sched_appt_check > 0 && $ct->event_id == $ev->id) ? $appointments++ : '' ;
						($ct->kept_appt > 0 && $ct->event_id == $ev->id) ? $keptappointments++ : '' ;
						(($ct->acat > 0 || $ct->aum > 0 || $ct->annuity_app > 0 || $ct->life_submitted > 0 || $ct->other > 0) && $ct->event_id == $ev->id) ? $prospectsclosed++ : '' ;
					}
					$eventcost += $ev->total_event_cost;
					$mailercost += $ev->mailers_cost;
					$adcost += $ev->ad_cost;
					$othercost += $ev->other_invite_cost;

					foreach($productionTable as  &$entry){
						if($entry->event_id == $ev->id) {
							if ($entry->production_type === 'acat') { $acatproduction += ($entry->prem_paid/100); }
							else if ($entry->production_type === 'annuity') { $annuityproduction += $entry->prem_paid/100; $annuitycom += ($entry->comp_agent_percent/100) * ($entry->prem_paid/100); }
							else if ($entry->production_type === 'other' || $entry->production_type === 'life') { $otherproduction += $entry->prem_paid/100; $othercom += ($entry->comp_agent_percent/100) * ($entry->prem_paid/100); }
							else if ($entry->production_type === 'aum') {  }
						}
					} if (!empty($acatproduction) && !empty($annuityproduction)) { $annuitypercent = ($annuityproduction/$acatproduction); }
				}
			}
			if($count > 0)
			{
				// If event is platinum referral then response_ratio is 'referral_attendee / referral_response'
				( !empty($mailers) && !empty($sum) ) ? $response_ratio = round((float)($sum / $mailers) * 100, 1 ).'%' : $response_ratio = '0%';
				( !empty($sum) && !empty($BU) ) ? $attendance_ratio = round((float)($BU / $sum) * 100, 1 ).'%' : $attendance_ratio = '0%';
				( !empty($BU) && !empty($appointments) ) ? $appointment_ratio = round((float)($appointments / $BU) * 100, 1 ).'%' : $appointment_ratio = '0%';
				( !empty($keptappointments) && !empty($appointments) ) ? $appointment_kept_ratio = round((float)($keptappointments/$appointments) * 100, 1 ).'%' : $appointment_kept_ratio = '0%' ;
				( !empty($prospectsclosed) && !empty($keptappointments) ) ? $closingratio = round((float)($prospectsclosed / $keptappointments) * 100, 1 ).'%' : $closingratio = '0%';
				$totaleventcost = ( ($eventcost + $mailercost + $adcost + $othercost) / 100 ) / $count;
				$grossprofit = ( $othercom + $annuitycom - ( ($eventcost + $mailercost + $adcost + $othercost) / 100 ) ) / $count;
				( !empty($annuitypercent) ) ? $avgtoannuity = round((float)($annuitypercent) * 100, 1 ).'%' : $avgtoannuity = '0%';

				if ($this->user->admin != 1 && !empty($key)) {
					$AVG->$key = (object) [
						'avg_response'           => round(($sum / $count),1),
						'response_ratio'         => $response_ratio,
						'avg_buying_units'       => round(($BU / $count),1),
						'attendance_ratio'       => $attendance_ratio,
						'avg_appointments'       => round(($appointments / $count),1),
						'appointment_ratio'      => $appointment_ratio,
						'avg_appointment_kept'   => round(($keptappointments / $count),1),
						'appointment_kept_ratio' => $appointment_kept_ratio,
						'prospectsclosed'        => round(($prospectsclosed / $count),1),
						'closingratio'           => $closingratio,
						'totaleventcost'         => $totaleventcost,
						'grossprofit'            => $grossprofit,
						'annuityavg'             => ($annuityproduction / $count),
						'avgtoannuity'           => $avgtoannuity,
						'totalannuity'           => $annuityproduction,
						'counter'                 => $count
					];
				} else {
					$AVG = (object) [];
				}
				// $avg_nums[$key] = ($sum / $count);
				// $avg_BU[$key] = ($BU / $count);
				// !empty($mailers) ? $AVG->$key["response_ratio"] = ($sum / $mailers)) : $AVG->$key['response_ratio'] = 'no mailers') ;
			}
			//END non-referrals



			//if($count > 0) { $avg_nums[$key] = 'Sum '.$sum.'/ Count'.$count; }
		}
//Client Averages
		// $number_of_clients = Client::count(array('conditions' => '(event_id = 3 AND inactive = 0) AND company_id = '.$this->user->company_id )); //Count Clients
		// $client_sched_appt = Client::count(array('conditions' => '(event_id = 3 AND sched_appt_check = 1) AND (inactive = 0 AND company_id ='.$this->user->company_id.')')); //Count how many clients have a scheduled appointment
		// $client_kept_appts = Client::count(array('conditions' => '(event_id = 3 AND kept_appt = 1) AND (inactive = 0 AND company_id ='.$this->user->company_id.')')); //Count kept appointments
		// $client_has_assets = Client::count(array('conditions' => '(event_id = 3 AND has_assets = 1) AND (inactive = 0 AND company_id ='.$this->user->company_id.')')); //Count clients with assets
		$number_of_clients = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id ='.$this->user->company_id.' AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").')' ));

		$client_sched_appt = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id ='.$this->user->company_id.' AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").') AND sched_appt_check = 1' ));

		$client_kept_appts = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id ='.$this->user->company_id.' AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").') AND kept_appt = 1' ));

		$client_has_assets = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id ='.$this->user->company_id.' AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").') AND has_assets = 1' ));

		$clients_closed    = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id ='.$this->user->company_id.' AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").')' )); //Count clients with some sort of business started

		$client_production = Production::find_by_sql('SELECT * FROM production LEFT JOIN clients ON production.client_id = clients.id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id = '.$this->user->company_id.' AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").')');

		foreach($client_production as &$cp){
			if ($cp->production_type === 'acat') { $client_acatproduction += ($cp->prem_paid/100); }
			else if ($cp->production_type === 'annuity') { $client_annuityproduction += $cp->prem_paid/100; $client_annuitycom += ($cp->comp_agent_percent/100) * ($cp->prem_paid/100); }
			else if ($cp->production_type === 'other' || $cp->production_type === 'life') { $client_othercom += ($cp->comp_agent_percent/100) * ($cp->prem_paid/100); }
			else if ($cp->production_type === 'aum') {  }
		}
//Referral Averages
		$number_of_referrals       = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = '.$this->user->company_id.') AND (clients.event_id = 2 OR clients.event_id = 1) AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view") )); //Count Referrals

		$referral_sched_appt       = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = '.$this->user->company_id.') AND (clients.event_id = 2 OR clients.event_id = 1) AND (clients.sched_appt_check = 1 AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").') ' )); //Count Referral scheduled appointments

		$referral_kept_appts       = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = '.$this->user->company_id.') AND (clients.event_id = 2 OR clients.event_id = 1) AND (clients.kept_appt = 1 AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view").')' )); //Count kept appointments

		$referral_has_assets       = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = '.$this->user->company_id.') AND (clients.has_assets = 1 AND clients.sched_appt_check = 1) AND (clients.event_id = 2 OR clients.event_id = 1) AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view") )); //Count referrals with assets and scheduled appointments

		$referrals_closed          = Client::count(array('select' => 'DISTINCT client.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = '.$this->user->company_id.') AND (clients.event_id = 2 OR clients.event_id = 1) AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view") )); //Count referrals with some sort of business started

		$referral_production       = Production::find_by_sql('SELECT * FROM production LEFT JOIN clients ON production.client_id = clients.id WHERE (clients.inactive = 0 AND clients.company_id = '.$this->user->company_id.') AND (clients.event_id = 1 OR clients.event_id = 2) AND YEAR(production.prem_paid_month) = '.$this->session->userdata("year_to_view") );

		foreach($referral_production as &$rp){
			if ($rp->production_type === 'acat') { $referral_acatproduction += ($rp->prem_paid/100); }
			else if ($rp->production_type === 'annuity') { $referral_annuityproduction += $rp->prem_paid/100; $referral_annuitycom += ($rp->comp_agent_percent/100) * ($rp->prem_paid/100); }
			else if ($rp->production_type === 'other' || $rp->production_type === 'life') { $referral_othercom += ($rp->comp_agent_percent/100) * ($rp->prem_paid/100); }
			else if ($rp->production_type === 'aum') {  }
		}

		$this->view_data["num_clients"]           = $number_of_clients;
		$this->view_data["num_referrals"]         = $number_of_referrals;
		$this->view_data["client_appts"]          = $client_sched_appt;
		$this->view_data["client_appts_ratio"]    = ( !empty($number_of_clients) ) ? round((float)($client_sched_appt / $number_of_clients) * 100, 1 ).'%' : '0%';
		$this->view_data["client_kept_appts"]     = $client_kept_appts;
		$this->view_data["client_kept_ratio"]     = ( !empty($client_sched_appt) ) ? round((float)($client_kept_appts / $client_sched_appt) * 100, 1 ).'%' : '0%';
		$this->view_data["client_has_assets"]     = $client_has_assets;
		$this->view_data["client_closed"]         = $clients_closed;
		$this->view_data["client_close_ratio"]    = (!empty($client_has_assets) ? round((float)($clients_closed / $client_has_assets) * 100, 1 ).'%' : '0%');
		$this->view_data["client_gross"]          = $client_annuitycom + $client_othercom;
		$this->view_data["client_annuity_avg"]    = ( !empty($client_acatproduction) ) ? round((float)($client_annuityproduction/$client_acatproduction) * 100, 1 ).'%' : '0%';
		$this->view_data["client_annuity"]        = $client_annuityproduction;

		$this->view_data["referral_appts"]        = $referral_sched_appt;
		$this->view_data["referral_appts_ratio"]  = ( !empty($number_of_referrals) ) ? round((float)($referral_sched_appt / $number_of_referrals) * 100, 1 ).'%' : '0%';
		$this->view_data["referral_kept_appts"]   = $referral_kept_appts;
		$this->view_data["referral_kept_ratio"]   = ( !empty($referral_sched_appt) ) ? round((float)($referral_kept_appts / $referral_sched_appt) * 100, 1 ).'%' : '0%';
		$this->view_data["referral_has_assets"]   = $referral_has_assets;
		$this->view_data["referral_close_ratio"]  = (!empty($referral_has_assets) ? round((float)($referrals_closed / $referral_has_assets) * 100, 1 ).'%' : '0%');
		$this->view_data["referral_closed"]       = $referrals_closed;
		$this->view_data["referral_gross"]        = $referral_annuitycom + $referral_othercom;
		$this->view_data["referral_annuity_avg"]  = ( !empty($referral_acatproduction) ) ? round((float)($referral_annuityproduction/$referral_acatproduction) * 100, 1 ).'%' : '0%';
		$this->view_data["referral_annuity"]      = $referral_annuityproduction;

		$this->view_data['responses'] = $avg_nums;
		$this->view_data['responses_ratio'] = $avg_ratio;
		$this->view_data["production"] = $productionTable;
		$this->view_data["clients"] = $clientstable;
		$this->view_data['avgs'] = $AVG;
		$this->view_data['referral_avgs'] = $AVG_referrals;
		$this->view_data['overall_avgs'] = $AVG_overall;

	}
	function filter($condition)
	{
		switch ($condition) {
			case 'open':
				$options = array('conditions' => 'progress < 100');
				break;
			case 'closed':
				$options = array('conditions' => 'progress = 100');
				break;
		}

		$this->view_data['project'] = Project::all($options);
		$this->content_view = 'projects/all';

		$this->view_data['projects_assigned_to_me'] = ProjectHasWorker::find_by_sql('select count(distinct(projects.id)) AS "amount" FROM projects, project_has_workers WHERE projects.progress != "100" AND (projects.id = project_has_workers.project_id AND project_has_workers.user_id = "'.$this->user->id.'") ');
		$this->view_data['tasks_assigned_to_me'] = ProjectHasTask::count(array('conditions' => 'user_id = '.$this->user->id.' and status = "open"'));

		$now = time();
		$beginning_of_week = strtotime('last Monday', $now); // BEGINNING of the week
		$end_of_week = strtotime('next Sunday', $now) + 86400; // END of the last day of the week
		$this->view_data['projects_opened_this_week'] = Project::find_by_sql('select count(id) AS "amount", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%w") AS "date_day", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%Y-%m-%d") AS "date_formatted" from projects where datetime >= "'.$beginning_of_week.'" AND datetime <= "'.$end_of_week.'" ');

	}
	function create()
	{
		if($_POST){
			unset($_POST['send']);
			$_POST['datetime'] = time();
			$_POST = array_map('htmlspecialchars', $_POST);
			unset($_POST['files']);
			//Strip commas from numbers
				$_POST['mailers_cost'] = strtr($_POST['mailers_cost'], array('.' => '' , ',' => ''));
				$_POST['ad_cost'] = strtr($_POST['ad_cost'], array('.' => '' , ',' => ''));
				$_POST['other_invite_cost'] = strtr($_POST['other_invite_cost'], array('.' => '' , ',' => ''));
				$_POST['total_event_cost'] = strtr($_POST['total_event_cost'], array('.' => '' , ',' => ''));
			$project = Project::create($_POST);
			$new_project_reference = $_POST['reference']+1;
			$project_reference = Setting::first();

			$project_reference->update_attributes(array('project_reference' => $new_project_reference));
       		if(!$project){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_create_project_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_create_project_success'));
       			$project_last = Project::last();
       			$sql = "INSERT INTO `project_has_workers` (`project_id`, `user_id`) VALUES (".$project_last->id.", ".$this->user->id.")";
				$query = $this->db->query($sql);
       			}
			redirect('projects/view/'.$project_last->id);
		}else
		{
			$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0'),  'order' => 'name'));
			$this->view_data['next_reference'] = Project::last();
			$this->theme_view = 'modal';
			$this->view_data['title'] = 'Add Event';
			$this->view_data['form_action'] = 'projects/create';
			$this->content_view = 'projects/_project';
		}
	}
	function update($id = FALSE)
	{
		if($_POST){
			unset($_POST['send']);
			$id = $_POST['id'];
			unset($_POST['files']);
			$_POST = array_map('htmlspecialchars', $_POST);
			if (!isset($_POST["progress_calc"])) {
				$_POST["progress_calc"] = 0;
			}
			$project = Project::find($id);
			//Strip commas from numbers
				$_POST['mailers_cost'] = strtr($_POST['mailers_cost'], array('.' => '' , ',' => ''));
				$_POST['ad_cost'] = strtr($_POST['ad_cost'], array('.' => '' , ',' => ''));
				$_POST['other_invite_cost'] = strtr($_POST['other_invite_cost'], array('.' => '' , ',' => ''));
				$_POST['total_event_cost'] = strtr($_POST['total_event_cost'], array('.' => '' , ',' => ''));

			$project->update_attributes($_POST);
       		if(!$project){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_project_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_project_success'));}
			redirect('projects/view/'.$id);
		}else
		{
			$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['project'] = Project::find($id);
			$this->theme_view = 'modal';
			if($this->user->admin === '1'){
				$this->view_data['isadmin'] = TRUE;
			}else {$this->view_data['isadmin'] = FALSE;}
			$this->load->helper('format');
			$this->view_data['title'] = 'Edit Event';
			$this->view_data['form_action'] = 'projects/update';
			$this->content_view = 'projects/_project';
		}
	}
	function assign($id = FALSE)
	{
		$this->load->helper('notification');
		if($_POST){
			unset($_POST['send']);
			$id = addslashes($_POST['id']);
			$project = Project::find_by_id($id);
			$sql = "SELECT user_id FROM project_has_workers WHERE project_id=".$id;
			$query = $this->db->query($sql);
			$query = $query->result_array();
			foreach($query as $k => $a) {
    			if (is_array($a)) { $query[$k] = $a['user_id']; }
			}

			$added = array_diff($_POST["user_id"], $query);
			$removed = array_diff($query, $_POST["user_id"]);

			foreach ($added as $value){
			$value = htmlspecialchars(addslashes($value));
			$sql = "INSERT INTO `project_has_workers` (`project_id`, `user_id`) VALUES (".$id.", ".$value.")";
			$query = $this->db->query($sql);
			$receiver = User::find_by_id($value);
			send_notification($receiver->email, $this->lang->line('application_notification_project_assign_subject'), $this->lang->line('application_notification_project_assign').'<br><strong>'.$project->name.'</strong>');
			}

			foreach ($removed as $value){
			$sql = "DELETE FROM `project_has_workers` WHERE user_id = ".$value." AND project_id=".$id;
			$query = $this->db->query($sql);
			//$receiver = User::find_by_id($value);
			}

       		if(!$query){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_project_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_project_success'));}
			redirect('projects/view/'.$id);
		}else
		{
			$this->view_data['users'] = User::find('all',array('conditions' => array('status=?','active')));
			$this->view_data['project'] = Project::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_assign_to_agents');
			$this->view_data['form_action'] = 'projects/assign';
			$this->content_view = 'projects/_assign';
		}
	}
	function delete($id = FALSE)
	{
		$project = Project::find($id);
		$project->delete();
		$sql = 'DELETE FROM project_has_tasks WHERE project_id = "'.$id.'"';
		$this->db->query($sql);
		$this->content_view = 'projects/all';
		if(!$project){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_project_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_project_success'));}
			if(isset($view)){redirect('projects/view/'.$id);}else{redirect('projects');}
	}
	function timer_reset($id = FALSE){
		$project = Project::find($id);
		$attr = array('time_spent' => '0');
		$project->update_attributes($attr);
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_timer_reset'));
		redirect('projects/view/'.$id);
	}
	function timer_set($id = FALSE){
		if($_POST){
		$project = Project::find_by_id($_POST['id']);
		$hours = $_POST['hours'];
		$minutes = $_POST['minutes'];
		$timespent = ($hours*60*60)+($minutes*60);
		$attr = array('time_spent' => $timespent);
		$project->update_attributes($attr);
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_timer_set'));
		redirect('projects/view/'.$_POST['id']);
		}else{
			$this->view_data['project'] = Project::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_timer_set');
			$this->view_data['form_action'] = 'projects/timer_set';
			$this->content_view = 'projects/_timer';
		}
	}
	function view($id = FALSE)
	{
		$this->view_data['submenu'] = array();
		$this->load->library('session');
		$projectstable = Project::find($id);
		$referralclients = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 and event_id = ?', $id)));
		$this->view_data['all_clients'] = Client::all(array('conditions' => array('inactive = 0 AND company_id = ?',  $projectstable->company_id)));
		$this->view_data['project'] = Project::find($id);
		$this->view_data['projects'] = Project::find('all');
		if ($projectstable->event === 'platinumreferral'){
			$this->view_data['product'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.acat, clients.client_prospect FROM production JOIN clients ON production.client_id = clients.id WHERE clients.referral = 1 AND clients.event_id = '.$id);
			$this->view_data['clients'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND event_id = ?', $id)));
			$this->view_data['clientslist'] = Client::all(array('conditions' => array('inactive = 0 AND event_id = ?', $id)));
			$this->view_data['scheduled_appts'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND sched_appt_check = ? and event_id = ?', '1', $id)));
			$this->view_data['first_appts'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND sched_appt >= ? and event_id = ?', '1', $id)));
			$this->view_data['kept_appts'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND kept_appt = ? and event_id = ?', '1', $id)));
			$this->view_data['closed_appts'] = Client::count(array('conditions' => array('(acat = 1 OR aum = 1 OR annuity_app = 1 OR life_submitted = 1 OR other = 1) AND referral = 1 AND inactive = 0 AND event_id = ?', $id)));
			$this->view_data['has_assets'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND has_assets = 1 AND event_id = ?', $id)));
			$this->view_data['annuity'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND annuity_paid = 1 AND event_id = ?', $id)));
			$this->view_data['acat'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND acat = 1 AND event_id = ?', $id)));
			$this->view_data['other'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND other = 1 AND event_id = ?', $id)));
		}
		else {
			$this->view_data['product'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.acat, clients.client_prospect FROM production JOIN clients ON production.client_id = clients.id WHERE clients.event_id = '.$id);
			$this->view_data['clients'] = Client::all(array('conditions' => array('inactive = 0 AND event_id = ?', $id)));
			$this->view_data['clientslist'] = Client::all(array('conditions' => array('inactive = 0 AND event_id = ?', $id)));
			$this->view_data['scheduled_appts'] = Client::count(array('conditions' => array('inactive = 0 AND sched_appt_check = ? and event_id = ?', '1', $id)));
			$this->view_data['first_appts'] = Client::count(array('conditions' => array('inactive = 0 AND sched_appt >= ? and event_id = ?', '1', $id)));
			$this->view_data['kept_appts'] = Client::count(array('conditions' => array('inactive = 0 AND kept_appt = ? and event_id = ?', '1', $id)));
			$this->view_data['closed_appts'] = Client::count(array('conditions' => array('(acat = 1 OR aum = 1 OR annuity_app = 1 OR life_submitted = 1 OR other = 1) AND inactive = 0 AND event_id = ?', $id)));
			$this->view_data['has_assets'] = Client::count(array('conditions' => array('inactive = 0 AND has_assets = 1 AND event_id = ?', $id)));
			$this->view_data['annuity'] = Client::all(array('conditions' => array('inactive = 0 AND annuity_paid = 1 AND event_id = ?', $id)));
			$this->view_data['acat'] = Client::all(array('conditions' => array('inactive = 0 AND acat = 1 AND event_id = ?', $id)));
			$this->view_data['other'] = Client::all(array('conditions' => array('inactive = 0 AND other = 1 AND event_id = ?', $id)));
		}


		//Hot Client/Prospect List
		$this->view_data['hot_prospect'] = Client::all(array('conditions' => array('inactive = 0 AND hot_prospect = 1 and company_id = ?',  $projectstable->company_id)));
		$this->view_data['company'] = Company::find($projectstable->company_id);
		$this->view_data['hot_client'] = Client::all(array('conditions' => array('inactive = 0 AND hot_client = 1 and company_id = ?', $projectstable->company_id)));

		$this->view_data['project_has_invoices'] = Invoice::all(array('conditions' => array('project_id = ?', $id)));
		if(!isset($this->view_data['project_has_invoices'])){$this->view_data['project_has_invoices'] = array();}
		$tasks = ProjectHasTask::count(array('conditions' => 'project_id = '.$id));
		$tasks_done = ProjectHasTask::count(array('conditions' => array('status = ? AND project_id = ?', 'done', $id)));
		$this->view_data['progress'] = $this->view_data['project']->progress;
		if($this->view_data['project']->progress_calc == 1){
			if ($tasks) {$this->view_data['progress'] = round($tasks_done/$tasks*100);}
			$attr = array('progress' => $this->view_data['progress']);
			$this->view_data['project']->update_attributes($attr);
		}
		// $projecthasworker = ProjectHasWorker::all(array('conditions' => array('user_id = ? AND project_id = ?', $this->user->id, $id)));
		$projectcompanyid = $this->view_data['project']->company_id;
		$usercompanyid = $this->user->company_id;
		if(!($projectstable->company_id === $usercompanyid) && $this->user->admin != 1){
				$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_no_access_error'));
				redirect('projects');
		}
		$tracking = $this->view_data['project']->time_spent;
		if(!empty($this->view_data['project']->tracking)){ $tracking=(time()-$this->view_data['project']->tracking)+$this->view_data['project']->time_spent; }

		$this->view_data['time_spent_from_today'] = time() - $this->view_data['project']->time_spent;
		$tracking = floor($tracking/60);
		$tracking_hours = floor($tracking/60);
		$tracking_minutes = $tracking-($tracking_hours*60);

		$this->view_data['time_spent'] = $tracking_hours." ".$this->lang->line('application_hours')." ".$tracking_minutes." ".$this->lang->line('application_minutes');
		$this->view_data['time_spent_counter'] = sprintf("%02s", $tracking_hours).":".sprintf("%02s", $tracking_minutes);

		$this->view_data['production_entries'] = Production::count(array('conditions' => array('pid = ?', $id)));

		$this->view_data['production'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$projectstable->company_id);

		$this->view_data['production_by_received'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$projectstable->company_id.' AND YEAR(app_date_received) = '.$this->session->userdata("year_to_view") );

		$this->view_data['production_by_submit'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$projectstable->company_id.' AND YEAR(production_submitted) = '.$this->session->userdata("year_to_view") );

		$this->view_data['production_by_year'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$projectstable->company_id.' AND YEAR(prem_paid_month) = '.$this->session->userdata("year_to_view") );

		$this->view_data['selected_year'] = $this->session->userdata("year_to_view");

		$this->load->helper('custom');
		$this->content_view = 'projects/view';

	}
	function tasks($id = FALSE, $condition = FALSE, $task_id = FALSE)
	{
		$this->view_data['submenu'] = array(
								$this->lang->line('application_back') => 'projects',
								$this->lang->line('application_overview') => 'projects/view/'.$id,
						 		);
		switch ($condition) {
			case 'add':
				$this->content_view = 'projects/_tasks';
				if($_POST){
					unset($_POST['send']);
					unset($_POST['files']);
					$description = $_POST['description'];
					$_POST = array_map('htmlspecialchars', $_POST);
					$_POST['description'] = $description;
					$_POST['project_id'] = $id;
					$task = ProjectHasTask::create($_POST);
		       		if(!$task){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_task_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_task_success'));}
					redirect('projects/view/'.$id);
				}else
				{
					$this->theme_view = 'modal';
					$this->view_data['project'] = Project::find($id);
					$this->view_data['title'] = $this->lang->line('application_add_task');
					$this->view_data['form_action'] = 'projects/tasks/'.$id.'/add';
					$this->content_view = 'projects/_tasks';
				}
				break;
			case 'update':
				$this->content_view = 'projects/_tasks';
				$this->view_data['task'] = ProjectHasTask::find($task_id);
				if($_POST){
					unset($_POST['send']);
					unset($_POST['files']);
					if(!isset($_POST['public'])){$_POST['public'] = 0;}
					$description = $_POST['description'];
					$_POST = array_map('htmlspecialchars', $_POST);
					$_POST['description'] = $description;
					$task_id = $_POST['id'];
					$task = ProjectHasTask::find($task_id);
					$task->update_attributes($_POST);
		       		if(!$task){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_task_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_task_success'));}
					redirect('projects/view/'.$id);
				}else
				{
					$this->theme_view = 'modal';
					$this->view_data['project'] = Project::find($id);
					$this->view_data['title'] = $this->lang->line('application_edit_task');
					$this->view_data['form_action'] = 'projects/tasks/'.$id.'/update/'.$task_id;
					$this->content_view = 'projects/_tasks';
				}
				break;
			case 'check':
					$task = ProjectHasTask::find($task_id);
					if ($task->status == 'done'){$task->status = 'open';}else{$task->status = 'done';}
					$task->save();
					$project = Project::find($id);
					$tasks = ProjectHasTask::count(array('conditions' => 'project_id = '.$id));
					$tasks_done = ProjectHasTask::count(array('conditions' => array('status = ? AND project_id = ?', 'done', $id)));
					if($project->progress_calc == 1){
						if ($tasks) {$progress = round($tasks_done/$tasks*100);}
						$attr = array('progress' => $progress);
						$project->update_attributes($attr);
					}
		       		if(!$task){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_task_error'));}
		       		$this->theme_view = 'ajax';
		       		$this->content_view = 'projects';
				break;
			case 'delete':
					$task = ProjectHasTask::find($task_id);
					$task->delete();
		       		if(!$task){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_task_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_task_success'));}
					redirect('projects/view/'.$id);
				break;
			default:
				$this->view_data['project'] = Project::find($id);
				$this->content_view = 'projects/tasks';
				break;
		}

	}

	//Production Entry******************************************************************************************************
	function productionentry($id = FALSE, $condition = FALSE, $production_id = FALSE)
	{
		$this->view_data['submenu'] = array(
								$this->lang->line('application_back') => 'projects',
								$this->lang->line('application_overview') => 'projects/view/'.$id,
						 		);
		switch ($condition) {
			case 'add':
				$this->content_view = 'projects/_productions';
				if($_POST){
					unset($_POST['send']);
					unset($_POST['files']);
					//$description = $_POST['description'];
					$_POST = array_map('htmlspecialchars', $_POST);
					//$_POST['description'] = $description;
					$_POST['pid'] = $id;
					//Strip commas from numbers
						function stripNums($n) {
							return $n = strtr($n, array('.' => '' , ',' => ''));
						}
						$_POST = array_map('stripNums', $_POST);
					$production = Production::create($_POST);
		       		if(!$production){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_task_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_task_success'));}
					redirect('projects/view/'.$id);
					$this->session->set_userdata('refer_from', '');
				}else
				{
					$refer_from = $this->session->userdata('refer_from');
					if ($refer_from == 'clientpage'){
						$this->theme_view = 'modal_to_modal';
					}else {
						$newdata = array(
						                   'last_client'     => '',
						                   's_acat'          => '',
						                   's_annuity'       => '',
						                   's_life'          => '',
						                   's_other'         => '',
										   'production_type' => ''
						               );

						$this->session->set_userdata($newdata);
						$this->theme_view = 'modal';
					}

					$this->load->library('session');
					$this->view_data['last_client'] = $this->session->userdata('last_client');
					$this->view_data['s_production_type'] = $this->session->userdata('production_type');
					$this->load->helper('custom');
					$projectstable = Project::find($id);
					$this->view_data['project'] = Project::find($id);
					$this->view_data['projects'] = Project::find('all');
					$this->view_data['client'] = Client::all(array('order' => 'lastname asc', 'conditions' => array('inactive = 0 AND company_id = ?', $projectstable->company_id)));
					$this->view_data['productions'] = Production::count(array('conditions' => 'pid = '.$id));
					$this->view_data['title'] = "Add Production Entry";
					$this->view_data['form_action'] = 'projects/productionentry/'.$id.'/add';
					$this->content_view = 'projects/_productions';
				}
				break;
			case 'update':
				//$this->content_view = 'projects/_productions';

				if($_POST){
					unset($_POST['send']);
					unset($_POST['files']);
					//if(!isset($_POST['public'])){$_POST['public'] = 0;}
					//$description = $_POST['description'];
					$_POST = array_map('htmlspecialchars', $_POST);
					//$_POST['description'] = $description;
					$production_id = $_POST['id'];
					$production = Production::find($production_id);
					//Strip commas from numbers
						$_POST['production_amount'] = strtr($_POST['production_amount'], array('.' => '' , ',' => ''));
						$_POST['prem_paid'] = strtr($_POST['prem_paid'], array('.' => '' , ',' => ''));
						// This function & array map removes all periods and commas from every POST variable
						// function stripNums($n) {
						// 	return $n = strtr($n, array('.' => '' , ',' => ''));
						// }
						// $_POST = array_map('stripNums', $_POST);
					$production->update_attributes($_POST);
		       		if(!$production){$this->session->set_flashdata('message', 'error:Production Entry Not Saved :(');}
		       		else{$this->session->set_flashdata('message', 'success:Production Entry Updated!');}
					redirect('projects/view/'.$id);
				}else
				{
					$this->theme_view = 'modal';
					$this->load->helper('custom');
					$this->view_data['production'] = Production::find($production_id);
					$this->view_data['project'] = Project::find($id);
					$projectstable = Project::find($id);
					$this->view_data['projects'] = Project::find('all');
					$this->view_data['client'] = Client::all(array('order' => 'lastname asc', 'conditions' => array('inactive = 0 AND company_id = ?', $projectstable->company_id)));
					$this->view_data['productions'] = Production::count(array('conditions' => 'pid = '.$id));
					$this->view_data['title'] = "Edit Production Entry";
					$this->view_data['form_action'] = 'projects/productionentry/'.$id.'/update/'.$production_id;
					$this->content_view = 'projects/_productions';
				}
				break;
			case 'delete':
					$production = Production::find($production_id);
					$production->delete();
		       		if(!$production){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_task_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_task_success'));}
					redirect('projects/view/'.$id);
				break;
			default:
				$this->view_data['project'] = Project::find($id);
				$this->content_view = 'projects/productions';
				break;
		}

	}
	//End Production Entry******************************************************************************************************

	function notes($id = FALSE)
	{
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$project = Project::find($id);
			$project->update_attributes($_POST);
		}
		$this->theme_view = 'ajax';
	}
	function media($id = FALSE, $condition = FALSE, $media_id = FALSE)
	{
	    $this->load->helper('notification');
		$this->view_data['submenu'] = array(
								$this->lang->line('application_back') => 'projects',
								$this->lang->line('application_overview') => 'projects/view/'.$id,
						 		$this->lang->line('application_tasks') => 'projects/tasks/'.$id,
						 		$this->lang->line('application_media') => 'projects/media/'.$id,
						 		);
		switch ($condition) {
			case 'view':

				if($_POST){
					unset($_POST['send']);
					unset($_POST['_wysihtml5_mode']);
					unset($_POST['files']);
					//$_POST = array_map('htmlspecialchars', $_POST);
					$_POST['text'] = $_POST['message'];
					unset($_POST['message']);
					$_POST['project_id'] = $id;
					$_POST['media_id'] = $media_id;
					$_POST['from'] = $this->user->firstname.' '.$this->user->lastname;
					$this->view_data['project'] = Project::find_by_id($id);
					$this->view_data['media'] = ProjectHasFile::find($media_id);
					$message = Message::create($_POST);
       				if(!$message){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_message_error'));}
       				else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_message_success'));

       					foreach ($this->view_data['project']->project_has_workers as $workers){
            			    send_notification($workers->user->email, "[".$this->view_data['project']->name."] New comment", 'New comment on meida file: '.$this->view_data['media']->name.'<br><strong>'.$this->view_data['project']->name.'</strong>');
            			}
            			if(isset($this->view_data['project']->company->email)){
            			send_notification($this->view_data['project']->company->email, "[".$this->view_data['project']->name."] New comment", 'New comment on meida file: '.$this->view_data['media']->name.'<br><strong>'.$this->view_data['project']->name.'</strong>');
            			}
       				}
       				redirect('projects/media/'.$id.'/view/'.$media_id);
				}
				$this->content_view = 'projects/view_media';
				$this->view_data['media'] = ProjectHasFile::find($media_id);
				$this->view_data['form_action'] = 'projects/media/'.$id.'/view/'.$media_id;
				$this->view_data['filetype'] = explode('.', $this->view_data['media']->filename);
				$this->view_data['filetype'] = $this->view_data['filetype'][1];
				$this->view_data['backlink'] = 'projects/view/'.$id;
				break;
			case 'add':
				$this->content_view = 'projects/_media';
				$this->view_data['project'] = Project::find($id);
				if($_POST){
					$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = '*';

					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload())
						{
							$error = $this->upload->display_errors('', ' ');
							$this->session->set_flashdata('message', 'error:'.$error);
							redirect('projects/media/'.$id);
						}
						else
						{
							$data = array('upload_data' => $this->upload->data());

							$_POST['filename'] = $data['upload_data']['orig_name'];
							$_POST['savename'] = $data['upload_data']['file_name'];
							$_POST['type'] = $data['upload_data']['file_type'];
						}

					unset($_POST['send']);
					unset($_POST['userfile']);
					unset($_POST['file-name']);
					unset($_POST['files']);
					$_POST = array_map('htmlspecialchars', $_POST);
					$_POST['project_id'] = $id;
					$_POST['user_id'] = $this->user->id;
					$media = ProjectHasFile::create($_POST);
		       		if(!$media){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_media_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_media_success'));

		       		    $attributes = array('subject' => $this->lang->line('application_new_media_subject'), 'message' => '<b>'.$this->user->firstname.' '.$this->user->lastname.'</b> '.$this->lang->line('application_uploaded'). ' '.$_POST['name'], 'datetime' => time(), 'project_id' => $id, 'type' => 'media', 'user_id' => $this->user->id);
					    $activity = ProjectHasActivity::create($attributes);

    		       		foreach ($this->view_data['project']->project_has_workers as $workers){
            			    send_notification($workers->user->email, "[".$this->view_data['project']->name."] ".$this->lang->line('application_new_media_subject'), $this->lang->line('application_new_media_file_was_added').' <strong>'.$this->view_data['project']->name.'</strong>');
            			}
            			if(isset($this->view_data['project']->company->email)){
            			send_notification($this->view_data['project']->company->email, "[".$this->view_data['project']->name."] ".$this->lang->line('application_new_media_subject'), $this->lang->line('application_new_media_file_was_added').' <strong>'.$this->view_data['project']->name.'</strong>');
            			}

		       		}
					redirect('projects/view/'.$id);
				}else
				{
					$this->theme_view = 'modal';
					$this->view_data['title'] = $this->lang->line('application_add_media');
					$this->view_data['form_action'] = 'projects/media/'.$id.'/add';
					$this->content_view = 'projects/_media';
				}
				break;
			case 'update':
				$this->content_view = 'projects/_media';
				$this->view_data['media'] = ProjectHasFile::find($media_id);
				$this->view_data['project'] = Project::find($id);
				if($_POST){
					unset($_POST['send']);
					unset($_POST['_wysihtml5_mode']);
					unset($_POST['files']);
					$_POST = array_map('htmlspecialchars', $_POST);
					$media_id = $_POST['id'];
					$media = ProjectHasFile::find($media_id);
					$media->update_attributes($_POST);
		       		if(!$media){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_media_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_media_success'));}
					redirect('projects/view/'.$id);
				}else
				{
					$this->theme_view = 'modal';
					$this->view_data['title'] = $this->lang->line('application_edit_media');
					$this->view_data['form_action'] = 'projects/media/'.$id.'/update/'.$media_id;
					$this->content_view = 'projects/_media';
				}
				break;
			case 'delete':
					$media = ProjectHasFile::find($media_id);
					$media->delete();
					$this->load->database();
					$sql = "DELETE FROM messages WHERE media_id = $media_id";
					$this->db->query($sql);
		       		if(!$media){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_media_error'));}
		       		else{	unlink('./files/media/'.$media->savename);
		       				$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_media_success'));
		       			}
					redirect('projects/view/'.$id);
				break;
			default:
				$this->view_data['project'] = Project::find($id);
				$this->content_view = 'projects/view/'.$id;
				break;
		}

	}
	function deletemessage($project_id, $media_id, $id){
					$message = Message::find($id);
					if($message->from == $this->user->firstname." ".$this->user->lastname || $this->user->admin == "1"){
					$message->delete();
					}
		       		if(!$message){
		       			$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_message_error'));
		       		}
		       		else{
		       			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_message_success'));
		       		}
					redirect('projects/media/'.$project_id.'/view/'.$media_id);
	}
	function tracking($id = FALSE)
	{
		$project = Project::find($id);
		if(empty($project->tracking)){
			$project->update_attributes(array('tracking' => time()));

		}else{
		$timeDiff=time()-$project->tracking;
		$project->update_attributes(array('tracking' => '', 'time_spent' => $project->time_spent+$timeDiff));
		}
		redirect('projects/view/'.$id);

	}
	function sticky($id = FALSE)
	{
		$project = Project::find($id);
		if($project->sticky == 0){
			$project->update_attributes(array('sticky' => '1'));
       		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_make_sticky_success'));

		}else{
		$project->update_attributes(array('sticky' => '0'));
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_remove_sticky_success'));
		}
		redirect('projects/view/'.$id);

	}
	function download($media_id = FALSE){
		$media = ProjectHasFile::find($media_id);
		$media->download_counter = $media->download_counter+1;
		$media->save();
		header('Content-Description: File Transfer');
        header('Content-Type: '.$media->type);
        header('Content-disposition: attachment; filename='.$media->filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize('./files/media/'.$media->savename));
        readfile('./files/media/'.$media->savename);
	}
	function activity($id = FALSE, $condition = FALSE, $activityID = FALSE)
	{
	    $this->load->helper('notification');
		$project = Project::find_by_id($id);
		//$activity = ProjectHasAktivity::find_by_id($activityID);
		switch ($condition) {
			case 'add':
				if($_POST){
					unset($_POST['send']);
					$_POST['subject'] = htmlspecialchars($_POST['subject']);
					$_POST['project_id'] = $id;
					$_POST['user_id'] = $this->user->id;
					$_POST['type'] = "comment";
					unset($_POST['files']);
					$_POST['datetime'] = time();
					$activity = ProjectHasActivity::create($_POST);
		       		if(!$activity){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_error'));}
		       		else{
		       		    $this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_success'));
		       		    foreach ($project->project_has_workers as $workers){
            			    send_notification($workers->user->email, "[".$project->name."] ".$_POST['subject'], $_POST['message'].'<br><strong>'.$project->name.'</strong>');
            			}
            			if(isset($project->client->email)){
            			send_notification($project->company->email, "[".$project->name."] ".$_POST['subject'], $_POST['message'].'<br><strong>'.$project->name.'</strong>');
            			}
		       		}
					//redirect('projects/view/'.$id);

				}
				break;
			case 'update':

				break;
			case 'delete':

				break;
		}

	}

}
