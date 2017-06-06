<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script>  

<script src="<?=base_url();?>media/js/plugins/forms/pages/jquery.formatCurrency-1.4.0.min.js"></script> 

<!-- datetimepicker -->
<?php /*?><link href="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet"/>  
<script src="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.js"></script> <?php */?>


<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end date range -->

<script  type="text/javascript">
var activity_type = "account_issues"; 
var is_change = 0; 
</script>

<div class="main">
	
    <?=$sidebar_view;?>
    
	<section id="content" >
	<div class="wrapper" >
		
        <div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Accounts</a><span class="divider">/</span></li>
				<li class="active">Issues</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-vcard"></i> Accounts</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-stack-list"></i>
							</div>
							<h4>Account Related Issues</h4>
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                         
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            
                            <!-- advance search -->
                            <div class="row-fluid"> 
                            
                            	<div class="span3" >
                                	
                                    <a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="account_form" >
                                        <i class="icon16  i-stack-plus"></i>
                                        Add Activity
                                    </a>  
                                     
                                </div>
                                
                                <!-- span9 -->
                                <div class="span9" >
                                	 	
                                	<button id="reportrange" class="pull-right btn btn-primary" > 
                                        <i class="icon18 i-calendar"></i>
                                        <?php /*?><span><?php echo date("F j, Y", strtotime('-30 day')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b> <?php */?>
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
                                    </button>
                                    <!-- end datepicker -->
                                    
                                    <!-- Advance Search -->
                                    <div class="btn-group call-search pull-right margin-right-10">
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon16 i-cogs"></i>
                                        Advance Search
                                        <span class="caret"></span>
                                        </button>
                                        
                                        <div class="dropdown-menu opensleft daterangepicker" >  
                                        
                                            <!-- advance-search menu--> 
                                            <div class="advance-search" style="min-width: 460px; " >   
                                            	 
                                                <div class="control-group">
                                                    <label class="control-label" for="act_idreceived">* ID Received</label> 
                                                    <div class="controls controls-row">  
                                                        <label class="radio-inline" >
                                                            <input type="radio" name="s_idreceived" value="1" > Yes
                                                        </label> 
                                                        
                                                        <label class="radio-inline">
                                                            <input type="radio" name="s_idreceived" value="0"  > No
                                                        </label>
                                                        
                                                        <label class="radio-inline">
                                                            <input type="radio" name="s_idreceived" value="" checked > <b>All</b>
                                                        </label>
                                                    </div>
                                                </div> 
                                                <!-- End .control-group -->
                                                
                                                
                                                <div class="control-group" style="padding-bottom: 10px !important;" >
                                                    <label class="radio-inline act-danger" >
                                                        Important <input type="checkbox" value="1" name="s_important" id="s_important" />  
                                                    </label> 
                                                    
                                                    <label class="radio-inline act-danger" >
                                                        Complaint <input type="checkbox" value="1" name="s_iscomplaint" id="s_iscomplaint" />  
                                                    </label>
                                                    
                                                    <label class="radio-inline act-danger" >
                                                        Updated No. <input type="checkbox" value="1" name="s_uploadpm" id="s_uploadpm" />  
                                                    </label>
                                                </div>
                                                <!-- End .control-group --> 
                                                
                                                <div class="control-group" style="padding-bottom: 10px;" > 
                                                	<label class="radio-inline act-danger"  >
                                                        More than <?=$this->common->days_warning-1?> days in process <input type="checkbox" value="1" name="s_warningdays" id="s_warningdays" />  
                                                    </label>   
                                                    
                                                    <label class="radio-inline"  >
                                                        Display Close Ticket <input type="checkbox" value="1" name="s_displayclose" id="s_displayclose" />  
                                                    </label>
                                                </div>
                                                <!-- End .control-group --> 
                                                
                                                <?php
												if(change_date_index())  
												 {   
												?>
                                                <div class="control-group">  
                                                    <label class="control-label" for="s_issue">Search from Date</label>
                                                    <div class="controls controls-row"  >
                                                        
                                                       <?php /*?> <label class="radio-inline" >
                                                           &nbsp; &nbsp;   Uploaded <input type="radio" value="uploaded" name="s_dateindex"  <?=($sdata[s_dateindex]=='uploaded')?"checked='checked'":""?> />                                 
                                                        </label> <?php */?>  
                                                        
                                                        <label class="radio-inline"  >
                                                            &nbsp; &nbsp;   Added <input type="radio" value="added" name="s_dateindex"  <?=($sdata[s_dateindex]=='added'|| ($sdata[s_dateindex]=='' && $date_index=="DateAddedInt"))?"checked='checked'":""?> />                                 
                                                        </label>
                                                        
                                                        <label class="radio-inline">
                                                            Updated <input type="radio" value="updated" name="s_dateindex"  <?=($sdata[s_dateindex]=='updated' || ($sdata[s_dateindex]=='' && $date_index=="DateUpdatedInt") )?"checked='checked'":""?> />                                 
                                                        </label>
                                                         
                                                    </div> 
                                                     
                                                </div>
                                                <!-- End .control-group --> 
                                                <?php
												 }
												?>
                                                  
                                            </div>
                                            <!-- end advance-search menu--> 
                                            
                                        </div>
                                        
                                    </div> 
                                    <!-- end Advance Search -->
                                    
                                </div>
                                <!-- end span9 --> 
                                
                            </div>
                            <!-- end advance search -->
                             
                            <input type="hidden" value="" name="s_page" id="s_page"  /> 
                            <input type="hidden"  style="text-align: center;" name="s_fromdate" id="s_fromdate" value="<?=$s_fromdate;?>" >
                            <input type="hidden"  style="text-align: center;" name="s_todate" id="s_todate" value="<?=$s_todate;?>" > 
                            <input type="hidden"  style="text-align: center;" name="s_dashboard" id="s_dashboard" value="<?=$sdata[s_dashboard];?>" >
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable">
							<thead>
                                <tr>   
                                    <th class="center" width="9%" >
                                        Currency
                                    </th>  
                                    
                                    <th class="center" width="11%" >
                                        Username
                                    </th>
                                    
                                    <th class="center" width="14%" >
                                        Source
                                    </th>
                                    
                                    <th class="center" width="17%" >
                                        Account Problem
                                    </th>
                                     
                                    <th class="center" width="14%" >
                                        Date Updated
                                    </th>
                                    
                                    <th class="center" width="12%" >
                                        Status
                                    </th> 
                                    
                                    <th class="center" width="9%" >
                                        Assignee
                                    </th>
                                    
                                    <th width="8%" class="center" >
                                        Action 
                                    </th>
                                     
                                </tr> 
 
							</thead>
							
                            <tbody id="ActivityList" class="dynamic-list" > 
                            	<tr id="SearchRow"  >  
                                    <td class="center" >
                                         <select name="s_currency" class="select2" >
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
                                       <select name="s_source" class="select2" >
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
                                    </td>
                                    
                                    <td class="center" >
                                    	<select name="s_accountproblem" class="select2"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                            	<?php
												foreach($problems as $row=>$problem) {
												?>
                                                <option value="<?=$problem->ProblemID?>" ><?=ucwords($problem->ProblemName)?></option>
                                                <?php	
												}//end foreach
												?> 
                                                
                                            </optgroup>  
                                        </select>        
                                    </td>
                                     
                                    <td class="center" >
                                        
                                    </td>
                                    
                                    <td class="center" >
                                         <select name="s_status" class="select2"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                                <option value="0" >New</option>
                                            	<?php
												foreach($status_list as $row=>$status) {
												?>
                                                <option value="<?=$status->StatusID?>" ><?=ucwords($status->Name)?></option>
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
                                    
                                    <td width="120" class="center" >
                                    	<button class="btn btn-primary btn_search " type="button"> 
                                            Search
                                        </button>       
                                    </td>
                                     
                                </tr>
                            	<?php /*?><tr>
                                    <td colspan="10" style="text-align: center;" ><img src="<?=base_url()?>media/images/loader.gif" /><br />Loading data from Server <br /></td>
                                </tr><?php */?>
                                
                            </tbody> 
                            
							<tfoot>
                                <tr> 
                                    <th class="center" colspan="8" >
                                    
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
                            	<a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="account_form" >
                                    <i class="icon16  i-stack-plus"></i>
                                    Add Activity
                                </a>  
                                
                                <?php if(allow_export_data()){ ?>
                                <!-- export button -->
                                <div class="btn-group dropup rfloat"> 
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
<div class="modal fade" id="ActivityStatusModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-vcard"></i>Account Related Issue Update Status</h4>
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
		  <h4 class="modal-title"  ><i class="icon20 i-vcard"></i>Account Related Issue Details </h4>
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

function getActivities(){ 
	 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>accounts/getActivities",  
		beforeSend:function(){   
			//show loading 
			searchLoading("show");
		},
		success:function(newdata){    
			searchLoading("hide");
			//alert(JSON.stringify(newdata));
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
				getActivities(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});   
			
			//for popover
			$("tr[data-toggle=popover]")
			  .popover({
			   	trigger: "hover"
			  })
			  .click(function(e) {
				e.preventDefault()
			});
			
			//edit_activity
			$('.activity_row .edit_activity').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var activity_id = $(this).attr('activity-id');   
				tabContent($(this), "<?=base_url()?>accounts/popupManageActivity/"+activity_id, "account_form");  
				$("#TabContainer").find(".nav-tabs li, .nav-tabs li a").addClass("disabled");  
			});
		 	
			//download attachment
			$(".activity_row .download_attachment").click(function(){
				var activity_id = $(this).attr("activity-id");  
				//if(activity_id)downloadAttachment(activity_id, "deposit_withdrawal");
				if(activity_id)window.location.href = "<?=base_url()?>accounts/downloadAttachment/"+activity_id+"/"+activity_type;
			});  
			
			//change status
			$('.activity_row .change_status').click(function() {
				var activity_id = $(this).attr('activity-id');  
				if(activity_id)loadAjaxContent("<?=base_url()?>accounts/popupManageStatusActivity/"+activity_id, $("#ActivityStatusModal").find(".ajax_content"));   
			});
			
			//view details
			$('.activity_row .view_activity').click(function() {
				var activity_id = $(this).attr('activity-id');  
				if(activity_id)loadAjaxContent("<?=base_url()?>accounts/viewActivityDetails/"+activity_id, $("#ActivityDetailsModal").find(".ajax_content"));   
			});
			 
		}
			
	}); //end ajax
}


function exportActivities(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>accounts/exportActivities",
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
 	
	//for popover
	$("tr[data-toggle=popover]")
	  .popover({
		trigger: "hover"
	  })
	  .click(function(e) {
		e.preventDefault()
	});
	
	$('#search_form .dropdown-menu .advance-search').click(function(e) {
		e.stopPropagation(); 
	});
	 
	 
	$('#search_form')[0].reset(); 
	$.uniform.update("input:checkbox[name=s_important]:checked");
	
	createRangeDatePicker($('#reportrange'), "<?=$this->common->start_date?>"); //create daterangepicker  
	 
	//method change 
	$("#search_form select[name=s_methodtype]").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		 //$("#MethodTd").append('<div class="td_loading" ></div>'); 
		 changeActivityMethods("<?=base_url()?>accounts/getActivityMethods", $(this).val(), $("select[name=s_method]").val(), $("select[name=s_method]"));
	});  
	  
	getActivities();
	$(".btn_search").click(function(){   
		$("input[name=s_page]").val("");
		getActivities(); 
	}); 
	 
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
		  if(is_change == 1)getActivities(); 
		  is_change = 0; //global
	});  
		   
	$(".btn_export").click(function(){
		exportActivities();
	}); 
	
	//MORE THAN SPECIFIC DAYS
	$("input:checkbox[name=s_warningdays]").click(function(){   
		warningDays($(this), "<?=$this->common->start_date?>", "<?=$this->common->days_warning?>");     
	});
	//END MORE THAN SPECIFIC DAYS
	
	$('#search_form select').select2({placeholder: "Select"});
	 
}); 
</script>

  

