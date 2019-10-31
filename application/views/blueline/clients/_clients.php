<?php
$attributes = array('class' => '', 'id' => '_clients');
echo form_open_multipart($form_action, $attributes);
?>
<?php if (isset($client)) { ?>
    <input id="id" type="hidden" name="id" value="<?= $client->id; ?>"/>
<?php } ?>
<?php if (isset($view)) { ?>
    <input id="view" type="hidden" name="view" value="true"/>
<?php }
$newdata = array(
    'last_client' => $client->id,
    's_acat' => $client->acat,
    's_annuity' => $client->annuity_app,
    's_life' => $client->life_submitted,
    's_other' => $client->other,
    's_aum' => $client->aum
);

$this->session->set_userdata($newdata);

?>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group has-success has-feedback">
            <label for="spouse"><?= $this->lang->line('application_firstname'); ?> *</label>
            <input id="firstname" type="text" name="firstname" class=" form-control" value="<?php if (isset($client)) {
                echo $client->firstname;
            } ?>" placeholder="John" required/>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group has-success has-feedback">
            <label for="lastname"><?= $this->lang->line('application_lastname'); ?> *</label>
            <input id="lastname" type="text" name="lastname" class="required form-control"
                   value="<?php if (isset($client)) {
                       echo $client->lastname;
                   } ?>" placeholder="Smith" required/>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group has-success has-feedback">
            <label for="spouse">Spouse Name</label>
            <input id="spouse" type="text" name="spouse" class=" form-control" value="<?php if (isset($client)) {
                echo $client->spouse;
            } ?>"/>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">

    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="form-group has-success has-feedback">
            <label for="email"><?= $this->lang->line('application_email'); ?>
                <div class="help-block with-errors"></div>
            </label>
            <input id="email" type="email" name="email" class="required email form-control"
                   value="<?php if (isset($client)) {
                       echo $client->email;
                   } ?>" data-error="This email address is invalid" placeholder="Ex. johnsmith@example.com"/>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group has-success has-feedback">
            <label for="phone"><?= $this->lang->line('application_phone'); ?>
                <div class="help-block with-errors"></div>
            </label><!-- pattern="^(\d{3}(\s|\-)?){2}\d{4}$" -->
            <input id="phone" type="text" name="phone"  class="form-control"
                   value="<?php if (isset($client)) {
                       echo $client->phone;
                   } ?>" minlength="10" data-error="Must be 10 digits" placeholder="Ex. (999) 999-9999"/>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group has-success has-feedback">
            <label for="mobile"><?= $this->lang->line('application_mobile'); ?>
                <div class="help-block with-errors"></div>
            </label>
            <input id="mobile" type="text" name="mobile" class="form-control" value="<?php if (isset($client)) {
                echo $client->mobile;
            } ?>"
                   data-error="Ex. (999) 999-9999" minlength="10" placeholder="Ex. (999) 999-9999"/>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="address"><?= $this->lang->line('application_address'); ?></label>
            <input id="address" type="text" name="address" class="form-control" value="<?php if (isset($client)) {
                echo $client->address;
            } ?>" placeholder="Ex. 123 Appleseed Road, Apt. 14"/>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="city"><?= $this->lang->line('application_city'); ?></label>
            <input id="city" type="text" name="city" class="form-control" value="<?php if (isset($client)) {
                echo $client->city;
            } ?>" placeholder="Ex. Miami"/>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group ">

        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="form-group ">
            <label for="state">State</label>
            <input id="state" type="text" name="state" class="form-control" value="<?php if (isset($client)) {
                echo $client->state;
            } ?>" placeholder="Ex. Florida"/>
        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="form-group ">
            <label for="zipcode">Zip</label>
            <input id="zipcode" type="text" name="zipcode" class="form-control" value="<?php if (isset($client)) {
                echo $client->zipcode;
            } ?>" placeholder="Ex. 12345"/>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="source_media"> Source Media </label>
            <input id="source_media" type="text" name="source_media" class="form-control" value="<?php if (isset($client)) {
                echo $client->source_media;
            } ?>" placeholder="Ex. N/A, Direct Mail, Radio, Digital, Email" />
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="income"> Income </label>
            <input id="income" type="text" name="income" class="form-control" value="<?php if (isset($client)) {
                echo $client->income;
            } ?>" placeholder="Ex. 100k" />
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="assets"> Assets </label>
            <input id="assets" type="text" name="assets" class="form-control" value="<?php if (isset($client)) {
                echo $client->assets;
            } ?>" placeholder="Ex. 500k" />
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="age"> Age </label>
            <input id="age" type="text" name="age" class="form-control" value="<?php if (isset($client)) {
                echo $client->age;
            } ?>" placeholder="Ex. 65" />
        </div>
    </div>
</div>


<div class="form-group">
    <!-- <label for="password">Password <?php /*if(!isset($client)){echo "*";} */ ?></label> -->
    <input id="password" type="hidden" name="password" class="form-control" value="password"/>
</div>

<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="form-group">
            <label for="userfile"><?= $this->lang->line('application_profile_picture'); ?></label>
            <div>
                <input id="uploadFile" class="form-control uploadFile" placeholder="Choose File" disabled="disabled"/>
                <div class="fileUpload btn btn-primary">
                    <span><i class="fa fa-upload"></i><span
                            class="hidden-xs"> <?= $this->lang->line('application_select'); ?></span></span>
                    <input id="uploadBtn" type="file" name="userfile" class="upload"/>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="col-xs-12 col-md-6">
         <div class="form-group">
            <?php
    $access = array();
    if (isset($client)) {
        $access = explode(",", $client->access);
    }
    ?>
            <label>Module Access</label>

            <ul class="accesslist">
              <?php foreach ($modules as $key => $value) { ?>
            <li> <input type="checkbox" class="checkbox" id="r_<?= $value->link; ?>" name="access[]" value="<?= $value->id; ?>" <?php if (in_array($value->id, $access)) {
        echo 'checked="checked"';
    } ?> data-labelauty="<?= $this->lang->line('application_' . $value->link); ?>"> </li>
            <?php } ?>
            </ul>
        </div>

    </div>-->
</div>
<!-- Metric Details -->
<div class="row">
    <div class="col-xs-12">
        <div class="edit-table-head">Metric Details</div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="form-group">

            <label for="event_id">Event / Lead Source *</label>
            <?php
            $eventarray = array(0 => 'None', 1 => 'Unsolicited Referral', 2 => 'Solicited Referral', 3 => 'Client');
            foreach ($project as $p) {
                if (empty($p->event) && $p->name == '6') {
                    $eventarray[$p->id] = 'Event Name Not Selected';
                } else if ($p->name == '6') {
                    $eventarray[$p->id] = eventName($p->event) . ' in ' . $p->location . ' ' . $p->event_date;
                }
            }
            if (isset($client)) {
                $eventsid = $client->event_id;
            }
            echo form_dropdown('event_id', $eventarray, $eventsid, 'style="width:100%" class="chosen-select"');
            ?>


        </div>
    </div>
</div>


<hr>

<div class="row">
    <div class="col-xs-12 col-md-6" style="margin-top: 24px;">
        <div class="form-group">
            <div class="checkbox">
                <label for="sched_appt_check">
                    <input id="sched_appt_check" class="yesnocheck" type="checkbox" data-label-text="Scheduled an Appt.?"
                           name="sched_appt_check" <?php if ($client->sched_appt_check === 1) { echo 'checked="checked" value="1"'; } ?> />
                </label>
            </div>
        </div>
        
        <div class="form-group sched_appt_check has-success has-feedback">
            <!-- checking to see if first appointment is past -->

            <label for="appt_date">First Appt. Date
                <input class="form-control datepicker" placeholder="MM/DD/YYYY" name="appt_date" id="appt_date" type="text" value="<?php if (isset($client)) {
                    echo $client->appt_date;
                } ?>" data-date-format="mm/dd/yyyy" />
            </label>
            <label for="appt_date_2">Second Appt. Date
                <input class="form-control datepicker" placeholder="MM/DD/YYYY" name="appt_date_2" id="appt_date_2" type="text" value="<?php if (isset($client)) {
                    echo $client->appt_date_2;
                } ?>" data-date-format="mm/dd/yyyy" />
            </label>
            <label for="appt_date_3">Third Appt. Date
                <input class="form-control datepicker" placeholder="MM/DD/YYYY" name="appt_date_3" id="appt_date_3"
                       type="text" value="<?php if (isset($client)) {
                    echo $client->appt_date_3;
                } ?>" data-date-format="mm/dd/yyyy" />
            </label>
        </div>
        <div class="form-group checkbox kept-appt sched_appt_check">
            <label for="kept_appt">
                <input id="kept_appt" type="checkbox" name="kept_appt" class="yesnocheck" data-label-text="Kept Appt?"
                       value="1" <?php if ($client->kept_appt == '1') {
                    echo 'checked="checked"';
                } ?> />
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="client_prospect">Client or Prospect?</label>
            <div class="checkbox" style="padding-left:20px;">
                <input id="client_prospect" class="yesnocheck" type="checkbox" data-off-color="info"
                       data-on-color="success" data-on-text="&nbsp;" data-off-text="&nbsp;"
                       name="client_prospect" <?php if ($client->client_prospect == '1') {
                    echo 'checked="checked" value="1" data-label-text="Client"';
                } else {
                    echo 'data-label-text="Prospect"';
                } ?> />
            </div>
        </div>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <div class="checkbox">
                <label for="has_assets">
                    <span data-toggle="popover" class="pop" data-placement="top"
                          data-content="Dollar amount above your minimum requirement.">
                        <input id="has_assets" type="checkbox" class="yesnocheck" data-label-text="Has Assets"
                               name="has_assets" value="1" <?php if ($client->has_assets == '1') {
                            echo 'checked="checked"';
                        } ?> />
                    </span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <div class="checkbox">
                <label for="referral">
                    <input id="referral" class="yesnocheck" type="checkbox" data-label-text="Is Prospect a Referral?"
                           name="referral" <?php if ($client->referral == '1') {
                        echo 'checked="checked" value="1"';
                    } ?> />
                </label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group" id="isProspect">
            <div class="checkbox">
                <label for="hot_prospect"><span data-toggle="popover" class="pop" data-placement="top"
                                                data-content="Realistic probability of closing within three months.">
                    <input id="hot_prospect" type="checkbox" class="yesnocheck" name="hot_prospect"
                           data-label-text="Hot Prospect" value="1" <?php if ($client->hot_prospect == '1') {
                        echo 'checked="checked"';
                    } ?> /></span>
                    <!-- <p>Hot Prospect</p> -->
                </label>
            </div>
        </div>
        <div class="hot">
            <div class="form-group">
                <label for="last_contact">Date of Last Contact</label>
                <input class="datepicker form-control" name="last_contact" id="last_contact"
                       placeholder="MM/DD/YYYY" type="text" value="<?php if (isset($client)) {
                    echo $client->last_contact;
                } ?>" data-date-format="mm/dd/yyyy" readonly />
            </div>
            <div class="form-group">
                <label for="follow_up">Follow Up Date</label>
                <input class="datepicker form-control" name="follow_up" id="follow_up" placeholder="MM/DD/YYYY"
                       type="text" value="<?php if (isset($client)) {
                    echo $client->follow_up;
                } ?>" data-date-format="mm/dd/yyyy" readonly />
            </div>
            <div class="form-group">
                <label for="p_probable_acat_size">Probable ACAT Size</label>
                <div class="input-group">
                    <div class="input-group-addon">&dollar;</div>
                    <input id="p_probable_acat_size" type="text" pattern="[0-9,.]*" name="p_probable_acat_size"
                           class="number form-control" value="<?php if (isset($client)) {
                        echo $client->p_probable_acat_size;
                    } ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label for="closing_probability">Probability of Closing</label>
                <?php //DROPDOWN
                $probability = array(25 => '25%', 50 => '50%', 75 => '75%');
                if (isset($client)) {
                    $closing_probability = $client->closing_probability;
                }
                echo form_dropdown('closing_probability', $probability, $closing_probability, 'style="width:100%" class="chosen-select"');
                ?>
            </div>
            <div class="form-group">
                <label for="p_annuity_probability">Probable percentage to Annuity</label>
                <?php //DROPDOWN
                $p_probability = array(0 => '0%', 10 => '10%', 20 => '20%', 30 => '30%', 40 => '40%', 50 => '50%', 60 => '60%', 70 => '70%', 80 => '80%', 90 => '90%', 100 => '100%');
                if (isset($client)) {
                    $p_annuity_probability = $client->p_annuity_probability;
                }
                echo form_dropdown('p_annuity_probability', $p_probability, $p_annuity_probability, 'style="width:100%" class="chosen-select"');
                ?>
            </div>
            <!-- <div class="form-group">
                <label>Probability Weighted outcome</label>
                <div class="showinfo" data-toggle="popover" class="pop" data-placement="top" data-content="Populates after saving ACAT size and probability of closing.">
                    <?php
            $p_weighted = 0;
            $p_weighted = ($client->p_probable_acat_size / 100) * ($closing_probability / 100);
            echo money($p_weighted);
            ?>
                </div>
            </div> -->
            <div class="form-group">
                <label for="prospect_comment">Comments</label>
                <textarea class="note" name="prospect_comment"
                          id="prospect_comment"><?= $client->prospect_comment; ?></textarea>
            </div>
        </div>


    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <div class="checkbox">
                <label for="hot_client"><span data-toggle="popover" class="pop" data-placement="top"
                                              data-content="Realistic probability of closing additional business within three months.">
                        <input id="hot_client" type="checkbox" class="yesnocheck" data-label-text="Hot Client"
                               name="hot_client" value="1" <?php if ($client->hot_client == '1') {
                            echo 'checked="checked"';
                        } ?> /></span>
                    <!-- <p>Hot Client</p> -->
                </label>
            </div>
        </div>
        <div class="hot">
            <div class="form-group">
                <label for="c_last_contact">Date of Last Contact</label>
                <input class="datepicker form-control" name="c_last_contact" id="c_last_contact"
                       placeholder="MM/DD/YYYY" type="text" value="<?php if (isset($client)) {
                    echo $client->c_last_contact;
                } ?>" data-date-format="mm/dd/yyyy" readonly />
            </div>
            <div class="form-group">
                <label for="c_follow_up">Follow Up Date</label>
                <input class="datepicker form-control" name="c_follow_up" id="c_follow_up" placeholder="MM/DD/YYYY"
                       type="text" value="<?php if (isset($client)) {
                    echo $client->c_follow_up;
                } ?>" data-date-format="mm/dd/yyyy" readonly />
            </div>
            <div class="form-group">
                <label for="c_probable_acat_size">Probable ACAT Size</label>
                <div class="input-group">
                    <div class="input-group-addon">&dollar;</div>
                    <input id="c_probable_acat_size" type="text" pattern="[0-9,.]*" name="c_probable_acat_size"
                           class="number form-control" value="<?php if (isset($client)) {
                        echo $client->c_probable_acat_size;
                    } ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c_closing_probability">Probability of Closing</label>
                <?php //DROPDOWN
                $c_probability = array(25 => '25%', 50 => '50%', 75 => '75%');
                if (isset($client)) {
                    $c_closing_probability = $client->c_closing_probability;
                }
                echo form_dropdown('c_closing_probability', $probability, $c_closing_probability, 'style="width:100%" class="chosen-select"');
                ?>
            </div>
            <div class="form-group">
                <label for="c_annuity_probability">Probable percentage to Annuity</label>
                <?php //DROPDOWN
                $c_probability = array(0 => '0%', 10 => '10%', 20 => '20%', 30 => '30%', 40 => '40%', 50 => '50%', 60 => '60%', 70 => '70%', 80 => '80%', 90 => '90%', 100 => '100%');
                if (isset($client)) {
                    $c_annuity_probability = $client->c_annuity_probability;
                }
                echo form_dropdown('c_annuity_probability', $c_probability, $c_annuity_probability, 'style="width:100%" class="chosen-select"');
                ?>
            </div>
            <!-- <div class="form-group">
                <label>Probability Weighted outcome</label>
                <div class="showinfo" data-toggle="popover" class="pop" data-placement="top" data-content="Populates after saving ACAT size and probability of closing.">
                    <?php
            $c_weighted = 0;
            $c_weighted = ($client->c_probable_acat_size / 100) * ($c_closing_probability / 100);
            echo money($c_weighted);
            ?>
                </div>
            </div> -->
            <div class="form-group">
                <label for="client_comment">Comments</label>
                <textarea class="note" name="client_comment"
                          id="client_comment"><?= $client->client_comment; ?></textarea>
            </div>
        </div>

    </div>
</div>

<hr>

<div class="row">
    <div class="col-xs-12 col-md-6">

        <div class="form-group checkbox">
            <label for="acat">
                <span data-toggle="popover" class="pop" data-placement="top"
                      data-content="">
                    <input id="acat" type="checkbox" class="yesnocheck" data-label-text="A.C.A.T." name="acat"
                           value="1" <?php if ($client->acat == '1') {
                        echo 'checked="checked"';
                    } ?> />
                </span>
            </label>
        </div>
        <!-- <div class="alert alert-info" id="acatalert">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Advisor!</strong> Go to your <a href="http://metrics.advisorsacademy.com/projects" target="_blank">Production</a> page to add the application. Make sure to come back to this window to save.
        </div> -->
        <!-- <div class="form-group acat">
            <label for="acat_date_received">Date Received</label>
            <input class="form-control datepicker" placeholder="MM/DD/YYYY" name="acat_date_received" id="acat_date_received" type="text" value="<?php if (isset($client)) {
            echo $client->acat_date_received;
        } ?>"  data-date-format="mm/dd/yyyy" readonly  />
        </div>
        <div class="form-group acat">
            <label for="acat_received_amount" data-toggle="popover" class="pop" data-placement="top" data-content="How much the ACAT Application was in dollars.">Amount for Received ACAT</label>
            <div class="input-group">
                <div class="input-group-addon">&dollar;</div>
                <input id="acat_received_amount" type="text" pattern="[0-9,.]*" name="acat_received_amount" class="number form-control" value="<?php if (isset($client)) {
            echo $client->acat_received_amount;
        } ?>" />
            </div>
        </div> -->

    </div>
    <div class="col-xs-12 col-md-6">

        <div class="form-group checkbox">
            <label for="aum">
                <input id="aum" type="checkbox" class="yesnocheck" data-label-text="Assets Under Management" name="aum"
                       value="1" <?php if ($client->aum == '1') {
                    echo 'checked="checked"';
                } ?> />
            </label>
        </div>

    </div>
</div>

<hr>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group checkbox">
            <label for="annuity_app">
                <span data-toggle="popover" class="pop" data-placement="top"
                      data-content="Check this box if you have received an Annuity Application but not submitted. Go to your <em>Production</em> page to add the application.">
                    <input id="annuity_app" type="checkbox" class="yesnocheck"
                           data-label-text="Annuity Application Completed?" name="annuity_app"
                           value="1" <?php if ($client->annuity_app == '1') {
                        echo 'checked="checked"';
                    } ?> />
                </span>
            </label>
        </div>
        <!-- <div class="form-group">
            <label for="annuity_date_received">Date Received</label>
            <input class="form-control datepicker" placeholder="MM/DD/YYYY" name="annuity_date_received" id="annuity_date_received" type="text" value="<?php if (isset($client)) {
            echo $client->annuity_date_received;
        } ?>"  data-date-format="mm/dd/yyyy" readonly  />
        </div>
        <div class="form-group">
            <label for="annuity_received_amount" data-toggle="popover" class="pop" data-placement="top" data-content="How much the Annuity Application was in dollars.">Dollar Amount Applied For</label>
            <div class="input-group">
                <div class="input-group-addon">&dollar;</div>
                <input id="annuity_received_amount" type="text" pattern="[0-9,.]*" name="annuity_received_amount" class="number form-control" value="<?php if (isset($client)) {
            echo $client->annuity_received_amount;
        } ?>" />
            </div>
        </div> -->

    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group checkbox">
            <label for="life_submitted">
                <span data-toggle="popover" class="pop" data-placement="top"
                      data-content="Check this box if you have received life insurance business with this client. Go to your <em style='text-decoration: underline;'>Production</em> page to add the application.">
                    <input id="life_submitted" type="checkbox" class="yesnocheck"
                           data-label-text="Life Insurance Received?" name="life_submitted"
                           value="1" <?php if ($client->life_submitted == '1') {
                        echo 'checked="checked"';
                    } ?> />
                </span>
            </label>
        </div>
        <!-- <div class="form-group">
            <label for="life_date_received">Date Received</label>
            <input class="form-control datepicker" placeholder="MM/DD/YYYY" name="life_date_received" id="life_date_received" type="text" value="<?php if (isset($client)) {
            echo $client->life_date_received;
        } ?>"  data-date-format="mm/dd/yyyy" readonly  />
        </div>
        <div class="form-group">
            <label for="life_received_amount" data-toggle="popover" class="pop" data-placement="top" data-content="The Life Insurance Application target premium.">Target Premium</label>
            <div class="input-group">
                <div class="input-group-addon">&dollar;</div>
                <input id="life_received_amount" type="text" pattern="[0-9,.]*" name="life_received_amount" class="number form-control" value="<?php if (isset($client)) {
            echo $client->life_received_amount;
        } ?>" />
            </div>
        </div> -->
    </div>
</div>

<hr>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group checkbox">
            <label for="other">
                <span data-toggle="popover" class="pop" data-html="true" data-placement="top"
                      data-content="Check this box if you have received business applications other than ACAT/Annuity/Life. Go to your <em style='text-decoration: underline;'>Production</em> page to add the application.">
                    <input id="other" type="checkbox" class="yesnocheck" data-label-text="Other Business Received"
                           name="other" value="1" <?php if ($client->other == '1') {
                        echo 'checked="checked"';
                    } ?> />
                </span>
            </label>
        </div>
        <!-- <div class="form-group">
            <label for="other_date_received">Date Received</label>
            <input class="form-control datepicker" placeholder="MM/DD/YYYY" name="other_date_received" id="other_date_received" type="text" value="<?php if (isset($client)) {
            echo $client->other_date_received;
        } ?>"  data-date-format="mm/dd/yyyy" readonly  />
        </div>
        <div class="form-group">
            <label for="other_received_amount">Dollar Amount on Received Application</label>
            <div class="input-group">
                <div class="input-group-addon">&dollar;</div>
                <input id="other_received_amount" type="text" pattern="[0-9,.]*" name="other_received_amount" class="number form-control" value="<?php if (isset($client)) {
            echo $client->other_received_amount;
        } ?>" />
            </div>
        </div> -->
    </div>
    <div class="col-xs-12 col-md-6">

    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <?php
        // $money_variables = ['lifeamount', 'aum_amount', 'acat_xfered', 'annuity_prem_app', 'annuity_paid_prem', 'other_deposit', 'client_opportunity', 'prospect_opportunity', 'c_probable_acat_size', 'p_probable_acat_size'];
        ?>
    </div>
    <div class="col-xs-12 col-md-6">

    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-info alert-dismissible fade in" role="alert" style="text-align: center;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
            <h4 id="oh-snap!-you-got-an-error!"><i class="glyphicon glyphicon-info-sign" style="top: 2px;"></i>
                Reminder!</h4>
            <p>Make sure you click the "save" button after you've finished entering the information.</p>
        </div>
    </div>
</div>
<div id="appt_alert" class="row hide">
    <div class="col-xs-12">
        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="text-align: center;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
            <h4 id="oh-snap!-you-got-an-error!"><i class="glyphicon glyphicon-info-sign" style="top: 2px;"></i>
                Missing Appointment For Production!</h4>
            <p>Enter appointment date for production entry.</p>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button id="deletebutton" type="button" class="btn btn-danger delete po pull-left" data-toggle="popover"
            data-trigger="click" data-title="<b><?= $this->lang->line('application_really_delete'); ?></b>"
            data-content="<a class='btn btn-danger' href='<?= base_url() ?>clients/delete/<?= $client->id; ?>'><?= $this->lang->line('application_yes_im_sure'); ?></a>
                              <a class='btn po-close'><?= $this->lang->line('application_no'); ?></a>
                              <input type='hidden' name='td-id' class='id' value='<?= $client->id; ?>'>">
        <!-- <i class="fa fa-times"></i> -->
        Delete
    </button>

    <input type="submit" id="submitter" name="send" class="btn btn-primary" value="<?= $this->lang->line('application_save'); ?>"/>
    <a class="btn" data-dismiss="modal"><?= $this->lang->line('application_close'); ?></a>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        // date
        $('.datepicker').toArray().forEach(function(field){
            new Cleave(field, {
                datePattern: ['m', 'd', 'Y'],
                date: true
            })
        });

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
        $('#life_received_amount, #acat_received_amount, #other_received_amount, #annuity_received_amount, #client_opportunity, #prospect_opportunity, #c_probable_acat_size, #p_probable_acat_size').maskMoney();
        $('#life_received_amount, #acat_received_amount, #other_received_amount, #annuity_received_amount, #client_opportunity, #prospect_opportunity, #c_probable_acat_size, #p_probable_acat_size').maskMoney('mask');


        //Can't be both hot client and hot prospect
        $('#hot_client').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('#hot_prospect').bootstrapSwitch('state', false, false);
            }
        });
        $('#hot_prospect').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('#hot_client').bootstrapSwitch('state', false, false);
            }
        });


        // $('#acatalert').hide();
        // $('#acat').on('switchChange.bootstrapSwitch', function(event, state) {
        //     if(state === true){
        //         $('#acatalert').show(300);
        //     } else {
        //         $('#acatalert').hide();
        //     }
        // });

        //Client or Prospect Label Switch
        $('#client_prospect').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $(this).bootstrapSwitch('labelText', "Client");
                // console.log('client ON');
            } else {
                $(this).bootstrapSwitch('labelText', "Prospect");
            }
        });


        //Show Hide
        var checkboxes = ['sched_appt_check', 'life_submitted', 'aum', 'acat', 'annuity_app', 'annuity_paid', 'other', 'hot_client', 'hot_prospect'];

        checkboxes.forEach(function (entry) {
            $checkbox = '#' + entry;
            if ($($checkbox).is(":checked")) {
                $($checkbox).closest('.form-group').nextAll().show();
                // console.log("Is checked so we showing: " + $checkbox);
            } else {
                $($checkbox).closest('.form-group').nextAll().hide();
                // console.log("Is NOT checked so we hiding: " + $checkbox);
            }

            $($checkbox).on('switchChange.bootstrapSwitch', function (event, state) {
                var parentGroup = $(this).parents('.form-group');
                if (state == true) {
                    $(parentGroup).nextAll().show(200);
                    // console.log("State switched to true: " + $checkbox);
                } else {
                    $(parentGroup).nextAll().hide(100);
                    // console.log("State switched to false: " + $checkbox);
                }
            });
        });

        // TextArea
        $('.note').on({
            click: function () {
                $(this).css('background', 'rgb(243, 249, 255)');
            },
            focus: function () {
                $(this).css('background', 'rgb(243, 249, 255)');
            },
            blur: function () {
                $(this).css('background', 'rgb(255, 255, 255)');
            }
        });

        // Toggle
        $.fn.bootstrapSwitch.defaults.size = 'normal';
        $.fn.bootstrapSwitch.defaults.onText = 'Yes';
        $.fn.bootstrapSwitch.defaults.offText = 'No';
        $.fn.bootstrapSwitch.defaults.handleWidth = '20';
        $.fn.bootstrapSwitch.defaults.labelWidth = '280';
        $(".yesnocheck").bootstrapSwitch();
        $("#referral").labelWidth = '50';

        //        appointment date
        $('#acat, #aum, #annuity_app, #life_submitted, #other').on('switchChange.bootstrapSwitch', function(){
            var state = $(this).bootstrapSwitch('state');
            var allSched;
            if(state === true){
                if(!$('#has_assets').bootstrapSwitch('state')) $('#has_assets').bootstrapSwitch('state',true, true);
                if(!$('#client_prospect').bootstrapSwitch('state')) {
                    $('#client_prospect').bootstrapSwitch('state',true, true);
                    $('#client_prospect').bootstrapSwitch('labelText', "Client");
                } 
                if(!$('#sched_appt_check').bootstrapSwitch('state')) {
                    $('#sched_appt_check').bootstrapSwitch('state',true, true);
                    $('#sched_appt_check').closest('.form-group').nextAll().show();
                }
                allSched= $('#appt_date, #appt_date_2, #appt_date_3').map(function(){
                    if($(this).val()!='') return $(this).val();
                }).get();
                if(allSched.length === 0) {
                    $('#submitter').prop("disabled",true);
                    $('#appt_alert').show();
                    $('#appt_alert').removeClass('hide');
                }

            }
        });

        //update last contact
        // $('#c_follow_up, #follow_up, #appt_date, #appt_date_2, #appt_date_3').on('change', function () {
        //     var allVals;
        //     var now = new Date();
        //     var cur;
        //     var soonest = 999999;
        //     var nowString=(now.getMonth() + 1) + '/' + now.getDate() + '/' +  now.getFullYear();
        //     var candidate = nowString;
        //     var diffFromToday;
        //     if ($('#hot_prospect').bootstrapSwitch('state')) {
        //         allVals= $('#follow_up, #appt_date, #appt_date_2, #appt_date_3').map(function(){
        //             if($(this).val()!='') return $(this).val();
        //         }).get();
        //         $.each(allVals,function(idx,val){
        //             cur = new Date(val);
        //             diffFromToday = dateDiffInDays(cur, now);
        //             if(diffFromToday >= 0 && diffFromToday < soonest){
        //                 soonest = diffFromToday;
        //                 candidate = val;
        //             }
        //         });
        //         if(candidate===nowString) candidate='';
        //         $('#last_contact').val(candidate);
        //     }
        //     else {
        //         allVals= $('#c_follow_up, #appt_date, #appt_date_2, #appt_date_3').map(function(){
        //             if($(this).val()!='') return $(this).val();
        //         }).get();
        //         $.each(allVals,function(idx,val){
        //             cur = new Date(val);
        //             diffFromToday = dateDiffInDays(cur, now);
        //             if(diffFromToday >= 0 && diffFromToday < soonest){
        //                 soonest = diffFromToday;
        //                 candidate = val;
        //             }
        //         });
        //         if(candidate===nowString) candidate='';
        //         $('#c_last_contact').val(candidate);
        //     }

        //     var allAppts = $('#appt_date, #appt_date_2, #appt_date_3').map(function(){
        //         if($(this).val()!='') return $(this).val();
        //     }).get();

        //     if(allAppts.length > 0) {
        //         $('#submitter').prop("disabled",false);
        //         $('#appt_alert').hide();
        //     }
        // });

        // function dateDiffInDays(d1, d2) {
        //     var t2 = d2.getTime();
        //     var t1 = d1.getTime();

        //     return parseInt((t2 - t1) / (24 * 3600 * 1000));
        // }

        // <?php
        // //Check first scheduled appointment vs current date
        // $today = (int)date("Ymd");
        // if ($client->appt_date != '') {
        //     $dadate = DateTime::createFromFormat('m/d/Y', $client->appt_date)->format('Ymd');

        //     if ($today > $dadate) {
        //         // echo "$('#sched_appt_check').bootstrapSwitch('state', false); console.log('After change to false'); ";
        //         echo "console.log('its past');";
        //     }else {
        //         echo "console.log('Appointment is still in the future');";
        //     }
        // }else {
        //     $dadate = '';
        // }
        // ?>

    });
</script>
