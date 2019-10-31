<?php
if(isset($update)){
if($this->user->admin == "1" && $update){
// $this->session->set_userdata('comingfrom', $uri);
$yearToDatePercent = $ytdAnnuityGoalColor = $yearToDateDollar = $annualGoal = $probableACAT = $p_probableAnnuityPrcnt = $c_probableAnnuityPrcnt = $probableCase  = '';
?>
<div class="newsbox"><a href="<?=base_url()?>settings/updates"><?=$this->lang->line('application_update_available');?> <?=$update?> <i class="fa fa-download"></i> </a></div>
<?php } }
//Get Total Annuity Production
    $completedAnnuities =  0;
    foreach ($production as &$pr) {
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
$currentAnualGoal = ($this->session->userdata('year_to_view') == '2015' ? true : false);
if ($this->session->userdata('year_to_view') == '2015') { $annualGoal = ($company[0]->annual_goal)/100; }
elseif ($this->session->userdata('year_to_view') == '2016') { $annualGoal = ($company[0]->annual_goal2016)/100; }
$yearToDateDollar = ($annualGoal * $currentDateNum) / 365;
// if(!empty($yearToDateDollar)){ $yearToDatePercent = $completedAnnuities / $yearToDateDollar; }
if(!empty($yearToDateDollar)){
    $yearToDatePercent = ($completedAnnuities / $yearToDateDollar);
    $pastPercent       = ($completedAnnuities / $annualGoal);
    if ($yearToDatePercent < 1 || $pastPercent < 1 )     { $ytdAnnuityGoalColor = 'below-goal'; }
    elseif ($yearToDatePercent > 1 || $pastPercent < 1 ) { $ytdAnnuityGoalColor = 'above-goal'; }
    elseif ($yearToDatePercent == 1 || $pastPercent < 1 ){ $ytdAnnuityGoalColor = 'at-goal'; }
}
?>
<?php
    // echo 'First Day of January = '.date("l", strtotime('first day of January '.date('Y') ));
    // echo '<br>Numerical Date (0-365) '.date('z',strtotime(date('Y-01-01'))).' - '.$currentDateNum;
    // echo '<br> Current Date '.date('l');
    // echo '<pre>';
    // var_dump($this->session->userdata("year_to_view"));
    // var_dump($hot_prospect);
    // echo '</pre>';
?>
    <div class="row">
        <div class="col-xs-12 col-sm-9">

        </div>
        <div class="col-xs-12 col-sm-3">
            <label for="year">Set Production Year</label>
            <?php
                $attributes = array('class' => 'yearchoice', 'id' => 'production_yearchoice');
                echo form_open($form_action, $attributes);
                    $year_array = array('' => 'Set Year', 2014 => '2014', 2015 => '2015', 2016 => '2016' );
                    $selected_year = $this->session->userdata('year_to_view');
                    if(isset($year_to_view)){ $theyear = $year_to_view; }
                    else{ $theyear = $year_to_view; }
                    echo form_dropdown('year', $year_array, $theyear, 'id="year-select" class="chosen-select" style="width:80%;"');
                    echo '<span class="pull-right"><input type="submit" name="send" class="btn btn-primary" value="Set" style="margin-top: 3px;" /></span>';
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
                <?php if ( date("Y") == $this->session->userdata("year_to_view") ) { ?>
                    <h4>YTD Annuity Goal <span class="<?php echo $ytdAnnuityGoalColor; ?>">(<?=round((float)$yearToDatePercent * 100 - 100, 1 );?>&percnt;)</span></h4>
                    <?=money($yearToDateDollar);?>
                <?php }
                elseif ( date("Y") > $this->session->userdata("year_to_view") ) { ?>
                    <h4>YTD Annuity Goal <span class="<?php echo $ytdAnnuityGoalColor; ?>">(<?=round((float)$pastPercent * 100 - 100, 1 );?>&percnt;)</span></h4>
                    <?=money($annualGoal);?>
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
                        <h4 data-toggle="popover" class="pop" data-placement="top" data-content="Dollar amount estimated to go towards Annuities.">Hot Client Total to Annuity</h4></a>
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
                        <h4 data-toggle="popover" class="pop" data-placement="top" data-content="Dollar amount estimated to go towards Annuities.">Hot Prospect Total to Annuity</h4></a>
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
              <div class="stdpad"><h2>ACATs</h2>
                  <a href="<?=base_url()?>reports/view/<?=$acatsheet[0]->id;?>">
                    <ul class="eventlist">
                        <li class="dark"><?php $pendingACATs = 0;
                            foreach ($production as &$pr) {
                                if(!empty($pr->production_submitted) && $pr->production_type == 'acat' && (empty($pr->prem_paid))) {
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
                                if(empty($pr->production_submitted) && $pr->production_type == 'acat') {
                                    $notsubmittedACATs += ($pr->production_amount/100);
                                }
                            }
                            echo '<span>Not Submitted<br>';
                            echo money($notsubmittedACATs);
                            echo '</span>';
                            ?>
                        </li>
                        <li class="greenbg"><?php $completedACATs = 0;
                            foreach ($production as &$pr) {
                                if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'acat') {
                                    $completedACATs += ($pr->prem_paid/100);
                                }
                            }
                            echo '<span>Completed<br>';
                            echo money($completedACATs);
                            echo '</span>';
                            ?>
                        </li>
                    </ul>
                  </a>
             </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-2">
                <div class="stdpad"><h2>AUM</h2><a href="<?=base_url()?>reports/view/<?=$aumsheet[0]->id;?>">
                      <ul class="eventlist">
                          <li class="dark"><?php $pendingAum = 0;
                              foreach ($production as &$pr) {
                                  if(!empty($pr->production_submitted) && $pr->production_type == 'aum' && (empty($pr->prem_paid))) {
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
                                  if(empty($pr->production_submitted) && $pr->production_type == 'aum') {
                                      $notsubmittedAum += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Not Submitted<br>';
                              echo money($notsubmittedAum);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="greenbg"><?php $completedAum = 0;
                              foreach ($production as &$pr) {
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
              <div class="stdpad"><h2>Annuities</h2><a href="<?=base_url()?>reports/view/<?=$annuitysheet[0]->id;?>">
                    <ul class="eventlist">
                        <li class="dark"><?php $annuitiesPending = 0;
                            foreach ($production as &$pr) {
                                if(!empty($pr->production_submitted) && $pr->production_type == 'annuity' && (empty($pr->prem_paid))) {
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
                                if(empty($pr->production_submitted) && $pr->production_type == 'annuity') {
                                    $notsubmittedAnnuities += ($pr->production_amount/100);
                                }
                            }
                            echo '<span>Not Submitted<br>';
                            echo money($notsubmittedAnnuities);
                            echo '</span>';
                            ?>
                        </li>
                        <li class="greenbg"><?php
                            echo '<span>Completed<br>';
                            echo money($completedAnnuities);
                            echo '</span>';
                            ?>
                        </li>
                    </ul></a>
             </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-2">
                <div class="stdpad"><h2>Life Insurance</h2><a href="<?=base_url()?>reports/view/<?=$lifesheet[0]->id;?>">
                      <ul class="eventlist">
                          <li class="dark"><?php $pendingLife = 0;
                              foreach ($production as &$pr) {
                                  if(!empty($pr->production_submitted) && $pr->production_type == 'life' && (empty($pr->prem_paid))) {
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
                                  if(empty($pr->production_submitted) && $pr->production_type == 'life') {
                                      $notsubmittedLife += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Not Submitted<br>';
                              echo money($notsubmittedLife);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="greenbg"><?php $completedLife = 0;
                              foreach ($production as &$pr) {
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
                <div class="stdpad"><h2>Other Business</h2><a href="<?=base_url()?>reports/view/<?=$othersheet[0]->id;?>">
                      <ul class="eventlist">
                          <li class="dark"><?php $pendingOther = 0;
                              foreach ($production as &$pr) {
                                  if(!empty($pr->production_submitted) && $pr->production_type == 'other' && (empty($pr->prem_paid))) {
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
                                  if(empty($pr->production_submitted) && $pr->production_type == 'other') {
                                      $notsubmittedOther += ($pr->production_amount/100);
                                  }
                              }
                              echo '<span>Not Submitted<br>';
                              echo money($notsubmittedOther);
                              echo '</span>';
                              ?>
                          </li>
                          <li class="greenbg"><?php $completedOther = 0;
                              foreach ($production as &$pr) {
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




<?php if($this->user->admin == "1"){ ?>
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
    $(document).ready(function(){

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
// $('ul.eventlist li').click(function(){
//   $('ul.eventlist li:first').slideUp('slow', function () { $(this).appendTo($('ul.eventlist')).fadeIn('slow'); });
// });



    });
    </script>
