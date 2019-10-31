<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function money($number)
{
    echo "&#36;" . money_format('%!n', (double)$number);
}

function moneyVal($number)
{
    return "&#36;" . money_format('%!n', (double)$number);
}

function daysSince($input)
{
    $now = time(); // or your date as well
    $your_date = strtotime($input);
    $datediff = $now - $your_date;

    return floor($datediff / (60 * 60 * 24));
}

function debug_to_console($data)
{

    if (is_array($data))
        $output = "<script>console.log( 'Debug Objects: " . implode(',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

function printHotTable($data)
{
    $table_body_str = <<<EOF
            <thead>
               <tr>
                   <th>Client Name</th>
                   <th>Last Contact</th>
                   <th>Follow Up</th>
                   <th>Probable ACAT Size</th>
                   <th>Closing Probability</th>
                   <th>Probability Weighted Outcome</th>
                   <th>Total To Annuity</th>
                   <th>Comments</th>
                   <th></th>
               </tr>
           </thead>
           <tbody>
EOF;

    foreach ($data as &$cur) {

        $last_contact_days = daysSince($cur->c_last_contact);
        $last_follow_up_days = $cur->c_follow_up != null ? daysSince($cur->c_follow_up) : 999999;
        if ($last_follow_up_days >= 0) {
            $days_since = min($last_contact_days, $last_follow_up_days);
        } else {
            $days_since = $last_contact_days;
        }
        $base_url = base_url();
        $row_class = $days_since > 90 ? 'oldRow' : '';
        $last_contact_date = date("m/d/Y", strtotime($cur->c_last_contact));
        $probable_acat = moneyVal($cur->c_probable_acat_size / 100);
        $closing_probability = moneyVal(($cur->c_probable_acat_size / 100) * ($cur->c_closing_probability / 100));
        $probability_weighted = moneyVal(($cur->c_probable_acat_size / 100) * ($cur->c_closing_probability / 100) * ($cur->c_annuity_probability / 100));

        $table_body_str .= "<tr href='{$base_url}clients/update/{$cur->id}' class='{$row_class}' data-toggle='mainmodal'>";
        $table_body_str .= "<td>{$cur->firstname} {$cur->lastname}</td>";
        $table_body_str .= "<td>{$last_contact_date}</td>";
        $table_body_str .= "<td>{$cur->c_follow_up}</td>";
        $table_body_str .= "<td>{$probable_acat}</td>";
        $table_body_str .= "<td>{$cur->c_closing_probability}&percnt;</td>";
        $table_body_str .= "<td>{$closing_probability}</td>";
        $table_body_str .= "<td>{$probability_weighted}</td>";
        $table_body_str .= "<td>{$cur->client_comment}</td>";

        if ($days_since <= 14) {
            $table_body_str .= '<td class="withinTwoWeeksRow">Added within two weeks</td>';
        } else if ($days_since <= 90 && $days_since >= 60) {
            $table_body_str .= '<td class="thirdMonthRow">Third month on list</td>';
        } else if ($days_since > 90) {
            $table_body_str .= '<td>Old</td>';
        } else {
            $table_body_str .= '<td>Within Three Months</td>';
        }
        $table_body_str .= '</tr>';
    }
    $table_body_str .= '</tbody>';
    echo $table_body_str;
}

setlocale(LC_MONETARY, 'en_US');
$mailer_response = $att_ratio = $assets_ratio = $appt_sched_ratio = $kept_appt_ratio = $closing_ratio = $annuitypercent = $annuitypercent = $totalannuitycomm = $probableACAT = $probableCase = $productiontotal = $refToClient = $refBuyingAttendance = $ref_assets_ratio = $count = $acatproduction = $annuityproduction = $lifeproduction = $newRow = $otherproduction = $annuitycom = $lifecom = $othercom = $aumproduction = $retVal = $radio_ratio = ''; ?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <?php if ($this->user->admin === '1' || $project->name === '6') { ?>
            <a class="btn btn-primary" href="<?= base_url() ?>projects/update/<?= $project->id; ?>"
               data-toggle="mainmodal"
               data-target="#mainModal"><?= $this->lang->line('application_edit_this_project'); ?></a>
        <?php } ?>
        <?php if ($project->sticky == 0) { ?>
            <a href="<?= base_url() ?>projects/sticky/<?= $project->id; ?>" class="btn btn-primary hidden-xs"><i
                        class="fa fa-star"></i> <?= $this->lang->line('application_add_to_quick_access'); ?></a>
        <?php } else { ?>
            <a href="<?= base_url() ?>projects/sticky/<?= $project->id; ?>" class="btn btn-primary hidden-xs"><i
                        class="fa fa-star-o"></i> <?= $this->lang->line('application_remove_from_quick_access'); ?></a>
        <?php } ?>

        <?php if ($project->name == '6' && empty($project->event)) {
            echo "<div class='arrow_box'><h1>Please Click the 'Edit this Event' Button and Choose an Event</h1></div>";
        }else { ?>


    </div>
</div>
    <div class="row">

        <div class="col-xs-12 col-sm-12">
            <h1><span class="nobold"><?php echo $company->name; ?></span> - <?php switch ($project->name) {
                    case "0":
                        echo "Metric Name Not Chosen";
                        break;
                    case "1":
                        echo "Production";
                        break;
                    case "2":
                        echo "Annuities";
                        break;
                    case "3":
                        echo "ACATs";
                        break;
                    case "4":
                        echo "Hot Prospect List";
                        break;
                    case "5":
                        echo "Hot Client List";
                        break;
                    case "6":
                        echo "Event Metrics";
                        break;
                    case "7":
                        echo "Life Insurance";
                        break;
                    case "8":
                        echo "Other Business";
                        break;
                    case "9":
                        echo "Assets Under Management";
                        break;
                    case "10":
                        echo "Year to Year";
                        break;
                    default:
                        echo "Metric Name Not Chosen";
                } ?></h1>
            <p class="truncate description"><?= $project->description; ?></p>
            <div class="progress tt" title="<?= $project->progress; ?>%">
                <div class="progress-bar <?php if ($project->progress == "100") { ?>done<?php } ?>" role="progressbar"
                     aria-valuenow="<?= $project->progress; ?>" aria-valuemin="0" aria-valuemax="100"
                     style="width: <?= $project->progress; ?>%;"></div>
            </div>
        </div>
    </div>
<?php
/******* If Name/Type = Event Metrics ********/
if ($project->name == '6') {

    ?>
    <div class="row">
        <?php echo "<span style='padding-left: 17px; visibility: hidden;'>Sheet Number = " . $project->name . "</span>"; ?>
        <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?= $this->lang->line('application_project_details'); ?></div>
            <div class="subcont">
                <ul class="details col-xs-12 col-sm-6">
                    <li class="hideOverflow"><span><?= $this->lang->line('application_project_id'); ?>:</span> <?php echo $project->id.' [ '.$project->leadjig_id.' ]'; ?>
                    </li>
                    <?php if ($project->name == '6'): ?>
                        <li><span>Event Type:</span> <?php
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
                            echo $eventoptions[$project->event];
                            if (!empty($project->name_or_venue)) {
                                echo ' - ' . $project->name_or_venue;
                            } ?>
                        </li>
                    <?php else: ?>
                        <li> &nbsp; </li>
                    <?php endif; ?>
                    <li><span>Company:</span> <?php if (!isset($project->company->name)){ ?> <a href="#" class="label label-default"><?php echo $company->name; //echo $this->lang->line('application_no_client_assigned');
                            }else{ ?><a class="label label-success" href="<?= base_url() ?>clients/view/<?= $project->company->id; ?>"><?php echo $project->company->name;
                                } ?></a></li>
                    <!--<li><span><?= $this->lang->line('application_assigned_to'); ?>
                            :</span> <?php foreach ($project->project_has_workers as $workers): ?> <a
                                class="label label-info"
                                style="padding: 2px 5px 3px;"><?php echo $workers->user->firstname . " " . $workers->user->lastname; ?></a><?php endforeach; ?>
                        <a href="<?= base_url() ?>projects/assign/<?= $project->id; ?>" class="label label-info tt" style="padding: 2px 5px 3px;" title="<?= $this->lang->line('application_assign_to'); ?>" data-toggle="mainmodal"><i class="fa fa-plus"></i></a></li>
                    </li>-->
                    <li><span>Time:</span> <?= $project->event_time ?></li>
                    <li><span>Mailer Size:</span> <?= number_format($project->mailer_size); ?></li>
                    <li><span>Email Invites:</span> <?= number_format($project->email_invites); ?></li>
                    <li class="multilineHide"><span>Income Targeted:</span> <?= $project->incomes_targeted; ?> </li>
                </ul>
                <ul class="details col-xs-12 col-sm-6"><span class="visible-xs divider"></span>
                    <?php if ($project->name == '6'): ?>
                        <li><span>Event Date:</span> <i
                                    class="fa fa-calendar-o"></i> <?php echo $project->event_date; ?></li>

                        <?php if ($project->event != 'radio' && $project->event != 'risradio' && $project->event != 'ristv'): ?>
                            <li><span>Location:</span> <?= $project->location; ?></li>
                            <li class="zipcodes"><span>Zip Codes Used:</span> <?= $project->zip_codes; ?></li>
                            <!-- <li><span>Filters Used:</span> <?= $project->filters; ?></li> -->
                            <li><span>Age Targeted:</span> <?= $project->age_targeted; ?></li>
                            <li><span>RSVPs:</span> <?= $project->rsvps; ?></li>
                            <li class="multilineHide"><span>Assets Targeted:</span> <?= $project->assets_targeted; ?> </li>
                        <?php else: ?>
                            <li> &nbsp; </li>
                            <li> &nbsp; </li>
                            <li> &nbsp; </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li> &nbsp; </li>
                        <li> &nbsp; </li>
                        <li> &nbsp; </li>
                        <li> &nbsp; </li>
                    <?php endif; ?>
                </ul>
                <br clear="both">
            </div>
        </div>
    </div>


    <!-- Event Metrics -->
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?= $this->lang->line('application_event_metrics'); ?></div>
            <div class="subcont">
                <?php if ($project->event === 'platinumreferral') { ?>
                    <ul class="details col-xs-12 col-sm-3 event-metrics">
                        <li><span>Number of Client Responses:</span> <?= $project->client_response; ?></li>
                        <li><span>Number of Referral Responses:</span> <?= $project->referral_response; ?></li>
                        <?php if (!empty($project->referral_response) && !empty($project->client_response)) {
                            $refToClient = $project->referral_response / ($project->referral_response + $project->client_response);
                        } else {
                            $refToClient = 0;
                        } ?>
                        <li class="responseratio">
                            <span>Referral to Client Response Ratio:</span> <?php echo round((float)$refToClient * 100, 1) . '%'; ?>
                        </li>
                    </ul>
                    <ul class="details col-xs-12 col-sm-3 event-metrics">
                        <li><span>Number of Client Attendees:</span> <?= $project->client_attendee; ?></li>
                        <li><span>Number of Referral Attendees:</span> <?= $project->referral_attendee; ?></li>
                        <li><span>Total Attended:</span> <?= $project->attended; ?></li>
                    </ul>
                    <ul class="details col-xs-12 col-sm-3 event-metrics">
                        <li><span>Total Referral Buying Units Attended:</span> <?= $project->bu_attended; ?></li>
                        <li>&nbsp;</li>
                        <?php if (!empty($project->bu_attended) && !empty($project->client_attendee) && !empty($project->referral_attendee)) {
                            $refBuyingAttendance = $project->bu_attended / ($project->client_attendee + $project->referral_attendee);
                        } ?>
                        <li class="responseratio">
                            <span>Referral Buying Unit Attended Ratio:</span> <?php echo round((float)$refBuyingAttendance * 100, 1) . '%'; ?>
                        </li>
                    </ul>
                    <ul class="details last col-xs-12 col-sm-3 event-metrics"><span class="visible-xs divider"></span>
                        <li><span>People with Assets:</span> <?= $has_assets; ?></li>
                        <li> &nbsp; </li>
                        <?php if (!empty($has_assets) && !empty($project->bu_attended)) {
                            $ref_assets_ratio = $has_assets / $project->bu_attended;
                        } ?>
                        <li class="responseratio">
                            <span>Percent with Assets:</span> <?php echo round((float)$ref_assets_ratio * 100, 1) . '%'; ?>
                        </li>
                    </ul>
                <?php } else { ?>
                    <?php if ($project->event != 'radio' && $project->event != 'risradio' && $project->event != 'ristv'): ?>
                        <ul class="details col-xs-12 col-sm-4 event-metrics">
                            <li><span>Number of Mailers:</span> <?=($project->number_mailers == 0) ? number_format($project->mailer_size/2) : $project->number_mailers ;?></li>
                            <li><span>Number of Ads:</span> <?= $project->ad; ?></li>
                            <li><span>Number of Other Invites:</span> <?= $project->other_invite; ?></li>
                            <li class="responseratio"><span>Total Responses:</span> <?= $project->total_responses; ?>
                            </li>
                        </ul>
                        <ul class="details col-xs-12 col-sm-4 event-metrics"><span class="visible-xs divider"></span>
                            <li><span>Mailer Cost:</span> <?php money($project->mailers_cost / 100); ?></li>
                            <li><span>Ads Cost:</span> <?php money($project->ad_cost / 100); ?></li>
                            <li><span>Other Cost:</span> <?php money($project->other_invite_cost / 100); ?></li>
                            <?php if (!empty($project->total_responses) && !empty($project->number_mailers)) {
                                $mailer_response = $project->total_responses / $project->number_mailers;
                            } elseif ( !empty($project->total_responses) && !empty($project->mailer_size) ) { $mailer_response = $project->total_responses / ($project->mailer_size/2); } 
                            else { $mailer_response = 0; }
                            ?>
                            <li class="responseratio">
                                <span>Response Ratio:</span> <?php echo round((float)$mailer_response * 100, 2) . '%'; ?>
                            </li>
                        </ul>
                        <ul class="details col-xs-12 col-sm-4 event-metrics"><span class="visible-xs divider"></span>
                            <li><span>Total &#35; Attended:</span> <?= $project->attended; ?></li>
                            <li><span>People with Assets:</span> <?= $has_assets; ?> </li>
                            <li><span>Total Buying Units Attended:</span> <?= $project->bu_attended; ?> </li>
                            <?php if (!empty($project->attended) && !empty($project->total_responses)) {
                                $att_ratio = $project->attended / $project->total_responses;
                            } ?>
                            <li class="responseratio">
                                <span>Attendance Ratio:</span> <?php echo round((float)$att_ratio * 100, 1) . '%'; ?>
                            </li>
                        </ul>
                        <!--<ul class="details last col-xs-12 col-sm-3 event-metrics"><span class="visible-xs divider"></span>
                            <li> &nbsp; </li>
                            <li><span>People with Assets:</span> <?= $has_assets; ?> </li>
                            <li><span>Total Buying Units Attended:</span> <?= $project->bu_attended; ?> </li>
                            <?php// if (!empty($has_assets) && !empty($project->bu_attended)) { $assets_ratio = $has_assets / $project->bu_attended; } ?>
                            <li class="responseratio">
                                <span>Percent with Assets:</span> <?php// echo round((float)$assets_ratio * 100, 1) . '%'; ?>
                            </li>
                        </ul>-->

                    <?php else: /*If Radio*/ ?>
                        <ul class="details col-xs-12 col-sm-12 event-metrics">
                            <li><span>Call Ins:</span> <?= $project->total_responses; ?></li>
                            <!--<li><span>People with Assets:</span> <?= $project->people_with_assets; ?></li>
                            <?php //if (!empty($project->people_with_assets) && !empty($project->total_responses)) {
                                //$radio_ratio = $project->people_with_assets / $project->total_responses;
                            //} ?>
                            <li class="responseratio">
                                <span>Percent with Assets:</span> <?php //echo round((float)$radio_ratio * 100, 1) . '%'; ?>
                            </li>-->
                        </ul>
                    <?php endif;
                } /*end else */ ?>
                <br clear="both">
            </div>
        </div>
    </div>
    <!-- Appointment & Closing Metrics -->
    <?php //$assets = array('college', 'federalemployee', 'taxpro');//var to see if College Planning & Federal Employee Benefits Specialist ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?= $this->lang->line('application_appointment_closing'); ?></div>
            <div class="subcont">
                <ul class="details col-xs-12 col-sm-4 appointment-closing">
                    <li><span>Total Appointments Scheduled:</span> <?= $scheduled_appts; ?></li>
                    <li><span>Appointments Kept:</span> <?= $kept_appts; ?></li>
                    <?php if (!empty($project->bu_attended)) {
                        $appt_sched_ratio = $scheduled_appts / $project->bu_attended;
                    } ?>
                    <li class="responseratio">
                        <span>Appointments Scheduled Ratio:</span> <?php echo round((float)$appt_sched_ratio * 100, 1) . '%'; ?>
                    </li>
                </ul>
                <ul class="details col-xs-12 col-sm-4 appointment-closing">
                    <li><span>1st Appointments Pending:</span> <?php echo($scheduled_appts - $kept_appts); ?></li>
                    <li><span>People with Assets:</span> <?= $has_assets; ?> </li>
                    <?php if (!empty($scheduled_appts) && !empty($kept_appts)) {
                        $kept_appt_ratio = $kept_appts / $scheduled_appts;
                    } ?>
                    <li class="responseratio">
                        <span>Kept Appointments Ratio:</span> <?php echo round((float)$kept_appt_ratio * 100, 1) . '%'; ?>
                    </li>
                </ul>
                <ul class="details col-xs-12 col-sm-4 last appointment-closing"><span class="visible-xs divider"></span>
                    <li><span>Prospects Closed:</span> <?= $closed_appts ?></li>
                    <?php if (!empty($has_assets) && !empty($kept_appts)) { $assets_ratio = $has_assets / $kept_appts; } ?>
                    <li class="responseratio"><span>Percent with Assets:</span> <?php echo round((float)$assets_ratio * 100, 1) . '%'; ?></li>
                    <?php if (!empty($closed_appts) && !empty($has_assets)) {
                        $closing_ratio = $closed_appts / $has_assets;
                    } ?>
                    <li class="responseratio">
                        <span>Closing Ratio:</span> <?php echo round((float)$closing_ratio * 100, 1) . '%'; ?></li>
                </ul>

                <br clear="both">
            </div>
        </div>
    </div>
    <?php
    //echo "<pre class='testing'><h1>Testing</h1>";
    foreach ($product as &$entry) {
        if ($entry->production_type == 'acat') {
            $acatproduction += ($entry->prem_paid / 100);
        } else if ($entry->production_type == 'annuity') {
            $annuityproduction += $entry->prem_paid / 100;
            $annuitycom += ($entry->comp_agent_percent / 100) * ($entry->prem_paid / 100);
        } else if ($entry->production_type == 'other' || $entry->production_type == 'life') {
            $otherproduction += $entry->prem_paid / 100;
            $othercom += ($entry->comp_agent_percent / 100) * ($entry->prem_paid / 100);
        }

        // echo $counting.'. '.$entry->firstname.' '.$entry->lastname.'[ '.(($entry->client_prospect == '1') ? 'Client' : 'Prospect').' ]'."\r\n";
        // echo '&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; Production Type '.$entry->production_type."\r\n";
        // echo "acat = ".($entry->acat == 1 ? 'yes' : 'no')."\r\n\r\n";
    }
    echo "</pre>";
    ?>
    <!-- Paid Business Metrics -->
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?= $this->lang->line('application_paid_business_metrics'); ?></div>
            <div class="subcont">
                <ul class="details col-xs-12 col-sm-6 paid-business-metrics">
                    <li><span>A.C.A.T.:</span> <?php money($acatproduction); ?></li>
                    <li><span>Total Annuity Premium:</span> <?php money($annuityproduction); ?></li>
                    <?php if (!empty($acatproduction) && !empty($annuityproduction)) {
                        $annuitypercent = ($annuityproduction / $acatproduction);
                    } ?>
                    <li class="responseratio">
                        <span>Percentage to Annuity:</span> <?php echo round((float)$annuitypercent * 100, 1) . '%'; ?>
                    </li>
                </ul>
                <ul class="details col-xs-12 col-sm-6 paid-business-metrics">
                    <li><span>Total Annuity Commission:</span> <?php money($annuitycom); ?></li>
                    <li><span>Total Other Premium:</span> <?php money($otherproduction); ?></li>
                    <li><span>Total &#36; Other Commission:</span> <?php money($othercom); ?></li>
                </ul>
                <br clear="both">
            </div>
        </div>
    </div>
    <!-- ROI Totals -->
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?= $this->lang->line('application_roi_totals'); ?></div>
            <div class="subcont">
                <ul class="details col-xs-12 col-sm-12 paid-business-metrics">
                    <li class="responseratio">
                        <span>Total All Commisions:</span> <?php money($othercom + $annuitycom); ?></li>
                    <li>
                        <span>Total Event Cost:</span> <?php money(($project->total_event_cost / 100) + ($project->mailers_cost / 100) + ($project->ad_cost / 100) + ($project->other_invite_cost / 100)); ?>
                    </li>
                    <li class="responseratio">
                        <span>Total Gross Profit:</span> <?php money(($othercom + $annuitycom) - (($project->total_event_cost / 100) + ($project->mailers_cost / 100) + ($project->ad_cost / 100) + ($project->other_invite_cost / 100))); ?>
                    </li>
                </ul>
                <br clear="both">
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/cleave.js"></script>
<?php } /******** End If Name/Type = Event Metrics ********/ ?>




<?php /******* Production Sheet ********/
if ($project->name == '1') {
    $eventarray = array(0 => ' ', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
    foreach ($projects as $p) {
        if (!isset($eventarray[$p->id])) {
            $eventarray[$p->id] = eventName($p->event) . ' in ' . $p->location . ' ' . $p->event_date . '';
        }
    }
    $fmo_array = array('' => 'Choose an FMO...', 'cmic' => 'Creative One', 'eca' => 'ECA Marketing', 'aaa' => 'Advisors&rsquo; Academy', 'other' => 'Other');
    $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'aum' => 'AUM', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production');
    ?>
    <style>
		/* Let's get this party started */
		::-webkit-scrollbar {
			width: 1em;
			height: 29px;
		}
		
		/* Track */
		::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgb(141, 179, 225); 
			-webkit-border-radius: 3px;
			border-radius: 3px;
		}
		
		/* Handle */
		::-webkit-scrollbar-thumb {
			-webkit-border-radius: 3px;
			border-radius: 3px;
			background: rgba(28, 104, 196, 0.5); 
  			outline: 1px solid slategrey;
			-webkit-box-shadow: inset 0 0 6px rgb(141, 179, 225); 
		}
		::-webkit-scrollbar-thumb:window-inactive {
			background: rgb(141, 179, 225); 
		}

		.DTFC_ScrollWrapper {
			margin-bottom: 15px;
		}
    </style>
    <!-- Title for Chart -->
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head">
                Production - <?php foreach ($production_by_year as &$entry) {
                    if ($entry->production_type == 'acat') {
                        $acatproduction += ($entry->prem_paid / 100);
                    } else if ($entry->production_type == 'aum') {
                        $aumproduction += $entry->prem_paid / 100;
                    } else if ($entry->production_type == 'annuity') {
                        $annuityproduction += $entry->prem_paid / 100;
                    } else if ($entry->production_type == 'life') {
                        $lifeproduction += $entry->prem_paid / 100;
                    } else if ($entry->production_type == 'other') {
                        $otherproduction += $entry->prem_paid / 100;
                    }
                } ?>
                <span class="acat-production">ACAT <?php money($acatproduction); ?></span>
                <span class="aum-production">AUM <?php money($aumproduction); ?></span>
                <span class="annuity-production">Annuity <?php money($annuityproduction); ?></span>
                <span class="life-production">Life <?php money($lifeproduction); ?></span>
                <span class="other-production">Other <?php money($otherproduction); ?></span>
                <span class="pull-right" style="margin-right: 13%;">
                                <a href="<?= base_url() ?>projects/productionentry/<?= $project->id; ?>/add"
                                   class="btn btn-primary" data-toggle="mainmodal">Add Production Entry</a>
                            </span>
            </div>
            <div class="subcont">
                <!-- hover display no-wrap -->
                <table id="production" class="table table-striped table-hover dt-responsive " width="100%">
                    <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Event / Lead Source</th>
                        <th>Production Type</th>
                        <th>FMO</th>
                        <th>Carrier</th>
                        <th>Product</th>
                        <th>Received</th>
                        <th>Amount</th>
                        <th>Submitted</th>
                        <th>Paid / Transaction Date</th>
                        <th>Paid / Transaction</th>
                        <th>Commission Percent</th>
                        <th>Commission Paid</th>
                        <!-- <th>Edit</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($production_by_submit as &$entry) {

                        //To populate Event / Lead Source for selected client;
                        $client_array = ['0' => 'Please select client...'];
                        $client_events = ['0' => '0'];
                        foreach ($all_clients as $acs) {
                            $client_array[$acs->id] = $acs->firstname . ' ' . $acs->lastname;
                            $client_events[$acs->id] = $acs->event_id;
                        } ?>
                        <tr id="<?= $entry->id; ?>" href="<?= base_url() ?>projects/productionentry/<?= $entry->pid; ?>/update/<?= $entry->id; ?>"
                            data-toggle="mainmodal"
                            >
                            <td><?php echo $entry->firstname . ' ' . $entry->lastname; ?></td>
                            <?php 
                                echo(isset($entry->client_id) 
                                ? (
                                    isset($eventarray[$entry->event_id]) ? '<td>'.$eventarray[$entry->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                ) : 'Please Assign Client'); 
                            ?>
                            <td><?php echo $production_type_array[$entry->production_type]; ?></td>
                            <td><?php echo($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : $fmo_array[$entry->fmo]); ?></td>
                            <td><?php echo($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : $entry->product_co); ?></td>
                            <td><?php echo($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : $entry->product_name); ?></td>
                            <td><?php $unix = human_to_unix($entry->app_date_received . ' 00:00');
                                if (!empty($entry->app_date_received)) {
                                    echo date($core_settings->date_format, $unix);
                                } else {
                                    echo '';
                                }
                                ?></td>
                            <td><?php if (!empty($entry->production_amount)) {
                                    echo money($entry->production_amount / 100);
                                } else {
                                    echo '';
                                } ?></td>
                            <td><?php $unix = human_to_unix($entry->production_submitted . ' 00:00');
                                if (!empty($entry->production_submitted)) {
                                    echo date($core_settings->date_format, $unix);
                                } else {
                                    echo '';
                                } ?></td>
                            <td><?php
                                $unix = human_to_unix($entry->prem_paid_month . ' 00:00');
                                if (!empty($entry->prem_paid_month)) {
                                    echo date($core_settings->date_format, $unix);
                                } else {
                                    echo '';
                                } ?></td>
                            <td><?php if (!empty($entry->prem_paid)) {
                                    echo money($entry->prem_paid / 100);
                                } else {
                                    echo '';
                                } ?></td>
                            <td><?php if ($entry->production_type == 'acat' || $entry->production_type == 'aum') {
                                    echo "N/A";
                                } else {
                                    echo $entry->comp_agent_percent . ' &percnt;';
                                } ?></td>
                            <td><?php echo($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : money(($entry->comp_agent_percent / 100) * ($entry->prem_paid / 100))); ?></td>
                            <!-- <td><a href="<?= base_url() ?>projects/productionentry/<?= $entry->pid; ?>/update/<?= $entry->id; ?>" class="edit-button" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a></td> -->
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
<?php }
/******** End Production ********/

/********* Start Hot Prospects / Hot Prospect List********/
if ($project->name == '4') { ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head">
                Hot Prospects - <span data-toggle="popover" data-content="Weighted Probable ACAT Amount.">
                               <?php foreach ($hot_prospect as &$hp) {
                                   $probableACAT += ($hp->p_probable_acat_size / 100) * ($hp->closing_probability / 100);
                               }
                               money($probableACAT); ?></span>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont">
                <!-- hover display no-wrap -->
                <table id="hotProspectsTable" class="table table-striped table-hover dt-responsive " width="100%">
                    <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Last Contact</th>
                        <th>Follow Up</th>
                        <th>Probable ACAT Size</th>
                        <th>Closing Probability</th>
                        <th>Probability Weighted Outcome</th>
                        <th>Total To Annuity</th>
                        <th>Comments</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($hot_prospect as &$hp) {
                        $last_contact_days = daysSince($hp->last_contact);
                        $last_follow_up_days = $hp->follow_up != null ? daysSince($hp->follow_up) : 999999;
                        if ($last_follow_up_days >= 0) {
                            $days_since = min($last_contact_days, $last_follow_up_days);
                        } else {
                            $days_since = $last_contact_days;
                        }
                        ?>
                        <tr href="<?= base_url() ?>clients/update/<?= $hp->id; ?>"
                            class="<?php if ($days_since > 90) echo 'oldRow' ?>" data-toggle="mainmodal">
                            <td><?php echo $hp->firstname . ' ' . $hp->lastname; ?></td>
                            <td><?php echo date("m/d/Y", strtotime($hp->last_contact)); ?></td>
                            <td><?php echo $hp->follow_up; ?></td>
                            <td><?php money($hp->p_probable_acat_size / 100); ?></td>
                            <td><?php echo $hp->closing_probability . '&percnt;'; ?></td>
                            <td><?php money(($hp->p_probable_acat_size / 100) * ($hp->closing_probability / 100)) ?></td>
                            <td><?php money(($hp->p_probable_acat_size / 100) * ($hp->closing_probability / 100) * ($hp->p_annuity_probability / 100)) ?></td>
                            <td><?php echo $hp->prospect_comment; ?></td>
                            <?php
                            if ($days_since <= 14) {
                                echo '<td class="withinTwoWeeksRow">Added within two weeks</td>';
                            } else if ($days_since <= 90 && $days_since >= 60) {
                                echo '<td class="thirdMonthRow">Third month on list</td>';
                            } else if ($days_since > 90) {
                                echo '<td>Old</td>';
                            } else {
                                echo '<td>Within Three Months</td>';
                            }
                            ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/cleave.js"></script>
<?php }
/*********** End Hot Prospects ***********/

/********* Start Hot Clients ********/
if ($project->name == '5') { ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head">
                Hot Clients - <span data-toggle="popover" data-content="Total possible for cases.">
                               <?php $probableCase = 0;
                               foreach ($hot_client as &$hc) {
                                   $probableCase += ($hc->c_probable_acat_size / 100) * ($hc->c_closing_probability / 100);
                               }
                               money($probableCase); ?></span>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont">
                <!-- hover display no-wrap -->
                <table id="hotClientsTable" class="table table-striped table-hover dt-responsive " width="100%">
                    <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Last Contact</th>
                        <th>Follow Up</th>
                        <th>Probable ACAT Size</th>
                        <th>Closing Probability</th>
                        <th>Probability Weighted Outcome</th>
                        <th>Total To Annuity</th>
                        <th>Comments</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($hot_client as &$hc) {

                        $last_contact_days = daysSince($hc->c_last_contact);
                        $last_follow_up_days = $hc->c_follow_up != null ? daysSince($hc->c_follow_up) : 999999;
                        if ($last_follow_up_days >= 0) {
                            $days_since = min($last_contact_days, $last_follow_up_days);
                        } else {
                            $days_since = $last_contact_days;
                        }

                        ?>
                        <tr href="<?= base_url() ?>clients/update/<?= $hc->id; ?>"
                            class="<?php if ($days_since > 90) echo 'oldRow' ?>"
                            data-toggle="mainmodal">
                            <td><?php echo $hc->firstname . ' ' . $hc->lastname; ?></td>
                            <td><?php echo date("m/d/Y", strtotime($hc->c_last_contact)); ?></td>
                            <td><?php echo $hc->c_follow_up; ?></td>
                            <td><?php money($hc->c_probable_acat_size / 100); ?></td>
                            <td><?php echo $hc->c_closing_probability . '&percnt;'; ?></td>
                            <td><?php money(($hc->c_probable_acat_size / 100) * ($hc->c_closing_probability / 100)); ?></td>
                            <td><?php money(($hc->c_probable_acat_size / 100) * ($hc->c_closing_probability / 100) * ($hc->c_annuity_probability / 100)) ?></td>
                            <td><?php echo $hc->client_comment; ?></td>


                            <?php
                            if ($days_since <= 14) {
                                echo '<td class="withinTwoWeeksRow">Added within two weeks</td>';
                            } else if ($days_since <= 90 && $days_since >= 60) {
                                echo '<td class="thirdMonthRow">Third month on list</td>';
                            } else if ($days_since > 90) {
                                echo '<td>Old</td>';
                            } else {
                                echo '<td>Within Three Months</td>';
                            }
                            ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/cleave.js"></script>
<?php } /*********** End Hot Clients ***********/ ?>



<?php /*********** Show Clients In Event ***********/
if ($project->name == '6') { ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head">
                Attendees
            </div>
            <div class="subcont">
                <table id="eventClientList" class=" table-striped table-hover dt-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Hot Client/Prospect</th>
                        <th>Last Contact Date</th>
                        <th>Follow Up Date</th>
                        <th>Source</th>
                        <th>Income</th>
                        <th>Assets</th>
                        <th>Age</th>
                        <th>ACAT</th>
                        <th>AUM</th>
                        <th>Annuity</th>
                        <th>Life</th>
                        <th>Other</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($clientslist as $cl): $count++; ?>
                        <tr href="<?= base_url() ?>clients/update/<?= $cl->id; ?>" data-toggle="mainmodal">
                            <td><?php echo $cl->firstname . ' ' . $cl->lastname; ?></td>
                            <td><?php
                                $whichHot = '';
                                if ($cl->hot_client == 1) {
                                    $whichHot = 'Hot Client';
                                } elseif ($cl->hot_prospect == 1) {
                                    $whichHot = 'Hot Prospect';
                                } else {
                                    $whichHot = 'Cold ' . $retVal = ($cl->client_prospect == 1) ? 'Client' : 'Prospect';
                                }
                                echo $whichHot; ?>
                            </td>
                            <td><?php
                                if ($whichHot == 'Hot Prospect') {
                                    echo $cl->last_contact;
                                } elseif ($whichHot == 'Hot Client') {
                                    echo $cl->c_last_contact;
                                } else {
                                    //Neither Hot Client nor Prospect
                                    $appts = array();
                                    array_push($appts, $cl->appt_date, $cl->appt_date_2, $cl->appt_date_3);
                                    $index = 0;
                                    foreach ($appts as $i => &$appt) {
                                        if ($appt != '' && daysSince($appt) >= 0) {
                                            $index = $i;
                                        }
                                    }
                                    if ($appts[$index] != '') echo $appts[$index];
                                    else echo "none";
                                }
                                ?>
                            </td>
                            <td><?php
                                if ($whichHot == 'Hot Prospect') {
                                    echo $cl->follow_up;
                                } elseif ($whichHot == 'Hot Client') {
                                    echo $cl->c_follow_up;
                                } else {
                                    //Neither Hot Client nor Prospect
                                }
                                ?>
                            </td>
                            <td><?php echo $cl->source_media; ?></td>
                            <td><?php echo $cl->income; ?></td>
                            <td><?php echo $cl->assets; ?></td>
                            <td><?php echo $cl->age; ?></td>
                            <td><?php echo ($cl->acat == 1) ? 'Yes' : 'No'; ?></td>
                            <td><?php echo ($cl->aum == 1) ? 'Yes' : 'No'; ?></td>
                            <td><?php echo ($cl->annuity_app == 1) ? 'Yes' : 'No'; ?></td>
                            <td><?php echo ($cl->life_submitted == 1) ? 'Yes' : 'No'; ?></td>
                            <td><?php echo ($cl->other == 1) ? 'Yes' : 'No'; ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($count == 0) { ?>
                        <div class="alert alert-info alert-dismissible fade in" role="alert"
                             style="text-align: center;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            <h4 id="oh-snap!-you-got-an-error!"><i class="glyphicon glyphicon-info-sign"
                                                                   style="top: 2px;"></i> No clients or prospects!</h4>
                            <p>Add clients and/or prospects on the "Clients / Prospects" page.</p>
                        </div>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>




<?php /******* ACAT Sheet **************************************************
 **********************************************************************
 **********************************************************************
 **********************************************************************/
if ($project->name == '3') {
    //Event Array
    $eventarray = array(0 => ' ', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
    foreach ($projects as $p) {
        if (!isset($eventarray[$p->id])) {
            $eventarray[$p->id] = eventName($p->event) . ' in ' . $p->location . ' ' . $p->event_date . '';
        }
    }
    $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production');
    //To populate Event / Lead Source for selected client;
    $client_array = ['0' => 'Please select client...'];
    $client_events = ['0' => '0'];
    foreach ($all_clients as $acs) {
        $client_array[$acs->id] = $acs->firstname . ' ' . $acs->lastname;
        $client_events[$acs->id] = $acs->event_id;
    }

    // echo '<pre>';
    // print_r( $this->session->userdata );
    // echo '<br>Selected Year = '.$selected_year;
    // echo '</pre>';
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Pending ACATs <?php echo '- ';
                $pendingACATs = 0;
                //foreach ($production_by_submit as &$pr) {
                foreach ($production as &$pr) {
                    $unix_year = $submitted_year = 0;
                    $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                    $submitted_year = date('Y', $unix_year);
                    //if (!empty($pr->production_submitted) && $pr->production_type == 'acat' && $paid_year != $selected_year) {
                    if (!empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                        $pendingACATs += ($pr->production_amount / 100);
                    }
                }
                echo money($pendingACATs); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="pendingAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Date Submitted</th>
                        <th>&dollar; Pending</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php
                    // foreach ($production_by_submit as &$pr) {
                    foreach ($production as &$pr) {
                        $unix_year = $submitted_year = 0;
                        $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                        $submitted_year = date('Y', $unix_year);
                        // if (!empty($pr->production_submitted) && $pr->production_type == 'acat' && $paid_year != $selected_year) {
                        if (!empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                            if (strtotime($pr->production_submitted) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                                // $spanTip = ' data-toggle="popover" class="pop" data-placement="top"
                                //   data-content="This has been pending for <em>over</em> 7 days." ';
                            } else {
                                $newRow = '';
                                // $spanTip = '';
                            } ?>

                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->production_submitted . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></div></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Not Submitted Yet <?php echo '- ';
                $unix_year = $received_year = $notsubmittedACATs = 0;
                // foreach ($production_by_received as &$pr) {
                foreach ($production as &$pr) {
                    $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                    $received_year = date('Y', $unix_year);
                    if (empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                        $notsubmittedACATs += ($pr->production_amount / 100);
                    }
                }
                echo money($notsubmittedACATs); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="notSubmittedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>App Rec Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php
                    // foreach ($production_by_received as &$pr) {
                    foreach ($production as &$pr) {
                        if (empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                            if (strtotime($pr->app_date_received) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->app_date_received . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head greenbg">
                Completed ACATs <?php echo '- ';
                $completedACATs = 0;
                //switched $production_by_year to $production to include all years here
                //switched to production_by_year. requested by andrew to only include completed for the year
                foreach ($production_by_year as &$pr) {
                    if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'acat') {
                        $completedACATs += ($pr->prem_paid / 100);
                    }
                }
                echo money($completedACATs); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont greenbg">
                <!-- hover display no-wrap -->
                <table id="completedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Paid Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php //switched $production_by_year to $production to include all years here
                    foreach ($production_by_year as &$pr) {
                        if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'acat') { ?>
                            <tr href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->prem_paid_month . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->prem_paid / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
<?php } ?>


<?php /******* AUM Sheet *********************************************
 **********************************************************************
 **********************************************************************
 **********************************************************************/
if ($project->name == '9') {
    //Event Array
    $eventarray = array(0 => ' ', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
    foreach ($projects as $p) {
        if (!isset($eventarray[$p->id])) {
            $eventarray[$p->id] = eventName($p->event) . ' in ' . $p->location . ' ' . $p->event_date . '';
        }

    }
    $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production');
    //To populate Event / Lead Source for selected client;
    $client_array = ['0' => 'Please select client...'];
    $client_events = ['0' => '0'];
    foreach ($all_clients as $acs) {
        $client_array[$acs->id] = $acs->firstname . ' ' . $acs->lastname;
        $client_events[$acs->id] = $acs->event_id;
    }
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Pending New Business <?php echo '- ';
                $pendingLife = 0;
                foreach ($production as &$pr) {
                    $unix_year = $submitted_year = 0;
                    $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                    $submitted_year = date('Y', $unix_year);
                    if (!empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                        $pendingLife += ($pr->production_amount / 100);
                    }
                }
                echo money($pendingLife); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="pendingAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Date Submitted</th>
                        <th>&dollar; Pending</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (!empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                            if (strtotime($pr->production_submitted) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->production_submitted . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Not Submitted Yet <?php echo '- ';
                $notsubmittedLife = 0;
                foreach ($production as &$pr) {
                    $unix_year = $received_year = 0;
                    $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                    $received_year = date('Y', $unix_year);
                    if (empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                        $notsubmittedLife += ($pr->production_amount / 100);
                    }
                }
                echo money($notsubmittedLife); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="notSubmittedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>App Rec Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                            if (strtotime($pr->app_date_received) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->app_date_received . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head greenbg">
                Completed / Paid <?php echo '- ';
                $completedLife = 0;
                foreach ($production_by_year as &$pr) {
                    if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'aum') {
                        $completedLife += ($pr->prem_paid / 100);
                    }
                }
                echo money($completedLife); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont greenbg">
                <!-- hover display no-wrap -->
                <table id="completedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Paid Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production_by_year as &$pr) {
                        if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'aum') { ?>
                            <tr href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->prem_paid_month . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->prem_paid / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
<?php } ?>



<?php /******* Annuities Sheet *********************************************
 **********************************************************************
 **********************************************************************
 **********************************************************************/
if ($project->name == '2') {
    //Event Array
    $eventarray = array(0 => ' ', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
    foreach ($projects as $p) {
        if (!isset($eventarray[$p->id])) {
            $eventarray[$p->id] = eventName($p->event) . ' in ' . $p->location . ' ' . $p->event_date . '';
        }
    }
    $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production');
    //To populate Event / Lead Source for selected client;
    $client_array = ['0' => 'Please select client...'];
    $client_events = ['0' => '0'];
    foreach ($all_clients as $acs) {
        $client_array[$acs->id] = $acs->firstname . ' ' . $acs->lastname;
        $client_events[$acs->id] = $acs->event_id;
    }
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Pending New Business <?php echo '- ';
                $annuitiesPending = 0;
                foreach ($production as &$pr) { // && (empty($pr->prem_paid))
                    $unix_year = $submitted_year = 0;
                    $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                    $submitted_year = date('Y', $unix_year);
                    if (!empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                        $annuitiesPending += ($pr->production_amount / 100);
                    }
                }
                echo money($annuitiesPending); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="pendingAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Date Submitted</th>
                        <th>&dollar; Pending</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (!empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                            if (strtotime($pr->production_submitted) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <!--<?php
                            // && (empty($pr->prem_paid))
                            //echo $paid_year.' '.$pr->firstname.' '.$pr->lastname;
                            //'Date is '.$unix_year.'. Years are '.date('Y').' = '.$paid_year;
                            //$unix2 = human_to_unix($pr->prem_paid_month.' 00:00'); echo date('Y', $unix2);
                            ?>-->
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->production_submitted . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Not Submitted Yet <?php echo '- ';
                $notsubmittedAnnuities = 0;
                foreach ($production as &$pr) {
                    $unix_year = $received_year = 0;
                    $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                    $received_year = date('Y', $unix_year);
                    if (empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                        $notsubmittedAnnuities += ($pr->production_amount / 100);
                    }
                }
                echo money($notsubmittedAnnuities); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="notSubmittedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>App Rec Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                            if (strtotime($pr->app_date_received) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->app_date_received . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head greenbg">
                Completed / Paid <?php echo '- ';
                $completedAnnuities = 0;
                foreach ($production_by_year as &$pr) {
                    if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'annuity') {
                        $completedAnnuities += ($pr->prem_paid / 100);
                    }
                }
                echo money($completedAnnuities); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont greenbg">
                <!-- hover display no-wrap -->
                <table id="completedAcats" class="table " width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Paid Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production_by_year as &$pr) {
                        if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'annuity') { ?>
                            <tr href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->prem_paid_month . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->prem_paid / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
<?php } ?>




<?php /******* Life Sheet *********************************************
 **********************************************************************
 **********************************************************************
 **********************************************************************/
if ($project->name == '7') {
    //Event Array
    $eventarray = array(0 => ' ', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
    foreach ($projects as $p) {
        if (!isset($eventarray[$p->id])) {
            $eventarray[$p->id] = eventName($p->event) . ' in ' . $p->location . ' ' . $p->event_date . '';
        }
    }
    $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production');
    //To populate Event / Lead Source for selected client;
    $client_array = ['0' => 'Please select client...'];
    $client_events = ['0' => '0'];
    foreach ($all_clients as $acs) {
        $client_array[$acs->id] = $acs->firstname . ' ' . $acs->lastname;
        $client_events[$acs->id] = $acs->event_id;
    }
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Pending New Business <?php echo '- ';
                $pendingLife = 0;
                foreach ($production as &$pr) {
                    $unix_year = $submitted_year = 0;
                    $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                    $submitted_year = date('Y', $unix_year);
                    if (!empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                        $pendingLife += ($pr->production_amount / 100);
                    }
                }
                echo money($pendingLife); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="pendingAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Date Submitted</th>
                        <th>&dollar; Pending</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (!empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                            if (strtotime($pr->production_submitted) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->production_submitted . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Not Submitted Yet <?php echo '- ';
                $notsubmittedLife = 0;
                foreach ($production as &$pr) {
                    $unix_year = $received_year = 0;
                    $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                    $received_year = date('Y', $unix_year);
                    if (empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                        $notsubmittedLife += ($pr->production_amount / 100);
                    }
                }
                echo money($notsubmittedLife); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="notSubmittedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>App Rec Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                            if (strtotime($pr->app_date_received) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->app_date_received . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head greenbg">
                Completed / Paid <?php echo '- ';
                $completedLife = 0;
                foreach ($production_by_year as &$pr) {
                    if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'life') {
                        $completedLife += ($pr->prem_paid / 100);
                    }
                }
                echo money($completedLife); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont greenbg">
                <!-- hover display no-wrap -->
                <table id="completedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Paid Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production_by_year as &$pr) {
                        if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'life') { ?>
                            <tr href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->prem_paid_month . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->prem_paid / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
<?php } ?>


<?php /******* Other Sheet *********************************************
 **********************************************************************
 **********************************************************************
 **********************************************************************/
if ($project->name == '8') {
    //Event Array
    $eventarray = array(0 => ' ', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
    foreach ($projects as $p) {
        if (!isset($eventarray[$p->id])) {
            $eventarray[$p->id] = eventName($p->event) . ' in ' . $p->location . ' ' . $p->event_date . '';
        }
    }
    $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production');
    //To populate Event / Lead Source for selected client;
    $client_array = ['0' => 'Please select client...'];
    $client_events = ['0' => '0'];
    foreach ($all_clients as $acs) {
        $client_array[$acs->id] = $acs->firstname . ' ' . $acs->lastname;
        $client_events[$acs->id] = $acs->event_id;
    }
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Pending New Business <?php echo '- ';
                $pendingOther = 0;
                foreach ($production as &$pr) {
                    $unix_year = $submitted_year = 0;
                    $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                    $submitted_year = date('Y', $unix_year);
                    if (!empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                        $pendingOther += ($pr->production_amount / 100);
                    }
                }
                echo money($pendingOther); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="pendingAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Date Submitted</th>
                        <th>&dollar; Pending</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (!empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year) {
                            if (strtotime($pr->production_submitted) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->production_submitted . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head dark">
                Not Submitted Yet <?php echo '- ';
                $notsubmittedOther = 0;
                foreach ($production as &$pr) {
                    $unix_year = $received_year = 0;
                    $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                    $received_year = date('Y', $unix_year);
                    if (empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                        $notsubmittedOther += ($pr->production_amount / 100);
                    }
                }
                echo money($notsubmittedOther); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont dark">
                <!-- hover display no-wrap -->
                <table id="notSubmittedAcats" class="table dt-responsive" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>App Rec Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production as &$pr) {
                        if (empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                            if (strtotime($pr->app_date_received) <= strtotime('-7 day')) {
                                $newRow = 'style="background: #c45557;"';
                            } else {
                                $newRow = '';
                            } ?>
                            <tr <?= $newRow ?>
                                    href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                    data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->app_date_received . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->production_amount / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head greenbg">
                Completed / Paid <?php echo '- ';
                $completedOther = 0;
                foreach ($production_by_year as &$pr) {
                    if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'other') {
                        $completedOther += ($pr->prem_paid / 100);
                    }
                }
                echo money($completedOther); ?>
                <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
            </div>
            <div class="subcont greenbg">
                <!-- hover display no-wrap -->
                <table id="completedAcats" class="table dt-responsive" rel="<?= base_url() ?>" width="100%">
                    <col style="width:15%">
                    <col style="width:15%">
                    <col style="width:10%">
                    <col style="width:10%">
                    <col style="width:50%">
                    <thead>
                    <tr>
                        <th>Event / Lead Source</th>
                        <th>Name</th>
                        <th>Paid Date</th>
                        <th>&dollar; Amount</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                    <?php foreach ($production_by_year as &$pr) {
                        if (!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'other') { ?>
                            <tr href="<?= base_url() ?>projects/productionentry/<?= $pr->pid ?>/update/<?= $pr->id ?>"
                                data-toggle="mainmodal">
                                <?php 
                                    echo(isset($pr->client_id) 
                                    ? (
                                        isset($eventarray[$pr->event_id]) ? '<td>'.$eventarray[$pr->event_id].'</td>' : '<td style="color: palevioletred; letter-spacing: 1px;">Event Deleted</td>'
                                    ) 
                                    : '<td>Please Assign Client</td>'); 
                                ?>
                                <td><?php echo $pr->firstname . ' ' . $pr->lastname; ?></td>
                                <td><?php $unix = human_to_unix($pr->prem_paid_month . ' 00:00');
                                    echo date($core_settings->date_format, $unix); ?></td>
                                <td><?php money($pr->prem_paid / 100); ?></td>
                                <td style="max-width: 200px; white-space: pre-wrap; overflow: auto;"><div style="max-height: 200px;"><?php echo $pr->production_notes; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
<?php } ?>


<?php /*************** Year to Year / Y2Y *********************/
if ($project->name === '10') { ?>
    <?php
        // echo '<pre style="background-color: #3c3c3c; color: #75abda; height: 500px;">',print_r($graphs,1),'</pre>';
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <label for="year"><?php echo $year1 ?> to <?php echo $year2 ?> Comparison</label>
            <a href="<?= base_url(); ?>projects/y2y/<?= $project->id; ?>/update" class="btn btn-primary"
               data-toggle="mainmodal">Set Years</a>
        </div>
    </div>
    <!--        <div class="row">-->
    <!--            <div class="col-sm-12">-->
    <!--                <pre>--><?php //echo print_r($temp1); ?><!-- </pre>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    <div class="row">-->
    <!--        <div class="col-sm-12">-->
    <!--            <pre>--><?php //echo print_r($temp2); ?><!-- </pre>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="row">-->
    <!--        <div class="col-sm-12">-->
    <!--            <pre>--><?php //echo print_r($temp3); ?><!-- </pre>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="row">-->
    <!--        <div class="col-sm-12">-->
    <!--            <pre>--><?php //echo print_r($temp4); ?><!-- </pre>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="row">-->
    <!--        <div class="col-sm-12">-->
    <!--            <pre>--><?php //echo var_dump($metricLabels); ?><!-- </pre>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="graphs-container">-->
    <div class="row graphs">
        <div class="col-md-6 half-graph-container">
            <div class="half-graph" id="piechart<?php echo $year1; ?>"></div>
        </div>
        <div class="col-md-6 half-graph-container">
            <div class="half-graph" id="piechart<?php echo $year2; ?>"></div>
        </div>
    </div>

    <div class="row" id="totalGraphs">
    </div>
    <div class="row">
        <div class="col-sm-12">
            <ul id="graphFilter">
                <li class="graphFilter selectedFilter all" id="all_graphs">
                    All
                </li>
            </ul>
        </div>
    </div>
    <div class="row" id="eventGraphs">
    </div>
    <!--    </div>-->
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <!--    <script type="text/javascript" src="https://www.google.com/jsapi"></script>-->
    <!--    <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}">-->
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);
        var eventoptions = <?php echo json_encode($eventoptions, JSON_PRETTY_PRINT); ?>;
        var years = [<?php echo $year1; ?>, <?php echo $year2; ?>];
        var eventGraphs = document.getElementById('eventGraphs');
        var totalGraphs = document.getElementById('totalGraphs');
        var graphFilter = document.getElementById('graphFilter');
        var visibleGraphs = [];
        var bar_graph_labels = {
            whole: ["Responses", "Buying Units Attended", "Appointments Scheduled", "Appointsments Kept", "Closed"],
            ratio: ["Response Ratio", "Attendance Ratio", "Appointment Ratio", "Appointments Kept Ratio", "Closing Ratio"],
            money: ["Annuity Average", "Total Annuity"],
            production: ["Annuity Production", "Other Production"],
        };
        var eventGraphGroups = {
            avg_response: 'whole',
            response_ratio: 'ratio',
            avg_buying_units: 'whole',
            attendance_ratio: 'ratio',
            avg_appointments: 'whole',
            appointment_ratio: 'ratio',
            avg_appointment_kept: 'whole',
            appointment_kept_ratio: 'ratio',
            num_closed: 'whole',
            closingratio: 'ratio',
            totaleventcost: 'none',
            grossprofit: 'none',
            annuityavg: 'money',
            avgtoannuity: 'none',
            totalannuity: 'money',
            counter: 'none'
        };

        Number.prototype.formatMoney = function(c, d, t){
            var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        };

        var event_metrics = <?php echo json_encode($event_metrics, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR); ?>;
        var graphData = <?php echo json_encode($graphs, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR); ?>;
        
        console.log('Event Metrics'+'\n');
        console.log(event_metrics);
        console.log('graphData \n');
        console.log(graphData);

        var barChartDatas = [];
        var totalChartDatas = [];

        var colors = {
            green: {
                full: "rgba(75,192,192,1)",
                medium: "rgba(75,192,192,.7)",
                light: "rgba(75,192,192,0.4)"
            },
            red: {
                full: 'rgba(255,99,132,1)',
                medium: 'rgba(255,99,132,.7)',
                light: 'rgba(255,99,132,.4)'
            },
            blue: {
                full: 'rgba(54, 162, 235, 1)',
                medium: 'rgba(54, 162, 235, .7)',
                light: 'rgba(54, 162, 235, .4)'
            }
        };

        var tooltipCallbacks = {
            money: {
                label: function(tooltipItems, data) {
                    return ' $'+tooltipItems.yLabel.formatMoney(2);
                }
            },
            percent: {
                label: function(tooltipItems, data) {
                    return ' '+tooltipItems.yLabel+'%';
                }
            },
            attendance: {
                title:function(tooltipItems, data) {
                    cur = data.datasets[tooltipItems[0].datasetIndex];
                    return cur.label;
                },
                label: function(tooltipItems, data) {
                    cur = data.datasets[tooltipItems.datasetIndex];
                    return "Closing Ratio: "+(cur.data[0].r/0.5)+"%";
                }
            },
            none: {
                label: function(tooltipItems, data) {
                    return tooltipItems.yLabel;
                }
            }
        };

        function rainbow(numOfSteps, step) {
            // This function generates vibrant, "evenly spaced" colours (i.e. no clustering). This is ideal for creating easily distinguishable vibrant markers in Google Maps and other apps.
            // Adam Cole, 2011-Sept-14
            // HSV to RBG adapted from: http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript
            var r, g, b;
            var h = step / numOfSteps;
            var i = ~~(h * 6);
            var f = h * 6 - i;
            var q = 1 - f;
            switch (i % 6) {
                case 0:
                    r = 1;
                    g = f;
                    b = 0;
                    break;
                case 1:
                    r = q;
                    g = 1;
                    b = 0;
                    break;
                case 2:
                    r = 0;
                    g = 1;
                    b = f;
                    break;
                case 3:
                    r = 0;
                    g = q;
                    b = 1;
                    break;
                case 4:
                    r = f;
                    g = 0;
                    b = 1;
                    break;
                case 5:
                    r = 1;
                    g = 0;
                    b = q;
                    break;
            }
            var c = "#" + ("00" + (~~(r * 255)).toString(16)).slice(-2) + ("00" + (~~(g * 255)).toString(16)).slice(-2) + ("00" + (~~(b * 255)).toString(16)).slice(-2);
//            return (c);
            r = (~~(r * 255));
            g = (~~(g * 255));
            b = (~~(b * 255));
//            console.log('rgba(' + r + ',' + g + ',' + b);
            return 'rgba(' + r + ',' + g + ',' + b;
        }

        function drawCharts() {
            years.forEach(function (year) {
                google.charts.setOnLoadCallback(function () {
                    drawPieChart(graphData[year].pie_graphs.annuity, year);
                });

            });
        }

        function buildChartJSPies() {
            var dontGraph = ["clients", "unsolicited", "solicited", "platinumreferral"];
            var dontGraph2 = ["production"];
            var columns = [];
            var key, obj, prop, owns = Object.prototype.hasOwnProperty;
            var barChartData_line_prev;
            for (key in graphData.comparison.bar_graphs) {
                if (owns.call(graphData.comparison.bar_graphs, key)) {
                    if (dontGraph.indexOf(key) < 0 && dontGraph2.indexOf(key) < 0) {
                        var barChartData = chartSkel_single();
                        var barChartData_whole = chartSkel();
                        var barChartData_ratio = chartSkel();
                        var barChartData_money = chartSkel();

                        obj = graphData.comparison.bar_graphs[key];

                        barChartData.title.push(this.eventoptions[key] + ": % Growth");


                        var prev, cur;
                        for (prop in obj) {
                            if (owns.call(obj, prop) && prop !== 'counter') {
                                prev = obj[prop].data[0];
                                cur = obj[prop].data[1];
                                switch (prop) {
                                    case 'whole':
                                        barChartData_whole.title.push(this.eventoptions[key] + ": Averages");
                                        barChartData_whole.labels = this.bar_graph_labels[prop];
                                        barChartData_whole.type = 'bar';
                                        barChartData_whole.scaleLabel = {y: ''};
                                        barChartData_whole.callback = tooltipCallbacks.none;
                                        barChartData_whole.datasets[0].data = prev;
                                        barChartData_whole.datasets[1].data = cur;
                                        break;
                                    case 'ratio':
                                        barChartData_ratio.title.push(this.eventoptions[key] + ": Ratios");
                                        barChartData_ratio.labels = this.bar_graph_labels[prop];
                                        barChartData_ratio.type = 'bar';
                                        barChartData_ratio.scaleLabel = {y: '%'};
                                        barChartData_ratio.callback = tooltipCallbacks.percent;
                                        barChartData_ratio.datasets[0].data = prev;
                                        barChartData_ratio.datasets[1].data = cur;
                                        break;
                                    case 'money':
                                        barChartData_money.title.push(this.eventoptions[key] + ": Money");
                                        barChartData_money.labels = this.bar_graph_labels[prop];
                                        barChartData_money.type = 'bar';
                                        barChartData_money.scaleLabel = {y: 'Dollars ($)'};
                                        barChartData_money.callback = tooltipCallbacks.money;
                                        barChartData_money.datasets[0].data = prev;
                                        barChartData_money.datasets[1].data = cur;
                                        break;
                                    default:
//                                    barChartData.labels.push(this.metricLabels[prop]);
//                                    barChartData.datasets[0].data.push(growth);
                                        break;
                                }
                            }
                        }
                        barChartDatas.push(barChartData_whole);
                        barChartDatas.push(barChartData_ratio);
                        barChartDatas.push(barChartData_money);
//                        barChartDatas.push(barChartData);
                    }
                    if (dontGraph2.indexOf(key) < 0) {
                        barChartData_line_prev = chartSkel_line();
                        barChartData_line_prev.type = 'line';
                        if(dontGraph.indexOf(key) > -1) barChartData_line_prev.title.push(this.eventoptions[key] + ": Annuity Production");
                        else {
                            barChartData_line_prev.title.push(this.eventoptions[key] + ": Average Annuity Production");
                        }
                        if (graphData[years[0]].line_graphs.hasOwnProperty(key)) barChartData_line_prev.datasets.push(blank_line_dataset(years[0], graphData[years[0]].line_graphs[key].annuityproduction.data, colors.blue));
                        if (graphData[years[1]].line_graphs.hasOwnProperty(key)) barChartData_line_prev.datasets.push(blank_line_dataset(years[1], graphData[years[1]].line_graphs[key].annuityproduction.data, colors.red));
                        barChartDatas.push(barChartData_line_prev);
                    }
                }
            }
            for (key in dontGraph) {
                if (owns.call(dontGraph, key)) {
                    check = dontGraph[key];
                    if (!graphData.comparison.bar_graphs.hasOwnProperty(check)) {
                        barChartData_line_prev = chartSkel_line();
                        barChartData_line_prev.type = 'line';
                        barChartData_line_prev.title.push(this.eventoptions[check] + ": Annuity Production");
                        blank_array = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                        show_counter = 0;
                        if (graphData[years[0]].line_graphs.hasOwnProperty(check)) {
                            cur_data1 = graphData[years[0]].line_graphs[check].annuityproduction.data;
                        } else {
                            cur_data1 = blank_array;
                            show_counter++;
                        }
                        if (graphData[years[1]].line_graphs.hasOwnProperty(check)) {
                            cur_data2 = graphData[years[1]].line_graphs[check].annuityproduction.data;
                        } else {
                            cur_data2 = blank_array;
                            show_counter++;
                        }
                        barChartData_line_prev.datasets.push(blank_line_dataset(years[0], cur_data1, colors.blue));
                        barChartData_line_prev.datasets.push(blank_line_dataset(years[1], cur_data2, colors.red));
                        if (show_counter < 2) {
                            barChartDatas.push(barChartData_line_prev);
                        }
                    }
                }
            }

            /*  TOTAL LINE GRAPHS
             *
             *
             * */
            var totalChartData_line_prev = chartSkel_line();
            totalChartData_line_prev.type = 'line';
            totalChartData_line_prev.title.push("Composite Annuity Production");
            totalChartData_line_prev.datasets.push(blank_line_dataset(years[0], graphData[years[0]].line_graphs.cumulative.annuityproduction.data, colors.blue));
            totalChartData_line_prev.datasets.push(blank_line_dataset(years[1], graphData[years[1]].line_graphs.cumulative.annuityproduction.data, colors.red));
            totalChartDatas.push(totalChartData_line_prev);

//            totalChartData_line_prev = chartSkel();
//            totalChartData_line_prev.type = 'bar';
//            totalChartData_line_prev.title.push("Yearly Production Breakdown");
//            totalChartData_line_prev.stacked = true;
//            totalChartData_line_prev.scaleLabel = {y: 'Dollars ($)'};
//            totalChartData_line_prev.callback =tooltipCallbacks.money;
//            totalChartData_line_prev.labels = [years[0], years[1]];
//            totalChartData_line_prev.datasets[0] = blank_bar_dataset(this.bar_graph_labels.production[0], graphData.comparison.bar_graphs.production.data[0], colors.blue);
//            totalChartData_line_prev.datasets[1] = blank_bar_dataset(this.bar_graph_labels.production[1], graphData.comparison.bar_graphs.production.data[1], colors.red);
//            console.log(totalChartData_line_prev);
//            totalChartDatas.push(totalChartData_line_prev);

            var attendanceBubble;
            years.forEach(function (year) {
                attendanceBubble = chartSkel_bubble();
                attendanceBubble.title = graphData[year].bubble_charts.attendance.title;
                attendanceBubble.scaleLabel = graphData[year].bubble_charts.attendance.scaleLabel;
                attendanceBubble.callback = tooltipCallbacks.attendance;
                attendanceBubble.type = 'bubble';
                for (var x = 0; x < graphData[year].bubble_charts.attendance.data.length; x++) {
                    ds = graphData[year].bubble_charts.attendance.data[x];
                    attendanceBubble.data.datasets.push(blank_bubble_dataset(ds.label, ds.data, rainbow(graphData[year].bubble_charts.attendance.data.length + 3, x)));
                }
                totalChartDatas.push(attendanceBubble);
            });


            var indcol;
            var title;
            for (id = 0; id < totalChartDatas.length; id++) {
                indcol = totalChartDatas[id].title[0].indexOf(':');
                title = totalChartDatas[id].title[0].slice(0, indcol);

                if (id === 0) {
                    totalGraphs.innerHTML += '<div class="tgraphjs"> <div class="col-xs-12 col-sm-12"><h1>' + "Composite" + '</h1> <p class="truncate description"></p> <div class="progress tt" title="" data-original-title="0%"> <div class="progress-bar " role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div> </div> </div>';
                }

                totalGraphs.innerHTML += '<div class="col-sm-12 col-lg-6 tgraphjs ' + title.split(' ').join('').split('&').join('') + '"><div class="aChart "><canvas style="background:white" id="tgraph' + id + '"></div></div>';
            }


            var lastTitle = '';
            for (id = 0; id < barChartDatas.length; id++) {
                indcol = barChartDatas[id].title[0].indexOf(':');
                title = barChartDatas[id].title[0].slice(0, indcol);
                if (lastTitle !== title || lastTitle == '') {
                    eventGraphs.innerHTML += '<div class="graphjs ' + title.split(' ').join('').split('&').join('') + '"> <div class="col-xs-12 col-sm-12"><h1>' + title + '</h1> <p class="truncate description"></p> <div class="progress tt" title="" data-original-title="0%"> <div class="progress-bar " role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div> </div> </div> </div>';
                }
                eventGraphs.innerHTML += '<div class="col-sm-12 col-lg-6 graphjs ' + title.split(' ').join('').split('&').join('') + '"><div class="aChart "><canvas style="background:white" id="graph' + id + '"></div></div>';
                if (lastTitle !== title || lastTitle == '') {
                    graphFilter.innerHTML += '<li class="graphFilter' + title.split(' ').join('').split('&').join('') + '" id="graphFilter' + id + '">' + title + '</li>';
                    lastTitle = title;
                }
            }
            $('#graphFilter').find('li').on('click', function (e) {
                var type = e.currentTarget.className.replace('graphFilter', '').replace('selectedFilter', '').trim();
                if (type === 'all') {
                    visibleGraphs = [];
                    $('#graphFilter').find('li:not(.all)').removeClass('selectedFilter');
                    if (!$(e.currentTarget).hasClass('selectedFilter')) $(e.currentTarget).addClass('selectedFilter');
                }
                else {
                    $('#graphFilter').find('li:not(.' + type + ')').removeClass('selectedFilter');
                    $(e.currentTarget).toggleClass('selectedFilter');
//                    var typeInd = visibleGraphs.indexOf(type);
//                    if (typeInd < 0) visibleGraphs.push(type);
//                    else {
//                        visibleGraphs.splice(typeInd, 1);
//                    }
                    visibleGraphs = [type];
                }
                $('.graphjs').each(function (index, li) {
                    if (visibleGraphs.length > 0) {
                        var flag = false;
                        visibleGraphs.forEach(function (o) {
                            if ($(li).hasClass(o) && !flag) {
                                $(li).show();
                                flag = true;
                            }
                        });
                        if (!flag) $(li).hide();
                    }
                    else $(li).show();
                })
            });

        }

        
        function drawPieChart(data_points, year) {
            

            var formatter = new google.visualization.NumberFormat({
                decimalSymbol: ',',
                groupingSymbol: '.',
                negativeColor: 'red',
                negativeParens: true,
                prefix: 'R$ '
            });

            var data = google.visualization.arrayToDataTable(data_points.data);
            //Set color
            colors = {"Clients":"#3366CC",
                    "Solicited Referrals":"#dc3912",
                    "Unsolicited Referrals":"#ff9900",
                    "Social Security":"#109618",
                    "RMD":"#990099",
                    "Estate":"#3b3eac",
                    "TaxPro":"#0099c6",
                    "College Planning":"#dd4477",
                    "Federal Employee Benefits Specialist":"#66aa00",
                    "Teacher Pro":"#b82e2e",
                    "Radio":"#006064",
                    "CPA \/ Attorney":"#994499",
                    "P&C Partnership":"#22aa99",
                    "Financial Literacy":"#aaaa11",
                    "Guest Speaker":"#6633cc",
                    "Platinum Referrals":"#e67300",
                    "Advisory Board":"#8b0707",
                    "Lunch &amp; Link":"#329262",
                    "Client Appreciation Party":"#5574a6",
                    "Select Club":"#3b3eac",
                    "Birthday Party":"#ffa000",
                    // "Retirement Party":"#795548",
                    "Retirement":"#795548",
                    "Mani \/ Pedi":"#546e7a",
                    "Dinner Seminar":"#f06292",
                    "WiserAdvisor":"#ad1457",
                    "Other 1":"#9fa8da",
                    "Other 2":"#1a237e",
                    "Other 3":"#81c784",
                    "God":"#81c784",
                    "Workshop 1":"#1b5e20",
                    "Workshop 2":"#69f0ae",
                    "Workshop 3":"#ffd180",
                    "Website":"#bf360c",
                    "Paladin":"#26c6da",
                    "Del Webb":"#6d4c41",
                    "TV" : "#59948f",
                    "God" : "#3d4e4d",
                    "IRA" : "#635994",
                    "Tax Workshop" : "#ffa000",
                    "RIS Radio" : "#183945",
                    "RIS TV" : "#116973",
                    "Webinar" : "#836890"}; 
                    
                        
            function colorsArray(chartdata){
                console.log(chartdata, chartdata.length)
                var array = [];
                for (var i=1;i<chartdata.length;i++){
                    array.push(colors[chartdata[i][0]]);
                }
                console.log(array, array.length);
                return array
            }
            //End set color
            var options = {
                title: data_points.title,
                is3D: true,
                colors: colorsArray(data_points.data)
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart' + year));

            chart.draw(data, options);
        }

        function resize() {
            // change dimensions if necessary
            years.forEach(function (year) {
                drawPieChart(graphData[year].pie_graphs.annuity, year);
            });
        }

        if (window.addEventListener) {
            window.addEventListener('resize', resize);
        }
        else {
            window.attachEvent('onresize', resize);
        }

        buildChartJSPies();
        var myBars = [];
        var myLines = [];
        var myBubbles = [];
        var ctx, newC;
        for (z = 0; z < barChartDatas.length; z++) {
            ctx = document.getElementById("graph" + z).getContext("2d");
            data = barChartDatas[z];
            if (data.type === 'line') {
                newC = getLineTemplate(data, data.title);
                myLines.push(newC);
            } else if (data.type === 'bar') {
                newC = getBarTemplate(data, data.title, false, data.scaleLabel,data.callback);
                myBars.push(newC);
            }
            else if (data.type === 'bubble') {
                newC = getBubbleTemplate(data.data, data.title, data.scaleLabel,data.callback);
                myBubbles.push(newC);
            }

        }
        for (z = 0; z < totalChartDatas.length; z++) {
            ctx = document.getElementById("tgraph" + z).getContext("2d");
            data = totalChartDatas[z];
            if (data.type === 'line') {
                newC = getLineTemplate(data, data.title);
                myLines.push(newC);
            } else if (data.type === 'bar') {
                newC = getBarTemplate(data, data.title, data.stacked, data.scaleLabel,data.callback);
                myBars.push(newC);
            } else if (data.type === 'bubble') {
                newC = getBubbleTemplate(data.data, data.title, data.scaleLabel,data.callback);
                myBubbles.push(newC);
            }
        }

        function getBubbleTemplate(data, title, scaleLabel,callback) {
            return new Chart(ctx, {
                type: 'bubble',
                data: data,
                options: {
                    animation: {
                        duration: 4000,
                        easing: "easeOutQuart"
                    },
                    responsive: true,
                    maintainAspectRatio: true,
                    title: {
                        display: true,
                        fontSize: 15,
                        text: title
                    },
                    tooltips: {
                        mode: 'nearest',
                        callbacks:callback
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: true
                            },
                            labels: {
                                show: true
                            },
                            scaleLabel: {
                                display: true,
                                fontSize: 14,
                                labelString: scaleLabel[0]
                            },
                            ticks: {
                                beginAtZero: true,
                                max: 110,
                                // Create scientific notation labels
                                callback: function (value, index, values) {
                                    return value + '%';
                                }
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                display: true
                            },
                            labels: {
                                show: true
                            },
                            scaleLabel: {
                                display: true,
                                fontSize: 13,
                                labelString: scaleLabel[1]
                            }
                        }]
                    }
                }
            });
        }

        function getBarTemplate(data, title, stacked=false, scaleLabel,callback) {
            return new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    legend: {labels: {fontColor: "black", fontSize: 15}},
                    animation: {
                        duration: 4000,
                        easing: "easeOutQuart"
                    },
                    responsive: true,
                    maintainAspectRatio: true,
                    title: {
                        display: true,
                        fontSize: 15,
                        text: title
                    },
                    tooltips: {
                        mode: 'label',
                        callbacks:callback
                    },
                    elements: {
                        line: {
                            fill: false
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: true
                            },
                            labels: {
                                show: true
                            },
                            stacked: stacked
                        }],
                        yAxes: [{
                            type: "linear",
                            display: true,
                            position: "left",
                            id: "y-axis-1",
                            gridLines: {
                                display: true
                            },
                            labels: {
                                show: true
                            },
                            scaleLabel: {
                                display: true,
                                fontSize: 14,
                                labelString: scaleLabel.y
                            },
                            ticks: {
                                beginAtZero: true,
                            },
                            stacked: stacked

                        }]
                    }
                }
            })
        }

        function getLineTemplate(data, title) {
            return new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                fontSize: 14,
                                labelString: 'Dollars ($)'
                            },
                            ticks: {
                                beginAtZero: true,
                            }
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                fontSize: 14,
                                labelString: 'Month'
                            }
                        }]
                    },
                    animation: {
                        duration: 4000,
                        easing: "easeOutQuart"
                    },
                    responsive: true,
                    maintainAspectRatio: true,
                    title: {
                        display: true,
                        fontSize: 15,
                        text: title
                    },
                    tooltips: {
                        mode: 'label',
                        callbacks: {
                            label: function(tooltipItems, data) {
                                return ' $'+tooltipItems.yLabel.formatMoney(2);
                            }
                        }
                    }
                }
            })
        }

        function chartSkel() {
            return {
                title: [],
                labels: [],
                datasets: [{
                    type: 'bar',
                    label: [years[0]],
                    data: [],
                    fill: false,
                    backgroundColor: [
                        'rgba(220,59,59,0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    hoverBackgroundColor: [
                        'rgba(220,59,59,0.4)',
                        'rgba(54, 162, 235, 0.4)',
                        'rgba(255, 206, 86, 0.4)',
                        'rgba(75, 192, 192, 0.4)',
                        'rgba(255, 159, 64, 0.4)'
                    ],
                    hoverBorderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1,
                    yAxisID: 'y-axis-1'
                }, {
                    type: 'bar',
                    label: [years[1]],
                    data: [],
                    fill: false,
                    backgroundColor: [
                        'rgba(220,59,59,0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    hoverBackgroundColor: [
                        'rgba(220,59,59,0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    hoverBorderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1,
                    yAxisID: 'y-axis-1'
                }]
            };
        }

        function chartSkel_single() {
            return {
                title: [],
                labels: [],
                datasets: [{
                    type: 'bar',
                    label: ["%"],
                    data: [],
                    fill: false,
                    backgroundColor: [
                        'rgba(220,59,59,0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(220,59,59,0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(220,59,59,0.5)',
                        'rgba(54, 162, 235, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    hoverBackgroundColor: [
                        'rgba(220,59,59,0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(220,59,59,0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(220,59,59,0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    hoverBorderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    yAxisID: 'y-axis-1'
                }]
            };
        }

        function chartSkel_line() {
            return {
                title: [],
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                datasets: []
            };
        }

        function chartSkel_bubble() {
            return {
                title: '',
                type: '',
                data: {
                    datasets: [],
                    labels: ["Bad",
                        "Average",
                        "Good",
                        "Very Good",
                        "Perfect"]
                }
            };
        }

        function blank_bar_dataset(label, data, color) {
            return {
                type: 'bar',
                label: [label],
                data: data,
                fill: false,
                backgroundColor: [
                    color.light,
                    color.medium
                ],
                borderColor: [
                    color.light,
                    color.medium
                ],
                hoverBackgroundColor: [
                    color.full,
                    color.medium
                ],
                hoverBorderColor: [
                    color.full,
                    color.medium
                ],
                borderWidth: 1,
                yAxisID: 'y-axis-1'
            };
        }

        function blank_bubble_dataset(label, data, color) {
            return {
                label: label,
                data: [data],
                backgroundColor: color + ',.4)',
                hoverBackgroundColor: color + ',.7)'
            };
        }

        function blank_line_dataset(label, data, color) {
            return {
                label: label,
                data: data,
                fill: true,
                lineTension: 0.1,
                backgroundColor: color.light,
                borderColor: color.full,
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: color.full,
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: color.full,
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                spanGaps: false,
            };
        }
    </script>
<?php } ?>

<?php if ($project->name === '0') {
    /*Hiding this*/ ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?= $this->lang->line('application_tasks'); ?> <span class=" pull-right"><button
                            class="btn btn-default sortListTrigger"><i class="fa fa-sort-amount-desc"></i></button> <a
                            href="<?= base_url() ?>projects/tasks/<?= $project->id; ?>/add" class="btn btn-primary"
                            data-toggle="mainmodal"><?= $this->lang->line('application_add_task'); ?></a></span></div>
            <div class="subcont">
                <ul class="todo sortlist">
                    <?php
                    $count = 0;
                    foreach ($project->project_has_tasks as $value): $count = $count + 1; ?>

                        <li class="<?= $value->status; ?> priority<?= $value->priority; ?>"><a
                                    href="<?= base_url() ?>projects/tasks/<?= $project->id; ?>/check/<?= $value->id; ?>"
                                    class="ajax-silent task-check"></a>
                            <input name="form-field-checkbox" class="checkbox-nolabel task-check" type="checkbox"
                                   data-link="<?= base_url() ?>projects/tasks/<?= $project->id; ?>/check/<?= $value->id; ?>" <?php if ($value->status == "done") {
                                echo "checked";
                            } ?>/>
                            <span class="lbl"> <p class="truncate name"><?= $value->name; ?></p></span>
                            <span class="pull-right">
                                  <?php if ($value->user_id != 0) { ?><img class="img-circle list-profile-img tt"
                                                                           title="<?= $value->user->firstname; ?> <?= $value->user->lastname; ?>"
                                                                           src="<?php
                                                                           if ($value->user->userpic != 'no-pic.png') {
                                                                               echo base_url() . "files/media/" . $value->user->userpic;
                                                                           } else {
                                                                               echo get_gravatar($value->user->email);
                                                                           }
                                                                           ?>"><?php } ?>
                                <?php if ($value->public != 0) { ?><span class="list-button"><i class="fa fa-eye tt"
                                                                                                title=""
                                                                                                data-original-title="<?= $this->lang->line('application_task_public'); ?>"></i>
                                    </span><?php } ?>
                                <a href="<?= base_url() ?>projects/tasks/<?= $project->id; ?>/update/<?= $value->id; ?>"
                                   class="edit-button" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a>
                                  </span>
                            <div class="todo-details">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <ul class="details">
                                            <li><span><?= $this->lang->line('application_priority'); ?>
                                                    :</span> <?php switch ($value->priority) {
                                                    case "0":
                                                        echo $this->lang->line('application_no_priority');
                                                        break;
                                                    case "1":
                                                        echo $this->lang->line('application_low_priority');
                                                        break;
                                                    case "2":
                                                        echo $this->lang->line('application_med_priority');
                                                        break;
                                                    case "3":
                                                        echo $this->lang->line('application_high_priority');
                                                        break;
                                                }; ?></li>
                                            <?php if ($value->value != 0) { ?>
                                                <li><span><?= $this->lang->line('application_value'); ?>
                                                    :</span> <?= $value->value; ?></li><?php } ?>
                                            <?php if ($value->due_date != "") { ?>
                                                <li><span><?= $this->lang->line('application_due_date'); ?>
                                                    :</span> <?php $unix = human_to_unix($value->due_date . ' 00:00');
                                                echo date($core_settings->date_format, $unix); ?></li><?php } ?>
                                            <li><span><?= $this->lang->line('application_assigned_to'); ?>
                                                    :</span> <?php if (isset($value->user->lastname)) {
                                                    echo $value->user->firstname . " " . $value->user->lastname;
                                                } else {
                                                    $this->lang->line('application_not_assigned');
                                                } ?> </li>
                                            <li><span>Custom: </span><?php echo "placeholder"; ?> </li>
                                        </ul>

                                    </div>
                                    <div class="col-sm-9"><h3><?= $this->lang->line('application_description'); ?></h3>
                                        <p><?= $value->description; ?></p></div>

                                </div>
                            </div>

                        </li>
                    <?php endforeach; ?>
                    <?php if ($count == 0) { ?>
                        <li class="notask">No Tasks yet</li>
                    <?php } ?>


                </ul>
            </div>
        </div>
    </div>
<?php } ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="table-head"><?= $this->lang->line('application_media'); ?> <span class=" pull-right"><a
                            href="<?= base_url() ?>projects/media/<?= $project->id; ?>/add" class="btn btn-primary"
                            data-toggle="mainmodal"><?= $this->lang->line('application_add_media'); ?></a></span></div>
            <div class="table-div min-height-410">
                <table id="media" class="table data-media" rel="<?= base_url() ?>projects/media/<?= $project->id; ?>"
                       cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="hidden"></th>
                        <th><?= $this->lang->line('application_name'); ?></th>
                        <th class="hidden-xs"><?= $this->lang->line('application_filename'); ?></th>
                        <th class="hidden-xs"><?= $this->lang->line('application_phase'); ?></th>
                        <th class="hidden-xs"><i class="fa fa-download"></i></th>
                        <th><?= $this->lang->line('application_action'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($project->project_has_files as $value): ?>

                        <tr id="<?= $value->id; ?>">
                            <td class="hidden"><?= human_to_unix($value->date); ?></td>
                            <td onclick=""><?= $value->name; ?></td>
                            <td class="hidden-xs truncate" style="max-width: 80px;"><?= $value->filename; ?></td>
                            <td class="hidden-xs"><?= $value->phase; ?></td>
                            <td class="hidden-xs"><span class="label label-info tt"
                                                        title="<?= $this->lang->line('application_download_counter'); ?>"><?= $value->download_counter; ?></span>
                            </td>
                            <td class="option " width="10%">
                                <button type="button" class="btn-option btn-xs po" data-toggle="popover"
                                        data-placement="left"
                                        data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?= base_url() ?>projects/media/<?= $project->id; ?>/delete/<?= $value->id; ?>'><?= $this->lang->line('application_yes_im_sure'); ?></a> <button class='btn po-close'><?= $this->lang->line('application_no'); ?></button> <input type='hidden' name='td-id' class='id' value='<?= $value->id; ?>'>"
                                        data-original-title="<b><?= $this->lang->line('application_really_delete'); ?></b>">
                                    <i class="fa fa-times fa-lg"></i></button>
                                <a href="<?= base_url() ?>projects/media/<?= $project->id; ?>/update/<?= $value->id; ?>"
                                   class="btn-option" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a>
                            </td>

                        </tr>

                    <?php endforeach; ?>


                    </tbody>
                </table>
                <?php if (!$project->project_has_files) { ?>
                    <div class="no-files">
                        <i class="fa fa-cloud-upload"></i><br>
                        No files have been uploaded yet!
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?php $attributes = array('class' => 'note-form', 'id' => '_notes');
            echo form_open(base_url() . "projects/notes/" . $project->id, $attributes); ?>
            <div class="table-head"><?= $this->lang->line('application_notes'); ?> <span class=" pull-right"><a
                            id="send" name="send"
                            class="btn btn-primary button-loader"><?= $this->lang->line('application_save'); ?></a></span><span
                        id="changed"
                        class="pull-right label label-warning"><?= $this->lang->line('application_unsaved'); ?></span>
            </div>

            <textarea class="input-block-level summernote-note" name="note"
                      id="textfield"><?= $project->note; ?></textarea>
            </form>
        </div>

    </div>


<!-- <div class="row" style="display: none;">
 <div class="col-xs-12 col-sm-12">
 <div class="table-head"><?= $this->lang->line('application_invoices'); ?> <span class=" pull-right"></span></div>
<div class="table-div">
 <table class="data table" id="invoices" rel="<?= base_url() ?>" cellspacing="0" cellpadding="0">
    <thead>
      <th width="70px" class="hidden-xs"><?= $this->lang->line('application_invoice_id'); ?></th>
      <th><?= $this->lang->line('application_client'); ?></th>
      <th class="hidden-xs"><?= $this->lang->line('application_issue_date'); ?></th>
      <th class="hidden-xs"><?= $this->lang->line('application_due_date'); ?></th>
      <th><?= $this->lang->line('application_status'); ?></th>
      <th class="hidden-xs"><?= $this->lang->line('application_action'); ?></th>
    </thead>
    <?php foreach ($project_has_invoices as $value): ?>

    <tr id="<?= $value->id; ?>" >
      <td class="hidden-xs" onclick=""><?= $value->reference; ?></td>
      <td onclick=""><span class="label label-info"><?php if (isset($value->company->name)) {
    echo $value->company->name;
} ?></span></td>
      <td class="hidden-xs"><span><?php $unix = human_to_unix($value->issue_date . ' 00:00');
    echo '<span class="hidden">' . $unix . '</span> ';
    echo date($core_settings->date_format, $unix); ?></span></td>
      <td class="hidden-xs"><span class="label <?php if ($value->status == "Paid") {
        echo 'label-success';
    }
    if ($value->due_date <= date('Y-m-d') && $value->status != "Paid") {
        echo 'label-important tt" title="' . $this->lang->line('application_overdue');
    } ?>"><?php $unix = human_to_unix($value->due_date . ' 00:00');
    echo '<span class="hidden">' . $unix . '</span> ';
    echo date($core_settings->date_format, $unix); ?></span> <span class="hidden"><?= $unix; ?></span></td>
      <td onclick=""><span class="label <?php $unix = human_to_unix($value->sent_date . ' 00:00');
    if ($value->status == "Paid") {
        echo 'label-success';
    } elseif ($value->status == "Sent") {
        echo 'label-warning tt" title="' . date($core_settings->date_format, $unix);
    } ?>"><?= $this->lang->line('application_' . $value->status); ?></span></td>

      <td class="option hidden-xs" width="8%">
                <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?= base_url() ?>invoices/delete/<?= $value->id; ?>'><?= $this->lang->line('application_yes_im_sure'); ?></a> <button class='btn po-close'><?= $this->lang->line('application_no'); ?></button> <input type='hidden' name='td-id' class='id' value='<?= $value->id; ?>'>" data-original-title="<b><?= $this->lang->line('application_really_delete'); ?></b>"><i class="fa fa-times fa-lg"></i></button>
                <a href="<?= base_url() ?>invoices/update/<?= $value->id; ?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a>
      </td>
    </tr>

    <?php endforeach; ?>
    </table>
        <?php if (!$project_has_invoices) { ?>
        <div class="no-files">
            <i class="fa fa-file-text"></i><br>

            <?= $this->lang->line('application_no_invoices_yet'); ?>
        </div>
         <?php } ?>
        </div>
  </div>


</div> -->

<br>


    <div class="row">
        <div class="col-sm-12"><h2><?= $this->lang->line('application_activities'); ?></h2>
            <hr/>
        </div>

    </div>
    <div class="row">


        <div class="col-xs-12 col-sm-12">
            <div id="timelinediv">
                <ul class="timeline">
                    <li class="timeline-inverted add-comment">
                        <div class="timeline-badge gray open-comment-box"><i class="fa fa-plus"></i></div>
                        <div id="timeline-comment" class="timeline-panel">
                            <div class="timeline-heading">
                                <h5 class="timeline-title"><?= $this->lang->line('application_new_comment'); ?></h5>
                            </div>
                            <div class="timeline-body">
                                <?php
                                $attributes = array('class' => 'ajaxform', 'id' => 'replyform', 'data-reload' => 'timelinediv');
                                echo form_open('projects/activity/' . $project->id . '/add', $attributes);
                                ?>
                                <div class="form-group">
                                    <input type="text" name="subject" class="form-control" id="subject"
                                           placeholder="<?= $this->lang->line('application_subject'); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <textarea class="input-block-level summernote" id="reply" name="message"
                                              required></textarea>
                                </div>
                                <button id="send" name="send"
                                        class="btn btn-primary button-loader"><?= $this->lang->line('application_send'); ?></button>
                                </form>

                            </div>
                        </div>
                    </li>

                    <?php foreach ($project->project_has_activities as $value): ?>
                        <?php
                        $writer = FALSE;
                        if ($value->user_id != 0) {
                            $writer = $value->user->firstname . " " . $value->user->lastname;
                            $image = get_user_pic($value->user->userpic, $value->user->email);
                        } else {
                            $writer = $value->client->firstname . " " . $value->client->lastname;
                            $image = get_user_pic($value->client->userpic, $value->client->email);

                        } ?>
                        <li class="timeline-inverted">
                            <div class="timeline-badge">
                                <?php if ($writer != FALSE) { ?>
                                    <img class="img-circle timeline-profile-img tt" title="<?= $writer ?>"
                                         src="<?= $image ?>">
                                <?php } else {
                                } ?></div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h5 class="timeline-title"><?= $value->subject; ?></h5>
                                    <p>
                                        <small class="text-muted"><span class="writer"><?= $writer ?></span> <span
                                                    class="datetime"><?php echo date($core_settings->date_format . ' ' . $core_settings->date_time_format, $value->datetime); ?></span>
                                        </small>
                                    </p>
                                </div>
                                <div class="timeline-body">
                                    <p><?= $value->message; ?></p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <!-- <li class="timeline-inverted timeline-firstentry">
                        <div class="timeline-badge gray"><i class="fa fa-bolt"></i></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h5 class="timeline-title"><?= $this->lang->line('application_project_created'); ?></h5>
                            <p><small class="text-muted"><?php echo date($core_settings->date_format . ' ' . $core_settings->date_time_format, $project->datetime); ?></small></p>
                          </div>
                          <div class="timeline-body">
                            <p><?= $this->lang->line('application_project_has_been_created'); ?></p>
                          </div>
                        </div>
                      </li> -->
                </ul>
            </div>
        </div>
    </div>

<?php } //End of The if/else ?>
<script>
    //    $(document).ready(function () {
    //        new $.fn.dataTable.FixedColumns(cA, {
    //            left: true
    //        });
    //    });
</script>