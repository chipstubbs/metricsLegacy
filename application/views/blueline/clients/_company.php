<?php
$attributes = array('class' => '', 'id' => '_company');
echo form_open_multipart($form_action, $attributes);
?>

<?php if(isset($company)){ ?>
<input id="id" type="hidden" name="id" value="<?=$company->id;?>" />
<?php } ?>
<?php if(isset($view)){ ?>
<input id="view" type="hidden" name="view" value="true" />
<?php } ?>

<div class="form-group" style="position: relative; left: 9999px; height:0px;">
        <label for="reference"><?=$this->lang->line('application_reference_id');?> *</label>
        <input id="reference" type="text" name="reference" class="required form-control"  value="<?php if(isset($company)){echo $company->reference;} else{ echo $core_settings->company_reference; } ?>"   readonly="readonly"  />
</div>

<?php /*if(isset($company)){ ?>
<div class="form-group">
        <label for="contact"><?=$this->lang->line('application_primary_contact');?></label>
        <?php $options = array();
                $options['0'] = '-';
                foreach ($company->clients as $value):
                $options[$value->id] = $value->firstname.' '.$value->lastname;
                endforeach;
        if(isset($company->client->id)){ $client = $company->client->id; }else{$client = "0";}
            echo form_dropdown('client_id', $options, $client, 'style="width:100%" class="chosen-select"');
        }?>
</div>
<pre>_company

<?php
$ci = $GLOBALS['CI'];
print_r($ci->user);
?>
<?php }*/ ?>

</pre>
<div class="form-group">
        <label for="primary_contact"><?=$this->lang->line('application_primary_contact');?> *</label>
        <input id="primary_contact" type="text" name="primary_contact" class="required form-control"  value="<?php if(isset($company)){echo $company->primary_contact;} else { echo ''; } ?>" />
</div>

<div class="form-group">
        <label for="name"><?=$this->lang->line('application_company');?> <?=$this->lang->line('application_name');?> *</label>
        <?php if($this->user->admin == '1'): ?>
            <input id="name" type="text" name="name" class="required form-control" value="<?php if(isset($company)){echo $company->name;} ?>"  required/>
        <?php else: ?>
            <input id="name" type="text" name="name" class="required form-control" value="<?php if(isset($company)){echo $company->name;} ?>" readonly="readonly" required/>
        <?php endif; ?>
</div>
<?php
if ($this->session->userdata('year_to_view') == '2015'){
?>
    <div class="form-group">
            <label for="annual_goal">Annual Goal</label>
            <input id="annual_goal" type="text" name="annual_goal" class="required form-control" value="<?php if(isset($company)){echo $company->annual_goal;} ?>" />
    </div>
<?php }elseif ($this->session->userdata('year_to_view') == '2016') { ?>
    <div class="form-group">
            <label for="annual_goal2016">Annual Goal</label>
            <input id="annual_goal2016" type="text" name="annual_goal2016" class="required form-control" value="<?php if(isset($company)){echo $company->annual_goal2016;} ?>" />
    </div>
<?php } elseif ($this->session->userdata('year_to_view') == '2017') { ?>
    <div class="form-group">
        <label for="annual_goal2017">Annual Goal</label>
        <input id="annual_goal2017" type="text" pattern="[0-9,.]*" step="any" name="annual_goal2017" class="required form-control" value="<?php if(isset($company)){echo $company->annual_goal2017;} ?>" />
    </div>
<?php } elseif ($this->session->userdata('year_to_view') == '2018') { ?>
        <div class="form-group">
        <label for="annual_goal2018">Annual Goal</label>
        <input id="annual_goal2018" type="text" pattern="[0-9,.]*" step="any" name="annual_goal2018" class="required form-control" value="<?php if(isset($company)){echo $company->annual_goal2018;} ?>" />
    </div>
<?php } elseif ($this->session->userdata('year_to_view') == '2019') { ?>
        <div class="form-group">
        <label for="annual_goal2019">Annual Goal</label>
        <input id="annual_goal2019" type="text" pattern="[0-9,.]*" step="any" name="annual_goal2019" class="required form-control" value="<?php if(isset($company)){echo $company->annual_goal2019;} ?>" />
    </div>
<?php } ?>

<div class="form-group">
        <label for="website"><?=$this->lang->line('application_website');?></label>
        <input id="website" type="text" name="website" class="required form-control" value="<?php if(isset($company)){echo $company->website;} ?>" />
</div>
<div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="text" name="email" class="form-control" value="<?php if(isset($company)){echo $company->email;}?>" />
</div>
<div class="form-group">
        <label for="phone"><?=$this->lang->line('application_phone');?></label>
        <input id="phone" type="text" name="phone" class="form-control" value="<?php if(isset($company)){echo $company->phone;}?>" />
</div>
<div class="form-group">
        <label for="address"><?=$this->lang->line('application_address');?></label>
        <input id="address" type="text" name="address" class="form-control" value="<?php if(isset($company)){echo $company->address;}?>" />
</div>
<div class="form-group">
        <label for="zipcode"><?=$this->lang->line('application_zip_code');?></label>
        <input id="zipcode" type="text" name="zipcode" class="form-control" value="<?php if(isset($company)){echo $company->zipcode;}?>" />
</div>
<div class="form-group">
        <label for="city"><?=$this->lang->line('application_city');?></label>
        <input id="city" type="text" name="city" class="form-control" value="<?php if(isset($company)){echo $company->city;}?>" />
</div>
<div class="form-group">
        <label for="country"><?=$this->lang->line('application_country');?></label>
        <input id="country" type="text" name="country" class="form-control" value="<?php if(isset($company)){echo $company->country;}?>" />
</div>
<div class="form-group">
        <label for="province"><?=$this->lang->line('application_province');?></label>
        <input id="province" type="text" name="province" class="form-control" value="<?php if(isset($company)){echo $company->province;}?>" />
</div>

        <div class="modal-footer">
        <input type="submit" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save');?>"/>
        <a class="btn" data-dismiss="modal"><?=$this->lang->line('application_close');?></a>
        </div>
<?php echo form_close(); ?>
<script type="text/javascript">
$(document).ready(function(){
    // Set up help tips
        $(function () {
            $(".pop").tooltip();
            $('.pop').popover({
                trigger: 'hover focus',
                    'placement': 'top',
                    html: true
            });
            $('#deletebutton').popover({
                trigger: 'click',
                    'placement': 'top',
                    html: true
            });

        });

    // Format Numbers
        $('#annual_goal, #annual_goat2016, #annual_goal2017, #annual_goal2018, #annual_goal2019').maskMoney();
        $('#annual_goal, #annual_goat2016, #annual_goal2017, #annual_goal2018, #annual_goal2019').maskMoney('mask');
});
</script>
