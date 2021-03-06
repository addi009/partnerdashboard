<?php include "inc/head.php"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/asidebar.php"; ?>

<link rel="stylesheet" href="assets/css/dealflow.css">
<link rel="stylesheet" href="assets/bower_components/percircle/dist/css/percircle.css">
<div class="content-wrapper">
   <section class="content-header" style="background-color: #D8DBE0; height: 81px;">
      <?php 
        $partner_id = $_SESSION['partner_id'];
        $q = 'select * from partner where `partner_id`= '.$partner_id.' ';
        $row = mysqli_query($con_MAIN,$q);
        $res = mysqli_fetch_object($row);
       ?> 
      <h1>
          <?php 
            echo $_SESSION['user_fname'].' '.$_SESSION['user_lname'];echo ":  Pipeline Deal Flow Key Performance Indicators";
          ?>
          <?php
 //echo "<pre>"; print_r($_SESSION); exit;
 ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fas fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
<section class="content">
  <div class="row" id="pageStatsRow">
                  <?php 
                  
                  $query1= 'SELECT COUNT(*) as total_deals 
                            FROM 
                            tasks,users,containers,boards_users
                            WHERE 
                            tasks.task_container=containers.container_id AND
                            containers.container_board=boards_users.board_id AND
                            boards_users.user_id=users.user_id AND 
                            tasks.task_archived=0 AND  users.user_id='.$_SESSION["user_id"];
                  $res1= mysqli_query($con_TaskBoard,$query1);
                  $r = mysqli_fetch_object($res1);
                  //echo"<pre>";print_r($res1);exit();

                  $query2='SELECT SUM(task_funding_amount_requested) AS funding_amount 
                            FROM 
                            tasks,users,containers,boards_users 
                            WHERE 
                            tasks.task_container=containers.container_id AND
                            containers.container_board=boards_users.board_id AND
                            boards_users.user_id=users.user_id AND 
                            tasks.task_archived=0 AND users.user_id='.$_SESSION["user_id"];
                  $res2= mysqli_query($con_TaskBoard,$query2);
                  $r2 = mysqli_fetch_object($res2);
                  //echo"<pre>";print_r($r2);exit();
                  $query3='SELECT COUNT(*) AS funding_close 
                  FROM 
                  tasks,users,containers,boards_users 
                  WHERE containers.container_name="Funding Closed" AND tasks.task_container=containers.container_id AND containers.container_board=boards_users.board_id AND
                  boards_users.user_id=users.user_id AND 
                  tasks.task_archived=0 AND users.user_id='.$_SESSION["user_id"];
//echo $query3;exit;
                  $res3= mysqli_query($con_TaskBoard,$query3);
                  $r3= mysqli_fetch_object($res3);
                  //echo"<pre>";print_r($r3);exit();

                  $query4='SELECT COUNT(*) AS funding_denied 
                            FROM 
                            tasks,users,containers,boards_users 
                            WHERE 
                            containers.container_name in ("Funding Denied","Funded Denied") 
                            AND tasks.task_container=containers.container_id 
                            AND containers.container_board=boards_users.board_id 
                            AND boards_users.user_id=users.user_id 
                            AND tasks.task_archived=0 
                            AND users.user_id='.$_SESSION["user_id"];
                  $res4= mysqli_query($con_TaskBoard,$query4);
                  // echo"<pre>";print_r($query4);exit();

                  $r4= mysqli_fetch_object($res4);
                  if ($r3->funding_close=="" || $r3->funding_close=="0"){
                    $result4 = "TBD";
                  }else if($r4->funding_denied=="" || $r4->funding_denied=="0"){
                    $result4 = "TBD";
                  }else{
                    $result4 = round(intval($r3->funding_close) / intval($r4->funding_denied));
                    $result4 .= "%";
                    
                  }

              ?>
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#3598DC;">
            <div class="inner">
              <h3 class="white"><?php echo number_format($r->total_deals); ?></h3>

              <p class="white">Total Active Deals</p>
            </div>
            <div class="icon">
              <i class="fa fa-cog"></i>
            </div>
          </div>
        </div>
         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#E8505C;">
            <div class="inner">
              <h3 class="white">$<?php echo nice_number($r2->funding_amount,'format'); ?></h3>
              <p class="white">Total Deal Amount</p>
            </div>
            <div class="icon">
              <i class="fa fa-bar-chart"></i>
            </div>
          </div>
        </div>
      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#32C6D2;">
            <div class="inner">
              <h3 class="white"><?php echo number_format($r3->funding_close); ?></h3>
              <p class="white">Total Funded Deals</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#8A48A9;">
            <div class="inner">
              <?php
              $dealWinRate = 0;
              if($r3->funding_close!="" && $r3->funding_close!=0){
                $dealWinRate = $r3->funding_close/$r->total_deals;
                $dealWinRate = $dealWinRate*100;
              }
              ?>
              <h3 class="white"><?php echo number_format($dealWinRate)."%"; //echo $result4; ?></h3>

              <p class="white">Deal Win Rate</p>
            </div>
            <div class="icon">
              <i class="fa fa-trophy"></i>
            </div>
          </div>
        </div>                         
  </div> 
<?php 
$query5 = "SELECT * from tasks where task_type = 'Surety Bonding' and task_status = 'pending'";
$res5 = mysqli_query($con_TaskBoard,$query5);
?>
<div class="widget-box transparent">
  <div class="widget-header widget-header-flat" style="background-color:#4A442C;color:white;padding:5px;border-style:solid;border-color:#d2d6de;margin-bottom: 5px;">
    <h4 class="widget-title lighter" style="font-size: 20px">
      NEWLY SUBMITTED BONDING REQUEST
      <a href="#bonding_request" id="bonding_requesta" data-toggle="collapse"  aria-controls="bonding_request">
      <i class="ace-icon fa fa-chevron-up pull-right" id="bonding_requesti" style="color:white;"></i>
      </a>
    </h4>
  </div>
   
  <div id="bonding_request" class="collapse ">
    <div class="container br-container">
      <ul class="nav nav-pills " >
        <li class="active"><a data-toggle="pill" href="#today">TODAY</a></li>
        <li><a data-toggle="pill" href="#this_week">THIS WEEK</a></li>
        <li><a data-toggle="pill" href="#last_week">LAST WEEK</a></li>
      </ul>
      <div class="tab-content" style="background-color: #DED8C8">
        <div id="today" class="tab-pane fade in active" >
          <div class="row tab-row">
            <?php 
              $task_id = array();
              if($res5->num_rows > 0 ) { 
              while ($task_detail = mysqli_fetch_array($res5)) {
                array_push($task_id, $task_detail['task_id']);
                ?>
                <div class="col-md-4 br-task">
                  <div class="row bg-5">
                    <div class="col-md-3">
                      <div id="greencircle" data-percent="80" class="small green percircle animate gt50" style="background-color:unset; font-size: 61px;">
                        <span>80%</span>
                        <div class="slice">
                          <div class="bar" style="transform: rotate(288deg);">
                          </div>
                          <div class="fill">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 text-center bg-6">
                      <span class="clr-2" >BUSINESS<br>RATING<br>INDEX</span>
                    </div>
                    <div class="col-md-6">
                      <span style="color: #97b1d2; font-size: 11px;">CONTRACT TYPE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>PRIME</b><br>CONTRACT AMOUNT: &nbsp;&nbsp;<b>$2.8M</b></span>
                    </div>
                  </div>
                  <div class="row bg-7">
                    <div class="col-md-12">
                      <div class="row" style="margin: 3px;">
                        <div class="col-md-4 text-center bg-8"><strong  style="font-size: 11px;">REQUEST TYPE:</strong></div>
                        <div class="col-md-8 text-center bg-9"><strong  style="font-size: 11px;">BID, PERFORMANCE, PAYMENT</strong></div>
                      </div>
                    </div>
                  </div>
                  <div class="row bg-10">
                    <div class="col-md-12 text-center">
                      <a href="#" data-toggle="modal" data-target="#bonding-request-modal" style="color: #57b6e4;font-size: 28px;">CLICK HERE TO VIEW</a>
                    </div>
                  </div>
                </div>
              <?php }
              }else { ?>
              <div class="row text-center">
                <div class="col-md-12">
                  <h1>No Bonding Requests Found.</h1>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <div id="this_week" class="tab-pane fade">
          <div class="row tab-row">
            <div class="row text-center">
              <div class="col-md-12">
                <h1>No Bonding Requests Found.</h1>
              </div>
            </div>
          </div>
        </div>
        <div id="last_week" class="tab-pane fade">
          <div class="row tab-row">
            <div class="row text-center">
              <div class="col-md-12">
                <h1>No Bonding Requests Found.</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="widget-box transparent">
  <div class="widget-header widget-header-flat" style="background-color:#366090;color:white;padding:5px;border-style:solid;border-color:#d2d6de;margin-bottom: 5px;">
    <h4 class="widget-title lighter" style="font-size: 25px">
      Your Deal Flow Pipeline 
    </h4>
  </div>
</div>
<?php 
$task_ids = join(",",$task_id);
  $query8 = "SELECT u.business_index_rating,s.`Business Unit Name` as depart_name, s.`Contract Type` as contract_type, s.`PO Total` as po_total, us.type_of_business, us.year_established,us.task_id
FROM user as u, scprs_main as s, user_suretybond_form1 as us 
WHERE u.user_id = us.user_id AND us.scprs_record_id = s.scprs_record_id AND us.task_id IN ('".$task_ids."')";
$res8 = mysqli_query($con_MAIN,$query8);
$bonding_request_detail = mysqli_fetch_object($res8);
?>
<div class="modal fade" id="bonding-request-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" id="bonding-request-modal-header">
        <div class="row">
          <div class="col-md-2">
            <div id="greencircle" data-percent="80" class="small green percircle animate gt50" style="margin: 7px 0 0 0;">
              <span><?php echo $bonding_request_detail->business_index_rating; ?>%</span>
              <div class="slice">
                <div class="bar" style="transform: rotate(288deg);">
                </div>
                <div class="fill">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-2 bg-13">
            <span class="clr-2">BUSINESS<br>RATING<br>INDEX</span>
          </div>
          <div class="col-md-7 col-md-offset-1"> 
            <span class="font-20 clr-1">CONTRACT SPONSOR: &nbsp;<b><?php echo $bonding_request_detail->depart_name; ?></b><br>CONTRACT TYPE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $bonding_request_detail->contract_type; ?></b><br>CONTRACT AMOUNT: &nbsp;&nbsp;<b><?php echo $bonding_request_detail->po_total; ?></b></span>
          </div>
        </div>
      </div>
      <div class="modal-body bg-bonding-request-modal">
        <div class="row">
          <div class="col-md-12">
            <div class="row bonding-request-modal-margin">
              <div class="col-md-4 bg-11"><strong class="container-1">REQUEST TYPE:</strong></div>
              <div class="col-md-8 bg-12"><strong class="container-1">BID, PERFORMANCE, PAYMENT</strong></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="row bonding-request-modal-margin">
              <div class="col-md-6">
                <div class="row mb-20">
                  <div class="col-md-4 bg-1" style=""><span class="number"><?php echo $bonding_request_detail->year_established; ?></span></div>
                  <div class="col-md-8 bg-2"><span class="type">YEARS IN BUSINESS</span></div>
                </div>
                <div class="row mb-20">
                  <div class="col-md-3 bg-1" style=""><span class="number">4</span></div>
                  <div class="col-md-9 bg-2"><span class="type">PRIME CONTRACTS</span></div>
                </div>
                <div class="row mb-20">
                  <div class="col-md-3 bg-1" style=""><span class="number">8</span></div>
                  <div class="col-md-9 bg-2"><span class="type">SUB CONTRACTS</span></div>
                </div>
              </div>
              <div class="col-md-4 col-md-offset-2">
                <div class="row mb-20">
                  <div class="col-md-12">
                    <div class="row text-center bg-3">
                      <div class="col-md-12">
                        <span class="industry">INDUSTRY</span>
                      </div>
                    </div>
                    <div class="row text-center">
                      <div class="col-md-12 bg-4">
                        <span class="industry-type"><?php echo $bonding_request_detail->type_of_business; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mb-20">
                  <div class="col-md-12">
                    <div class="row text-center bg-3">
                      <div class="col-md-12">
                        <span class="industry">LICENCES</span>
                      </div>
                    </div>
                    <div class="row text-center">
                      <div class="col-md-12 bg-4">
                        <span class="industry-type">C-12<br>C-32<br>C-18</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row text-center">
          <div class="col-md-12">
            <a href="javascript:;" onclick="confirm_br_modal(<?php echo $bonding_request_detail->task_id; ?>)" type="button" class="btn bg-btn-pipeline"><span class="text-white font-20">MOVE TO PIPELINE</span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="bonding_request_confirm_modal_<?php echo $bonding_request_detail->task_id; ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="row" style="background-color: #4F81BC;border-radius: 20px;padding: 35px;">
        <div class="col-md-12 text-center" style="margin-bottom: 20px">
          <span style="color: #ffff;font-size: 18px">By Moving this Bond Request to your Deal Flow Pipeline you are indicating to the Business Owner that you are actively evaluating this Bond Request and will provide a formal decision on whether you will proceed within 48 hours. </span>
        </div>
        <div class="col-md-4 text-center">
          <a href="javascript:;" class="btn btn-agree" onclick="bonding_request_submit(<?php echo $bonding_request_detail->task_id; ?>)" style="font-size: 15px;color: #ffff;">YES I AGREE</a>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 text-center">
          <button class="btn btn-cancel-br" data-dismiss="modal" style="font-size: 15px"><span class="text-white">CANCEL</span></button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row" style="display: none;" id="reload_pipeline">
  <div class="col-md-12" align="right">
    <button class="btn btn-lg btn-md btn-refresh" style="background-color:  #4F81BC;border-radius: 15px" onclick="location.reload()" style="margin:1%;">NEW DEAL MOVED TO YOUR PIPELINE <br><span style="color: #d0ea7d">CLICK HERE TO REFRESH
</span></button>
  </div>
</div>
<iframe src = "https://cpm-stage1.pw/dashboard/pkanban/access/login_auto?user_id=<?php echo $_SESSION['user_id'];?>" width = "100%" height = "900px">
    Sorry your browser does not support inline frames.
</iframe>

</section>  
</div>
<?php include "inc/footer.php"; ?>
<script type="text/javascript" src="assets/bower_components/percircle/dist/js/percircle.js"></script>
<script type="text/javascript">
  function confirm_br_modal(task_id){
    $('#bonding_request_confirm_modal_'+task_id+'').modal('show');
    $('#bonding-request-modal').modal('hide');
  }
  $('#bonding_requesta').click(function(){
    if ($('#bonding_requesti').hasClass('ace-icon fa fa-chevron-up')) {
      $('#bonding_requesti').removeClass('ace-icon fa fa-chevron-up');
      $('#bonding_requesti').addClass('ace-icon fa fa-chevron-down');
    }else{
      $('#bonding_requesti').removeClass('ace-icon fa fa-chevron-down');
      $('#bonding_requesti').addClass('ace-icon fa fa-chevron-up');
    }
  });
  function bonding_request_submit(task_id){
    $.post("includes/bonding_request.php",{task_id: task_id}).done(function(e){
      $('#bonding_request_confirm_modal_'+task_id+'').modal('hide');
      $('#reload_pipeline').show();
    });
  }
</script>
