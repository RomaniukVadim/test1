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
                <li class="active">Search</li>
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
                            
                            <div class="w-right span5 "> 
                            <form id="search_form_url"  name="search_form_url" class="form-horizontal" method="post" onsubmit="return false;" autocomplete="off">
                            	<button class="btn btn-primary  rfloat btn-search-url"  style="margin-top: -5px; "  > Search </button>
                                <input class="search-query form-control rfloat" type="text" id="s_url" name="s_url" placeholder="Enter URL" value="<?=$surl?>" >  
                            </form>
                            </div>
 
                            <div class="clearfix"> </div> 
                            
						</div>
						<!-- End .widget-title --> 
                         
						<div class="widget-content" id="SearchResult"   >  
                        	<form id="search_form"  name="search_form" class="form-horizontal search-form" method="post" onsubmit="return false;" autocomplete="off">
                            <input type="hidden" value="" name="s_page" id="s_page"  />
                            <input type="hidden" value="" name="s_urlid" id="s_urlid"  />     
                            <input type="hidden" value="" name="s_currencies" id="s_currencies"  />     
                            
                            <div id="UrlInfo" class="search_info hide" >	 
                            	
                                <div class="control-group" >  
                                	
                                    <div class="span6" > 
                                        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered " >
                                        	<tr> 
                                            	<th class="left" width="30%" >URL </th>  
                                                <td id="UrlName" ></td>
                                            </tr> 
                                        </table>
                                    </div>
                                
                                </div>
                                
                                <div class="control-group" >  
                                	
                                    <div class="span4" > 
                                        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered " >
                                        	<tr> 
                                            	<th class="left" >Status </th>  
                                                <td id="UrlStatusName" ></td>
                                            <tr> 
                                        </table>
                                    </div>  
                                    
                                    <div class="span4" > 
                                        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered " >
                                        	<tr> 
                                            	<th class="left" >Countries </th>  
                                                <td id="UrlCurrencies" ></td>
                                            </tr> 
                                        </table>
                                    </div>   
                                    
                                    <div class="span4" > 
                                        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered " >
                                        	<tr> 
                                            	<th class="left" >Blocked Countries </th>  
                                                <td id="UrlBlockedCurrencies" ></td>
                                            </tr> 
                                        </table>
                                    </div>   
                                    
                                </div>
                                <!-- End .control-group --> 
                                
                                <div class="control-group" >  
                                
                                </div>
                                <!-- End .control-group -->  
                              
                            </div>
                            
                            <div id="SuggestedUrl" class="search_info hide" >   
                            	<h3>Related URL:</h3>
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
                                          
                                    </tr> 
     
                                </thead>
                                
                                <tbody id="UrlList" class="dynamic-list" > 
                                    <tr class="result_row" >
                                        <td colspan="8" style="text-align: center;" ><img src="<?=base_url()?>media/images/loader.gif" /><br />Loading data from Server <br /></td>
                                    </tr>
                                     
                                </tbody> 
                                
                                <tfoot>
                                    <tr> 
                                        <th class="left" colspan="8" >
                                        
                                        </th> 
                                    </tr>
                                </tfoot>
                                </table> 
                            </div>
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
function getSearchBackupUrl(){  
	$.ajax({ 
		data: $("#search_form_url").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>backup_url/getSearchBackupUrl",  
		beforeSend:function(){   
			//show loading  
			$("#UserActivitiesContainer").find(".tab-pane").removeClass("active"); 
			$('#UserActivitiesContainer .nav-tabs li').removeClass("active");
	
			$("#UserActivitiesContent div.tab-pane").html(""); 
			$("#UserActivitiesContent div.active").removeClass("active"); 
			
			$("#hidden_ausername").val(""); 
			$(".search_info").hide(); 
			searchLoading("show"); 
		},
		success:function(newdata){     
			searchLoading("hide"); 
			 
			if(newdata.success > 0)
			 {   	
				$("#s_urlid").val(newdata.url.UrlID); 
				$("#s_currencies").val(newdata.url.Currencies);  
				$("#UrlName").text((newdata.url.Url)?newdata.url.Url:"_ _ _ _ _ _"); 
				$("#UrlStatusName").text((newdata.url.StatusName)?newdata.url.StatusName:"_ _ _ _ _ _"); 
				$("#UrlCurrencies").text((newdata.url.CurrencyNames)?newdata.url.CurrencyNames:"_ _ _ _ _ _"); 
				$("#UrlBlockedCurrencies").text((newdata.url.BlockedCurrencyNames)?newdata.url.BlockedCurrencyNames:"_ _ _ _ _ _"); 
				$(".search_info").show(); 
				$(".alert").remove();  
				getRelatedBackupUrl(); 
			 }
			else
			 {
				$(".search_info").hide();   
				$("#s_urlid").val(""); 
				createMessage($("#SearchResult"),newdata.message, "error"); 
			 }
			    
			 
		}
			
	}); //end ajax
}
 
function getRelatedBackupUrl(){ 
 	
	$.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>backup_url/getRelatedBackupUrl",  
		beforeSend:function(){   
			//show loading 
			searchLoading("show"); 
		},
		success:function(newdata){   
		 
			searchLoading("hide"); 
			$("#UrlList").find("tr.result_row").remove();
			$("#UrlList").append(newdata.urls);
		 
			//for pagination 
			/*$("#dataTable_info").html(newdata.pagination_string);
			$("#Pagination").html(newdata.pagination); 
			
			$("#Pagination li").each(function(index) { 
				if(!$(this).hasClass("active") && $(this).find("a").length > 0)
				 { 
					 $(this).find("a").addClass("pagination_link"); 
					 $(this).find("a").attr("page-num", $(this).find("a").attr("href").replace('/','')); 
				 } 
				$(this).find("a").removeAttr("href");
			});*/
 			
			
			$(".tip").tooltip ({placement: 'top'}); 
			 
		}
			
	}); //end ajax
}
 
$(function() { 	 
 	
	$('#search_form_url')[0].reset(); 
	$('#search_form')[0].reset();  
	
	$.uniform.update("input:checkbox[name=s_important]:checked");
	
	if($("#s_url").val())getSearchBackupUrl();
	$(".btn-search-url").click(function(){    
		if($("#s_username").val())get12betUserByName(); 
	});  
	
	$("#search_form_url").submit(function( event ) { 
		if($("#s_url").val())getSearchBackupUrl(); 
		event.preventDefault();	  
	});
	 
	   
   $('#UserModal, #PageModal').on('hide.bs.modal', function (e) {     
	   $(".select2-drop, .select2-drop-mask").hide();  
	   if(is_change == 1)getBackupUrlList(); 
	   is_change = 0; //global 
	});		 
	
	$('#UserModal, UploadUrl').modal({ 
		show: false, 
		keyboard: true
	});	 
 
	
	$('#search_form select').select2({placeholder: "Select"});   
	 
	 
}); 
</script>

  

