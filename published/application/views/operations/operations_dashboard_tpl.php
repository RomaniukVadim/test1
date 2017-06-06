<!-- Tables plugins -->
<script src="<?=base_url();?>/media/js/plugins/tables/datatables/jquery.dataTables.min.js"></script> 

<style>
.txt {    
	overflow: hidden !important; 
    text-overflow: ellipsis !important; 
	/*white-space: nowrap !important;  */ 
}
</style>

<div class="main">
	
    <?=$sidebar_view;?>
    
	<section id="content">
	<div class="wrapper">
		
        <div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Pages</a><span class="divider">/</span></li> 
				<li class="active">Dashboard</li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-screen"></i> Operations</h1>
			</div> 
             
            <div class="col-lg-12">
            
                <div class="page-header">
                    <h4>Search for specific topic.</h4>
                </div>
                
                <div class="faq-search">
                <form class="form-horizontal" role="form" onsubmit="return false; ">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <div class="input-append">
                                <!--<input class="searchfield " type="text" placeholder="Find question ...">
                                <button class="btn" type="submit" style="margin: 0 !important;" >
                                <i class="icon16 i-search-3"></i>
                                </button>--> 
                                <input id="tsearch_topic" class="search-query" type="text" placeholder="Search here ..." name="tsearch" >
                                <button class="btn" type="submit" style="line-height: 25px !important; " id="BtnTopicSearch"  >
                                <i class="icon16 i-search-2 gap-right0"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
 				
                <div class="page-header">
                	<h4>Topics</h4>
                </div>  
                
                <!-- topic list -->
                <div class="categories row-fluid topic_list">
                	
                    <ul class="list-unstyled">
                    	<?php     
						if(count($topics) > 0){
							foreach($topics as $row => $topic){ 
								//$cur_class = ($mainnav=="banners" && ($current_lang==$lang->LanguageID))?"class='current'":"";
								$topic_list .= '<li> 
													<i class="icon16 i-folder"></i>
													<a href="#" class="topic_link" id="Topic_'.$topic->TopicID.'" topic-id="'.$topic->TopicID.'">'.$topic->Name.'</a>
													<span class="notification blue gap-left10">'.$topic->CountPage.'</span> 
												</li>';
							 } 
						}
						echo $topic_list; 
						?>
                          
                    </ul>
                     
                </div>
                <!-- end topic list -->
                
                
                <!-- selected topic -->
                <div class="page-header">
                	<h4 class="selected_h4" ><!--Daily Task--></h4>
                </div>
                
                <!-- popular list -->
				<div class="popular-question row-fluid topic_popular" id="SelectedTopic" >
                	<ul class="list-unstyled topic_page the_list">
                            
                    </ul>
                </div>
                <!-- end selected topic -->
                 
                <!-- selected --> 
                <!--<div class="answer">
                    <div class="page-header">
                    <h4>How can I edit the information in MySQL databases online?</h4>
                    </div>
                    <p>Each hosting account has installed PhpMyAdmin, which is available in cPanel, MySQL. With it, you can change the content databases you real-time data. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                     
               </div> -->
               <!-- end selected -->
               
               <!-- pagination -->
               <?php /*?><div class="pagination">
                    <ul class="pagination">
                        <li>
                        	<a href="#">← Previous</a>
                        </li>
                        <li class="active">
                        	<a href="#">1</a>
                        </li>
                        <li>
                        	<a href="#">2</a>
                        </li>
                        <li>
                        	<a href="#">3</a>
                        </li>
                        <li>
                        	<a href="#">4</a>
                        </li>
                        <li>
                        	<a href="#">Next →</a>
                        </li>
                    </ul>
                </div><?php */?>
               <!-- end pagination -->
               
            </div>
			<?php /*?><div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-file-8"></i>
							</div>
							<h4><?=$page->Title;?></h4>
							<a href="#" class="minimize"></a>  
                             
						</div>
						<!-- End .widget-title -->
                         
						<div class="widget-content page_content"> 
                        	<?=$page->Content;?>
						</div>
						<!-- End .widget-content -->
                         
					</div>
					<!-- End .widget -->
                    
				</div>
				<!-- End .span12 -->
                 
			</div>
			<!-- End .row-fluid --><?php */?>
             
            
		</div>
		<!-- End .container-fluid -->
	</div>
	<!-- End .wrapper -->
	</section>
    
</div>
<!-- End .main --> 

<!-- Init plugins only for page -->  


<script>
var getTutorials = function(action_type, topic_id, text) {
	action_type = (action_type!== undefined || action_type=="")?action_type:"";
	topic_id = (topic_id!== undefined || topic_id=="")?topic_id:"";
 	var keywords = $("#tsearch_topic").val().trim(); 
	
	$.ajax({
		data: "action="+action_type+"&topic_id="+topic_id+"&keywords="+urlencode(keywords)+"&rand="+Math.random(),
		type:"POST",  
		url: "<?=base_url();?>operations/getTutorials",
		beforeSend:function(){       
			$(".topic_link").removeClass("selected_topic");
			$(".selected_h4").append('<img src="media/images/loader.gif" class="username_loader" />'); 
			$(".topic_page").html(""); 
		},
		success:function(msg){    
	 		     
			$(".username_loader").remove(); 
			$("#Topic_"+topic_id).addClass("selected_topic");
			$(".selected_h4").html(text);
			
			if(msg)//>0
			 {  
			 	$("#SelectedTopic").find("ul.the_list").html(msg); 
			 } 
			else
			 {
				//createMessage(result[1], "error"); 
			 }
			 	
		}
		
	}); //end ajax
}

$(function(){
	$(".topic_link").click(function(){
		var topic_id = $(this).attr("topic-id");
		getTutorials("specific", topic_id, $("#Topic_"+topic_id).text());  
	}); 
	//getTutorials("popular", "", "Popular"); 
	
	
	$("#BtnTopicSearch").click(function(){
		var keyword = $("#tsearch_topic").val().trim(); 
		if(keyword) getTutorials("search", "", "<i>Search result(s) for <span class='search_topic_txt'>"+keyword+"</span> </i>");  
	});

}); 
</script>
