<?php

$attributes = array('class' => '', 'id' => '_project');
echo form_open($form_action, $attributes);
if(isset($project)){ ?>
<input id="id" type="hidden" name="id" value="<?php echo $project->id; ?>" />
<?php }

// $link = $_SERVER['REQUEST_URI'];
// if (strpos($link,'create') !== false) {
//     echo '<div class="alert alert-info" role="alert">';
//     echo '<p>Enter the information for the event you wish to add. After you have clicked the "save" button it will appear in the "Events" section on this page labeled "Click to select Event Type"!</p>';
//     echo '</div>';
// }
//Check to see if a user is adding an event
if (isset($_GET['useradd'])) {
    $useradd = $_GET['useradd'];
}else{
    $useradd = '';
}
?>
<p class="hidden"></p>
<!-- Metric Details -->
<div class="edit-table-head" style="margin-top: 0;">Metric Details</div>

<div class="form-group" style="display: none;">
        <label for="reference"><?=$this->lang->line('application_reference_id');?> *</label>
            <input type="text" name="reference" class="form-control" id="reference" value="<?php if(isset($project)){echo $project->reference;} else{ echo $core_settings->project_reference;} ?>" />
</div>
<?php if($this->user->admin == '1'){ ?>
<div class="form-group">
                          <label for="name"><?=$this->lang->line('application_metric_name');?> *</label>
                          <?php
                          $nameoptionsAdmin = array(
                                      '0'   => '-',
                                      '1'   => 'Production',
                                      '2'   => 'Annuities',
                                      '3'   => 'ACATS',
                                      '4'   => 'Hot Prospect List',
                                      '5'   => 'Hot Client List',
                                      '6'   => 'Event Metrics',
                                      '7'   => 'Life Insurance',
                                      '8'   => 'Other Business',
                                      '9'   => 'AUM',
                                      '10'  => 'Year-to-Year'
                                  );
                          $nameoptions = array(
                                      '0' => '-',
                                      '6' => 'Event Metrics'
                                  );
                          if(isset($project)){ $names = $project->name; }
                          echo form_dropdown('name', $nameoptionsAdmin, $names, 'style="width:100%" required class="chosen-select"');
?>
</div>
<?php
}
else {
    if(isset($project)){ $names = $project->name; }
    echo '<input type="hidden" name="name" class="form-control" id="name" value="6" required/>';
}
?>
<!-- Client Dropdown -->
        <?php
        if(isset($project) && isset($project->company->id)){$client = $project->company->id;}else{$client = "";}
        if($this->user->admin == '1'){
            $options = array();
                $options['0'] = '-';
                foreach ($companies as $value):
                $options[$value->id] = $value->name;
                endforeach;
            ?>
            <div class="form-group">
                <label for="client"><?=$this->lang->line('application_client');?></label><br>
                <?php echo form_dropdown('company_id', $options, $client, 'style="width:100%" class="chosen-select"'); ?>
            </div>
        <?php }
        else {
            // form_hidden('company_id', 'johndoe');
            $options = array();
            foreach ($companies as $value):
            $options[$value->id] = $value->name;
        endforeach; ?>
            <input type="hidden" name="company_id" class="form-control" id="company_id" value="<?php echo $this->user->company_id; ?>" required/>
        <?php }?>


<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="event_date">Date</label>
            <input class="form-control datepicker" name="event_date" id="event_date" type="text" value="<?php if(isset($project)){echo $project->event_date;} ?>" data-date-format="mm-dd-yyyy" />
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="event_time">Time</label>
            <?php
            $timeoptions = array(
                    '09:00 AM' => '09:00 AM',
                    '09:30 AM' => '09:30 AM',
                    '10:00 AM' => '10:00 AM',
                    '10:30 AM' => '10:30 AM',
                    '11:00 AM' => '11:00 AM',
                    '11:30 AM' => '11:30 AM',
                    '12:00 PM' => '12:00 PM',
                    '12:30 PM' => '12:30 PM',
                    '01:00 PM' => '01:00 PM',
                    '01:30 PM' => '01:30 PM',
                    '02:00 PM' => '02:00 PM',
                    '02:30 PM' => '02:30 PM',
                    '03:00 PM' => '03:00 PM',
                    '03:30 PM' => '03:30 PM',
                    '04:00 PM' => '04:00 PM',
                    '04:30 PM' => '04:30 PM',
                    '05:00 PM' => '05:00 PM',
                    '05:30 PM' => '05:30 PM',
                    '06:00 PM' => '06:00 PM',
                    '06:30 PM' => '06:30 PM',
                    '07:00 PM' => '07:00 PM',
                    '07:30 PM' => '07:30 PM',
                    '08:00 PM' => '08:00 PM',
                    '08:30 PM' => '08:30 PM',
                );
            if(isset($project)){ $eventtime = $project->event_time; }
            echo form_dropdown('event_time', $timeoptions, $eventtime, 'id="event_time" style="width:100%" class="chosen-select"');
            ?>
        </div>
    </div>
</div>



<?php if ($project->event != 'radio' && $project->event != 'risradio' && $project->event != 'ristv'): ?>
    <?php if ($project->event != 'taxpro'): ?>
        <div class="form-group">
                                  <label for="location">Location</label>
                                  <input type="text" name="location" class="form-control" id="location"  value="<?php if(isset($project)){echo $project->location; } ?>" />
        </div>
    <?php endif; ?>

    <div class="form-group">
                              <label for="zip_codes">Zip Codes Used (Separate with comma)</label>
                              <input type="text" name="zip_codes" class="form-control" id="zip_codes"  value="<?php if(isset($project)){echo $project->zip_codes; } ?>" />
    </div>

    <div class="form-group">
                              <label for="filters">Filters Used</label>
                              <input type="text" name="filters" class="form-control" id="filters"  value="<?php if(isset($project)){echo $project->filters; } ?>" />
    </div>
<?php endif; ?>

<div class="form-group">
                        <label for="textfield"><?=$this->lang->line('application_description');?></label>
                        <textarea class="input-block-level form-control"  id="textfield" name="description"><?php if(isset($project)){echo $project->description;} ?></textarea>
</div>

<?php /******** Event Metrics Type ********/
if ($names == '6' || $useradd == 'yes') { ?>
<div class="edit-table-head">Event Metrics</div>
    <?php if (strpos($link,'update') !== false) {
        echo '<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '<p>Enter Event Type Below</p>';
        echo '</div>';
    }  ?>
    <div class="form-group">

            <label for="eventtype">Event Type *</label>
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
            ksort($eventoptions);
            if(isset($project)){ $events = $project->event; }
            if($isadmin){
                echo form_dropdown('event', $eventoptions, $events, 'id="events" style="width:100%" class="chosen-select"');
            }
            else {
                echo form_dropdown('event', $eventoptions, $events, 'id="events" style="width:100%" class="chosen-select"');
            }
            ?>

    </div>

    <div class="form-group" id="nameOrVenue">
        <label for="name_or_venue">Name or Venue</label>
        <input type="text" name="name_or_venue" class="form-control" id="name_or_venue"  value="<?php if(isset($project)){echo $project->name_or_venue; } ?>" />
    </div>

    <?php if ($project->event === 'platinumreferral') { ?>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="client_response">Number of Client Responses</label>
                    <input type="text" name="client_response" class="form-control" id="client_response"  value="<?php if(isset($project)){echo $project->client_response; } ?>" />
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="referral_response">Number of Referral Responses</label>
                    <input type="text" name="referral_response" class="form-control" id="referral_response"  value="<?php if(isset($project)){echo $project->referral_response; } ?>" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="client_attendee">Number of Client Attendees</label>
                    <input type="text" name="client_attendee" class="form-control" id="client_attendee"  value="<?php if(isset($project)){echo $project->client_attendee; } ?>" />
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="referral_attendee">Number of Referral Attendees</label>
                    <input type="text" name="referral_attendee" class="form-control" id="referral_attendee"  value="<?php if(isset($project)){echo $project->referral_attendee; } ?>" />
                </div>
            </div>
        </div>
    <?php } /*End of Platinum Referral Fields*/
    else { ?>


    <?php if ($project->event != 'radio' && $project->event != 'risradio' && $project->event != 'ristv'): ?><div class="row"  id="mailer">
        <div class="col-xs-12 col-md-6">

                <div class="form-group">
                    <label for="number_mailers">Number of Mailers</label>
                    <input type="text" name="number_mailers" class="form-control" id="number_mailers"  value="<?php if(isset($project)){echo $project->number_mailers; } ?>" />
                </div>

        </div>
        <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="mailers_cost">Cost of Mailers</label>
                    <div class="input-group">
                        <div class="input-group-addon">&dollar;</div>
                        <input type="text" name="mailers_cost" class="form-control" id="mailers_cost"  value="<?php if(isset($project)){echo $project->mailers_cost; } ?>" />
                    </div>
                </div>
        </div>
    </div><hr><?php endif; ?>

    <div class="row" id="ads">
        <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="ad" data-toggle="popover" data-content="Ex. Newspapers">Number of Ads</label>
                    <input type="text" name="ad" class="form-control" id="ad"  value="<?php if(isset($project)){echo $project->ad; } ?>" />
                </div>
        </div>
        <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="ad_cost">Cost of Ads</label>
                    <div class="input-group">
                        <div class="input-group-addon">&dollar;</div>
                        <input type="text" name="ad_cost" class="form-control" id="ad_cost"  value="<?php if(isset($project)){echo $project->ad_cost; } ?>" />
                    </div>
                </div>
        </div>
    </div>
    <hr>
    <div class="row" id="other-invite">
        <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="other_invite">Other Invites</label>
                    <input type="text" name="other_invite" class="form-control" id="other_invite"  value="<?php if(isset($project)){echo $project->other_invite; } ?>" />
                </div>
        </div>
        <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="other_invite_cost">Cost of Other Invites</label>
                    <div class="input-group">
                        <div class="input-group-addon">&dollar;</div>
                        <input type="text" name="other_invite_cost" class="form-control" id="other_invite_cost"  value="<?php if(isset($project)){echo $project->other_invite_cost; } ?>" />
                    </div>
                </div>
        </div>
    </div>
    <hr>


    <div class="form-group">
          <label for="total_responses"><?php if($project->event == 'radio' || $project->event == 'risradio' || $project->event == 'ristv'){ echo "Call Ins"; } else { echo "Total Responses"; } ?> *</label>
          <input type="text" name="total_responses" class="form-control" id="total_responses"  value="<?php if(isset($project)){echo $project->total_responses; } ?>" />
    </div>

    <?php }/* End of rest of fields if not Platinum Referral */ ?>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                  <label for="attended"><?php if($project->event == 'radio' || $project->event == 'risradio' || $project->event == 'ristv'){ echo "Calls Answered/Reached"; } else { echo "Total Attended"; } ?></label>
                  <input type="text" name="attended" class="form-control" id="attended"  value="<?php if(isset($project)){echo $project->attended; } ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                  <label for="bu_attended"><?php if ($project->event === 'platinumreferral') { echo "Total Referral Buying Units Attended"; } elseif($project->event == 'radio' || $project->event == 'risradio' || $project->event == 'ristv'){ echo "Total Buying Units Answered/Reached"; } else{ echo "Total Buying Units Attended"; } ?></label>
                  <input type="text" name="bu_attended" class="form-control" id="bu_attended"  value="<?php if(isset($project)){echo $project->bu_attended; } ?>" <?php if ($project->event == 'platinumreferral') {
                      echo 'data-placement="left" data-toggle="popover" data-content="Make sure to only enter Referrals here."';
                  } ?> />
            </div>
        </div>
    </div>


<div class="edit-table-head">ROI Totals</div>
    <div class="form-group">
                              <label for="total_event_cost">Other Event Cost *</label>
                              <div class="input-group">
                                  <div class="input-group-addon">&dollar;</div>
                                  <input type="text" name="total_event_cost" class="form-control" id="total_event_cost"  value="<?php if(isset($project)){echo $project->total_event_cost; } ?>" data-placement="top" data-toggle="popover" data-content="Cost to hold event excluding invite costs. (Mailers/Ads/etc)" />
                              </div>
    </div>

<?php }
/******** End Event Metrics Type ********/ ?>


        <div class="modal-footer">

        <?php if($this->user->admin == '1'){ ?>
            <button type="button" class="btn btn-danger pull-left" data-toggle="modal" data-target="#myModal">
              Delete
            </button>
        <?php } ?>

        <input type="submit" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save');?>"/>
        <a class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('application_close');?></a>
        </div>

<?php echo form_close(); ?>

<script type="text/javascript">
$( document ).ready(function() {
    // Set up help tips
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
        $('[data-toggle="popover"]').popover({
            trigger: 'hover'
        });
    });

    //Set up money Inputs
    $('#mailers_cost, #ad_cost, #other_invite_cost, #total_event_cost').maskMoney({prefix:''});
    $('#mailers_cost, #ad_cost, #other_invite_cost, #total_event_cost').maskMoney('mask');

    var $chosen =  $('.active-result.result-selected').data('option-array-index');

    $('#events').on('change', function(index,el){

        var $selected = $('.chosen-single span').text();
        if ( $selected === 'Guest Speaker')
        {
            $('label[for="name_or_venue"]').text('Club / Association');
            $('#nameOrVenue').show(300);
        }
        else if ($selected === 'Financial Literacy') {
            $('label[for="name_or_venue"]').text('Company Name');
            $('#nameOrVenue').show(300);
        }
        else if ($selected === 'P&C Partnership' || $selected === 'CPA / Attorney') {
            $('label[for="name_or_venue"]').text('Company Name');
            $('#nameOrVenue').show(300);
        }
        else if ($selected === 'TaxPro') {
            $('label[for="total_responses"]').text('Total 1040s');
        }
        else {
            $('#nameOrVenue').hide();
        }

    });

});
</script>

<?php if($this->user->admin == '1'){ ?>
<style>
div#myModal {
    top: 20%;
}
div#myModal .modal-dialog {
    width: 310px;
}
div#myModal .modal-content {
    border-bottom: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
}
div#myModal .modal-header {
    background: #444;
}
</style>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>&nbsp;
        <h4 class="modal-title" id="myModalLabel">Are you sure you want to delete?</h4>
      </div>
      <div class="modal-body">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal">No</button>
          <a class="pull-left btn btn-danger" role="button" href="<?=base_url()?>projects/delete/<?=$project->id;?>" >Delete</a>
          <div style="clear: both; content: ' '; "></div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
