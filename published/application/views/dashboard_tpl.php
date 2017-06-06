<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.pie.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.resize.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.time.min.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/sparklines/jquery.sparkline.min.js"></script>
<!--<script src="<?=base_url();?>media/js/plugins/charts/flot/date.js"></script>--> <!-- Only for generating random data delete in production site-->
<script src="<?=base_url();?>media/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
<!--<script src="<?=base_url();?>media/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>-->
 
<style>
.category {
	margin-right: 0px !important; 
	font-weight: normal !important;
}
 
.priority {
    margin-left: 0px !important;
}
 
.weather .degree {
	font-size: 78px !important;	 
}

@media only screen and (max-width: 1280px) {
	.weather .degree {
		font-size: 68px !important;	 
	} 
}

</style>
<!-- main -->
<div class="main">
		
    <?=$sidebar_view?>
    
	<section id="content">
	<div class="wrapper">
		<div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Library</a><span class="divider">/</span></li>
				<li class="active">Data</li>
			</ul>
		</div>
		<div class="container-fluid">
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-dashboard"></i> Dashboard </h1>
			</div> 
            
            <!-- ACTIVITY STATISTIC -->
            <div class="row-fluid"> 
            	
                <!-- activity statistic filechart span8 -->
				<div class="span8"  >
					<div class="widget panel">  
                        <div class="widget-title">
							<div class="icon blue"  id="UpdateActivityStatisticBtn">
								<i class="icon20 i-stats"></i>
							</div>	
							<h4 id="ActivityStatisticLoader" >Activity Statistic</h4>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						
                        <div class="widget-content center"> 
                        	 
							 <div class="chart" style="width: 100%; height:250px; margin-top: 10px;" >
                                
                          	 </div><!-- End .overpay_chart --> 
                             
                             <div class="campaign-stats center"> 
                             
                                <div class="items">
                                    <div class="circle easyPieChart tip" data-percent="0" id="TotalActivityCircle" title="Total Activity" >
                                        <span class="counter" >0</span>
                                        <canvas height="80" width="80"></canvas>
                                    </div>
                                    <div class="txt">Total</div>
                                </div>
                                
                                <div class="items">
                                    <div class="circle easyPieChart tip" data-percent="0" id="TotalComplaintCircle" title="Total Complaint" >
                                        <span class="counter " >0</span>
                                        <canvas height="80" width="80"></canvas>
                                    </div>
                                    <div class="txt">Complaint</div>
                                </div> 
                                
                                <div class="items">
                                    <div class="circle-red easyPieChart red tip" data-percent="0" id="TotalImportantCircle" title="Total Important" >
                                        <span class="counter" >0</span>
                                        <canvas height="80" width="80"></canvas>
                                    </div>
                                    <div class="txt">Important</div>
                                </div> 
                                
                                <div class="items">
                                    <div class="circle easyPieChart tip" data-percent="0" id="TotalActivityClose" title="Total Close Activity" >
                                        <span class="counter" >0</span>
                                        <canvas height="80" width="80"></canvas>
                                    </div>
                                    <div class="txt">Close Ticket</div>
                                </div>
                                
                             </div>
                             
                             <div class="clearfix"></div>
                           
						</div>
						<!-- End .widget-content --> 
					</div>
					<!-- End .widget -->
                    
				</div>
				<!-- end activity statistic filechart span8 -->
                
                <!-- ASSIGNED ACTIVITIES -->
				<div class="span4"  >
					<div class="widget"> 
						<div class="widget-title">
							<div class="icon blue" >
								<i class="icon20 i-user-plus"></i>
							</div>	
							<h4 id="AssignedActivityLoader" >Assigned Activities</h4>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						
                        <div class="widget-content">  
                        
                       		<div class="weather">
                                <div class="center clearfix"> 
                                    <div class="pull-left online-icon">
                                        <div class="icon">
                                       		<i class="icon64  i-user-plus-2 orange"></i>
                                        </div>
                                        <span class="today">total</span>
                                    </div>
                                    <div class="pull-right figure">
                                    	<span class="degree blue" id="TotalAssignedActivities" >0</span>
                                    </div>
                                </div>
                                
                                <ul class="clearfix" id="AssignedActivityList">
                                	<?php
									//create link  
									//$data['s_fromdate'] = $date_from; 
									//$data['s_todate'] = $date_to; 
									$data['s_assignee'] = $this->session->userdata('mb_usertype'); 
									
									$data['s_dashboard'] = 1; 
									$data['s_dateindex'] = $date_index;     
									$params = encode_string(http_build_query($data, '', '&amp;')); 
									?>
                                    <li class="tip assigned-status-cnt" title="Bank" id="assigned_bank" act-pending="0" act-new="0" act-inprogress="0" >
                                    	<a href="<?=base_url("banks/activities/".$params)?>">
                                        <span class="day" >Bank</span>
                                        <span class="dayicon"><i class="icon24 i-office bank-color"></i></span>
                                        <span class="max">0</span>
                                        <!--<span class="min">0</span>--> 
                                        </a>
                                    </li>  
                                    <li class="tip assigned-status-cnt" title="Promotions" id="assigned_promotion"  act-pending="0" act-new="0" act-inprogress="0" > 
                                    	<a href="<?=base_url("promotions/activities/".$params)?>">
                                        <span class="day">Pro</span>
                                        <span class="dayicon"><i class="icon24 i-star-2 promotion-color"></i></span>
                                        <span class="max">0</span>
                                        <!--<span class="min">0</span>--> 
                                        </a>
                                    </li>  
                                    <li class="tip assigned-status-cnt" title="Casino"  id="assigned_casino_issues"  act-pending="0" act-new="0" act-inprogress="0" > 
                                    	<a href="<?=base_url("casino/activities/".$params)?>">
                                        <span class="day" >Cas</span>
                                        <span class="dayicon"><i class="icon24 i-dice casino_issues-color"></i></span>
                                        <span class="max">0</span>
                                        <!--<span class="min">0</span>--> 
                                        </a>
                                    </li>  
                                    <li class="tip assigned-status-cnt" title="Accounts" id="assigned_account_issues" act-pending="0" act-new="0" act-inprogress="0" >
                                    	<a href="<?=base_url("accounts/activities/".$params)?>">
                                        <span class="day" >Acc</span>
                                        <span class="dayicon"><i class="icon24 i-vcard account_issues-color"></i></span>
                                        <span class="max">0</span>
                                        <!--<span class="min">0</span>--> 
                                        </a>
                                    </li>  
                                    <li class="tip assigned-status-cnt" title="Suggestions" id="assigned_suggestions_complaints" act-pending="0" act-new="0" act-inprogress="0" > 
                                    	<a href="<?=base_url("suggestions/activities/".$params)?>">
                                        <span class="day" >Sug</span>
                                        <span class="dayicon"><i class="icon24 i-pencil-5 suggestions_complaints-color"></i></span>
                                        <span class="max">0</span>
                                        <!--<span class="min">0</span>--> 
                                        </a>
                                    </li>  
                                    <li class="tip assigned-status-cnt" title="Access" id="assigned_website_mobile" act-pending="0" act-new="0" act-inprogress="0" bar-color="" > 
                                    	<a href="<?=base_url("access/activities/".$params)?>" >
                                        <span class="day" >Acs</span>
                                        <span class="dayicon"><i class="icon24 i-key-2 website_mobile-color"></i></span>
                                        <span class="max">0</span>
                                        <!--<span class="min">0</span>--> 
                                        </a>
                                    </li>    
                                </ul>
                            </div>  
                            
                             
                            <!-- COUNT ACTIVITIES -->
                            <div id="CountStatus" class="status-count" >
                            	 
                                <div class="campaign-stats center"> 
                                     
                                    <div class="items">
                                        <div class="circle-status easyPieChart tip" data-percent="0" id="TotalNew" title="Total New" >
                                            <span class="counter" >0</span>
                                            <canvas height="80" width="80"></canvas>
                                        </div>
                                        <div class="txt">New</div>
                                    </div>
                                    
                                    <div class="items">
                                        <div class="circle-status easyPieChart tip" data-percent="0" id="TotalPending" title="Total Pending" >
                                            <span class="counter" >0</span>
                                            <canvas height="80" width="80"></canvas>
                                        </div>
                                        <div class="txt">Pending</div>
                                    </div>  
                                    
                                    <div class="items">
                                        <div class="circle-status easyPieChart tip" data-percent="0" id="TotalInProgress" title="Total In Progress" >
                                            <span class="counter " >0</span>
                                            <canvas height="80" width="80"></canvas>
                                        </div>
                                        <div class="txt">In Progress</div>
                                    </div> 
                                     
                                 </div>
                                 
                                 <div class="clearfix"></div>
                             
                             </div>
                             <!-- COUNT ACTIVITIES -->
                             
                             
						</div>
						<!-- End .widget-content --> 
                        
					</div>
					<!-- End .widget -->
				</div> 
                <script>
				var total_new = 0; 
				var total_pending = 0; 
				var total_inprogress = 0; 
				var total_status = 0; 
				function countAssignedActivity() {  
					total_new = 0; 
					total_pending = 0; 
					total_inprogress = 0; 
					total_status = 0; 
					
					$.ajax({ 
						data: "rand="+Math.random(),//$(this).serialize(),
						type:"POST",  
						dataType: "JSON",
						url: "<?=base_url();?>dashboard/countAssignedActivity",  
						beforeSend:function(){   
							 $("#AssignedActivityLoader .header_loader").remove();
							 $("#AssignedActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />');
						},
						success:function(newdata){   
							//console.log(JSON.stringify(newdata));    
							$("#AssignedActivityList").show(); 
							$("#AssignedActivityLoader .header_loader").remove(); 
							var total = 0;  
							$.each(newdata, function( index, value ) {  
								$("#assigned_"+index).find(".max").text(value.CountRecord);    
								$("#assigned_"+index).attr({
									"act-new": value.TotalNew, 
									"act-inprogress": value.TotalInProgress,
									"act-pending": value.TotalPending, 
									"bar-color": rgb2hex($("."+index + "-color").css("color"))
								}); 
								  
								total_new += parseInt(value.TotalNew); 
								total_pending += parseInt(value.TotalPending);  
								total_inprogress += parseInt(value.TotalInProgress);   
								
								
								if(value.CountRecord > 0){
									total += parseInt(value.CountRecord); 
									total_status += parseInt(value.CountRecord); 
								}
								
							});
							
							total = (total > 9)?total:'0'+total;
							$("#TotalAssignedActivities").html(total);   
							
							//set default count: total data-percent="100"  
							$("#CountStatus").find(".items #TotalNew .counter").text(total_new);  
							$('#TotalNew').data('easyPieChart').update(parseInt((total_new/total_status)*100));
							
							$("#CountStatus").find(".items #TotalPending .counter").text(total_pending);   
							$('#TotalPending').data('easyPieChart').update(parseInt((total_pending/total_status)*100) );
							
							$("#CountStatus").find(".items #TotalInProgress .counter").text(total_inprogress);  
							$('#TotalInProgress').data('easyPieChart').update(parseInt((total_inprogress/total_status)*100))   
							
							 
							
							//end set default
							 
							var process_actcount = setTimeout(function() {     
								countAssignedActivity(); 
							}, act_updateInterval); //act_updateInterval
						}
							
					}); //end ajax
				} 
				
				
				$(function(){   
					
					var def_barcolor = '#62aeef'; 
					if($("#AssignedActivityList").length > 0) {   
						countAssignedActivity(); 
					}
					
					$(".circle-status").easyPieChart({
						barColor: def_barcolor,
						borderColor: '#227dcb',
						trackColor: '#d7e8f6',
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: 20,
						size: 80,
						animate: 1500
					}); 
					 
					 
					$(".assigned-status-cnt").hover(
						function() {  
							$("#CountStatus").find(".items #TotalNew .counter").text($(this).attr("act-new")); 
							$("#CountStatus").find(".items #TotalInProgress .counter").text($(this).attr("act-inprogress"));  
							$("#CountStatus").find(".items #TotalPending .counter").text($(this).attr("act-pending"));  
							var bar_color =  $(this).attr("bar-color");   
						 	var total_stat_cnt = parseInt($(this).attr("act-new")) + parseInt($(this).attr("act-inprogress")) + parseInt($(this).attr("act-pending")); 
						  
							
							$('#TotalNew').data('easyPieChart').update(0);
							$('#TotalInProgress').data('easyPieChart').update(0);
							$('#TotalPending').data('easyPieChart').update(0);
							
							$("#TotalNew").data('easyPieChart').options['barColor'] = bar_color;   
							$("#TotalPending").data('easyPieChart').options['barColor'] = bar_color;     
							$("#TotalInProgress").data('easyPieChart').options['barColor'] = bar_color;   

							$('#TotalNew').data('easyPieChart').update(parseInt((parseInt($(this).attr("act-new"))/total_stat_cnt)*100));
							$('#TotalInProgress').data('easyPieChart').update(parseInt((parseInt($(this).attr("act-inprogress"))/total_stat_cnt)*100));
							$('#TotalPending').data('easyPieChart').update(parseInt((parseInt($(this).attr("act-pending"))/total_stat_cnt)*100)); 
							
						}, function() {  
							$("#CountStatus").find(".items #TotalNew .counter").text(total_new); 
							$("#CountStatus").find(".items #TotalInProgress .counter").text(total_inprogress);  
							$("#CountStatus").find(".items #TotalPending .counter").text(total_pending);  
							
							$("#TotalNew").data('easyPieChart').options['barColor'] = def_barcolor;  
							$("#TotalPending").data('easyPieChart').options['barColor'] = def_barcolor; 
							$("#TotalInProgress").data('easyPieChart').options['barColor'] = def_barcolor; 
							
							$('#TotalNew').data('easyPieChart').update(parseInt((parseInt(total_new)/total_status)*100));
							$('#TotalPending').data('easyPieChart').update(parseInt((parseInt(total_pending)/total_status)*100));  
							$('#TotalInProgress').data('easyPieChart').update(parseInt((parseInt(total_inprogress)/total_status)*100));
							
						} 
						
					);
					 
						 

				}); 
				</script>
				<!-- END ASSIGNED ACTIVITIES span4 --> 
                  
                 
			</div>  
            <!-- END 1st row-fluid -->
             
            
              
			<div class="row-fluid"> 
            	
                <!-- sop/policy category chart span8 -->
				<div class="span6 panel">
					<div class="widget"> 
						<div class="widget-title">
							<div class="icon blue">
								<i class="icon20 i-pie-5"  ></i>
							</div>	
							<h4 id="CountProcessActivityLoader" >On Process Activity</h4>
							<a href="#" class="minimize" ></a>
						</div> 
                        <!-- End .widget-title -->
						
                        <div class="widget-content center"> 
                        	 
							 <div class="vital-stats">
                                <ul id="ProcessActivityList">  
                                
                                    <li class="process_bank" >
                                        <a   class="tip" title="Bank" >
                                            <div class="item">
                                                <div class="icon bank" ><i class="icon20 i-office"></i></div>
                                                <span class="percent">0</span>
                                                <span class="txt">Bank</span>
                                            </div>
                                        </a>
                                    </li> 
                                    
                                    <li class="process_promotion">
                                        <a class="tip" title="Promotion" >
                                            <div class="item">
                                                <div class="icon promotion" ><i class="icon20 i-star-2"></i></div>
                                                <span class="percent">0</span>
                                                <span class="txt">Promotion</span>
                                            </div>
                                        </a>
                                    </li> 
                                    
                                    <li class="process_casino_issues">
                                        <a  class="tip" title="Casino" >
                                            <div class="item">
                                                <div class="icon casino_issues" ><i class="icon20 i-dice"></i></div>
                                                <span class="percent">0</span>
                                                <span class="txt">Casino</span>
                                            </div>
                                        </a>
                                    </li> 
                                    
                                    <li class="process_account_issues">
                                        <a  class="tip" title="Account" >
                                            <div class="item">
                                                <div class="icon account_issues" ><i class="icon20 i-vcard"></i></div>
                                                <span class="percent">0</span>
                                                <span class="txt">Account</span>
                                            </div>
                                        </a>
                                    </li> 
                                	
                                    <li class="process_suggestions_complaints">
                                        <a class="tip" title="Suggestions" >
                                            <div class="item">
                                                <div class="icon suggestions_complaints" ><i class="icon20 i-pencil-5"></i></div>
                                                <span class="percent">0</span>
                                                <span class="txt">Suggestions</span>
                                            </div>
                                        </a>
                                    </li> 
                                    
                                    <li class="process_website_mobile">
                                        <a class="tip" title="Access" >
                                            <div class="item">
                                                <div class="icon website_mobile" ><i class="icon20 i-key-2"></i></div>
                                                <span class="percent">0</span>
                                                <span class="txt">Access</span>
                                            </div>
                                        </a>
                                    </li> 
                                    
                                </ul>
                           </div><!-- End .vital-stats --> 
                           
						</div>
						<!-- End .widget-content --> 
                        
					</div>
					<!-- End .widget -->
				</div>
				<!-- sop/policy category chart span8 -->
                
                
                <!-- GROUP ASSIGNED -->
				<div class="span6"  >
					<div class="widget"> 
						<div class="widget-title">
							<div class="icon blue" >
								<i class="icon20 i-users-4"></i>
							</div>	
							<h4 id="GroupAssignedLoader" >Group Assigned</h4>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						
                        <div class="widget-content"> 
                        	<div class="chart-donut" style="width: 100%; height: 300px;"></div>
						</div>
						<!-- End .widget-content --> 
                        
					</div>
					<!-- End .widget -->
				</div> 
                <script>
				var pieColours = ['#62aeef', '#d8605f', '#72c380', '#d95006', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282', '#4088f4', '#0fec38', '#d9f732', '#f87710', '#64b108'];	
				var group_pie;  
				var data_pie = []; 
				
				var options = {
					series: {
						pie: { 
							show: true,
							innerRadius: 0.4,
							highlight: {
								opacity: 0.1
							},
							radius: 1,
							stroke: {
								width: 2
							}, 
							label : {
								threshold: 0 
							}, 
							startAngle: 2, 
							border: 30 //darken the main color with 30
						}					
					},
					legend:{
						show:true,
						labelFormatter: function(label, series) {   	
							
							//series is the series object for the label 
							return '<a href="' + series.dlink + '">' + label + " ("+series.data[0][1]+")" + '</a>';
						},
						margin: 5, 
						width: 20,
						padding: 1, 
						position: "nw"   //"ne" or "nw" or "se" or "sw" 
					},
					grid: {
						hoverable: true,
						clickable: true
					},
					tooltip: true, //activate tooltip
					tooltipOpts: {
						//content: "%s : %y.1"+"%"
						//content: "%s : %y.0"+"",
						content: function() {
							//series is the series object for the label    
							return "%s(%y.0) : %p.1%";
						},
						shifts: {
							x: -30,
							y: -50
						},
						defaultTheme: false
					}
				};  
				 
				function countGroupAssigned() { 
				  
					$.ajax({ 
						data: "rand="+Math.random(),//$(this).serialize(),
						type:"POST",  
						dataType: "JSON",
						url: "<?=base_url();?>dashboard/countGroupAssigned",  
						beforeSend:function(){   
							 $("#GroupAssignedLoader .header_loader").remove();
							 $("#GroupAssignedLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />');  
							 data_pie = []; //reset data_pie
						},
						success:function(newdata){     
						   //alert(JSON.stringify(newdata)); 
							$("#GroupAssignedLoader .header_loader").remove(); 
							var i = 0;   
							$.each(newdata, function( index, value ) {    
								//alert(JSON.stringify(value));
								data_pie.push({label: value.Name,  data: parseInt(value.TotalCount), color: pieColours[i], percent: 20, dlink: value.Link }); 
								i++; 
								if(i >= pieColours.length)i=0;    
							});   
							
							/*data_pie = [
								{ label: "USAx",  data: 38, color: pieColours[0]},
								{ label: "Brazilx",  data: 23, color: pieColours[1]},
								{ label: "Indiax",  data: 15, color: pieColours[2]},
								{ label: "Turkey",  data: 9, color: pieColours[3]},
								{ label: "France",  data: 7, color: pieColours[4]},
								{ label: "China",  data: 5, color: pieColours[5]},
								{ label: "Germany",  data: 3, color: pieColours[6]} 
							]; */ 
							
							$.plot($(".chart-donut"), data_pie, options);
							
							//update pie 
							//group_pie.setData(data_pie); 
							//group_pie.draw();  
							var group_actcount = setTimeout(function() {     
								countGroupAssigned(); 
							}, act_updateInterval); //act_updateInterval
						}
							
					}); //end ajax
				
				}
				
				
				$(function () {
					
					if($(".chart-donut").length) { 
						countGroupAssigned();
						group_pie = $.plot($(".chart-donut"), data_pie, options);
					}//End of .cart-donut
					
				});
					
				
				</script>
				<!-- END GROUP ASSIGNED -->
                
                
			</div>
			<!-- End 2nd .row-fluid --> 
            
            <div class="row-fluid">
            	<div class="span12"  >
					 
                    
                </div>
            </div> 
            
		</div>
		<!-- End .container-fluid -->
	</div>
	<!-- End .wrapper -->
	</section>
    
</div>
<!-- End .main --> 
<script>     
 
var act_data = [];
var act_alreadyFetched = {};  
var act_plot;   
var act_updateInterval = 180000;  
var act_color_arr = [<?=implode(',', array_map(function($object){return '"'.$object[color].'"';}, $activity_types));?>]; 
var act_list = [<?=implode(',', array_map(function($object){return '"'.$object.'"';}, array_keys($activity_types)));?>]; //this is not an error 
var act_ctr = 0; 
var act_total = 0; 
var act_total_important = 0; 
var act_total_complaint = 0;
var act_total_close = 0; 
 

function generateChart(container, sdata, options, chartColours) {     
	 
	//define chart colors ( you maybe add more colors if you want or flot will add it automatic )
	chartColours = (chartColours.length <= 0)?['#62aeef', '#d8605f', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282']:chartColours;  
	
	var chartMinDate = sdata[0].date_from; //first day
	var chartMaxDate = sdata[0].date_to;//last day
	
	var tickSize = [1, "day"];
	var tformat = "%d/%m/%y";
	
	//graph options
	var options = {
		grid: {
			show: true,
			aboveData: true,
			color: "#3f3f3f" ,
			labelMargin: 5,
			axisMargin: 0, 
			borderWidth: 0,
			borderColor:null,
			minBorderMargin: 5 ,
			clickable: true, 
			hoverable: true,
			autoHighlight: true,
			mouseActiveRadius: 100
		},
		series: {
			lines: {
				show: true,
				fill: false,
				lineWidth: 2,
				steps: false
				},
			points: {
				show:true,
				radius: 2.8,
				symbol: "circle",
				lineWidth: 2.5
			}
		},
		legend: { 
			position: "ne", 
			margin: [0,-25], 
			noColumns: 0,
			labelBoxBorderColor: null,
			labelFormatter: function(label, series) {
				// just add some space to labes    
				return label+'&nbsp;&nbsp;';
			},
			width: 40,
			height: 1
		},
		colors: chartColours,
		shadowSize:0,
		tooltip: true, //activate tooltip
		tooltipOpts: {
			content: "%s: %y.0",
			xDateFormat: "%m/%d/%y",
			shifts: {
				x: -30,
				y: -50
			},
			defaultTheme: false
		},
		yaxis: { min: 0 },
		xaxis: { 
			mode: "time",
			minTickSize: tickSize,
			timeformat: tformat,
			min: chartMinDate,
			max: chartMaxDate
		}
	};   
	//END CHART VARIABLES 
	act_plot = $.plot(container, sdata, options);  
	//setTimeout(updateChart, act_updateInterval); 
	 
	//updateChart(); 
	var repeat = setTimeout(function() {     
		updateChart();
	}, act_updateInterval); 
	 
	
}//end generateChart


function getActivityStatistic(type) {
	$.ajax({ 
		data: "rand="+Math.random()+"&type="+type,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>dashboard/getActivityStatistic",   
		beforeSend:function(){   
			 $("#ActivityStatisticLoader .header_loader").remove();
			 $("#ActivityStatisticLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />');
		},
		success:function(newdata){     
			//alert(JSON.stringify(newdata)); 
			var xdata = newdata.data;  
			act_total_complaint += newdata.count_complaint;   
			act_total_important += newdata.count_important;  
			act_total_close += newdata.count_close;  
			 
			$.each(xdata, function( index, value ) { 
				act_total = (value[1] > 0)?act_total+value[1]:act_total;
			});
			  
			// Extract the first coordinate pair; jQuery has parsed it, so 
			//var firstcoordinate = "(" + series.data[0][0] + ", " + series.data[0][1] + ")";
			//button.siblings("span").text("Fetched " + series.label + ", first point: " + firstcoordinate);
		 
			// Push the new data onto our existing data array 
			if (!act_alreadyFetched[newdata.label]) {
				act_alreadyFetched[newdata.label] = true;
				act_data.push(newdata);    
			} 
			
			if(act_ctr < act_list.length)
			 { 	
			 	 activityPieChart();   
				 getActivityStatistic(act_list[act_ctr]);  
				 //generateChart($(".chart"), act_data, '', act_color_arr );  
				 act_ctr++;  
			 }
			else
			 { 
				 $("#ActivityStatisticLoader .header_loader").remove();  
				 generateChart($(".chart"), act_data, '', act_color_arr );  
				 act_ctr = 0;     
			 }  
			//act_data = sortResults(act_data, 'label', true);  
		}
			
	}); //end ajax
}

function sortResults(obj, prop, asc) {
    obj = obj.sort(function(a, b) {
        if (asc) return (a[prop] > b[prop]) ? 1 : ((a[prop] < b[prop]) ? -1 : 0);
        else return (b[prop] > a[prop]) ? 1 : ((b[prop] < a[prop]) ? -1 : 0);
    }); 
	return obj; 
}

function updateChart() {     
	act_total = 0; 
	act_total_important = 0; 
	act_total_complaint = 0;
	act_total_close = 0;
	
	act_plot.setData(act_data);
	// since the axes don't change, we don't need to call 
	//plot.setupGrid()
	act_plot.draw(); 
	/*var repeat = setTimeout(function() {     
		UpdateChart();
	}, act_updateInterval); */
	
	//clear data
	act_data = [];
	act_alreadyFetched = {};
 	act_ctr = 0; 
	
	getActivityStatistic(act_list[act_ctr]); 
	act_ctr++; 
	 
}

//Setup campaign stats
var activityPieChart = function(container, options) { 
	   
	if(act_total > 0)
	 { 
		$('#TotalActivityCircle').data('easyPieChart').update(100);   
	 } 
	$("#TotalActivityCircle").find(".counter").text(act_total);  
 	
	//var percentage = (act_total_complaint > 0)?parseInt((act_total_complaint/act_total)*100):0;  
	//percentage = 0; 
	if(act_total_complaint > 0)
	 {   
		$('#TotalComplaintCircle').data('easyPieChart').update(100);   
	 } 
	$("#TotalComplaintCircle").find(".counter").text(act_total_complaint); 
	 
	//var percentage = (act_total_important > 0)?parseInt((act_total_important/act_total)*100):0;  
	if(act_total_important > 0)
	 { 
		$('#TotalImportantCircle').data('easyPieChart').update(100);   
	 } 
	$("#TotalImportantCircle").find(".counter").text(act_total_important); 
	
	var percentage = (act_total_close > 0)?parseInt((act_total_close/act_total)*100):0;  
	if(act_total_close > 0)
	 { 
		$('#TotalActivityClose').data('easyPieChart').update(percentage);   
	 } 
	$("#TotalActivityClose").find(".counter").text(percentage+"%");    
	//$("#TotalActivityClose").tooltip({content: "Awesome title!"});    
	
	$(".circle").easyPieChart({
		barColor: '#62aeef',
		borderColor: '#227dcb',
		trackColor: '#d7e8f6',
		scaleColor: false,
		lineCap: 'butt',
		lineWidth: 20,
		size: 80,
		animate: 5500
	}); 
	 
	$(".circle-red").easyPieChart({
		barColor: '#d8605f',
		trackColor: '#f6dbdb',
		scaleColor: false,
		lineCap: 'butt',
		lineWidth: 20,
		size: 80,
		animate: 1500
	}); 
	 
}
  
  
function countProcessActivity() {
	
	$.ajax({ 
		data: "rand="+Math.random(),//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>dashboard/countProcessActivity",  
		beforeSend:function(){   
			 $("#CountProcessActivityLoader .header_loader").remove();
			 $("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />');
		},
		success:function(newdata){     
			 
			$("#ProcessActivityList").show(); 
			$("#CountProcessActivityLoader .header_loader").remove(); 
			 
			$.each(newdata, function( index, value ) { 
				$(".process_"+index).find(".percent").text(value);
			});
			
			var process_actcount = setTimeout(function() {     
				countProcessActivity(); 
			}, act_updateInterval); 
		}
			
	}); //end ajax
}



  
$(function(){ 
	
	//dashboardNotice(4); 
	
	$("#UpdateActivityStatisticBtn").click(function(){      
		act_data = [];
		act_alreadyFetched = {}; 
		act_ctr = 0; 
		getActivityStatistic(act_list[act_ctr]); 
		act_ctr++;  
	});
	 
	//check if element exist and draw chart    
	if($(".chart").length && (act_list.length>0) ) {  
		getActivityStatistic(act_list[act_ctr]); 
		act_ctr++;      
		activityPieChart(); 
	}//End .chart if   


	
	if($("#ProcessActivityList").length > 0) {   
		countProcessActivity(); 
	}
	
	 
});  




//GET NOTICE.. 

<?php /*?>function dashboardNotice(limit) {
	var color = ['medium', 'high', 'normal', '', 'medium', 'high', 'normal', '']; 
	var color2 = ['', 'label-info', 'label-inverse', 'label-important', '', 'label-info', 'label-inverse', 'label-important']; 
	$.ajax({ 
		data: "rand="+Math.random()+"&limit="+limit,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>dashboard/getNotice",   
		beforeSend:function(){   
			 $(".username_loader").remove();
			 $("#NoticeLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="username_loader" />');
		},
		success:function(data){    
			$(".username_loader").remove(); 
			$.each(data, function(key,value){ 
				$("#NoticeList").find(".todo-list").append("<li class=\"task-item clearfix\"><span class=\"priority "+color[key]+" tip\" title=\""+value.Title+"\"><i class=\"icon12 i-circle-2\"></i></span><span class=\"task\">"+value.Title+"</span></li>"); 
			});
		}
			
	}); //end ajax
}
  <?php */?>
   
</script>