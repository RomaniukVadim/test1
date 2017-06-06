<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
  
<script src="<?=base_url();?>media/js/plugins/forms/pages/jquery.formatCurrency-1.4.0.min.js"></script> 

<script  type="text/javascript">
var is_change = 0; 
</script>

<div class="main">
	
    <?=$sidebar_view;?>
    
	<section id="content" >
	<div class="wrapper" >
		
        <div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Banks</a><span class="divider">/</span></li>
                  <li class="active">Analysis Reasons</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-office"></i> Banks</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-chart"></i>
							</div>
							<h4>Analysis Reasons</h4>
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                         <?=$server?>
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            <input type="hidden" value="" name="s_page" id="s_page"  />  
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable">
							<thead>
                                <tr> 
                                    <th class="center" width="8%" >
                                        Reason ID
                                    </th>  
                                    
                                    <th class="left" width="20%" >
                                        Reason
                                    </th>  
                                    
                                    <th class="center" width="10%" >
                                        Category
                                    </th>  
                                    
                                    <th class="center" width="10%" >
                                        Type
                                    </th> 
                                     
                                    <th class="center" width="12%" >
                                        Date Last Updated
                                    </th>
                                    
                                    <th class="center" width="10%" >
                                        Status
                                    </th>
                                    
                                    <th class="center" width="8%" >
                                        Action 
                                    </th> 
                                     
                                </tr> 
 
							</thead>
							
                            <tbody id="ReasonsList" class="dynamic-list"> 
                            	<tr id="SearchRow"  > 
                                    <td class="center" > 
                                    	<input class="text_filter" name="s_id" type="text" rel="1" value=""> 
                                    </td>  
                                    
                                    <td class="left" >
                                       <input class="text_filter left" name="s_name" type="text" rel="1" value="">
                                    </td>
                                    
                                    <td class="center" >  
                                         <select name="s_category" class="select2"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                            	<?php   
												foreach($categories as $row=>$category) {
												?>
                                                <option value="<?=$category->CategoryID?>" ><?=ucwords($category->CategoryName)?></option>
                                                <?php	
												}//end foreach
												?> 
                                            </optgroup>  
                                        </select>   
                                    </td> 
                                    <td class="center" >  
                                    	
                                         <select name="s_type" class="select2"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                            	<?php   
												foreach($types as $row=>$type) {
												?>
                                                <option value="<?=$type[Value]?>" ><?=ucwords($type[Label])?></option>
                                                <?php	
												}//end foreach
												?> 
                                            </optgroup>  
                                        </select>   
                                    </td> 
                                     
                                    <td class="center" ></td> 
                                    
                                    <td class="center" >   
                                         <select name="s_status" class="select2"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                            	<?php   
												foreach($status_list as $row=>$status) {
												?>
                                                <option value="<?=$status[Value]?>" ><?=ucwords($status[Label])?></option>
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
                                
                            	<tr class="activity_row" >
                                    <td colspan="7" style="text-align: center;" ><img src="<?=base_url()?>media/images/loader.gif" /><br />Loading data from Server <br /></td>
                                </tr>
                                
                                 
                                
                            </tbody> 
                            
							<tfoot>
                                <tr> 
                                    <th class="left" colspan="7" >
                                    
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
                                	<div class="dataTables_paginate paging_bootstrap pagination" id="Pagination" >
                                    	<?=$pagination?>
                                    </div> 
                                </div>
                            </div>
                            <!-- end pagination -->
                            
							<div class="form-actions"> 
                            	<!--<a data-toggle="modal" href="#example" class="btn btn-primary btn-large"> - See more at: http://www.w3resource.com/twitter-bootstrap/modals-tutorial.php#sthash.JrP7FH9r.dpuf-->
                            	<a href="#ReasonModal" class="btn btn_addreason"  data-toggle="modal" target-form="bank_form" >
                                    <i class="icon16  i-stack-plus"></i>
                                    Add Reason
                                </a>  
                                
                                <?php if(allow_export_data()){ ?>
                                <!-- export button -->
                               <?php /*?> <div class="btn-group dropup rfloat">  
                                    <button class="btn dropdown-toggle i-file-excel btn_export" data-toggle="dropdown" >
                                    Export &nbsp 
                                    </button>
                                </div>  <?php */?>
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

<!-- DEPOSIT METHOD MODAL -->
<div class="modal fade" id="ReasonModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-chart"></i><span>Manage Analysis Reason</span></h4>
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
<!-- END DEPOSIT METHOD MODAL -->
 

<script> 
var dcategory = "<?=$dcategory?>";
function getAnalysisReasons(){ 
 	
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>analysis_reasons/getAnalysisReasons",  
		beforeSend:function(){   
			//show loading
			searchLoading("show");
		},
		success:function(newdata){    
			searchLoading("hide");
			//alert(JSON.stringify(newdata));
			$("#ReasonsList").find("tr.activity_row").remove();
			$("#ReasonsList").append(newdata.reasons);
			
			//for pagination 
			$("#dataTable_info").html(newdata.pagination_string);
			$("#Pagination").html(newdata.pagination);
			$("#Pagination li").each(function(index) { 
				if(!$(this).hasClass("active") && $(this).find("a").length > 0)
				 { 
					 $(this).find("a").addClass("pagination_link"); 
					 $(this).find("a").attr("page-num", $(this).find("a").attr("href").replace('/','')); 
				 } 
				$(this).find("a").removeAttr("href");
			});
 			
			
			$(".tip").tooltip ({placement: 'top'});
			
			$(".pagination_link").click(function(){ 
				$("input[name=s_page]").val($(this).attr("page-num")); 
				getAnalysisReasons(); 
			});
			//end pagination 
			
			//edit_method
			$('.activity_row .edit_reason').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var reason_id = $(this).attr('reason-id'); 
				if(reason_id)loadAjaxContent("<?=base_url()?>analysis_reasons/popupManageReason/"+reason_id, $("#ReasonModal").find(".ajax_content"));  
			});
			  
			 
		}
			
	}); //end ajax
}

 
$(function() { 	 
 	 
	$('#search_form')[0].reset(); 
	$.uniform.update("input:checkbox[name=s_important]:checked");
	 
	getAnalysisReasons();
	$(".btn_search").click(function(){   
		$("input[name=s_page]").val("");
		getAnalysisReasons(); 
	}); 
	
	$('#search_form select').select2({placeholder: "Select"}); 
	
	//clicking add method button 
	$('.btn_addreason').click(function(e) {  
		$("html, body").animate({ scrollTop: 0 }, "slow");  
		loadAjaxContent("<?=base_url()?>analysis_reasons/popupManageReason/"+dcategory, $("#ReasonModal").find(".ajax_content")); 
	});
	 
	$('#ReasonModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();  
		  if(is_change == 1)getAnalysisReasons(); 
		  is_change = 0; //global 
	});   
	
	$('#ReasonModal').modal({ 
		show: false, 
		keyboard: false
	});	
	 
}); 
</script>

  

