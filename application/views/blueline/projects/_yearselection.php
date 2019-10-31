<?php
$attributes = array('class' => 'yearselect', 'id' => '_yearselect');
echo form_open($form_action, $attributes);
$from = $this->session->userdata('refer_from');
?>
<?php if (isset($production)) { ?>
    <input id="id" type="hidden" name="id" value="<?php echo $production->id; ?>"/>
<?php } ?>

<input id="company_id" type="hidden" name="company_id" class="form-control"
       value="<?php if ($this->user->admin == '0') {
           echo $this->user->company_id;
       } else {
           echo '';
       } ?>"/>
<div class="form-group">
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="year1">1<sup>st</sup> Year Selection</label>
                <?php
                $attributes = array('class' => 'yearchoice', 'id' => 'y2y_first');
                echo form_open($form_action, $attributes);
                $year_array = array('' => 'Set Year', 2014 => '2014', 2015 => '2015', 2016 => '2016', 2017 => '2017', 2018 => '2018' );
//                $year1 = $this->session->userdata('year1');
//                $year2 = $this->session->userdata('year2');
                echo form_dropdown('year1', $year_array, $year1, 'id="year1" class="chosen-select" style="width:80%;"');
                ?>
            </div>
        </div>
        <div class='col-xs-12 col-md-6'>
            <div class="form-group">
                <label for="year2">2<sup>nd</sup> Year Selection</label>
                <?php
                echo form_dropdown('year2', $year_array, $year2, 'id="year2" class="chosen-select" style="width:80%;"');
                ?>
            </div>
        </div>  
    </div>
    
</div>

<div class="modal-footer">

        <a class="btn btn-default pull-left" data-dismiss="modal"><?= $this->lang->line('application_close'); ?></a>
        <i class="fa fa-spinner fa-spin" id="showloader" style="display:none"></i>
        <!-- <input type="submit" id="send" name="send" class="btn btn-primary" value="<?= $this->lang->line('application_save_and_add'); ?>"/> -->
    <input type="submit" name="send" class="btn btn-primary" value="<?= $this->lang->line('application_save'); ?>"/>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        
    });
</script>
