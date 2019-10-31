<?php
if(isset($update)){
if($this->user->admin == "1" && $update){
// $this->session->set_userdata('comingfrom', $uri);
?>
<!--<div class="newsbox"><a href="<?=base_url()?>settings/updates"><?=$this->lang->line('application_update_available');?> <?=$update?> <i class="fa fa-download"></i> </a></div>-->
<?php } }
$yearToDatePercent = $ytdAnnuityGoalColor = $yearToDateDollar = $annualGoal = $probableACAT = $p_probableAnnuityPrcnt = $c_probableAnnuityPrcnt = $probableCase  = '';
//Get Total Annuity Production
    $completedAnnuities =  0;
    foreach ($production_by_paid as &$pr) {
       if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'annuity') {
           $completedAnnuities += ($pr->prem_paid/100);
       }
    }
//Get Total Hot Prospect
foreach ($hot_prospect as &$hp) {$probableACAT += ($hp->p_probable_acat_size/100)*($hp->closing_probability/100);}
//Get Total Probable to Annuity (Prospect)
foreach ($hot_prospect as &$hp) {$p_probableAnnuityPrcnt += (($hp->p_probable_acat_size/100)*($hp->closing_probability/100)*$hp->p_annuity_probability)/100;}
//Get Total Hot Client
foreach ($hot_client as &$hc) {$probableCase += ($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100);}
//Get Total Probable to Annuity (Client)
foreach ($hot_client as &$hc) {$c_probableAnnuityPrcnt += (($hc->c_probable_acat_size/100)*($hc->c_closing_probability/100)*$hc->c_annuity_probability)/100;}
//Variables
$januaryFirst = date('z',strtotime(date('Y-01-01')));
$currentDateNum = date('z');
// $currentAnualGoal = ($this->session->userdata('year_to_view') == '2015' ? true : false);
if     ($this->session->userdata('year_to_view') == '2015') { $annualGoal = ($company->annual_goal)/100;     }
elseif ($this->session->userdata('year_to_view') == '2016') { $annualGoal = ($company->annual_goal2016)/100; }
elseif ($this->session->userdata('year_to_view') == '2017') { $annualGoal = ($company->annual_goal2017)/100; }
elseif ($this->session->userdata('year_to_view') == '2018') { $annualGoal = ($company->annual_goal2018)/100; }
elseif ($this->session->userdata('year_to_view') == '2019') { $annualGoal = ($company->annual_goal2019)/100; }

$yearToDateDollar = ($annualGoal * $currentDateNum) / 365; // Tells us how much production they should be at each day of the year according to their goal
// if(!empty($yearToDateDollar)){ $yearToDatePercent = $completedAnnuities / $yearToDateDollar; }
if(!empty($yearToDateDollar)){
    if ($this->session->userdata('year_to_view') < date('Y')) { 
        $yearToDatePercent = ($completedAnnuities / $annualGoal); 
    } // No longer using year to date dollar. Which is only for current year
      // $annualGoal is determined above started with line 28, so this variable works for both
    elseif ($this->session->userdata('year_to_view') == date('Y')) { $yearToDatePercent = ($completedAnnuities / $yearToDateDollar); }
    $pastPercent       = ($completedAnnuities / $annualGoal);
    if ($yearToDatePercent < 1 )     { $ytdAnnuityGoalColor = 'below-goal'; }
    elseif ($yearToDatePercent > 1 ) { $ytdAnnuityGoalColor = 'above-goal'; }
    elseif ($yearToDatePercent == 1 ){ $ytdAnnuityGoalColor = 'at-goal'; }
}

/*
|| $pastPercent < 1
|| $pastPercent < 1
|| $pastPercent < 1
*/


?>
<?php
    // echo 'First Day of January = '.date("l", strtotime('first day of January '.date('Y') ));
    // echo '<br>Numerical Date (0-365) '.date('z',strtotime(date('Y-01-01'))).' - '.$currentDateNum;
    // echo '<br> Current Date '.date('l');
    // echo '<pre>';
    // print_r($this->user);
    // echo 'This users id = '.$this->user->id."\r\n";
    // print_r($this->user->company_id);
    // echo 'this user company id: '.$this->user->company_id."\r\n";
    // echo $company->primary_contact.' - '.$company->name;
    // var_dump($this->session->userdata("year_to_view"));
    // var_dump($yearToDatePercent);
    // echo '</pre>';
?>
     <?php if($this->user->admin == '1'){ ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6">       
                <div class="form-group">
                    <label for="company_id">Company</label>
                        <!-- Select Company -->
                        <?php
                            $attributes = array('class' => '', 'id' => 'company_select');
                            echo form_open('dashboard/companyswitch/'.$this->user->id , $attributes);
                            // echo form_open('settings/user_update/'.$this->user->id , $attributes);
                            $result = count($companies);
                            $options = array();
                            $options['0'] = '-';
                            for($i = 0; $i < $result; $i++ )
                            {
                                $options[$companies[$i]->id] = $companies[$i]->primary_contact.' - '.$companies[$i]->name;
                            }
                            asort($options);
                            if(isset($this->user->company_id)){$userCompany = $this->user->company_id;}else{$userCompany = "";}
                            echo form_dropdown('company_id', $options, $userCompany, 'id="company-select" style="width:100%" class="chosen-select"');
                            echo '<span class="company_select_set"><input type="submit" name="send2" class="btn btn-primary" value="Set" style="margin-top: 1px;padding: 9px 21px;" /></span>';
                            echo form_close();
                        ?>
                </div>
           
        </div>
        <div class="col-xs-12 col-sm-6">
            
        </div>
    </div>
    <?php } ?>
    <div class="row">
        <?php if ($this->user->ljbutton === '1') { ?>
        <div class="col-xs-12 col-sm-2">
            <label for="updater">&nbsp;</label>
            <form action="<?php //echo site_url() ?>" method="post" id="apiSync">
                <button type="submit" name="updater" id="updateit" class="btn btn-primary leadjig">LeadJig Update</button>
            </form>
        </div>
        <?php } ?>
        <div class="col-xs-12 <?=($this->user->ljbutton==='1')?'col-sm-1':'col-sm-3';?>">
            <a href="<?php echo site_url() ?>dashboard/getCSV" id="excel" class="btn btn-primary" target="_blank">Download Excel <i class="fa fa-download" style="
                padding-left: 5px;
                vertical-align: sub;
            "></i></a>               
        </div>
        <div class="col-xs-12 col-sm-6"></div>
        <div class="col-xs-12 col-sm-3">
            <label for="year">Set Production Year</label>
            <!-- Select Year -->
            <?php
                $attributes = array('class' => 'yearchoice', 'id' => 'production_yearchoice');
                $form_action = '';
                echo form_open($form_action, $attributes);
                    $year_array = array('' => 'Set Year', 2014 => '2014', 2015 => '2015', 2016 => '2016', 2017 => '2017', 2018 => '2018', 2019 => '2019' );
                    $selected_year = $this->session->userdata('year_to_view');
                    if(isset($year_to_view)){ $theyear = $year_to_view; }
                    else{ $theyear = $year_to_view; }
                    echo form_dropdown('year', $year_array, $theyear, 'id="year-select" class="chosen-select" style="width:80%;"');
                    echo '<span class="pull-right"><input type="submit" name="send" class="btn btn-primary" value="Set" style="margin-top: 1px; padding:9px 21px;" /></span>';
                echo form_close();
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="bs-callout bs-callout-primary">
                <span class="pull-right"><a href="<?=base_url()?>clients/company/update/<?=$this->user->company_id;?>/view" class="btn btn-primary" data-toggle="mainmodal"><i class="icon-edit"></i> <?=$this->lang->line('application_edit');?></a></span>
                <h4>Annual Annuity Goal</h4>
                <?php echo money($annualGoal); ?>
            </div>
            <div class="bs-callout bs-callout-default">
                <h4>YTD Annuity Production</h4>
                <?php echo money($completedAnnuities); ?>
            </div>
            <div class="bs-callout bs-callout-primary">
            <?php // echo "Year to Date Percent = {$yearToDatePercent} <br> Past Percent = {$pastPercent} <br> Css Color = {$ytdAnnuityGoalColor} <br> Current Date ";?>
                <?php if ( date("Y") == $this->session->userdata("year_to_view") ) { ?>
                    <h4>YTD Annuity Goal <span class="<?php echo $ytdAnnuityGoalColor; ?>">(<?=round((float)$yearToDatePercent * 100 - 100, 1 );?>&percnt;)</span></h4>
                    <span data-toggle="popover" class="pop" data-placement="top" data-content="Dollar amount needed to be current on your goal.">
                        <?=money($yearToDateDollar);?>
                    </span>
                <?php }
                elseif ( date("Y") > $this->session->userdata("year_to_view") ) { ?>
                    <h4>YTD Annuity Goal <span class="<?php echo $ytdAnnuityGoalColor; ?>">(<?=round((float)$pastPercent * 100 - 100, 1 );?>&percnt;)</span></h4>
                    <span data-toggle="popover" class="pop" data-placement="left" data-content="Percentage above shows production compared to goal.">
                        <?=money($annualGoal);?>
                    </span>
                <?php }
                else { ?>
                    <h4>YTD Annuity Goal</h4>
                    <span class="below-goal">Please set an annuity goal</span>
                <?php } ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="bs-callout bs-callout-default">
                <div class="row">
                    <div class="col-xs-6"><a href="<?=base_url()?>reports/view/<?=$hotclientsheet[0]->id;?>">
                        <h4>Hot Client Probability Total</h4></a>
                        <?=money($probableCase);?>
                    </div>
                    <div class="col-xs-6"><a href="<?=base_url()?>reports/view/<?=$hotclientsheet[0]->id;?>">
                        <h4 data-toggle="popover" class="pop" data-placement="left" data-content="Dollar amount estimated to go towards Annuities.">Hot Client Total to Annuity</h4></a>
                        <?=money($c_probableAnnuityPrcnt);?>
                    </div>
                </div>
            </div>
            <div class="bs-callout bs-callout-primary">
                <div class="row">
                    <div class="col-xs-6"><a href="<?=base_url()?>reports/view/<?=$hotprospectsheet[0]->id;?>">
                        <h4>Hot Prospect Probability Total</h4></a>
                        <?=money($probableACAT);?>
                    </div>
                    <div class="col-xs-6"><a href="<?=base_url()?>reports/view/<?=$hotprospectsheet[0]->id;?>">
                        <h4 data-toggle="popover" class="pop" data-placement="left" data-content="Dollar amount estimated to go towards Annuities.">Hot Prospect Total to Annuity</h4></a>
                        <?=money($p_probableAnnuityPrcnt);?>
                    </div>
                </div>
            </div>
            <div class="bs-callout bs-callout-default">
                <div class="row">
                    <div class="col-xs-6">
                        <h4>Total</h4>
                        <?=money($probableACAT + $probableCase);?>
                    </div>
                    <div class="col-xs-6">
                        <h4 data-toggle="popover" class="pop" data-placement="left" data-content="Dollar amount estimated to go towards Annuities.">Total to Annuity</h4>
                        <?=money($p_probableAnnuityPrcnt + $c_probableAnnuityPrcnt);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row overview">
            <div class="col-xs-12 col-sm-12 col-md-2">
              <div class="stdpad"><h1>ACATs</h1>
                  <a href="<?=base_url()?>reports/view/<?=$acatsheet[0]->id;?>">
                    <ul class="eventlist">
                        <li class="dark"><?php $pendingACATs = 0;
                            foreach ($production as &$pr) {
                                $unix_year = $submitted_year = 0;
                                $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                                $submitted_year = date('Y', $unix_year);
                                if(!empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
                                    $pendingACATs += ($pr->production_amount/100);
                                }
                            }
                            echo '<span>Pending<br>';
                            echo money($pendingACATs);
                            echo '</span>';
                            ?>
                        </li>
                        <li class="dark"><?php $notsubmittedACATs = 0;
                            foreach ($production as &$pr) {
                                $unix_year = $received_year = 0;
                                $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                                $received_year = date('Y', $unix_year);
                                if(empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && $received_year <= $selected_year ) {
                                    $notsubmittedACATs += ($pr->production_amount/100);
                                }
                            }
                            echo '<span>Not Submitted<br>';
                            echo money($notsubmittedACATs);
                            echo '</span>';
                            ?>
                        </li>
                        <li class="greenbg"><?php $completedACATs = 0;
                        //Changed $production_by_paid to $production to include all years for ACAT completed
                        //Changed back to production_by_paid to only include current year, requested by andrew
                            foreach ($production_by_paid as &$pr) {
                                if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'acat') {
                                    $completedACATs += ($pr->prem_paid/100);
                                }
                            }
                            echo '<span data-toggle="popover" class="pop" data-placement="bottom" data-content="Dollar amount includes total from all previous years.">Completed<br>';
                            echo money($completedACATs);
                            echo '</span>';
                            ?>
                        </li>
                    </ul>
                  </a>
             </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-2">
                <div class="stdpad"><h1>AUM</h1><a href="<?=base_url()?>reports/view/<?=$aumsheet[0]->id;?>">
                      <ul class="eventlist">
                          <li class="dark"><?php $pendingAum = 0;
                              foreach ($production as &$pr) {
                                $unix_year = $submitted_year = 0;
                                $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                                $submitted_year = date('Y', $unix_year);
                                  if(!empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
                                      $pendingAum += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Pending<br>';
                              echo money($pendingAum);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="dark"><?php $notsubmittedAum = 0;
                              foreach ($production as &$pr) {
                                $unix_year = $received_year = 0;
                                $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                                $received_year = date('Y', $unix_year);
                                  if(empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid_month) && $received_year <= $selected_year ) {
                                      $notsubmittedAum += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Not Submitted<br>';
                              echo money($notsubmittedAum);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="greenbg"><?php $completedAum = 0;
                              foreach ($production_by_paid as &$pr) {
                                  if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'aum') {
                                      $completedAum += ($pr->prem_paid/100);
                                  }
                              }
                              echo '<span>Completed<br>';
                              echo money($completedAum);
                              echo '</span>';
                              ?>
                          </li>
                      </ul></a>
               </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4">
              <div class="stdpad"><h1>Annuities</h1><a href="<?=base_url()?>reports/view/<?=$annuitysheet[0]->id;?>">
                    <ul class="eventlist">
                        <li class="dark"><?php $annuitiesPending = 0;
                            foreach ($production as &$pr) {
                                $unix_year = $submitted_year = 0;
                                $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                                $submitted_year = date('Y', $unix_year);
                                if(!empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
                                    $annuitiesPending += ($pr->production_amount/100);
                                }
                            }
                            echo '<span>Pending<br>';
                            echo money($annuitiesPending);
                            echo '</span>';
                            ?>
                        </li>
                        <li class="dark"><?php $notsubmittedAnnuities = 0;
                            foreach ($production as &$pr) {
                                $unix_year = $received_year = 0;
                                $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                                $received_year = date('Y', $unix_year);
                                if(empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && $received_year <= $selected_year) {
                                    $notsubmittedAnnuities += ($pr->production_amount/100);
                                }
                            }
                            echo '<span>Not Submitted<br>';
                            echo money($notsubmittedAnnuities);
                            echo '</span>';
                            ?>
                        </li>
                        <li class="greenbg"><?php
                            // foreach ($production_by_paid as &$pr) {
                            //     if(empty($pr->production_submitted) && $pr->production_type == 'annuity') {
                            //         $notsubmittedAnnuities += ($pr->production_amount/100);
                            //     }
                            // }
                            echo '<span>Completed<br>';
                            echo money($completedAnnuities);
                            echo '</span>';
                            ?>
                        </li>
                    </ul></a>
             </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-2">
                <div class="stdpad"><h1>Life Insurance</h1><a href="<?=base_url()?>reports/view/<?=$lifesheet[0]->id;?>">
                      <ul class="eventlist">
                          <li class="dark"><?php $pendingLife = 0;
                              foreach ($production as &$pr) {
                                  $unix_year = $submitted_year = 0;
                                  $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                                  $submitted_year = date('Y', $unix_year);
                                  if(!empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
                                      $pendingLife += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Pending<br>';
                              echo money($pendingLife);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="dark"><?php $notsubmittedLife = 0;
                              foreach ($production as &$pr) {
                                  $unix_year = $received_year = 0;
                                  $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                                  $received_year = date('Y', $unix_year);
                                  if(empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && $received_year <= $selected_year ) {
                                      $notsubmittedLife += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Not Submitted<br>';
                              echo money($notsubmittedLife);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="greenbg"><?php $completedLife = 0;
                              foreach ($production_by_paid as &$pr) {
                                  if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'life') {
                                      $completedLife += ($pr->prem_paid/100);
                                  }
                              }
                              echo '<span>Completed<br>';
                              echo money($completedLife);
                              echo '</span>';
                              ?>
                          </li>
                      </ul></a>
               </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-2">
                <div class="stdpad"><h1>Other Business</h1><a href="<?=base_url()?>reports/view/<?=$othersheet[0]->id;?>">
                      <ul class="eventlist">
                          <li class="dark"><?php $pendingOther = 0;
                              foreach ($production as &$pr) {
                                  $unix_year = $submitted_year = 0;
                                  $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
                                  $submitted_year = date('Y', $unix_year);
                                  if(!empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
                                      $pendingOther += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Pending<br>';
                              echo money($pendingOther);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="dark"><?php $notsubmittedOther = 0;
                              foreach ($production as &$pr) {
                                  $unix_year = $received_year = 0;
                                  $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
                                  $received_year = date('Y', $unix_year);
                                  if(empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && $received_year <= $selected_year ) {
                                      $notsubmittedOther += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Not Submitted<br>';
                              echo money($notsubmittedOther);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="greenbg"><?php $completedOther = 0;
                              foreach ($production_by_paid as &$pr) {
                                  if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'other') {
                                      $completedOther += ($pr->prem_paid/100);
                                  }
                              }
                              echo '<span>Completed<br>';
                              echo money($completedOther);
                              echo '</span>';
                              ?>
                          </li>
                      </ul></a>
               </div>
        </div>


    </div>

    <div class="row overview">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <?php 
                // $asterisk = (stristr($user->email, $obj->users[0]->email) ? 'match' : 'nope');
                // echo $asterisk;
                
                // echo "<pre id='pre'>";
                // var_dump($obj->users[0]->company_name);
                // echo $obj->users[0]->company_name;
                // foreach ($companies as $k => $val) {
                //     ($this->user->company_id === $val->id) ? $companyName =  $val->name : '';
                // }
                // echo "This is current users company: $companyName";
                // echo '</pre>';
                // echo "<div style='display:none;'";
                // phpinfo();
                // echo "</div>";
                $productionCompanies = array('Advisors Academy',
                    'Advisors Academy (Cox)',
                    'Agemy Financial Strategies, Inc',
                    'Arbor Financial',
                    'Archer and Associates',
                    'At World Financial LLC',
                    'Beacon Wealth Advisors',
                    'Brad Williams Financial Services',
                    'Bridge Retirement Planning',
                    'Carter Financial Group',
                    'Cetera Advisors',
                    'Chapel Financial Group',
                    'CKS Summit Group',
                    'Clear Income Strategies',
                    'Crystal Lake Tax & Financial',
                    'Curry Poole Group, LLC',
                    'Curry Poole Group [Curry]',
                    'Eissman Wealth Management, LLC',
                    'Empowerment Resources International',
                    'Empowerment Resources International (Culverson)',
                    'Empowerment Resources International (Evans)',
                    'Empowerment Resources International (Green)',
                    'Empowerment Resources International (Harris)',
                    'Empowerment Resources International (Norfleet)',
                    'Excalibur Life Insurance &amp; Annuity Solutions',
                    'Fellowship Financial Group',
                    'Foothills Financial Group',
                    'Fritz Marcia Financial LLC',
                    'Peason Financial Group, Inc.',
                    'Johnson Wealth and Income Management',
                    'Jonathan Spatz',
                    'Key Advisors Group, LLC',
                    'Key Advisors Group LLC (Doug)',
                    'Koch Financial Advisors &amp; Insurance Brokers LLC',
                    'Lineweaver Financial Group',
                    'LSP Planning',
                    'Main Street Family Advisors Inc',
                    'McAdams Tax Advisory Group',
                    'McAdams Tax Advisory Group ( Burt )',
                    'McAdams Tax Advisory Group ( Osing )',
                    'McCartin Financial',
                    'McLean Tax Advisory Group',
                    'Melia Advisory Group',
                    'Mid-American Tax Advisory Group',
                    'Mosley Financial Services',
                    'North Shore Asset Management & Tax Advisory Inc',
                    'Oracle Financial Group',
                    'Pacific Financial Planners',
                    'Patrick Lynch Financial Services',
                    'Peak Capital Management',
                    'Premier Financial Group',
                    'Providence Financial & Insurance Services',
                    'Prudence Planning',
                    'Scott and Associates of Texas Inc',
                    'Scranton Financial Group',
                    'Scranton Financial Group (Stone)',
                    'Scranton Financial Group CT',
                    'Senior Benefit Group',
                    'SFG',
                    'Stearns Retirement Group',
                    'Steve Cox Financial Services',
                    'Strategic Senior Benefits Group',
                    'Structus',
                    'Three Bridges Financial Group',
                    'Val Trust Financial Group',
                    'Wendel Retirement Planning',
                    'Wendel Retirement Planning',
                    'Wood Financial Group, LLC');
                
                if ($sync === 1) 
                {
                    // echo "<pre>";
                    // // $obj = json_decode(json_encode($prospects));
                    // // print_r($obj);

                    // $cc = 0;
                    // foreach($clients as $aClient) {
                    //     $cc++;
                    //     $fn = trim($aClient->firstname).' '.trim($aClient->lastname)."\r\n";
                    //     print_r($cc.' '.$fn);
                    // }
                    // echo "</pre>";

                    echo "<pre>All Events\r\n";
                    $obj1 = json_decode(json_encode($allLeadJigEvents));
                    print_r($allLeadJigEvents);
                    echo "</pre>";

                    echo "<pre>Matched Events\r\n";
                    $obj2 = json_decode(json_encode($matchedEvents));
                    print_r($obj2);
                    echo "</pre>";

                    
                    // echo "<pre>Unmatched Events - Ones that will be added\r\n";
                    // $obj3 = json_decode(json_encode($unmatchedEvents));
                    // print_r($obj3);
                    // echo "</pre>";

                    // echo "<pre>Prospects - Only ones with created date after 2/01/2017 will be added\n";
                    // $prospectsMatched = array();
                    // foreach($prospects as $pKey => $pVal) {
                    //     $prospectsMatched[$pKey] = $pVal;
                    //     foreach($clients as $aClient) {
                    //         $fn = trim($aClient->firstname).' '.trim($aClient->lastname);
                    //         if( $fn == $pVal['fullname'])
                    //         {
                    //             // $prospectsMatched[$pKey]['matched'] = 1;
                    //             // $prospectsMatched[$pKey]['address'] = $aClient->address;
                    //             // $prospectsMatched[$pKey]['city'] = $aClient->city;
                    //             // $prospectsMatched[$pKey]['state'] = $aClient->state;
                    //             // $prospectsMatched[$pKey]['zipcode'] = $aClient->zipcode;
                    //             // $prospectsMatched[$pKey]['phone'] = $aClient->phone;
                    //             // $prospectsMatched[$pKey]['email'] = $aClient->email;
                    //             // $prospectsMatched[$pKey]['event'] = $aClient->event_id;
                    //             // $prospectsMatched[$pKey]['client_id'] = $aClient->id;
                    //             unset( $prospectsMatched[ $pKey ] );
                    //         }
                    //     }
                    // }
                    // foreach ($prospectsMatched as $pM){
                    // print_r($pM['fullname']);
                    // }
                    
                    // print_r($prospectsMatched);
                    // print_r($prospects);
                    // foreach($prospects as $p) {
                    //     print_r($p['fullname']);
                    // }
                    // echo "</pre>";
                }
                
            ?>
            
            <?php // Update last_synced in 'users' table with NOW()
                // echo ($sync === 1 ? 'Synced' : 'Not Synced'); 
                // $nextWeek = time() + (7 * 24 * 60 * 60); // 7 days; 24 hours; 60 mins; 60 secs
                // date_default_timezone_set('America/New_York');
                // echo '<pre>Timezone:       '. $timezone ."\n";
                // echo 'Next Week: '. date('Y-m-d', $nextWeek) ."\n"; // or using strtotime():
                // echo 'Time: '. date('H:i:s', time()) ."\n";
                // echo "Last Sync = ".date('m/d/Y h:i:s', $this->user->last_sync)." ".$this->user->last_sync;
            ?>
        </div>
    </div>
    
    <style>
    pre {
        background: #443e3e;
        color: #cbe1ff;
        width: 29%;
        float: left;
    }
    .modalJig {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .92 ) 
                    url('https://advisorsacademy.com/wp-content/uploads/2017/07/loader.gif') 
                    50% 50% 
                    no-repeat;
    }
    body.loading .modalJig {
        display: block;
    }
    body.loading .modalJig:after {
        content: "Loading LeadJig Data. Please wait for it to complete. You will be returned to the dashboard when it is complete.";
        position: relative;
        margin: 0 auto;
        padding-bottom: 20%;
        top: 20%;
        text-align: center;
        font-size: 2em;
        display: block;
        max-width: 900px;
    }
    </style>
    <div class="modalJig"><!-- Place at bottom of page --></div>

<?php if($this->user->admin == "2"){ //Changed to two for now to hide from all ?>
    <div class="row statstic-chart">
          <div class="col-xs-12 col-sm-12 dashboard-chart">
            <h1><?=$this->lang->line('application_statistics');?>
            <div class="btn-group pull-right">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <?=$year;?> <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y");?>"><?=date("Y");?></a></li>

                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-1;?>"><?=date("Y")-1;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-2;?>"><?=date("Y")-2;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-3;?>"><?=date("Y")-3;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-4;?>"><?=date("Y")-4;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-5;?>"><?=date("Y")-5;?></a></li>
                  </ul>
            </div></h1>

            <figure style="width: auto; height: 250px;" id="dashboard_line_chart"></figure>
          </div>
    </div>

    <div id="stat-numbers" class="row">
        <div class="col-xs-6 col-sm-3"><h2><?=$invoices_open;?><small> / <?=$invoices_all;?></small></h2> <h5><?=$this->lang->line('application_open_invoices');?></h5></div>
        <div class="col-xs-6 col-sm-3"><h2><?=$projects_open;?><small> / <?=$projects_all;?></small></h2> <h5><?=$this->lang->line('application_open_projects');?></h5></div>
        <div class="col-xs-6 col-sm-3"><h2><?=$core_settings->currency;?> <?php if(empty($payments[0]->summary)){echo "0";}else{echo number_format($payments[0]->summary); }?></h2> <h5><?=$this->lang->line('application_'.$month);?> <?=$this->lang->line('application_payments');?></h5></div>
        <div class="col-xs-6 col-sm-3"><h2><?=$core_settings->currency;?> <?php if(empty($paymentsoutstanding[0]->summary)){echo "0";}else{echo number_format($paymentsoutstanding[0]->summary); } ?></h2> <h5><?=$this->lang->line('application_outstanding_payments');?></h5></div>
      </div>
<?php } ?>



    <?php
      $line1 = '{ "xScale": "time",
                  "yScale": "linear",
                  "yMin": 0,
                   "main": [
                    {
                    "className": ".dashboard_chart",
                    "data": [';
      for ($i = 01; $i <= 12; $i++) {

        $num = "0";
        foreach ($stats as $value):
        $act_month = explode("-", $value->paid_date);
        if($act_month[1] == $i){
          $num = $value->summary;
        }
        endforeach;
          $i = sprintf("%02.2d", $i);
          $line1 .= "{x: '".$year."-".$i."', y: ".$num."}";
          if($i != "12"){ $line1 .= ",";}
        }

        $line1 .= ']},
                            ]
                          }';
        ?>



  <script type="text/javascript">
    //modal
    $(document).ready(function(){
        var frm = $('#apiSync');
        $body = $("body");

        frm.submit(function (e) {

            e.preventDefault();
            $body.addClass("loading");

            $.ajax({
                type: frm.attr('method'),
                url: "#",
                traditional: true,
                // data: frm.serialize(),
                data: { 'updater' : 'updater' },
                success: function (data) {
                    $body.removeClass("loading");
                    console.log('Submission was successful.');
                    // console.log(data);
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        });  
        
        
        

    //xChart Dashboard
        var tt = document.createElement('div'),
        leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
        topOffset = -32;
        tt.className = 'ex-tooltip';
        document.body.appendChild(tt);


        var data = <?=$line1?>;
        var opts = {
        "dataFormatX": function (x) { return d3.time.format('%Y-%m').parse(x); },
        "tickFormatX": function (x) { return d3.time.format('%Y-%m')(x); },
        "mouseover": function (d, i) {
            var pos = $(this).offset();
            var lineclass = $(this).parent().attr("class");
            lineclass = lineclass.split(" ");
            console.log(lineclass[2]);
            if( lineclass[2] == "dashboard_chart"){
            var linename = "<?=$this->lang->line('application_received');?>: ";
            }else{
            var linename = "Opened";
            }
            $(tt).text(linename + d.y)
            .css({top: topOffset + pos.top, left: pos.left + leftOffset})
            .show();
        },
        "mouseout": function (x) {
            $(tt).hide();
        },
        "tickHintY": 4,
        "paddingLeft":40,

        };
        if($("#dashboard_line_chart").length != 0) {
        var myChart = new xChart('line-dotted', data, '#dashboard_line_chart', opts);
        }
        //xChart DAshboard End

        function tick(){
        $('ul.dash-messages li:first').slideUp('slow', function () { $(this).appendTo($('ul.dash-messages')).fadeIn('slow'); });
        }
        <?php if(count($message) > 2){ ?>
        setInterval(function(){ tick() }, 5000);
        <?php } ?>

    });
</script>
