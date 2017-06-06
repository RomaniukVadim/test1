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
var is_change = 0;  
var source; 
var template;

var source_details; 
var template_details; 

//var total_leads = 0; 
//var total_contacted = 0; 

var total = {};
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
tr.conversion-row-footer th {
	border-right: 0px !important;
}
tr.conversion td.sub-product {
	border-left: 0px !important;
	vertical-align: middle;
}
.action-holder {
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
          <li class="active">CRM Conversions</li>
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
                <div class="icon"> <i class="icon20 i-call-outgoing"></i> </div>
                <h4>CRM Conversions</h4>
                <a href="#" class="minimize"></a> </div>
              <!-- End .widget-title -->
              
              <div class="widget-content" >
                <form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                  
                  <!-- search options -->
                  <div class="row-fluid" id="form-widget-content" >
                    <div class="span3">
                      <?php /*?><div class="btn-group">  
                                        <button class="btn dropdown-toggle i-bars btn-show-selected" data-toggle="dropdown">
                                            <span class="btn-text" >All Products</span> &nbsp
                                            <span class="icon16 caret" ></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu"> 
                                            <li><a href="#" report-type="" class="btn-show-report" >All Products</a></li>  
                                            <?php
											foreach($sub_products as $row=>$product) {
											?>
											<li><a href="#" report-type="conversion-row-<?=$product->SubID?>" class="btn-show-report" ><?=$product->Name?></a></li>
											<?php	
											}//end foreach
											?> 
											 
                                        </ul>
                                    </div><?php */?>
                    </div>
                    <!-- end span 3 -->
                    
                    <div class="span9" >
                      <div class="btn-group pull-right" style="margin-left: 10px;" >
                        <button class="btn dropdown-toggle btn-primary btn_search" data-toggle="dropdown"> Search </button>
                      </div>
                      
                      <!-- datepicker -->
                      <div id="reportrange" class="pull-right btn btn-primary pull-right" > <i class="icon18 i-calendar"></i>
                        <?php /*?><span><?php echo date("F j, Y", strtotime('-30 day')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b> <?php */?>
                        <span>
                        <?php  
						$s_fromdate = $sdata['s_fromdate'];
						$s_todate = $sdata['s_todate'];   
						 
						//$s_fromdate = "2015-09-01 00:00:00";
						//$s_todate = "2015-09-30 23:59:59";
						 
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
							$s_fromdate = date("Y-m-01 00:00:00");
							$s_todate = date('Y-m-t 23:59:59');
							echo date("F d, Y 12:00 A", strtotime($s_fromdate)).' - '.date("F d, Y 11:59 A", strtotime($s_todate));   
						 }
						?>
                        </span> 
                        <!--<b class="caret"></b>  --> 
                        <i class="icon19 i-arrow-down-2"></i> </div>
                      <!-- end datepicker --> 
                      
                      <!-- Advance Search -->
                      <div class="pull-right btn-group call-search margin-right-10" >
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <i class="icon16 i-cogs"></i> Advance Search <span class="caret"></span> </button>
                        <div class="dropdown-menu  opensleft daterangepicker" > 
                          
                          <!-- advance-search menu-->
                          <div class="advance-search"  >
                            <div class="control-group">
                              <div class="span6" >
                                <label class="control-label" for="s_product">Product</label>
                                <div class="controls controls-row"   >
                                  <select class="select2 myselect" name="s_product" id="s_product"  >
                                    <optgroup label="" >
                                    <option value="" >- All Product -</option>
                                    <?php
								   foreach($sub_products as $row=>$product) {
									?>
                                    <option value="<?=$product->SubID?>" <?=($product->SubID==$sdata['s_product'])?"selected='selected'":""?> >
                                    <?=$product->Name?>
                                    </option>
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
                                    <option value="<?=$currency->CurrencyID?>" >
                                    <?=$currency->Abbreviation?>
                                    </option>
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
                                    <option value="<?=$category->CategoryID?>" >
                                    <?=$category->Name?>
                                    </option>
                                    <?php	
									}//end foreach
									?>
                                    </optgroup>
                                  </select>
                                </div>
                              </div> 
                              
                              <?php
							  if(admin_access() || csd_supervisor_access())
							   {
							  ?> 
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
                             <?php
							   }
							 ?>
                             
                              <?php /*?><div class="span6" >
									<label class="control-label" for="s_promotion">Promotion</label>
									<div class="controls controls-row"   >
										<select name="s_promotion" id="s_promotion" class="required select2"  disabled="disabled" > 
											<optgroup label="Select Promotion"> 
												<option value=""  >- All --</option>   
											</optgroup>  
										</select> 
									</div> 
								</div><?php */?>
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
                  <input type="hidden"  style="text-align: center;" name="s_fromdate" id="s_fromdate" value="<?=$s_fromdate;?>" >
                  <input type="hidden"  style="text-align: center;" name="s_todate" id="s_todate" value="<?=$s_todate;?>" >
                  <input type="hidden" name="s_subproductid" id="s_subproductid" value="" >
                  <input type="hidden" name="s_categoryid" id="s_categoryid" value="" >
                  <input type="hidden" name="s_action" id="s_action" value="" > 
                  <input type="hidden" name="click_subproductid" id="click_subproductid" value="" >
                  <input type="hidden" name="click_categoryid" id="click_categoryid" value="" >
                  <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="overflow: scroll;">
                    <thead>
                       
                      <tr>
                        <th colspan="5">&nbsp; </th>
                        <th colspan="2">Deposited</th>
                        <th colspan="2">Claimed</th> 
                        <th colspan="3">Conversion</th>
                      </tr> 
                      
                      <tr>
                        <th class="center" > Product </th>
                        <th class="center" > Campaign </th>
                        <th class="center" width="9%"  > Total Leads</th>
                        <th class="center" width="5%"  > Contacted</th> 
                        <th class="center" width="5%"  > Offer</th> 
                        
                        <th class="center" width="4%"  > Market</th>
                        <th class="center" width="4%"  > Personal</th> 
                        
                        <th class="center" width="4%"  >Market</th>
                        <th class="center" width="4%"  >Personal</th>
                        <th class="center" width="4%"  >Market</th>
                        <th class="center" width="4%"  >Personal</th>
                        <th class="center" width="5%"  > Auto </th>
                      </tr>
                    </thead>
                    <tbody id="ActivityList" class="dynamic-list" >
                     
                    </tbody>
                    <tfoot>
                       
                    </tfoot>
                  </table>
                  <div class="span6" style="margin-left: 0 !important; margin-top: 20px;"  id="TotalCountArr"  >
                    <table cellpadding="0" cellspacing="0"  class="table table-bordered table-hover">
                      <tbody id="TotalCountList" >
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
                    <div class="dataTables_paginate paging_bootstrap pagination" id="ActivitiyPagination" >
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
                    <a href="#CommonModal" title="export results" alt="export results" class="btn btn_export tip"  id="ExportBtn"  data-toggle="modal" > <i class="icon16 i-file-excel" ></i> Export Results </a> </div>
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
<div class="modal fade" id="ActivityActivityDetailsModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog"  >
    <div class="modal-content"  >
      <div class="modal-header" >
        <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"  ><i class="icon20 i-upload-4"></i>Upload Record</h4>
      </div>
      
      <!-- tab content -->
      <div style="padding: 20px 20px 20px 20px; " class="ajax_content" > </div>
      <!-- end content -->
      
      <?php /*?><div class="modal-footer" > 
		  <div id="SuggestionFormLoader" style="float: left; " ></div>
		  <button type="submit" class="btn btn-primary sugbtn" id="BtnSubmitSuggestion" >Submit</button>	
		  <button type="button" class="btn btn-default sugbtn" id="BtnCloseSuggestion" data-dismiss="modal" >Close</button>
		</div><?php */?>
    </div>
    <!-- /.modal-content --> 
    
  </div>
  <!-- /.modal-dialog --> 
  
</div>
<!-- END UPDATE STATUS MODAL --> 

<!-- ACTIVITY DETAILS MODAL -->
<div class="modal fade" id="ActivityDetailsModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  tabindex="-1" >
  <div class="modal-dialog"  >
    <div class="modal-content"  >
      <div class="modal-header" >
        <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"  ><i class="icon20 i-call-outgoing"></i>Conversion Details</h4>
      </div>
      
      <!-- tab content -->
      <div style="padding: 20px 20px 20px 20px; " class="ajax_content" >  
      	
      	<div id="TableList" >
        	
            <div > 
                 <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                    <thead>  
                    	<tr>
                            <th colspan="5">&nbsp; </th>
                            <th colspan="2">Deposited</th>
                            <th colspan="2">Claimed</th> 
                            <th colspan="3">Conversion</th>
                        </tr>
                        
                        <tr> 
                            <th class="center" >Promotion</th>
                            <th class="center" width="7%" >Currency</th>
                            <th class="center" width="7%" >Leads</th>
                            <th class="center" width="7%" >Contacted</th> 
                            <th class="center" width="7%" >Offer</th> 
                            <th class="center" width="7%"  >Market</th>
							<th class="center" width="7%"  >Personal</th> 
                            <th class="center" width="7%"  >Market</th>
							<th class="center" width="7%"  >Personal</th>   
                            <th class="center" width="7%"  >Market</th> 
                            <th class="center" width="7%"  >Personal</th>
							<th class="center" width="7%"  >Auto</th>
                        </tr>
                    </thead>
                </table>
            </div>
            
            <!-- scroll -->   
            <div class="modal_scroll" style="max-height: 400px; overflow:auto; margin-top: -1px; " >	  
             
                <div id="ScrollWrap"  style="display: block !important; position: relative !important; "  > 
                     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                        <tbody id="PromotionListDetails" > 
                        	
                        </tbody>
                      </table>
                </div>
            </div>    
        	<!-- end scroll -->
            
            <div style="margin-top: -1px;" > 
                 <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; border-top: 0px !important; " >
                    <tfoot>
                        <tr>
                            <th class="center" colspan="5" style="border-top: 0px !important;" >&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
            </div>  
            
        
        </div>	
         
        <?php /*?><table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="overflow: scroll;">
            <thead>
               
              <tr>
                <th colspan="5">&nbsp; </th>
                <th colspan="2">Deposited</th>
                <th colspan="2">Claimed</th> 
                <th colspan="3">Conversion</th>
              </tr> 
              
              <tr> 
                <th class="left" > Promotions </th>
                <th class="center" width="7%"  >Currency</th>
                <th class="center" width="9%"  >Leads</th>
                <th class="center" width="5%"  >Contacted</th> 
                <th class="center" width="5%"  >Offer</th> 
                
                <th class="center" width="4%"  >Market</th>
                <th class="center" width="4%"  >Personal</th> 
                
                <th class="center" width="4%"  >Market</th>
                <th class="center" width="4%"  >Personal</th>
                <th class="center" width="4%"  >Market</th>
                <th class="center" width="4%"  >Personal</th>
                <th class="center" width="5%"  >Auto</th>
              </tr>
            </thead>
            
            <tbody id="PromotionListDetails" class="dynamic-list" >
            	<tr> 
                    <th class="center" ></th>
                    <th class="center" ></th>
                    <th class="center" ></th>
                    <th class="center" ></th> 
                    <th class="center" ></th> 
                    
                    <th class="center" ></th>
                    <th class="center" ></th> 
                    
                    <th class="center" ></th>
                    <th class="center" ></th>
                    <th class="center" ></th>
                    <th class="center" ></th>
                    <th class="center" ></th>
              	</tr> 
            </tbody>
            
            <tfoot>
               
            </tfoot>
          </table><?php */?>
      
      </div>
      <!-- end content -->
      
      <?php /*?><div class="modal-footer" > 
		  <div id="SuggestionFormLoader" style="float: left; " ></div>
		  <button type="submit" class="btn btn-primary sugbtn" id="BtnSubmitSuggestion" >Submit</button>	
		  <button type="button" class="btn btn-default sugbtn" id="BtnCloseSuggestion" data-dismiss="modal" >Close</button>
		</div><?php */?>
    </div>
    <!-- /.modal-content --> 
    
  </div>
  <!-- /.modal-dialog --> 
  
</div>
<!-- END ACTIVITY DETAILS MODAL --> 

<script id="list-row" type="text/x-handlebars-template">
  {{#if results.length }}
  	{{#each results}}	
	  <tr class="conversion-row conversion-row-{{this.SubProductID}} category-name" id="SubProduct{{this.SubProductID}}" sub-product="{{this.SubProductID}}"  >   
		<td class="sub-product sub-product-{{this.SubProductID}}"  style="vertical-align: middle; border-left: 0px !important; " >{{this.SubProductName}}</td>
		<td >
			<a id="View{{this.Category}}_{{this.SubProductID}}" class="btn-link view_details tip" target="CsaContentDetails" data-toggle="modal" subproduct-id="{{this.SubProductID}}" category-id="{{this.Category}}" title="view promotions" href="#ActivityDetailsModal" >{{this.CategoryName}}</a>  
		</td>    
		<td class="center" >{{formatNumber this.TotalLeads}}</td>  
		<td class="center" >{{formatNumber this.TotalReached}}</td>
		<td class="center" >{{formatNumber this.TotalOffered}}</td> 
		<td class="center" > 
			{{#xif " this.ClaimedOnly == 0" }}
				{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }}
					<span class="action-number" >{{formatNumber this.TotalDeposited}}</span> 
				{{/xif}} 
			{{else}} 
				NA
			{{/xif}} 
			<div class="action-holder" >
				<?php
				if(admin_access() || can_upload_crm_record())
				 {
				?>
				{{#xif " this.ClaimedOnly == 0" }}
				<button class="btn btn-link tip upload-record" title="Upload deposited record" category-id="{{this.Category}}" subproduct-id="{{this.SubProductID}}" action="deposited" >
					<i class="icon16 i-upload-4"></i>
				</button>     
				{{/xif}} 
				<?php
				 }
				?>
			</div>   
		</td>
		<td class="center" >   
			{{#xif " this.ClaimedOnly == 0" }}
				{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }}
					{{formatNumber this.TotalPersonalDeposited}} 
				{{/xif}} 
			{{else}} 
				NA
			{{/xif}} 
			
		</td>
		<td class="center" > 
			{{#xif " this.TotalClaimed > 0 || this.TotalDeposited > 0" }}
				<span class="action-number" >{{formatNumber this.TotalClaimed}}</span>
			{{/xif}}   
			<div class="action-holder" > 
				<?php
				if(admin_access() || can_upload_crm_record())
				 {
				?>
				<button class="btn btn-link tip upload-record" title="Upload claimed record" category-id="{{this.Category}}" subproduct-id="{{this.SubProductID}}" action="claimed" >
					<i class="icon16 i-upload-4"></i>
				</button>    
				<?php
				 }
				?>
			</div>   
		</td> 
		
		<td class="center" > 
			{{#xif " this.TotalClaimed > 0 || this.TotalDeposited > 0" }}
				{{formatNumber this.TotalPersonalClaimed}}
			{{/xif}} 
		</td> 
		
		<td class="center" >
			{{#xif " this.ClaimedOnly == '1' " }}
				{{#xif " this.TotalClaimed > 0" }}
					{{roundNumbers (multiplyVariables (divideVariables this.TotalClaimed this.TotalLeads) 100) 2 }}%
				{{/xif}}    
			{{else}}
			 	{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }}
					{{roundNumbers (multiplyVariables (divideVariables this.TotalDeposited this.TotalLeads) 100) 2 }}%
				{{/xif}} 
			{{/xif}}  
			
		</td>
		
		<td class="center" > 
			{{#xif " this.ClaimedOnly == '1' " }}
				{{#xif " this.TotalClaimed > 0 " }} 
					{{roundNumbers (multiplyVariables (divideVariables this.TotalPersonalClaimed (sumVariables this.TotalReached this.TotalOffered) ) 100) 2 }}% 
				{{/xif}} 
			{{else}}
			 	{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }} 
					{{roundNumbers (multiplyVariables (divideVariables this.TotalPersonalDeposited (sumVariables this.TotalReached this.TotalOffered) ) 100) 2 }}% 
				{{/xif}} 
			{{/xif}}
			
		</td>
		
		<td class="center" >
			 
			{{#xif " this.ClaimedOnly == '1' " }}
				{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }} 
					{{roundNumbers (multiplyVariables (divideVariables (subtractVariables this.TotalClaimed this.TotalPersonalClaimed) this.TotalLeads) 100) 2 }}%
				{{/xif}} 
			{{else}}
			 	{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }} 
					{{roundNumbers (multiplyVariables (divideVariables (subtractVariables this.TotalDeposited this.TotalPersonalDeposited) this.TotalLeads) 100) 2 }}%
				{{/xif}} 
			{{/xif}}
		</td>
		
	 </tr> 
	 
	 {{computeTotalConversions this}}
	  
	{{/each}} 
	<tr class="conversion-row-footer conversion-row" >   
		<th colspan="2" class="center" style="vertical-align: middle; " >TOTAL</th> 
		<th class="center" >{{getGlobalVar "total.total_leads"}}</th>  
		<th class="center" >{{getGlobalVar "total.total_contacted"}}</th>  
		<th class="center" >{{getGlobalVar "total.total_offered"}}</th>  
		<th class="center" >
			{{#xif " total.total_deposited > 0 " }}	
				{{getGlobalVar "total.total_deposited"}} 
			{{/xif}}
		</th> 
		
		<th class="center" >
			{{#xif " total.total_deposited > 0 " }}	
				 {{getGlobalVar "total.total_personal_deposited"}} 
			{{/xif}}
		</th>      
		
		<th class="center" >
			{{#xif "total.total_claimed > 0" }}	
				{{getGlobalVar "total.total_claimed"}} 
			{{/xif}}
		</th>    
		
		<th class="center" >
			{{#xif " total.total_claimed > 0" }}	
				{{getGlobalVar "total.total_personal_claimed"}} 
			{{/xif}}
		</th>
		 
		<th class="center" >
			{{#xif " total.total_deposited > 0 || total.total_claimed > 0" }}	
				{{roundNumbers (getGlobalVar "total.total_market_percentage") 2}}%
			{{/xif}}  
		</th>   
		<th class="center" >
			{{#xif " total.total_deposited > 0 || total.total_claimed > 0" }}	
				{{roundNumbers (getGlobalVar "total.total_personal_deposited_per") 2}}%
			{{/xif}}  
		</th>     
		<th class="center" >
			{{#xif " total.total_deposited > 0 || total.total_claimed > 0" }}	
				{{roundNumbers (getGlobalVar "total.total_auto_percentage") 2}}%
			{{/xif}}  
		</th>  
		<?php /*?> 
		<th class="center" >
			{{#xif " total.total_deposited > 0 || total.total_claimed > 0" }}	
				{{roundNumbers (getGlobalVar "total.total_personal_percentage") 2}}%
			{{/xif}}   
		</th>  <?php */?>
		
	</tr>
  {{else}}
  	<tr class="conversion-row" >   
		<td colspan="20" class="center"  >No record found! </td>      
	 </tr>
  {{/if}}	
  
</script> 


<script id="list-row-details" type="text/x-handlebars-template"> 
 	{{#if results.length }}
		{{#each results}}	
			<tr class="conversion-row-details conversion-row-{{this.SubProductID}} promotion-name" > 
				<td class="left" promotion-id="{{this.Promotion}}" >{{this.PromotionName}}</td>
				<td class="center" width="7%" >{{this.Abbreviation}}</td>
				<td class="center" width="7%" >{{formatNumber this.TotalLeads}}</td>  
				<td class="center" width="7%" >{{formatNumber this.TotalReached}}</td>
				<td class="center" width="7%" >{{formatNumber this.TotalOffered}}</td> 
				<td class="center" width="7%" > 
					{{#xif " this.ClaimedOnly == 0" }}
						{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }}
							<span class="action-number" >{{formatNumber this.TotalDeposited}}</span> 
						{{/xif}} 
					{{else}} 
						NA
					{{/xif}}    
				</td>
				<td class="center" width="7%" >   
					{{#xif " this.ClaimedOnly == 0" }}
						{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }}
							{{formatNumber this.TotalPersonalDeposited}} 
						{{/xif}} 
					{{else}} 
						NA
					{{/xif}} 
					
				</td>
				<td class="center" width="7%" > 
					{{#xif " this.TotalClaimed > 0 || this.TotalDeposited > 0" }}
						<span class="action-number" >{{formatNumber this.TotalClaimed}}</span>
					{{/xif}}      
				</td> 
			
				<td class="center" width="7%" > 
					{{#xif " this.TotalClaimed > 0 || this.TotalDeposited > 0" }}
						{{formatNumber this.TotalPersonalClaimed}}
					{{/xif}} 
				</td> 
				
				<td class="center" width="7%" >
					{{#xif " this.ClaimedOnly == '1' " }}
						{{#xif " this.TotalClaimed > 0" }}
							{{roundNumbers (multiplyVariables (divideVariables this.TotalClaimed this.TotalLeads) 100) 2 }}%
						{{/xif}}    
					{{else}}
						{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }}
							{{roundNumbers (multiplyVariables (divideVariables this.TotalDeposited this.TotalLeads) 100) 2 }}%
						{{/xif}} 
					{{/xif}}  
					
				</td>
				
				<td class="center" width="7%" > 
					{{#xif " this.ClaimedOnly == '1' " }}
						{{#xif " this.TotalClaimed > 0 " }} 
							{{roundNumbers (multiplyVariables (divideVariables this.TotalPersonalClaimed (sumVariables this.TotalReached this.TotalOffered) ) 100) 2 }}% 
						{{/xif}} 
					{{else}}
						{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }} 
							{{roundNumbers (multiplyVariables (divideVariables this.TotalPersonalDeposited (sumVariables this.TotalReached this.TotalOffered) ) 100) 2 }}% 
						{{/xif}} 
					{{/xif}}
					
				</td>
			
				<td class="center" width="7%" > 
					{{#xif " this.ClaimedOnly == '1' " }}
						{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }} 
							{{roundNumbers (multiplyVariables (divideVariables (subtractVariables this.TotalClaimed this.TotalPersonalClaimed) this.TotalLeads) 100) 2 }}%
						{{/xif}} 
					{{else}}
						{{#xif " this.TotalDeposited > 0 || this.TotalClaimed > 0" }} 
							{{roundNumbers (multiplyVariables (divideVariables (subtractVariables this.TotalDeposited this.TotalPersonalDeposited) this.TotalLeads) 100) 2 }}%
						{{/xif}} 
					{{/xif}}
				</td>
		  	</tr>	     
		{{/each}} 
		 
	{{else}}
	<tr class="conversion-row" >   
		<td colspan="20" class="center"  >No record found! </td>      
	 </tr>
	{{/if}}	 	 
</script> 



<script>  
var xhr; 
var class_a = "";  

function initializeTotal() {
	total = {
		total_leads: 0, 
		total_contacted: 0, 
		total_offered: 0, 
		total_claimed: 0, 
		total_deposited: 0, 
		total_personal_deposited: 0,	 
		total_personal_claimed: 0, 
		total_market_percentage: 0, 
		total_personal_deposited_per: 0,	 	 
		total_personal_claimed_per: 0, 
		total_personal_percentage: 0, 
		total_auto_percentage: 0	 	 
	}
}

function getCrmConversions(){  

	initializeTotal(); 
	
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>crm_conversions/getCrmConversions",  
		beforeSend:function(){    
			//show loading 
			$("#ActivityList").find("tr.conversion-row").remove();	 
			$("#TotalCountArr").hide();
		 	searchLoading("show");  
		},
		success:function(newdata){   
			//console.log(JSON.stringify(newdata));
			searchLoading("hide");
			$("#ActivityLoader").remove(); 
			$("#ActivityList").find("tr.conversion-row").remove();
			
			//$("#ActivityList").append(newdata.outcomes);
			$("#ActivityList").append(template(newdata));
			
			//format the table 
			$("#dataTable tbody").find("tr.conversion-row").each(function() {
				//merge and put the text to All merged TD's
				var subid = $(this).attr("sub-product"); 
				var rowspan = $("td.sub-product-"+subid).length;  
				var span_text = (rowspan > 1)?"":"";
				
				$("td.sub-product-"+subid+":not(:first)").remove();
				
				if(rowspan > 1)
				 { 
					$("#dataTable tbody").find("td.sub-product-"+subid).attr("rowspan", rowspan);      
				 }
				 
			});  
			  
			//$("#dataTable").find("tfoot").html($("#ActivityList tr#OutcomeTotalCount").html());
			//$("#ActivityList tr#OutcomeTotalCount").remove();
 			  
			//for pagination 
			$("#dataTable_info").html(newdata.pagination_string);
			//$("#ActivitiyPagination").html(newdata.pagination);
		 	
			//total table
			//$("#TotalCountArr").show();
			//$("#TotalCountArr").find("#TotalCountList").html(newdata.total_arr); 
			
			if(newdata.results.length > 0)$(".btn_export").show();
		  
			 
			$(".tip").tooltip ({placement: 'top'});     
			
			//set default content
			/*if(class_a)
			 {
				 $("[report-type='"+class_a+"']").trigger("click");
			 }
			else
			 {
				 $("[report-type='status-col']").trigger("click");
			 }*/ 
  			
			 //hover row showing and hiding upload icon	
			 $(".category-name").hover(
			   function() {
				 $(".action-holder").hide();
				 $(this).find(".action-holder").show();
			   }, function() {
				 $(this).find(".action-holder").hide();
			   }
			 );
			 
			 //clicking upload icon
			 $(".action-holder .upload-record").click(function(){  
				
				 var category = $(this).attr("category-id");  
				 var subproduct = $(this).attr("subproduct-id");  
				 var action = $(this).attr("action"); 
			  	 
				 $("#ActivityActivityDetailsModal").find(".modal-title").text("Upload " + action.ucwords() + " Record");  
				 
				 if(category && subproduct)
				  {
					$("#s_categoryid").val(category);   
					$("#s_subproductid").val(subproduct);  
					$("#s_action").val(action);  
					
				 	$("#ActivityActivityDetailsModal").modal('show');  
				 	loadAjaxContentForm("<?=base_url()?>crm_conversions/popupUploadRecord", $("#ActivityActivityDetailsModal").find(".ajax_content"), $("#search_form"));
				  } 
				 else
				  {
					$("#s_categoryid").val("");   
					$("#s_subproductid").val("");	
					$("#s_action").val("");	  
				  }
				  
			 }); 
			 
			 //view conversion details
			 $('.view_details').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var click_subproductid = $(this).attr('subproduct-id');   
				var click_categoryid = $(this).attr('category-id');    
				$("#click_subproductid").val(click_subproductid);
				$("#click_categoryid").val(click_categoryid);  
				if(click_subproductid && click_categoryid)getCrmConversionsDetails();
				//if(user_id)loadAjaxContent("<?=base_url()?>users/popupManageUser/"+user_id, $("#ActivityDetailsModal").find(".ajax_content"));  
			});
			   
		}
		  
	}); //end ajax 
	
	  
}


function exportCrmConversions(){ 
	initializeTotal(); 
	
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>crm_conversions/exportCrmConversions",  
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
 
function getCrmConversionsDetails(){     
	 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>crm_conversions/getCrmConversionsDetails",  
		beforeSend:function(){    
			//show loading 
			//$("#ActivityList").find("tr.conversion-row").remove();	 
			//$("#TotalCountArr").hide();
		 	//searchLoading("show");   
			$(".select2-drop, .select2-drop-mask").hide();    
			$("#PromotionListDetails").html("<tr class=\"conversion-row-details\" ><td class=\"center\" colspan=\"20\" ><img src=\""+base_url+"media/images/loader.gif\" class=\"modal_loader\"  /></td></tr>"); 
			 
		},
		success:function(newdata){   
			//console.log(JSON.stringify(newdata));  
			$("#PromotionListDetails").find("tr.conversion-row-details").remove(); 
			$("#PromotionListDetails").append(template_details(newdata));  
			$("#PromotionListDetails .tip").tooltip ({placement: 'top'});    
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

var verticalScroll = function() {
	$(".modal_scroll").niceScroll("#ScrollWrap",{
		cursoropacitymax: 0.8,
        cursorborderradius: 0,
        cursorwidth: "10px", 
		bouncescroll: false, 
		zindex: 999999, 
		autohidemode: true //true, cursor 
	});	 
}

 
$(function() { 	 
 	initializeTotal(); 
	
	source   = $("#list-row").html();
	template = Handlebars.compile(source); 
	
	source_details   = $("#list-row-details").html();
	template_details = Handlebars.compile(source_details);
	  
	//helpers
	Handlebars.registerHelper("computeTotalConversions", function(obj) {   
	  total.total_leads += (parseFloat(obj.TotalLeads) >= 0)?parseFloat(obj.TotalLeads):0;   
	  total.total_contacted += (parseFloat(obj.TotalReached) >= 0)?parseFloat(obj.TotalReached):0;
	  total.total_offered += (parseFloat(obj.TotalOffered) >= 0)?parseFloat(obj.TotalOffered):0; 
	  total.total_deposited += (parseFloat(obj.TotalDeposited) >= 0 && obj.ClaimedOnly != 1)?parseFloat(obj.TotalDeposited):0;
	  
	  total.total_claimed += (parseFloat(obj.TotalClaimed) >= 0)?parseFloat(obj.TotalClaimed):0; 
	  
	  total.total_personal_deposited += (parseFloat(obj.TotalPersonalDeposited) >= 0 && obj.ClaimedOnly != 1)?parseFloat(obj.TotalPersonalDeposited):0;
	  total.total_personal_claimed += (parseFloat(obj.TotalPersonalClaimed) >= 0)?parseFloat(obj.TotalPersonalClaimed):0;
	    
	  /*if(obj.ClaimedOnly == 1)
	   { 
	  	  total.total_market_percentage = ((parseFloat(total.total_claimed)) / parseFloat(total.total_leads)) * 100;	       
	  	  total.total_personal_deposited_per = (parseFloat(total.total_personal_claimed) / parseFloat(total.total_contacted + total.total_offered)) * 100;    	
	   }
	  else
	   {
		   total.total_market_percentage = ((parseFloat(total.total_deposited)) / parseFloat(total.total_leads)) * 100;	       
	  	   total.total_personal_deposited_per = (parseFloat(total.total_personal_deposited) / parseFloat(total.total_contacted + total.total_offered)) * 100;   
	   } */
	    
	  
	  total.total_market_percentage = ((parseFloat(total.total_deposited)) / parseFloat(total.total_leads)) * 100;	       
	  total.total_personal_deposited_per = ((parseFloat(total.total_personal_deposited) ) / parseFloat(total.total_contacted + total.total_offered)) * 100; 
		   	       
	  total.total_personal_claimed_per = (parseFloat(total.total_personal_claimed) / parseFloat(total.total_contacted)) * 100;	 
	  
	  
	  //total.total_personal_percentage += ((parseFloat(obj.TotalPersonalDeposited) / parseFloat(obj.TotalReached)) * 100) + ((parseFloat(obj.TotalPersonalClaimed) / parseFloat(obj.TotalReached)) * 100); 
	 total.total_auto_percentage = ((parseFloat(total.total_deposited) - parseFloat(total.total_personal_deposited)) / parseFloat(total.total_leads) ) * 100;  
	    
	  //Handlebars.helpers.selectBoxOptions(selectedValue, options); // call another helper
	  //total_market_percentage, total_personal_deposited_per, total_auto_percentage	     	  
	});
	  
	$('#search_form .dropdown-menu .advance-search').click(function(e) {
		e.stopPropagation(); 
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
			  minDate: moment("<?=$this->common->start_date?>").hours(23).minutes(59).seconds(59),
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
  	 
	getCrmConversions();
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
		 
		getCrmConversions(); 
	}); 
	   
	
	$(".btn_export").click(function(){  
		
		exportCrmConversions();
	});
	
	$(".btn-show-report").click(function(){  
		class_a = $(this).attr("report-type");   
		$(".btn-show-selected").find("span.btn-text").text($(this).text());   
		$(".conversion-row").hide();
		if(class_a)
		 {
			$("."+class_a).show(); 
		 }
		else
		 {
			$(".conversion-row").show(); 
		 }
	});
	
	
	$("#s_currency").change(function(){    
		var currency = ($(this).val())?$(this).val():"";   
		var category = ($("#s_category").val())?$("#s_category").val():"";   
		//if(currency || category)changePromotions("<?=base_url()?>promotions/getPromotionsList", '', currency, '', $("select[name=s_promotion]"), '', category);   
		 
		 /*setTimeout(function(){ 
		 	 	$("select[name=s_promotion] option[value='N/A']").remove();  
		    }, 
		 1000);*/
	});
	
	$("#s_category").change(function(){     
		var category = ($(this).val())?$(this).val():"";   
		var currency = ($("#s_currency").val())?$("#s_currency").val():"";  
		//if(currency || category)changePromotions("<?=base_url()?>promotions/getPromotionsList", '', currency, '', $("select[name=s_promotion]"), '', category);   
		 
		 /*setTimeout(function(){ 
		 	 	$("select[name=s_promotion] option[value='N/A']").remove();  
		    }, 
		 1000);*/
	});
	 
	
	$('#ActivityActivityDetailsModal, #ActivityDetailsModal').on('hide.bs.modal', function (e) {  
		  $(".select2-drop, .select2-drop-mask").hide();  
		  if(is_change == 1)getCrmConversions(); 
		  is_change = 0; //global  
	});   
	
	$('#ActivityActivityDetailsModal, #ActivityDetailsModal').modal({ 
		show: false, 
		keyboard: true
	});
	
	verticalScroll();
	$(".modal_scroll").mouseover(function() {
	  $(".modal_scroll").getNiceScroll().resize();
	});
	 
	$('#search_form select').select2({placeholder: "Select"});  
	 	 
});  

</script> 
