<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script>  

<?php /*?><script src="<?=base_url();?>media/js/plugins/forms/pages/jquery.formatCurrency-1.4.0.min.js"></script> <?php */?>
 
<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end date range -->

<script  type="text/javascript">
var activity_type = "casino_issues";
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
				<li><a href="#">Casino</a><span class="divider">/</span></li>
				<li class="active">Search Issues</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-dice"></i> Casino</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-stack-list"></i>
							</div>
							<h4>Search Issues</h4>
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                        
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            
                            <!-- more search -->
                            <div class="row-fluid"> 
                            
                            	<div class="span2" > 
                                 
                                </div>
                                
                                <!-- datepicker -->
                                <div class="span10" > 
                                
                                	<div id="reportrange" class="pull-right btn btn-primary pull-right" > 
                                        <i class="icon18 i-calendar"></i>
                                        <?php /*?><span><?php echo date("F j, Y", strtotime('-30 day')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b> <?php */?>
                                        <span>
                                            <?php
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
                                    <!-- end datepicker -->  
                                    
                                    <!-- Call Search -->
                                    <?php /*?><div class="btn-group pull-right call-search" style="margin-right: 10px;" >
                                    
                                        <button class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
                                        <i class="icon16 i-call-outgoing"></i>
                                        Call Search
                                        <span class="caret"></span>
                                        </button>
                                        
                                        <div class="dropdown-menu  opensleft daterangepicker" > 
                                            <!-- advance-search menu--> 
                                            <div class="advance-search"  >   
                                            
                                                <div class="control-group">
                                                    <div class="span6" >
                                                        <label class="control-label" for="s_calloutcome">Outcome</label>
                                                        <div class="controls controls-row"  >
                                                            <select class="select2 myselect" name="s_calloutcome" id="s_calloutcome" >
                                                                <optgroup label="" >    
                                                                    <option value="" >- Select Outcome -</option> 
                                                                    <?php
                                                                    foreach($outcomes as $row=>$outcome) {
                                                                    ?>
                                                                    <option value="<?=$outcome->outcome_id?>" result-id="<?=$outcome->result_id?>" result-name="<?=$outcome->result_name?>" ><?=$outcome->outcome_name?></option>
                                                                    <?php	
                                                                    }//end foreach
                                                                    ?> 
                                                                </optgroup> 
                                                            </select>  
                                                        </div>    
                                                    </div>
                                                    
                                                    <div class="span6" > 
                                                        <label class="control-label" for="s_result">Result</label>
                                                        <div class="controls controls-row"  >
                                                            <input type="text" name="s_callresultname" id="s_callresultname" class="span12 disabled" value="" disabled="disabled" style="min-height: 36px !important; text-align: left;" >  
                                                            <input type="hidden" class="input-medium " name="s_callresult" id="s_callresult"   >
                                                        </div>    
                                                    </div>
                                                    
                                                </div>
                                                <!-- End .control-group --> 
                                                
                                                <div class="control-group" style="padding-bottom: 20px;" >
                                                	<label class="radio-inline" >
                                                        <input type="checkbox" value="1" name="s_callsendsms" id="s_callsendsms" /> Send SMS 
                                                    </label> 
                                                    
                                                    <label class="radio-inline" >
                                                        <input type="checkbox" value="1" name="s_callsendemail" id="s_callsendemail" /> Send Email
                                                    </label>  
                                                </div>
                                                <!-- End .control-group --> 
                                                
                                                <div class="control-group">
                                                	<label class="radio-inline" >
                                                        <input type="radio" value="account_active" name="s_callproblem" /> Account Active
                                                    </label> 
                                                    
                                                    <label class="radio-inline" >
                                                        <input type="radio" value="number_invalid" name="s_callproblem" /> Number Invalid
                                                    </label> 
                                                    
                                                    <label class="radio-inline" >
                                                        <input type="radio" value="account_frozen" name="s_callproblem" /> Account Frozen
                                                    </label> 
                                                    
                                                    <label class="radio-inline" >
                                                        <a href="#" class="uncheck_scallproblem" >uncheck</a>
                                                    </label> 
                                                </div>
                                                <!-- End .control-group --> 
                                                    
                                            </div>
                                            <!-- end advance-search menu-->
                                        </div>
                                        
                                     </div> <?php */?>
                                     <!-- end Call Search --> 
                                     
                                     <!-- Advance Search -->
                                     <div class="btn-group call-search pull-right margin-right-10">
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon16 i-cogs"></i>
                                        Advance Search
                                        <span class="caret"></span>
                                        </button>
                                        
                                        <div class="dropdown-menu  opensleft daterangepicker" >  
                                        
                                            <!-- advance-search menu--> 
                                            <div class="advance-search" style="min-width: 400px; " >   
                                            	 
                                                <div class="control-group">
                                                    <label class="radio-inline act-danger" >
                                                        Important <input type="checkbox" value="1" name="s_important" id="s_important" />  
                                                    </label> 
                                                    
                                                    <label class="radio-inline act-danger" >
                                                        Complaint <input type="checkbox" value="1" name="s_iscomplaint" id="s_iscomplaint" />  
                                                    </label>
                                                    
                                                    <label class="radio-inline act-danger" >
                                                        Upload PM <input type="checkbox" value="1" name="s_uploadpm" id="s_uploadpm" />  
                                                    </label>
                                                </div>
                                                <!-- End .control-group --> 
                                                 
                                            </div>
                                            <!-- end advance-search menu--> 
                                            
                                        </div>
                                        
                                     </div> 
                                     <!-- end Advance Search -->
                                    
                                </div>
                                <!-- end span9 -->  
                                
                            </div>
                            <!-- end more search -->
                             
                            <input type="hidden" value="" name="s_page" id="s_page"  /> 
                            <input type="hidden"  style="text-align: center;" name="s_fromdate" id="s_fromdate" value="<?=$s_fromdate;?>" >
                            <input type="hidden"  style="text-align: center;" name="s_todate" id="s_todate" value="<?=$s_todate;?>" >
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable">
							<thead>
                                <tr>  
                                    <th class="center" width="9%" >
                                        Currency
                                    </th>  
                                    
                                    <th class="center" width="9%" >
                                        Username
                                    </th>
                                    
                                    <th class="center" width="10%" >
                                        Source
                                    </th>
                                    
                                    <th class="center" width="10%" >
                                       Casino Product	
                                    </th>
                                     
                                    <th class="center" >
                                       Category Name
                                    </th>
                                    
                                    <th class="center" >
                                       Date Updated
                                    </th>
                                     
                                    <th class="center" width="12%" >
                                        Status
                                    </th> 
                                    
                                    <th class="center" width="9%" >
                                        Assignee
                                    </th>
                                    
                                    <th width="9%" class="center" >
                                        Action 
                                    </th>
                                     
                                </tr> 
 
							</thead>
							
                            <tbody id="ActivityList" class="dynamic-list" > 
                            	<tr id="SearchRow"  >  
                                    <td class="center" >
                                         <select name="s_currency" class="select2 select_filter" >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                            	<?php
												foreach($currencies as $row=>$currency) {
												?>
                                                <option value="<?=$currency->CurrencyID?>" ><?=$currency->Abbreviation?></option>
                                                <?php	
												}//end foreach
												?> 
                                                
                                            </optgroup>  
                                        </select> 
                                    </td>  
                                    
                                    <td class="center" >
                                       <input class="text_filter" name="s_username" type="text" rel="1" value="">
                                    </td>
                                    
                                    <td class="center" >
                                       <div class="span12" >	   
                                           <select name="s_source" class="select2 select_filter"  >
                                                <optgroup label="" >    
                                                    <option value="" >- All -</option>
                                                    <?php
                                                    foreach($sources as $row=>$source) {
                                                    ?>
                                                    <option value="<?=$source->SourceID?>" ><?=$source->Source?></option>
                                                    <?php	
                                                    }//end foreach
                                                    ?> 
                                                </optgroup>  
                                            </select>
                                       </div>
                                    </td>
                                    
                                    <td class="center" >
                                    	<select name="s_casinoproduct" class="select2 select_filter"  >
                                            <optgroup label="" >    
                                                <option value="" >- All -</option>
                                                <?php
                                                foreach($sub_products as $row=>$sub_product) {
                                                ?>
                                                <option value="<?=$sub_product->SubID?>" ><?=$sub_product->Name?></option>
                                                <?php	
                                                }//end foreach
                                                ?> 
                                            </optgroup>  
                                        </select>
                                    </td> 
                                    
                                    <td class="center" >
                                        <select name="s_issuecategory" class="select2 select_filter"  >
                                            <optgroup label="" >    
                                                <option value="" >- All -</option>
                                                <?php
                                                foreach($issue_categories as $row=>$issue_category) {
                                                ?>
                                                <option value="<?=$issue_category->CategoryID?>" ><?=$issue_category->Name?></option>
                                                <?php	
                                                }//end foreach
                                                ?> 
                                            </optgroup>  
                                        </select>
                                    </td>
                                    
                                    <td class="center" >
                                        
                                    </td>
                                    
                                    <td class="center" >
                                         <select name="s_status" class="select2 select_filter"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                                <option value="0" >New</option>
                                            	<?php
												foreach($status_list as $row=>$status) {
												?>
                                                <option value="<?=$status->StatusID?>"  <?=($status->Status != 1)?'class="act-danger"':''; ?> ><?=ucwords($status->Name)?></option>
                                                <?php	
												}//end foreach
												?> 
                                                
                                            </optgroup>  
                                        </select>   
                                    </td> 
                                    
                                    <td width="9%" class="center" >
                                       <!--<span class="filter_column filter_text">
                                       	    <input class="search_init text_filter" name="s_agent" type="text" rel="1" value="">
                                       </span>--> 
                                       <select name="s_assignee" class="select2" style="width: 100px !important;"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option> 
                                            	<?php
												foreach($utypes as $row=>$utype) {
												?>
                                                <option value="<?=$utype->GroupID?>" ><?=ucwords($utype->Name)?></option>
                                                <?php	
												}//end foreach
												?> 
                                                
                                            </optgroup>  
                                        </select> 
                                    </td>
                                    
                                    <td class="center" >
                                    	<button class="btn btn-primary btn_search " type="button"> 
                                            Search
                                        </button>       
                                    </td>
                                     
                                </tr>
                            	<?php /*?><tr>
                                    <td colspan="11" style="text-align: center;" ><img src="<?=base_url()?>media/images/loader.gif" /><br />Loading data from Server <br /></td>
                                </tr><?php */?>
                                
                            </tbody> 
                            
							<tfoot>
                                <tr> 
                                    <th class="center" colspan="9" >
                                    
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
                            	<!--<a data-toggle="modal" href="#example" class="btn btn-primary btn-large"> - See more at: http://www.w3resource.com/twitter-bootstrap/modals-tutorial.php#sthash.JrP7FH9r.dpuf-->
                            	<?php /*?><a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="casino_form" >
                                    <i class="icon16  i-stack-plus"></i>
                                    Add Activity
                                </a> <?php */?> 
                                
                                <?php if(allow_export_data()){ ?>
                                <!-- export button -->
                                <div class="btn-group dropup rfloat"> 
                                    <a href="#CommonModal" title="export results" alt="export results" class="btn btn_export tip hide"  id="ExportBtn"  data-toggle="modal" >
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
<div class="modal fade" id="ActivityStatusModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-dice"></i>Casino Issue Update Status</h4>
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
<div class="modal fade" id="ActivityDetailsModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-dice"></i>Casino Issue Details </h4>
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

function getSearchActivities(){ 
	 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>search_casino_issues/getSearchActivities",  
		beforeSend:function(){   
			//show loading 
			searchLoading("show");   
		},
		success:function(newdata){   
			//alert(JSON.stringify(newdata));
			searchLoading("hide");
			 
			$("#ActivityList").find("tr.activity_row").remove();
			$("#ActivityList").append(newdata.activities);
			
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
				getSearchActivities(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});  
			
			//edit_activity
			$('.activity_row .edit_activity').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var activity_id = $(this).attr('activity-id');  
				tabContent($(this), "<?=base_url()?>casino/popupManageActivity/"+activity_id, "casino_form");  
				$("#TabContainer").find(".nav-tabs li, .nav-tabs li a").addClass("disabled");  
			});
		 	
			//download attachment
			$(".activity_row .download_attachment").click(function(){
				var activity_id = $(this).attr("activity-id");  
				//if(activity_id)downloadAttachment(activity_id, "deposit_withdrawal");
				if(activity_id)window.location.href = "<?=base_url()?>casino/downloadAttachment/"+activity_id+"/"+activity_type;
			});  
			
			//change status
			$('.activity_row .change_status').click(function() {
				var activity_id = $(this).attr('activity-id');  
				if(activity_id)loadAjaxContent("<?=base_url()?>casino/popupManageStatusActivity/"+activity_id, $("#ActivityStatusModal").find(".ajax_content"));   
			});
			
			//view details
			$('.activity_row .view_activity').click(function() {
				var activity_id = $(this).attr('activity-id');  
				var default_tab = $(this).attr('target'); 
				if(activity_id)loadAjaxContent("<?=base_url()?>casino/viewActivityDetails/"+activity_id+"/1", $("#ActivityDetailsModal").find(".ajax_content"), default_tab);
			});
			 
		}
			
	}); //end ajax
}

function exportSearchActivities(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>search_casino_issues/exportSearchActivities",
		cache: false,  
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
		  format: 'YYYY/MM/DD H:mm:ss ' 
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
		 //changePromotions("<?=base_url()?>casino/getPromotionsList", '', $(this).val(), $("select[name=s_promotion]").val(), $("select[name=s_promotion]"));
	}); 
	
	 
	//getSearchActivities();
	$(".btn_search").click(function(){   
		$("input[name=s_page]").val("");
		getSearchActivities(); 
	}); 
	
	$('#search_form select').select2({placeholder: "Select"}); 
	
	//clicking add activity button 
	$('.btn_addactivity').click(function(e) { 
		clearActivityTab(); 	 
		$("html, body").animate({ scrollTop: 0 }, "slow"); 
		var target_form = $(this).attr("target-form"); 
		$("#TabContainer").find('li [marker="'+target_form+'"]').trigger("click");   
	});
	 
	$('#ActivityModal, #ActivityStatusModal, #ActivityDetailsModal').modal({ 
		show: false, 
		keyboard: true
	});
	
	$('#ActivityModal, #ActivityStatusModal, #ActivityDetailsModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();   
		  if(is_change == 1)getSearchActivities(); 
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
		exportSearchActivities();
	});
	 
});  

</script>
 
