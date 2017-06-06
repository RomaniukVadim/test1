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
          
        <li><a href="<?=base_url();?>dashboard"><span class="icon"><i class="icon20 i-screen"></i></span><span class="txt">Dashboard</span></a></li> 
        <?php
		if(view_access() || admin_access()) 
		 {
		?>
        <li class="hasSub <?=($main_page=="banks")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-office"></i></span><span class="txt">Bank</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>banks/activities" ><span class="icon"><i class="icon20  i-calculate-2"></i></span><span class="txt">Deposit/Withdrawal</span></a></li>
                
                <?php
				if(admin_only()) 
				 {
				?>
                <li><a href="<?=base_url();?>banks/deposit-methods"><span class="icon"><i class="icon20 i-credit"></i></span><span class="txt">Deposit Methods</span></a></li>            				
                <li><a href="<?=base_url();?>banks/deposit-categories"><span class="icon"><i class="icon20  i-file-plus-2"></i></span><span class="txt">Deposit Categories</span></a></li>            	
                <li><a href="<?=base_url();?>banks/withdrawal-categories"><span class="icon"><i class="icon20  i-file-minus-2"></i></span><span class="txt">Withdrawal Categories</span></a></li>  
                <li><a href="<?=base_url();?>banks/analysis-reasons"><span class="icon"><i class="icon20   i-chart"></i></span><span class="txt">Analysis Reasons</span></a></li>  
                <li><a href="<?=base_url();?>banks/analysis-categories"><span class="icon"><i class="icon20   i-bars"></i></span><span class="txt">Analysis Categories</span></a></li>  
                <?php
				 }
				?>
                 
                <li><a href="<?=base_url();?>banks/search"><span class="icon"><i class="icon20  i-search-2"></i></span><span class="txt">Search Activities</span></a></li> 	 
            	 
			</ul>
        </li> 
        <?php
		 }
		?>
        
        <?php
		if(view_access() || admin_access()) 
		 {
		?>
        <li class="hasSub <?=($main_page=="promotions")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-star-2"></i></span><span class="txt">Promotions</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>promotions/activities" ><span class="icon"><i class="icon20 i-stack-list"></i></span><span class="txt">Promotional Activities</span></a></li>
                
                <?php 
				if(admin_access() || manage_promotion())
				 {
				?>
                <li ><a href="<?=base_url();?>promotions/manage-promotions"><span class="icon"><i class="icon20 i-gift"></i></span><span class="txt">Manage Promotions</span></a></li>            	
                <li><a href="<?=base_url();?>promotions/categories"><span class="icon"><i class="icon20  i-podium"></i></span><span class="txt">Promotional Categories</span></a></li>            	<li><a href="<?=base_url();?>promotions/issues"><span class="icon"><i class="icon20 i-drawer-3"></i></span><span class="txt">Promotional Issues</span></a></li>            	
                <?php
				 }
				?>
                
                <?php
				if(admin_only() || view_management())
				 {
				?>
                <li><a href="<?=base_url();?>promotions/management-approval"><span class="icon"><i class="icon20 i-thumbs-up"></i></span><span class="txt">Management Approval</span></a></li>        
                <?php
				 }
				?>
                
                <?php
				if(admin_access() || allow_agent_report() )
				 {
				?>    	   
                <li><a href="<?=base_url();?>promotions/agent-summary-report"><span class="icon"><i class="icon20  i-reading"></i></span><span class="txt">Agent Summary Report</span></a></li>        
                <?php
				 }
				?> 
                    	
                <li><a href="<?=base_url();?>promotions/search"><span class="icon"><i class="icon20  i-search-2"></i></span><span class="txt">Search Activities</span></a></li>            	
                <?php
				if(admin_access() || can_upload_promotions())
				 {
				?>
                <li><a href="<?=base_url();?>promotions/uploaded"><span class="icon"><i class="icon20  i-upload"></i></span><span class="txt">Uploaded Promotions</span></a></li>            	<?php
		 		 }
				?>
                   
            </ul>
        </li> 
        <?php
		 }
		?>
        
        <?php
		if(view_access() || admin_access()) 
		 {
		?>
        <li class="hasSub <?=($main_page=="casino")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-dice"></i></span><span class="txt">Casino</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>casino/activities" ><span class="icon"><i class="icon20  i-stack-list"></i></span><span class="txt">Casino Issues</span></a></li>
                
                <?php
				if(admin_access())
				 {
				?>
                <li><a href="<?=base_url();?>casino/categories"><span class="icon"><i class="icon20 i-podium"></i></span><span class="txt">Issues Categories</span></a></li> 
                <?php
		 		 }
				?>
                				            	
                <li ><a href="<?=base_url();?>casino/search" ><span class="icon"><i class="icon20  i-search-2"></i></span><span class="txt">Search Issues</span></a></li> 
            </ul>
        </li> 
        <?php
		 }
		?>
        
        <?php
		if(view_access() || admin_access()) 
		 {
		?>
        <li class="hasSub <?=($main_page=="accounts")?"current":"";?>" ><a href="#"  ><span class="icon"><i class="icon20  i-vcard"></i></span><span class="txt">Accounts</span></a>
            <ul class="sub">
            	<li ><a href="<?=base_url();?>accounts/activities" ><span class="icon"><i class="icon20  i-stack-list"></i></span><span class="txt">Account Related Issues</span></a></li> 
                
                <?php
				if(admin_access())
				 {
				?> 
                <li  class="<?=($sub_page=="accounts")?"current":"";?>" ><a href="<?=base_url();?>accounts/related-problems"><span class="icon"><i class="icon20 i-user-block"></i></span><span class="txt">Related Problems</span></a></li> 
                
                <li  class="<?=($sub_page=="accounts")?"current":"";?>" ><a href="<?=base_url();?>accounts/related-problem-categories"><span class="icon"><i class="icon20 i-tree-3"></i></span><span class="txt">Problem Categories</span></a></li> 
                <?php
				 }
				?>
                
                <li ><a href="<?=base_url();?>accounts/search" ><span class="icon"><i class="icon20  i-search-2"></i></span><span class="txt">Search Related Issues</span></a></li>
             </ul>
        </li>
        <?php
		 }
		?>
        
        <?php
		if(view_access() || admin_access()) 
		 {
		?>
        <li class="hasSub <?=($main_page=="suggestions")?"current":"";?>" ><a href="#"><span class="icon"><i class="icon20  i-pencil-5"></i></span><span class="txt">Suggestions</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>suggestions/activities" ><span class="icon"><i class="icon20  i-bubble-13"></i></span><span class="txt">Suggestions</a></li>
                <?php
				if(admin_access())
				 {
				?>
                <li><a href="<?=base_url();?>suggestions/types"><span class="icon"><i class="icon20  i-bubbles-8"></i></span><span class="txt">Types</span></a></li>            
				<?php
				 }
				?>
                
                <li><a href="<?=base_url();?>suggestions/search"><span class="icon"><i class="icon20  i-search-2"></i></span><span class="txt">Search Suggestions</span></a></li>             </ul>
        </li>
        <?php
		 }
		?>
        
        <?php
		if(view_access() || admin_access()) 
		 {
		?>
        <li class="hasSub <?=($main_page=="websites")?"current":"";?>" ><a href="#"><span class="icon"><i class="icon20 i-key-2"></i></span><span class="txt">Access</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>access/activities" ><span class="icon"><i class="icon20  i-earth"></i></span><span class="txt">Website/Mobile Access</span></a></li>
                
                <?php
				if(admin_access()) 
				 {
				?>
                <li><a href="<?=base_url();?>access/problems"><span class="icon"><i class="icon20  i-network"></i></span><span class="txt">Access Problems</span></a></li>
                <?php
				 }
				?>
                
                <li ><a href="<?=base_url();?>access/search" ><span class="icon"><i class="icon20  i-search-2"></i></span><span class="txt">Search Activities</span></a></li>           
            </ul>
        </li>
        <?php
		 }
		?>
        
        <?php
		if(view_access() || admin_access()) 
		 {
		?> 
        <li class="hasSub <?=($main_page=="12bet_users")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-people"></i></span><span class="txt">12BET Users</span></a>
            <ul class="sub">
                <?php if(admin_access()){ ?><li ><a href="<?=base_url()?>12bet-users/list" ><span class="icon"><i class="icon20 i-numbered-list"></i></span><span class="txt">12BET Users List</span></a></li><?php } ?> 
                <li ><a href="<?=base_url()?>12bet-users/search" ><span class="icon"><i class="icon20 i-search-2"></i></span><span class="txt">Search 12BET Users</span></a></li> 
            </ul>
        </li> 
        <?php
		 }
		?>
        
        <?php
		if(admin_access() || view_access() || strict_backup_url()) 
		 {
		?> 
        <li class="hasSub <?=($main_page=="back_url")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-link-2"></i></span><span class="txt">Backup URL</span></a>
            <ul class="sub">
                <?php if(strict_backup_url()){ ?><li ><a href="<?=base_url()?>backup-url/list" ><span class="icon"><i class="icon20 i-link2"></i></span><span class="txt">URL List</span></a></li><?php } ?> 
                <li ><a href="<?=base_url()?>backup-url/search" ><span class="icon"><i class="icon20 i-link-5"></i></span><span class="txt">Search URL</span></a></li> 
            </ul>
        </li> 
        <?php
		 }
		?>
        
        <?php
		if(admin_access() || manage_user()) 
		 {
		?>
        <li class="hasSub <?=($main_page=="manage")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-cogs"></i></span><span class="txt">Management</span></a>
            <ul class="sub"> 
                <?php
				if(admin_access())
				 {
				?>
                <li ><a href="<?=base_url();?>manage/user-types" ><span class="icon"><i class="icon20 i-users"></i></span><span class="txt">User Types</span></a></li>
                <?php
				 }
				?>
                <li ><a href="<?=base_url();?>manage/users" ><span class="icon"><i class="icon20 i-users-4"></i></span><span class="txt">Users</span></a></li> 
				<?php
				if(admin_access())
				 {
				?> 
                <li ><a href="<?=base_url();?>manage/currencies" ><span class="icon"><i class="icon20 i-coin"></i></span><span class="txt">Currencies</span></a></li> 
                <li ><a href="<?=base_url();?>manage/status" ><span class="icon"><i class="icon20 i-support"></i></span><span class="txt">Status</span></a></li> 
                <li ><a href="<?=base_url();?>manage/activity-source" ><span class="icon"><i class="icon20 i-mobile-3"></i></span><span class="txt">Activity Source</span></a></li>  
                <li ><a href="<?=base_url();?>manage/suggestionTypes" ><span class="icon"><i class="icon20 i-warning"></i></span><span class="txt">Suggestions Types</span></a></li>  				
				<?php
				if(super_admin())
				 {
				?>
                <li ><a href="<?=base_url();?>manage/pages" ><span class="icon"><i class="icon20 i-file-8"></i></span><span class="txt">Pages</span></a></li>  
                <?php
				 }
				?>
                 
                
                <li ><a href="<?=base_url();?>manage/checking-category" ><span class="icon"><i class="icon20 i-checkmark-circle"></i></span><span class="txt">Checking Category</span></a></li> 				
                <li ><a href="<?=base_url();?>manage/12bet-checking" ><span class="icon"><i class="icon20 i-stack-checkmark"></i></span><span class="txt">12BET Checking</span></a></li>  
                
                <li ><a href="<?=base_url();?>manage/call-outcomes" ><span class="icon"><i class="icon20 i-call-outgoing"></i></span><span class="txt">Call Outcome</span></a></li> 
                <li ><a href="<?=base_url();?>manage/call-results" ><span class="icon"><i class="icon20 i-phone-4"></i></span><span class="txt">Call Results</span></a></li>  
                <?php
				 }
				?>
                
                <?php 
				if(admin_only())
				 {
				?> 
                <li ><a href="<?=base_url();?>manage/result-categories" ><span class="icon"><i class="icon20 i-contact-add-2"></i></span><span class="txt">Result Categories</span></a></li>
                <li ><a href="<?=base_url();?>manage/chat-groups" ><span class="icon"><i class="icon20 i-bubbles-10"></i></span><span class="txt">Chat Groups</span></a></li>
            	<?php
				 }
				?>
            </ul>
        </li>
        <?php
		 }
		?> 
        
        <?php
		if(admin_access() || can_check() || shift_report() ) 
		 {
		?> 
        <li class="hasSub <?=($main_page=="checking")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-file-check-2"></i></span><span class="txt">Checking</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url()?>checking/12bet" ><span class="icon"><i class="icon20  i-checkmark-circle"></i></span><span class="txt">12BET Checking</span></a></li>
                <?php if(admin_access() || shift_report() || shift_report_all()  ){ ?><li ><a href="<?=base_url()?>checking/shift-report" ><span class="icon"><i class="icon20 i-clipboard-4"></i></span><span class="txt">Shift Report</span></a></li><?php } ?>
                <?php if(super_admin()){ ?><li ><a href="<?=base_url()?>checking/market-apps" ><span class="icon"><i class="icon20 i-mail-4"></i></span><span class="txt">Market Apps</span></a></li><?php } ?> 
                <!--<li><a href="#"><span class="icon"><i class="icon20 i-coin"></i></span><span class="txt">Check Casino</span></a></li>   -->         	 
            </ul>
        </li> 
        <?php
		 }
		?>
        
        <?php
		if(report_module() ) 
		 {
		?>
        <li class="hasSub <?=($main_page=="reports")?"current":"";?>"><a href="#"><span class="icon"><i class="icon20 i-stats"></i></span><span class="txt">Reports</span></a>
            <ul class="sub">
                 
				<?php if(can_cal_system()) { ?><li ><a href="<?=base_url()?>reports/cal" ><span class="icon"><i class="icon20 i-phone-6"></i></span><span class="txt">CAL System</span></a></li><?php } ?>
                
                <?php if(can_crm_calls()) { ?><li ><a href="<?=base_url()?>reports/crm" ><span class="icon"><i class="icon20 i-call-outgoing"></i></span><span class="txt">CAL CRM Calls</span></a></li><?php } ?>
                
                <?php if(admin_only() || can_upload_crm_record() || can_view_crm_record()) { ?><li><a href="<?=base_url()?>reports/conversions"><span class="icon"><i class="icon20 i-contact-add"></i></span><span class="txt">CRM Conversions</span></a></li> <?php } ?> 
                 
				<?php if(admin_only()) { ?><li><a href="<?=base_url()?>reports/kayako"><span class="icon"><i class="icon20 i-support"></i></span><span class="txt">Kayako</span></a></li> <?php } ?>           	 
            </ul>
        </li> 
		<?php
		 }
		?>    
        
       <?php /*?> <li class="hasSub <?=($main_page=="suggestions")?"current":"";?>" ><a href="<?=base_url();?>suggestions"><span class="icon"><i class="icon20 i-bubble-12"></i></span><span class="txt">Message Board</span></a> 
        </li> <?php */?>
         
		<?php 
		//view_knowledge_portal()
		if(admin_only() || $this->session->userdata('mb_no') == 316 || true) 
		{ 
		?>
		<li><a href="<?=base_url();?>portal/dashboard" target="_blank"><span class="icon"><i class="icon20 i-brain"></i></span><span class="txt">Knowledge Portal</span></a></li> 
        <?php 
		} 
		?> 
        
    </ul>
    </nav>
    <script>
	$(function(){
		//$(".subroot .current").find("ul.sub").addClass("show").css("display","block");	
		//$(".subroot").removeClass("current"); 
		//setTimeout(function() {   
			//$(".subroot").trigger("click");
			//$(".subroot").trigger("click");
			//$(".arrow").trigger("click");
			//$(".arrow").trigger("click"); 
		//}, 100); 
		
	});
	</script>   
    <!-- End #mainnav -->
    
    
    <!--<div class="sidebar-widget center">
        <h4 class="sidebar-widget-header">
            <i class="icon i-pie-2"></i>
            Basic stats
        </h4>
        <div class="spark-stats">
            <ul>
                <li>
                	<a href="#"></a>
                </li>
                <li>
                	<a href="#"></a>
                </li>
            </ul>
        </div>
    </div>-->
    
    <!-- ONLINE GAUGE -->
    <div class="sidebar-widget center">
        <h4 class="sidebar-widget-header"><i class="icon i-users"></i> Online Users</h4>
        <div id="gauge_online" style="width:200px; height:150px;"></div>
        <!--<div id="gauge1" style="width:200px; height:150px;"></div>-->
    </div><!-- end .sidebar-widget --> 
    <script src="<?=base_url();?>media/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
	<script src="<?=base_url();?>media/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>
    <script>
	var conn = []; 
	var g; //gauge
	var listeners = {}; 
	function getChatConnected(connects) { //called in chat.js
		//conn = connects; 
		   
		/*console.log(JSON.stringify(connects)); */
		// var uid_arr = $.map(connects.listeners, function(n, i) {
						// return (n.uid);
				    // });
		// listeners = $.extend(uid_arr, listeners);  
		// g.refresh(listeners.length);
	}
	
	function logged_in(i) { 
		g.refresh(i);
	}

	$(function(){
		var max_staff = 100; 
		g = new JustGage({
			id: "gauge_online", 
			value: getRandomInt(0, max_staff), 
			min: 0,
			max: max_staff,
			title: " ",
			gaugeColor: '#6f7a8a',
			labelFontColor: '#555',
			titleFontColor: '#555',
			valueFontColor: '#555',
			showMinMax: false
		 });
	
		/*var g1 = new JustGage({
			id: "gauge1", 
			value: getRandomInt(100, 500), 
			min: 100,
			max: 500,
			title: "Visitors now",
			gaugeColor: '#6f7a8a',
			labelFontColor: '#555',
			titleFontColor: '#555',
			valueFontColor: '#555',
			showMinMax: false
		 });*/
	
		/*setInterval(function() {
		  g.refresh(getRandomInt(0, 100));
		  g1.refresh(getRandomInt(100, 500));
		}, 2500);*/
		
	}); 
	</script>
    <!-- END ONLINE GAUGE --> 
    
    
</div>
<!-- End .sidebar-wrapper --> 

 


</aside>
<!-- End #sidebar -->