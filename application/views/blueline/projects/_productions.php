<?php
$attributes = array('class' => 'productionform', 'id' => '_production');
echo form_open($form_action, $attributes);
function money($number){
    echo "&#36;".money_format('%!n', $number);
}
$from = $this->session->userdata('refer_from');
?>
<?php if(isset($production)){  ?>
        <input id="id" type="hidden" name="id" value="<?php echo $production->id; ?>" />
<?php } ?>

<input id="company_id" type="hidden" name="company_id" class="form-control" value="<?php if($this->user->admin == '0') {echo $this->user->company_id;} else { echo ''; } ?>" />
<div class="form-group">

        <label for="client_id">Client / Lead  *</label>
        <?php
            $eventarray = array(0 => 'No event selected...', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
            foreach ($projects as $p){
                if (!isset($eventarray[$p->id])) {
                    $eventarray[$p->id] = eventName($p->event).' in '.$p->location.' '.$p->event_date.'';
                }
            }
            $clientarray = [0 => 'Please select client...'];
            foreach ($client as $c){
                if ( !isset($clientarray[$c->id]) ) {
                    $clientarray[$c->id] = $c->lastname.', '.$c->firstname.' - '.$eventarray[$c->event_id];
                }
            }
            if(isset($production)){ $clientsid = $production->client_id; }
            else{ $clientsid = $last_client; }
            // else { $clientsid = 0; }

            if ( empty($client) ) {
                echo '<div class="modal-header" style="width: 600px; margin: 60px auto;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" style="font-size: 25px;"></i></button>
                        <h4 class="modal-title">Error!</h4>
                      </div>';
                echo '<div style="width: 600px;
                                  margin: -60px auto;
                                  background-color: #fff;
                                  text-align: center;
                                  padding: 35px;">';
                echo '<p>You must add a client or prospect first!</p>';
                echo '</div>';
                exit();
            }else {
                echo form_dropdown('client_id', $clientarray, $clientsid, 'style="width:100%" class="chosen-select" required');
            }

        ?>
</div>
<div class="form-group">

        <label for="production_type">Production Type *</label>
        <?php
            $production_type_array = array('' => 'Select Production Type...', 'aum' => 'AUM', 'acat' => 'ACAT', 'annuity' => 'Annuity', 'life' => 'Life Insurance', 'other' => 'Other Production' );
            if(isset($production)){ $productiontype = $production->production_type; }
            else{ $productiontype = $s_production_type; }
            echo form_dropdown('production_type', $production_type_array, $productiontype, 'id="production_type" style="width:100%" class="chosen-select"');
        ?>


</div>
<div class="form-group acatHide">
    <label for="fmo">FMO *</label>
    <?php
        $fmo_array = array('' => 'Choose an FMO...', 'cmic' => 'Creative One', 'eca' => 'ECA Marketing', 'aaa' => 'Advisors&rsquo; Academy', 'other' => 'Other' );
        if(isset($production)){ $fmo = $production->fmo; }
        echo form_dropdown('fmo', $fmo_array, $fmo, 'style="width:100%" class="chosen-select"');
    ?>
</div>
<div class="form-group acatHide">
        <label for="product_co">Company</label>
        <input id="product_co" type="text" name="product_co" class="form-control" value="<?php if(isset($production)){echo $production->product_co;} ?>" />
</div>
<div class="form-group acatHide">
        <label for="product_name">Product Name</label>
        <input id="product_name" type="text" name="product_name" class="form-control" value="<?php if(isset($production)){echo $production->product_name;} ?>" />
</div>
<hr>
<div class="form-group">
        <label for="app_date_received">Application Received and Not Submitted (Date)</label>
        <input class="form-control date-picker" name="app_date_received" id="app_date_received" type="text" value="<?php if(isset($production)){echo $production->app_date_received;} ?>" data-date-format="yyyy-mm-dd" />
</div>
<div class="form-group">
        <label for="production_amount">Application Amount</label>
        <div class="input-group">
            <div class="input-group-addon">&dollar;</div>
            <input id="production_amount" type="text" pattern="[0-9,.]*" step="any" name="production_amount" class="form-control" value="<?php if(isset($production)){ echo $production->production_amount; } ?>"  />
        </div>
</div>
<div class="form-group">
        <label for="production_submitted">Application Submitted Date</label>
        <input class="form-control date-picker" name="production_submitted" id="production_submitted" type="text" value="<?php if(isset($production)){echo $production->production_submitted;} ?>" data-date-format="yyyy-mm-dd" />
</div>
<div class="form-group">
        <label for="prem_paid_month">Actual Premium Paid Date</label>
        <input class="form-control date-picker" name="prem_paid_month" id="prem_paid_month" type="text" value="<?php if(isset($production)){echo $production->prem_paid_month;} ?>" data-date-format="yyyy-mm-dd" />
</div>
<div class="form-group toggle-paid-amount">
        <label for="prem_paid">Premium Paid</label>
        <div class="input-group">
            <div class="input-group-addon">&dollar;</div>
            <input id="prem_paid" type="text" pattern="[0-9,.]*" step="any" name="prem_paid" class="form-control" value="<?php if(isset($production)){ echo $production->prem_paid; } ?>" />
        </div>
</div>
<div class="form-group acatHide toggle-paid-amount">
        <label for="comp_agent_percent">Commission Percent</label>
        <span class="pull-right input-info">Enter numbers only</span>
        <input id="comp_agent_percent" type="number" step="any" name="comp_agent_percent" class="form-control" value="<?php if(isset($production)){ echo $production->comp_agent_percent; } ?>" />
</div>
<div class="form-group">
    <label for="production_notes">Comments</label>
    <textarea class="note" name="production_notes" id="production_notes" ><?php echo $production->production_notes; ?></textarea>
</div>

        <div class="modal-footer">
          <?php if(isset($production)){ ?>
            <a href="<?=base_url()?>projects/productionentry/<?=$production->pid;?>/delete/<?=$production->id;?>" class="btn btn-danger pull-left" ><?=$this->lang->line('application_delete');?></a>
          <?php }else{  ?>
         <a class="btn btn-default pull-left" <?php if($from == 'clientpage') { ?>onClick='location.replace("http://metrics.advisorsacademy.com/clients");' <?php }else{} ?> data-dismiss="modal"><?=$this->lang->line('application_close');?></a>
        <i class="fa fa-spinner fa-spin" id="showloader" style="display:none"></i>
        <!-- <input type="submit" id="send" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save_and_add');?>"/> -->
        <?php } ?>
        <input type="submit" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save');?>"/>
        </div>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function(){
    //Money fields
    $('#production_amount, #prem_paid').maskMoney();
    $('#production_amount, #prem_paid').maskMoney('mask');

    //Can only put in paid amount if paid date is Set
    var chosen =  $('#production_type').val();

    // $('.toggle-paid-amount').hide();
    if( $('#prem_paid').val() != '0.00'  ) { $('.toggle-paid-amount').show(); }

    // Removed so it wouldn't hide the fields below prem_paid_month when deleting the date
    // $('#prem_paid_month').change(function()
    // {
    //     var chosen =  $('#production_type').val();
    //     if( !$(this).val() ) {
    //         $('.toggle-paid-amount').hide(300);
    //     }
    //     else if (chosen === 'acat' || chosen === 'aum'){
    //         $('.toggle-paid-amount').show(300);
    //         $('.toggle-paid-amount.acatHide').hide();
    //     }
    //     else if (chosen != 'acat' && chosen != 'aum'){
    //         $('.toggle-paid-amount').show(300);
    //     }
    // });

    //Acat modifications
    if(chosen === 'acat' || chosen === 'aum'){acatStuff(chosen);}
    $('#production_type').on('change', function(index,el){

        var end = this.value;
        acatStuff(end);

    });

    function acatStuff(value){
        if(value ==='acat'){
            $('label[for="prem_paid_month"]').text('Completed ACAT Date');
            $('label[for="prem_paid"]').text('Final ACAT Amount');
            $(".acatHide").hide();
        }else if(value ==='aum'){
            $('label[for="prem_paid_month"]').text('Completed Date');
            $('label[for="prem_paid"]').text('Final Amount');
            $(".acatHide").hide();
        }
        else{
            $('label[for="prem_paid_month"]').text('Premium Paid Date');
            $('label[for="prem_paid"]').text('Premium Paid');
            $(".acatHide").show();
        }

    }
});
</script>
