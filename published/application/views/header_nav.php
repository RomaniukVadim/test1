<header id="header" class="navbar navbar-inverse navbar-fixed-top" >
<div class="navbar-inner">
	<div class="container-fluid"> 
    
		<a class="brand tip" href="<?=base_url()."dashboard";?>" title="Customer Activities Log" >
        	<span class="compname"  > 
                <span ><i class="icon20 i-phone-6"></i>ustOmer </span>  
                <span class="separator" >|</span> 
             	<span class="text" ><div>Activity <br />Log</div></span> 
            </span>    
        </a>
		<div class="nav-no-collapse">
			
            <!-- head search -->
            <div id="top-search">
				<form action="#" method="post" class="form-search" name="form_search" id="form_search" >
					<div class="input-append">
						<input type="text" name="tsearch" id="tsearch" placeholder="12BET username ..." class="search-query" value="<?=($sdata[s_dashboard]!=1)?trim(urldecode($skeywords)):"";?>" >
                        <button type="submit" class="btn"><i class="icon16 i-search-2 gap-right0"></i></button>
					</div>
				</form>
                <script>
				$(function(){     
					var page_ajax =  "<?=$page_ajax?>";
					$("#form_search").submit(function( event ) { 
						var keywords = $("#tsearch").val();   
						$("#s_username").val(keywords);
						if(page_ajax == 1)
						 {
							 $("#search_form_user").submit(); 
						 }
						else
						 {
							//if(keywords)window.location = "<?=base_url()?>search-activities/"+encodeURIComponent(keywords);    	
							if(keywords)window.location = "<?=base_url()?>12bet-users/search/"+encodeURIComponent(keywords); 
						 }
						 event.preventDefault();	 
						  
					});
					
					$("#tsearch").change(function(){  
						$("#s_username").val($(this).val());
					}); 

				});
				</script>
			</div>
            <!-- head search -->
            
            <!-- top menu -->
			<ul class="nav pull-right"> 
            	
                <li class="divider-vertical for_notification"></li>
                
                <!-- notifications -->
                <?php /*?><li class="dropdown for_notification" id="HeaderNotification" >
                	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    	<i class="icon24 i-bell-2"></i><span class="notification red counter" >0</span>
                    </a> 
                    <!--<div class="scroll" style="height:200px; overflow:auto; margin-top:80px; z-index: 999999;"  ></div>-->
                    <ul class="dropdown-menu" >
                        <li class="" ><a href="#" class=""><i class="icon16 i-calendar-2"></i> Notice created dasfasf</a></li> 
                    </ul>
                     
				</li><?php */?>
                <!-- end notifications -->
                  
                    
                <!-- profile --> 
				<li class="dropdown"><a href="#" class="dropdown-toggle notification_toggle" data-toggle="dropdown"><i class="icon24 i-bell-2"></i><span class="notification red global_notice_counter hidden" >0</span></a>
        			<ul  class="dropdown-menu " >
                    	<li> 
                        	<div class="scroll-menu"  style="max-height: 280px; overflow:auto; z-index: 999999; display: block; width: 250px;  "   >    
                                 
                                <div class="li-menu" >
                               		<ul class="dropdown-menu-child" id="notice_panel_container"  > 
                                        <?php /*?><li class="" ><a href="#" class=""><i class="icon16 i-info"></i> Intranet notice </a></li>
                                        <li class="" ><a href="#" class=""><i class="icon16 i-file"></i> Intranet request</a></li> 
                                        <li class="" ><a href="#" class=""><i class="icon16 i-brain"></i> CSD Portal</a></li>
                                        <li class="" ><a href="#" class=""><i class="icon16 i-calendar-5"></i> Calendar notice</a></li>
                                        <li class="" ><a href="#" class=""><i class="icon16 i-bus"></i> Transportation</a></li>  <?php */?>	                         
                                    </ul>  	 
                                </div>
                            	 
                            </div>
                        	  
             		 	</li>
                    
                    </ul>
                    
				</li> 
                <!-- end profile -->
                
                
                
                
				<?php /*?><li class="divider-vertical"></li> <?php */?>
                <!-- inbox -->
				<?php /*?><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon24 i-envelop-2"></i><span class="notification red">434</span></a>
				<ul class="dropdown-menu messages">
					<li class="head">
					<h4>Inbox</h4>
					<span class="count">3 messages</span><span class="new-msg"><a href="#" class="tipB" title="Write message"><i class="icon16 i-pencil-5"></i></a></span></li>
					<li><a href="#" class="clearfix"><span class="avatar"><img src="images/avatars/peter.jpg" alt="avatar"></span><span class="msg">Call me i need to talk with you</span><button class="btn close"><i class="icon12 i-close-2"></i></button></a></li>
					<li><a href="#" class="clearfix"><span class="avatar"><img src="images/avatars/milen.jpg" alt="avatar"></span><span class="msg">Problem with registration</span><button class="btn close"><i class="icon12 i-close-2"></i></button></a></li>
					<li><a href="#" class="clearfix"><span class="avatar"><img src="images/avatars/anonime.jpg" alt="avatar"></span><span class="msg">I have question about ...</span><button class="btn close"><i class="icon12 i-close-2"></i></button></a></li>
					<li class="foot"><a href="email.html">View all messages</a></li>
				</ul>
				</li> <?php */?>
                <!-- end inbox -->
                
                 <!-- inbox -->
                <?php /*?><li class="divider-vertical"></li> 
				<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" id="chat-notifier"><i class="icon24 i-envelop-2"></i><span class="notification red hidden"></span></a>
                </li><?php */?>
                
                <li class="divider-vertical"></li>
                <li class="dropdown"><a href="#"   id="chat-notifier"><i class="icon24 i-envelop-2"></i><span class="notification red hidden" >0</span></a>
				</li> 
                
                
				<li class="divider-vertical"></li>  
                <!-- profile -->
                <?php
				$profile_img = ($this->session->userdata('mb_profilepic') && file_exists("media/uploads/cache/".$this->session->userdata('mb_profilepic')))?"media/uploads/cache/".$this->session->userdata('mb_profilepic'):""; 
				if($profile_img == "")
				 {
					 $profile_img = ($this->session->userdata('mb_profilepic') && file_exists("media/uploads/".$this->session->userdata('mb_profilepic')))?"media/uploads/".$this->session->userdata('mb_profilepic'):"media/images/avatar-male.jpg";
				 }
				?>
				<li class="dropdown user"><a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><img src="<?=base_url().$profile_img;?>" alt="profile"  class="primary-photo" ><span class="more"><i class="icon16 i-arrow-down-2"></i></span></a>
				<ul class="dropdown-menu">
                	<li>
                    	<br />
                        <i class="icon16 "> &nbsp;&nbsp;&nbsp;</i>Color schemes:
                        <ul id="color-schemes">
                        	<li>
                            	<span class="darkgrey-scheme tip scheme-option" title="Dark Grey" scheme="DarkgreyScheme" ></span>
                            </li>
                            <li>
                            	<span class="brownish-scheme tip scheme-option" title="Brownish" scheme="BrownishScheme" ></span>
                            </li> 
                            <li>
                            	<span class="lightgrey-scheme tip scheme-option" title="Light Grey" scheme="" ></span>
                            </li>
                            <li>
                            	<span class="cyan-scheme tip scheme-option" title="Cyan" scheme="CyanScheme" ></span>
                            </li>
                            <li>
                            	<span class="red-scheme tip scheme-option" title="Red" scheme="RedScheme" ></span>
                            </li>
                            <li>
                            	<span class="orange-scheme tip scheme-option" title="Orange" scheme="OrangeScheme" ></span>
                            </li>
                            <li>
                            	<span class="green-scheme tip scheme-option" title="Green" scheme="GreenScheme" ></span>
                            </li>
                            <li>
                            	<span class="amethyst-scheme tip scheme-option" title="Amethyst" scheme="AmethystScheme" ></span>
                            </li>
                        </ul>
                    </li>

					<li class="disabled" ><a href="#" class="disabled"><i class="icon16 i-happy disabled"></i> Hi <?=ucwords($this->session->userdata("mb_nick"));?>!</a></li>
                    <!--<li class="disabled" ><a href="#" class="disabled"><i class="icon16 i-cogs disabled"></i> Settings</a></li>-->
					<!--<li><a href="<?=base_url()."manage-profile";?>" class=""><i class="icon16 i-user"></i> Profile</a></li> -->
                <?php /*?><?php 
				if(dual_view())
				 {
					 if($this->session->userdata('mb_pageview') == "checkers")
					  {
				?>
                	<li><a href="#" class="change_view" page-view="approvers" ><i class="icon16 i-eye-7"></i> Approvers View</a></li>
                <?php		  
					  }
					 else
					  {
				?>
                	<li><a href="#" class="change_view" page-view="checkers" ><i class="icon16  i-checkmark-4"></i> Checkers View</a></li>
                <?php		  
					  }
				?>
                
                <?php	 
				 }
				?><?php */?>
					<!--<li><a href="#suggestionsForm" data-toggle="modal" id="LinkSuggestionPopup" ><i class="icon16 i-bubble-12"></i>Suggestions</a></li> -->     
                    <?php if($this->config->item('change_internal_username') == TRUE){?><li><a href="#ChangePassword" class="change-internal-username"  ><i class="icon16 i-user"></i>Internal Username</a></li><?php } ?>
                    <li><a href="#ChangePassword" class="change-password"  ><i class="icon16 i-lock-3"></i>Change Password</a></li> 
                    <li><a href="<?=base_url()."login/logout";?>" ><i class="icon16 i-exit"></i> Logout</a></li> 
				</ul>
				</li> 
                <!-- end profile -->
				<li class="divider-vertical"></li>
			</ul> 
            <!-- top menu -->
            
		</div>
		<!--/.nav-collapse -->
	</div>
</div> 



</header> 

<script> 
var changeView = function(page_view) {
		
	$.ajax({
		data: "action=change_view&page_view="+page_view+"&rand="+Math.random(),//$(this).serialize(),
		type:"POST", 
		url: "<?=base_url();?>common/changeView",
		beforeSend:function(){       
			 
		},
		dataType:"JSON",
		success:function(msg){    
			if(msg.success > 0)
			 {   
			 	 if(page_view == "checkers")
				  {
					 window.location = "<?=base_url();?>operations"; 
				  }
				 else
				  {
					//window.location = "<?=current_url();?>";  
					window.location = "<?=base_url();?>dashboard"; 
				  }
				 
			 } 
			else
			 {
				createMessage(msg.message, "error"); 
			 }
			 		
		}
		
	}); //end ajax
}
//end change view
		
$(function(){ 
	 
	$(".change_view").click(function(){
		var page_view = $(this).attr("page-view");
		
		if(page_view)
		 {
			 changeView(page_view);
		 }
		 
	}); 
	
	$(".scheme-option").click(function(){
		var scheme = $(this).attr("scheme");
		$("body").attr("id",scheme); 
		localStorage.setItem('SelectedScheme', scheme)
		//$.cookie("SelectedScheme", scheme);  
	}); 
	
	$(".change-password").click(function(){ 
		$("#ChangePassword").find(".modal-title").html("<i class=\"icon20 i-lock-3\"></i>Change Password</h4>");
		$("#ChangePassword").modal('show');  
		loadAjaxContent("<?=base_url()?>manage/popupChangePassword", $("#ChangePassword").find(".ajax_content"));
	});
	
	$(".change-internal-username").click(function(){
		$("#ChangePassword").find(".modal-title").html("<i class=\"icon20 i-user\"></i>Internal System Username</h4>");
		$("#ChangePassword").modal('show');  
		loadAjaxContent("<?=base_url()?>manage/popupChangeInternalUsername", $("#ChangePassword").find(".ajax_content")); 
		
	});
	
	/*$(".scroll-menu").niceScroll(".li-menu",{
		cursoropacitymax: 0.8,
        cursorborderradius: 0,
        cursorwidth: "10px", 
		bouncescroll: false, 
		zindex: 999999, 
		autohidemode: false //true, cursor 
	});*/
	//$(".scroll-menu").niceScroll();
	 
	
	$(".notification_toggle").click(function(){ 
		$(".scroll-menu").niceScroll(".li-menu",{
			cursoropacitymax: 0.8,
			cursorborderradius: 0,
			cursorwidth: "10px", 
			bouncescroll: false, 
			zindex: 999999, 
			autohidemode: false //true, cursor 
		}); 
		//$(".modal_scroll").getNiceScroll().resize();
	});
	
});
</script>
<!-- End #header --> 