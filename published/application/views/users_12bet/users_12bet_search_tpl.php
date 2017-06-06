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
				<li><a href="#">12BET Users</a><span class="divider">/</span></li>
                <li class="active">Search</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-people"></i> 12BET Users</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget"> 
                    
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-search-2"></i>
							</div>
							<h4>Search 12BET Users</h4>
							<a href="#" class="minimize"></a>  
                            
                            <div class="w-right span5 "> 
                            <form id="search_form_user"  name="search_form_user" class="form-horizontal" method="post" onsubmit="return false;" autocomplete="off">
                            	<!--<button class="btn btn-primary  rfloat btn-search-user"  style="margin-top: -5px; "  > Search </button>-->
                                <input class="search-query form-control rfloat" type="hidden" id="s_username" name="s_username" placeholder="12BET Username" value="<?=$skeywords?>" >  									
                            </form>
                            </div>
 
                            <div class="clearfix"> </div> 
                            
						</div>
						<!-- End .widget-title --> 
                          
						<div class="widget-content" id="SearchResult" >  
                        	<!-- form -->    
                            <form id="activities_form" name="activities_form" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >   
                            <input type="hidden" value="" name="s_page" id="s_page"  />
                            <input type="hidden" name="hidden_ausername" id="hidden_ausername" value="" >
                            <input type="hidden" name="hidden_a12userid" id="hidden_a12userid" value="" >  
                                
                            <div id="UserInfo" class="search_info hide" >	 
                            
                                <div class="control-group" >  
                                    <div class="span4" > 
                                        <label class="control-label" for="user_name">Username</label> 
                                        <div class="controls"   >
                                            <label class="info-label highlight-detail" for="user_name" id="user_name" ></label>
                                        </div>
                                    </div>   
                                    
                                    <div class="span4" > 
                                        <label class="control-label" for="user_systemid">System ID</label> 
                                        <div class="controls controls-row"   >
                                            <label class="info-label" for="user_systemid"  id="user_systemid" ></label>
                                        </div>
                                    </div> 
                                    
                                    <div class="span4" > 
                                        <label class="control-label" for="user_currency">Currency</label> 
                                        <div class="controls"   >
                                            <label class="info-label" for="user_currency"  id="user_currency" ></label>
                                        </div>
                                    </div> 
                                     
                                </div>
                                <!-- End .control-group --> 
                                
                                <div class="control-group" >  
                                
                                </div>
                                <!-- End .control-group -->  
                              
                            </div>
                            
                            
                            <div class="search_info hide search_info_activities" > 
                            
                                <div id="UserActivitiesContainer" class="row-fluid" >
                                    <a href="#ActivityModal" class="btn btn_adduseractivity pull-right"  data-toggle="modal" target-form="bank_form" >
                                       <i class="icon16  i-stack-plus"></i>
                                       Add Activity
                                    </a>
                                    
                                    <ul id="myTab" class="nav nav-tabs">
                                        <li  ><a href="<?=base_url()?>banks/userActivities" data-target="#sbank_form" marker="sbank_form" data-toggle="stab" marker-user="bank_form" ><i class="icon14 i-office"></i> Bank</a></li>
                                        <li><a href="<?=base_url()?>promotions/userActivities" data-target="#spromotions_form" marker="spromotions_form" data-toggle="stab" marker-user="promotions_form"><i class="icon14 i-star-2"></i> Promotions</a></li>  
                                        <li><a href="<?=base_url()?>casino/userActivities" data-target="#scasino_form" marker="scasino_form" data-toggle="stab"  marker-user="casino_form"><i class="icon14 i-dice"></i> Casino</a></li> 
                                        <li><a href="<?=base_url()?>accounts/userActivities" data-target="#saccount_form" marker="saccount_form" data-toggle="stab"  marker-user="account_form" ><i class="icon14 i-vcard"></i> Account</a></li>
                                        <li><a href="<?=base_url()?>suggestions/userActivities" data-target="#ssuggestion_form" marker="ssuggestions_form" data-toggle="stab" marker-user="suggestions_form" ><i class="icon14 i-pencil-5"></i> Suggestions</a></li> 
                                        <li><a href="<?=base_url()?>access/userActivities" data-target="#saccess_form" marker="saccess_form" data-toggle="stab"  marker-user="access_form"><i class="icon14 i-key-2"></i> Access</a></li> 
                                          
                                    </ul>  
                                    
                                    
                                        
                                    <div class="tab-content" id="UserActivitiesContent" >
                                        <div class="tab-pane" id="sbank_form"></div>
                                        <div class="tab-pane" id="spromotions_form"></div>
                                        <div class="tab-pane" id="scasino_form"></div>
                                        <div class="tab-pane" id="saccount_form"></div>
                                        <div class="tab-pane" id="ssuggestions_form"></div>
                                        <div class="tab-pane" id="saccess_form"></div>
                                    </div>
                                    
                                </div> 
                                
                            </div>
                            </form> 
                            <!-- end form -->
                             
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
<div class="modal fade" id="ActivityDetailsModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" >
	
	<div class="modal-dialog" >
	  
	  <div class="modal-content"  >
		
		<div class="modal-header" >
		  <button type="button" class="close sugbtn" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"  ><i class="icon20 i-file-8"></i>Activity Details </h4> 
          <?php /*?><h4 class="modal-title"  ><i class="icon20 i-dice"></i>Casino Issue Details </h4><?php */?>
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
function get12betUserByName(){  
  
	$.ajax({ 
		data: $("#search_form_user").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>users_12bet/get12betUserByName",  
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
				$("#hidden_a12userid").val(newdata.user.UserID);
				$("#hidden_ausername").val(newdata.user.Username); 
				$("#user_name").text((newdata.user.Username)?newdata.user.Username:"_ _ _ _ _ _ _ _ _ _"); 
				$("#user_systemid").text((newdata.user.SystemID)?newdata.user.SystemID:"_ _ _ _ _ _ _ _ _ _"); 
				$("#user_currency").text((newdata.user.CurrencyName)?newdata.user.CurrencyName:"_ _ _ _ _ _ _ _ _ _"); 
				$(".search_info").show();
				$(".alert").remove();   
 
				if(newdata.can_view_act == 1)
				 {  
				 	$(".search_info_activities").show();    
					$('[data-toggle="stab"]:first').trigger("click");  
				 }
				else
				 {
					 $(".search_info_activities").hide();    
				 }
			 }
			else
			 {
				$(".search_info").hide();   
				$(".search_info_activities").hide();    
				$("#hidden_ausername").val(""); 
				$("#hidden_a12userid").val(""); 
				createMessage($("#SearchResult"),newdata.message, "error"); 
			 }
			    
			 
		}
			
	}); //end ajax
}

//ACTIVITY TAB
function userActivities(element, loadurl, container) { 
	 
	if (element.hasClass('disabled')) {
		return false;
	} 
		
	//clear  
	$("#SearchResult").find(".tab-pane").html("");  
	$("#UserActivitiesContainer").find(".tab-pane").removeClass("active"); 
	$('#UserActivitiesContainer .nav-tabs li').removeClass("active");
 	
	$(".select2-drop, .select2-drop-mask").hide();
	
	$.get(loadurl, function(data) {  
		$("#SearchResult #"+container).html(data); 
	});
	
	//change border 
	$("#UserActivitiesContainer").removeClass();//remove all class
	$("#UserActivitiesContainer").addClass(container); 
	
	$(".tab-content #"+container).addClass("active"); 
 
	$('.nav-tabs').find('[marker="'+container+'"]').closest("li").addClass("active");
	
	element.tab('show');
	if(container)disableCurrentTab(container);   
	//return false;
	
}
//END ACTIVITY TAB
 
$(function() { 	 
 	 
	$('#search_form_user')[0].reset(); 
	$.uniform.update("input:checkbox[name=s_important]:checked"); 
 	
	if($("#s_username").val())get12betUserByName();
	$(".btn-search-user").click(function(){    
		if($("#s_username").val())get12betUserByName(); 
	});  
	
	$("#search_form_user").submit(function( event ) { 
		if($("#s_username").val())get12betUserByName(); 
		event.preventDefault();	  
	});
	 
	 
   $('#ActivityModal, #ActivityStatusModal, #ActivityDetailsModal').modal({ 
		show: false, 
		keyboard: true
	});
	 
	 
	$('#ActivityModal, #ActivityStatusModal, #ActivityDetailsModal').on('hide.bs.modal', function (e) {     
		  $(".select2-drop, .select2-drop-mask").hide();  
		  if(is_change == 1)$('#UserActivitiesContainer .nav-tabs li.active [data-toggle="stab"]').trigger("click"); //click the active tab 
		  is_change = 0; //global 
	});	 
	 
	
	$('[data-toggle="stab"]').click(function(e) {   
		if ($(this).hasClass('disabled')) {
			return false;
		}   
		$("#s_page").val(''); 
		var target_form = $(this).attr("marker-user");  
		$('.btn_adduseractivity').attr("target-form", target_form); 
		userActivities($(this), $(this).attr('href'), $(this).attr('marker'));
		return false;  
	});
	
	//clicking add activity button 
	$('.btn_adduseractivity').click(function(e) { 
		clearActivityTab(); 	 
		$("html, body").animate({ scrollTop: 0 }, "slow"); 
		var target_form = $(this).attr("target-form");   
		$("#TabContainer").find('li [marker="'+target_form+'"]').trigger("click");  
	}); 
	   
	$('#search_form_user select').select2({placeholder: "Select"});  
	 
}); 
</script>

  

