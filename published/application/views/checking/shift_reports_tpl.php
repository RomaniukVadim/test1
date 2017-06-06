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

.ellipsis {
  text-overflow: ellipsis;
  /*max-width: 95%;  */ 
  width: 300px;   
  /* Required for text-overflow to do anything */
  /*white-space: nowrap;*/
  overflow: hidden;  
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
				<li class="active">Shift Reports</li>
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
								<i class="icon20 i-clipboard-4"></i>
							</div>
							<h4>Shift Reports</h4> 
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                        
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            	<input type="hidden" value="" name="hidden_autype" id="hidden_autype"   />
                            <!-- search options -->
                            <div class="row-fluid"  > 
                            
                            	<div class="span5">  
                                
                                    <div class="btn-group pull-left">  
                                        <button class="btn dropdown-toggle i-coin btn-show-selected-currency" data-toggle="dropdown">
                                            <span class="btn-text" >All Currency</span> &nbsp
                                            <span class="icon16 caret" ></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu">  
                                            <li><a href="#" currency-id="" currency="" class="btn-show-currency" >All Currency</a></li> 
                                            <?php 
											foreach($currencies as $row=>$currency) {
											?> 
                                            <li><a href="#" currency-id="<?=$currency->CurrencyID?>" currency="currency-<?=$currency->CurrencyID?>" class="btn-show-currency" ><?=$currency->Abbreviation?></a></li> 
											<?php	
											}//end foreach
											?> 
                                        </ul> 
                                        <input type="hidden" value="" name="s_currency" id="s_currency"   />
                                    </div>  
                                    
                                 	<?php
									if(admin_access() || shift_report_all())
									 {
									?>
                                    <div class="btn-group pull-left">  
                                        <button class="btn dropdown-toggle i-users btn-show-selected" data-toggle="dropdown">
                                            <span class="btn-text" >All Users</span> &nbsp
                                            <span class="icon16 caret" ></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu" >  
                                            <li><a href="#" type="" user-type="" class="btn-show-user" >All Users</a></li> 
                                            <?php 
											foreach($utypes as $row=>$utype) {
											?> 
                                            <li><a href="#" type="<?=$utype->GroupID?>" user-type="utype-<?=$utype->GroupID?>" class="btn-show-user" ><?=$utype->Name?></a></li> 
											<?php	
											}//end foreach
											?> 
                                        </ul>
                                    </div>
                                    
                                    <?php
									 }
									?>
                                    
                                    <div class="btn-group pull-left">  
                                        <button class="btn dropdown-toggle i-support btn-show-selected-status" data-toggle="dropdown">
                                            <span class="btn-text" >All Status</span> &nbsp
                                            <span class="icon16 caret" ></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu">  
                                            <li><a href="#" status-id="" status="" class="btn-show-status" >All Status</a></li> 
                                            <?php 
											foreach($shift_status as $row=>$status) {
											?> 
                                            <li><a href="#" status-id="<?=$status?>" status="status-<?=$status?>" class="btn-show-status" ><?=ucwords($row)?></a></li> 
											<?php	
											}//end foreach
											?> 
                                        </ul> 
                                        <input type="hidden" value="" name="s_status" id="s_status"   />
                                    </div>
                                            
                                </div>
                                <!-- end span 2 -->
                                
                                <div class="span7" >  
                                 	
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
                                      
                                    
                                </div>
                                <!-- end span 9 -->   
                                
                                
                                
                            </div>
                            <!-- end search options -->
                            
                            <input type="hidden" value="" name="s_page" id="s_page"  /> 
                            <input type="hidden"  style="text-align: center;" name="s_fromdate" id="s_fromdate" value="<?=$s_fromdate;?>" >
                            <input type="hidden"  style="text-align: center;" name="s_todate" id="s_todate" value="<?=$s_todate;?>" > 
							
                             
                            <!-- MORNING SHIFT -->
                            <div class="row-fluid">  
                                
                                <div class="span12 panel">
                                
                                    <div class="widget" id="Shift_1" > 
                                    	<div class="widget-title">
                                            <div class="icon blue">
                                                <i class="icon20 i-sun"  ></i>
                                            </div>	
                                            <h4 id="ActivityLoader_1" >Morning Shift</h4>
                                            <a href="#" class="minimize" ></a>  
                                        </div> 
                                        <!-- End .widget-title -->
                                        
                                        <div class="widget-content"> 
                            				<!-- TABLE LIST -->
                                            <div id="MorningTableList"  >
                                            
                                                <div style="max-width: 100% !important;" > 
                                                     <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                                                        <thead>
                                                            <tr> 
                                                                <th class="left" > Report </th> 
                                                                <th class="center" width="16%" > Date Updated </th> 
                                                                <th class="center" width="12%" > Updated By </th>
                                                                <th class="center" width="13%" > Status </th> 
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                
                                                <!-- scroll -->   
                                                <div class="scroller" style="max-height: 250px; overflow:auto; margin-top: -1px; " >	  
                                                 
                                                    <div id="MorningScrollWrap" style="display: block !important; position: relative !important; "  > 
                                                         <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                                                            <tbody id="ActivityList_1" class="dynamic-list" >
                                                                
                                                            </tbody> 
                                                        </table>   
                                                    </div>  
                                                </div>
                                                <!-- END scroll -->
                                                
                                                <div style="margin-top: -1px;" > 
                                                     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; border-top: 0px !important; " >
                                                        <tfoot>
                                                            <tr>
                                                                <th class="center" colspan="4" style="border-top: 0px !important;" > 
                                                                    <?php
                                                                    if(can_post_shift_report())
                                                                    {
                                                                    ?>
                                                                    <button class="btn dropdown-toggle i-stack-plus btn-show-form pull-left" shift-id='1' >
                                                                        <span class="btn-text" >Add Report</span> &nbsp; 
                                                                    </button> 
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                
                                            </div>
                                            <!-- END TABLE LIST --> 
                                            
                            			</div> 
                                          
                                    </div>
                                     
                                </div>
                                 
                            </div>    
                            <!-- END MORNING SHIFT -->      
                            
                            <!-- AFTERNOON SHIFT -->
                            <div class="row-fluid">  
                                
                                <div class="span12 panel">
                                
                                    <div class="widget" id="Shift_2" > 
                                    	<div class="widget-title">
                                            <div class="icon blue">
                                                <i class="icon20 i-sun-2"  ></i>
                                            </div>	
                                            <h4 id="ActivityLoader_2" >Afternoon Shift</h4>
                                            <a href="#" class="minimize" ></a>
                                        </div> 
                                        <!-- End .widget-title -->
                                        
                                        <div class="widget-content"> 
                            				<!-- TABLE LIST -->
                                            <div id="AfternoonTableList"  >
                                            
                                                <div style="max-width: 100% !important;" > 
                                                     <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable1" style="margin-bottom: 0px !important; " >
                                                        <thead>
                                                            <tr> 
                                                                <th class="left" > Report </th> 
                                                                <th class="center" width="16%" > Date Updated </th> 
                                                                <th class="center" width="12%" > Updated By </th>
                                                                <th class="center" width="13%" > Status </th> 
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                
                                                <!-- scroll -->   
                                                <div class="scroller" style="max-height: 250px; overflow:auto; margin-top: -1px; " >	  
                                                 
                                                    <div id="AfternoonScrollWrap"  style="display: block !important; position: relative !important; "  > 
                                                         <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                                                            <tbody id="ActivityList_2" class="dynamic-list" >
                                                                 
                                                            </tbody> 
                                                        </table>   
                                                    </div>  
                                                </div>
                                                <!-- END scroll -->
                                                
                                                <div style="margin-top: -1px;" > 
                                                     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable2" style="margin-bottom: 0px !important; border-top: 0px !important; " >
                                                        <tfoot>
                                                            <tr>
                                                                <th class="center" colspan="4" style="border-top: 0px !important;" >
                                                                    <?php
                                                                    if(can_post_shift_report())
                                                                    {
                                                                    ?>
                                                                    <button class="btn dropdown-toggle i-stack-plus btn-show-form pull-left" shift-id='2' >
                                                                        <span class="btn-text" >Add Report</span> &nbsp; 
                                                                    </button> 
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                
                                            </div>
                                            <!-- END TABLE LIST --> 
                                            
                            			</div> 
                                          
                                    </div>
                                     
                                </div>
                                 
                            </div>    
                            <!-- END AFTERNOON SHIFT -->
                            
                            <!-- NIGHT SHIFT -->
                            <div class="row-fluid">  
                                
                                <div class="span12 panel">
                                
                                    <div class="widget" id="Shift_3" > 
                                    	<div class="widget-title">
                                            <div class="icon blue">
                                                <i class="icon20 i-moon"  ></i>
                                            </div>	
                                            <h4 id="ActivityLoader_3" >Night Shift</h4>
                                            <a href="#" class="minimize" ></a>
                                        </div> 
                                        <!-- End .widget-title -->
                                        
                                        <div class="widget-content"> 
                            				<!-- TABLE LIST -->
                                            <div id="NightTableList"  >
                                            
                                                <div style="max-width: 100% !important;" > 
                                                     <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable3" style="margin-bottom: 0px !important; " >
                                                        <thead>
                                                            <tr> 
                                                                <th class="left" > Report </th> 
                                                                <th class="center" width="16%" > Date Updated </th> 
                                                                <th class="center" width="12%" > Updated By </th>
                                                                <th class="center" width="13%" > Status </th> 
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                
                                                <!-- scroll -->   
                                                <div class="scroller" style="max-height: 250px; overflow:auto; margin-top: -1px; " >	  
                                                 
                                                    <div id="NightScrollWrap" style="display: block !important; position: relative !important; "  > 
                                                         <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                                                            <tbody id="ActivityList_3"  class="dynamic-list" >
                                                             	     
                                                            </tbody> 
                                                        </table>   
                                                    </div>  
                                                </div>
                                                <!-- END scroll -->
                                                
                                                <div style="margin-top: -1px;" > 
                                                     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; border-top: 0px !important; " >
                                                        <tfoot>
                                                            <tr>
                                                                <th class="center" colspan="4" style="border-top: 0px !important;" > 
                                                                    <?php
                                                                    if(can_post_shift_report())
                                                                    {
                                                                    ?>
                                                                	<button class="btn dropdown-toggle i-stack-plus btn-show-form pull-left" shift-id='3' >
                                                                        <span class="btn-text" >Add Report</span> &nbsp; 
                                                                    </button>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                
                                            </div>
                                            <!-- END TABLE LIST --> 
                                            
                            			</div> 
                                          
                                    </div>
                                     
                                </div>
                                 
                            </div>    
                            <!-- END NIGHT SHIFT --> 
                             
                            </form> 
                           
                            <?php /*?><!-- pagination -->
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
                            <!-- end pagination --><?php */ ?>
                            
							<div class="form-actions"> 
                             	 
                                <?php if(allow_export_data()){ ?>
                                <!-- export button -->
                                <div class="btn-group dropup rfloat"> 
                                    <!--<button class="btn dropdown-toggle i-file-excel btn_export" data-toggle="dropdown" >-->
                                    <!--<button class="btn dropdown-toggle i-file-excel btn_export" data-toggle="dropdown" >
                                    Export  
                                    </button>--> 
                                    <a href="#CommonModal" title="export results" alt="export results" class="btn btn_export tip"  id="ExportBtn"  data-toggle="modal" >
                                    	<i class="icon16 i-file-excel" ></i> Export Report
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
<div class="modal fade checking-modal" id="Checking12BetModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-clipboard-4"></i>Shift Report</h4>
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
var found = 0; 
function getShiftReports(shift_id){ 
  
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		cache: false,  
		async: false, 
		url: "<?=base_url();?>shift_report/getShiftReports/"+shift_id,  
		beforeSend:function(){   
			//show loading  
			//$("#ActivityList_"+shift_id).find("tr.check_row").remove();	
			searchLoadingRight("show", $("#Shift_"+shift_id)) 
		},
		success:function(newdata){   
		  
			searchLoadingRight("hide", $("#Shift_"+shift_id));
			$("#ActivityList_"+shift_id).find("tr.check_row").remove(); 
			
			$("#ActivityList_"+shift_id).append(newdata.reportlist);  
			 
			//if(newdata.records > 0  )$(".btn_export").show();
			$(".btn_export").show();
			
			//dynamic-list check-row
			
			$(".tip").tooltip ({placement: 'top'});    
			
			//view details
			$("#ActivityList_"+shift_id).find('.report_details').click(function() { 
				var report_id = $(this).attr('report-id');     
				var shift_id = $(this).attr('shift-id'); 
				//var default_tab = $(this).attr('target');     
				if(report_id)loadAjaxContent("<?=base_url()?>shift_report/popupShiftReport/"+shift_id + "/" +report_id, $("#Checking12BetModal").find(".ajax_content"), "");
			}); 
			
			$(".scroller").getNiceScroll().resize();
			 
		}
		  
	}); //end ajax 
	   
}


function exportReports(){ 
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>shift_report/exportShiftReport",  
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
  

var verticalScroll = function(scroller) {
	scroller.niceScroll("",{
		cursoropacitymax: 0.8,
        cursorborderradius: 0,
        cursorwidth: "10px", 
		bouncescroll: false, 
		zindex: 999999, 
		autohidemode: true //true, cursor 
	});
}
 
$(function() { 	 
 	
	$('#search_form .dropdown-menu .advance-search').click(function(e) {
		e.stopPropagation(); 
	});
	 
	 
	$('#search_form')[0].reset(); 
	//$.uniform.update("input:checkbox[name=s_important]:checked");
	
	verticalScroll($('.scroller'));
	$(".scroller").mouseover(function() {
	  $(this).getNiceScroll().resize();
	});
	  
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
	   
	
	$('#UserModal').modal({ 
		show: false, 
		keyboard: true
	});
	   
	$('#Checking12BetModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();  
		  if(is_change == 1)$(".btn_search").trigger('click'); 
		  is_change = 0; //global 
	});  
  	 
	getShiftReports(1);
	getShiftReports(2);
	getShiftReports(3); 
	
	$(".btn_search").click(function(){    
		$("input[name=s_page]").val("");
		getShiftReports(1);
		getShiftReports(2);
		getShiftReports(3);
	});  
	
	$(".btn_export").click(function(){ 
		exportReports();
	});
	
	$(".btn-show-form").click(function(){ 
		var default_shift = $(this).attr("shift-id");  
		$("#Checking12BetModal").modal('show');  
		loadAjaxContent("<?=base_url()?>shift_report/popupShiftReport/" + default_shift, $("#Checking12BetModal").find(".ajax_content"));
	});
	 
	
	$(".btn-show-user").click(function(){   
		var type = $(this).attr("type");   
		//class_a = $(this).attr("user-type");  
		$(".btn-show-selected").find("span.btn-text").text($(this).text());   
		$("#hidden_autype").val(type); 
		
	}); 
	
	$(".btn-show-currency").click(function(){   
		var currency_id = $(this).attr("currency-id");   
		//class_a = $(this).attr("currency");  
		$(".btn-show-selected-currency").find("span.btn-text").text($(this).text());   
		$("#s_currency").val(currency_id); 
		
	}); 
	
	$(".btn-show-status").click(function(){   
		var status_id = $(this).attr("status-id");   
		//class_a = $(this).attr("currency");  
		$(".btn-show-selected-status").find("span.btn-text").text($(this).text());   
		$("#s_status").val(status_id); 
		
	});
	
	$('#search_form select').select2({placeholder: "Select"});  
	 
		 
});  

</script>
 