<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 

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
				<li><a href="#">Backup URL</a><span class="divider">/</span></li>
                <li class="active">List</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-link-2"></i> Backup URL</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget"> 
                    
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-link-5"></i>
							</div>
							<h4>Search</h4>
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
                                    <th class="left"  >
                                        URL
                                    </th> 
                                    
                                    <th class="center" width="18%" >
                                        Countries
                                    </th> 
                                    
                                    <th class="center" width="18%" >
                                        Blocked Countries
                                    </th> 
                                        
                                    <th class="center" width="10%" >
                                        Status
                                    </th>
                                     
                                    <th class="center" width="12%" >
                                        Action 
                                    </th> 
                                     
                                </tr> 
 
							</thead>
							
                            <tbody id="UrlList" class="dynamic-list" > 
                            	<tr id="SearchRow"  >  
                                    <td class="left" > 
                                    	<input class="text_filter" name="s_url" type="text" rel="1" value="" style="text-align: left; " > 
                                    </td>  
                                    
                                    <td class="center" >
                                       <select name="s_currency" class="select2"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                            	<?php
												foreach($currencies as $row=>$currency) {
												?>
                                                <option value="<?=$currency->CurrencyID?>" <?=($sdata[s_currency]==$currency->CurrencyID)?"selected='selected'":""?> ><?=$currency->Abbreviation?></option>
                                                <?php	
												}//end foreach
												?> 
                                                
                                            </optgroup>  
                                        </select>
                                    </td>  
                                    
                                    <td class="center" >  
                                    	<select name="s_blocked" class="select2"  >
                                            <optgroup label="" >    
                                            	<option value="" >- All -</option>
                                            	<?php
												foreach($currencies as $row=>$currency) {
												?>
                                                <option value="<?=$currency->CurrencyID?>" <?=($sdata[s_blocked]==$currency->CurrencyID)?"selected='selected'":""?> ><?=$currency->Abbreviation?></option>
                                                <?php	
												}//end foreach
												?> 
                                                
                                            </optgroup>  
                                        </select>      
                                    </td> 
                                    
                                    <td class="center" >  
                                     	<?php /*?><select name="s_status" class="select2"  >
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
                                        </select> <?php */?>     
                                    </td> 
                                     
                                    <td class="center" >
                                    	<button class="btn btn-primary btn_search " type="button"> 
                                            Search
                                        </button>       
                                    </td>
                                     
                                </tr> 
                                
                            	<tr class="result_row" >
                                    <td colspan="8" style="text-align: center;" ><!--<img src="<?=base_url()?>media/images/loader.gif" />-->Enter URL to search <br /></td>
                                </tr>
                                
                                 
                                
                            </tbody> 
                            
							<tfoot>
                                <tr> 
                                    <th class="left" colspan="8" >
                                    
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
<div class="modal fade" id="UserModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog"  >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-link-2"></i><span>Manage Backup Url</span></h4>
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
var selected_url =  "";
function getSearchBackupUrlList(){ 
 	var url = $("input[name=s_url]").val();    
	 		
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>backup_url/getSearchBackupUrlList",  
		beforeSend:function(){   
			//show loading 
			searchLoading("show");  
			if(url == "")
			 {
				 searchLoading("hide"); 
				 $("#UrlList").find("tr.result_row").remove();
				 $("#UrlList").append("<tr class=\"result_row\" ><td colspan=\"5\" style=\"text-align: center;\" >Enter URL to search <br /></td></tr>");  
				 $("#dataTable_info").html("");
				 $("#Pagination").html("");  
				 return false; 
			 }
			else
			 {
				var x = isValidUrl(url);      
				searchLoading("hide"); 
				if(x == false)
				 {
					 $("#UrlList").find("tr.result_row").remove();
					 $("#UrlList").append("<tr class=\"result_row\" ><td colspan=\"5\" style=\"text-align: center;\" >Enter correct URL without subdirectory <br /></td></tr>");  
					 $("#dataTable_info").html("");
					 $("#Pagination").html("");  
					 return false;  
				 }
			 }
			 
		},
		success:function(newdata){   
			//alert(JSON.stringify(newdata)); 
			searchLoading("hide"); 
			$("#UrlList").find("tr.result_row").remove();
			$("#UrlList").append(newdata.status);
			
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
				getSearchBackupUrlList(); 
			});
			//end pagination 
			
			//edit_method 
			
			$('.url_row .edit_url').click(function() {   
				$("html, body").animate({ scrollTop: 0 }, "slow"); 
				var url_id = $(this).attr('url-id');     
				if(url_id)loadAjaxContent("<?=base_url()?>backup_url/popupManageBackupUrl/"+url_id, $("#UserModal").find(".ajax_content"));    
			}); 
			
			$("input:checkbox[name='check_url[]']").click(function(){ 
				 
				 selected_url = $("input[name='check_url[]']:checked").map(function() {return this.value;}).get().join(',');  
			   
				 if(selected_url != "")
				  {
					 $(".btn-update-selected").removeClass("disabled"); 
					 $(".btn-update-selected").removeAttr("disabled", "disabled"); 
				  }
				 else
				  {
					 $(".btn-update-selected").addClass("disabled");  
					 $(".btn-update-selected").attr("disabled", "disabled");
				  }
				  
				  if(!$(this).is(':checked')) 
				  { 
					 $("input:checkbox[name=checkbox_all]").removeAttr("checked"); 
					 $.uniform.update("input:checkbox[name=checkbox_all]");
				  }
				  
				  $("div.alert").remove();
				  
			  }); 
			  $('input[type=checkbox]').uniform();
			  
			 
		}
			
	}); //end ajax
}
 
function isValidUrl(str) {
  /*var pattern = new RegExp('^(https?:\/\/)?'+'((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|'+'((\d{1,3}\.){3}\d{1,3}))'+'(\:\d+)?(\/[-a-z\d%_.~+]*)*'+'(\?[;&a-z\d%_.~+=-]*)?'+'(\#[-a-z\d_]*)?$','i'); // fragment locater
  if(!pattern.test(str)) { 
    return false;
  } else {
    return true;
  }*/  
  //var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
  var regexp = /^(?:(ftp|http|https):\/\/)?(?:[\w-]+\.)+[a-z]{3,6}\/?$/;
  return regexp.test(str);
	  
}
 
$(function() { 	 
 	 
	$('#search_form')[0].reset(); 
	$.uniform.update("input:checkbox[name=s_important]:checked");
	 
	//getSearchBackupUrlList();
	$(".btn_search").click(function(){    
		$("input[name=s_page]").val("");
		getSearchBackupUrlList(); 
	}); 
	   
   $('#UserModal, #PageModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();  
		  if(is_change == 1)getSearchBackupUrlList(); 
		  is_change = 0; //global 
	});		 
	
	$('#UserModal, UploadUrl').modal({ 
		show: false, 
		keyboard: true
	});	 
	
	//clicking add method button 
	$('.btn-addurl').click(function(e) {  
		$("#UserModal").modal('show');
		$("html, body").animate({ scrollTop: 0 }, "slow");  
		loadAjaxContent("<?=base_url()?>backup_url/popupManageBackupUrl/", $("#UserModal").find(".ajax_content")); 
	});
	
	$('.btn-show-upload').click(function(e) {  
		$("#PageModal").modal('show');
		$("html, body").animate({ scrollTop: 0 }, "slow");  
		loadAjaxContent("<?=base_url()?>backup_url/popupUploadBackupUrl/", $("#PageModal").find(".ajax_content")); 
	});
	
	//check all checkbox
	$("#CheckboxAll").click(function () {  
        if ($("#CheckboxAll").is(':checked')) {
            $("input:checkbox[name='check_url[]']").prop("checked", true); 
        } else {
            $("input:checkbox[name='check_url[]']").prop("checked", false); 
        }
		//selected_url = $('input[name="\'check_url[]\'"]:checked').map(function() {return this.value;}).get().join(',');  
		selected_url = $("input[name='check_url[]']:checked").map(function() {return this.value;}).get().join(','); 
		
		if(selected_url != "")
		  {
			 $(".btn-update-selected").removeClass("disabled"); 
			 $(".btn-update-selected").removeAttr("disabled", "disabled"); 
		  }
		 else
		  {
			 $(".btn-update-selected").addClass("disabled");  
			 $(".btn-update-selected").attr("disabled", "disabled");
		  }
		  
		$.uniform.update("input[type=checkbox]"); 
		$("div.alert").remove();
			
    });
	
	$('.status-selected').click(function(e) {  
		 var status = $(this).attr("status-id");  
		 if(status)
		  {
			$("#s_updatestatus").val(status);   
			selected_url = $("input[name='check_url[]']:checked").map(function() {return this.value;}).get().join(',');   
			var status_name = $(this).text(); 
			if(selected_url)
			 {  
				createMessage("", "Are you sure to update the status of the selected URL(s) to <b>"+status_name+"</b> ?", "confirm", function(){
						 updateBackupUrlStatus();
				 });  
			 } 
			 
		  }
		 else
		  {
			$("#s_updatestatus").val("");  
		  }
		 
	});
	
	
	$('#search_form select').select2({placeholder: "Select"});   
	 
	 
}); 
</script>

  

