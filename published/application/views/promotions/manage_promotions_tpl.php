<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 

<script  type="text/javascript">
var is_change = 0; 
</script>

<style>
.tab-pane {
	/*display: none; 	*/
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
                <li class="active">Manage Promotions</li>
			</ul>  
		</div> 
         
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-star-2"></i> Promotions</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget"> 
                    
						<div class="widget-title">
							<div class="icon">
								<i class="icon20  i-gift"></i>
							</div>
							<h4>Promotions List</h4>
							<a href="#" class="minimize"></a>  
						</div>
						<!-- End .widget-title --> 
                        
                         <?=$server?>
						<div class="widget-content" >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            <input type="hidden" value="" name="s_page" id="s_page"  /> 
                            <input type="hidden" value="<?=$dcategory?>" name="s_category" id="s_category"  /> 
                            
                            <div class="row-fluid"  > 
                            	 
                                
                                <!-- span12 -->
                                <div class="span12" >  
                                      
                                    <!-- Advance Search -->
                                     <div class="btn-group call-search pull-right margin-right-10">
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon16 i-cogs"></i>
                                            Advance Search
                                            <span class="caret"></span>
                                        </button>
                                        
                                        <div class="dropdown-menu  opensright daterangepicker" > 
                                            <!-- advance-search menu--> 
                                            <div class="advance-search"  >   
                                                
                                                <div class="control-group">
                                                    <div class="span6" >
                                                        <label class="control-label" for="s_product">Product</label>
                                                        <div class="controls controls-row"  >
                                                            <select name="s_product" class="select2"  id="s_product" >
                                                                <optgroup label="" >     
                                                                    <option value="" >- All -</option>
                                                                    <?php   
                                                                    foreach($products as $row=>$product) {
                                                                    ?>
                                                                    <option value="<?=$product->ProductID?>" ><?=$product->ProductName?></option>
                                                                    <?php	
                                                                    }//end foreach
                                                                    ?> 
                                                                </optgroup>  
                                                            </select>  
                                                        </div>    
                                                    </div>
                                                    
                                                    <div class="span6" >   
                                                        <label class="control-label" for="s_subproduct">Sub Product</label>
                                                        <div class="controls controls-row"  >
                                                            <select name="s_subproduct" id="s_subproduct" class="required  select2 span12" disabled="disabled" > 
                                                                <optgroup label="Select Sub Product"> 
                                                                    <option value="" ></option> 
                                                                </optgroup>  
                                                                
                                                            </select>   
                                                        </div>   
                                                          
                                                    </div>
                                                </div>
                                                <!-- End .control-group -->
                                                
                                                <div class="control-group">
                                                    <div class="span6" >
                                                        <label class="control-label" for="s_foruser">For User</label>
                                                        <div class="controls controls-row"  >
                                                            <select class="select2 myselect span12" name="s_foruser" >
                                                                <optgroup label="" >    
                                                                    <option value="" >- All -</option> 
                                                                    <option value="1" >CSA</option> 
                                                                    <option value="10" >CRM</option>  
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
                                <!-- span12 -->
                                 
                                 
                                
                            </div>
                            <!-- end more search -->
                            
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable">
							<thead>
                                <tr>  
                                    <th class="center" width="11%" >
                                        Code
                                    </th>
                                    
                                    <th class="left" width="19%" >
                                        Name
                                    </th>  
                                    
                                    <th class="center"  >
                                        Currency
                                    </th>
                                    
                                    <th class="center"  >
                                        Category
                                    </th>
                                    
                                    <th class="center" width="8%" >
                                        Product
                                    </th>
                                    
                                    <th class="cener"  >
                                        Started Date
                                    </th>
                                    
                                    <th class="center"  >
                                        End Date
                                    </th>
                                     
                                    <th class="center"  >
                                        Last Updated
                                    </th>
                                    
                                    <th class="center" width="6%" >
                                        Updated By
                                    </th>  
                                    
                                    <th class="center" width="8%" >
                                        Status
                                    </th>
                                    
                                    <th class="center" width="7%" >
                                        Action 
                                    </th> 
                                     
                                </tr> 
 
							</thead>
							
                            <tbody id="PromotionList" class="dynamic-list"  > 
                            	<tr id="SearchRow"  >  
                                    <td>
                                       <input class="text_filter" name="s_bonuscode" type="text" rel="1" value=""  >
                                    </td>
                                    
                                    <td>
                                       <input class="text_filter" name="s_name" type="text" rel="1" value=""  >
                                    </td>
                                    
                                    <td>
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
                                    
                                    <td>
                                    	<select name="s_category" class="select2" >
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
                                    </td>
									
                                    <td class="center" >
                                       
                                    </td>
                                     
                                    <td class="center" ></td> 
                                    <td class="center" ></td> 
                                    <td class="center" ></td> 
                                    
                                    <td class="center" > 
                                    	<?php /*?><input class="text_filter" name="s_id" type="text" rel="1" value=""> <?php */?>
                                    </td>  
                                    
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
                                    <td colspan="10" style="text-align: center;" ><img src="<?=base_url()?>media/images/loader.gif" /><br />Loading data from Server <br /></td>
                                </tr>
                                
                            </tbody> 
                            
							<tfoot>
                                <tr> 
                                    <th class="left" colspan="11" >
                                    
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
                            	<a href="#PromotionModal" class="btn btn_addpromotion"  data-toggle="modal" target-form="access_form" >
                                    <i class="icon16  i-stack-plus"></i>
                                    Add Promotion
                                </a>   
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

 

<!-- DEPOSIT METHOD MODAL -->
<div class="modal fade" id="PromotionModal"  role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" tab-index="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-gift"></i><span>Manage Promotions</span></h4>
		</div>
		
		<!-- tab and content -->
        <div style="padding: 20px 20px 20px 20px;" class="ajax_content"  > 
              
        </div>
        <!-- end tab and content -->
		  
		
	  </div><!-- /.modal-content -->
	
	</div><!-- /.modal-dialog --> 
     
</div>
<!-- END DEPOSIT METHOD MODAL -->
 

<script> 
function getPromotionsList(){ 
 	
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>manage_promotions/getPromotionsList",  
		beforeSend:function(){   
			//show loading
			searchLoading("show");
		},
		success:function(newdata){   
			//alert(JSON.stringify(promotions));
			searchLoading("hide");
			//$("#PromotionList").find("tr.activity_row").remove();
			$("#PromotionList").append(newdata.promotions);
			
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
				getPromotionsList(); 
			});
			//end pagination 
			
			//edit_method
			$('.activity_row .edit_promotion').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var promotion_id = $(this).attr('promotion-id');    
				if(promotion_id)loadAjaxContent("<?=base_url()?>manage_promotions/popupManagePromotion/"+promotion_id, $("#PromotionModal").find(".ajax_content"));  
			});
			  
			 
		}
			
	}); //end ajax
}

 
$(function() { 	 
 	
	$('#search_form .dropdown-menu .advance-search').click(function(e) {
		e.stopPropagation(); 
	});
	 
	$('#search_form')[0].reset(); 
	$.uniform.update("input:checkbox[name=s_important]:checked");
	 
	getPromotionsList();
	$(".btn_search").click(function(){   
		$("input[name=s_page]").val("");
		getPromotionsList(); 
	}); 
	
	$('#search_form select').select2({placeholder: "Select"}); 
	
	//clicking add method button 
	$('.btn_addpromotion').click(function(e) {  
		$("html, body").animate({ scrollTop: 0 }, "slow");  
		loadAjaxContent("<?=base_url()?>manage_promotions/popupManagePromotion/", $("#PromotionModal").find(".ajax_content")); 
	});
	 
	$('#PromotionModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();  
		  if(is_change == 1)getPromotionsList();  
		  is_change = 0; //global 
	}); 
	
	$("#s_product").change(function(){  
		var product = ($("#s_product").val())?$("#s_product").val():"";  
		//var category = ($("#act_category").val())?$("#act_category").val():"";
		//$("#hidden_aproduct").val($(this).find(":selected").text());
		changeProduct("<?=base_url()?>manage_promotions/getSubProductList", product, '<?=$promotion->SubProductID?>', $("select[name=s_subproduct]"), '');  
	});
	$("#pro_product").trigger("change");	 
	 
}); 
</script>

  

