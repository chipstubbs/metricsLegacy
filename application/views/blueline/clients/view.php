<?php $this->session->set_userdata('comingfrom', $uri); ?>
 <div class="row">

	<div class="col-md-12">
		<h2><?=$company->name;?> <?php echo $camefrom; ?></h2>
	</div>
</div>
		<div class="row">
		<div class="col-md-12 marginbottom20">
		<div class="table-head"><?=$this->lang->line('application_company_details');?> <span class="pull-right"><a href="<?=base_url()?>clients/company/update/<?=$company->id;?>/view" class="btn btn-primary" data-toggle="mainmodal"><i class="icon-edit"></i> <?=$this->lang->line('application_edit');?></a></span></div>
		<div class="subcont">
		<ul class="details col-md-6">
			<li><span><?=$this->lang->line('application_company_name');?>:</span> <?php echo $company->name = empty($company->name) ? "-" : $company->name; ?></li>
			<li><span>Primary Contact:</span> <?php if(isset($company->primary_contact)){ echo $company->primary_contact;}else{echo "";} ?></li>
			<li><span><?=$this->lang->line('application_email');?>:</span> <?php if(isset($company->email)){ echo $company->email; }else{ echo "-"; } ?></li>
			<li><span><?=$this->lang->line('application_website');?>:</span> <?php echo $company->website = empty($company->website) ? "-" : '<a target="_blank" href="http://'.$company->website.'">'.$company->website.'</a>' ?></li>
			<li><span><?=$this->lang->line('application_phone');?>:</span> <?php echo $company->phone = empty($company->phone) ? "-" : $company->phone; ?></li>
			<?php if($company->vat != ""){?>
			<li>&nbsp;</li>
			<?php } ?>
			<span class="visible-xs"></span>
		</ul>
		<ul class="details col-md-6">
			<li><span><?=$this->lang->line('application_address');?>:</span> <?php echo $company->address = empty($company->address) ? "-" : $company->address; ?></li>
			<li><span><?=$this->lang->line('application_zip_code');?>:</span> <?php echo $company->zipcode = empty($company->zipcode) ? "-" : $company->zipcode; ?></li>
			<li><span><?=$this->lang->line('application_city');?>:</span> <?php echo $company->city = empty($company->city) ? "-" : $company->city; ?></li>
			<li><span><?=$this->lang->line('application_country');?>:</span> <?php echo $company->country = empty($company->country) ? "-" : $company->country; ?></li>
			<li><span><?=$this->lang->line('application_province');?>:</span> <?php echo $company->province = empty($company->province) ? "-" : $company->province; ?></li>
		</ul>
		<br clear="all">
		</div>
		</div>
		</div>
        
<div class="row">
	 	<div class="col-md-12">
	 		<?php 
             if(!empty($company->clients[0])){ ?>
                <div class="alert alert-warning">
                    <?=$this->lang->line('application_client_has_no_contacts');?> <a href="<?=base_url()?>clients/create/<?=$company->id;?>" data-toggle="mainmodal"><?=$this->lang->line('application_add_new_contact');?></a>
                </div>
	 		<?php } ?>
	 	<div class="data-table-marginbottom">

             <!-- Check for appointments Kept -->
             <?php
                echo "\n";
                $timestamp = time(); 
                $timestampClock = date('m/d/Y h:i:s a', time());
                $date = date("Y-m-d"); 
                $today = (int)date("Ymd");
                $showAppointments =  0;

                foreach ($clients as $cs) {
                    $thedate = '';
                    if ($cs->sched_appt_check === 1 && $cs->appt_date != '' && $cs->kept_appt === 0) {
                        
                        $thedate = DateTime::createFromFormat('m/d/Y', $cs->appt_date)->format('Ymd');

                        if ($today > $thedate) { ++$showAppointments; }

                    } 
                }
            ?>  

                <div class="row" <?php echo ($showAppointments === 0) ? 'style="display:none;"' : 'has appointments'; ?> >
                    <div class="col-sm-12 col-md-12">
                        <div class="stdpad" style="background:white;">
                            <div class="table-head">Appointment Check</div>
                            <div id="main-nano-wrapper" class="nano has-scrollbar">
                                <div class="nano-content" tabindex="0" style="padding-right:15px;">
                                <?php
                                    foreach ($clients as $c) {

                                        //Check first scheduled appointment vs current date
                                        $dadate = '';

                                        if ($c->sched_appt_check === 1 && $c->appt_date != '' && $c->kept_appt === 0 && $c->appt_checked_no != 1) {
                                            $dadate = DateTime::createFromFormat('m/d/Y', $c->appt_date)->format('Ymd');

                                            if ($today > $dadate) {
                                                //Reminders Here

                                                ?>

                                                    <!--Appointment Checking Individual Start-->
                                                    <p style="padding: 10px 10px 20px 10px; border-bottom: 1px solid #ddd;">
                                                        <?php 
                                                        echo "<span style=''>Did you keep your appointment on ".$c->appt_date." with <b>".$c->firstname." ".$c->lastname."</b></span>";
                                                        ?>
                                                        <a class="btn btn-success pull-right" href="<?=base_url()?>clients/appointmentCheck/<?=$c->id;?>" style="margin-left:20px;">Yes </a>
                                                        <!--<a class="btn btn-danger pull-right" data-dismiss="alert">No</a>-->
                                                        <a class="btn btn-danger pull-right" data-dismiss="alert" href="<?=base_url()?>clients/removeScheduledAppointment/<?=$c->id;?>" >No </a>
                                                        
                                                    </p>
                                                    <!--Appoint Checking Individual End-->


                                            <?php
                                            }else {
                                                //Do nothing
                                                //echo "<span style='color:green;'>".$c->firstname." ".$c->lastname."'s appoint is still in the future.</span> \n";
                                            }
                                        }else {
                                            //No Appointments
                                            $dadate = '';
                                            //echo "<span style='color:rgb(195, 176, 32)  ;'>".$c->firstname." ".$c->lastname." has no appointments.</span> \n";
                                        }

                                    }
                                    //close the row
                                ?>
                                </div>
                                    <div class="nano-pane" style="display: none;">
                                        <div class="nano-slider" style="height: 282px; transform: translate(0px, 0px);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

        <!-- ADD NEW 'ADVISOR CLIENT' -->
            <?php 
                // if($this->user->admin == '0'){ 
                ?>
              <div class="stdpad dash">
                  <h2>Clients/Prospects
                      <span class="pull-right" style="margin-top: -0.25%;">
                          <a href="<?=base_url()?>clients/create/<?=$this->user->company_id;?>/dash" class="btn btn-primary" data-toggle="mainmodal">Add Client/Prospect</a>
                      </span>
                  </h2>
                  <div><!-- id="main-nano-wrapper" class="nano" -->
                      <div><!--  class="nano-content" -->
                          <!-- BEGIN LIST CLIENTS -->
                              <?php
                              /* Production array to show acats */
                              $productions = [];
                              foreach ($production as $prod):
                                  if ($prod->production_type == 'acat') {
                                      $productions[$prod->client_id] = $prod->production_amount/100;
                                  }
                              endforeach;
                              ?>
                             <div class="subcont">
                                 <!-- hover display no-wrap -->
                                  <table id="clientdashboard" class="table-striped table-hover dt-responsive">
                                      <thead>
                                          <tr>
                                              <th></th>
                                              <th>Name</th>
                                              <th>Event/Lead Source</th>
                                              <th>Client/Prospect</th>
                                              <th>Media Source</th>
                                              <th>Income</th>
                                              <th>Assets</th>
                                              <th>Age</th>
                                              <!-- <th>A.C.A.T.</th> -->
                                              <!-- <th>Action</th> -->
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php
                                          $count = 0;
                                          foreach ($clients as $value):  $count = $count+1; ?>
                                          <tr href="<?=base_url()?>clients/update/<?=$value->id;?>" data-toggle="mainmodal">
                                              <td style="width: 30px;"><a href="<?=base_url()?>clients/update/<?=$value->id;?>" class="btn-option" data-toggle="mainmodal">
                                                  <img class="minipic35" src="
                                                      <?php
                                                      if($value->userpic != 'no-pic.png'){
                                                          echo base_url()."files/media/".$value->userpic;
                                                      }else{
                                                          echo get_gravatar($value->email, '20');
                                                      }
                                                      ?>
                                                  " /></a>
                                              </td>
                                              <td><?php echo $value->firstname.' '.$value->lastname; ?></td>
                                              <td><?php
                                                      $eventarray = array(0 => 'None', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
                                                      foreach ($projects as $p){
                                                          $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                                                      }
                                                      echo $eventarray[$value->event_id];
                                                  ?></td>
                                              <td><?php echo ($value->client_prospect == 1) ? 'Client' : 'Prospect'; ?></td>
                                              <!-- <td><?php //echo money($productions[$value->id]); ?></td> -->
                                              <!-- <td onclick="$(this).off();">
                                                  <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>clients/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i></button>
	                                              <a href="<?=base_url()?>clients/update/<?=$value->id;?>" title="<?=$this->lang->line('application_edit');?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
                                              </td> -->
                                              <td><?php echo $value->source_media; ?></td>
                                              <td><?php echo $value->income; ?></td>
                                              <td><?php echo $value->assets; ?></td>
                                              <td><?php echo $value->age; ?></td>
                                          </tr>
                                          <?php endforeach;?>

                                          <?php if($count == 0) { ?>
                                              <!-- No entered or active clients / prospects -->
                                          <?php } ?>
                                      </tbody>
                                  </table>


                             </div>

                         <!-- END LIST CLIENTS -->
                </div>
                <?php 
                    // } 
                    ?>
            </div>

        </div>
        <!-- END ADD NEW 'ADVISOR CLIENT' -->
	</div>
	</div>
</div>

<div class="row" style="display: none;">
    <div class="col-md-12"><!--Was 6 columns-->
        <div class="data-table-marginbottom">
            <!-- Metrics -->
            <div class="table-head"><?=$this->lang->line('application_projects');?></div>
            <div class="table-div">
            <table id="projects" class="table" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
            <thead>
                <th class="hidden-xs" style="width:70px">Sheet Number</th>
                <th><?=$this->lang->line('application_name');?></th>
                <th>Total Event Cost</th>
            </thead>
            <?php foreach ($company->projects as $value):?>

            <tr id="<?=$value->id;?>" >
                <td class="hidden-xs" style="width:70px"><?=$value->name;?></td>
                <td><?php switch ($value->name) {
                         case "0":
                             echo "Metric Name Not Chosen";
                             break;
                         case "1":
                             echo "Production";
                             break;
                         case "2":
                             echo "Pending New Business";
                             break;
                         case "3":
                             echo "Pending ACATS / Rollovers";
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
                        case "7":
    						echo "Life Insurance";
    						break;
    					case "8":
    						echo "Other Business";
    						break;
    					case "9":
    						echo "AUM";
    						break;
                        case "10":
    						echo "Year to Year";
    						break;
                         default:
                             echo "Metric Name Not Chosen";
                    } ?></td>
                <td class="hidden-xs"><div class="hide progress progress-striped active progress-medium tt <?php if($value->progress== "100"){ ?>progress-success<?php } ?>" title="<?=$value->progress;?>%">
                          <div class="bar" style="width:<?=$value->progress;?>%"></div>
                    </div>
                    <!-- This shows that I can pull project values to show the "Metric Totals Sheet" details. -->
                    Total Event Cost: [<?=$value->total_event_cost;?>]
                </td>
            </tr>

            <?php endforeach;?>
            </table>
            <?php if(!$company->projects) { ?>
            <div class="no-files">
                <i class="fa fa-lightbulb-o"></i><br>

                <?=$this->lang->line('application_no_projects_yet');?>
            </div>
             <?php } ?>
            </div>
        </div>
    </div>

		<!--<div class="col-md-6">
	 	<div class="data-table-marginbottom">
		<div class="table-head"><?=$this->lang->line('application_invoices');?></div>
		<div class="table-div">
		<table id="invoices" class="data-no-search table" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
		<thead>
			<th width="70px"><?=$this->lang->line('application_invoice_id');?></th>
			<th><?=$this->lang->line('application_issue_date');?></th>
			<th><?=$this->lang->line('application_due_date');?></th>
			<th><?=$this->lang->line('application_status');?></th>
		</thead>
		<?php foreach ($company->invoices as $value):?>

		<tr id="<?=$value->id;?>" >
			<td><?=$value->reference;?></td>
			<td><span class="label"><?php $unix = human_to_unix($value->issue_date.' 00:00'); echo date($core_settings->date_format, $unix);?></span></td>
			<td><span class="label <?php if($value->status == "Paid"){echo 'label-success';} if($value->due_date <= date('Y-m-d') && $value->status != "Paid"){ echo 'label-important tt" title="'.$this->lang->line('application_overdue'); } ?>"><?php $unix = human_to_unix($value->due_date.' 00:00'); echo date($core_settings->date_format, $unix);?></span></td>
			<td><span class="label <?php $unix = human_to_unix($value->sent_date.' 00:00'); if($value->status == "Paid"){echo 'label-success';}elseif($value->status == "Sent"){ echo 'label-warning tt" title="'.date($core_settings->date_format, $unix);} ?>"><?=$this->lang->line('application_'.$value->status);?></span></td>
		</tr>
		<?php endforeach;?>
		</table>
		<?php if(!$company->invoices) { ?>
        <div class="no-files">
            <i class="fa fa-file-text"></i><br>

            <?=$this->lang->line('application_no_invoices_yet');?>
        </div>
         <?php } ?>
		</div>
		</div>
		</div>-->
</div>

<div class="row">

	<div class="col-xs-12 col-sm-12">
        <?php $attributes = array('class' => 'note-form', 'id' => '_notes');
            echo form_open(base_url()."clients/notes/".$company->id, $attributes); ?>
     <div class="table-head"><?=$this->lang->line('application_notes');?> <span class=" pull-right"><a id="send" name="send" class="btn btn-primary"><?=$this->lang->line('application_save');?></a></span><span id="changed" class="pull-right label label-warning"><?=$this->lang->line('application_unsaved');?></span></div>

      <textarea class="input-block-level summernote-note" name="note" id="textfield" ><?=$company->note;?></textarea>
    </form>
    </div>
</div>

<div class="col-sm-12  col-md-12 main">
		<div class="row">
             <br>

             <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>



            <div class="bs-callout bs-callout-default">
                <h4>CSV Import</h4>
                <p style="font-size: 12px;">Make sure your CSV file has these fields:</p>
                <ul style="font-size: 12px;">
                    <li>firstname (fname, first name, First Name)</li>
                    <li>lastname (lname, last name, Last Name)</li>
                    <li>email (Email)</li>
                </ul>
                <form method="post" action="<?php echo base_url(); ?>clients/importcsv" enctype="multipart/form-data">
                    <input type="file" name="userfile" id="upload" class="custom-file-input" style="display: inline-block;">
                    <span id="filenamespan" style="display: inline-block;">&nbsp;</span>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                </form>
            </div>


        </div>
</div>
<script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/cleave.js"></script>
<script>
$('#upload').change(function(){
    var filename = $('#upload').val().replace(/C:\\fakepath\\/i, '');
    $('#filenamespan').prepend(filename);
});

$(function () {
    $(".pop").tooltip();
    $('.pop').popover({
        trigger: 'click',
        'placement': 'top',
        html: true
    });
    $('#deletebutton').popover({
        trigger: 'click',
        'placement': 'top',
        html: true
    });

});
</script>
