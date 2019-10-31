<?php
/**
 * @file        Application View
 * @author      Luxsys <support@luxsys-apps.com>
 * @copyright   By Luxsys (http://www.luxsys-apps.com)
 * @version     2.2.0
 */
$act_uri = $this->uri->segment(1, 0);
$lastsec = $this->uri->total_segments();
$act_uri_submenu = $this->uri->segment($lastsec);
if(!$act_uri){ $act_uri = 'dashboard'; }
// if($act_uri == 'projects') {
//     redirect('reports');
// }
if(is_numeric($act_uri_submenu)){
    $lastsec = $lastsec-1;
    $act_uri_submenu = $this->uri->segment($lastsec);
}
$this->session->set_userdata('refer_from', '');
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <META Http-Equiv="Cache-Control" Content="no-cache">
    <META Http-Equiv="Pragma" Content="no-cache">
    <META Http-Equiv="Expires" Content="0">
    <link rel="SHORTCUT ICON" href="<?=base_url()?>assets/blueline/img/favicon.ico"/>
    <title><?=$core_settings->company;?></title>

    <!-- Bootstrap core CSS and JS -->
    <link href="<?=base_url()?>assets/blueline/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?=base_url()?>assets/blueline/js/plugins/jquery-1.11.0.min.js"></script>
    <link href="<?=base_url()?>assets/blueline/css/bootstrap-switch.min.css" rel="stylesheet">
    <script src="<?=base_url()?>assets/blueline/js/plugins/bootstrap-switch.min.js"></script>

    <!-- Custom styles for this template -->

   <script type="text/javascript">
  WebFontConfig = {
    google: { families: [ 'Open+Sans:400italic,400,300,600,700:latin' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
      '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })(); </script>
  <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/bootstrap.min.css">
    <!-- Plugins -->
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery-ui-1.10.3.custom.min.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/datepicker.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/bootstrap-timepicker.css"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/colorpicker.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/refineslide.css"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery-slider.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/summernote.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/chosen.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/dataTables.bootstrap.css" />
    <!-- DataTables -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css" /> -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.5/css/dataTables.responsive.css" /> -->
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/dataTables.responsive.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/fixedcolumns/3.0.4/css/dataTables.fixedColumns.css" />

    <!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.css"> -->
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery.mCustomScrollbar.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/xcharts.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/nprogress.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery-labelauty.css" />


    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/blueline.css"/>

    <link href="<?=base_url()?>assets/blueline/css/font-awesome.min.css" rel="stylesheet">


    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/user.css"/>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

<body>
<div id="mainwrapper">
 <a href="#" class="menu-trigger"><i class="fa fa-bars visible-xs"></i></a>
    <div class="side">
    <div class="sidebar-bg"></div>
        <div class="sidebar">
        <div class="navbar-header">

          <a class="navbar-brand" href="#"><img src="<?=base_url()?><?=$core_settings->logo;?>" alt="<?=$core_settings->company;?>"></a>
        </div>

          <ul class="nav nav-sidebar">
              <?php foreach ($menu as $key => $value) { ?>
               <?php
               $icon = "";
               switch($value->icon){
                    case "icon-th":
                        $icon = "fa-dashboard";
                    break;
                    case "icon-inbox":
                        $icon = "fa-inbox";
                    break;
                    case "icon-briefcase":
                        $icon = "fa-lightbulb-o";
                    break;
                    case "icon-user":
                        $icon = "fa-users";
                    break;
                    case "icon-list-alt":
                        $icon = "fa-file-text-o";
                    break;
                    case "icon-calendar":
                        $icon = "fa-calendar";
                    break;
                    case "icon-file":
                        $icon = "fa-archive";
                    break;
                    case "icon-tag":
                        $icon = "fa-tag";
                    break;
                    case "icon-cog":
                        $icon = "fa-cog";
                    break;
               }

               ?>
               <li id="<?=strtolower($value->name);?>" class="<?php if ($act_uri == strtolower($value->name)) {echo "active";}?>"><a href="<?=site_url($value->link);?>"><span class="menu-icon"><i class="fa <?=$icon;?>"></i></span>
                   <span class="nav-text"><?php echo $this->lang->line('application_'.$value->link);?></span>
                <?php if(strtolower($value->name) == "messages" && $messages_new[0]->amount != "0"){ ?><span class="notification-badge"><?=$messages_new[0]->amount;?></span><?php } ?>
                <?php if(strtolower($value->name) == "quotations" && $quotations_new[0]->amount != "0"){ ?><span class="notification-badge"><?=$quotations_new[0]->amount;?></span><?php } ?>
                <?php if(strtolower($value->name) == "tickets" && $tickets_new[0]->amount != "0"){ ?><span class="notification-badge"><?=$tickets_new[0]->amount;?></span><?php } ?>
               </a> </li>
              <?php } ?>
          </ul>

    <?php foreach ($widgets as $key => $val) {

        if($sticky && $val->link == "quickaccess"){ ?>
            <ul class="nav nav-sidebar quick-access menu-sub hidden-sm hidden-xs">
            <h4><?=$this->lang->line('application_quick_access');?></h4>

                <?php foreach ($sticky as $value): ?>
                    <li>
                        <a href="<?=base_url()?>projects/view/<?=$value->id;?>">
                          <p class="truncate"><i class="fa fa-lightbulb-o <?php if(!empty($value->tracking)){echo "fa-spin";} ?>"></i> <?php switch ($value->name) {
                                   case " ":
                                       echo "Metric Name Not Chosen";
                                       break;
                                   case "1":
                                       echo "Production";
                                       break;
                                   case "2":
                                       echo "Annuities";
                                       break;
                                   case "3":
                                       echo "ACATS";
                                       break;
                                  case "4":
                                      echo "Hot Prospect List";
                                      break;
                                  case "5":
                                      echo "Hot Client List";
                                      break;
                                  case "6":
                                      echo eventName($value->event)." Event Metrics";
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
                              } ?> </p>
                        </a>
                       <div class="submenu hidden">
                            <ul>
                            <?php if(isset($value->company->name)){ ?>
                            <li class="underline"><a href="<?=base_url()?>clients/view/<?=$value->company_id;?>"><b><?=$value->company->name?></b></a></li>
                            <?php } ?>
                              <li><a href="<?=base_url()?>projects/view/<?=$value->id;?>"> <?=$this->lang->line('application_go_to_project');?></li>
                              <!-- <li><a href="<?=base_url()?>projects/tracking/<?=$value->id;?>" id="<?=$value->id;?>"><?php if(empty($value->tracking)){ echo $this->lang->line("application_start_timer");}else{echo $this->lang->line("application_stop_timer");} ?></a></li> -->
                            </ul>

                        </div>
                    </li>
                   <?php endforeach; ?>
            </ul>
        <?php }

        if($user_online && $val->link == "useronline"){ ?>

            <ul class="nav nav-sidebar user-online menu-sub hidden-sm hidden-xs">
            <h4><?=$this->lang->line('application_user_online');?></h4>
        <?php foreach ($user_online as $value):
                if($value->last_active+(15 * 60) > time()){ $status = "online";}else{ $status = "away";} ?>
                <li>
                    <a href="#" class="<?=$status;?>">
                      <p class="truncate"><img class="img-circle" src="<?php
                if($value->userpic != 'no-pic.png'){
                  echo base_url()."files/media/".$value->userpic;
                }else{
                  echo get_gravatar($value->email);
                }
                 ?>" width="21px" /> <?php echo $value->firstname." ".$value->lastname;?> </p>
                    </a>
                    <!-- <div class="submenu">
                        <ul>
                          <li><a href="#"><span class="menu-icon"><i class="fa fa-envelope-o"></i></span> <?=$this->lang->line('application_write_a_message');?></a></li>
                        </ul>

                    </div> -->
                </li>
            <?php endforeach; ?>
            </ul>

            <?php if($client_online){ ?>
                <ul class="nav nav-sidebar user-online menu-sub hidden-sm hidden-xs">
                    <h4><?=$this->lang->line('application_client_online');?></h4>
                    <?php foreach ($client_online as $value):
                        if($value->last_active+(15 * 60) > time()){ $status = "online";}else{ $status = "away";} ?>
                        <li>
                            <a href="#" class="<?=$status;?>">
                              <p class="truncate"><img class="img-circle" src="
               <?php
                if($value->userpic != 'no-pic.png'){
                  echo base_url()."files/media/".$value->userpic;
                }else{
                  echo get_gravatar($value->email);
                }
                 ?>" width="21px"> <?php echo $value->firstname." ".$value->lastname;?> </p>
                            </a>
                           <!-- <div class="submenu">
                                <ul>
                                  <li><a href="#"><span class="menu-icon"><i class="fa fa-envelope-o"></i></span> <?=$this->lang->line('application_write_a_message');?></a></li>
                                </ul>

                            </div>-->
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php } } } ?>

        </div>
    </div>

    <div class="content-area" onclick="">
      <div id="overlay">
          <img src="http://fixedincomecounsel.com/img/ajax-loader.gif" id="img-load" />
      </div>
      <div class="row mainnavbar">
      <div class="topbar">
      <?php
                if($this->user->userpic != 'no-pic.png'){
                  $userimage = base_url()."files/media/".$this->user->userpic;
                }else{
                  $userimage = get_gravatar($this->user->email);
                }
                 ?>
      <span class="inline visible-xs"><a href="<?=site_url("agent");?>" data-toggle="mainmodal" title="<?=$this->lang->line('application_profile');?>"><img class="img-circle topbar-userpic" src="<?=$userimage;?>" height="21px"></a></span>
      <img class="img-circle topbar-userpic hidden-xs" src="<?=$userimage;?>" height="21px">

      <span class="hidden-xs"><?php echo character_limiter($this->user->firstname." ".$this->user->lastname, 25);?> </span>
      <span class="hidden-xs"><a href="<?=site_url("messages");?>" title="<?=$this->lang->line('application_messages');?>"><i class="fa fa-inbox"></i></a></span>
      <span class="hidden-xs"><a href="<?=site_url("agent");?>" data-toggle="mainmodal" title="<?=$this->lang->line('application_profile');?>"><i class="fa fa-cog"></i></a></span>
      <!-- <span class="btn-group">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                      <?php if(!empty($core_settings->language)){$default_language = $core_settings->language; }else{ $default_language = "english"; } ?>
                                      <img src="<?=base_url()?>assets/blueline/img/<?php if($this->input->cookie('language') != ""){echo $this->input->cookie('language');}else{echo $default_language;} ?>.png" style="margin-top:-49px" align="middle">

                                    </a>
                                     <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
                                        <?php if ($handle = opendir('application/language/')) {

									          while (false !== ($entry = readdir($handle))) {
									              if ($entry != "." && $entry != "..") {
									                ?><li><a href="<?=base_url()?>agent/language/<?=$entry;?>"><img src="<?=base_url()?>assets/blueline/img/<?=$entry;?>.png" class="language-img"> <?=ucwords($entry);?></a></li><?php
									              }
									          }

									          closedir($handle);
									          }
									    ?>

                                      </ul>
            </span> -->
      <span><a href="<?=site_url("logout");?>" title="<?=$this->lang->line('application_logout');?>"><i class="fa fa-sign-out"></i></a></span>
      </div>

        </div>




        <?=$yield?>






    </div>
    <!-- Notify -->
    <?php if($this->session->flashdata('message')) { $exp = explode(':', $this->session->flashdata('message'))?>
        <div class="notify <?=$exp[0]?>"><?=$exp[1]?></div>
    <?php } ?>


    <!-- Modal -->
    <div class="modal fade" id="mainModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="mainModalLabel" aria-hidden="true"></div>



    <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery-ui-1.10.3.custom.min.js"></script>

    <!-- Plugins -->
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/date-time/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/date-time/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/bootstrap-colorpicker.min.js"></script>

        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.knob.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.autosize-min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.maskMoney.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.inputlimiter.1.3.1.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.refineslide.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/summernote.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/chosen.jquery.min.js"></script>

        <!-- DataTables -->
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/fixedcolumns/3.0.4/js/dataTables.fixedColumns.min.js"></script>


        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.mCustomScrollbar.concat.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.nanoscroller.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jqBootstrapValidation.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/chart.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/d3.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/xcharts.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/nprogress.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery-labelauty.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/validator.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/blueline.js"></script>

        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/customs.js"></script>

 </div> <!-- Mainwrapper end -->
  <?php if ($this->user->admin == 1): ?>
      <script type="text/javascript" charset="utf-8">
      $(document).ready(function(){

          $('#clients .nav-text').text('Companies');

          $('.non-events').DataTable( {
              "search": {
                  "smart": true
              },
              "order": [[ 2, "asc" ]],
              "lengthChange": false,
              "iDisplayLength": 10,
              responsive: true,
              "language": {
                  "search": "",
                  "paginate": {
                      "previous": "<i class='fa fa-arrow-left'></i>",
                      "next" : "<i class='fa fa-arrow-right'></i>"
                  }
              }
          } );

      });
      </script>
  <?php endif; ?>
  <script type="text/javascript" charset="utf-8">
        $(window).load(function() {
            // When the page has loaded
            $(".content-area").css('visibility', 'visible');
        });
      getWidthAndHeight();
         // Get window height for sidebars
              $(window).resize(function() {
                getWidthAndHeight();
              });
                function getWidthAndHeight (){
                  var winHeight = $(window).height();
                  $('div.sidebar-bg').css({'height': winHeight,});
                  var winHeight2 = winHeight-165;
                  $('.message-list').css({'height': winHeight2,});
                  var winHeight3 = winHeight-65;
                  $('.messages-right').css({'height': winHeight3,});
                }


    // ToolTips
      $(document).ready(function(){
          $('.pop').tooltip({
              'placement': 'top'
          });
          $('.pop').popover({
              trigger: 'hover',
                  'placement': 'top',
                  html: true
          });
        $("form").validator();

        // Toggle

        $("#menu li a, .submenu li a").removeClass("active");
        if("" == "<?php echo $act_uri_submenu; ?>"){$("#sidebar li a").first().addClass("active");}
        <?php if($act_uri_submenu != "0"){ ?>$(".submenu li a#<?php echo $act_uri_submenu; ?>").parent().addClass("active");<?php } ?>
        $("#menu li#<?php echo $act_uri; ?>").addClass("active");


        //Datatables

        var dontSort = [];
                $('.data-sorting thead th').each( function () {
                    if ( $(this).hasClass( 'no_sort' )) {
                        dontSort.push( { "bSortable": false } );
                    } else {
                        dontSort.push( null );
                    }
                } );

        // $('#contacts').DataTable( {
        //     responsive: true
        // } );

        $('#EventAverages').DataTable( {
            "search": {
                "smart": true
            },
            "order": [[ 2, "asc" ]],
            "lengthChange": false,
            "iDisplayLength": 10,
            responsive: true,
            "language": {
                "search": "",
                "paginate": {
                    "previous": "<i class='fa fa-arrow-left'></i>",
                    "next" : "<i class='fa fa-arrow-right'></i>"
                }
            }
        } );
        $('.projects.events').addClass('table').DataTable( {
            "search": {
                "smart": true
            },
            "order": [[ 0, "asc" ]],
            "lengthChange": false,
            "iDisplayLength": 10,
            responsive: true,
            "language": {
                "search": "",
                "paginate": {
                    "previous": "<i class='fa fa-arrow-left'></i>",
                    "next" : "<i class='fa fa-arrow-right'></i>"
                }
            }
        } );

     $('#pendingAcats, #notSubmittedAcats, #completedAcats').DataTable( {
         "search": {
             "smart": true
         },
         "order": [[ 2, "asc" ]],
         "lengthChange": false,
         "iDisplayLength": 10,
         responsive: true,
         "language": {
             "search": "",
             "paginate": {
                 "previous": "<i class='fa fa-arrow-left'></i>",
                 "next" : "<i class='fa fa-arrow-right'></i>"
             }
         }
     } );
     $('#clientdashboard, #contacts').addClass('table').DataTable( {
         "search": {
             "smart": true
         },
         "order": [[ 1, "asc" ]],
         "lengthChange": false,
         "iDisplayLength": 10,
         responsive: true,
         "language": {
             "search": "",
             "emptyTable": "No entered or active clients/prospects. Click the blue button at the top right of this table to add a client/prospect.",
             "paginate": {
                 "previous": "<i class='fa fa-arrow-left'></i>",
                 "next" : "<i class='fa fa-arrow-right'></i>"
             }
         }
     } );
     $('#eventClientList').addClass('table').DataTable( {
         "search": {
             "smart": true
         },
         "order": [[ 0, "asc" ]],
         "lengthChange": false,
         "iDisplayLength": 10,
         responsive: true,
         "language": {
             "search": "",
             "paginate": {
                 "previous": "<i class='fa fa-arrow-left'></i>",
                 "next" : "<i class='fa fa-arrow-right'></i>"
             }
         }
     } );
     $('#hotProspectsTable, #hotClientsTable').addClass('table').DataTable( {
         "search": {
             "smart": true
         },
         "order": [[ 5, "desc" ]],
         "lengthChange": false,
         "iDisplayLength": 10,
         responsive: true,
         "language": {
             "search": "",
             "paginate": {
                 "previous": "<i class='fa fa-arrow-left'></i>",
                 "next" : "<i class='fa fa-arrow-right'></i>"
             }
         }
     } );


      });

      // Outside of doc load

      </script>
      <!-- Browser-Update -->
    <script>
        var $buoop = {vs:{i:9,f:15,o:12.1,s:5.1},c:2};
        function $buo_f(){
        var e = document.createElement("script");
        e.src = "//browser-update.org/update.js";
        document.body.appendChild(e);
        };
        try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
        catch(e){window.attachEvent("onload", $buo_f)}
    </script>

 </body>
</html>
