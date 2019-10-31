<?php
//error_reporting(0);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
function money($number){
    echo "&#36;".money_format('%!n', (double)$number);
}
setlocale(LC_MONETARY, 'en_US');
$mailer_response = $att_ratio = $assets_ratio = $appt_sched_ratio = $kept_appt_ratio = $closing_ratio = $annuitypercent = $annuitypercent = $totalannuitycomm = $probableACAT = $probableCase = $productiontotal = $refToClient = $refBuyingAttendance = $ref_assets_ratio = $count = $acatproduction = $annuityproduction = $lifeproduction = $newRow = $otherproduction = $annuitycom = $lifecom = $othercom = $aumproduction = $retVal = $radio_ratio = ''; ?>

          <div class="row">
              <div class="col-xs-12 col-sm-12">
                  <?php if($this->user->admin === '1' || $project->name === '6') { ?>
                        <a class="btn btn-primary" href="<?=base_url()?>projects/update/<?=$project->id;?>" data-toggle="mainmodal" data-target="#mainModal"><?=$this->lang->line('application_edit_this_project');?></a>
                  <?php } ?>
                  <?php if($project->sticky == 0){ ?>
        				<a href="<?=base_url()?>projects/sticky/<?=$project->id;?>" class="btn btn-primary hidden-xs"><i class="fa fa-star"></i> <?=$this->lang->line('application_add_to_quick_access');?></a>
        			<?php }else{ ?>
        				<a href="<?=base_url()?>projects/sticky/<?=$project->id;?>" class="btn btn-primary hidden-xs"><i class="fa fa-star-o"></i> <?=$this->lang->line('application_remove_from_quick_access');?></a>
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
                           default:
                               echo "Metric Name Not Chosen";
                      } ?></h1>
                  <p class="truncate description"><?=$project->description;?></p>
                  <div class="progress tt" title="<?=$project->progress;?>%">
                    <div class="progress-bar <?php if($project->progress== "100"){ ?>done<?php } ?>" role="progressbar" aria-valuenow="<?=$project->progress;?>"  aria-valuemin="0" aria-valuemax="100" style="width: <?=$project->progress;?>%;"></div>
                  </div>
              </div>
</div>
<?php
/******* If Name/Type = Event Metrics ********/
if ($project->name == '6') {

?>
    <div class="row">
            <?php echo "<span style='padding-left: 17px; visibility: hidden;'>Sheet Number = ".$project->name."</span>"; ?>
          <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?=$this->lang->line('application_project_details');?></div>
                <div class="subcont">
                  <ul class="details col-xs-12 col-sm-6">
                    <li><span><?=$this->lang->line('application_project_id');?>:</span> <?=$project->reference;?></li>
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
                                        'other1' => 'Other 1',
                                        'other2' => 'Other 2',
                                        'other3' => 'Other 3'
                            );
                            echo $eventoptions[$project->event];
                            if(!empty($project->name_or_venue)){echo ' - '.$project->name_or_venue;} ?>
                        </li>
                        <?php else: ?>
                            <li> &nbsp; </li>
                        <?php endif; ?>
                    <li><span>Company:</span> <?php if(!isset($project->company->name)){ ?> <a href="#" class="label label-default"><?php echo $this->lang->line('application_no_client_assigned'); }else{ ?><a class="label label-success" href="<?=base_url()?>clients/view/<?=$project->company->id;?>"><?php echo $project->company->name;} ?></a></li>
	                <li><span><?=$this->lang->line('application_assigned_to');?>:</span> <?php foreach ($project->project_has_workers as $workers):?> <a class="label label-info" style="padding: 2px 5px 3px;"><?php echo $workers->user->firstname." ".$workers->user->lastname;?></a><?php endforeach;?> <!--<a href="<?=base_url()?>projects/assign/<?=$project->id;?>" class="label label-info tt" style="padding: 2px 5px 3px;" title="<?=$this->lang->line('application_assign_to');?>" data-toggle="mainmodal"><i class="fa fa-plus"></i></a></li>-->
                    </ul>
                    <ul class="details col-xs-12 col-sm-6"><span class="visible-xs divider"></span>
                    <?php if ($project->name == '6'): ?>
                            <li><span>Event Date:</span> <i class="fa fa-calendar-o"></i> <?php  echo $project->event_date;?></li>

                        <?php if ($project->event != 'radio'): ?>
                            <li><span>Location:</span> <?=$project->location;?></li>
                            <li><span>Zip Codes Used:</span> <?=$project->zip_codes;?></li>
                            <li><span>Filters Used:</span> <?=$project->filters;?></li>
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
                        <div class="table-head"><?=$this->lang->line('application_event_metrics');?></div>
                        <div class="subcont">
                        <?php if ($project->event === 'platinumreferral') { ?>
                            <ul class="details col-xs-12 col-sm-3 event-metrics">
                                <li><span>Number of Client Responses:</span> <?=$project->client_response;?></li>
                                <li><span>Number of Referral Responses:</span> <?=$project->referral_response;?></li>
                                <?php if (!empty($project->referral_response) && !empty($project->client_response)) { $refToClient = $project->referral_response / ($project->referral_response + $project->client_response); } else { $refToClient = 0;}?>
                                <li class="responseratio"><span>Referral to Client Response Ratio:</span> <?php echo round((float)$refToClient * 100, 1 ) . '%'; ?></li>
                            </ul>
                            <ul class="details col-xs-12 col-sm-3 event-metrics">
                                <li><span>Number of Client Attendees:</span> <?=$project->client_attendee;?></li>
                                <li><span>Number of Referral Attendees:</span> <?=$project->referral_attendee;?></li>
                                <li><span>Total Attended:</span> <?=$project->attended;?></li>
                            </ul>
                            <ul class="details col-xs-12 col-sm-3 event-metrics">
                                <li><span>Total Referral Buying Units Attended:</span> <?=$project->bu_attended;?></li>
                                <li>&nbsp;</li>
                                <?php if(!empty($project->bu_attended) && !empty($project->client_attendee) && !empty($project->referral_attendee)) {
                                    $refBuyingAttendance = $project->bu_attended / ($project->client_attendee + $project->referral_attendee);
                                } ?>
                                <li class="responseratio"><span>Referral Buying Unit Attended Ratio:</span> <?php echo round((float)$refBuyingAttendance * 100, 1 ) . '%'; ?></li>
                            </ul>
                            <ul class="details last col-xs-12 col-sm-3 event-metrics"><span class="visible-xs divider"></span>
                                <li><span>People with Assets:</span> <?=$has_assets;?></li>
                                <li> &nbsp; </li>
                                <?php if (!empty($has_assets) && !empty($project->bu_attended)) { $ref_assets_ratio = $has_assets/$project->bu_attended; } ?>
                                <li class="responseratio"><span>Percent with Assets:</span> <?php echo round((float)$ref_assets_ratio * 100, 1 ) . '%'; ?></li>
                            </ul>
                        <?php } else { ?>
                        <?php if ($project->event != 'radio'): ?>
                            <ul class="details col-xs-12 col-sm-3 event-metrics">
                                <li><span>Number of Mailers:</span> <?=$project->number_mailers;?></li>
                                <li><span>Number of Ads:</span> <?=$project->ad;?></li>
                                <li><span>Number of Other Invites:</span> <?=$project->other_invite;?></li>
                                <li class="responseratio"><span>Total Responses:</span> <?=$project->total_responses;?></li>
                            </ul>
                            <ul class="details col-xs-12 col-sm-3 event-metrics"><span class="visible-xs divider"></span>
                                <li><span>Mailer Cost:</span> <?php money($project->mailers_cost/100);?></li>
                                <li><span>Ads Cost:</span> <?php money($project->ad_cost/100);?></li>
                                <li><span>Other Cost:</span> <?php money($project->other_invite_cost/100);?></li>
                                <?php if (!empty($project->total_responses) && !empty($project->number_mailers)) {
                                    $mailer_response = $project->total_responses/$project->number_mailers; } ?>
                                <li class="responseratio"><span>Response Ratio:</span> <?php echo round((float)$mailer_response * 100, 1 ). '%'; ?></li>
                            </ul>
                            <ul class="details col-xs-12 col-sm-3 event-metrics"><span class="visible-xs divider"></span>
                                <li><span>Total &#35; Attended:</span> <?=$project->attended;?></li>
                                <li> &nbsp; </li>
                                <li> &nbsp; </li>
                                <?php if (!empty($project->attended) && !empty($project->total_responses)) { $att_ratio = $project->attended/$project->total_responses; } ?>
                                <li class="responseratio"><span>Attendance Ratio:</span> <?php echo round((float)$att_ratio * 100, 1 ) . '%'; ?></li>
                            </ul>
                            <ul class="details last col-xs-12 col-sm-3 event-metrics"><span class="visible-xs divider"></span>
                                <li> &nbsp; </li>
                                <li> <span>People with Assets:</span> <?=$has_assets;?> </li>
                                <li> <span>Total Buying Units Attended:</span> <?=$project->bu_attended;?> </li>
                                <?php if (!empty($has_assets) && !empty($project->attended)) { $assets_ratio = $has_assets/$project->bu_attended; } ?>
                                <li class="responseratio"><span>Percent with Assets:</span> <?php echo round((float)$assets_ratio * 100, 1 ) . '%'; ?></li>
                            </ul>

                        <?php else: /*If Radio*/ ?>
                            <ul class="details col-xs-12 col-sm-12 event-metrics">
                                <li><span>Call Ins:</span> <?=$project->total_responses;?></li>
                                <li><span>People with Assets:</span> <?=$project->people_with_assets;?></li>
                                <?php if (!empty($project->people_with_assets) && !empty($project->total_responses)) { $radio_ratio = $project->people_with_assets/$project->total_responses; } ?>
                                <li class="responseratio"><span>Percent with Assets:</span> <?php echo round((float)$radio_ratio * 100, 1 ) . '%'; ?></li>
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
                        <div class="table-head"><?=$this->lang->line('application_appointment_closing');?></div>
                        <div class="subcont">
                            <ul class="details col-xs-12 col-sm-4 appointment-closing">
                                <li><span>Total Appointments Scheduled:</span> <?=$scheduled_appts;?></li>
                                <li>&nbsp;</li>
                                <?php if (!empty($project->bu_attended)) { $appt_sched_ratio = $scheduled_appts/$project->bu_attended; } ?>
                                <li class="responseratio"><span>Appointments Scheduled Ratio:</span> <?php echo round((float)$appt_sched_ratio * 100, 1 ) . '%'; ?></li>
                            </ul>
                            <ul class="details col-xs-12 col-sm-4 appointment-closing">
                                <li><span>1st Appointments Pending:</span> <?php echo ($scheduled_appts - $kept_appts);?></li>
                                <li><span>Appointments Kept:</span> <?=$kept_appts;?></li>
                                <?php if (!empty($scheduled_appts) && !empty($kept_appts)) { $kept_appt_ratio = $kept_appts/$scheduled_appts; } ?>
                                <li class="responseratio"><span>Kept Appointments Ratio:</span> <?php echo round((float)$kept_appt_ratio * 100, 1 ) . '%'; ?></li>
                            </ul>
                            <ul class="details col-xs-12 col-sm-4 last appointment-closing"><span class="visible-xs divider"></span>
                                <li><span>Prospects Closed:</span> <?=$closed_appts?></li>
                                <li> <span>People with Assets:</span> <?=$has_assets;?> </li>
                                <?php if (!empty($closed_appts) && !empty($has_assets)) { $closing_ratio = $closed_appts/$has_assets; } ?>
                                <li class="responseratio"><span>Closing Ratio:</span> <?php echo round((float)$closing_ratio * 100, 1 ) . '%'; ?></li>
                            </ul>

                            <br clear="both">
                        </div>
                    </div>
                </div>
                <?php
                //echo "<pre class='testing'><h1>Testing</h1>";
                foreach($product as  &$entry){
                    if ($entry->production_type == 'acat') { $acatproduction += ($entry->prem_paid/100); }
                    else if ($entry->production_type == 'annuity') { $annuityproduction += $entry->prem_paid/100; $annuitycom += ($entry->comp_agent_percent/100) * ($entry->prem_paid/100); }
                    else if ($entry->production_type == 'other' || $entry->production_type == 'life') { $otherproduction += $entry->prem_paid/100; $othercom += ($entry->comp_agent_percent/100) * ($entry->prem_paid/100); }

                    // echo $counting.'. '.$entry->firstname.' '.$entry->lastname.'[ '.(($entry->client_prospect == '1') ? 'Client' : 'Prospect').' ]'."\r\n";
                    // echo '&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; Production Type '.$entry->production_type."\r\n";
                    // echo "acat = ".($entry->acat == 1 ? 'yes' : 'no')."\r\n\r\n";
                }
                echo "</pre>";
                ?>
                <!-- Paid Business Metrics -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="table-head"><?=$this->lang->line('application_paid_business_metrics');?></div>
                        <div class="subcont">
                            <ul class="details col-xs-12 col-sm-6 paid-business-metrics">
                                <li><span>A.C.A.T.:</span> <?php money($acatproduction);?></li>
                                <li><span>Total Annuity Premium:</span> <?php money($annuityproduction);?></li>
                                <?php if (!empty($acatproduction) && !empty($annuityproduction)) { $annuitypercent = ($annuityproduction/$acatproduction); } ?>
                                <li class="responseratio"><span>Percentage to Annuity:</span> <?php echo round((float)$annuitypercent * 100, 1 ) . '%'; ?></li>
                            </ul>
                            <ul class="details col-xs-12 col-sm-6 paid-business-metrics">
                                <li><span>Total Annuity Commission:</span> <?php money($annuitycom); ?></li>
                                <li><span>Total Other Premium:</span> <?php money($otherproduction);?></li>
                                <li><span>Total &#36; Other Commission:</span> <?php money($othercom); ?></li>
                            </ul>
                            <br clear="both">
                        </div>
                    </div>
                </div>
                <!-- ROI Totals -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="table-head"><?=$this->lang->line('application_roi_totals');?></div>
                        <div class="subcont">
                            <ul class="details col-xs-12 col-sm-12 paid-business-metrics">
                                <li class="responseratio"><span>Total All Commisions:</span> <?php money($othercom + $annuitycom); ?></li>
                                <li><span>Total Event Cost:</span> <?php money(($project->total_event_cost/100) + ($project->mailers_cost/100) + ($project->ad_cost/100) + ($project->other_invite_cost/100)); ?></li>
                                <li class="responseratio"><span>Total Gross Profit:</span> <?php money(($othercom + $annuitycom) - (($project->total_event_cost/100) + ($project->mailers_cost/100) + ($project->ad_cost/100) + ($project->other_invite_cost/100)) ); ?></li>
                            </ul>
                            <br clear="both">
                        </div>
                    </div>
                </div>
                <?php } /******** End If Name/Type = Event Metrics ********/ ?>




                <?php /******* Production ********/
                if ($project->name == '1') {

                    $eventarray = array(0 => ' ', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
                    foreach ($projects as $p){
                        $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                    }
                    $fmo_array = array('' => 'Choose an FMO...', 'cmic' => 'Creative One', 'eca' => 'ECA Marketing', 'aaa' => 'Advisors&rsquo; Academy', 'other' => 'Other' );
                    $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'aum' => 'AUM', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production' );
                ?>

                <!-- Title for Chart -->
                <div class="row">
                     <div class="col-xs-12 col-sm-12">
                        <div class="table-head">
                            Production - <?php foreach ($production_by_year as &$entry) {
                                if ($entry->production_type == 'acat') { $acatproduction += ($entry->prem_paid/100); }
                                else if ($entry->production_type == 'aum') { $aumproduction += $entry->prem_paid/100; }
                                else if ($entry->production_type == 'annuity') { $annuityproduction += $entry->prem_paid/100; }
                                else if ($entry->production_type == 'life') { $lifeproduction += $entry->prem_paid/100; }
                                else if ($entry->production_type == 'other') { $otherproduction += $entry->prem_paid/100; }
                            } ?>
                            <span class="acat-production">ACAT <?php money($acatproduction); ?></span>
                            <span class="aum-production">AUM <?php money($aumproduction); ?></span>
                            <span class="annuity-production">Annuity <?php money($annuityproduction); ?></span>
                            <span class="life-production">Life <?php money($lifeproduction); ?></span>
                            <span class="other-production">Other <?php money($otherproduction); ?></span>
                            <span class="pull-right" style="margin-right: 13%;">
                                <a href="<?=base_url()?>projects/productionentry/<?=$project->id;?>/add" class="btn btn-primary" data-toggle="mainmodal">Add Production Entry</a>
                            </span>
                        </div>
                        <div class="subcont">
                            <!-- hover display no-wrap -->
                            <table id="production" class="table table-striped table-hover dt-responsive " width="100%">
                                <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Lead Source</th>
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
                                    <?php foreach ($production as &$entry) {

                                        //To populate lead source for selected client;
                                        $client_array = ['0' => 'Please select client...'];
                                        $client_events = ['0' => '0'];
                                        foreach ($all_clients as $acs) {
                                            $client_array[$acs->id] = $acs->firstname.' '.$acs->lastname;
                                            $client_events[$acs->id] = $acs->event_id;
                                        } ?>
                                        <tr id="<?=$entry->id;?>" href="<?=base_url()?>projects/productionentry/<?=$entry->pid;?>/update/<?=$entry->id;?>" data-toggle="mainmodal">
                                            <td><?php echo $entry->firstname.' '.$entry->lastname; ?></td>
                                            <td><?php echo (isset($entry->client_id) ? $eventarray[$entry->event_id] : 'Please Assign Client' ); ?></td>
                                            <td><?php echo $production_type_array[$entry->production_type]; ?></td>
                                            <td><?php echo ($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : $fmo_array[$entry->fmo]); ?></td>
                                            <td><?php echo ($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : $entry->product_co); ?></td>
                                            <td><?php echo ($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : $entry->product_name); ?></td>
                                            <td><?php $unix = human_to_unix($entry->app_date_received.' 00:00');
                                                if(!empty($entry->app_date_received)) { echo date($core_settings->date_format, $unix); } else { echo ''; }
                                            ?></td>
                                            <td><?php if(!empty($entry->production_amount)) { echo money($entry->production_amount/100); } else { echo ''; } ?></td>
                                            <td><?php $unix = human_to_unix($entry->production_submitted.' 00:00');
                                            if(!empty($entry->production_submitted)) { echo date($core_settings->date_format, $unix); } else { echo ''; } ?></td>
                                            <td><?php
                                                    $unix = human_to_unix($entry->prem_paid_month.' 00:00');
                                                    if(!empty($entry->prem_paid_month)) { echo date($core_settings->date_format, $unix); } else { echo ''; } ?></td>
                                            <td><?php if(!empty($entry->prem_paid)) { echo money($entry->prem_paid/100); } else { echo ''; } ?></td>
                                            <td><?php if ($entry->production_type == 'acat' || $entry->production_type == 'aum') { echo "N/A"; } else { echo $entry->comp_agent_percent.' &percnt;'; } ?></td>
                                            <td><?php echo ($entry->production_type == 'acat' || $entry->production_type == 'aum' ? 'N/A' : money(($entry->comp_agent_percent/100) * ($entry->prem_paid/100))); ?></td>
                                            <!-- <td><a href="<?=base_url()?>projects/productionentry/<?=$entry->pid;?>/update/<?=$entry->id;?>" class="edit-button" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a></td> -->
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
                <?php } /******** End Production ********/

                /********* Start Hot Prospects / Hot Prospect List********/
                if ($project->name == '4') { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                       <div class="table-head">
                           Hot Prospects - <span data-toggle="popover" data-content="Weighted Probable ACAT Amount.">
                               <?php foreach ($hot_prospect as &$hp) {$probableACAT += ($hp->p_probable_acat_size/100)*($hp->closing_probability/100);} money($probableACAT);?></span>
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
                                       if( strtotime($hp->last_contact) >= strtotime('-14 day') ) { ?>
                                       <tr href="<?=base_url()?>clients/update/<?=$hp->id;?>" data-toggle="mainmodal">
                                           <td><?php echo $hp->firstname.' '.$hp->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hp->last_contact)); ?></td>
                                           <td><?php echo $hp->follow_up; ?></td>
                                           <td><?php money($hp->p_probable_acat_size/100); ?></td>
                                           <td><?php echo $hp->closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100)) ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100) * ($hp->p_annuity_probability/100)) ?></td>
                                           <td><?php echo $hp->prospect_comment; ?></td>
                                           <td style="background: #44ff44;">Added within last two weeks</td>
                                       </tr>
                                   <?php }
                                   else if (  (strtotime($hp->last_contact) >= strtotime('-90 day')) && ( strtotime($hp->last_contact) < strtotime('-60 day')) ) {?>
                                       <tr href="<?=base_url()?>clients/update/<?=$hp->id;?>" data-toggle="mainmodal">
                                           <td><?php echo $hp->firstname.' '.$hp->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hp->last_contact)); ?></td>
                                           <td><?php echo $hp->follow_up; ?></td>
                                           <td><?php money($hp->p_probable_acat_size/100); ?></td>
                                           <td><?php echo $hp->closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100)) ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100) * ($hp->p_annuity_probability/100)) ?></td>
                                           <td><?php echo $hp->prospect_comment; ?></td>
                                           <td style="background: #ff4444;">Third month on list</td>
                                       </tr>
                                   <?php }
                                   else if (( strtotime($hp->last_contact) < strtotime('-90day') )) { ?>
                                       <tr style="color: #c3c3c3;" href="<?=base_url()?>clients/update/<?=$hp->id;?>" data-toggle="mainmodal">
                                           <td><?php echo $hp->firstname.' '.$hp->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hp->last_contact)); ?></td>
                                           <td><?php echo $hp->follow_up; ?></td>
                                           <td><?php money($hp->p_probable_acat_size/100); ?></td>
                                           <td><?php echo $hp->closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100)) ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100) * ($hp->p_annuity_probability/100)) ?></td>
                                           <td><?php echo $hp->prospect_comment; ?></td>
                                           <td>Old</td>
                                       </tr>
                                   <?php }
                                   else { ?>
                                       <tr href="<?=base_url()?>clients/update/<?=$hp->id;?>" data-toggle="mainmodal">
                                           <td><?php echo $hp->firstname.' '.$hp->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hp->last_contact)); ?></td>
                                           <td><?php echo $hp->follow_up; ?></td>
                                           <td><?php money($hp->p_probable_acat_size/100); ?></td>
                                           <td><?php echo $hp->closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100)) ?></td>
                                           <td><?php money(($hp->p_probable_acat_size/100)*($hp->closing_probability/100) * ($hp->p_annuity_probability/100)) ?></td>
                                           <td><?php echo $hp->prospect_comment; ?></td>
                                           <td>Within three months</td>
                                       </tr>
                                   <?php } } ?>
                               </tbody>
                           </table>


                       </div>
                   </div>
               </div>
                <?php } /*********** End Hot Prospects ***********/

                /********* Start Hot Clients ********/
                if ($project->name == '5') { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                       <div class="table-head">
                           Hot Clients - <span data-toggle="popover" data-content="Total possible for cases.">
                               <?php foreach ($hot_client as &$hc) {$probableCase += ($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100);} money($probableCase);?></span>
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
                                       if( strtotime($hc->c_last_contact) >= strtotime('-14 day') ) { ?>
                                       <tr href="<?=base_url()?>clients/update/<?=$hc->id;?>" data-toggle="mainmodal">
                                           <td><?php echo $hc->firstname.' '.$hc->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hc->c_last_contact)); ?></td>
                                           <td><?php echo $hc->c_follow_up; ?></td>
                                           <td><?php money($hc->c_probable_acat_size/100); ?></td>
                                           <td><?php echo $hc->c_closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100)); ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100) * ($hc->c_annuity_probability/100)) ?></td>
                                           <td><?php echo $hc->client_comment; ?></td>
                                           <td style="background: #44ff44;">Added within two weeks</td>
                                       </tr>
                                   <?php }
                                   else if (  (strtotime($hc->c_last_contact) >= strtotime('-90 day')) && ( strtotime($hc->c_last_contact) < strtotime('-60 day')) ) {?>
                                       <tr href="<?=base_url()?>clients/update/<?=$hc->id;?>" data-toggle="mainmodal">
                                           <td><?php echo $hc->firstname.' '.$hc->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hc->c_last_contact)); ?></td>
                                           <td><?php echo $hc->c_follow_up; ?></td>
                                           <td><?php money($hc->c_probable_acat_size/100); ?></td>
                                           <td><?php echo $hc->c_closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100)); ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100) * ($hc->c_annuity_probability/100)) ?></td>
                                           <td><?php echo $hc->client_comment; ?></td>
                                           <td style="background: #ff4444;">Third month on list</td>
                                       </tr>
                                   <?php }
                                   else if (( strtotime($hc->c_last_contact) < strtotime('-90 day') )) { ?>
                                       <tr href="<?=base_url()?>clients/update/<?=$hc->id;?>" data-toggle="mainmodal" style="color: #c3c3c3;">
                                           <td><?php echo $hc->firstname.' '.$hc->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hc->c_last_contact)); ?></td>
                                           <td><?php echo $hc->c_follow_up; ?></td>
                                           <td><?php money($hc->c_probable_acat_size/100); ?></td>
                                           <td><?php echo $hc->c_closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100)); ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100) * ($hc->c_annuity_probability/100)) ?></td>
                                           <td><?php echo $hc->client_comment; ?></td>
                                           <td>Old</td>
                                       </tr>
                                   <?php }
                                   else { ?>
                                       <tr href="<?=base_url()?>clients/update/<?=$hc->id;?>" data-toggle="mainmodal">
                                           <td><?php echo $hc->firstname.' '.$hc->lastname; ?></td>
                                           <td><?php echo date("m/d/Y", strtotime($hc->c_last_contact)); ?></td>
                                           <td><?php echo $hc->c_follow_up; ?></td>
                                           <td><?php money($hc->c_probable_acat_size/100); ?></td>
                                           <td><?php echo $hc->c_closing_probability.'&percnt;'; ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100)); ?></td>
                                           <td><?php money(($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100) * ($hc->c_annuity_probability/100)) ?></td>
                                           <td><?php echo $hc->client_comment; ?></td>
                                           <td>Within Three Months</td>
                                       </tr>
                                   <?php } } ?>
                               </tbody>
                           </table>


                       </div>
                   </div>
                </div>
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
                                    <th>Spouse</th>
                                    <th>ACAT</th>
                                    <th>AUM</th>
                                    <th>Annuity</th>
                                    <th>Life</th>
                                    <th>Other</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($clientslist as  $cl):  $count++; ?>
                                <tr href="<?=base_url()?>clients/update/<?=$cl->id;?>" data-toggle="mainmodal">
                                    <td><?php echo $cl->firstname.' '.$cl->lastname; ?></td>
                                    <td><?php
                                        $whichHot = '';
                                        if ($cl->hot_client == 1) {
                                            $whichHot = 'Hot Client';
                                        } elseif ($cl->hot_prospect == 1) {
                                            $whichHot = 'Hot Prospect';
                                        } else {
                                            $whichHot = 'Cold '.$retVal = ($cl->client_prospect == 1) ? 'Client' : 'Prospect' ;
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
                                    <td><?php echo $cl->spouse; ?></td>
                                    <td><?php echo ($cl->acat == 1) ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo ($cl->aum == 1) ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo ($cl->annuity_app == 1) ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo ($cl->life_submitted == 1) ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo ($cl->other == 1) ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <?php endforeach;?>

                                <?php if($count == 0) { ?>
                                    <div class="alert alert-info alert-dismissible fade in" role="alert" style="text-align: center;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 id="oh-snap!-you-got-an-error!"><i class="glyphicon glyphicon-info-sign" style="top: 2px;"></i> No clients or prospects!</h4>
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
                foreach ($projects as $p){
                    $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                }
                $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production' );
                //To populate lead source for selected client;
                $client_array = ['0' => 'Please select client...'];
                $client_events = ['0' => '0'];
                foreach ($all_clients as $acs) {
                    $client_array[$acs->id] = $acs->firstname.' '.$acs->lastname;
                    $client_events[$acs->id] = $acs->event_id;
                }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                       <div class="table-head dark">
                           Pending ACATs <?php echo '- ';
                           $pendingACATs = 0;
                           foreach ($production_by_submit as &$pr) {
                               $unix_year = $paid_year = 0;
                               $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                               if(!empty($pr->production_submitted) && $pr->production_type == 'acat' && $paid_year != $selected_year ) {
                                   $pendingACATs += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Date Submitted</th>
                                       <th>&dollar; Pending</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_submit as &$pr) {
                                       $unix_year = $paid_year = 0;
                                       $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                                       if(!empty($pr->production_submitted) && $pr->production_type == 'acat' && $paid_year != $selected_year ) {
                                           if( strtotime($pr->production_submitted) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                               <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                                   <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                                   <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                                   <td><?php $unix = human_to_unix($pr->production_submitted.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                                   <td><?php money($pr->production_amount/100); ?></td>
                                                   <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
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
                           $notsubmittedACATs = 0;
                           foreach ($production_by_received as &$pr) {
                               if(empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid)) {
                                   $notsubmittedACATs += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>App Rec Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_received as &$pr) {
                                       if(empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid)) {
                                           if( strtotime($pr->app_date_received) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                               <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php echo $pr->app_date_received; ?></td>
                                           <td><?php money($pr->production_amount/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                           foreach ($production_by_year as &$pr) {
                               if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'acat') {
                                   $completedACATs += ($pr->prem_paid/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Paid Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_year as &$pr) {
                                       if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'acat') { ?>
                                       <tr href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php $unix = human_to_unix($pr->prem_paid_month.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                           <td><?php money($pr->prem_paid/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                foreach ($projects as $p){
                    $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                }
                $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production' );
                //To populate lead source for selected client;
                $client_array = ['0' => 'Please select client...'];
                $client_events = ['0' => '0'];
                foreach ($all_clients as $acs) {
                    $client_array[$acs->id] = $acs->firstname.' '.$acs->lastname;
                    $client_events[$acs->id] = $acs->event_id;
                }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                       <div class="table-head dark">
                           Pending New Business <?php echo '- ';
                           $pendingLife = 0;
                           foreach ($production_by_submit as &$pr) {
                               $unix_year = $paid_year = 0;
                               $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                               if(!empty($pr->production_submitted) && $pr->production_type == 'aum' && $paid_year != $selected_year ) {
                                   $pendingLife += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Date Submitted</th>
                                       <th>&dollar; Pending</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_submit as &$pr) {
                                       $unix_year = $paid_year = 0;
                                       $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                                       if(!empty($pr->production_submitted) && $pr->production_type == 'aum' && $paid_year != $selected_year ) {
                                           if( strtotime($pr->production_submitted) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                               <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                                   <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                                   <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                                   <td><?php $unix = human_to_unix($pr->production_submitted.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                                   <td><?php money($pr->production_amount/100); ?></td>
                                                   <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
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
                           foreach ($production_by_received as &$pr) {
                               if( empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid) ) {
                                   $notsubmittedLife += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>App Rec Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_received as &$pr) {
                                       if(empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid)) {
                                           if( strtotime($pr->app_date_received) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                       <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php echo $pr->app_date_received; ?></td>
                                           <td><?php money($pr->production_amount/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                               if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'aum') {
                                   $completedLife += ($pr->prem_paid/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Paid Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_year as &$pr) {
                                       if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'aum') { ?>
                                       <tr href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php $unix = human_to_unix($pr->prem_paid_month.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                           <td><?php money($pr->prem_paid/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                foreach ($projects as $p){
                    $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                }
                $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production' );
                //To populate lead source for selected client;
                $client_array = ['0' => 'Please select client...'];
                $client_events = ['0' => '0'];
                foreach ($all_clients as $acs) {
                    $client_array[$acs->id] = $acs->firstname.' '.$acs->lastname;
                    $client_events[$acs->id] = $acs->event_id;
                }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                       <div class="table-head dark">
                           Pending New Business <?php echo '- ';
                           $annuitiesPending = 0;
                           foreach ($production_by_submit as &$pr) { // && (empty($pr->prem_paid))
                               $unix_year = $paid_year = 0;
                               $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                               if(!empty($pr->production_submitted) && $pr->production_type == 'annuity' && $paid_year != $selected_year ) {
                                   $annuitiesPending += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Date Submitted</th>
                                       <th>&dollar; Pending</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_submit as &$pr) {
                                       $unix_year = $paid_year = 0;
                                       $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                                       if(!empty($pr->production_submitted) && $pr->production_type == 'annuity' && $paid_year != $selected_year ) {
                                           if( strtotime($pr->production_submitted) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                           <!--<?php
                                           // && (empty($pr->prem_paid))
                                           //echo $paid_year.' '.$pr->firstname.' '.$pr->lastname;
                                           //'Date is '.$unix_year.'. Years are '.date('Y').' = '.$paid_year;
                                           //$unix2 = human_to_unix($pr->prem_paid_month.' 00:00'); echo date('Y', $unix2);
                                           ?>-->
                                               <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                                   <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                                   <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                                   <td><?php $unix = human_to_unix($pr->production_submitted.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                                   <td><?php money($pr->production_amount/100); ?></td>
                                                   <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
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
                           foreach ($production_by_received as &$pr) {
                               if(empty($pr->production_submitted) && $pr->production_type == 'annuity'  && empty($pr->prem_paid)) {
                                   $notsubmittedAnnuities += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>App Rec Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_received as &$pr) {
                                       if(empty($pr->production_submitted) && $pr->production_type == 'annuity'  && empty($pr->prem_paid)) {
                                           if( strtotime($pr->app_date_received) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                           <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                               <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                               <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                               <td><?php echo $pr->app_date_received; ?></td>
                                               <td><?php money($pr->production_amount/100); ?></td>
                                               <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                           </tr>
                                   <?php } } ?>
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
                               if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'annuity') {
                                   $completedAnnuities += ($pr->prem_paid/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Paid Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_year as &$pr) {
                                       if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'annuity') { ?>
                                       <tr href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php $unix = human_to_unix($pr->prem_paid_month.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                           <td><?php money($pr->prem_paid/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                foreach ($projects as $p){
                    $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                }
                $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production' );
                //To populate lead source for selected client;
                $client_array = ['0' => 'Please select client...'];
                $client_events = ['0' => '0'];
                foreach ($all_clients as $acs) {
                    $client_array[$acs->id] = $acs->firstname.' '.$acs->lastname;
                    $client_events[$acs->id] = $acs->event_id;
                }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                       <div class="table-head dark">
                           Pending New Business <?php echo '- ';
                           $pendingLife = 0;
                           foreach ($production_by_submit as &$pr) {
                               $unix_year = $paid_year = 0;
                               $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                               if(!empty($pr->production_submitted) && $pr->production_type == 'life' && $paid_year != $selected_year ) {
                                   $pendingLife += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Date Submitted</th>
                                       <th>&dollar; Pending</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_submit as &$pr) {
                                       $unix_year = $paid_year = 0;
                                       $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                                       if(!empty($pr->production_submitted) && $pr->production_type == 'life' && $paid_year != $selected_year ) {
                                           if( strtotime($pr->production_submitted) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                               <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                                   <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                                   <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                                   <td><?php $unix = human_to_unix($pr->production_submitted.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                                   <td><?php money($pr->production_amount/100); ?></td>
                                                   <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
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
                           foreach ($production_by_received as &$pr) {
                               if(empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid)) {
                                   $notsubmittedLife += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>App Rec Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_received as &$pr) {
                                       if(empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid)) {
                                           if( strtotime($pr->app_date_received) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                       <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php echo $pr->app_date_received; ?></td>
                                           <td><?php money($pr->production_amount/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                               if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'life') {
                                   $completedLife += ($pr->prem_paid/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Paid Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_year as &$pr) {
                                       if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'life') { ?>
                                       <tr href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php $unix = human_to_unix($pr->prem_paid_month.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                           <td><?php money($pr->prem_paid/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                foreach ($projects as $p){
                    $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                }
                $production_type_array = array('' => '<span style="color:#d9534f">No type selected</span>', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production' );
                //To populate lead source for selected client;
                $client_array = ['0' => 'Please select client...'];
                $client_events = ['0' => '0'];
                foreach ($all_clients as $acs) {
                    $client_array[$acs->id] = $acs->firstname.' '.$acs->lastname;
                    $client_events[$acs->id] = $acs->event_id;
                }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                       <div class="table-head dark">
                           Pending New Business <?php echo '- ';
                           $pendingOther = 0;
                           foreach ($production_by_submit as &$pr) {
                               $unix_year = $paid_year = 0;
                               $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                               if(!empty($pr->production_submitted) && $pr->production_type == 'other' && $paid_year != $selected_year ) {
                                   $pendingOther += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Date Submitted</th>
                                       <th>&dollar; Pending</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_submit as &$pr) {
                                       $unix_year = $paid_year = 0;
                                       $unix_year = human_to_unix($pr->prem_paid_month.' 00:00'); $paid_year = date('Y', $unix_year);
                                       if(!empty($pr->production_submitted) && $pr->production_type == 'other' && $paid_year != $selected_year ) {
                                           if( strtotime($pr->production_submitted) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                               <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                                   <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                                   <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                                   <td><?php $unix = human_to_unix($pr->production_submitted.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                                   <td><?php money($pr->production_amount/100); ?></td>
                                                   <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
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
                           foreach ($production_by_received as &$pr) {
                               if(empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid)) {
                                   $notsubmittedOther += ($pr->production_amount/100);
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
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>App Rec Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_received as &$pr) {
                                       if(empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid)) {
                                           if( strtotime($pr->app_date_received) >= strtotime('-7 day') ) { $newRow = 'style="background: rgb(166, 40, 40);"'; } else { $newRow = ''; } ?>
                                               <tr <?=$newRow?> href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php echo $pr->app_date_received; ?></td>
                                           <td><?php money($pr->production_amount/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
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
                               if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'other') {
                                   $completedOther += ($pr->prem_paid/100);
                               }
                           }
                           echo money($completedOther); ?>
                           <span class="pull-right" style="margin-right: 13%;">
                               <a></a>
                           </span>
                       </div>
                       <div class="subcont greenbg">
                           <!-- hover display no-wrap -->
                           <table id="completedAcats" class="table dt-responsive" rel="<?=base_url()?>" width="100%">
                               <col style="width:15%">
                               <col style="width:15%">
                               <col style="width:10%">
                               <col style="width:10%">
                               <col style="width:50%">
                               <thead>
                                   <tr>
                                       <th>Lead Source</th>
                                       <th>Name</th>
                                       <th>Paid Date</th>
                                       <th>&dollar; Amount</th>
                                       <th>Notes</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php foreach ($production_by_year as &$pr) {
                                       if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'other') { ?>
                                       <tr href="<?=base_url()?>projects/productionentry/<?=$pr->pid?>/update/<?=$pr->id?>" data-toggle="mainmodal">
                                           <td><?php echo (isset($pr->client_id) ? $eventarray[$pr->event_id] : 'Please Assign Client' ); ?></td>
                                           <td><?php echo $pr->firstname.' '.$pr->lastname; ?></td>
                                           <td><?php $unix = human_to_unix($pr->prem_paid_month.' 00:00'); echo date($core_settings->date_format, $unix); ?></td>
                                           <td><?php money($pr->prem_paid/100); ?></td>
                                           <td style="max-width: 200px; white-space: normal;"><?php echo $pr->production_notes; ?></td>
                                       </tr>
                                   <?php } } ?>
                               </tbody>
                           </table>


                       </div>
                   </div>
                </div>
            <?php } ?>

<?php if ($project->name === '0'){
    /*Hiding this*/ ?>
<div class="row">
               <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?=$this->lang->line('application_tasks');?> <span class=" pull-right"><button class="btn btn-default sortListTrigger" ><i class="fa fa-sort-amount-desc"></i></button> <a href="<?=base_url()?>projects/tasks/<?=$project->id;?>/add" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_add_task');?></a></span></div>
                <div class="subcont">
                  <ul class="todo sortlist">
                    	<?php
				$count = 0;
				foreach ($project->project_has_tasks as $value):  $count = $count+1; ?>

				    <li class="<?=$value->status;?> priority<?=$value->priority;?>"><a href="<?=base_url()?>projects/tasks/<?=$project->id;?>/check/<?=$value->id;?>" class="ajax-silent task-check"></a>
				    	<input name="form-field-checkbox" class="checkbox-nolabel task-check" type="checkbox" data-link="<?=base_url()?>projects/tasks/<?=$project->id;?>/check/<?=$value->id;?>" <?php if($value->status == "done"){echo "checked";}?>/>
				    	<span class="lbl"> <p class="truncate name"><?=$value->name;?></p></span>
				    	<span class="pull-right">
                                  <?php if ($value->user_id != 0) {  ?><img class="img-circle list-profile-img tt"  title="<?=$value->user->firstname;?> <?=$value->user->lastname;?>"  src="<?php
                if($value->user->userpic != 'no-pic.png'){
                  echo base_url()."files/media/".$value->user->userpic;
                }else{
                  echo get_gravatar($value->user->email);
                }
                 ?>"><?php } ?>
                                    <?php if ($value->public != 0) {  ?><span class="list-button"><i class="fa fa-eye tt" title="" data-original-title="<?=$this->lang->line('application_task_public');?>"></i></span><?php } ?>
                                    <a href="<?=base_url()?>projects/tasks/<?=$project->id;?>/update/<?=$value->id;?>" class="edit-button" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a>
                                  </span>
                    <div class="todo-details">
                    <div class="row">
                        <div class="col-sm-3">
                        <ul class="details">
                            <li><span><?=$this->lang->line('application_priority');?>:</span> <?php switch($value->priority){case "0": echo $this->lang->line('application_no_priority'); break; case "1": echo $this->lang->line('application_low_priority'); break; case "2": echo $this->lang->line('application_med_priority'); break; case "3": echo $this->lang->line('application_high_priority'); break;};?></li>
                            <?php if($value->value != 0){ ?><li><span><?=$this->lang->line('application_value');?>:</span> <?=$value->value;?></li><?php } ?>
                            <?php if($value->due_date != ""){ ?><li><span><?=$this->lang->line('application_due_date');?>:</span> <?php  $unix = human_to_unix($value->due_date.' 00:00'); echo date($core_settings->date_format, $unix);?></li><?php } ?>
                            <li><span><?=$this->lang->line('application_assigned_to');?>:</span> <?php if(isset($value->user->lastname)){ echo $value->user->firstname." ".$value->user->lastname;}else{$this->lang->line('application_not_assigned');}?> </li>
                            <li><span>Custom: </span><?php echo "placeholder"; ?> </li>
                         </ul>

                        </div>
                        <div class="col-sm-9"><h3><?=$this->lang->line('application_description');?></h3> <p><?=$value->description;?></p></div>

                    </div>
                    </div>

					</li>
				 <?php endforeach;?>
				 <?php if($count == 0) { ?>
					<li class="notask">No Tasks yet</li>
				 <?php } ?>



                         </ul>
                </div>
               </div>
</div>
<?php } ?>
<div class="row">
<div class="col-xs-12 col-sm-6">
 <div class="table-head"><?=$this->lang->line('application_media');?> <span class=" pull-right"><a href="<?=base_url()?>projects/media/<?=$project->id;?>/add" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_add_media');?></a></span></div>
<div class="table-div min-height-410">
 <table id="media" class="table data-media" rel="<?=base_url()?>projects/media/<?=$project->id;?>" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
                    <th  class="hidden"></th>
					<th><?=$this->lang->line('application_name');?></th>
					<th class="hidden-xs"><?=$this->lang->line('application_filename');?></th>
					<th class="hidden-xs"><?=$this->lang->line('application_phase');?></th>
					<th class="hidden-xs"><i class="fa fa-download"></i></th>
					<th><?=$this->lang->line('application_action');?></th>
          </tr></thead>

        <tbody>
        <?php foreach ($project->project_has_files as $value):?>

				<tr id="<?=$value->id;?>">
					<td class="hidden"><?=human_to_unix($value->date);?></td>
					<td onclick=""><?=$value->name;?></td>
					<td class="hidden-xs truncate" style="max-width: 80px;"><?=$value->filename;?></td>
					<td class="hidden-xs"><?=$value->phase;?></td>
					<td class="hidden-xs"><span class="label label-info tt" title="<?=$this->lang->line('application_download_counter');?>" ><?=$value->download_counter;?></span></td>
					<td class="option " width="10%">
				        <button type="button" class="btn-option btn-xs po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>projects/media/<?=$project->id;?>/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times fa-lg"></i></button>
				        <a href="<?=base_url()?>projects/media/<?=$project->id;?>/update/<?=$value->id;?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a>
			       </td>

				</tr>

				<?php endforeach;?>



        </tbody></table>
        <?php if(!$project->project_has_files) { ?>
				<div class="no-files">
				    <i class="fa fa-cloud-upload"></i><br>
				    No files have been uploaded yet!
				</div>
				 <?php } ?>
        </div>
</div>
<div class="col-xs-12 col-sm-6">
<?php $attributes = array('class' => 'note-form', 'id' => '_notes');
		echo form_open(base_url()."projects/notes/".$project->id, $attributes); ?>
 <div class="table-head"><?=$this->lang->line('application_notes');?> <span class=" pull-right"><a id="send" name="send" class="btn btn-primary button-loader"><?=$this->lang->line('application_save');?></a></span><span id="changed" class="pull-right label label-warning"><?=$this->lang->line('application_unsaved');?></span></div>

  <textarea class="input-block-level summernote-note" name="note" id="textfield" ><?=$project->note;?></textarea>
</form>
</div>

</div>


<!-- <div class="row" style="display: none;">
 <div class="col-xs-12 col-sm-12">
 <div class="table-head"><?=$this->lang->line('application_invoices');?> <span class=" pull-right"></span></div>
<div class="table-div">
 <table class="data table" id="invoices" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
    <thead>
      <th width="70px" class="hidden-xs"><?=$this->lang->line('application_invoice_id');?></th>
      <th><?=$this->lang->line('application_client');?></th>
      <th class="hidden-xs"><?=$this->lang->line('application_issue_date');?></th>
      <th class="hidden-xs"><?=$this->lang->line('application_due_date');?></th>
      <th><?=$this->lang->line('application_status');?></th>
      <th class="hidden-xs"><?=$this->lang->line('application_action');?></th>
    </thead>
    <?php foreach ($project_has_invoices as $value):?>

    <tr id="<?=$value->id;?>" >
      <td class="hidden-xs" onclick=""><?=$value->reference;?></td>
      <td onclick=""><span class="label label-info"><?php if(isset($value->company->name)){echo $value->company->name; }?></span></td>
      <td class="hidden-xs"><span><?php $unix = human_to_unix($value->issue_date.' 00:00'); echo '<span class="hidden">'.$unix.'</span> '; echo date($core_settings->date_format, $unix);?></span></td>
      <td class="hidden-xs"><span class="label <?php if($value->status == "Paid"){echo 'label-success';} if($value->due_date <= date('Y-m-d') && $value->status != "Paid"){ echo 'label-important tt" title="'.$this->lang->line('application_overdue'); } ?>"><?php $unix = human_to_unix($value->due_date.' 00:00'); echo '<span class="hidden">'.$unix.'</span> '; echo date($core_settings->date_format, $unix);?></span> <span class="hidden"><?=$unix;?></span></td>
      <td onclick=""><span class="label <?php $unix = human_to_unix($value->sent_date.' 00:00'); if($value->status == "Paid"){echo 'label-success';}elseif($value->status == "Sent"){ echo 'label-warning tt" title="'.date($core_settings->date_format, $unix);} ?>"><?=$this->lang->line('application_'.$value->status);?></span></td>

      <td class="option hidden-xs" width="8%">
                <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>invoices/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times fa-lg"></i></button>
                <a href="<?=base_url()?>invoices/update/<?=$value->id;?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-pencil fa-lg"></i></a>
      </td>
    </tr>

    <?php endforeach;?>
    </table>
        <?php if(!$project_has_invoices) { ?>
        <div class="no-files">
            <i class="fa fa-file-text"></i><br>

            <?=$this->lang->line('application_no_invoices_yet');?>
        </div>
         <?php } ?>
        </div>
  </div>


</div> -->

<br>


<div class="row">
  <div class="col-sm-12"><h2><?=$this->lang->line('application_activities');?></h2><hr/></div>

</div>
<div class="row">



              <div class="col-xs-12 col-sm-12">
            <div id="timelinediv">
                  <ul class="timeline">
                     <li class="timeline-inverted add-comment">
                        <div class="timeline-badge gray open-comment-box"><i class="fa fa-plus"></i></div>
                        <div id="timeline-comment" class="timeline-panel">
                          <div class="timeline-heading">
                            <h5 class="timeline-title"><?=$this->lang->line('application_new_comment');?></h5>
                          </div>
                          <div class="timeline-body">
                               <?php
                                $attributes = array('class' => 'ajaxform', 'id' => 'replyform', 'data-reload' => 'timelinediv');
                                echo form_open('projects/activity/'.$project->id.'/add', $attributes);
                                ?>
                                  <div class="form-group">
                                    <input type="text" name="subject" class="form-control" id="subject" placeholder="<?=$this->lang->line('application_subject');?>" required/>
                                  </div>
                                    <div class="form-group">
                                        <textarea class="input-block-level summernote" id="reply" name="message" required/></textarea>
                                     </div>
                                <button id="send" name="send" class="btn btn-primary button-loader"><?=$this->lang->line('application_send');?></button>
                                </form>

                          </div>
                        </div>
                      </li>

 <?php foreach ($project->project_has_activities as $value):?>
                      <?php
                      $writer = FALSE;
                      if ($value->user_id != 0) {
                          $writer = $value->user->firstname." ".$value->user->lastname;
                          $image = get_user_pic($value->user->userpic, $value->user->email);
                          }else{
                          $writer = $value->client->firstname." ".$value->client->lastname;
                          $image = get_user_pic($value->client->userpic, $value->client->email);

                      }?>
                      <li class="timeline-inverted">
                        <div class="timeline-badge">
                        <?php if ($writer != FALSE) {  ?>
                        <img class="img-circle timeline-profile-img tt" title="<?=$writer?>"  src="<?=$image?>">
                        <?php }else{ } ?></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h5 class="timeline-title"><?=$value->subject;?></h5>
                            <p><small class="text-muted"><span class="writer"><?=$writer?></span> <span class="datetime"><?php  echo date($core_settings->date_format.' '.$core_settings->date_time_format, $value->datetime); ?></span></small></p>
                          </div>
                          <div class="timeline-body">
                            <p><?=$value->message;?></p>
                          </div>
                        </div>
                      </li>
	<?php endforeach;?>
                      <!-- <li class="timeline-inverted timeline-firstentry">
                        <div class="timeline-badge gray"><i class="fa fa-bolt"></i></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h5 class="timeline-title"><?=$this->lang->line('application_project_created');?></h5>
                            <p><small class="text-muted"><?php  echo date($core_settings->date_format.' '.$core_settings->date_time_format, $project->datetime); ?></small></p>
                          </div>
                          <div class="timeline-body">
                            <p><?=$this->lang->line('application_project_has_been_created');?></p>
                          </div>
                        </div>
                      </li> -->
                  </ul>
                  </div>
              </div>
</div>

<?php } //End of The if/else ?>
