<!-- sibar -->                     
<aside id="sidebar"> 

<div class="side-options">
    <ul>
        <li><a href="#" id="collapse-nav" class="act act-primary tip" title="Collapse navigation"><i class="icon16 i-arrow-left-7"></i></a></li>
    </ul>
</div> 

<div class="sidebar-wrapper">
    <nav id="mainnav">
    <ul class="nav nav-list"> 
		<?php
		if(view_access())
		 {
		?>
        <li><a href="<?=base_url();?>dashboard"><span class="icon"><i class="icon20 i-screen"></i></span><span class="txt">Dashboard</span></a></li>
        <li class="hasSub <?=($main_page=="notice")?"current":"";?>">
            <a id="NoticeLink" href="<?=base_url();?>notice"><span class="icon"><i class="icon20 i-notification"></i></span>
            <span class="txt">Important Notice</span> 
        	<?php /*?><span class="notification red" id="LeftNotifyNotice" ></span><?php */?>
           	</a> 
        </li> 
        
			<?php
            if(view_only())
             {
            ?>
        <li class="hasSub <?=($main_page=="report")?"current":"";?>"><a href="<?=base_url();?>reports"><span class="icon"><i class="icon20 i-stack"></i></span><span class="txt">Operation Report</span></a></li>   
			<?php
             }
            ?>
        
        <li class="hasSub <?=($main_page=="task")?"current":"";?>"><a href="<?=base_url();?>tasks"><span class="icon"><i class="icon20 i-clipboard-2"></i></span><span class="txt">Daily Task</span></a></li>  
        
			<?php
            if(view_only())
             {
            ?> 
        <li class="hasSub <?=($main_page=="accounts")?"current":"";?>" ><a href="#"><span class="icon"><i class="icon20  i-vcard"></i></span><span class="txt">Accounts/Agents</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>accounts" ><span class="icon"><i class="icon20  i-stack-list"></i></span><span class="txt">Account List</span></a></li> 
                <li ><a href="<?=base_url();?>agents" ><span class="icon"><i class="icon20  i-users-5"></i></span><span class="txt">Agent List</span></a></li>            
                <li><a href="<?=base_url();?>modes"><span class="icon"><i class="icon20 i-mobile"></i></span><span class="txt">Modes</span></a></li>         
             </ul>
        </li>
        
			<?php
             }
            ?>
        
        <li class="hasSub <?=($main_page=="bonus")?"current":"";?>" ><a href="<?=base_url();?>bonus"><span class="icon"><i class="icon20  i-diamond-2"></i></span><span class="txt">Bonus</span></a></li>
        
        <li class=" <?=($main_page=="operations")?"current":"";?>" ><a href="<?=base_url();?>operations"><span class="icon"><i class="icon20 i-reading"></i></span><span class="txt">SOP / Policy</span></a></li> 
        
        <?php
		 }
		?>
         
        <li class="hasSub <?=($main_page=="overpay")?"current":"";?>"><a  href="<?=base_url();?>overpay" ><span class="icon"><i class="icon20 i-coins"></i></span><span class="txt">Overpay Case File</span></a> 
        </li>
        
        <?php
		if(view_access())
		 {
		?>
        <li class="hasSub <?=($main_page=="schedules")?"current":"";?>" ><a href="<?=base_url();?>schedules"><span class="icon"><i class="icon20 i-calendar-4"></i></span><span class="txt">Schedule</span></a></li>
        
			 <?php
            if(view_only())
             {
            ?>
        <li class="hasSub <?=($main_page=="meetings")?"current":"";?>"><a href="<?=base_url();?>meetings"><span class="icon"><i class="icon20 i-people"></i></span><span class="txt">Meeting Minutes</span></a></li>  
        	<?php
			 }
			else
			 {
			?>
        <li class="hasSub <?=($main_page=="meetings")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-people"></i></span><span class="txt">Meeting Minutes</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>meetings" ><span class="icon"><i class="icon20  i-file-8"></i></span><span class="txt">Minutes List</span></a></li>
                <li><a href="<?=base_url();?>manage-meetings"><span class="icon"><i class="icon20  i-file-plus-2"></i></span><span class="txt">Add Meeting Minutes</span></a></li>            	 
            </ul>
        </li>  
            <?php	 
			 }
			?>
        
        <?php
		 }
		?>
        
        <li class="hasSub <?=($main_page=="warning")?"current":"";?>"><a href="<?=base_url();?>warning"><span class="icon"><i class="icon20 i-warning"></i></span><span class="txt">Performance Warning</span></a></li>
        
        <?php
		if(view_access())
		 {
		?>
        <li class="hasSub <?=($main_page=="websites")?"current":"";?>" ><a href="<?=base_url();?>websites"><span class="icon"><i class="icon20  i-link-6"></i></span><span class="txt">Websites / Links</span></a> 
        </li>
         
        <li class="<?=($main_page=="users")?"current":"";?>" ><a href="<?=base_url();?>users"><span class="icon"><i class="icon20 i-users"></i></span><span class="txt">Users</span></a> 
        </li>
        <?php
		 }
		?>
        
			<?php
            if(view_management())
             {
            ?>
        <li class="hasSub <?=($main_page=="suggestions")?"current":"";?>" ><a href="<?=base_url();?>suggestions"><span class="icon"><i class="icon20 i-bubble-12"></i></span><span class="txt">Message Board</span></a> 
        </li>
			<?php
             }
            ?>
        
        <?php  
		//echo $menu_list;
		?>  
      
          
    </ul>
    </nav>
    <?php
	//if($this->session->userdata("mb_usertype") != 1 && $this->session->userdata("mb_usertype") != 2) 
	if(notify_type())
	 {
	?>
    <script> 
	var notification_ctr = 0; 
	var alerts = new Array(); 
	
	var getUnreadNotification = function() {
		
		$.ajax({
			data: "action=count_unread&rand="+Math.random(),//$(this).serialize(),
			type:"POST", 
			url: "<?=base_url();?>common/getUnreadNotification",
			beforeSend:function(){       
				//$("#HeaderNotification").find("ul.dropdown-menu").html("");
			},
			dataType:"JSON",
			success:function(msg){   
				 
				if(msg.length > 0)
				 {   
					//$("#NoticeLink").append('<span class="notification red" id="LeftNotifyNotice" >'+msg.length+'</span>');  
					$.each(msg, function(index, data) {
					   
					   if($("#HeaderNotification").find("li.notification_notice_" + data.PageID).length <= 0)
					    {
							$("#HeaderNotification").find("ul.dropdown-menu").prepend("<li class=\"notification_notice_"+data.PageID+"\" ><a href=\"<?=base_url();?>view-notice/"+data.PageID+"\" class=\"\"><i class=\"icon16 i-notification\"></i>"+data.UpdatedByName + " " + data.NoticeAction + " notice. " +"</a></li>");  
						   notification_ctr++;
					   
						   //push alerts
						   var obj = {
								message: data.UpdatedByName + " " + data.NoticeAction + " notice. <i>" + data.Title + "<i/>",
								group: "info notice",
								header: '<i class="icon16 i-notification"></i> Important', 
								strlink: ""
							};
							alerts.push(obj); 	
						}//end if
					   else
					    {
							  	
						}
					   
					}); //end each
					
					checkNotification(1);
					$("#HeaderNotification").find("span.counter").html(notification_ctr);
					 
				 } 
				else
				 {
					//createMessage(result[1], "error"); 
				 }
				
				 checkNotification(msg.length);
						
			}
			
		}); //end ajax
	}
	
	
	$(function(){
		$(".subroot").removeClass("current");  
		
		//
		setInterval(function() {  
			getUnreadNotification();   
		}, 90000);
		 
		getUnreadNotification(); 
		
		//stickyMessage("Jaypee created notice", "info", '<i class="icon16 i-notification"></i> Important');
	 
		setInterval(function() {   
			var pre_arr = alerts;  
			var max_disp = 3; 
			var x = 0;  
			for(i=pre_arr.length-1; i>=0; i--)
			 {	
				if(pre_arr[i] !== undefined && pre_arr[i].message !== undefined && (x < max_disp) )
				 { 	 
				  	 stickyMessage(pre_arr[i]); 
					 alerts.splice(i,1); 
					 x++; 
					 //alerts.shift();
					 //alerts.filter; 
					// alerts.clean;   
				 }	 
			 }
			 
			/*$.each(alerts, function(i, data){ 
			
				if(data !== undefined && data.message !== undefined  )
				 {
					 console.log(alerts.length + " ---- ");
				 
					 console.log(data.message);
					 //alerts.splice(i,1); 
					 alerts.shift();
					 //alerts.filter; 
					// alerts.clean;   
				 }
				
			});*/
			
		}, 20000);
		
		/*$(".scroll").niceScroll({
			cursoropacitymax: 0.8,
			cursorborderradius: 0,
			cursorwidth: "10px"
		});*/
		
		/*setTimeout(function() {
			$.jGrowl("Jaypee created notice", {
				group: 'info', 
				header: '<i class="icon16 i-notification"></i> Important',
				error: '',
				position: 'bottom-left',
				sticky: false,
				closeTemplate: '<i class="icon16 i-close-2"></i>',
				animateOpen: {
					width: 'show',
					height: 'show'
				}
			});
		}, 2000);   */  
		 
		
	});
	</script>    
    <?php
	 }
	?>
    <!-- End #mainnav -->
</div>
<!-- End .sidebar-wrapper -->
</aside>
<!-- End #sidebar -->