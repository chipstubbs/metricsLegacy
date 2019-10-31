
	<?php
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
			'pcpartnership' => 'P&amp;C Partnership',
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
	?>
	<div class="col-sm-13  col-md-12 main">
    <div class="row tile-row hidden">
		<div class="col-md-3 col-xs-6 tile"><h1><i class="fa fa-lightbulb-o"></i> <?php if(isset($projects_assigned_to_me[0])){echo $projects_assigned_to_me[0]->amount;} ?> <span class="hidden-xs"><?=$this->lang->line('application_projects');?></span></h1><h2 class="hidden-xs"><?=$this->lang->line('application_assigned_to_me');?></h2></div>
		<div class="col-md-3 col-xs-6 tile"> <h1><i class="fa fa-tasks"></i> <?php if(isset($tasks_assigned_to_me)){echo $tasks_assigned_to_me;} ?> <span class="hidden-xs"><?=$this->lang->line('application_tasks');?></span></h1><h2 class="hidden-xs"><?=$this->lang->line('application_assigned_to_me');?></h2></div>
		<div class="col-md-6 col-xs-12 tile">
		<figure style="width: auto; height: 100px;" id="project_line_chart"></figure>
		</div>
    </div>
     <div class="row">

	  	<?php
			if ($this->user->admin == 1){
				echo '<a href="'.base_url().'projects/create" class="btn btn-primary" data-toggle="mainmodal">';
				echo $this->lang->line('application_create_new_project');
				echo '</a>';
			}else{}
		?>

      <div class="btn-group pull-right margin-right-3 hidden">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <?php $last_uri = $this->uri->segment($this->uri->total_segments()); if($last_uri != "projects"){echo $this->lang->line('application_'.$last_uri);}else{echo $this->lang->line('application_all');} ?> <span class="caret"></span>
          </button>
          <ul class="dropdown-menu pull-right" role="menu">
            <?php foreach ($submenu as $name=>$value):?>
	                <li><a id="<?php $val_id = explode("/", $value); if(!is_numeric(end($val_id))){echo end($val_id);}else{$num = count($val_id)-2; echo $val_id[$num];} ?>" href="<?=site_url($value);?>"><?=$name?></a></li>
	            <?php endforeach;?>
          </ul>
      </div>
    </div>

	<div class="row">
	<div class="col-md-2 col-xs-12">
		<div class="table-head">Metric Reports</div>
		<div class="list-group">
			<?php foreach ($project as $value):
				if ($value->name != '6') {
					if($this->user->admin == "1" || $this->user->company_id == $value->company_id){ ?>
	               		<a class="list-group-item" id="<?=$value->id;?>" href="<?=base_url();?>reports/view/<?=$value->id;?>">
							<?php
							if ($value->name === '6') {
								    echo $eventoptions[$value->event].
								    ' ';
								}
								switch ($value->name) {
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
								        echo "AUM";
								        break;
								    default:
								        echo "Metric Name Not Chosen";
								}
							?>
						</a>
            <?php } }
			endforeach;?>
		</div>
	</div>

	<div class="col-md-10 col-xs-12">
		<div class="table-head">Event Averages</div>
		<table class="table" id="EventAverages" width="100%">
			<thead>
				<th> Event Type </th>
				<th> Responses </th>
				<th> B.U.s </th>
				<th> Scheduled Appts </th>
				<th> Appts Kept </th>
				<th> Closing </th>
				<th> Cost </th>
				<th> Profit </th>
				<th> To Annuity </th>
				<th> Total Annuity </th>
			</thead>
			<!-- Client averages -->
			<tr>
				<td>Client</td>
				<td><?php echo $num_clients; ?></td>
				<td></td>
				<td><?php echo $client_appts.' <span class="avg-percent">(&nbsp;'.$client_appts_ratio.'&nbsp;)</span>'; ?></td>
				<td><?php echo $client_kept_appts.' <span class="avg-percent">(&nbsp;'.$client_kept_ratio.'&nbsp;)</span>'; ?></td>
				<td><?php echo $client_closed.' <span class="avg-percent">(&nbsp;'.$client_close_ratio.'&nbsp;)</span>'; ?></td>
				<td></td>
				<td><?php echo '&dollar;'.number_format($client_gross, 2); ?></td>
				<td><?php echo '<span class="avg-percent">(&nbsp;'.$client_annuity_avg.'&nbsp;)</span>'; ?></td>
				<td><?php echo '&dollar;'.number_format($client_annuity, 2); ?></td>
			</tr>
			<!-- Referral averages -->
			<tr>
				<td>Referrals</td>
				<td><?php echo $num_referrals; ?></td>
				<td></td>
				<td><?php echo $referral_appts.' <span class="avg-percent">(&nbsp;'.$referral_appts_ratio.'&nbsp;)</span>'; ?></td>
				<td><?php echo $referral_kept_appts.' <span class="avg-percent">(&nbsp;'.$referral_kept_ratio.'&nbsp;)</span>'; ?></td>
				<td><?php echo $referral_closed.' <span class="avg-percent">(&nbsp;'.$referral_close_ratio.'&nbsp;)</span>'; ?></td>
				<td></td>
				<td><?php echo '&dollar;'.number_format($referral_gross, 2); ?></td>
				<td><?php echo '<span class="avg-percent">(&nbsp;'.$referral_annuity_avg.'&nbsp;)</span>'; ?></td>
				<td><?php echo '&dollar;'.number_format($referral_annuity, 2); ?></td>
			</tr>
			<?php if(is_object($avgs)) {
				foreach ($avgs as $e => $avg) { ?>
					<tr>
						<td> <?php echo $eventoptions[$e].' <span style="color:khaki;">( '.$avg->counter.' )</span>'; ?> </td>
						<td> <?php echo $avg->avg_response.'&nbsp; &nbsp; &nbsp;<span class="avg-percent">(&nbsp;'.$avg->response_ratio.'&nbsp;)</span>'; ?> </td>
						<td> <?php echo $avg->avg_buying_units; ?> </td>
						<td> <?php echo $avg->avg_appointments.'&nbsp; &nbsp; &nbsp;<span class="avg-percent">(&nbsp;'.$avg->appointment_ratio.'&nbsp;)</span>'; ?> </td>
						<td> <?php echo $avg->avg_appointment_kept.'&nbsp; &nbsp; &nbsp;<span class="avg-percent">(&nbsp;'.$avg->appointment_kept_ratio.'&nbsp;)</span>'; ?> </td>
						<td> <?php echo $avg->prospectsclosed.'&nbsp; &nbsp; &nbsp;<span class="avg-percent">(&nbsp;'.$avg->closingratio.'&nbsp;)</span>'; ?> </td>
						<td> <?php echo '&dollar;'.number_format($avg->totaleventcost, 2); ?> </td>
						<td> <?php echo '&dollar;'.number_format($avg->grossprofit, 2); ?> </td>
						<td> <?php echo '&dollar;'.number_format($avg->annuityavg, 2).'&nbsp; &nbsp; &nbsp;<span class="avg-percent">(&nbsp;'.$avg->avgtoannuity.'&nbsp;)</span>'; ?> </td>
						<td> <?php echo '&dollar;'.number_format($avg->totalannuity, 2); ?> </td>
					</tr>
				<?php }
			} else {

			}?>
		</table>
	</div>
	</div>


	<!-- Event Overview -->
	<div class="row">
	<div class="col-xs-12 col-sm-12">


		<?php
			if ($this->user->admin != 1){
				echo '<a href="'.base_url().'projects/create/?useradd=yes" class="btn btn-primary" data-toggle="mainmodal">';
				echo 'Create Event';
				echo '</a>';
			}else{}
		?>
	<div class="table-head">Events</div>
	<div class="table-div">
	<table class="table nowrap" id="eventOverview" rel="<?=base_url()?>" cellspacing="0" cellpadding="0" >
			<thead>
				<tr data-toggle="modal">
					<th><?=$this->lang->line('application_metric_name');?></th>
					<th>Event Date</th>
					<th>Location</th>
					<th>Total Responses</th>
					<th>Response Ratio</th>
					<th>&#35; Attended</th>
					<th>Attendance Ratio</th>
					<th>&#35; Buying Units Attended</th>
					<th>&#35; Appt Scheduled</th>
					<th>Appt Sched Ratio</th> <!-- 10th column -->
					<th>Appt Kept</th>
					<th>Prospects Closed</th>
					<th>&#35; Mailers</th>
					<th>Mailer Cost</th>
					<th>&#35; Ads</th>
					<th>Ads Cost</th>
					<th>&#35; Other Invites</th>
					<th>Other Invite Cost</th>
					<th>People w. Assets</th>
					<th>&#37; w. Assets</th> <!-- 20th column -->
					<th>1<sup>st</sup> Appt Pending</th>
					<th>Kept Appt Ratio</th>
					<th>Closing Ratio</th>
					<th>ACAT</th>
					<th>Annuity Premium</th>
					<th>Annuity Commission</th>
					<th>Other Premium</th>
					<th>Other Commission</th>
					<th>&#37; to Annuity</th>
					<th>All Commissions</th> <!-- 30th column -->
					<th>Event Cost</th>
					<th>Gross Profit</th>
					<th>View / Delete</th> <!-- View Event -->
				</tr>
			</thead>

			<tbody>
			<?php
				foreach ($project as $value):
					if ($value->name == '6') {
				$workers = array();
				//foreach($value->project_has_workers as $worker){ array_push($workers, $worker->user_id);}
				/*if($this->user->admin == "1" || in_array($this->user->id, $workers)){ */
				if($this->user->admin == "1" || $this->user->company_id == $value->company_id) {

				$scheduled_appointments = $kept_appointments = $prospects_closed = $has_assets = $kept_appt_ratio = $assets_ratio = $closing_ratio = $acatproduction = $annuityproduction = $annuitycom = $otherproduction = $othercom = $annuitypercent = 0;
				foreach ($clients as $c) {
					if ($c->event_id == $value->id && $c->inactive == 0 && $c->sched_appt_check > 0) {
						$scheduled_appointments++;
					}
					if ($c->event_id == $value->id && $c->inactive == 0 && $c->kept_appt > 0) {
						$kept_appointments++;
					}
					if ( ($c->event_id == $value->id && $c->inactive == 0) && ($c->acat > 0 || $c->aum > 0 || $c->annuity_app > 0 || $c->life_submitted > 0 || $c->other > 0) ) {
						$prospects_closed++;
					}
					if ($c->event_id == $value->id && $c->inactive == 0 && $c->has_assets > 0) {
						$has_assets++;
					}
				}

				if (!empty($value->bu_attended)) { $assets_ratio = $has_assets/$value->bu_attended; }
				if (!empty($scheduled_appointments) && !empty($kept_appointments)) { $kept_appt_ratio = $kept_appointments/$scheduled_appointments; }
				if (!empty($prospects_closed) && !empty($kept_appointments)) { $closing_ratio = $prospects_closed/$kept_appointments; }

				foreach($production as  &$entry){
					if($entry->event_id == $value->id) {
						if ($entry->production_type === 'acat') { $acatproduction += ($entry->prem_paid/100); }
						else if ($entry->production_type === 'annuity') { $annuityproduction += $entry->prem_paid/100; $annuitycom += ($entry->comp_agent_percent/100) * ($entry->prem_paid/100); }
						else if ($entry->production_type === 'other' || $entry->production_type === 'life') { $otherproduction += $entry->prem_paid/100; $othercom += ($entry->comp_agent_percent/100) * ($entry->prem_paid/100); }
						else if ($entry->production_type === 'aum') {  }
						//echo '['.$entry->id.']'.$entry->firstname.' '.$entry->lastname.' Acat = '.number_format($annuityproduction).'<br>';
						//echo $entry->event_id.' = Event ID and '.$value->id.' = Project ID<br>';
					}
				}

				if (!empty($acatproduction) && !empty($annuityproduction)) { $annuitypercent = ($annuityproduction/$acatproduction); }

			?>
			<tr id="<?=$value->id;?>" href="<?=base_url()?>projects/view/<?=$value->id;?>">
				<td onclick=""><?php
				if ($value->name === '6') {
					$eventoptions = array(
		                        '' => '-',
								1 => 'Unsolicited Referral',
								2 => 'Solicited Referral',
								'client' => 'Client',
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
					echo $eventoptions[$value->event].' ';
				}
				switch ($value->name) {
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
						echo "AUM";
						break;
				default:
						echo "Metric Name Not Chosen";
				} ?></td>
				<td><?php echo $value->event_date; ?></td>
				<td><?php echo $value->location; ?></td>
				<td><?=$value->total_responses;?></td>
					<?php if (!empty($value->total_responses) && !empty($value->number_mailers)) { $mailer_response = $value->total_responses/$value->number_mailers; } else {$mailer_response = 0;}?>
				<td><?=round((float)$mailer_response * 100, 1 ). '%';?></td>
				<td><?=$value->attended;?></td>
					<?php if (!empty($value->bu_attended) && !empty($value->total_responses)) { $att_ratio = $value->	attended/$value->total_responses; } else { $att_ratio = 0; } ?>
				<td><?=round((float)$att_ratio * 100, 1 ) . '%';?></td>
				<td><?=$value->bu_attended;?></td>
				<td><?php echo $scheduled_appointments; ?></td>
					<?php if (!empty($value->bu_attended) && !empty($scheduled_appointments)) { $appt_sched_ratio = $scheduled_appointments/$value->bu_attended; } else { $appt_sched_ratio = 0; }?>
				<td><?=round((float)$appt_sched_ratio * 100, 1 ) . '%';?></td> <!-- 10th column -->
				<td><?=$kept_appointments;?></td>
				<td><?=$prospects_closed;?></td>
				<td><?=$value->number_mailers;?></td>
				<td><?=money($value->mailers_cost/100);?></td>
				<td><?=$value->ad;?></td>
				<td><?=money($value->ad_cost/100);?></td>
				<td><?=$value->other_invite;?></td>
				<td><?=money($value->other_invite_cost/100);?></td>
				<td><?=$has_assets;?></td>
				<td><?=round((float)$assets_ratio * 100, 1 ) . '%';?></td> <!-- 20th column -->
				<td><?=$scheduled_appointments - $kept_appointments;?></td>
				<td><?=round((float)$kept_appt_ratio * 100, 1 ) . '%';?></td>
				<td><?=round((float)$closing_ratio * 100, 1 ) . '%';?></td>
				<td><?=money($acatproduction);?></td>
				<td><?=money($annuityproduction);?></td>
				<td><?=money($annuitycom);?></td>
				<td><?=money($otherproduction);?></td>
				<td><?=money($othercom);?></td>
				<td><?=round((float)$annuitypercent * 100, 1 ) . '%';?></td>
				<td><?=money($othercom + $annuitycom);?></td> <!-- 30th column -->
				<td><?=money(($value->total_event_cost/100) + ($value->mailers_cost/100) + ($value->ad_cost/100) + ($value->other_invite_cost/100)); ?></td>
				<td><?=money(($othercom + $annuitycom) - (($value->total_event_cost/100) + ($value->mailers_cost/100) + ($value->ad_cost/100) + ($value->other_invite_cost/100)) );?></td>
				<td class="option" width="8%">
					<a href="<?=base_url()?>projects/view/<?=$value->id;?>" class=""><i class="fa fa-lightbulb-o" > </i></a>
					&nbsp;
					<button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>projects/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i>
                    </button>
				</td> <!-- 33rd column -->
			</tr>
			<?php } } ?>
			<?php endforeach;?>



			</tbody>
		</table>
		</div>

	</div>
	</div>
	<!-- End Event Overview -->

<script>
$(document).ready(function(){

                        //xChart
                          var tt = document.createElement('div'),
                            leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
                            topOffset = -32;
                          tt.className = 'ex-tooltip';
                          document.body.appendChild(tt);

                          var data = {
                            "xScale": "time",
                            "yScale": "linear",
                            "yMin": 0,
                            "main": [
                              {
                                "className": ".project_chart_opened",
                                "data": [
                                <?php
                                $days = array();
                                $this_week_days = array(
                                  date("Y-m-d",strtotime('monday this week')),
                                  date("Y-m-d",strtotime('tuesday this week')),
                                    date("Y-m-d",strtotime('wednesday this week')),
                                      date("Y-m-d",strtotime('thursday this week')),
                                        date("Y-m-d",strtotime('friday this week')),
                                          date("Y-m-d",strtotime('saturday this week')),
                                            date("Y-m-d",strtotime('sunday this week')));
                                foreach ($projects_opened_this_week as $value) {
                                  $days[$value->date_formatted] = $value->amount;

                                  //$days[$value->date_day."_date"] = $value->date_formatted;
                                }
                                foreach ($this_week_days as $selected_day) {
                                  $y = 0;
                                    if(isset($days[$selected_day])){ $y = $days[$selected_day];}
                                      ?>{

                                    "x": "<?php echo $selected_day; ?>",
                                    "y": <?php echo $y; ?>
                                  },
                                  <?php } ?>


                                ]},
                              {
                                "className": ".project_chart_closed",
                                "data": [

                                ]



                              }
                            ]
                          };
                          var opts = {
                            "dataFormatX": function (x) { return d3.time.format('%Y-%m-%d').parse(x); },
                            "tickFormatX": function (x) { return d3.time.format('%a')(x); },
                            "mouseover": function (d, i) {
                              var pos = $(this).offset();
                              var lineclass = $(this).parent().attr("class");
                              lineclass = lineclass.split(" ");
                              console.log(lineclass[2]);
                              if( lineclass[2] == "project_chart_closed"){
                                var linename = "Closed";
                              }else{
                                var linename = "Opened";
                              }
                              $(tt).text(d.y + ' Projects ' +linename)
                                .css({top: topOffset + pos.top, left: pos.left + leftOffset})
                                .show();
                            },
                            "mouseout": function (x) {
                              $(tt).hide();
                            },
                            "tickHintY": 4,
                            "paddingLeft":20,

                          };
                          if($("#project_line_chart").length != 0) {
                          var myChart = new xChart('line-dotted', data, '#project_line_chart', opts);
                          }
                          //xChart End
						  new $.fn.dataTable.FixedColumns( eventtTable, {
						      left:   true
						  } );

});
</script>
