<script src="<?=base_url();?>media/js/handlebars/handlebars-v3.0.3.js"></script>
<script src="<?=base_url();?>media/js/handlebars/handlebars-helper-x.js"></script>

<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script>  
 
<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/moment.js"></script>
<script type="text/javascript" src="<?=base_url();?>media/js/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end date range -->
 
<script src="<?=base_url()?>media/js/plugins/forms/validation/jquery.validate.js"></script> 
 
<link href="<?=base_url()?>media/js/plugins/forms/switch/bootstrapSwitch.css" rel="stylesheet" />
<script src="<?=base_url()?>media/js/plugins/forms/switch/bootstrapSwitch.js"></script>

<script  type="text/javascript">
var activity_type = "promotion";
var is_change = 0; 
</script>
 
<style>
#search_form .select2-choice {
	max-width: 150px; 
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
				<li><a href="#">Checking</a><span class="divider">/</span></li>
				<li class="active">Market Apps Checking</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-file-check-2"></i> Checking</h1>
			</div> 
            
			<div class="row-fluid"  >
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-mail-4"></i>
							</div>
							<h4>Market Apps Checking</h4> 
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                        
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            
                            <!-- search options -->
                            <div class="row-fluid"  > 
                            
                            	<div class="span3">
                                 	
                                    <button class="btn dropdown-toggle btn-show-form" id="BtnShowUpload" >
                                        <i class="icon12 i-checkmark-3"></i>
                                        Check
                                    </button>
                                    
                                    <?php /*?><div class="btn-group">  
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
                                    </div><?php */?> 
                                           
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
                                                     
                                                     <div class="span6" >  
                                                         <label class="control-label" for="s_category">Category</label>
                                                         <div class="controls controls-row"   >
                                                            <select class="select2 myselect" name="s_category" id="s_category"  >
                                                                <optgroup label="" >    
                                                                    <option value="" >- All Category -</option> 
                                                                    <?php
                                                                    foreach($checking_categories as $row=>$category) {
                                                                    ?>
                                                                    <option value="<?=$category->CategoryID?>" <?=($category->CategoryID==$sdata['s_categoryid'])?"selected='selected'":""?> ><?=$category->CategoryName?></option>
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
                                    <th class="center" width="15%" >
                                        Date Checked
                                    </th>   
                                    
                                    <th class="center"   >
                                        Currency
                                    </th> 
                                    
                                    <th class="center"  >
                                        Category
                                    </th> 
                                    
                                    <th class="center" >
                                        Checked By
                                    </th>
                                    
                                    <th class="center" width="10%" >
                                        Action
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
                                    <th class="center" colspan="5" >
                                    
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
<div class="modal fade checking-modal" id="UserModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-checkmark-circle"></i>12Bet Checking</h4>
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


<!-- UPDATE STATUS MODAL -->
<div class="modal fade checking-modal" id="Checking12BetModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-mail-4"></i>Market Apps Checking</h4>
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

 

<script>  
var xhr;  

function getCheck12Bet(){ 
 
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>check_12bet/getCheck12Bet",  
		beforeSend:function(){   
			//show loading 
			$("#ActivityList").find("tr.check_row").remove();	
		 	searchLoading("show");  
		},
		success:function(newdata){   
			//console.log(JSON.stringify(newdata));
			searchLoading("hide");
			$("#ActivityLoader").remove(); 
			$("#ActivityList").find("tr.check_row").remove();
			$("#ActivityList").append(newdata.checklist);
			$("#dataTable").find("tfoot").html($("#ActivityList tr#AgentTotalCount").html());
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
				getCheck12Bet(); 
			});
			//end pagination 
			$(".tip").tooltip ({placement: 'top'});    
			
			//view details
			$('.show_remarks').click(function() { 
				var check_id = $(this).attr('check-id');    
				//var default_tab = $(this).attr('target');   
				if(check_id)loadAjaxContent("<?=base_url()?>check_12bet/view12betCheckingDetails/"+check_id, $("#UserModal").find(".ajax_content"), "");
			}); 
			 
		}
		  
	}); //end ajax 
	
	  
}


function exportReports(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>check_12bet/exportCheck12Bet",  
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

 
$(function() { 	 
 	
	$('#search_form .dropdown-menu .advance-search').click(function(e) {
		e.stopPropagation(); 
	});
	 
	 
	$('#search_form')[0].reset(); 
	//$.uniform.update("input:checkbox[name=s_important]:checked");
	
	createRangeDatePicker($('#reportrange'), "<?=$this->common->start_date?>"); //create daterangepicker 
	   
	
	$('#UserModal').modal({ 
		show: false, 
		keyboard: true
	});
	   
	   
	$("#search_form select[name=s_usertypex]").change(function(){     
		var values_id = $.map($(this).find(":selected"), function(option) { 
					   return option.value; 
					});    		 
		$("#s_usertype").val(values_id);   
		changeAgentList("<?=base_url()?>cal/getAgentList", $("#s_usertype").val(), "", $("#s_agent"));   
	}); 
  	 
	//getCheck12Bet();
	$(".btn_search").click(function(){    
		$("input[name=s_page]").val("");
		getCheck12Bet(); 
	});  
	
	$(".btn_export").click(function(){ 
		exportReports();
	});
	
	$(".btn-show-form").click(function(){
		$("#Checking12BetModal").modal('show');  
		loadAjaxContent("<?=base_url()?>check_12bet/popupCheckMarketApps", $("#Checking12BetModal").find(".ajax_content"));
	});
	
	/*$(".btn-show-report").click(function(){  
		class_a = $(this).attr("report-type");   
		$(".btn-show-selected").find("span.btn-text").text($(this).text());   
		$(".check_row").hide();
		if(class_a)
		 {
			$("."+class_a).show(); 
		 }
		else
		 {
			$(".check_row").show(); 
		 }  
	});*/
	
	$('#Checking12BetModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();   
		  if(is_change == 1)getCheck12Bet(); 
		  is_change = 0; //global 
	}); 
	
	$('#search_form select').select2({placeholder: "Select"});  
	
	 	 
});  

</script>
 