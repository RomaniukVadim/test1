<script src="<?=base_url();?>media/js/handlebars/handlebars-v3.0.3.js"></script>   
<script src="<?=base_url();?>media/js/handlebars/handlebars-helper-x.js"></script>  

<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script>  
 
<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end date range -->

<script  type="text/javascript">
var activity_type = "promotion";
var is_change = 0; 

var source; 
var template; 
var user;   

var source_ctr = 0; 
</script>
 

<style>
#search_form .select2-choice {
	max-width: 180px; 
} 

.search-form .controls {
	margin-left: 0px !important; 
	margin-bottom: 0px !important;  
	padding-bottom: 0px !important;	 
}

.select2-container { 
	margin-bottom: 0px !important;
}
 
#search_form input {
	text-align: left !important; 
} 

.source-col {
	display: none; 	
}

</style>

 
<div class="main">
	
    <?=$sidebar_view;?>
    
	<section id="content" >
	<div class="wrapper" >
		
        <div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Reports</a><span class="divider">/</span></li>
				<li class="active">CAL</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-stats"></i> Reports</h1>
			</div> 
            
			<div class="row-fluid"  >
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-phone-6"></i>
							</div>
							<h4>CAL Reports</h4> 
							<a href="#" class="minimize"></a>    
						</div>
						<!-- End .widget-title -->
                        
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            
                            <!-- search options -->
                            <div class="row-fluid"  > 
                            
                            	<div class="span3">
                                 	
                                    <div class="btn-group">  
                                        <button class="btn dropdown-toggle i-bars btn-show-selected" data-toggle="dropdown">
                                            <span class="btn-text" >Status Report</span> &nbsp
                                            <span class="icon16 caret" ></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu"> 
                                            <li><a href="#" report-type="status-col" class="btn-show-report" >Status Report</a></li> 
                                            <li><a href="#" report-type="source-col" class="btn-show-report" >Source Report</a></li> 
                                            <li><a href="#" report-type="custom-col" class="btn-show-report" >Others Report</a></li> 
                                        </ul>
                                    </div> 
                                           
                                </div>
                                <!-- end span 3 -->
                                
                                <div class="span9" >  
                                 	
                                	<div class="btn-group pull-right" style="margin-left: 10px;" >
                                        <button class="btn dropdown-toggle btn-primary btn_search" data-toggle="dropdown"> 
                                            Search
                                        </button>
                                    </div>
                                    
                                    <!-- datepicker --> 
                                	<div id="reportrange" class="pull-right btn btn-primary pull-right" > 
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
                                    </div>  
                                    <!-- end datepicker --> 
                                    
                                    <?php
									if(admin_access() || csd_supervisor_access())
									 {
									?>
                                    <!-- Advance Search -->
                                    <div class="pull-right btn-group call-search margin-right-10" >
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon16 i-cogs"></i>
                                            Advance Search
                                            <span class="caret"></span>
                                        </button>
                                        
                                        <div class="dropdown-menu  opensleft daterangepicker" >  
                                        
                                            <!-- advance-search menu--> 
                                            <div class="advance-search"  >    
                                                <div class="control-group"> 
                                                     <label class="control-label" for="s_usertypex">User Type</label>
                                                     <div class="controls controls-row"  >
                                                        <select class="span8" name="s_usertypex" id="s_usertypex" multiple >
                                                            <optgroup label="" >      
                                                                <?php
                                                                foreach($utypes as $row=>$utype) {
																	if(in_array($utype->GroupID, $this->csdsup_allow_types) || (count($this->csdsup_allow_types) <= 0) )
																	 {
                                                                ?>
                                                                <option value="<?=$utype->GroupID?>"  ><?=$utype->Name?></option>
                                                                <?php
																	 }
                                                                }//end foreach
                                                                ?> 
                                                            </optgroup>  
                                                        </select> 
                                                        <input type="hidden" value="" name="s_usertype" id="s_usertype" />
                                                     </div> 
                                                </div>
                                                <!-- End .control-group --> 
                                                 
                                                <div class="control-group">
                                                	 
                                                     <div class="span6" > 
                                                         <label class="control-label" for="s_agent">Agent</label>
                                                         <div class="controls controls-row"   >
                                                            <select class="select2 myselect" name="s_agent" id="s_agent"  >
                                                                <optgroup label="" >    
                                                                    <option value="" >- All Staff -</option> 
                                                                    <?php
                                                                    foreach($agents as $row=>$agent) {
                                                                    ?>
                                                                    <option value="<?=$agent->mb_no?>" <?=($agent->mb_no==$sdata['s_agentid'])?"selected='selected'":""?> ><?=$agent->mb_nick?></option>
                                                                    <?php	
                                                                    }//end foreach
                                                                    ?> 
                                                                </optgroup> 
                                                            </select>
                                                         </div>   
                                                     </div>
                                                     
                                                     <div class="span6" >
                                                     	<label class="control-label" for="s_currency">Currency</label>
                                                         <div class="controls controls-row"   >
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
                                                         </div> 
                                                     </div>
                                                      
                                                </div>
                                                <!-- End .control-group --> 
                                                 
                                            </div>
                                            <!-- end advance-search menu--> 
                                            
                                        </div>
                                        
                                    </div> 
                                    <!-- end Advance Search -->
                                    <?php
									 }
									?>
                                    
                                </div>
                                <!-- end span 9 -->   
                                
                                
                                
                            </div>
                            <!-- end search options -->
                            
                            <input type="hidden" value="" name="s_page" id="s_page"  /> 
                            <input type="hidden"  style="text-align: center;" name="s_fromdate" id="s_fromdate" value="<?=$s_fromdate;?>" >
                            <input type="hidden"  style="text-align: center;" name="s_todate" id="s_todate" value="<?=$s_todate;?>" > 
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="overflow: scroll;">
							<thead>
                                <tr> 
                                    <th class="center" width="12%" >
                                        Agent
                                    </th>   
                                       
                                    <?php
									//$blank_th = "";
									foreach($reports_status as $row=>$stat) {
									//$blank_th .= "<th class=\"center\" >";
									?>
                                    <th class="center status-col report-col" >
                                        <?=$stat[Label]?>
                                    </th> 
                                    <?php		
									}//end foreach
									?>
                                      
                                    <?php
									//$blank_th = "";
									foreach($sources as $row=>$source) {
									//$blank_th .= "<th class=\"center\" >";
									?>
                                    <th class="center source-col report-col" >
                                        <?=$source->Source?>
                                    </th> 
                                    <?php		
									}//end foreach
									?>
                                    
                                    <?php
									//$blank_th = "";
									foreach($customs as $row=>$custom) {
									//$blank_th .= "<th class=\"center\" >";
									?>
                                    <th class="center custom-col report-col" >
                                        <?=$custom[Label]?>
                                    </th> 
                                    <?php		
									}//end foreach
									?>
                                    
                                </tr> 
 
							</thead>
							
                            <tbody id="ActivityList" class="dynamic-list" > 
                            	 
                                
                            </tbody> 
                            
							<tfoot>
                                <tr> 
                                    <th class="center" colspan="<?=count($reports_status)+count($sources)+1?>" >
                                    
                                    </th> 
                                </tr>
							</tfoot>
							</table> 
                            <input type="hidden" value="" name="s_mbno" id="s_mbno"  />
                            <input type="hidden" value="" name="s_statusid" id="s_statusid"  />
                            <input type="hidden" value="" name="s_sourceid" id="s_sourceid"  />
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
                             	 
                                <?php if(allow_export_data()){ ?>
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

<script id="list-row" type="text/x-handlebars-template">
  {{#if status_data.length }}
  	{{#each status_data}}	  
	  <tr class="user-row" id="User{this.mb_no}" >
	  	 <td class="center" >{{this.mb_nick}}</td> 
		 {{#each ../report_status}}
		 <td class="center status-col report-col" >
		 	<a class="status-details btn-link tip" mb-no="{{../this.mb_no}}" status-id="{{this.StatusID}}" title="view details" >{{displayData ../this this.CountName}}</a>
		 </td>   
		 {{/each}} 
		 
		 {{#each ../report_sources}}
		 <td class="center source-col report-col" >
		 	<a class="source-details btn-link tip" mb-no="{{../this.mb_no}}" source-id="{{this.SourceID}}" title="view details" >
				{{displaySourceData ../../source_data this.SourceID}}</td>      
			</a>
		 {{/each}} 	
		 
		 {{#each ../report_customs}}
		 <td class="center custom-col report-col act-danger" >{{displayComplaintData ../../source_data this.CountName}}</td>  
		 {{/each}} 	 
		 {{setVariable "source_ctr"}}
	  </tr>  
	{{/each}}   
  {{else}} 
  	<tr class="user-row" >   
		<td colspan="20" class="center"  >No record found! </td>      
	 </tr>
  {{/if}}	
   
</script>






<script>  
var xhr; 
var class_a = "";  

function getCalStatusReports(){ 
	source_ctr = 0; 
	 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>cal/getCalStatusReports",  
		beforeSend:function(){   
			//show loading 
			$("#ActivityList").find("tr.user-row").remove();	
		 	searchLoading("show");  
		},
		success:function(newdata){   
			//console.log(JSON.stringify(newdata));
			searchLoading("hide");
			$("#ActivityLoader").remove(); 
			$("#ActivityList").find("tr.user-row").remove();
			//$("#ActivityList").append(newdata.users);
			//$("#dataTable").find("tfoot").html($("#ActivityList tr#AgentTotalCount").html());
			//$("#ActivityList tr#AgentTotalCount").remove();
 
 			
			
			$("#ActivityList").append(template(newdata));
			
 
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
				getCalStatusReports(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});    
			
			
			//set default content
			if(class_a)
			 {
				 $("[report-type='"+class_a+"']").trigger("click");
			 }
			else
			 {
				 $("[report-type='status-col']").trigger("click");
			 }
			
			//clicking status details
			$(".status-details").click(function(){
				var mb_no = $(this).attr("mb-no"); 
				var status_id = $(this).attr("status-id");    
				var params = "";
				if(mb_no && status_id)
				 { 
					$("#s_mbno").val(mb_no);  
					$("#s_statusid").val(status_id);   
					generateParams(base_url + "reports/cal/status-details/");   
					//alert(params); 
				 }
				 
			});
			
			//clicking source details
			$(".source-details").click(function(){
				var mb_no = $(this).attr("mb-no"); 
				var source_id = $(this).attr("source-id");    
				var params = "";
				 
				if(mb_no && source_id)
				 { 
					$("#s_mbno").val(mb_no);  
					$("#s_sourceid").val(source_id);   
					generateParams(base_url + "reports/cal/source-details/");   
					//alert(params); 
				 }
				 
			});   
			
		}
		  
	}); //end ajax 
	
	  
}


function exportReports(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>cal/exportCalReport",  
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
 

function changeAgentList(url, result, default_result, container, display){ 
 
	$.ajax({ 
		data: "rand="+Math.random()+"&result="+result,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		beforeSend:function(){    
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />'); 
			 /*if(result == "")
			  {	
			  	  container.html('<option value=""></option>');
			  	  container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }*/
		},
		success:function(newdata){        
		 	container.html('<option value=""></option>');
		  	if(newdata.length > 0)
			 {  
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {   
					selected = (default_result == value.mb_no)?'selected="selected"':'';
					new_string = '<option value="'+value.mb_no+'" '+selected+'>'+value.mb_nick+'</option>';  
					container.append(new_string);
				});
				
			 }
		    else
			 {  
				 container.attr('disabled', true); 
			 } 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			 
		}
			
	}); //end ajax
}

function generateParams(url){  
	
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>cal/generateParams",   
		cache: false,
		beforeSend:function(){   
			//show loading   
		},
		success:function(newdata){   
			//alert(JSON.stringify(newdata));
			//exportLoading("hide"); 
			if(newdata.params)
			 {
				 window.location = url + newdata.params;
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
	
	source   = $("#list-row").html();
	template = Handlebars.compile(source);
	 
	//display status data
	Handlebars.registerHelper("displayData", function(obj, field) {      
	  return obj[field]; 	  
	});
	 
	//display source data
	Handlebars.registerHelper("displaySourceData", function(obj, id) {     
	  return obj[source_ctr]["Source_"+id]; 	  
	});
	
	//display complaint data
	Handlebars.registerHelper("displayComplaintData", function(obj, field) {     
	  var pattern = /,/ig;
	  var value = obj[source_ctr][field];   
	  value = value.replace(pattern, ", "); 
	  return value; 	 	  
	});
	 
	Handlebars.registerHelper("setVariable", function() {   
	  source_ctr++; 	  
	});
	
	 
	$('#search_form')[0].reset(); 
	//$.uniform.update("input:checkbox[name=s_important]:checked");
	
	//Start of the system 2013-09-01 00:00:00 
	$('#reportrange').daterangepicker(
		 {
			  ranges: {
				 'Today': [ moment().hours(0).minutes(0).seconds(0),  moment().hours(23).minutes(59).seconds(59)], 
				 'Yesterday': [moment().subtract(1, 'days').hours(0).minutes(0).seconds(0), moment().subtract(1, 'days').hours(23).minutes(59).seconds(59)], 
				 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				 'This Month': [moment().startOf('month'), moment().endOf('month')],
				 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
			function(start, end, label) {
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
	   
	   
	$("#search_form select[name=s_usertypex]").change(function(){     
		var values_id = $.map($(this).find(":selected"), function(option) { 
					   return option.value; 
					});    		 
		$("#s_usertype").val(values_id);   
		changeAgentList("<?=base_url()?>cal/getAgentList", $("#s_usertype").val(), "", $("#s_agent"));   
	}); 
  	 
	getCalStatusReports();
	$(".btn_search").click(function(){    
		$("input[name=s_page]").val("");
		getCalStatusReports(); 
	}); 
	   
	
	$(".btn_export").click(function(){
		exportReports();
	});
	
	$(".btn-show-report").click(function(){  
		class_a = $(this).attr("report-type");  
		$(".btn-show-selected").find("span.btn-text").text($(this).text());   
		$(".report-col").hide();
		$("."+class_a).show();
	});
	
	 
	$('#search_form select').select2({placeholder: "Select"});  
	
	
		 
});  

</script>
 