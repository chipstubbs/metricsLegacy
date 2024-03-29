<?php
$attributes = array('class' => '', 'id' => 'user_form');
echo form_open_multipart($form_action, $attributes);
?>

<div class="form-group">
    <?php if($this->user->admin == '1'){ ?>
        <label for="username"><?=$this->lang->line('application_username');?> *</label>
        <input id="username" type="text" name="username" class="required form-control"  value="<?php if(isset($user)){echo $user->username;} ?>"  required/>
    <?php } else { ?>
        <label for="username">Username:
            <input id="username" type="hidden" name="username" value="<?php if(isset($user)){echo $user->username;} ?>"/>
        <h4 style="display:inline-block;margin:0"><?php if(isset($user)){echo $user->username;} ?></h4></label>
    <?php } ?>
</div>
<div class="form-group">
        <label for="firstname"><?=$this->lang->line('application_firstname');?> *</label>
        <input id="firstname" type="text" name="firstname" class="required form-control"  value="<?php if(isset($user)){echo $user->firstname;} ?>"  required/>
</div>
<div class="form-group">
        <label for="lastname"><?=$this->lang->line('application_lastname');?> *</label>
        <input id="lastname" type="text" name="lastname" class="required form-control"  value="<?php if(isset($user)){echo $user->lastname;} ?>"  required/>
</div>
<div class="form-group">
        <label for="email"><?=$this->lang->line('application_email');?> *</label>
        <input id="email" type="email" name="email" class="required email form-control" value="<?php if(isset($user)){echo $user->email;} ?>"  required/>
</div>
<?php if($this->user->admin == '1'){ ?>
    <div class="form-group">
            <label for="company_id">Company *</label>
            <?php
                $result = count($companies);
                $options = array();
                $options['0'] = '-';
                for($i = 0; $i < $result; $i++ )
                {
                 $options[$companies[$i]->id] = $companies[$i]->name;
                }
                if(isset($user->company_id)){$client = $user->company_id;}else{$client = "";}
                echo form_dropdown('company_id', $options, $client, 'style="width:100%" class="chosen-select"');
            ?>
    </div>

<div class="form-group">
        <label for="password"><?=$this->lang->line('application_password');?> <?php if(!isset($user)){echo '*';} ?></label>
        <input id="password" type="password" name="password" class="form-control "  minlength="6" <?php if(!isset($user)){echo 'required';} ?>/>
</div>
<div class="form-group">
        <label for="password"><?=$this->lang->line('application_confirm_password');?> <?php if(!isset($user)){echo '*';} ?></label>
        <input id="confirm_password" type="password" name="confirm_password" class="form-control" data-match="#password" />
</div>
<?php } ?>
<div class="form-group">
                <label for="userfile"><?=$this->lang->line('application_profile_picture');?></label>
                <div>
                    <input id="uploadFile" type="text" name="dummy" class="form-control uploadFile" placeholder="Choose File" disabled="disabled" />
                          <div class="fileUpload btn btn-primary">
                              <span><i class="fa fa-upload"></i><span class="hidden-xs"> <?=$this->lang->line('application_select');?></span></span>
                              <input id="uploadBtn" type="file" name="userfile" class="upload" />
                          </div>
                  </div>
              </div>

<?php if(!isset($agent)){ ?>
<div class="form-group">
        <label for="title"><?=$this->lang->line('application_title');?> *</label>
        <input id="title" type="text" name="title" class="required form-control"  value="<?php if(isset($user)){echo $user->title;} ?>" />
</div>

<div class="form-group">
        <label for="status"><?=$this->lang->line('application_status');?></label>
        <?php $options = array(
                                'active'  => $this->lang->line('application_active'),
                                'inactive'    => $this->lang->line('application_inactive')
                               ); ?>

        <?php
        if(isset($user)){$status = $user->status;}else{$status = 'active';}
        echo form_dropdown('status', $options, $status, 'style="width:100%" class="chosen-select"');?>
</div>
<div class="form-group">
        <label for="admin"><?=$this->lang->line('application_admin');?></label>
        <?php $options = array(
                                '1'  => $this->lang->line('application_yes'),
                                '0'    => $this->lang->line('application_no')
                               ); ?>

        <?php
        if(isset($user)){$admin = $user->admin;}else{$admin = '0';}
        echo form_dropdown('admin', $options, $admin, 'style="width:100%" class="chosen-select"');?>
</div>
<div class="form-group">
        <label for="ljbutton">LeadJig Button</label>
        <?php $options = array(
                                '1'  => $this->lang->line('application_yes'),
                                '0'    => $this->lang->line('application_no')
                               ); ?>

        <?php
        if(isset($user)){$ljbutton = $user->ljbutton;}else{$ljbutton = '0';}
        echo form_dropdown('ljbutton', $options, $ljbutton, 'style="width:100%" class="chosen-select"');?>
</div>
<?php } ?>
<?php if( (!isset($agent) && $this->user->admin == "1") && $this->user->id != '68' ) {
$access = array();
if(isset($user)){ $access = explode(",", $user->access); }
?>
<?=$this->lang->line('application_module_access');?>

<div class="form-group">
<ul class="accesslist">
  <?php foreach ($modules as $key => $value) {
    if ($value->type == "widget" && !isset($wi)) { ?>
     <label>Widgets</label>
    <?php $wi = TRUE; } ?>

<li> <input type="checkbox" class="checkbox" id="r_<?=$value->link;?>" name="access[]" data-labelauty="<?=$this->lang->line('application_'.$value->link);?>" value="<?=$value->id;?>" <?php if(in_array($value->id, $access)){ echo 'checked="checked"';}?>>  </li>
<?php } ?>
</ul>
</div>
<?php } ?>


        <div class="modal-footer">
        <input type="submit" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save');?>"/>
        <a class="btn" data-dismiss="modal"><?=$this->lang->line('application_close');?></a>
        </div>

<?php echo form_close(); ?>
