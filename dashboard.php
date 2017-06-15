<?php include('header.php');



include "db/config.php";
?>
<?php
$startDate = date('Y-m-d') . ' 00:00:00';
$endDate = date('Y-m-d') . ' 23:59:59';
$currentTime = date('Y-m-d H:i:s');
$InactiveTime = date("Y-m-d H:i:s", strtotime('-30 minutes', strtotime($currentTime)));
//echo $InactiveTime;die;

/* ========================Count Prasent Surver================================================ */
$sql = "select distinct CREATED_BYID from trn_surveylive where SERVEY_DATE between '" . $startDate . "'  AND '" . $endDate . "' AND `CREATED_BYRID` = '5'";
$result = mysqli_query($connection, $sql);
if ($result) {
    $total_present = mysqli_num_rows($result);
} else {
    $total_present = 0;
}

/* ========================Count Absebt Surver================================================ */
$sql1 = "select SURVEYORID from mst_surveyor";
$result1 = mysqli_query($connection, $sql1);
if ($result1) {
    $total_emp = mysqli_num_rows($result1);
} else {
    $total_emp = 0;
}
$total_absent = $total_emp - $total_present;

/* ========================Count InActive Surver================================================ */
$InActibe = array();
$sqk = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, max(trn_surveylive.`SERVEY_DATE`) ,trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS`,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID`   FROM  trn_surveylive  left JOIN  `mst_surveyor` ON mst_surveyor.SURVEYORID=trn_surveylive.CREATED_BYID WHERE trn_surveylive.`SERVEY_DATE` between '" . $startDate . "' and '" . $currentTime . "'   AND trn_surveylive.`CREATED_BYRID` ='5' GROUP BY trn_surveylive.`CREATED_BYID` ORDER BY trn_surveylive.`SURVEY_ID` DESC";
$InActiveQ = mysqli_query($connection, $sqk);
 while ($data = mysqli_fetch_assoc($InActiveQ)) {
if (strtotime($InactiveTime) >= strtotime($data['max(trn_surveylive.`SERVEY_DATE`)'])) {
	$InActibe[] =$i;
	$i++;
 }}
    $total_Inactive = count($InActibe);

/* ======================== Prasent Surver Query================================================ */


if (isset($_REQUEST['data']) && $_REQUEST['data'] == 'Present') {

    $FetchDataQuery = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, max(trn_surveylive.`SERVEY_DATE`),trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS` ,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID` ,max(trn_surveylive.`SURVEY_ID`)
FROM trn_surveylive 
left JOIN `mst_surveyor` ON mst_surveyor.SURVEYORID = trn_surveylive.CREATED_BYID
WHERE trn_surveylive.`SERVEY_DATE` between '" . $startDate . "' and '" . $endDate . "' 
AND trn_surveylive.`CREATED_BYRID` = '5'
GROUP BY trn_surveylive.`CREATED_BYID`  
ORDER BY trn_surveylive.`SURVEY_ID` DESC";

//echo  $FetchDataQuery;die;

    // $FetchDataQuery = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, trn_surveylive.`SERVEY_DATE`,trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS` ,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID` ,trn_surveylive.`SURVEY_ID` FROM  trn_surveylive  left JOIN  `mst_surveyor` ON mst_surveyor.SURVEYORID=trn_surveylive.CREATED_BYID WHERE trn_surveylive.`SERVEY_DATE` between '".$startDate."' and '".$endDate."'  AND trn_surveylive.`CREATED_BYRID` ='5'  ORDER BY trn_surveylive.`SURVEY_ID` DESC";  


    /* ======================== InActive Surver Query================================================ */
} else if (isset($_REQUEST['data']) && $_REQUEST['data'] == 'Inactive') {
    $FetchDataQuery = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, max(trn_surveylive.`SERVEY_DATE`) ,trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS`,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID`   FROM  trn_surveylive  left JOIN  `mst_surveyor` ON mst_surveyor.SURVEYORID=trn_surveylive.CREATED_BYID WHERE trn_surveylive.`SERVEY_DATE` between '" . $startDate . "' and '" . $currentTime . "'   AND trn_surveylive.`CREATED_BYRID` ='5' GROUP BY trn_surveylive.`CREATED_BYID` ORDER BY trn_surveylive.`SURVEY_ID` DESC";
	
} else if (isset($_REQUEST['data']) && $_REQUEST['data'] == 'Absent') {

    $FetchDataQuery = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, max(trn_surveylive.`SERVEY_DATE`) ,trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS`,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID`   FROM  mst_surveyor LEFT JOIN  `trn_surveylive` ON mst_surveyor.SURVEYORID=trn_surveylive.CREATED_BYID GROUP BY mst_surveyor.`SURVEYORID`";
} else {

    $FetchDataQuery = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`,max(trn_surveylive.`SERVEY_DATE`) ,trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS` ,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID` FROM  trn_surveylive left JOIN  `mst_surveyor`  ON mst_surveyor.SURVEYORID =trn_surveylive.CREATED_BYID  WHERE trn_surveylive.`SERVEY_DATE` between '" . $startDate . "' and '" . $endDate . "'   AND trn_surveylive.`CREATED_BYRID` ='5' ORDER BY trn_surveylive.`SURVEY_ID` DESC";
}

$i = 0;
$records = array();
$TotalSevecount = mysqli_query($connection, $FetchDataQuery);
if (isset($_REQUEST['data']) && $_REQUEST['data'] == 'Absent') {
    while ($data = mysqli_fetch_assoc($TotalSevecount)) {

        $surveyCountQ = "select SURVEY_ID FROM  trn_surveylive where CREATED_BYID='" . $data['SURVEYORID'] . "' AND `SERVEY_DATE` between '" . $startDate . "' and '" . $endDate . "' AND  `CREATED_BYRID` ='5'";
        $LastsurveyCountQ = "select SERVEY_DATE FROM  trn_surveylive where CREATED_BYID='" . $data['SURVEYORID'] . "' AND  `CREATED_BYRID` ='5' order by SURVEY_ID DESC limit 0,1";
        //echo $LastsurveyCountQ;

        $LastSurveyCountQE = mysqli_query($connection, $LastsurveyCountQ);

        $LastSurveyData = mysqli_fetch_assoc($LastSurveyCountQE);
        $SurveyCountQE = mysqli_query($connection, $surveyCountQ);

        $CountSurvey = mysqli_num_rows($SurveyCountQE);

        $todayDate = date('Y-m-d');

        $UserLoginDate = date('Y-m-d', strtotime($data['max(trn_surveylive.`SERVEY_DATE`)']));

        if ($UserLoginDate != $todayDate) {

            $records[$i]['lastSurveyDate'] = $LastSurveyData['SERVEY_DATE'];
            $records[$i]['SurverId'] = $data['SURVEYORID'];
            $records[$i]['SurveyCount'] = $CountSurvey;
            $records[$i]['SURVEYOR_NAME'] = $data['SURVEYOR_NAME'];
            $records[$i]['SERVEY_DATE'] = $data['max(trn_surveylive.`SERVEY_DATE`)'];
            $records[$i]['Time'] = $data['max(trn_surveylive.`SERVEY_DATE`)'];
            $records[$i]['UserName'] = $data['FIRST_NAME'];
            $records[$i]['LAT'] = $data['LAT'];
            $records[$i]['LONGT'] = $data['LONGT'];
        }
        $i++;
    }
} else if(isset($_REQUEST['data']) && $_REQUEST['data'] == 'Inactive') {
 while ($data = mysqli_fetch_assoc($TotalSevecount)) {
if (strtotime($InactiveTime) >= strtotime($data['max(trn_surveylive.`SERVEY_DATE`)'])) { 
        $surveyCountQ = "select SURVEY_ID FROM  trn_surveylive where CREATED_BYID='" . $data['SURVEYORID'] . "' AND `SERVEY_DATE` between '" . $startDate . "' and '" . $endDate . "' AND `CREATED_BYRID` ='5'";
        $SurveyCountQE = mysqli_query($connection, $surveyCountQ);
        $CountSurvey = mysqli_num_rows($SurveyCountQE);
        $records[$i]['SurverId'] = $data['SURVEYORID'];
        $records[$i]['SurveyCount'] = $CountSurvey;
        $records[$i]['SURVEYOR_NAME'] = $data['SURVEYOR_NAME'];
        $records[$i]['SERVEY_DATE'] = $data['max(trn_surveylive.`SERVEY_DATE`)'];
        $records[$i]['Time'] = $data['max(trn_surveylive.`SERVEY_DATE`)'];
        $records[$i]['UserName'] = $data['FIRST_NAME'];
        $records[$i]['LAT'] = $data['LAT'];
        $records[$i]['LONGT'] = $data['LONGT'];
$i++;
 }       
    }																
																			
}else  {



    while ($data = mysqli_fetch_assoc($TotalSevecount)) {

        $surveyCountQ = "select SURVEY_ID FROM  trn_surveylive where CREATED_BYID='" . $data['SURVEYORID'] . "' AND `SERVEY_DATE` between '" . $startDate . "' and '" . $endDate . "' AND `CREATED_BYRID` ='5'";
        $SurveyCountQE = mysqli_query($connection, $surveyCountQ);
        $CountSurvey = mysqli_num_rows($SurveyCountQE);
        $records[$i]['SurverId'] = $data['SURVEYORID'];
        $records[$i]['SurveyCount'] = $CountSurvey;
        $records[$i]['SURVEYOR_NAME'] = $data['SURVEYOR_NAME'];
        $records[$i]['SERVEY_DATE'] = $data['max(trn_surveylive.`SERVEY_DATE`)'];
        $records[$i]['Time'] = $data['max(trn_surveylive.`SERVEY_DATE`)'];
        $records[$i]['UserName'] = $data['FIRST_NAME'];
        $records[$i]['LAT'] = $data['LAT'];
        $records[$i]['LONGT'] = $data['LONGT'];

        $i++;
    }
}


//echo "aaa";die;
//echo "<pre>";
//print_r($records);	die;
?>

<div id="content" class="app-content" role="main">
    <div class="app-content-body ">
        <div class="hbox hbox-auto-xs hbox-auto-sm" ng-init="app.settings.asideFolded = false;app.settings.asideDock = false;"> 

            <!-- main -->

            <div class="col">
                <div class="wrapper-md" ng-controller="FlotChartDemoCtrl"> 

                    <!-- stats -->

                    <div class="row">
                        <div class="col-md-5">
                            <div class="row row-sm text-center">
                                <div class="col-xs-6"> <a href="dashboard.php?data=Present">
                                        <div class="panel padder-v item">
                                            <div class="h1 text-info font-thin h1"><?php echo $total_present; ?></div>
                                            <span class="text-muted text-xs">Present</span>
                                            <div class="top text-right w-full"> 

<!-- <img src="img/emp-present.png" class="circle emp-detail"/>--> 

                                                <i class="fa fa-caret-down text-warning m-r-sm"></i> </div>
                                        </div>
                                    </a> </div>
                                <div class="col-xs-6"> <a href="dashboard.php?data=Absent" class="block panel padder-v bg-primary item"> <span class="text-white font-thin h1 block"><?php echo $total_absent; ?></span> <span class="text-muted text-xs">Absent</span> <span class="bottom text-right w-full"> 

<!--<img src="img/emp-absent.png" class="circle emp-detail"/>--> 

                                            <i class="fa fa-cloud-upload text-muted m-r-sm"></i> </span> </a> </div>
                                <div class="col-xs-6"> <a href="dashboard.php?data=Inactive" class="block panel padder-v bg-info item"> <span class="text-white font-thin h1 block"><?php echo $total_Inactive; ?></span> <span class="text-muted text-xs">In Active</span> <span class="top"> 

<!--<img src="img/emp-check-in.png" class="circle emp-detail"/>--> 

                                            <i class="fa fa-caret-up text-warning m-l-sm m-r-sm"></i> </span> </a> </div>
                                <div class="col-xs-6">
                                    <div class="panel padder-v item">
                                        <div class="font-thin h1">0</div>
                                        <span class="text-muted text-xs">Logged Out</span>
                                        <div class="bottom"> 

<!--<img src="img/emp-log-out.png" class="circle emp-detail"/>--> 

                                            <i class="fa fa-caret-up text-warning m-l-sm m-r-sm"></i> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                
                                <?php $ConverRateQt = "SELECT trn_surveylive.`CREATED_BYRID` FROM  trn_surveylive  WHERE trn_surveylive.`SERVEY_DATE` between '" . $startDate . "' and '" . $InactiveTime . "'  AND trn_surveylive.`CREATED_BYRID` ='5'";
$ConverRateQty = mysqli_query($connection, $ConverRateQt);
if ($ConverRateQty) {
    $total_ConverionVal = mysqli_num_rows($ConverRateQty);
} else {
    $total_ConverionVal = 0;
}


$BookRateQ = "SELECT trn_surveylive.`CREATED_BYRID` FROM  trn_surveylive  WHERE trn_surveylive.`SERVEY_DATE` between '" . $startDate . "' and '" . $InactiveTime . "'  AND trn_surveylive.`CREATED_BYRID` ='5' AND trn_surveylive.`SURVEY_TAG`=102";
$BookRateQty = mysqli_query($connection, $BookRateQ);
if ($BookRateQty) {
    $total_Book = mysqli_num_rows($BookRateQty);
} else {
    $total_Book = 0;
}
$ConverSR = round($total_ConverionVal/$total_Book,2);

?>
                                    <div class="panel padder-v item">
                                        <div ui-jq="easyPieChart" ui-options="{



                                             percent: <?php echo $ConverSR?>,



                                             lineWidth: 4,



                                             trackColor: '#e8eff0',



                                             barColor: '#7266ba',



                                             scaleColor: false,



                                             size: 115,



                                             rotate: 90,



                                             lineCap: 'butt'



                                             }" class="inline m-t easyPieChart" style="width: 115px; height: 115px; line-height: 115px;">
                                            <div> <span class="text-primary h4"><?php echo $ConverSR?>%</span> </div>
                                            <canvas width="115" height="115"></canvas>
                                        </div>
                                        <div class="text-muted font-bold text-xs m-t m-b">Conversion Rate</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="panel wrapper">
                                <label class="i-switch bg-warning pull-right" ng-init="showSpline = true">
                                    <input type="checkbox" ng-model="showSpline">
                                    <i></i> </label>
                                <h2 class="map-header">Real-Time Location</h2>

                                <!--<h4 class="font-thin m-t-none m-b text-muted">Google Map</h4>-->

                                <div class="card card-map">
                                    <style>



                                        .card-map .map {



                                            height: 373px;



                                            padding-top: 20px;



                                            width: 100%  }



                                        .card-map .map > div {



                                            height: 100%; }



                                    </style>
                                    <div class="map">
                                        <div id="map"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- / stats --> 

                    <!-- service -->

                    <div class="panel hbox hbox-auto-xs no-border">
                        <div class="col wrapper">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#survey-details">Survey Details</a></li>
                                        <li><a data-toggle="tab" href="#result">Survey Results</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="survey-details" class="tab-pane fade in active">
                                            <div class="x_panel">
                                                <div class="x_title clearfix">
                                                    <div class="pull-left">
                                                        <h4>Survey Details</h4>
                                                    </div>
                                                    <div class="pull-right">
                                                        <ul class="nav navbar-right panel_toolbox">
                                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                                                            <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                                                        </ul>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="x_content">
                                                    <table id="datatable-buttons" class="table table-bordered table-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:10px !important;">S.N.</th>
                                                                <th>Surveyor Name</th>
                                                                <th>Last Survey Done</th>
                                                                <th>Number Of Survery</th>
                                                                <th>Status</th>
                                                                <th>View All Route</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td></td>
                                                                <td><input type="text" placeholder="Search Server Name"></td>
                                                                <td><input type="text" placeholder="Search Last Survey Done"></td>
                                                                <td><input type="text" placeholder="Search Number Of Survery"></td>
                                                                <td><input type="text" placeholder="Search Status"></td>
                                                                <td></td>
                                                            </tr>
                                                            <?php
                                                            $uniqueId = array();

                                                            $j = 1;

                                                            for ($i = 0; $i < sizeof($records); $i++) {

                                                                if (!empty($records[$i]['SurverId'])) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $j; ?></td>
                                                                        <td><?php echo $records[$i]['SURVEYOR_NAME']; ?></td>
                                                                        <td><?php
																		
																		
																		
                                                                            if (isset($records[$i]['lastSurveyDate']) && !empty($records[$i]['lastSurveyDate'])) {
                                                                                echo $records[$i]['lastSurveyDate'];
                                                                            } else
                                                                            if (!empty($records[$i]['SERVEY_DATE'])) {
                                                                                echo $records[$i]['Time'];
                                                                            }
                                                                            ?></td>
                                                                        <td><?php echo $records[$i]['SurveyCount']; ?></td>
                                                                        <td><?php if (strtotime($InactiveTime) >= strtotime($records[$i]['SERVEY_DATE'])) { ?>
                                                                                <button class="btn m-b-xs w-xs btn-danger btn-rounded">In Active</button>
                                                                            <?php } else { ?>
                                                                                <button class="btn m-b-xs w-xs btn-success btn-rounded"> Active</button>
                                                                            <?php } ?></td>
                                                                        <td>
                                                                            <?php if(isset($records[$i]['SurverId']) && !empty($records[$i]['SurverId'])){ ?>
                                                                            <a class="btn btn-success" href="view-all-root.php?SurveryId=<?php echo $records[$i]['SurverId']; ?>" >View All Route</a>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $j++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <style>
                                        .quesansclass{ padding-left:10px; }
										.right_padding{padding-right:20px;}
                                        </style>
                                        <div id="result" class="tab-pane fade">
                                         <div class="x_panel">
                                                <div class="x_title clearfix">
                                                  <div class="x_content">
                                            <h3>Bihar Survey</h3>
                                            <div class="well m-t bg-light lt">
      										<div class="row quesansclass">
        									 <p><strong>Q 1. Aap kaun sa akhbaar kharid kaar padhte hai ?</strong></p>
                                             <?php
												 $sqlQ1 = "select PSID FROM trn_pre_survey where NP1=1";
												$Ans1 = mysqli_query($connection, $sqlQ1);
												$Ans1Qus1 = mysqli_num_rows($Ans1);
								
 												$sqlQ2 = "select PSID FROM trn_pre_survey where NP2=1";
												$Ans2 = mysqli_query($connection, $sqlQ2);
												$Ans2Qus1 = mysqli_num_rows($Ans2);
												
												$sqlQ3 = "select PSID FROM trn_pre_survey where NP3=1";
												$Ans3 = mysqli_query($connection, $sqlQ3);
												$Ans3Qus1 = mysqli_num_rows($Ans3);
												
												$sqlQ4 = "select PSID FROM trn_pre_survey where NP4=1";
												$Ans4 = mysqli_query($connection, $sqlQ4);
												$Ans4Qus1 = mysqli_num_rows($Ans4);
											 
										       $sqlQ5 = "select PSID FROM trn_pre_survey where NP5=1";
												$Ans5 = mysqli_query($connection, $sqlQ5);
												$Ans4Qus1 = mysqli_num_rows($Ans5);
											       
											   ?>
                                             
                                            <p class="right_padding">1) Prabhat Khabar (<?php echo  $Ans1Qus1;?>) <br />
                                            2) Hindustan (<?php echo  $Ans2Qus1;?>)  <br />
                                            3) Dainik Jagran (<?php echo  $Ans3Qus1;?>)  <br />
                                            4) Anya Hindi Akhbaar (<?php echo  $Ans4Qus1;?>)   <br />
                                            5) Angreji Akhbaar   (<?php echo  $Ans5Qus1;?>)   </p>
                                           <?php
												 $Q2Op1 = "select PSID FROM trn_pre_survey where Q2=1";
												$Ans1op1 = mysqli_query($connection, $Q2Op1);
												$Ans1op1 = mysqli_num_rows($Ans1op1);
								
 												$Q2op2= "select PSID FROM trn_pre_survey where Q2=2";
												$Ans2op2 = mysqli_query($connection, $Q2op2);
												$Ans2op2 = mysqli_num_rows($Ans2op2);
												
											     $Q2op3= "select PSID FROM trn_pre_survey where Q2=3";
												$Ans2op3 = mysqli_query($connection, $Q2op3);
												$Ans2op3 = mysqli_num_rows($Ans2op3);  
											   ?>
                                        
                                             <p><strong>Q 2. Aap jo akhbaar padhte hai  wah kitana nispaksh hai?</strong></p>
                                            <p>1) Thoda (<?php echo  $Ans1op1;?>)   <br />
                                             2)  Bahut (<?php echo  $Ans2op2;?>) <br /> 
                                             3) Bilkul Nahi  (<?php echo  $Ans2op3;?>)  </p>
                                           
                                             <?php
												 $Q3Op1 = "select PSID FROM trn_pre_survey where Q3_1=1";
												$Ans3op1 = mysqli_query($connection, $Q3Op1);
												$Ans3op1 = mysqli_num_rows($Ans3op1);
								
 												$Q3op2= "select PSID FROM trn_pre_survey where Q3_2=1";
												$Ans3op2 = mysqli_query($connection, $Q3op2);
												$Ans3op2 = mysqli_num_rows($Ans3op2);
												
											    $Q3op3= "select PSID FROM trn_pre_survey where Q3_3=1";
												$Ans3op3 = mysqli_query($connection, $Q3op3);
												$Ans3op3 = mysqli_num_rows($Ans3op3);  
												
												 $Q3op4= "select PSID FROM trn_pre_survey where Q3_4=1";
												$Ans3op4 = mysqli_query($connection, $Q3op4);
												$Ans3op4 = mysqli_num_rows($Ans3op4);  
											   ?>
                                               <p><strong>Q 3. Akhbar me kaun se mudde prathmikta se uthane chaheye ?(Koi do chuniye) </strong></p>
                                            <p>1) Bhrastachar   (<?php echo  $Ans3op1;?>) <br />
                                            2) Aapradh  (<?php echo  $Ans3op2;?>) <br />
                                            3) Rojgar/udayog  (<?php echo  $Ans3op3;?>)<br />
                                            4) Vikas   (<?php echo  $Ans3op4;?>)</p>
                                           
                                             <?php
												 $Q4Op1 = "select PSID FROM trn_pre_survey where Q4_1=1";
												$Ans4op1 = mysqli_query($connection, $Q4Op1);
												$Ans4op1 = mysqli_num_rows($Ans4op1);
								
 												$Q4op2= "select PSID FROM trn_pre_survey where Q4_2=1";
												$Ans4op2 = mysqli_query($connection, $Q4op2);
												$Ans4op2 = mysqli_num_rows($Ans4op2);
												
											    $Q4op3= "select PSID FROM trn_pre_survey where Q4_3=1";
												$Ans4op3 = mysqli_query($connection, $Q4op3);
												$Ans4op3 = mysqli_num_rows($Ans4op3);  
												
												 $Q4op4= "select PSID FROM trn_pre_survey where Q4_4=1";
												$Ans4op4 = mysqli_query($connection, $Q4op4);
												$Ans4op4 = mysqli_num_rows($Ans4op4);  
											   ?>
                                               <p><strong>Q 4.Appko vartaman me aapne bihar ki pahle jaruraat kaun si lagtee hai ?(Koi do chuniye)</strong></p>
                                            <p>1) Kanun Byabasta (<?php echo  $Ans4op1;?>) <br />
                                                2) swasth   (<?php echo  $Ans4op2;?>)<br />
                                                3) Siksha (<?php echo  $Ans4op3;?>)<br />
                                                4)  Rojgar/udayog    (<?php echo  $Ans4op4;?>)</p>
                                         
                                            
                                              <p><strong>Q 5.Akhbar me kaun si khabre aap prathmikta ke sath padha chahenge ? (Inko 1 se 3 krm me number degeye )</strong></p>
                                            <p>1) Bihar ke khabre <br />
                                                2) aapne jile /Sahar ki khabare <br />
                                                3) Rastriya / Anterrastiya khabre    </p>
                                          
                                             <?php
												 $Q4Op1 = "select PSID FROM trn_pre_survey where Q4_1=1";
												$Ans4op1 = mysqli_query($connection, $Q4Op1);
												$Ans4op1 = mysqli_num_rows($Ans4op1);
								
 												$Q4op2= "select PSID FROM trn_pre_survey where Q4_2=1";
												$Ans4op2 = mysqli_query($connection, $Q4op2);
												$Ans4op2 = mysqli_num_rows($Ans4op2);
											 
											   ?>
                                              <p><strong>Q 6.Kya aap Akhbaar me khabro ke alawa Rastriya / Anterrastiya  ester ke visesagjo ke vichar bhi padhna chahte hai ? </strong></p>
                                            <p>1) Haa  Ruchi hai  (<?php echo  $Ans4op4;?>)<br /> 
                                                 2) Nahi hai      (<?php echo  $Ans4op4;?>)</p>
                                            
                                              <p><strong>Q 7.Kya aapko lagta hai akhbar me nigative khabre jayda hote hai ? </strong></p>
                                            <p>1) Haa <br />
                                                2) Nahi    <br />
                                                3)  Kabhi Kabhi    </p> 
                                            
                                            
                                		 <p><strong> Q.8 . Kya aap Bihar ke alawa desh ke anya rajya kee bhee rochak va zaroori khabre parna chahte hai?</strong></p>

                                             <p>1) Haa   <br />
                                                2) Naa  </p>
                                          
                                           <p><strong> Q.9 Aapke anusaar akhabar kee keemat kya honi chahiye</strong></p>

                                             <p>1) 120 Masik   <br />
                                             2) 110 Masik  <br />
                                             3) 150 Masik <br />
                                             4) 130 Masik   </p>
                                        
                                            
                                        <p><strong>Q 10 . Aapke parivaar kee mahila sadaasya ke liye sawaal: 
                                         Akhbaar me nimn khabre kis prathmikta ke sath parnaa chahti hai (Koi do Chuniye)</strong></p>

                                             <p>1)Bihar kee Khabre  <br /> 
                                             2) Apne shahar kee khabre <br />
                                             3) samajik/pariwarik khabre  <br />
                                             4) Swasthya Sambandhi Khabre  <br />
                                             5) Bollywood/ Television kee Khabre      </p>
                                          </div>
                                        </div>
                                           
                                           
                                              </div>
                                              </div>
                                              </div>







                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- / main --> 

            <!-- right col -->

            <div class="col w-md bg-white-only b-l bg-auto no-border-xs">
                <?php
                $RecentBookingQ = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, trn_surveylive.`SERVEY_DATE`,trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS` ,trn_surveylive.`SOCITY` ,trn_surveylive.`CITY`,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID` ,trn_surveylive.`SURVEY_ID` FROM  trn_surveylive  left JOIN  `mst_surveyor` ON mst_surveyor.SURVEYORID=trn_surveylive.CREATED_BYID WHERE trn_surveylive.`CREATED_BYRID` ='5' ORDER BY trn_surveylive.`SURVEY_ID` DESC";
                ?>
                <div class="padder-md"> 

                    <!-- streamline --><br>
                    <div class="m-b text-md">Recent Survey</div>
                    <div class="streamline b-l m-b">
                        <?php
                        $RecentBookingQu = mysqli_query($connection, $RecentBookingQ);
                        while ($RecentBookingData = mysqli_fetch_assoc($RecentBookingQu)) {
                            ?>
                            <div class="sl-item">
                                <div class="m-l">
                                    <div class="text-muted"><?php echo $RecentBookingData['SERVEY_DATE'] ?></div>
                                    <p><?php echo $RecentBookingData['FIRST_NAME'] ?>, <?php echo $RecentBookingData['SOCITY'] . ',' . $RecentBookingData['CITY'] ?>.</p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- / right col --> 

            </div>
        </div>
    </div>
</div>

<!-- /content -->

<?php include('footer.php'); ?>
<?php
$MapQuery = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`,  max(trn_surveylive.`SERVEY_DATE`),trn_surveylive.`ADDRESS` ,trn_surveylive.`LATI`,trn_surveylive.`LONGI`  FROM  trn_surveylive  left JOIN  `mst_surveyor` ON mst_surveyor.SURVEYORID=trn_surveylive.CREATED_BYID
	                     WHERE trn_surveylive.`SERVEY_DATE` between '" . $startDate . "' and '" . $endDate . "'   AND trn_surveylive.`CREATED_BYRID` ='5'
                         GROUP BY trn_surveylive.`CREATED_BYID`
                         ORDER BY trn_surveylive.`CREATED_BYID` DESC";
$currentTime = date('Y-m-d H:i:s');
$InactiveTime = date("Y-m-d H:i:s", strtotime('-30 minutes', strtotime($currentTime)));
$j = 0;
$recordsMap = array();
$MapData12 = mysqli_query($connection, $MapQuery);
while ($MapData = mysqli_fetch_assoc($MapData12)) {
    if (strtotime($MapData['max(trn_surveylive.`SERVEY_DATE`)']) >= strtotime($InactiveTime)) {
        $recordsMap[$j]['SURVEYOR_NAME'] = $MapData['SURVEYOR_NAME'];
        $recordsMap[$j]['SERVEY_DATE'] = $MapData[' max(trn_surveylive.`SERVEY_DATE`)'];
        $recordsMap[$j]['Address'] = $MapData['ADDRESS'];
        $recordsMap[$j]['Time'] = date('H:i', strtotime($MapData['max(trn_surveylive.`SERVEY_DATE`)']));
        $recordsMap[$j]['color'] = 'green';
        $recordsMap[$j]['LAT'] = $MapData['LATI'];
        $recordsMap[$j]['LONGT'] = $MapData['LONGI'];
    } else {

        $recordsMap[$j]['SURVEYOR_NAME'] = $MapData['SURVEYOR_NAME'];
        $recordsMap[$j]['SERVEY_DATE'] = $MapData['max(trn_surveylive.`SERVEY_DATE`)'];
        $recordsMap[$j]['Time'] = date('H:i', strtotime($MapData['max(trn_surveylive.`SERVEY_DATE`)']));
        $recordsMap[$j]['Address'] = $MapData['ADDRESS'];
        $recordsMap[$j]['color'] = 'red';
        $recordsMap[$j]['LAT'] = $MapData['LATI'];
        $recordsMap[$j]['LONGT'] = $MapData['LONGI'];
    }

    $j++;
};
//echo "<pre>";
//print_r( $recordsMap);
?>

<!--  Google Maps Plugin   --> 

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCajg2gehjcPV6DEmPRoKOtoXo4y4QOdY0&angular.noop=initMap"></script> 
<script type="text/javascript">



var locations = [
<?php foreach ($recordsMap as $MapVal) {
	 if(!empty($MapVal['LAT']) && $MapVal['LAT']!='0.0' && !empty($MapVal['LONGT']) && $MapVal['LONGT']!='0.0'){?>
    ['<?php echo $MapVal['SURVEYOR_NAME'] ?>' ,'<?php echo $MapVal['LAT'] ?>', '<?php echo $MapVal['LONGT'] ?>','<?php echo $MapVal['SERVEY_DATE'] ?>'],
<?php }} ?>
];


 var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 5,
      center: new google.maps.LatLng(20.5937, 78.9629),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }


  


</script> 
