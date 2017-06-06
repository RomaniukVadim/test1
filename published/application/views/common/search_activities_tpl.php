<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
 
<!-- Tables plugins -->
<!--<script src="<?=base_url();?>media/js/plugins/tables/datatables/jquery.dataTables.min.js"></script> 
<script src="<?=base_url();?>media/js/plugins/tables/datatables/jquery.dataTables.columnFilter.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/pages/jquery.formatCurrency-1.4.0.min.js"></script>--> 
 
<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end date range -->

<script  type="text/javascript">
var activity_type = ""; 
var is_change = 0; 
</script>

 
<div class="main">
	
    <?=$sidebar_view;?>
    
	<section id="content" >
	<div class="wrapper" >
		
        <div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Search</a><span class="divider">/</span></li>
				<li class="active">Activities</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-search-2"></i> Search</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-stack-list"></i>
							</div>
							<h4>Search Results</h4>
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                        
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off" >
                            <input type="hidden" name="t_search" id="t_search" placeholder="Search here ..." class="search-query" value="<?=$skeywords?>" >
                            <!-- more search -->
                            <div class="row-fluid"> 
                            	
                                <!-- span 2 -->
                            	<div class="span2" >
                                	  
                                	<a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="bank_form" >
                                        <i class="icon16  i-stack-plus"></i>
                                        Add Activity
                                    </a> 
                                              
                                </div>
                                <!-- span 2 -->
                                
                                <!-- span10 -->
                                <div class="span10" > 
                                    
                                    <!-- datepicker -->
                                	<div id="reportrange" class="pull-right btn btn-primary" > 
                                        <i class="icon18 i-calendar"></i>
                                        <?php /*?><span><?php echo date("F j, Y", strtotime('-30 day')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b> <?php */?>
                                        <span class="" >
                                            <?php
                                            $s_fromdate = $sdata['s_fromdate'];
											$s_todate = $sdata['s_todate'];    
                                            if($s_fromdate && $s_todate)
                                             {
                                                $s_fromdate = urldecode(urlencode($s_fromdate));
                                                $s_todate = urldecode(urlencode($s_todate)); 
                                                echo date("F j, Y h:i A", strtotime($s_fromdate)).' - '.date("F j, Y h:i A ", strtotime($s_todate."23:59:59"));                                                $s_fromdate = date("Y-m-d H:i:00", strtotime($s_fromdate));
                                                $s_todate = date("Y-m-d 23:59:59", strtotime($s_todate));
                                             }
                                            else
                                             {  
												//$s_fromdate = date("Y-m-d 00:00:01", strtotime('-1 month' . date("Y-m-d 00:00:01")));
                                                //$s_todate = date('Y-m-d 23:59:59');   
												echo date("F d, Y 12:00 A", strtotime($this->common->start_date)).' - '.date("F d, Y 11:59 A"); 
                                             }
                                            ?> 
                                        </span> 
                                        <!--<b class="caret"></b>  -->
                                        <i class="icon19 i-arrow-down-2"></i>
                                    </div>
                                    <!-- end datepicker --> 
                                    
                                    <!-- Advance Search -->
                                    <div class="pull-right btn-group call-search margin-right-10" >
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon16 i-cogs"></i>
                                            Advance Search
                                            <span class="caret"></span>
                                        </button>
                                        
                                        <div class="dropdown-menu  opensleft daterangepicker" >  
                                        
                                            <!-- advance-search menu--> 
                                            <div class="advance-search" style="min-width: 420px; " >   
                                                 
                                                <div class="control-group" style="padding-bottom: 10px !important;" >
                                                    <label class="radio-inline act-danger" >
                                                        Important <input type="checkbox" value="1" name="s_important" id="s_important" />  
                                                    </label> 
                                                    
                                                    <label class="radio-inline act-danger" >
                                                        Complaint <input type="checkbox" value="1" name="s_iscomplaint" id="s_iscomplaint" />  
                                                    </label>
                                                </div>
                                                <!-- End .control-group -->  
                                                
                                                <?php
												if(admin_access())  
												 {  
												?>
                                                <div class="control-group">  
                                                    <label class="control-label" for="s_issue">Search from Date</label>
                                                    <div class="controls controls-row"  >
                                                        
                                                        <?php /*?><label class="radio-inline" >
                                                           &nbsp; &nbsp;   Uploaded <input type="radio" value="uploaded" name="s_dateindex"  <?=($sdata[s_dateindex]=='uploaded')?"checked='checked'":""?> />                             </label>   <?php */?>
                                                        
                                                        <label class="radio-inline"  >
                                                           &nbsp; &nbsp; Added <input type="radio" value="added" name="s_dateindex"  <?=($sdata[s_dateindex]=='added'|| ($sdata[s_dateindex]=='' && $date_index=="SearchAllAddedKey"))?"checked='checked'":""?> />                                 
                                                        </label>
                                                        
                                                        <label class="radio-inline">
                                                            Updated <input type="radio" value="updated" name="s_dateindex"  <?=($sdata[s_dateindex]=='updated' || ($sdata[s_dateindex]=='' && $date_index=="SearchAllUpdatedKey") )?"checked='checked'":""?> />                                 
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
                                <!-- end span10 -->
                                 
                                
                            </div>
                            <!-- end more search -->   
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
                                    
                                    <th class="center" width="10%" >
                                        Activity 
                                    </th>  
                                    
                                    <th class="center"  >
                                        Concern
                                    </th>  
                                    
                                    <th class="center" width="9%" >
                                        Username
                                    </th> 
                                    
                                    <th class="center" width="10%" >
                                        Transaction ID
                                    </th>
                                    
                                    <th class="center" width="12%" >
                                       Amount
                                    </th>
                                    
                                    <th class="center" width="10%" >
                                        Status
                                    </th> 
                                    
                                    <th class="center" width="10%" >
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
                                    	<select name="s_activity" class="select2" >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option> 
                                                <option value="banks" >Banks</option> 
                                                <option value="promotions" >Promotions</option> 
                                                <option value="casino" >Casino</option> 
                                                <option value="accounts" >Accounts</option> 
                                                <option value="suggestions" >Suggestions</option>  
                                                <option value="access" >Access</option> 
                                            </optgroup>  
                                        </select> 
                                    </td>
                                    
                                    <td class="center" >
                                       <input class="text_filter" name="s_concern" type="text" rel="1" value="">
                                    </td> 
                                      
                                    <td class="center" >
                                    	<input class="text_filter" name="s_username" type="text" rel="1" value="">    
                                    </td>
                                     
                                    <td width="9%" class="center" >
                                       <!--<span class="filter_column filter_text">
                                       	    <input class="search_init text_filter" name="s_agent" type="text" rel="1" value="">
                                       </span>-->
                                    </td>
                                    
                                    <td width="9%" class="center" >
                                       <!--<span class="filter_column filter_text">
                                       	    <input class="search_init text_filter" name="s_agent" type="text" rel="1" value="">
                                       </span>-->
                                    </td>
                                    
                                    <td class="center" >
                                         <select name="s_status" class="select2" style="width: 100px !important;"  >
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
                                                <option value="<?=$utype->GroupID?>" <?=(($utype->GroupID==$sdata[s_assignee]) )?"selected='selected'":"";?> ><?=ucwords($utype->Name)?></option>
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
                                    <th class="left" colspan="9" > </th>  
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
                                	<div class="dataTables_paginate paging_bootstrap pagination" id="ActivityPagination" >
                                    	<?=$pagination?>
                                    </div> 
                                </div>
                            </div>
                            <!-- end pagination -->
                            
							<div class="form-actions"> 
                            	<a href="#ActivityModal" class="btn btn_addactivity"  data-toggle="modal" target-form="promotions_form" >
                                    <i class="icon16  i-stack-plus"></i>
                                    Add Activity
                                </a>
                                 
                                <?php if(allow_export_data()){ ?>
                                <!-- export button -->
                                <div class="btn-group dropup rfloat"> 
                                    <a href="#CommonModal" title="export results" alt="export results" class="btn btn_export tip"   data-toggle="modal" >
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

<!-- ACTIVITY DETAILS MODAL -->
<div class="modal fade" id="ActivityDetailsModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  tabindex="-1" >
	
	<div class="modal-dialog" >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-office"></i>Deposit/Withdrawal Activity Details</h4>
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
var modalobj = {
				 "banks" : { "icon":"i-office", "title":"Deposit/Withdrawal Activity Details", "type":"deposit_withdrawal", "form":"bank_form" }, 
				 "promotions" : { "icon":"i-star-2", "title":"Promotional Activity Details", "type":"promotion", "form":"promotions_form"  }, 
				 "casino" : { "icon":"i-dice", "title":"Casino Issue Details", "type":"casino_issues", "form":"casino_form"  }, 
				 "accounts" : { "icon":"i-vcard", "title":"Account Related Issue Details", "type":"account_issues", "form":"account_form"  }, 
				 "suggestions" : { "icon":"i-pencil-5", "title":"Suggestions/Self Exclusion Details", "type":"suggestions_complaints", "form":"suggestions_form"  }, 
				 "access" : { "icon":"i-earth", "title":"Website/Mobile Access Issue Details", "type":"website_mobile", "form":"access_form"  } 
			   }; 
				   
function getSearch(){ 

	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>search/getSearch",  
		beforeSend:function(){    
			//show loading 
			searchLoading("show");  
		},
		success:function(newdata){  
			searchLoading("hide");
			//alert(JSON.stringify(newdata));
			//$("#ActivityList").find("tr.activity_row").remove();
			$("#ActivityList").append(newdata.activities);
			
			//for pagination 
			$("#dataTable_info").html(newdata.pagination_string);
			$("#ActivityPagination").html(newdata.pagination); 
			 
			if(newdata.records > 0)$(".btn_export").show();
			
			$("#ActivityPagination li").each(function(index) { 
				if(!$(this).hasClass("active") && $(this).find("a").length > 0)
				 { 
					 $(this).find("a").addClass("pagination_link"); 
					 $(this).find("a").attr("page-num", $(this).find("a").attr("href").replace('/','')); 
				 } 
				$(this).find("a").removeAttr("href");
			});
 			
			$(".pagination_link").click(function(){ 
				$("input[name=s_page]").val($(this).attr("page-num")); 
				getSearch(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});  
			
			//edit_activity
			$('.activity_row .edit_activity').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var activity_id = $(this).attr('activity-id');  
				var activity = $(this).attr('activity');  
				if(activity_id && activity)tabContent($(this), "<?=base_url()?>"+activity+"/popupManageActivity/"+activity_id, modalobj[activity].form);  
				$("#TabContainer").find(".nav-tabs li, .nav-tabs li a").addClass("disabled");  
			});
		 	
			//download attachment
			/*$(".activity_row .download_attachment").click(function(){
				var activity_id = $(this).attr("activity-id");  
				//if(activity_id)downloadAttachment(activity_id, "deposit_withdrawal");
				if(activity_id)window.location.href = "<?=base_url()?>search/downloadAttachment/"+activity_id+"/deposit_withdrawal";
			}); */ 
			
			//change status
			/*$('.activity_row .change_status').click(function() {
				var activity_id = $(this).attr('activity-id');  
				if(activity_id)loadAjaxContent("<?=base_url()?>search/popupManageStatusActivity/"+activity_id, $("#ActivityStatusModal").find(".ajax_content"));   
			});*/
			
			//view details
			$('.activity_row .view_activity').click(function() {
				var activity_id = $(this).attr('activity-id');  
				var activity = $(this).attr('activity');      
				
				if(activity_id && activity)
				{ 
					activity_type = modalobj[activity].type;  
					$("#ActivityDetailsModal").find(".modal-title").html("<i class=\"icon20 "+modalobj[activity].icon+"\"></i>"+modalobj[activity].title); 
					loadAjaxContent("<?=base_url()?>"+activity+"/viewActivityDetails/"+activity_id+"/1", $("#ActivityDetailsModal").find(".ajax_content"), "CsaContentDetails");     
				}
			});
			 
		}
			
	}); //end ajax
}


function exportActivities(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>search/exportActivities",  
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
	 
	//method change 
	$("#search_form select[name=s_methodtype]").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		 //$("#MethodTd").append('<div class="td_loading" ></div>'); 
		 changeActivityMethods("<?=base_url()?>search/getActivityMethods", $(this).val(), $("select[name=s_method]").val(), $("select[name=s_method]"));
	});  
	  
	getSearch();
	$(".btn_search").click(function(){   
		$("input[name=s_page]").val("");
		getSearch(); 
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
		  if(is_change == 1)getSearch();
		  is_change = 0; //global 
	}); 	 
	
	$(".btn_export").click(function(){
		exportActivities();
	});
	 
}); 
</script>

  

