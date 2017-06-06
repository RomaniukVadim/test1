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
        
        <?php /*?><li class="hasSub <?php if($mainnav == "banners")echo "current";?>" ><a href="#" class="arrow" ><span class="icon"><i class="icon20 i-images"></i></span><span class="txt">Banners</span></a>
            <ul class="sub show" style="display: block;" >
                <li class="subroot hasSub" ><a href="#" ><span class="icon"><i class="icon20 i-stack-list"></i></span><span class="txt">Banners List</span></a>
               		 <ul class="sub expand show" style="display: block;" >  
                     	<?php   
						$lang_menu = "";
						if(count($access_lang) > 0){
							if(count($access_lang) == 1)
							 { 
								 $lang = $access_lang; 
								  
						$lang_menu .= '<li><a href="'.base_url().'banners/'.$lang->LanguageID.'"><span class="icon"><i class="icon20 i-earth"></i></span><span class="txt">'.$lang->Name.'</span></a></li>';
						  
							 }
							else
							 { 
								 foreach($access_lang as $row => $lang){ 
								 	//$cur_class = ($mainnav=="banners" && ($current_lang==$lang->LanguageID))?"class='current'":"";
						$lang_menu .= '<li '.$cur_class.'><a href="'.base_url().'banners/'.$lang->LanguageID.'"><span class="icon"><i class="icon20 i-earth"></i></span><span class="txt">'.$lang->FolderName.'</span></a></li>';
								 } 
							 }
							
						}
						echo $lang_menu; 
						?>
                     </ul>
                </li> 
                <li><a href="<?=base_url();?>manage-banners"><span class="icon"><i class="icon20 i-stack-plus"></i></span><span class="txt">Add Banner</span></a></li> 
            </ul>
        </li> <?php */?>
        
        
        <?   
		if(admin_access() > 0)
		 {   
		?>
        <li><a href="<?=base_url();?>dashboard"><span class="icon"><i class="icon20 i-screen"></i></span><span class="txt">Dashboard</span></a></li> 
        
        <li class="hasSub" ><a href="#"><span class="icon"><i class="icon20 i-users"></i></span><span class="txt">Users</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>users" ><span class="icon"><i class="icon20 i-users-3"></i></span><span class="txt">Users List</span></a></li>
                <li><a href="<?=base_url();?>manage-users"><span class="icon"><i class="icon20 i-user-plus-3"></i></span><span class="txt">Add User</span></a></li> 
            </ul>
        </li>
        
        <li class="hasSub" ><a href="#"><span class="icon"><i class="icon20  i-vcard"></i></span><span class="txt">Accounts</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>accounts" ><span class="icon"><i class="icon20  i-stack-list"></i></span><span class="txt">Account List</span></a></li>
                <li><a href="<?=base_url();?>manage-accounts"><span class="icon"><i class="icon20  i-user-plus-2"></i></span><span class="txt">Add Account</span></a></li>            </ul>
        </li>


        <li class="hasSub" ><a href="#"><span class="icon"><i class="icon20  i-books"></i></span><span class="txt">Topics</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>topics" ><span class="icon"><i class="icon20  i-file-8"></i></span><span class="txt">Topic List</span></a></li>
                <li><a href="<?=base_url();?>manage-topics"><span class="icon"><i class="icon20  i-stack-plus"></i></span><span class="txt">Add Topic</span></a></li>            </ul>
        </li>
        
        <li class="hasSub" ><a href="#"><span class="icon"><i class="icon20 i-file-6"></i></span><span class="txt">Pages</span></a>
            <ul class="sub">
                <li ><a href="<?=base_url();?>pages" ><span class="icon"><i class="icon20  i-file-8"></i></span><span class="txt">Page List</span></a></li>
                <li><a href="<?=base_url();?>manage-pages"><span class="icon"><i class="icon20  i-file-plus-2"></i></span><span class="txt">Add Page</span></a></li>            </ul>
        </li>
        <?php
		 }
		?>
        
         
        
        <!--<li><a href="charts.html"><span class="icon"><i class="icon20 i-stats-up"></i></span><span class="txt">Charts</span></a></li>
        <li><a href="#"><span class="icon"><i class="icon20 i-menu-6"></i></span><span class="txt">Forms</span></a>
        <ul class="sub">
            <li><a href="form-elements.html"><span class="icon"><i class="icon20 i-stack-list"></i></span><span class="txt">Form elements</span></a></li>
            <li><a href="form-validation.html"><span class="icon"><i class="icon20 i-stack-checkmark"></i></span><span class="txt">Form validation</span></a></li>
            <li><a href="form-wizard.html"><span class="icon"><i class="icon20 i-stack-star"></i></span><span class="txt">Form wizard</span></a></li>
        </ul>
        </li>
        
        <li><a href="#"><span class="icon"><i class="icon20 i-table-2"></i></span><span class="txt">Tables</span></a>
        <ul class="sub">
            <li><a href="tables.html"><span class="icon"><i class="icon20 i-table"></i></span><span class="txt">Static tables</span></a></li>
            <li><a href="data-tables.html"><span class="icon"><i class="icon20 i-table"></i></span><span class="txt">Data tables</span></a></li>
        </ul>
        </li>
        <li><a href="grid.html"><span class="icon"><i class="icon20 i-grid-5"></i></span><span class="txt">Grid</span></a></li>
        <li><a href="typo.html"><span class="icon"><i class="icon20 i-font"></i></span><span class="txt">Typography</span></a></li>
        <li><a href="calendar.html"><span class="icon"><i class="icon20 i-calendar"></i></span><span class="txt">Calendar</span></a></li>
        <li><a href="#"><span class="icon"><i class="icon20 i-cogs"></i></span><span class="txt">Ui Elements</span></a>
        <ul class="sub">
            <li><a href="icons.html"><span class="icon"><i class="icon20 i-IcoMoon"></i></span><span class="txt">Icons</span></a></li>
            <li><a href="buttons.html"><span class="icon"><i class="icon20 i-point-up"></i></span><span class="txt">Buttons</span></a></li>
            <li><a href="ui-elements.html"><span class="icon"><i class="icon20 i-puzzle"></i></span><span class="txt">UI Elements</span></a></li>
        </ul>
        </li>
        <li><a href="gallery.html"><span class="icon"><i class="icon20 i-images"></i></span><span class="txt">Gallery</span></a></li>
        <li><a href="maps.html"><span class="icon"><i class="icon20 i-location-4"></i></span><span class="txt">Maps</span></a></li>
        <li><a href="file-manager.html"><span class="icon"><i class="icon20 i-cloud-upload"></i></span><span class="txt">File manager</span></a></li>
        <li><a href="widgets.html"><span class="icon"><i class="icon20 i-cube-3"></i></span><span class="txt">Widgets</span></a></li>
        
        <li><a href="#"><span class="icon"><i class="icon20 i-file-8"></i></span><span class="txt">Pages</span></a>
        <ul class="sub">
            <li><a href="#"><span class="icon"><i class="icon20 i-warning"></i></span><span class="txt">Error Pages</span></a>
            <ul class="sub">
                <li><a href="403.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 403</span></a></li>
                <li><a href="404.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 404</span></a></li>
                <li><a href="405.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 405</span></a></li>
                <li><a href="500.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 500</span></a></li>
                <li><a href="503.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 503</span></a></li>
                <li><a href="offline.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Offline page</span></a></li>
            </ul>
            </li>
            <li><a href="invoice.html"><span class="icon"><i class="icon20 i-credit"></i></span><span class="txt">Invoice page</span></a></li>
            <li><a href="profile.html"><span class="icon"><i class="icon20 i-user"></i></span><span class="txt">Profile page</span></a></li>
            <li><a href="search.html"><span class="icon"><i class="icon20 i-search-2"></i></span><span class="txt">Search page</span></a></li>
            <li><a href="email.html"><span class="icon"><i class="icon20 i-envelop-2"></i></span><span class="txt">Email page</span></a></li>
            <li><a href="support.html"><span class="icon"><i class="icon20 i-support"></i></span><span class="txt">Support page</span></a></li>
            <li><a href="faq.html"><span class="icon"><i class="icon20 i-question"></i></span><span class="txt">FAQ page</span></a></li>
            <li><a href="blank.html"><span class="icon"><i class="icon20 i-file-7"></i></span><span class="txt">Blank page</span></a></li>
        </ul>
        </li>-->
    </ul>
    </nav>
    <script>
	$(function(){
		$(".subroot").removeClass("current"); 
		setTimeout(function() {   
			//$(".subroot").trigger("click");
			//$(".subroot").trigger("click");
			//$(".arrow").trigger("click");
			//$(".arrow").trigger("click"); 
		}, 100);
		
		
		
	});
	</script>   
    <!-- End #mainnav -->
</div>
<!-- End .sidebar-wrapper -->
</aside>
<!-- End #sidebar -->