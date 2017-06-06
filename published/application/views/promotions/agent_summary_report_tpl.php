<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script>  

<?php /*?><script src="<?=base_url();?>media/js/plugins/forms/pages/jquery.formatCurrency-1.4.0.min.js"></script> <?php */?>
 
<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end date range -->

<script  type="text/javascript">
var activity_type = "promotion";
var is_change = 0; 
</script>
 

<style>
#search_form .select2-choice {
	max-width: 180px; 
}
</style>
 
<div class="main">
	
    <?=$sidebar_view;?>
    
	<section id="content" >
	<div class="wrapper" >
		
        <div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Promotions</a><span class="divider">/</span></li>
				<li class="active">Agent Summary Report</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-star-2"></i> Promotions</h1>
			</div> 
            
			<div class="row-fluid" >
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-reading"></i>
							</div>
							<h4>Agent Summary Report</h4> 
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                        
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            
                            <!-- advance search -->
                            <div class="row-fluid">  
                            
                            	<div class="btn-group pull-right" style="margin-left: 10px;" >
                                	<button class="btn dropdown-toggle btn-primary btn_search" data-toggle="dropdown"> 
                                    	Search
                                    </button>
                                </div>
                                
                                <div id="reportrange" class="btn btn-primary pull-right" > 
                                    <i class="icon18 i-calendar"></i>
                                     
                                    <span>
                                        <?php 
										$s_fromdate = $sdata['s_fromdate'];
										$s_todate = $sdata['s_todate'];  
										if($s_fromdate && $s_todate)
										 { 
											$s_fromdate = urldecode(urlencode($s_fromdate));
											$s_todate = urldecode(urlencode($s_todate)); 
											echo date("F j, Y h:i A", strtotime($s_fromdate)).' - '.date("F j, Y h:i A ", strtotime($s_todate));
											$s_fromdate = date("Y-m-d H:i:s", strtotime($s_fromdate));
											$s_todate = date("Y-m-d H:i:s", strtotime($s_todate));  
										 }
										else
										 { 
											echo date("F d, Y 12:00 A", strtotime($this->common->start_date)).' - '.date("F d, Y 11:59 A"); 
											//$s_fromdate = date("Y-m-d 00:00:00");
											//$s_todate = date('Y-m-d 23:59:59');   
										 }
										?>
                                    </span> 
                                    <!--<b class="caret"></b>  -->
                                    <i class="icon19 i-arrow-down-2"></i> 
                                    
                                    
                                </div> 
                                
                            </div> 
                            <!-- end advance search --> 
                            <input type="hidden" value="" name="s_page" id="s_page"  /> 
                            <input type="hidden"  style="text-align: center;" name="s_fromdate" id="s_fromdate" value="<?=$s_fromdate;?>" >
                            <input type="hidden"  style="text-align: center;" name="s_todate" id="s_todate" value="<?=$s_todate;?>" > 
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable">
							<thead>
                                <tr> 
                                    <th class="center" width="12%" >
                                        Agent
                                    </th>  
                                    
                                    <th class="center" width="9%" >
                                        All
                                    </th>  
                                    
                                    <th class="center" >
                                        Reached Duration
                                    </th> 
                                    
                                    <?php
									//$blank_th = "";
									foreach($call_results as $row=>$call_result) {
									//$blank_th .= "<th class=\"center\" >";
									?>
                                    <th class="center" >
                                        <?=$call_result->result_name?>
                                    </th> 
                                    <?php		
									}//end foreach
									?>
                                    
                                </tr> 
 
							</thead>
							
                            <tbody id="ActivityList" class="dynamic-list" > 
                            	 
                                
                            </tbody> 
                            
							<tfoot id="ActivityListFooter">
                                <tr> 
                                    <th class="center" colspan="11" >
                                    
                                    </th> 
                                </tr>
							</tfoot>
							</table> 
                            </form> 
                           
                             <!-- pagination -->
                            <div class="row-fluid">
                            	<div class="span4">  
                                    <div id="dataTable_info" class="dataTables_info"  ><!--Showing 1 to 10 of 58 entries--></div>   
                                </div>
                            	
                                <div class="span8" > 
                                	<div class="dataTables_paginate paging_bootstrap pagination" id="ActivitiyPagination" >
                                    	<?=$pagination?>
                                    </div> 
                                </div>
                            </div>
                            <!-- end pagination -->
                            
							<div class="form-actions"> 
                             	 
                                <?php if(admin_access() || allow_agent_report() ){ ?>
                                <!-- export button -->
                                <div class="btn-group dropup rfloat"> 
                                    <!--<button class="btn dropdown-toggle i-file-excel btn_export" data-toggle="dropdown" >-->
                                    <!--<button class="btn dropdown-toggle i-file-excel btn_export" data-toggle="dropdown" >
                                    Export  
                                    </button>--> 
                                    <a href="#CommonModal" title="export results" alt="export results" class="btn btn_export tip"  id="ExportBtn"  data-toggle="modal" >
                                    	<i class="icon16 i-file-excel" ></i> Export Results
                                    </a>
                                </div>  
                                <!-- end export button -->
                                <?php }?>
                                
                            </div>
                           
						</div>
						<!-- End .widget-content -->
					</div>
					<!-- End .widget -->
				</div>
				<!-- End .span12 -->
			</div>
			<!-- End .row-fluid -->
		</div>
		<!-- End .container-fluid -->
	</div>
	<!-- End .wrapper -->
	</section>
    
</div>
<!-- End .main --> 

 
<style>
	.suggestions_input {
		width: 100% !important; 	
	}
</style> 

<!-- UPDATE STATUS MODAL -->
<div class="modal fade" id="ActivityStatusModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-star-2"></i>Promotional Activity Update Status</h4>
		</div>
		
		<!-- tab content --> 
		<div style="padding: 20px 20px 20px 20px; " class="ajax_content" > 
          
		</div> 
		<!-- end content -->
		 
		<?php /*?><div class="modal-footer" > 
		  <div id="SuggestionFormLoader" style="float: left; " ></div>
		  <button type="submit" class="btn btn-primary sugbtn" id="BtnSubmitSuggestion" >Submit</button>	
		  <button type="button" class="btn btn-default sugbtn" id="BtnCloseSuggestion" data-dismiss="modal" >Close</button>
		</div><?php */?>
		
	  </div><!-- /.modal-content -->
	
	</div><!-- /.modal-dialog --> 
     
</div>
<!-- END UPDATE STATUS MODAL -->

<!-- ACTIVITY DETAILS MODAL -->
<div class="modal fade" id="ActivityDetailsModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-star-2"></i>Promotional Activity Details </h4>
		</div>
		
		<!-- tab content -->
		<div style="padding: 20px 20px 20px 20px; " class="ajax_content" > 
          
		</div>
		<!-- end content -->
		 
		<?php /*?><div class="modal-footer" > 
		  <div id="SuggestionFormLoader" style="float: left; " ></div>
		  <button type="submit" class="btn btn-primary sugbtn" id="BtnSubmitSuggestion" >Submit</button>	
		  <button type="button" class="btn btn-default sugbtn" id="BtnCloseSuggestion" data-dismiss="modal" >Close</button>
		</div><?php */?>
		
	  </div><!-- /.modal-content -->
	
	</div><!-- /.modal-dialog --> 
     
</div>  
<!-- END ACTIVITY DETAILS MODAL --> 

<script>  
var xhr; 

function getCountCalls(){ 
	 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>agent_summary_report/getCountCalls",  
		beforeSend:function(){   
			//show loading 
			$("#ActivityList").find("tr.activity_row").remove();	
		 	searchLoading("show");   
			$("#ActivityListFooter").find("tr:first").html("<th colspan=\"11\" ></th>");
		},
		success:function(newdata){   
			//alert(JSON.stringify(newdata));
			searchLoading("hide");
			$("#ActivityLoader").remove(); 
			$("#ActivityList").find("tr.activity_row").remove();
			$("#ActivityList").append(newdata.reports);  
			$("#dataTable").find("tfoot").html("<tr>"+$("#ActivityList tr#AgentTotalCount").html()+"</tr>");
			$("#ActivityList tr#AgentTotalCount").remove();
 
			//for pagination 
			$("#dataTable_info").html(newdata.pagination_string);
			$("#ActivitiyPagination").html(newdata.pagination);
		 
			if(newdata.records > 0)$(".btn_export").show();
		 
			$("#ActivitiyPagination li").each(function(index) { 
				if(!$(this).hasClass("active") && $(this).find("a").length > 0)
				 { 
					 $(this).find("a").addClass("pagination_link"); 
					 $(this).find("a").attr("page-num", $(this).find("a").attr("href").replace('/','')); 
				 } 
				$(this).find("a").removeAttr("href");
			});
			
 			
			$(".pagination_link").click(function(){ 
				$("input[name=s_page]").val($(this).attr("page-num")); 
				getCountCalls(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});   
			 
		}
			
	}); //end ajax
}


function exportReports(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>agent_summary_report/exportReports",  
		beforeSend:function(){   
			//show loading  
		 	exportLoading("show");   	
			
		},
		success:function(newdata){   
			//alert(JSON.stringify(newdata));
			exportLoading("hide"); 
			if(newdata.success == 1)
			 {
				 window.location = "<?=base_url();?>download/"+newdata.download_link;
			 }
			else
			 {
				 
			 }
			  
		}
			
	}); //end ajax	
}
 
 
$(function() { 	 
 	
	$('#search_form .dropdown-menu .advance-search').click(function(e) {
		e.stopPropagation(); 
	});
	 
	 
	$('#search_form')[0].reset(); 
	$.uniform.update("input:checkbox[name=s_important]:checked");
	
	//Start of the system 2013-09-01 00:00:00 
	$('#reportrange').daterangepicker(
	 { 
		  ranges: { 
			 'Today': [ moment().hours(0).minutes(0).seconds(0),  moment().hours(23).minutes(59).seconds(59)],
			 'Yesterday': [moment().subtract('days', 1).hours(0).minutes(0).seconds(0), moment().subtract('days', 1).hours(23).minutes(59).seconds(59)], 
			 'Last 7 Days': [moment().subtract('days', 6).hours(0).minutes(0).seconds(0), moment().hours(23).minutes(59).seconds(59)],  
			 'Last 30 Days': [moment().subtract('days', 29).hours(0).minutes(0).seconds(0),  moment().hours(23).minutes(59).seconds(59)],  
			 'This Month': [moment().startOf('month').hours(0).minutes(0).seconds(0), moment().endOf('month').hours(23).minutes(59).seconds(59)],   
			 'Last Month': [moment().subtract('month', 1).startOf('month').hours(0).minutes(0).seconds(0), moment().subtract('month', 1).endOf('month').hours(23).minutes(59).seconds(59)],  
			 'From the beginning': ["<?=$this->common->start_date?>", moment().hours(23).minutes(59).seconds(59)] 
		  }, 
		  //startDate: "<?=$s_fromdate;?>",//moment(),
		  //endDate: "<?=$s_todate;?>",//moment(),
		  maxDate: moment().hours(23).minutes(59).seconds(59),
		  timePicker: true, 
		  timePickerIncrement: 1, //minutes default 30
		  selected_hour: 24,  
		  format: 'YYYY/MM/DD H:mm:ss', 
		  showDropdowns: true 
	 },
		function(start, end, label)  {   
			if(label == "From the beginning")
			 {	 
				 $('#reportrange span').html(start.format('MMMM DD, YYYY h:mm A') + ' - ' + moment().format('MMMM DD, YYYY 11:59 A'));  
				 $("#s_fromdate").val(""); 
				 $("#s_todate").val("");
			 }
			else
			 {  
				$('#reportrange span').html(start.format('MMMM DD, YYYY h:mm A') + ' - ' + end.format('MMMM DD, YYYY h:mm A'));
				$("#s_fromdate").val(start.format('YYYY-MM-DD HH:mm:ss')); 
				$("#s_todate").val(end.format('YYYY-MM-DD HH:mm:ss'));
			 }
		}
	);
	 
	//currency change 
	$("#search_form select[name=s_currency]").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		 //$("#MethodTd").append('<div class="td_loading" ></div>'); 
		 changePromotions("<?=base_url()?>promotions/getPromotionsList", '', $(this).val(), $("select[name=s_promotion]").val(), $("select[name=s_promotion]"));
	}); 
	
	 
	getCountCalls();
	$(".btn_search").click(function(){   
		$("input[name=s_page]").val("");
		getCountCalls(); 
	}); 
	
	$('#search_form select').select2({placeholder: "Select"}); 
	
	//clicking add activity button 
	$('.btn_addactivity').click(function(e) { 
		clearActivityTab(); 	 
		$("html, body").animate({ scrollTop: 0 }, "slow"); 
		var target_form = $(this).attr("target-form"); 
		$("#TabContainer").find('li [marker="'+target_form+'"]').trigger("click");   
	})
	
	$('#ActivityModal, #ActivityStatusModal, #ActivityDetailsModal').modal({ 
		show: false, 
		keyboard: false
	});
	
	$('#ActivityModal, #ActivityStatusModal, #ActivityDetailsModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();  
		  if(is_change == 1)getCountCalls(); 
		  is_change = 0; //global 
	}); 
	
	$("#s_calloutcome").change(function(){  
		var selected = $(this).find('option:selected');  
		$("#s_callresult").val(selected.attr("result-id"));  
		$("#s_callresultname").val(selected.attr("result-name"));  
	});  
	$("#s_calloutcome").trigger("change"); 
	
		 
	$(".uncheck_scallproblem").click(function(){   
		$('input:radio[name=s_callproblem]:checked').prop("checked", false); 
		$.uniform.update("input:radio[name=s_callproblem]");  
		//$.uniform.update("input[type=checkbox]"); 
	}); 
	
	$(".btn_export").click(function(){
		exportReports();
	});
	 
});  

</script>
 
