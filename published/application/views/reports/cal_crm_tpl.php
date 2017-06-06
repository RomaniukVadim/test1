<script src="<?=base_url();?>media/js/handlebars/handlebars-v3.0.3.js"></script>
<script src="<?=base_url();?>media/js/handlebars/handlebars-helper-x.js"></script>

<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script>  
 
<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/moment.js"></script>
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

tr.total_info th {
	background: url("<?=base_url()?>media/images/patterns/furley_bg2.png") repeat scroll 0 0 rgba(0, 0, 0, 0) !important; 	
}

.result_name {
	font-weight: bold; 
	vertical-align: middle !important;   
	border-left: 0 !important; 
}

.table-bordered tbody tr td:first-child {
	 border-left: 1px solid #C9C9C9 !important;
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
				<li class="active">CAL CRM Calls</li>
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
								<i class="icon20 i-call-outgoing"></i>
							</div>
							<h4>CRM Calls Report</h4> 
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                        
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            
                            <!-- search options -->
                            <div class="row-fluid" id="form-widget-content" > 
                            
                            	<div class="span3">
                                 	
                                    <div class="btn-group">  
                                        <button class="btn dropdown-toggle i-bars btn-show-selected" data-toggle="dropdown">
                                            <span class="btn-text" >All Results</span> &nbsp
                                            <span class="icon16 caret" ></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu"> 
                                            <li><a href="#" report-type="" class="btn-show-report" >All Results</a></li>  
                                            <?php
											foreach($results as $row=>$result) {
											?>
											<li><a href="#" report-type="result_<?=$result->result_id?>" class="btn-show-report" ><?=$result->result_name?></a></li>
											<?php	
											}//end foreach
											?> 
											 
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
											$s_fromdate = $sdata[s_fromdate];
											$s_todate = $sdata[s_todate]; 
											
                                            if($s_fromdate && $s_todate)
                                             {
                                                $s_fromdate = urldecode(urlencode($s_fromdate));
                                                $s_todate = urldecode(urlencode($s_todate)); 
                                                echo date("F j, Y h:i A", strtotime($s_fromdate)).' - '.date("F j, Y h:i A", strtotime($s_todate));
                                                $s_fromdate = date("Y-m-d H:i:00", strtotime($s_fromdate));
                                                $s_todate = date("Y-m-d H:i:00", strtotime($s_todate));
                                             }
                                            else
                                             {
                                                echo date("F j, Y 12:01 ")." AM".' - '.date("F j, Y 11:59 A");  
                                                $s_fromdate = date("Y-m-d 00:00:00");
                                                $s_todate = date('Y-m-d 23:59:59');
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
                                            <div class="advance-search"  >   
                                                
                                                <div class="control-group">
                                                	  
                                                     <div class="span6" > 
                                                         <label class="control-label" for="s_agent">Agent</label>
                                                         <div class="controls controls-row"   >
                                                            <select class="select2 myselect" name="s_agent" id="s_agent"  >
                                                                <optgroup label="" >    
                                                                    <option value="" >- All CRM -</option> 
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
                                                            <select name="s_currency" id="s_currency" class="select2" >
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
                                                  
                                                <div class="control-group"> 
                                                	
                                                    <div class="span6" >
                                                    	<label class="control-label" for="s_category">Category</label>
                                                        <div class="controls controls-row"   >
                                                            <select name="s_category" id="s_category" class="required select2" > 
                                                                <optgroup label="" >    
                                                                    <option value="" >- All -</option> 
                                                                    <?php
                                                                    foreach($categories as $row=>$category) {
                                                                    ?>
                                                                    <option value="<?=$category->CategoryID?>" ><?=$category->Name?></option>
                                                                    <?php	
                                                                    }//end foreach
                                                                    ?> 
                                                                </optgroup>   
                                                            </select> 
                                                        </div> 
                                                    </div> 
                                                    
                                                    <div class="span6" >
                                                    	<label class="control-label" for="s_promotion">Promotion</label>
                                                        <div class="controls controls-row"   >
                                                            <select name="s_promotion" id="s_promotion" class="required select2"  disabled="disabled" > 
                                                                <optgroup label="Select Promotion"> 
                                                                    <option value=""  >- All --</option>  
                                                                    <?php /*?><?php
                                                                    foreach($promotions as $row => $promotion){ 
                                                                        ?>
                                                                    <option  value="<?=$promotion->PromotionID;?>" <?php if($promotion->PromotionID == $activity->Promotion) echo "selected='selected'";?> ><?=$promotion->Name;?></option>	 		
                                                                        <?php 
                                                                        }
                                                                    ?><?php */?>
                                                                </optgroup>  
                                                            </select> 
                                                        </div> 
                                                    </div> 
                                                               
                                                </div>
                                                <!-- End .control-group -->  
                                                
                                                <div class="control-group">
                                                	  
                                                     <div class="span6" > 
                                                         <label class="radio-inline" >
                                                            <b>All Uploaded &nbsp;</b>  <input type="checkbox" value="1" name="s_isupload" id="s_isupload" checked="checked" /> 
                                                         </label> 
                                                     </div>
                                                     
                                                     <div class="span6" > 
                                                     	<label class="control-label" for="s_basetotal"  >Total Base</label>
                                                        <div class="controls controls-row"  >
                                                            <input type="text" name="s_basetotal" id="s_basetotal" class="disabled" value="" disabled="disabled" style="min-height: 36px !important; text-align: left; width: 60% !important; " >   
                                                        </div> 	        
                                                     </div>
                                                      
                                                </div>
                                                <!-- End .control-group --> 
                                                  
                                            </div>
                                            <!-- end advance-search menu--> 
                                            
                                        </div>
                                        
                                    </div> 
                                    <!-- end Advance Search -->
                                    
                                </div>
                                <!-- end span 9 -->   
                                
                                
                                
                            </div>
                            <!-- end search options -->
                            
                            <input type="hidden" value="" name="s_page" id="s_page"  />  
                            <input type="hidden" value="" name="s_ppage" id="s_ppage"  />
                            <input type="hidden"  style="text-align: center;" name="s_fromdate" id="s_fromdate" value="<?=$s_fromdate;?>" >
                            <input type="hidden"  style="text-align: center;" name="s_todate" id="s_todate" value="<?=$s_todate;?>" > 
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="overflow: scroll;">
							<thead>
                                <tr> 
                                    <th class="center" >
                                        Result
                                    </th>   
                                    
                                    <th class="center" >
                                        Outcome
                                    </th> 
                                    
                                    <th class="center" width="10%" >
                                        Total
                                    </th> 
                                    
                                    <th class="center" width="10%" >
                                        Percentage
                                    </th>
                                      
                                </tr> 
                                 
							</thead>
							
                            <tbody id="ActivityList" class="dynamic-list" > 
                           		
                                <?php /*?><?php 
								foreach($outcomes as $row=>$outcome) { 
								?>
                                <tr>
                                    <td class="center result-col report-col" ><?=$outcome->result_name?></td>  
                                    <td class="center outcome-col report-col" ><?=$outcome->outcome_name?></td>  
                                    <td class="center total-col report-col" >0</td>  
                                    <td class="center percentage-col report-col" >0</td>  
                                </tr>
								<?php		 
								}//end foreach
								?><?php */?> 	 
                                
                            </tbody> 
                            
							<tfoot>
                                <tr> 
                                    <th class="center" colspan="4" >
                                    
                                    </th> 
                                </tr>
							</tfoot>
							</table>  
                            
                            <div class="span6" style="margin-left: 0 !important; margin-top: 20px;"  id="TotalCountArr"  >
                                <table cellpadding="0" cellspacing="0"  class="table table-bordered table-hover"> 
                                <tbody id="TotalCountList"  > 
                                       
                                </tbody>  
                                </table> 
                             </div>
                             
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
                             	 
                                <?php if(allow_export_data() || report_module() ){ ?>
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
<div class="modal fade promotion-called-modal" id="ActivityDetailsModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-star-2"></i>Promotions</h4>
		</div>
		
		<!-- tab content -->
		<div style="padding: 20px 20px 20px 20px; " class="ajax_content" > 
          	
            <?php /*?><div class="row-fluid" style="margin-bottom: 20px; "  > 
            
                <div class="btn-group">  
                    <button class="btn dropdown-toggle i-coin btn-show-currency" data-toggle="dropdown">
                        <span class="btn-text" >All Currencies</span> &nbsp
                        <span class="icon16 caret" ></span>
                    </button>
                    
                    <ul class="dropdown-menu"> 
                        <li><a href="#" currency-name="" class="btn-show-report-promotion" >All Currencies</a></li>  
                        <?php
                        foreach($currencies as $row=>$currency) {
                        ?>
                        <li><a href="#" currency-name="promotion-currency-<?=$currency->CurrencyID?>" class="btn-show-report-promotion promotion-currency-<?=$currency->CurrencyID?>" currency-id="<?=$currency->CurrencyID?>" ><?=$currency->Abbreviation?></a></li>
                        <?php	
                        }//end foreach
                        ?> 
                         
                    </ul> 
                    
                </div>  
                
           	</div> <?php */?>
                 
            <div>
            
            	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTablePromotion" style="overflow: scroll;">
                    <thead>
                        <tr> 
                            <th class="left" >
                                Promotion
                            </th>   
                            
                            <th class="center" >
                                Currency
                            </th> 
                            
                            <th class="center" width="10%" >
                                Total Base 
                            </th>
                            
                            <th class="center" width="10%" >
                                Calls
                            </th> 
                            
                            <th class="center" width="10%" >
                                Reached
                            </th>
                              
                        </tr> 
                         
                    </thead>
                    
                    <tbody id="ActivityPromotionCalledList" class="dynamic-list-promotion" > 
                        
                    </tbody> 
                    
                    <tfoot>
                        <tr> 
                            <th class="center" colspan="20" >
                            
                            </th> 
                        </tr>
                    </tfoot>
                    </table>
                    
            </div>
            
            <!-- pagination -->
            <div id="PromotionListPagination" class="row-fluid">
                <div class="span4">  
                    <div id="dataTable_info2" class="dataTables_info"  ><!--Showing 1 to 10 of 58 entries--></div>   
                </div>
                
                <div class="span8" > 
                    <div class="dataTables_paginate paging_bootstrap pagination" id="ActivityPaginationPromotion" >
                         
                    </div> 
                </div>
            </div>
            <!-- end pagination -->
            
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

<script id="list-row-details" type="text/x-handlebars-template">   
	{{#each outcomes}}	    
		<tr class="outcome_row result_{{this.result_id}} {{#xif " this.IsSummary == 1 "}} total_info summary_{{this.result_id}} {{/xif}} "  {{#xif " this.IsSummary != 1 "}} id="Outcome{{this.outcome_id}}" {{/xif}} >  
			{{#xif " this.Countx == 0 "}}	 
				<{{this.html}} class="center result_name"  
					{{#xif " this.RowSpan > 0 "}}  
					rowspan="{{this.RowSpan}}" 	
					{{/xif}} 
				style="border-left: 0 !important;" >{{this.result_name}}</{{this.html}}>
			{{/xif}} 
			
			<{{this.html}} class="center" {{#xif " this.IsSummary == 1 "}} colspan="2" {{/xif}} >
				{{this.outcome_name}}
			</{{this.html}}>  
			
			<{{this.html}} class="center" >{{formatNumberComma this.CallCount}}</{{this.html}}>  
			<{{this.html}} class="center" >{{this.Average}}</{{this.html}}>  
		</tr>     
	{{else}}
		 <tr class="outcome-row" >   
			<td colspan="20" class="center"  >No record found! </td>      
		 </tr>
	{{/each}}       
</script> 

<script id="list-row-details-summary" type="text/x-handlebars-template"> 
 	{{#if records }}
		<tr>   
			<th class="center" >CRM Agent</th> 
			<td class="center"  id="SummaryCrmAgent" >{{summary.CrmAgent}}</td>
		</tr>
		<tr>   
			<th class="center" >Currency</th> 
			<td class="center" id="SummaryCurrency" >{{summary.Currency}}</td>
		</tr>
		<tr>   
			<th class="center" >Category</th> 
			<td class="center" id="SummaryCategory" >{{summary.Category}}</td>
		</tr>
		<tr>   
			<th class="center" >Promotion</th> 
			<td class="center" id="SummaryPromotion" ><a href="#ActivityDetailsModal" data-toggle="modal" >{{summary.Promotion}}</a></td>
		</tr>  
		<tr>   
			<th class="center" >Total Base</th> 
			<td class="center" id="SummaryTotalBase" ><a href="{{formatNumberComma summary.TotalBaseLink}}" >{{summary.TotalBase}}</a></td>
		</tr> 
		<tr>   
			<th class="center" >Total Calls</th> 
			<td class="center" id="SummaryTotalCalls" ><a href="{{formatNumberComma summary.TotalCallsLink}}" >{{summary.TotalCalls}}</a></td>
		</tr> 
		<tr>   
			<th class="center" >Total Calls Ave.</th> 
			<td class="center" id="SummaryTotalCallsAve" >{{summary.TotalCallsAve}}%</td>
		</tr> 
		<tr>   
			<th class="center" >Unattempted Calls</th> 
			<td class="center" id="SummaryUnattemptedCalls" >{{formatNumberComma summary.UnattemptedCalls}}</td>
		</tr>
		<tr>   
			<th class="center" >Unattempted Calls Ave.</th> 
			<td class="center" id="SummaryUnattemptedCallsAve" >{{summary.UnattemptedCallsAve}}%</td>
		</tr>   
	{{else}}
	 
	{{/if}}	 	  	
</script> 

<script id="list-row-promotions" type="text/x-handlebars-template">  
	{{#each promotions}}	  
		<tr class="promotion-row promotion-currency-{{this.PromotionCurrency}}" id="Promotion-{{this.PromotionID}}" > 
			<td class="left" >{{this.PromotionName}}</td>     
			<td class="center" >{{this.PromotionCurrencyName}}</td>   
			<td class="center" >{{formatNumberComma this.PromotionCountBase}}</td>  
			<td class="center" >{{formatNumberComma this.CallCount}}</td>   
			<td class="center" >{{formatNumberComma this.ReachedCount}}</td>  
		</tr>  
	{{else}}
		<tr class="promotion-row" >   
			<td colspan="20" class="center"  >No record found! </td>      
		</tr>  
	{{/each}}      
</script> 


<script>  
var xhr; 
var class_a = "";  
var template_list;
var template_list_summary; 
var template_details;
var list_row_promotions;

function getCrmReports(){ 
	 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>crm/getCrmReports",  
		beforeSend:function(){   
			//show loading 
			$("#ActivityList").find("tr.outcome_row").remove();	 
			$("#TotalCountArr").hide();
		 	searchLoading("show");  
		},
		success:function(newdata){   
			//console.log(JSON.stringify(newdata));
			searchLoading("hide");
			$("#ActivityLoader").remove(); 
			$("#ActivityList").find("tr.outcome_row").remove();
			
			//$("#ActivityList").append(newdata.outcomes);  
			 
			$("#ActivityList").append(template_list(newdata));
			$("#TotalCountList").html(template_list_summary(newdata));
			
			
			
			$("#dataTable").find("tfoot").html($("#ActivityList tr#OutcomeTotalCount").html());
			$("#ActivityList tr#OutcomeTotalCount").remove();
 			  
			//for pagination 
			$("#dataTable_info").html(newdata.pagination_string);
			$("#ActivityPagination").html(newdata.pagination);
		 	
			//total table
			$("#TotalCountArr").show();
			$("#TotalCountArr").find("#TotalCountList").html(newdata.total_arr); 
			
			if(newdata.records > 0)$(".btn_export").show();
		 
			/*$("#ActivityPagination li").each(function(index) { 
				if(!$(this).hasClass("active") && $(this).find("a").length > 0)
				 { 
					 $(this).find("a").addClass("pagination_link"); 
					 $(this).find("a").attr("page-num", $(this).find("a").attr("href").replace('/','')); 
				 } 
				$(this).find("a").removeAttr("href");
			});*/ 
			 
			$(".pagination_link").click(function(){ 
				$("input[name=s_page]").val($(this).attr("page-num")); 
				getCrmReports(); 
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
			 
		}
		  
	}); //end ajax 
	
	  
}


function exportReports(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>crm/exportCrmReport",  
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
 
function getCrmReportsPromotions(){ 
	 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>crm/getCrmReportsPromotions",   
		beforeSend:function(){       
			$("#ActivityPromotionCalledList").html("<tr class=\"row-loader\" ><td colspan=\"20\" class=\"center\" ><img src=\""+base_url+"media/images/loader.gif\" /> &nbsp; loading... </td></tr>");   
		},
		success:function(newdata){   
			//console.log(JSON.stringify(newdata)); 
			$("#ActivityPromotionCalledList").find("tr.row-loader").remove(); 
			$("#ActivityPromotionCalledList").append(list_row_promotions(newdata));
			 
			 
			//for pagination 
			if(newdata.records > 0)
			 {
				$("#dataTable_info2").html(newdata.pagination_string);
				$("#ActivityPaginationPromotion").html(newdata.pagination);  
				
				$("#ActivityPaginationPromotion li").each(function(index) { 
					if(!$(this).hasClass("active") && $(this).find("a").length > 0)
					 { 
						 $(this).find("a").addClass("pagination_link"); 
						 $(this).find("a").attr("page-num", $(this).find("a").attr("href").replace('/','')); 
					 } 
					$(this).find("a").removeAttr("href");
				});
				
				$("#PromotionListPagination .pagination_link").click(function(){  
					$("html, body").animate({ scrollTop: 0 }, "slow"); 
					$("input[name=s_ppage]").val($(this).attr("page-num"));    
					getCrmReportsPromotions(); 
				});  
				
			 }
			else
			 {
				$("#dataTable_info2").html("");
				$("#ActivityPaginationPromotion").html("");
			 }
			 
		}
		  
	}); //end ajax 
	
	  
} 
  
$(function() { 	 
 	
	template_list = Handlebars.compile($("#list-row-details").html());
	template_list_summary = Handlebars.compile($("#list-row-details-summary").html()); 
	list_row_promotions = Handlebars.compile($("#list-row-promotions").html());
	
	$('#search_form .dropdown-menu .advance-search').click(function(e) {
		e.stopPropagation(); 
	});
	 
	 
	$('#search_form')[0].reset(); 
	//$.uniform.update("input:checkbox[name=s_important]:checked");
	
	//Start of the system 2013-09-01 00:00:00 
	$('#reportrange').daterangepicker(
		 {
			  ranges: {
				 //'Today': [moment(), moment()], 
				 'Today': ["<?=date('Y-m-d 00:00:00')?>", "<?=date('Y-m-d 23:59:59')?>"],
				 //'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
				 'Yesterday': ["<?=date('Y-m-d 00:00:00', strtotime('-1 day'))?>", "<?=date('Y-m-d 23:59:59', strtotime('-1 day'))?>"],
				 'Last 7 Days': [moment().subtract('days', 6), moment()],
				 'Last 30 Days': [moment().subtract('days', 29), moment()],
				 'This Month': [moment().startOf('month'), moment().endOf('month')],
				 'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
				 'From the beginning': ["<?=date('2013-09-01 00:00:00')?>", "<?=date('Y-m-d 23:59:59')?>"]
			  },
			  //startDate: moment().subtract('days', 29),
			  startDate: "<?=$s_fromdate;?>",//moment(),
			  endDate: "<?=$s_todate;?>",//moment(),
			  timePicker: true, 
			  timePickerIncrement: 1, //minutes default 30
			  selected_hour: 24, 
			  //format: 'MM/DD/YYYY h:mm A'
			  format: 'YYYY/MM/DD H:mm:ss' 
		 },
			function(start, end) {
				$('#reportrange span').html(start.format('MMMM DD, YYYY h:mm A') + ' - ' + end.format('MMMM DD, YYYY h:mm A'));
				$("#s_fromdate").val(start.format('YYYY-MM-DD HH:mm:ss')); 
				$("#s_todate").val(end.format('YYYY-MM-DD HH:mm:ss'));
			}
	);
	   
	   
	$("#search_form select[name=s_usertypex]").change(function(){     
		var values_id = $.map($(this).find(":selected"), function(option) { 
					   return option.value; 
					});    		 
		$("#s_usertype").val(values_id);   
		changeAgentList("<?=base_url()?>cal/getAgentList", $("#s_usertype").val(), "", $("#s_agent"));   
	}); 
  	 
	getCrmReports();
	$(".btn_search").click(function(){    
		$("input[name=s_page]").val("");
		$('.alert').remove();
		if(!$("input:checkbox[name='s_isupload']").is(':checked'))
		 {
			if($("#s_basetotal").val() == "" || $("#s_basetotal").val()<= 0)
			 {
				createMessage("", "Enter base total!", "error");
				 return false; 
			 }
		 }
		 
		getCrmReports(); 
	}); 
	   
	
	$(".btn_export").click(function(){
		if(!$("input:checkbox[name='s_isupload']").is(':checked'))
		 {
			if($("#s_basetotal").val() == "" || $("#s_basetotal").val()<= 0)
			 {
				createMessage("", "Enter base total!", "error");
				 return false; 
			 }
		 }
		exportReports();
	});
	
	$(".btn-show-report").click(function(){  
		class_a = $(this).attr("report-type");   
		$(".btn-show-selected").find("span.btn-text").text($(this).text());   
		$(".outcome_row").hide();
		if(class_a)
		 {
			$("."+class_a).show(); 
		 }
		else
		 {
			$(".outcome_row").show(); 
		 }
	});
	
	$(".btn-show-report-promotion").click(function(){  
		var class_a = $(this).attr("currency-name");   
		$(".btn-show-currency").find("span.btn-text").text($(this).text());   
		$(".promotion-row").hide();   
		if(class_a)
		 {
			$("."+class_a).show(); 
		 }
		else
		 {
			$(".promotion-row").show(); 
		 }
	});
	
	
	
	$("#s_currency").change(function(){    
		var currency = ($(this).val())?$(this).val():"";   
		var category = ($("#s_category").val())?$("#s_category").val():"";   
		if(currency || category)changePromotions("<?=base_url()?>promotions/getPromotionsList", '', currency, '', $("select[name=s_promotion]"), '', category, 2, 2);   
		 
		 setTimeout(function(){ 
		 	 	$("select[name=s_promotion] option[value='N/A']").remove();  
		    }, 
		 1000);
	});
	
	$("#s_category").change(function(){     
		var category = ($(this).val())?$(this).val():"";   
		var currency = ($("#s_currency").val())?$("#s_currency").val():"";  
		if(currency || category)changePromotions("<?=base_url()?>promotions/getPromotionsList", '', currency, '', $("select[name=s_promotion]"), '', category, 2, 2);   
		 
		 setTimeout(function(){ 
		 	 	$("select[name=s_promotion] option[value='N/A']").remove();  
		    }, 
		 1000);
	});
	
	$("input:checkbox[name='s_isupload']").click(function(){  
		        
		  if(!$(this).is(':checked')) 
		  {  
		  	 $("#s_basetotal").removeAttr("disabled"); 
			 $("#s_basetotal").removeClass("disabled"); 
			   
		  } 
		 else
		  {   
		  	$("#s_basetotal").focus();		
			$("#s_basetotal").attr("disabled", "disabled"); 
			$("#s_basetotal").addClass("disabled"); 		  
			$("#s_basetotal").val("");  
		  } 
		   
	 });
	 
	$('#search_form select').select2({placeholder: "Select"});  
	
	
	$("#ActivityDetailsModal").on('show', function(event){
		//alert(event.currentTarget);
		$("input[name=s_ppage]").val("");    
		$("html, body").animate({ scrollTop: 0 }, "slow"); 
		getCrmReportsPromotions(); 
		 
	});
		 
});  

</script>
 