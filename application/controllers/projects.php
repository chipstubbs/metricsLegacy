<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $access = FALSE;
        if ($this->client) {
            if ($this->input->cookie('fc2_link') != "") {
                $link = $this->input->cookie('fc2_link');
                $link = str_replace("/tickets/", "/ctickets/", $link);
                redirect($link);
            } else {
                redirect('cprojects');
            }

        } elseif ($this->user) {
            foreach ($this->view_data['menu'] as $key => $value) {
                if ($value->link == "projects") {
                    $access = TRUE;
                }
            }
            if (!$access) {
                redirect('login');
            }
        } else {
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
        // $this->view_data['companies'] = Company::find('all', array('conditions' => array('inactive=?', '0')));
        $this->content_view = 'projects/all';
        // $this->view_data['projects_assigned_to_me'] = ProjectHasWorker::find_by_sql('select count(distinct(projects.id)) AS "amount" FROM projects, project_has_workers WHERE projects.progress != "100" AND (projects.id = project_has_workers.project_id AND project_has_workers.user_id = "' . $this->user->id . '") ');
        // $this->view_data['tasks_assigned_to_me'] = ProjectHasTask::count(array('conditions' => 'user_id = ' . $this->user->id . ' and status = "open"'));

        // $now = time();
        // $beginning_of_week = strtotime('last Monday', $now); // BEGINNING of the week
        // $end_of_week = strtotime('next Sunday', $now) + 86400; // END of the last day of the week
        // $this->view_data['projects_opened_this_week'] = Project::find_by_sql('select count(id) AS "amount", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%w") AS "date_day", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%Y-%m-%d") AS "date_formatted" from projects where datetime >= "' . $beginning_of_week . '" AND datetime <= "' . $end_of_week . '" ');

        $this->load->helper('custom');
        $this->load->helper('format');
        //Used to pull averages
        // $productionTable = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $this->user->company_id . ' AND YEAR(prem_paid_month) = ' . $this->session->userdata("year_to_view"));

        $avgFields = "production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount";
        $productionTable = Production::getProduction($avgFields, $this->user->company_id, $this->session->userdata("year_to_view"));

        // $this->view_data['project'] = Project::find('all', array('conditions' => array('company_id=?', $this->user->company_id)));
        $this->view_data['project'] = Project::find_by_sql('SELECT projects.name, projects.id, projects.company_id FROM projects WHERE company_id = ' . $this->user->company_id .' ');


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
            'wiseradvisor' => 'WiserAdvisor',
            'other1' => 'Other 1',
            'other2' => 'Other 2',
            'other3' => 'Other 3',
            'workshop1' => 'Workshop 1',
            'workshop2' => 'Workshop 2',
            'workshop3' => 'Workshop 3',
            'website' => 'Website',
            'paladin' => 'Paladin',
            'delwebb' => 'Del Webb', 
            'tv' => 'TV',
            'god' => 'God',
            'ira' => 'IRA',
            'taxes' => 'Tax Workshop',
            'risradio' => 'RIS Radio',
            'ristv' => 'RIS TV',
            'webinar' => 'Webinar'
        );
        //
        //Responses & Response Ratios
        //
        //$eventprojectsoverall = Project::find('all', array( 'conditions' => array( 'name = 6 AND company_id=? ORDER BY event', $this->user->company_id ) ));
        //If we use the switch for referral we'd use this query. But we are just grabbing all events. $eventprojects = Project::find_by_sql('SELECT * FROM projects JOIN clients ON projects.id = clients.event_id WHERE projects.name = 6 AND projects.company_id = '.$this->user->company_id.' AND clients.referral = 0');
        //$referral_eventprojects = Project::find_by_sql('SELECT * FROM projects LEFT JOIN clients ON projects.id = clients.event_id WHERE projects.name = 6 AND projects.company_id = '.$this->user->company_id.' AND clients.referral = 1');
        // $eventprojects = Project::find('all', array( 'conditions' => array( 'name = 6 AND company_id=? ORDER BY event', $this->user->company_id ) ));
        //Event projects that filter by year
        $eventprojects = Project::find('all', array('conditions' => array('(name = 6 AND company_id=? ) AND YEAR(STR_TO_DATE(event_date, "%m/%d/%Y")) = ' . $this->session->userdata("year_to_view") . ' ORDER BY event', $this->user->company_id)));
        $this->view_data['eventprojects'] = $eventprojects;
        $clientstable = Client::all(array('conditions' => 'inactive = 0 and company_id =' . $this->user->company_id));
        $avg_nums = array();
        $avg_ratio = array();
        $AVG = new stdClass();
        $AVG_referrals = new stdClass();
        $AVG_overall = new stdClass();
        foreach ($eventoptions as $key => $value) {
            $count = $count_referrals = $count_overall = $sum = $mailers = $BU = $response_ratio = $attendance_ratio = $appointments = $appointment_ratio = $keptappointments = $appointment_kept_ratio = $prospectsclosed = $closingratio = $eventcost = $mailercost = $adcost = $othercost = $totaleventcost = $acatproduction = $annuityproduction = $annuitycom = $otherproduction = $othercom = $annuitypercent = $avgtoannuity = $has_assets = 0;
            //For non-referrals
            foreach ($eventprojects as $ev) {
                if ($ev->event == $key) {
                    $count++;
                    // If event is platinum referral then $sum is referral_attendee++
                    if ($ev->event === 'platinumreferral') {
                        $sum += $ev->referral_attendee;
                        $mailers += $ev->referral_response;
                    } else {
                        $sum += $ev->total_responses;
                        $mailers += $ev->number_mailers;
                    }

                    $BU += $ev->bu_attended;
                    foreach ($clientstable as $ct) {
                        ($ct->sched_appt_check > 0 && $ct->event_id == $ev->id) ? $appointments++ : '';
                        ($ct->kept_appt > 0 && $ct->event_id == $ev->id) ? $keptappointments++ : '';
                        ($ct->has_assets > 0 && $ct->event_id == $ev->id) ? $has_assets++ : '';
                        if (($ct->acat > 0 || $ct->aum > 0 || $ct->annuity_app > 0 || $ct->life_submitted > 0 || $ct->other > 0) && $ct->event_id == $ev->id) {
                            $prospectsclosed++;
                        }
                    }
                    $eventcost += $ev->total_event_cost;
                    $mailercost += $ev->mailers_cost;
                    $adcost += $ev->ad_cost;
                    $othercost += $ev->other_invite_cost;

                    foreach ($productionTable as &$entry) {
                        if ($entry->event_id == $ev->id) {
                            // $prospectsclosed++;
                            if ($entry->production_type === 'acat') {
                                $acatproduction += ($entry->prem_paid / 100);
                            } else if ($entry->production_type === 'annuity') {
                                $annuityproduction += $entry->prem_paid / 100;
                                $annuitycom += ($entry->comp_agent_percent / 100) * ($entry->prem_paid / 100);
                            } else if ($entry->production_type === 'other' || $entry->production_type === 'life') {
                                $otherproduction += $entry->prem_paid / 100;
                                $othercom += ($entry->comp_agent_percent / 100) * ($entry->prem_paid / 100);
                            } else if ($entry->production_type === 'aum') {
                            }
                        }
                    }
                    if (!empty($acatproduction) && !empty($annuityproduction)) {
                        $annuitypercent = ($annuityproduction / $acatproduction);
                    }
                }
            }
            if ($count > 0) {
                // If event is platinum referral then response_ratio is 'referral_attendee / referral_response'
                (!empty($mailers) && !empty($sum)) ? $response_ratio = round((float)($sum / $mailers) * 100, 1) . '%' : $response_ratio = '0%';
                (!empty($sum) && !empty($BU)) ? $attendance_ratio = round((float)($BU / $sum) * 100, 1) . '%' : $attendance_ratio = '0%';
                (!empty($BU) && !empty($appointments)) ? $appointment_ratio = round((float)($appointments / $BU) * 100, 1) . '%' : $appointment_ratio = '0%';
                (!empty($keptappointments) && !empty($appointments)) ? $appointment_kept_ratio = round((float)($keptappointments / $appointments) * 100, 1) . '%' : $appointment_kept_ratio = '0%';
                //changed this to prospectsclosed/has_assets as requested by andrew (!empty($prospectsclosed) && !empty($keptappointments)) ? $closingratio = round((float)($prospectsclosed / $keptappointments) * 100, 1) . '%' : $closingratio = '0%';
                (!empty($prospectsclosed) && !empty($has_assets)) ? $closingratio = round((float)($prospectsclosed / $has_assets) * 100, 1) . '%' : $closingratio = '0%';
                $totaleventcost = (($eventcost + $mailercost + $adcost + $othercost) / 100) / $count;
                $grossprofit = ($othercom + $annuitycom - (($eventcost + $mailercost + $adcost + $othercost) / 100)) / $count;
                (!empty($annuitypercent)) ? $avgtoannuity = round((float)($annuitypercent) * 100, 1) . '%' : $avgtoannuity = '0%';

                // if ($this->user->admin != 1 && !empty($key)) {
                if (!empty($key)) {
                    $AVG->$key = (object)[
                        'avg_response' => round(($sum / $count), 1),
                        'response_ratio' => $response_ratio,
                        'avg_buying_units' => round(($BU / $count), 1),
                        'attendance_ratio' => $attendance_ratio,
                        'avg_appointments' => round(($appointments / $count), 1),
                        'appointment_ratio' => $appointment_ratio,
                        'avg_appointment_kept' => round(($keptappointments / $count), 1),
                        'appointment_kept_ratio' => $appointment_kept_ratio,
                        'prospectsclosed' => round(($prospectsclosed / $count), 1),
                        'closingratio' => $closingratio,
                        'totaleventcost' => $totaleventcost,
                        'grossprofit' => $grossprofit,
                        'annuityavg' => ($annuityproduction / $count),
                        'avgtoannuity' => $avgtoannuity,
                        'totalannuity' => $annuityproduction,
                        'counter' => $count,
                        'pclosed' => $prospectsclosed,
                        'ka'      => $keptappointments
                    ];
                } else {
                    $AVG = (object)[];
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
        $number_of_clients = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id =' . $this->user->company_id . ' AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ')'));

        $client_sched_appt = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id =' . $this->user->company_id . ' AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ') AND sched_appt_check = 1'));

        $client_kept_appts = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id =' . $this->user->company_id . ' AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ') AND kept_appt = 1'));

        $client_has_assets = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id =' . $this->user->company_id . ' AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ') AND has_assets = 1'));

        $clients_closed = Client::count(array('select' => 'DISTINCT clients.id ', 'joins' => 'RIGHT JOIN production ON clients.id = production.client_id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id =' . $this->user->company_id . ' AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ')')); //Count clients with some sort of business started

        $client_production = Production::find_by_sql('SELECT * FROM production LEFT JOIN clients ON production.client_id = clients.id WHERE (clients.event_id = 3 AND clients.inactive = 0) AND (clients.company_id = ' . $this->user->company_id . ' AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ')');

        foreach ($client_production as &$cp) {
            if ($cp->production_type === 'acat') {
                $client_acatproduction += ($cp->prem_paid / 100);
            } else if ($cp->production_type === 'annuity') {
                $client_annuityproduction += $cp->prem_paid / 100;
                $client_annuitycom += ($cp->comp_agent_percent / 100) * ($cp->prem_paid / 100);
            } else if ($cp->production_type === 'other' || $cp->production_type === 'life') {
                $client_othercom += ($cp->comp_agent_percent / 100) * ($cp->prem_paid / 100);
            } else if ($cp->production_type === 'aum') {
            }
        }
//Referral Averages - Unsolicited
//////////////////////////////////////////////////////
        $number_of_referrals = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 1) AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view"))); //Count Referrals

        $referral_sched_appt = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND clients.event_id = 1 AND (clients.sched_appt_check = 1 AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ')')); //Count Referral scheduled appointments

        $referral_kept_appts = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 1) AND (clients.kept_appt = 1 AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ')')); //Count kept appointments

        $referral_has_assets = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.has_assets = 1 AND clients.sched_appt_check = 1) AND (clients.event_id = 1) AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view"))); //Count referrals with assets and scheduled appointments

        $referrals_closed = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 1 AND clients.has_assets = 1) AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view"))); //Count referrals with some sort of business started

        $referral_production = Production::find_by_sql('SELECT * FROM production LEFT JOIN clients ON production.client_id = clients.id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 1) AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view"));

        foreach ($referral_production as &$rp) {
            if ($rp->production_type === 'acat') {
                $referral_acatproduction += ($rp->prem_paid / 100);
            } else if ($rp->production_type === 'annuity') {
                $referral_annuityproduction += $rp->prem_paid / 100;
                $referral_annuitycom += ($rp->comp_agent_percent / 100) * ($rp->prem_paid / 100);
            } else if ($rp->production_type === 'other' || $rp->production_type === 'life') {
                $referral_othercom += ($rp->comp_agent_percent / 100) * ($rp->prem_paid / 100);
            } else if ($rp->production_type === 'aum') {
            }
        }
//End unsolicited referral averages


//Referral Averages - Solicited
//////////////////////////////////////////////////////
        $solicited_number_of_referrals = Client::all(array('select' => 'DISTINCT clients.id', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 2 AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ')')); //Count Referrals

        $solicited_referral_sched_appt = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 2) AND (clients.sched_appt_check = 1 AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ') ')); //Count Referral scheduled appointments

        $solicited_referral_kept_appts = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 2) AND (clients.kept_appt = 1 AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view") . ')')); //Count kept appointments

        $solicited_referral_has_assets = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.has_assets = 1 AND clients.sched_appt_check = 1) AND (clients.event_id = 2) AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view"))); //Count referrals with assets and scheduled appointments

        $solicited_referrals_closed = Client::all(array('select' => 'DISTINCT clients.id ', 'joins' => 'INNER JOIN production ON clients.id = production.client_id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 2 AND clients.has_assets = 1) AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view"))); //Count referrals with some sort of business started

        $solicited_referral_production = Production::find_by_sql('SELECT * FROM production LEFT JOIN clients ON production.client_id = clients.id WHERE (clients.inactive = 0 AND clients.company_id = ' . $this->user->company_id . ') AND (clients.event_id = 2) AND YEAR(production.prem_paid_month) = ' . $this->session->userdata("year_to_view"));

        foreach ($solicited_referral_production as &$srp) {
            if ($srp->production_type === 'acat') {
                $solicited_referral_acatproduction += ($srp->prem_paid / 100);
            } else if ($srp->production_type === 'annuity') {
                $solicited_referral_annuityproduction += $srp->prem_paid / 100;
                $solicited_referral_annuitycom += ($srp->comp_agent_percent / 100) * ($srp->prem_paid / 100);
            } else if ($srp->production_type === 'other' || $srp->production_type === 'life') {
                $solicited_referral_othercom += ($srp->comp_agent_percent / 100) * ($srp->prem_paid / 100);
            } else if ($srp->production_type === 'aum') {
            }
        }
//End solicited referral averages

        $this->view_data["num_clients"] = $number_of_clients;
        // $this->view_data["num_referrals"]         = $number_of_referrals; //Replace with num_solicited & num_unsolicited
        $this->view_data["client_appts"] = $client_sched_appt;
        $this->view_data["client_appts_ratio"] = (!empty($number_of_clients)) ? round((float)($client_sched_appt / $number_of_clients) * 100, 1) . '%' : '0%';
        $this->view_data["client_kept_appts"] = $client_kept_appts;
        $this->view_data["client_kept_ratio"] = (!empty($client_sched_appt)) ? round((float)($client_kept_appts / $client_sched_appt) * 100, 1) . '%' : '0%';
        $this->view_data["client_has_assets"] = $client_has_assets;
        $this->view_data["client_closed"] = $clients_closed;
        $this->view_data["client_close_ratio"] = (!empty($client_has_assets) ? round((float)($clients_closed / $client_has_assets) * 100, 1) . '%' : '0%');
        $this->view_data["client_gross"] = $client_annuitycom + $client_othercom;
        $this->view_data["client_avgcase"] = (!empty($number_of_clients) ? ( $client_annuityproduction / $number_of_clients ) : '0');
        $this->view_data["client_annuity_avg"] = (!empty($client_acatproduction)) ? round((float)($client_annuityproduction / $client_acatproduction) * 100, 1) . '%' : '0%';
        $this->view_data["client_annuity"] = $client_annuityproduction;

        //Unsolicited Referrals
        $this->view_data["num_unsolicited"] = count($number_of_referrals);
        $this->view_data["referral_appts"] = count($referral_sched_appt);
        $this->view_data["referral_appts_ratio"] = (!empty($number_of_referrals)) ? round((float)(count($referral_sched_appt) / count($number_of_referrals)) * 100, 1) . '%' : '0%';
        $this->view_data["referral_kept_appts"] = count($referral_kept_appts);
        $this->view_data["referral_kept_ratio"] = (!empty($referral_sched_appt)) ? round((float)(count($referral_kept_appts) / count($referral_sched_appt)) * 100, 1) . '%' : '0%';
        $this->view_data["referral_has_assets"] = count($referral_has_assets);
        $this->view_data["referral_close_ratio"] = (!empty($referral_has_assets) ? round((float)(count($referrals_closed) / count($referral_has_assets)) * 100, 1) . '%' : '0%');
        $this->view_data["referral_closed"] = count($referrals_closed);
        $this->view_data["referral_gross"] = $referral_annuitycom + $referral_othercom;
        $this->view_data["referral_avgcase"] = (!empty($number_of_referrals) ? ( $referral_annuityproduction / count($number_of_referrals) ) : '0');
        $this->view_data["referral_annuity_avg"] = (!empty($referral_acatproduction)) ? round((float)($referral_annuityproduction / $referral_acatproduction) * 100, 1) . '%' : '0%';
        $this->view_data["referral_annuity"] = $referral_annuityproduction;

        //Solicited Referrals
        $this->view_data["num_solicited"] = count($solicited_number_of_referrals);
        $this->view_data["solicited_referral_appts"] = count($solicited_referral_sched_appt);
        $this->view_data["solicited_referral_appts_ratio"] = (!empty($solicited_number_of_referrals)) ? round((float)(count($solicited_referral_sched_appt) / count($solicited_number_of_referrals)) * 100, 1) . '%' : '0%';
        $this->view_data["solicited_referral_kept_appts"] = count($solicited_referral_kept_appts);
        $this->view_data["solicited_referral_kept_ratio"] = (!empty($solicited_referral_sched_appt)) ? round((float)(count($solicited_referral_kept_appts) / count($solicited_referral_sched_appt)) * 100, 1) . '%' : '0%';
        $this->view_data["solicited_referral_has_assets"] = count($solicited_referral_has_assets);
        $this->view_data["solicited_referral_close_ratio"] = (!empty($solicited_referral_has_assets) ? round((float)(count($solicited_referrals_closed) / count($solicited_referral_has_assets)) * 100, 1) . '%' : '0%');
        $this->view_data["solicited_referral_closed"] = count($solicited_referrals_closed);
        $this->view_data["solicited_referral_gross"] = $solicited_referral_annuitycom + $solicited_referral_othercom;
        $this->view_data["solicited_avgcase"] = (!empty($solicited_number_of_referrals) ? ( $solicited_referral_annuityproduction / count($solicited_number_of_referrals) ) : '0');
        $this->view_data["solicited_referral_annuity_avg"] = (!empty($solicited_referral_acatproduction)) ? round((float)($solicited_referral_annuityproduction / $solicited_referral_acatproduction) * 100, 1) . '%' : '0%';
        $this->view_data["solicited_referral_annuity"] = $solicited_referral_annuityproduction;

        $this->view_data['responses'] = $avg_nums;
        $this->view_data['responses_ratio'] = $avg_ratio;
        $this->view_data["production"] = $productionTable;
        // $this->view_data["production"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $this->user->company_id);
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

        $this->view_data['projects_assigned_to_me'] = ProjectHasWorker::find_by_sql('select count(distinct(projects.id)) AS "amount" FROM projects, project_has_workers WHERE projects.progress != "100" AND (projects.id = project_has_workers.project_id AND project_has_workers.user_id = "' . $this->user->id . '") ');
        $this->view_data['tasks_assigned_to_me'] = ProjectHasTask::count(array('conditions' => 'user_id = ' . $this->user->id . ' and status = "open"'));

        $now = time();
        $beginning_of_week = strtotime('last Monday', $now); // BEGINNING of the week
        $end_of_week = strtotime('next Sunday', $now) + 86400; // END of the last day of the week
        $this->view_data['projects_opened_this_week'] = Project::find_by_sql('select count(id) AS "amount", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%w") AS "date_day", DATE_FORMAT(FROM_UNIXTIME(`datetime`), "%Y-%m-%d") AS "date_formatted" from projects where datetime >= "' . $beginning_of_week . '" AND datetime <= "' . $end_of_week . '" ');

    }

    function create()
    {
        if ($_POST) {
            unset($_POST['send']);
            $_POST['datetime'] = time();
            $_POST = array_map('htmlspecialchars', $_POST);
            unset($_POST['files']);
            //Strip commas from numbers
            $_POST['mailers_cost'] = strtr($_POST['mailers_cost'], array('.' => '', ',' => ''));
            $_POST['ad_cost'] = strtr($_POST['ad_cost'], array('.' => '', ',' => ''));
            $_POST['other_invite_cost'] = strtr($_POST['other_invite_cost'], array('.' => '', ',' => ''));
            $_POST['total_event_cost'] = strtr($_POST['total_event_cost'], array('.' => '', ',' => ''));
            $project = Project::create($_POST);
            $new_project_reference = $_POST['reference'] + 1;
            $project_reference = Setting::first();

            $project_reference->update_attributes(array('project_reference' => $new_project_reference));
            if (!$project) {
                $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_create_project_error'));
            } else {
                $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_create_project_success'));
                $project_last = Project::last();
                $sql = "INSERT INTO `project_has_workers` (`project_id`, `user_id`) VALUES (" . $project_last->id . ", " . $this->user->id . ")";
                $query = $this->db->query($sql);
            }
            redirect('projects/view/' . $project_last->id);
        } else {
            $this->view_data['companies'] = Company::find('all', array('conditions' => array('inactive=?', '0'), 'order' => 'name'));
            $this->view_data['next_reference'] = Project::last();
            $this->theme_view = 'modal';
            $this->view_data['title'] = 'Add Event';
            $this->view_data['form_action'] = 'projects/create';
            $this->content_view = 'projects/_project';
        }
    }

    function update($id = FALSE)
    {
        if ($_POST) {
            unset($_POST['send']);
            $id = $_POST['id'];
            unset($_POST['files']);
            $_POST = array_map('htmlspecialchars', $_POST);
            if (!isset($_POST["progress_calc"])) {
                $_POST["progress_calc"] = 0;
            }
            $project = Project::find($id);
            //Strip commas from numbers
            $_POST['mailers_cost'] = strtr($_POST['mailers_cost'], array('.' => '', ',' => ''));
            $_POST['ad_cost'] = strtr($_POST['ad_cost'], array('.' => '', ',' => ''));
            $_POST['other_invite_cost'] = strtr($_POST['other_invite_cost'], array('.' => '', ',' => ''));
            $_POST['total_event_cost'] = strtr($_POST['total_event_cost'], array('.' => '', ',' => ''));

            $project->update_attributes($_POST);
            if (!$project) {
                $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_project_error'));
            } else {
                $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_project_success'));
            }
            redirect('projects/view/' . $id);
        } else {
            $this->view_data['companies'] = Company::find('all', array('conditions' => array('inactive=?', '0')));
            $this->view_data['project'] = Project::find($id);
            $this->theme_view = 'modal';
            if ($this->user->admin === '1') {
                $this->view_data['isadmin'] = TRUE;
            } else {
                $this->view_data['isadmin'] = FALSE;
            }
            $this->load->helper('format');
            $this->view_data['title'] = 'Edit Event';
            $this->view_data['form_action'] = 'projects/update';
            $this->content_view = 'projects/_project';
        }
    }

    function assign($id = FALSE)
    {
        $this->load->helper('notification');
        if ($_POST) {
            unset($_POST['send']);
            $id = addslashes($_POST['id']);
            $project = Project::find_by_id($id);
            $sql = "SELECT user_id FROM project_has_workers WHERE project_id=" . $id;
            $query = $this->db->query($sql);
            $query = $query->result_array();
            foreach ($query as $k => $a) {
                if (is_array($a)) {
                    $query[$k] = $a['user_id'];
                }
            }

            $added = array_diff($_POST["user_id"], $query);
            $removed = array_diff($query, $_POST["user_id"]);

            foreach ($added as $value) {
                $value = htmlspecialchars(addslashes($value));
                $sql = "INSERT INTO `project_has_workers` (`project_id`, `user_id`) VALUES (" . $id . ", " . $value . ")";
                $query = $this->db->query($sql);
                $receiver = User::find_by_id($value);
                send_notification($receiver->email, $this->lang->line('application_notification_project_assign_subject'), $this->lang->line('application_notification_project_assign') . '<br><strong>' . $project->name . '</strong>');
            }

            foreach ($removed as $value) {
                $sql = "DELETE FROM `project_has_workers` WHERE user_id = " . $value . " AND project_id=" . $id;
                $query = $this->db->query($sql);
                //$receiver = User::find_by_id($value);
            }

            if (!$query) {
                $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_project_error'));
            } else {
                $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_project_success'));
            }
            redirect('projects/view/' . $id);
        } else {
            $this->view_data['users'] = User::find('all', array('conditions' => array('status=?', 'active')));
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
        $sql = 'DELETE FROM project_has_tasks WHERE project_id = "' . $id . '"';
        $this->db->query($sql);
        $this->content_view = 'projects/all';
        if (!$project) {
            $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_delete_project_error'));
        } else {
            $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_delete_project_success'));
        }
        if (isset($view)) {
            redirect('projects/view/' . $id);
        } else {
            redirect('projects');
        }
    }

    // function timer_reset($id = FALSE)
    // {
    //     $project = Project::find($id);
    //     $attr = array('time_spent' => '0');
    //     $project->update_attributes($attr);
    //     $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_timer_reset'));
    //     redirect('projects/view/' . $id);
    // }

    function timer_set($id = FALSE)
    {
        if ($_POST) {
            $project = Project::find_by_id($_POST['id']);
            $hours = $_POST['hours'];
            $minutes = $_POST['minutes'];
            $timespent = ($hours * 60 * 60) + ($minutes * 60);
            $attr = array('time_spent' => $timespent);
            $project->update_attributes($attr);
            $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_timer_set'));
            redirect('projects/view/' . $_POST['id']);
        } else {
            $this->view_data['project'] = Project::find($id);
            $this->theme_view = 'modal';
            $this->view_data['title'] = $this->lang->line('application_timer_set');
            $this->view_data['form_action'] = 'projects/timer_set';
            $this->content_view = 'projects/_timer';
        }
    }

    function view($id = FALSE)
    {
        $this->load->helper('custom');
        $projectstable = Project::find($id);

        $page = getViewFilename($projectstable->name);

        $this->view_data['submenu'] = array();
        $this->load->library('session');

        $referralclients = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 and event_id = ?', $id)));
        $this->view_data['all_clients'] = Client::all(array('conditions' => array('inactive = 0 AND company_id = ?', $projectstable->company_id)));
        $this->view_data['project'] = $projectstable;
        //$this->view_data['projects'] = Project::find('all');
        $this->view_data['projects'] = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
        if ($projectstable->event === 'platinumreferral') {
            $this->view_data['product'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.acat, clients.client_prospect FROM production JOIN clients ON production.client_id = clients.id WHERE clients.referral = 1 AND clients.event_id = ' . $id);
            //$this->view_data['clients'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND event_id = ?', $id)));
            $this->view_data['clientslist'] = Client::all(array('conditions' => array('inactive = 0 AND event_id = ?', $id)));
            $this->view_data['scheduled_appts'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND sched_appt_check = ? and event_id = ?', '1', $id)));
            $this->view_data['first_appts'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND sched_appt >= ? and event_id = ?', '1', $id)));
            $this->view_data['kept_appts'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND kept_appt = ? and event_id = ?', '1', $id)));
            $this->view_data['closed_appts'] = Client::count(array('conditions' => array('(acat = 1 OR aum = 1 OR annuity_app = 1 OR life_submitted = 1 OR other = 1) AND referral = 1 AND inactive = 0 AND event_id = ?', $id)));
            $this->view_data['has_assets'] = Client::count(array('conditions' => array('inactive = 0 AND referral = 1 AND has_assets = 1 AND event_id = ?', $id)));
            $this->view_data['annuity'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND annuity_paid = 1 AND event_id = ?', $id)));
            $this->view_data['acat'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND acat = 1 AND event_id = ?', $id)));
            $this->view_data['other'] = Client::all(array('conditions' => array('inactive = 0 AND referral = 1 AND other = 1 AND event_id = ?', $id)));
        } else {
            $this->view_data['product'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.acat, clients.client_prospect FROM production JOIN clients ON production.client_id = clients.id WHERE clients.event_id = ' . $id);
            //$this->view_data['clients'] = Client::all(array('conditions' => array('inactive = 0 AND event_id = ?', $id)));
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
        $this->view_data['hot_prospect'] = Client::all(array('conditions' => array('inactive = 0 AND hot_prospect = 1 and company_id = ?', $projectstable->company_id)));
        $this->view_data['company'] = Company::find($projectstable->company_id);
        $this->view_data['hot_client'] = Client::all(array('conditions' => array('inactive = 0 AND hot_client = 1 and company_id = ?', $projectstable->company_id)));

        // $this->view_data['project_has_invoices'] = Invoice::all(array('conditions' => array('project_id = ?', $id)));
        // if (!isset($this->view_data['project_has_invoices'])) {
        //     $this->view_data['project_has_invoices'] = array();
        // }
        // $tasks = ProjectHasTask::count(array('conditions' => 'project_id = ' . $id));
        // $tasks_done = ProjectHasTask::count(array('conditions' => array('status = ? AND project_id = ?', 'done', $id)));
        // $this->view_data['progress'] = $this->view_data['project']->progress;
        // if ($this->view_data['project']->progress_calc == 1) {
        //     if ($tasks) {
        //         $this->view_data['progress'] = round($tasks_done / $tasks * 100);
        //     }
        //     $attr = array('progress' => $this->view_data['progress']);
        //     $this->view_data['project']->update_attributes($attr);
        // }
        // $projecthasworker = ProjectHasWorker::all(array('conditions' => array('user_id = ? AND project_id = ?', $this->user->id, $id)));
        $projectcompanyid = $this->view_data['project']->company_id;
        $usercompanyid = $this->user->company_id;
        if (!($projectstable->company_id === $usercompanyid) && $this->user->admin != 1) {
            $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_no_access_error'));
            redirect('projects');
        }
        // $tracking = $this->view_data['project']->time_spent;
        // if (!empty($this->view_data['project']->tracking)) {
        //     $tracking = (time() - $this->view_data['project']->tracking) + $this->view_data['project']->time_spent;
        // }

        // $this->view_data['time_spent_from_today'] = time() - $this->view_data['project']->time_spent;
        // $tracking = floor($tracking / 60);
        // $tracking_hours = floor($tracking / 60);
        // $tracking_minutes = $tracking - ($tracking_hours * 60);

        // $this->view_data['time_spent'] = $tracking_hours . " " . $this->lang->line('application_hours') . " " . $tracking_minutes . " " . $this->lang->line('application_minutes');
        // $this->view_data['time_spent_counter'] = sprintf("%02s", $tracking_hours) . ":" . sprintf("%02s", $tracking_minutes);

        $this->view_data['production_entries'] = Production::count(array('conditions' => array('pid = ?', $id)));

        $this->view_data['production'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $projectstable->company_id);

        $this->view_data['production_by_received'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $projectstable->company_id . ' AND YEAR(app_date_received) = ' . $this->session->userdata("year_to_view"));

        $this->view_data['production_by_submit'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $projectstable->company_id . ' AND YEAR(production_submitted) = ' . $this->session->userdata("year_to_view"));

        $this->view_data['production_by_year'] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $projectstable->company_id . ' AND YEAR(prem_paid_month) = ' . $this->session->userdata("year_to_view"));

        $this->view_data["acat_complete_past_2017"] = Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = '.$this->user->company_id.' AND production.production_type = "acat" AND YEAR(prem_paid_month) >= 2017 ' );



        if ($page === 'y2y') {

            if ($this->session->userdata('year1')) {
                $year1 = $this->view_data['year1'] = $this->session->userdata('year1');
            } else $year1 = $this->view_data['year1'] = date("Y") - 1;
            if ($this->session->userdata('year2')) {
                $year2 = $this->view_data['year2'] = $this->session->userdata('year2');
            } else $year2 = $this->view_data['year2'] = date("Y");

            $company_id = $projectstable->company_id;

            if (!isset($this->view_data["event_metrics"])) $this->view_data["event_metrics"] = new stdClass();
            $this->calculateAverages($year1, $company_id, null);
            if (isset($this->view_data["event_metrics"]->$year1->spillOver->$year2)) {
                $spill = $this->view_data["event_metrics"]->$year1->spillOver->$year2;
            } else $spill = null;
            $this->calculateAverages($year2, $company_id, $spill);

            $graphs = new stdClass();
            $graphs->$year1 = new stdClass();
            $graphs->$year2 = new stdClass();
            $graphs->comparison = new stdClass();
            foreach ($this->view_data["event_metrics"] as $year => $obj) {
                $this->buildAnnuityPie($year, $obj, $graphs);
                $this->buildLineGraphs($year, $obj, $graphs);
                $this->buildBubbleCharts($year, $obj, $graphs);
            }
            $this->buildBarGraphs($this->view_data["event_metrics"]->$year1, $this->view_data["event_metrics"]->$year2, $graphs);
            $this->view_data['graphs'] = $graphs;

        }

        $this->view_data['selected_year'] = $this->session->userdata("year_to_view");
        $this->view_data["eventoptions"] = getEventOptions();

        $this->content_view = 'projects/view';
//        $this->content_view = "metrics/".getViewFilename($projectstable->name);


    }

    function buildBarGraphs($year1, $year2, $graphs)
    {
//        formula for comparison growth if needed
//        (($v2 - $v1) / abs($v1)) * 100;
        $graphs->comparison->bar_graphs = new stdClass();
        foreach ($year1->by_event as $event_type => $stats) {
            if (isset($year2->by_event->$event_type)) {
                $stats2 = $year2->by_event->$event_type;
                $graphs->comparison->bar_graphs->$event_type = new stdClass();
                foreach (array('whole', 'ratio', 'money') as $graph_type) {
                    $data = array(array(), array());
                    switch ($graph_type) {
                        case 'whole':
                            $data[0][] = $stats->averages->avg_response;
                            $data[0][] = $stats->averages->avg_buying_units;
                            $data[0][] = $stats->averages->avg_appointments;
                            $data[0][] = $stats->averages->avg_appointment_kept;
                            $data[0][] = $stats->averages->num_closed;
                            $data[1][] = $stats2->averages->avg_response;
                            $data[1][] = $stats2->averages->avg_buying_units;
                            $data[1][] = $stats2->averages->avg_appointments;
                            $data[1][] = $stats2->averages->avg_appointment_kept;
                            $data[1][] = $stats2->averages->num_closed;
                            break;
                        case 'ratio':
                            $data[0][] = $stats->averages->response_ratio;
                            $data[0][] = $stats->averages->attendance_ratio;
                            $data[0][] = $stats->averages->appointment_ratio;
                            $data[0][] = $stats->averages->appointment_kept_ratio;
                            $data[0][] = $stats->averages->closingratio;
                            $data[1][] = $stats2->averages->response_ratio;
                            $data[1][] = $stats2->averages->attendance_ratio;
                            $data[1][] = $stats2->averages->appointment_ratio;
                            $data[1][] = $stats2->averages->appointment_kept_ratio;
                            $data[1][] = $stats2->averages->closingratio;
                            break;
                        case 'money':
                            $data[0][] = $stats->averages->annuityavg;
                            $data[0][] = $stats->averages->totalannuity;
                            $data[1][] = $stats2->averages->annuityavg;
                            $data[1][] = $stats2->averages->totalannuity;
                            break;
                    }
                    $graphs->comparison->bar_graphs->$event_type->$graph_type = (object)[
                        'data' => $data
                    ];
                }
            }
//            }
        }

        $data = array(array(), array());
        $data[0][] = $year1->by_attribute->annuityproduction->yearly;
        $data[1][] = $year1->by_attribute->otherproduction->yearly;
        $data[0][] = $year2->by_attribute->annuityproduction->yearly;
        $data[1][] = $year2->by_attribute->otherproduction->yearly;

        $graphs->comparison->bar_graphs->production = (object)[
            'data' => $data
        ];
    }

    function buildLineGraphs($year, $obj, $graphs)
    {
        $graphs->$year->line_graphs = new stdClass();
        $graphs->$year->line_graphs->cumulative = new stdClass();
        $graphs->$year->line_graphs->cumulative->annuityproduction = new stdClass();
        $graphs->$year->line_graphs->cumulative->annuityproduction->data = array();

        for ($x = 0; $x < 12; $x++) {
            $graphs->$year->line_graphs->cumulative->annuityproduction->data[$x] = 0;
        }

        foreach ($obj->by_attribute->annuityproduction->monthly as $month => $total) {
            $m = explode('/', $month)[0] - 1;
            $graphs->$year->line_graphs->cumulative->annuityproduction->data[$m] = $total;
        }

        foreach ($obj->by_event as $event_type => $stats) {
            $graphs->$year->line_graphs->$event_type = new stdClass();
            $graphs->$year->line_graphs->$event_type->annuityproduction = new stdClass();
            $graphs->$year->line_graphs->$event_type->annuityproduction->data = array();

            for ($x = 0; $x < 12; $x++) {
                $graphs->$year->line_graphs->$event_type->annuityproduction->data[$x] = 0;
            }
            foreach ($stats->totals->annuityproduction->monthly as $month => $total) {
                $m = explode('/', $month)[0] - 1;
                $graphs->$year->line_graphs->$event_type->annuityproduction->data[$m] = $total;
            }
        }
    }

    function buildBubbleCharts($year, $obj, $graphs)
    {
        $graphs->$year->bubble_charts = new stdClass();
        $graphs->$year->bubble_charts->attendance = new stdClass();
        $graphs->$year->bubble_charts->attendance->title = "Most Popular Events: $year";
        $graphs->$year->bubble_charts->attendance->data = array();
        $graphs->$year->bubble_charts->attendance->scaleLabel = array("Average Attendance Ratio", "Average Buying Units");

        $dontGraph = ["clients", "solicited", "unsolicited", "platinumreferral"];
        foreach ($obj->by_event as $event_type => $stats) {
            if (!in_array($event_type, $dontGraph)) {
                $dataset = new stdClass();
                $dataset->label = getEventOptions()[$event_type];
                $dataset->data = (object)[
                    'x' => $stats->averages->attendance_ratio,
                    'y' => $stats->averages->avg_buying_units,
                    'r' => $stats->averages->closingratio * .5
                ];
                array_push($graphs->$year->bubble_charts->attendance->data, $dataset);
            }
        }
    }

    function buildAnnuityPie($year, $obj, $graphs)
    {
        $graphs->$year->pie_graphs = new stdClass();
        $graphs->$year->pie_graphs->annuity = new stdClass();
        $graphs->$year->pie_graphs->annuity->title = "Total Annuity Production for $year ($)";
        $graphs->$year->pie_graphs->annuity->data = array();

        array_push($graphs->$year->pie_graphs->annuity->data, array('Lead Source', 'Annuity Production'));
        foreach ($obj->by_attribute->annuityproduction->by_event as $event_type => $total) {
            if ($total > 0) array_push($graphs->$year->pie_graphs->annuity->data, array(getEventOptions()[$event_type], $total));
        }
        if (count($graphs->$year->pie_graphs->annuity->data) == 1) {
            $graphs->$year->pie_graphs->annuity->title = "No Production Yet for $year";
        }
    }

    function printVar($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    function calculateAverages($year, $company_id, $spillOver)
    {
        $event_fields = "projects.id,event,event_date,referral_attendee,referral_response,total_responses,number_mailers,bu_attended,total_event_cost,mailers_cost,ad_cost,other_invite_cost";
        $events = Project::getEventsByYearAndCompany($event_fields, $year, $company_id);
        // $events = Production::getProductionEntriesByEventYear($event_fields, $company_id, $year);
        $events_arr = array_map(function ($res) {
            return $res->attributes();
        }, $events);

        $client_fields = "clients.id,clients.sched_appt_check,clients.event_id,clients.has_assets,clients.kept_appt,clients.acat,clients.aum,clients.annuity_app,clients.life_submitted,clients.other,projects.event";
        $clients = Client::getAllActiveClientsByEventForYear($client_fields, $company_id, $year);
        $clients_and_referalls = Client::getAllActiveClients_SR_UR_who_did_production($client_fields, $company_id, $year);
        $clients_arr1 = array_map(function ($res) {
            return $res->attributes();
        }, $clients);
        $clients_arr2 = array_map(function ($res) {
            return $res->attributes();
        }, $clients_and_referalls);
        $clients_arr = array_merge($clients_arr1, $clients_arr2);

        $production_fields = "production.id,production.production_type,production.prem_paid,production.comp_agent_percent,production.prem_paid_month,production.client_id,clients.event_id,projects.event,projects.event_date";
        $production = Production::getProductionEntriesByEventYear($production_fields, $company_id, $year);
        //    $production_arr2 = array_map(function ($res) {
        //        return $res->attributes();
        //    }, $production);
        if ($spillOver != null) {
        //            $this->printVar()
            $production = array_merge($production, $spillOver->production);
        }

        // $production_fields = "production.production_type,production.prem_paid,production.comp_agent_percent,production.prem_paid_month,clients.event_id,projects.event,projects.event_date";
        
        //Production based off event date
        $production_by_year = Production::getProductionEntriesByYearAndCompany($production_fields, $company_id, $year);
        // $production_by_year = Production::getProductionEntriesByEventYear($production_fields, $company_id, $year);

        //For production based off paid year
        //$production_by_year = Production::getProductionBasedOffPaidYear($production_fields, $company_id, $year);
        
        //var dump query
        // $this->view_data["queryresult"] = $production_by_year;

        //Redo Graph
        $productionNew = Production::getProduction($production_fields, $company_id, $year);
        

        $event_metrics = (object)[ 
            'by_attribute' => (object)[
                'annuityproduction' => (object)[
                    'monthly' => new stdClass(),
                    'by_event' => new stdClass(),
                    'yearly' => 0
                ],
                'annuitycommission' => (object)[
                    'monthly' => new stdClass(),
                    'yearly' => 0
                ],
                'otherproduction' => (object)[
                    'yearly' => 0,
                    'monthly' => new stdClass()
                ],
                'othercommission' => (object)[
                    'yearly' => 0,
                    'monthly' => new stdClass()
                ],
                'totalcommission' => (object)[
                    'monthly' => new stdClass(),
                    'yearly' => 0
                ]
            ],
            'by_event' => new stdClass(),
            'spillOver' => new stdClass()
        ];

        $event_value_accumulator_model = (object)[
            'sum' => 0,
            'mailers' => 0,
            'BU' => 0,
            'has_assets' => 0,
            'appointments' => 0,
            'keptappointments' => 0,
            'prospectsclosed' => 0,
            'eventcost' => 0,
            'mailercost' => 0,
            'othercost' => 0,
            'adcost' => 0,
            'acatproduction' => 0,
            'annuityproduction' => 0,
            'annuitycom' => 0,
            'otherproduction' => 0,
            'othercom' => 0,
            'count' => 0,
        ];

        $event_metrics_by_event_model = (object)[
            'totals' => (object)[
                'annuityproduction' => (object)[
                    'yearly' => 0,
                    'monthly' => new stdClass()
                ],
                'annuitycommission' => (object)[
                    'yearly' => 0,
                    'monthly' => new stdClass()
                ],
                'otherproduction' => (object)[
                    'yearly' => 0,
                    'monthly' => new stdClass()
                ],
                'othercommission' => (object)[
                    'yearly' => 0,
                    'monthly' => new stdClass()
                ],
                'totalcommission' => (object)[
                    'yearly' => 0,
                    'monthly' => new stdClass()
                ]
            ],
            'clients' => array(),
            'production' => array(),
            'events' => array(),
            'events_by_month' => array(),
            'averages' => new stdClass()
        ];

        $event_value_accumulator = array();

        foreach ($events as $e) {
            $event_type = $e->event;
            if (!isset($event_value_accumulator[$event_type])) {
                $event_value_accumulator[$event_type] = unserialize(serialize($event_value_accumulator_model));
            }
            if (!isset($event_metrics->by_event->$event_type)) {
                $event_metrics->by_event->$event_type = unserialize(serialize($event_metrics_by_event_model));
            }
            array_push($event_metrics->by_event->$event_type->events, $e->attributes());

            $date_array = explode('/', $e->event_date);
            $cur_event_month = $date_array[0] . '/' . $date_array[2];
            $event_metrics->by_event->$event_type->events_by_month[$cur_event_month]++;

            $event_value_accumulator[$event_type]->count++;

            if ($event_type === 'platinumreferral') {
                $event_value_accumulator[$event_type]->sum += $e->referral_attendee;
                $event_value_accumulator[$event_type]->mailers += $e->referral_response;
            } else {
                $event_value_accumulator[$event_type]->sum += $e->total_responses;
                $event_value_accumulator[$event_type]->mailers += $e->number_mailers;
            }

            $event_value_accumulator[$event_type]->BU += $e->bu_attended;
            $event_value_accumulator[$event_type]->eventcost += $e->total_event_cost;
            $event_value_accumulator[$event_type]->mailercost += $e->mailers_cost;
            $event_value_accumulator[$event_type]->adcost += $e->ad_cost;
            $event_value_accumulator[$event_type]->othercost += $e->other_invite_cost;

        }

        foreach ($clients_arr as $c_entry) {
            $event_type = $c_entry['event'];
            if (!isset($event_value_accumulator[$event_type])) {
                $event_value_accumulator[$event_type] = unserialize(serialize($event_value_accumulator_model));
            }
            if (!isset($event_metrics->by_event->$event_type)) {
                $event_metrics->by_event->$event_type = unserialize(serialize($event_metrics_by_event_model));
            }
            array_push($event_metrics->by_event->$event_type->clients, $c_entry);
            if (in_array($event_type, array("clients", "solicited", "unsolicited"))) {
                $event_value_accumulator[$event_type]->count++;
            }
            if ($c_entry['sched_appt_check'] > 0) $event_value_accumulator[$event_type]->appointments++;
            if ($c_entry['kept_appt'] > 0) $event_value_accumulator[$event_type]->keptappointments++;
            if ($c_entry['has_assets'] > 0) $event_value_accumulator[$event_type]->has_assets++;
            if ($c_entry['acat'] > 0 || $c_entry['aum'] > 0 || $c_entry['annuity_app'] > 0 || $c_entry['life_submitted'] > 0 || $c_entry['other'] > 0) $event_value_accumulator[$event_type]->prospectsclosed++;
        }

        foreach ($production as $p_entry) {
            
            //Moved $spillFlag = false; and $event_type = $p_entry->event; inside the else{} condition
            if ($p_entry->event_id > 3 && !paid_within_year($p_entry->event_date, $p_entry->prem_paid_month)) {
                $event_type = "clients";
                $spillFlag = true;
            } else {
                $spillFlag = false;
                $event_type = $p_entry->event;
            }

            if (!isset($event_value_accumulator[$event_type])) {
                $event_value_accumulator[$event_type] = unserialize(serialize($event_value_accumulator_model));
            }
            if (!isset($event_metrics->by_event->$event_type)) {
                $event_metrics->by_event->$event_type = unserialize(serialize($event_metrics_by_event_model));
            }
            if (in_array($event_type, array("clients", "solicited", "unsolicited"))) {
                $date_array = explode('-', $p_entry->prem_paid_month);
                $cur_event_month = $date_array[1] . '/' . $date_array[0];
                $paid_year = $date_array[0];
            } else {
                $date_array = explode('/', $p_entry->event_date);
                $cur_event_month = $date_array[0] . '/' . $date_array[2];
                $paid_year = $date_array[2];
            }
            if ($spillFlag) {
                if (!isset($event_metrics->spillOver->$paid_year)) $event_metrics->spillOver->$paid_year = new stdClass();
                if (!isset($event_metrics->spillOver->$paid_year->production)) $event_metrics->spillOver->$paid_year->production = array();
                $p_entry->event_id = 3;
                $p_entry->event = "clients";
                array_push($event_metrics->spillOver->$paid_year->production, $p_entry);
                continue;
            } else {
            // if (!isset($event_metrics->spillOver->$paid_year)) $event_metrics->spillOver->$paid_year = new stdClass();
            // if (!isset($event_metrics->spillOver->$paid_year->production1)) $event_metrics->spillOver->$paid_year->production1 = array();
            // array_push($event_metrics->spillOver->$paid_year->production1, $p_entry->attributes());
                array_push($event_metrics->by_event->$event_type->production, $p_entry->attributes());
            }
            $prem_paid = $p_entry->prem_paid / 100;
            $comp_agent_percent = $p_entry->comp_agent_percent / 100;
            $commission = $comp_agent_percent * $prem_paid;
            switch ($p_entry->production_type) {
                case 'acat':
                    $event_value_accumulator[$event_type]->acatproduction += $prem_paid;
                    break;
                case 'annuity':
                    $event_value_accumulator[$event_type]->annuityproduction += $prem_paid;
                    $event_value_accumulator[$event_type]->annuitycom += $commission;

                    if (!isset($event_metrics->by_event->$event_type->totals->annuitycommission->monthly->$cur_event_month)) $event_metrics->by_event->$event_type->totals->annuitycommission->monthly->$cur_event_month = 0;
                    $event_metrics->by_event->$event_type->totals->annuitycommission->monthly->$cur_event_month += $commission;
                    $event_metrics->by_event->$event_type->totals->annuitycommission->yearly += $commission;

                    if (!isset($event_metrics->by_event->$event_type->totals->annuityproduction->monthly->$cur_event_month)) $event_metrics->by_event->$event_type->totals->annuityproduction->monthly->$cur_event_month = 0;
                    $event_metrics->by_event->$event_type->totals->annuityproduction->monthly->$cur_event_month += $prem_paid;
                    $event_metrics->by_event->$event_type->totals->annuityproduction->yearly += $prem_paid;
                    break;
                case 'other':
                case 'life':
                    $event_value_accumulator[$event_type]->otherproduction += $prem_paid;
                    $event_value_accumulator[$event_type]->othercom += $commission;

                    if (!isset($event_metrics->by_event->$event_type->totals->otherproduction->monthly->$cur_event_month)) $event_metrics->by_event->$event_type->totals->otherproduction->monthly->$cur_event_month = 0;
                    $event_metrics->by_event->$event_type->totals->otherproduction->monthly->$cur_event_month += $prem_paid;
                    $event_metrics->by_event->$event_type->totals->otherproduction->yearly += $prem_paid;

                    if (!isset($event_metrics->by_event->$event_type->totals->othercommission->monthly->$cur_event_month)) $event_metrics->by_event->$event_type->totals->othercommission->monthly->$cur_event_month = 0;
                    $event_metrics->by_event->$event_type->totals->othercommission->monthly->$cur_event_month += $commission;
                    $event_metrics->by_event->$event_type->totals->othercommission->yearly += $commission;
                    break;
                case 'aum':
                    break;
            }
        }
        
        foreach ($production_by_year as $p_entry) {
            //paid within year check
            if ($p_entry->event_id > 3 && !paid_within_year($p_entry->event_date, $p_entry->prem_paid_month)) {
                $event_type = "clients";
            } else {
                $event_type = $p_entry->event;
            }
            
            //Add just event type
            // $event_type = $p_entry->event;

            //Date set to prem paid here - Chip
            $date_array = explode('-', $p_entry->prem_paid_month);
            $prem_paid_date = $date_array[1] . '/' . $date_array[0];
            $prem_paid = $p_entry->prem_paid / 100;
            $comp_agent_percent = $p_entry->comp_agent_percent / 100;
            $commission = $comp_agent_percent * $prem_paid;
            if (!isset($event_metrics->spillOver->$date_array[0])) $event_metrics->spillOver->$date_array[0] = new stdClass();
            if (!isset($event_metrics->spillOver->$date_array[0]->production)) $event_metrics->spillOver->$date_array[0]->production = array();
            array_push($event_metrics->spillOver->$date_array[0]->production, $p_entry->attributes());

            switch ($p_entry->production_type) {
                case 'acat':
                    break;
                case 'annuity':
                    if (!isset($event_metrics->by_attribute->annuitycommission->monthly->$prem_paid_date)) $event_metrics->by_attribute->annuitycommission->monthly->$prem_paid_date = 0;
                    $event_metrics->by_attribute->annuitycommission->monthly->$prem_paid_date += $commission;
                    $event_metrics->by_attribute->annuitycommission->yearly += $commission;

                    if (!isset($event_metrics->by_attribute->annuityproduction->monthly->$prem_paid_date)) $event_metrics->by_attribute->annuityproduction->monthly->$prem_paid_date = 0;
                    $event_metrics->by_attribute->annuityproduction->monthly->$prem_paid_date += $prem_paid;
                    $event_metrics->by_attribute->annuityproduction->yearly += $prem_paid;

                    if (!isset($event_metrics->by_attribute->annuityproduction->by_event->$event_type)) $event_metrics->by_attribute->annuityproduction->by_event->$event_type = 0;
                    $event_metrics->by_attribute->annuityproduction->by_event->$event_type += $prem_paid;
                    break;
                case 'other':
                case 'life':
                    $event_metrics->by_attribute->otherproduction->yearly += $prem_paid;
                    $event_metrics->by_attribute->othercommission->yearly += $commission;
                    break;
                case 'aum':
                    break;
            }
        }

        $averages_model = (object)[
            'avg_response' => 0,
            'response_ratio' => 0,
            'avg_buying_units' => 0,
            'avg_appointments' => 0,
            'attendance_ratio' => 0,
            'appointment_ratio' => 0,
            'avg_appointment_kept' => 0,
            'appointment_kept_ratio' => 0,
            'num_closed' => 0,
            'closingratio' => 0,
            // 'totaleventcost' => 0,
            'grossprofit' => 0,
            'annuityavg' => 0,
            'avgtoannuity' => 0,
            'totalannuity' => 0,
            'counter' => 0
        ];


        foreach ($event_value_accumulator as $event_type => $e_data) {
            $cur_avg = unserialize(serialize($averages_model));
            if (!in_array($event_type, array("clients", "solicited", "unsolicited"))) {
                if ($e_data->mailers > 0) $cur_avg->response_ratio = round((float)($e_data->sum / $e_data->mailers) * 100, 1);
                if ($e_data->sum > 0) $cur_avg->attendance_ratio = round((float)($e_data->BU / $e_data->sum) * 100, 1);
                if ($e_data->BU > 0) $cur_avg->appointment_ratio = round((float)($e_data->appointments / $e_data->BU) * 100, 1);
                $cur_avg->avg_response = round(($e_data->sum / $e_data->count), 1);
                $cur_avg->avg_buying_units = round(($e_data->BU / $e_data->count), 1);
                $cur_avg->avg_appointment_kept = round(($e_data->keptappointments / $e_data->count), 1);
                $cur_avg->num_closed = round(($e_data->prospectsclosed / $e_data->count), 1);
                if ($e_data->keptappointments > 0) $cur_avg->closingratio = round((float)($e_data->prospectsclosed / $e_data->keptappointments) * 100, 1);
                $cur_avg->totaleventcost = (($e_data->eventcost + $e_data->mailercost + $e_data->adcost + $e_data->othercost) / 100) / $e_data->count;
                $cur_avg->grossprofit = ($e_data->othercom + $e_data->annuitycom - (($e_data->eventcost + $e_data->mailercost + $e_data->adcost + $e_data->othercost) / 100)) / $e_data->count;
            } else {
                $cur_avg->avg_response = $e_data->count;
                $cur_avg->avg_appointment_kept = $e_data->keptappointments;
                if ($e_data->count > 0) $cur_avg->appointment_ratio = round((float)($e_data->appointments / $e_data->count) * 100, 1);
                else $cur_avg->appointment_ratio = 0;

                $cur_avg->num_closed = $e_data->prospectsclosed;

                if ($e_data->has_assets > 0) $cur_avg->closingratio = round((float)($e_data->prospectsclosed / $e_data->has_assets) * 100, 1);
                else  $cur_avg->closingratio = 0;

                if ($e_data->count > 0) $cur_avg->grossprofit = ($e_data->othercom + $e_data->annuitycom) / $e_data->count;
                else $cur_avg->grossprofit = 0;
            }

            if ($e_data->acatproduction > 0) $cur_avg->avgtoannuity = round((float)($e_data->annuityproduction / $e_data->acatproduction) * 100, 1);
            if ($e_data->appointments > 0) $cur_avg->appointment_kept_ratio = round((float)($e_data->keptappointments / $e_data->appointments) * 100, 1);
            if ($e_data->count > 0) {
                $cur_avg->avg_appointments = round(($e_data->appointments / $e_data->count), 1);
                $cur_avg->annuityavg = $e_data->annuityproduction / $e_data->count;
            }
            else { $cur_avg->avg_appointments = $cur_avg->annuityavg = 0; }
            
            $cur_avg->totalannuity = $e_data->annuityproduction;
            $cur_avg->counter = $e_data->count;

            $event_metrics->by_event->$event_type->totals->annuityproduction->yearly = $e_data->annuityproduction;
            $event_metrics->by_event->$event_type->totals->annuitycommission->yearly = $e_data->annuitycom;
            $event_metrics->by_event->$event_type->totals->othercommission->yearly = $e_data->othercom;
            $event_metrics->by_event->$event_type->totals->totalcommission->yearly = $e_data->othercom + $e_data->annuitycom;

            foreach ($event_metrics->by_event->$event_type->events_by_month as $m => $val) {
                $event_metrics->by_event->$event_type->totals->annuityproduction->monthly->$m /= $val;
            }

            $event_metrics->by_attribute->totalcommission->yearly = $event_metrics->by_attribute->annuitycommission->yearly + $event_metrics->by_attribute->othercommission->yearly;

            $event_metrics->by_event->$event_type->totals->raw = $event_value_accumulator[$event_type];

            $event_metrics->by_event->$event_type->averages = unserialize(serialize($cur_avg));
        }

        $this->view_data["event_metrics"]->$year = $event_metrics;
    }

    function tasks($id = FALSE, $condition = FALSE, $task_id = FALSE)
    {
        $this->view_data['submenu'] = array(
            $this->lang->line('application_back') => 'projects',
            $this->lang->line('application_overview') => 'projects/view/' . $id,
        );
        switch ($condition) {
            case 'add':
                $this->content_view = 'projects/_tasks';
                if ($_POST) {
                    unset($_POST['send']);
                    unset($_POST['files']);
                    $description = $_POST['description'];
                    $_POST = array_map('htmlspecialchars', $_POST);
                    $_POST['description'] = $description;
                    $_POST['project_id'] = $id;
                    $task = ProjectHasTask::create($_POST);
                    if (!$task) {
                        $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_task_error'));
                    } else {
                        $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_task_success'));
                    }
                    redirect('projects/view/' . $id);
                } else {
                    $this->theme_view = 'modal';
                    $this->view_data['project'] = Project::find($id);
                    $this->view_data['title'] = $this->lang->line('application_add_task');
                    $this->view_data['form_action'] = 'projects/tasks/' . $id . '/add';
                    $this->content_view = 'projects/_tasks';
                }
                break;
            case 'update':
                $this->content_view = 'projects/_tasks';
                $this->view_data['task'] = ProjectHasTask::find($task_id);
                if ($_POST) {
                    unset($_POST['send']);
                    unset($_POST['files']);
                    if (!isset($_POST['public'])) {
                        $_POST['public'] = 0;
                    }
                    $description = $_POST['description'];
                    $_POST = array_map('htmlspecialchars', $_POST);
                    $_POST['description'] = $description;
                    $task_id = $_POST['id'];
                    $task = ProjectHasTask::find($task_id);
                    $task->update_attributes($_POST);
                    if (!$task) {
                        $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_task_error'));
                    } else {
                        $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_task_success'));
                    }
                    redirect('projects/view/' . $id);
                } else {
                    $this->theme_view = 'modal';
                    $this->view_data['project'] = Project::find($id);
                    $this->view_data['title'] = $this->lang->line('application_edit_task');
                    $this->view_data['form_action'] = 'projects/tasks/' . $id . '/update/' . $task_id;
                    $this->content_view = 'projects/_tasks';
                }
                break;
            case 'check':
                $task = ProjectHasTask::find($task_id);
                if ($task->status == 'done') {
                    $task->status = 'open';
                } else {
                    $task->status = 'done';
                }
                $task->save();
                $project = Project::find($id);
                $tasks = ProjectHasTask::count(array('conditions' => 'project_id = ' . $id));
                $tasks_done = ProjectHasTask::count(array('conditions' => array('status = ? AND project_id = ?', 'done', $id)));
                if ($project->progress_calc == 1) {
                    if ($tasks) {
                        $progress = round($tasks_done / $tasks * 100);
                    }
                    $attr = array('progress' => $progress);
                    $project->update_attributes($attr);
                }
                if (!$task) {
                    $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_task_error'));
                }
                $this->theme_view = 'ajax';
                $this->content_view = 'projects';
                break;
            case 'delete':
                $task = ProjectHasTask::find($task_id);
                $task->delete();
                if (!$task) {
                    $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_delete_task_error'));
                } else {
                    $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_delete_task_success'));
                }
                redirect('projects/view/' . $id);
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
            $this->lang->line('application_overview') => 'projects/view/' . $id,
        );
        switch ($condition) {
            case 'add':

                if ($_POST) {
                    $this->content_view = 'projects/_productions';
                    unset($_POST['send']);
                    unset($_POST['files']);
                    //$description = $_POST['description'];
                    $_POST = array_map('htmlspecialchars', $_POST);
                    //$_POST['description'] = $description;
                    $_POST['pid'] = $id;
                    //Strip commas from numbers
                    //function stripNums($n)
                    //{
                    //    return $n = strtr($n, array('.' => '', ',' => ''));
                    //}
                    //
                    //$_POST = array_map('stripNums', $_POST);
                    foreach ($_POST as $key => $value) {
                        if ($key !== 'production_notes') {
                            $_POST[$key] = strtr($value, array('.' => '', ',' => ''));
                        }
                    }
                    $production = Production::create($_POST);
                    if (!$production) {
                        $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_task_error'));
                    } else {
                        $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_task_success'));
                    }
                    redirect('projects/view/' . $id);
                    $this->session->set_userdata('refer_from', '');
                } else {
                    $refer_from = $this->session->userdata('refer_from');
                    if ($refer_from == 'clientpage') {
                        $this->theme_view = 'modal_to_modal';
                    } else {
                        $newdata = array(
                            'last_client' => '',
                            's_acat' => '',
                            's_annuity' => '',
                            's_life' => '',
                            's_other' => '',
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
                    $this->view_data['projects'] = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
                    $this->view_data['client'] = Client::all(array('order' => 'lastname asc', 'conditions' => array('inactive = 0 AND company_id = ?', $projectstable->company_id)));
                    // $this->view_data['productions'] = Production::count(array('conditions' => 'pid = ' . $id));
                    $this->view_data['title'] = "Add Production Entry";
                    $this->view_data['form_action'] = 'projects/productionentry/' . $id . '/add';
                    $this->content_view = 'projects/_productions';
                }
                break;
            case 'update':
                //$this->content_view = 'projects/_productions';

                if ($_POST) {
                    unset($_POST['send']);
                    unset($_POST['files']);
                    //if(!isset($_POST['public'])){$_POST['public'] = 0;}
                    //$description = $_POST['description'];
                    $_POST = array_map('htmlspecialchars', $_POST);
                    //$_POST['description'] = $description;
                    $production_id = $_POST['id'];
                    $production = Production::find($production_id);
                    //Strip commas from numbers
                    $_POST['production_amount'] = strtr($_POST['production_amount'], array('.' => '', ',' => ''));
                    $_POST['prem_paid'] = strtr($_POST['prem_paid'], array('.' => '', ',' => ''));
                    // This function & array map removes all periods and commas from every POST variable
                    // function stripNums($n) {
                    // 	return $n = strtr($n, array('.' => '' , ',' => ''));
                    // }
                    // $_POST = array_map('stripNums', $_POST);
                    $production->update_attributes($_POST);
                    if (!$production) {
                        $this->session->set_flashdata('message', 'error:Production Entry Not Saved :(');
                    } else {
                        $this->session->set_flashdata('message', 'success:Production Entry Updated!');
                    }
                    // redirect('projects/view/'.$id);
                    redirect($this->session->userdata('refer_from'));
                } else {
                    $this->theme_view = 'modal';
                    $this->load->helper('custom');
                    $this->view_data['production'] = Production::find($production_id);
                    $this->view_data['project'] = Project::find($id);
                    $projectstable = Project::find($id);
                    $this->view_data['projects'] = Project::find('all',array('conditions' => array('company_id=?', $this->user->company_id)));
                    $this->view_data['client'] = Client::all(array('order' => 'lastname asc', 'conditions' => array('inactive = 0 AND company_id = ?', $projectstable->company_id)));
                    $this->view_data['productions'] = Production::count(array('conditions' => 'pid = ' . $id));
                    $this->view_data['title'] = "Edit Production Entry";
                    $this->view_data['form_action'] = 'projects/productionentry/' . $id . '/update/' . $production_id;
                    $this->content_view = 'projects/_productions';
                }
                break;
            case 'delete':
                $production = Production::find($production_id);
                $production->delete();
                if (!$production) {
                    $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_delete_task_error'));
                } else {
                    $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_delete_task_success'));
                }
                redirect('projects/view/' . $id);
                break;
            default:
                $this->view_data['project'] = Project::find($id);
                $this->content_view = 'projects/productions';
                break;
        }

    }

    //End Production Entry******************************************************************************************************

    function y2y($id = FALSE, $condition = FALSE, $production_id = FALSE)
    {
        $this->view_data['submenu'] = array(
            $this->lang->line('application_back') => 'projects',
            $this->lang->line('application_overview') => 'projects/view/' . $id,
        );
        if ($condition == 'update') {

            if ($_POST) {
                unset($_POST['send']);
                unset($_POST['files']);
                $_POST = array_map('htmlspecialchars', $_POST);
                if ($_POST['year1'] != $_POST['year2']) {
                    $this->session->set_userdata('year1', $_POST['year1']);
                    $this->session->set_userdata('year2', $_POST['year2']);
                }
                redirect($this->session->userdata('refer_from'));
            } else {
                $this->theme_view = 'modal';
                $this->load->helper('custom');
                if ($this->session->userdata('year1')) {
                    $this->view_data['year1'] = $this->session->userdata('year1');
                } else $this->view_data['year1'] = date("Y") - 1;
                if ($this->session->userdata('year2')) {
                    $this->view_data['year2'] = $this->session->userdata('year2');
                } else $this->view_data['year2'] = date("Y");
                $this->view_data['title'] = "Select Year Comparison";
                $this->view_data['form_action'] = 'projects/y2y/' . $id . '/update/';
                $this->content_view = 'projects/_yearselection';
            }
        }

    }

    function getProductionByYear($year, $company_id)
    {
        return Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $company_id . ' AND YEAR(prem_paid_month) = ' . $year);
    }

    //End Production Entry******************************************************************************************************

    function notes($id = FALSE)
    {
        if ($_POST) {
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
            $this->lang->line('application_overview') => 'projects/view/' . $id,
            $this->lang->line('application_tasks') => 'projects/tasks/' . $id,
            $this->lang->line('application_media') => 'projects/media/' . $id,
        );
        switch ($condition) {
            case 'view':

                if ($_POST) {
                    unset($_POST['send']);
                    unset($_POST['_wysihtml5_mode']);
                    unset($_POST['files']);
                    //$_POST = array_map('htmlspecialchars', $_POST);
                    $_POST['text'] = $_POST['message'];
                    unset($_POST['message']);
                    $_POST['project_id'] = $id;
                    $_POST['media_id'] = $media_id;
                    $_POST['from'] = $this->user->firstname . ' ' . $this->user->lastname;
                    $this->view_data['project'] = Project::find_by_id($id);
                    $this->view_data['media'] = ProjectHasFile::find($media_id);
                    $message = Message::create($_POST);
                    if (!$message) {
                        $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_message_error'));
                    } else {
                        $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_message_success'));

                        foreach ($this->view_data['project']->project_has_workers as $workers) {
                            send_notification($workers->user->email, "[" . $this->view_data['project']->name . "] New comment", 'New comment on meida file: ' . $this->view_data['media']->name . '<br><strong>' . $this->view_data['project']->name . '</strong>');
                        }
                        if (isset($this->view_data['project']->company->email)) {
                            send_notification($this->view_data['project']->company->email, "[" . $this->view_data['project']->name . "] New comment", 'New comment on meida file: ' . $this->view_data['media']->name . '<br><strong>' . $this->view_data['project']->name . '</strong>');
                        }
                    }
                    redirect('projects/media/' . $id . '/view/' . $media_id);
                }
                $this->content_view = 'projects/view_media';
                $this->view_data['media'] = ProjectHasFile::find($media_id);
                $this->view_data['form_action'] = 'projects/media/' . $id . '/view/' . $media_id;
                $this->view_data['filetype'] = explode('.', $this->view_data['media']->filename);
                $this->view_data['filetype'] = $this->view_data['filetype'][1];
                $this->view_data['backlink'] = 'projects/view/' . $id;
                break;
            case 'add':
                $this->content_view = 'projects/_media';
                $this->view_data['project'] = Project::find($id);
                if ($_POST) {
                    $config['upload_path'] = './files/media/';
                    $config['encrypt_name'] = TRUE;
                    $config['allowed_types'] = '*';

                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors('', ' ');
                        $this->session->set_flashdata('message', 'error:' . $error);
                        redirect('projects/media/' . $id);
                    } else {
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
                    if (!$media) {
                        $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_media_error'));
                    } else {
                        $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_media_success'));

                        $attributes = array('subject' => $this->lang->line('application_new_media_subject'), 'message' => '<b>' . $this->user->firstname . ' ' . $this->user->lastname . '</b> ' . $this->lang->line('application_uploaded') . ' ' . $_POST['name'], 'datetime' => time(), 'project_id' => $id, 'type' => 'media', 'user_id' => $this->user->id);
                        $activity = ProjectHasActivity::create($attributes);

                        foreach ($this->view_data['project']->project_has_workers as $workers) {
                            send_notification($workers->user->email, "[" . $this->view_data['project']->name . "] " . $this->lang->line('application_new_media_subject'), $this->lang->line('application_new_media_file_was_added') . ' <strong>' . $this->view_data['project']->name . '</strong>');
                        }
                        if (isset($this->view_data['project']->company->email)) {
                            send_notification($this->view_data['project']->company->email, "[" . $this->view_data['project']->name . "] " . $this->lang->line('application_new_media_subject'), $this->lang->line('application_new_media_file_was_added') . ' <strong>' . $this->view_data['project']->name . '</strong>');
                        }

                    }
                    redirect('projects/view/' . $id);
                } else {
                    $this->theme_view = 'modal';
                    $this->view_data['title'] = $this->lang->line('application_add_media');
                    $this->view_data['form_action'] = 'projects/media/' . $id . '/add';
                    $this->content_view = 'projects/_media';
                }
                break;
            case 'update':
                $this->content_view = 'projects/_media';
                $this->view_data['media'] = ProjectHasFile::find($media_id);
                $this->view_data['project'] = Project::find($id);
                if ($_POST) {
                    unset($_POST['send']);
                    unset($_POST['_wysihtml5_mode']);
                    unset($_POST['files']);
                    $_POST = array_map('htmlspecialchars', $_POST);
                    $media_id = $_POST['id'];
                    $media = ProjectHasFile::find($media_id);
                    $media->update_attributes($_POST);
                    if (!$media) {
                        $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_media_error'));
                    } else {
                        $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_media_success'));
                    }
                    redirect('projects/view/' . $id);
                } else {
                    $this->theme_view = 'modal';
                    $this->view_data['title'] = $this->lang->line('application_edit_media');
                    $this->view_data['form_action'] = 'projects/media/' . $id . '/update/' . $media_id;
                    $this->content_view = 'projects/_media';
                }
                break;
            case 'delete':
                $media = ProjectHasFile::find($media_id);
                $media->delete();
                $this->load->database();
                $sql = "DELETE FROM messages WHERE media_id = $media_id";
                $this->db->query($sql);
                if (!$media) {
                    $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_delete_media_error'));
                } else {
                    unlink('./files/media/' . $media->savename);
                    $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_delete_media_success'));
                }
                redirect('projects/view/' . $id);
                break;
            default:
                $this->view_data['project'] = Project::find($id);
                $this->content_view = 'projects/view/' . $id;
                break;
        }

    }

    function deletemessage($project_id, $media_id, $id)
    {
        $message = Message::find($id);
        if ($message->from == $this->user->firstname . " " . $this->user->lastname || $this->user->admin == "1") {
            $message->delete();
        }
        if (!$message) {
            $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_delete_message_error'));
        } else {
            $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_delete_message_success'));
        }
        redirect('projects/media/' . $project_id . '/view/' . $media_id);
    }

    function tracking($id = FALSE)
    {
        $project = Project::find($id);
        if (empty($project->tracking)) {
            $project->update_attributes(array('tracking' => time()));

        } else {
            $timeDiff = time() - $project->tracking;
            $project->update_attributes(array('tracking' => '', 'time_spent' => $project->time_spent + $timeDiff));
        }
        redirect('projects/view/' . $id);

    }

    function sticky($id = FALSE)
    {
        $project = Project::find($id);
        if ($project->sticky == 0) {
            $project->update_attributes(array('sticky' => '1'));
            $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_make_sticky_success'));

        } else {
            $project->update_attributes(array('sticky' => '0'));
            $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_remove_sticky_success'));
        }
        redirect('projects/view/' . $id);

    }

    function download($media_id = FALSE)
    {
        $media = ProjectHasFile::find($media_id);
        $media->download_counter = $media->download_counter + 1;
        $media->save();
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $media->type);
        header('Content-disposition: attachment; filename=' . $media->filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize('./files/media/' . $media->savename));
        readfile('./files/media/' . $media->savename);
    }

    function activity($id = FALSE, $condition = FALSE, $activityID = FALSE)
    {
        $this->load->helper('notification');
        $project = Project::find_by_id($id);
        //$activity = ProjectHasAktivity::find_by_id($activityID);
        switch ($condition) {
            case 'add':
                if ($_POST) {
                    unset($_POST['send']);
                    $_POST['subject'] = htmlspecialchars($_POST['subject']);
                    $_POST['project_id'] = $id;
                    $_POST['user_id'] = $this->user->id;
                    $_POST['type'] = "comment";
                    unset($_POST['files']);
                    $_POST['datetime'] = time();
                    $activity = ProjectHasActivity::create($_POST);
                    if (!$activity) {
                        $this->session->set_flashdata('message', 'error:' . $this->lang->line('messages_save_error'));
                    } else {
                        $this->session->set_flashdata('message', 'success:' . $this->lang->line('messages_save_success'));
                        foreach ($project->project_has_workers as $workers) {
                            send_notification($workers->user->email, "[" . $project->name . "] " . $_POST['subject'], $_POST['message'] . '<br><strong>' . $project->name . '</strong>');
                        }
                        if (isset($project->client->email)) {
                            send_notification($project->company->email, "[" . $project->name . "] " . $_POST['subject'], $_POST['message'] . '<br><strong>' . $project->name . '</strong>');
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
