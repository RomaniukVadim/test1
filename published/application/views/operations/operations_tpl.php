<!-- Tables plugins -->
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>

<style>
/*.txt {    
	overflow: hidden !important; 
    text-overflow: ellipsis !important; 
	white-space: nowrap !important;   
}*/
</style>

<div class="main">
	
    <?=$sidebar_view;?>
    
	<section id="content">
	<div class="wrapper">
		
        <div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Pages</a><span class="divider">/</span></li>
                <li><a href="#"><?=$page->TopicName;?></a><span class="divider">/</span></li>
				<li class="active"><?=$page->Title;?></li>
			</ul>  
		</div> 
        
		<div class="container-fluid">
        	 
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-books"></i> Pages</h1>
			</div> 
            
			<div class="row-fluid">
            	 
				<div class="span12"> 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-file-8"></i>
							</div>
							<h4><?=$page->Title;?></h4>
							<a href="#" class="minimize"></a>  
                            <!--<div class="w-right">
                                <form class="form-horizontal" name="form_content_search" id="form_content_search" role="form" method="post">
                                    <input type="text" name="page_txtsearch" id="page_txtsearch" class="search-query col-lg-12 form-control" placeholder="Search">
                                </form>
                            </div>
                            <div class="clearfix"> </div> -->
						</div>
						<!-- End .widget-title -->
                         
						<div class="widget-content page_content"> 
                        	<?=$page->Content;?>
                            
                            <div style="clear: both; " ></div>
                            
                            <?php 
							   if($page->File && file_exists("media/uploads/".$page->File))
								{  
							   ?>
							   <div style="padding: 10px 0px 10px 0px; " >
									<a href="javascript:file_download('<?=$page->File?>', '<?=str_replace("%","[percent]",$page->OriginalFile)?>','<?=base_url();?>');" class="tip" title="Download attach file" ><?=$page->OriginalFile;?></a>
							   </div>
							   <?php
								}
							   ?> 	 
                            
						</div>
						<!-- End .widget-content -->
                         
					</div>
					<!-- End .widget -->
                    
				</div>
				<!-- End .span12 -->
                 
			</div>
			<!-- End .row-fluid -->
            
            <!-- BELOW PAGE -->
            <div class="row-fluid">
            	
                <!-- comments or feedback --> 
				<div class="span6" > 
                 
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-bubble-6"></i>
							</div>
							<h4>Comment/Feedback</h4>
							<a href="#" class="minimize"></a>   
						</div>
						<!-- End .widget-title -->
                         
						<div class="widget-content" id="CommentFormPanel" >   
                        
                        	 <div class="panel-body">
                                <form class="form-horizontal pad15 pad-bottom0" role="form" name="comment_form" id="comment_form" >
                                    <div class="form-group">
                                        <input id="comment_subject" name="comment_subject" class="span12 required" type="text" placeholder="Subject">
                                    </div><!-- End .form-group  -->
                                    
                                    <div class="form-group">
                                        <textarea name="comment_message" id="comment_message" class="span12 required" rows="4" placeholder="Type your message here ..." maxlength="250" ></textarea>
                                    </div><!-- End .form-group  --> 
                                    <br />
                                    <div class="form-group">
                                        <!--<button class="btn btn-link tip pad5" title="Insert link"><i class="icon16 i-link gap-left0 gap-right0"></i></button>
                                        <button class="btn btn-link tip pad5" title="Attach file"><i class="icon16 i-file-plus-2 gap-left0 gap-right0"></i></button>
                                        <button class="btn btn-link tip pad5" title="Add video"><i class="icon16 i-camera gap-left0 gap-right0"></i></button>
                                        <button class="btn btn-link gap-right10 tip pad5" title="Upload image"><i class="icon16 i-image-2 gap-left0 gap-right0"></i></button>-->
                                        <input type="hidden" name="hidden_action_comment" id="hidden_action_comment" value="add" />
                                        <input type="hidden" name="comment_pageid" id="comment_pageid" value="<?=$page->PageID;?>" />
                                        <button type="submit" id="BtnSendMessage" class="btn btn-primary pull-right">Send Message</button>
                                    </div><!-- End .form-group  -->
                                </form> 
                                <div class="clearfix"></div> 
                            </div><!-- End .panel-body --> 
                            
						</div>
						<!-- End .widget-content -->
                         
					</div>
					<!-- End .widget -->
                    
				</div>
				<!-- End .span6 -->
                <!-- end comments or feedback -->
                 
                <?php 
				$tags_colors = array("", "label-success", "label-warning", "label-important", "label-info", "label-inverse");
				$tags = explode(",", trim($page->Tags)); 
				if((count($tags) > 0) && (trim($page->Tags !="")) )
				 {
				?>
                <!-- page tags -->
                <div class="span6"> 
                 
					<div class="widget">
                    
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-tag-2"></i>
							</div>
							<h4>TAGS</h4>
							<a href="#" class="minimize"></a>  
						</div>
						<!-- End .widget-title -->
                         
						<div class="widget-content" style="line-height: 20px; !important;" >  
                            
                            <div class="page_tags" > 
                            	<?php
								for($i=0; $i<count($tags); $i++){ 
									$search_txt = base_url()."search/".urlencode($tags[$i]); 
								?>
                                <a href="<?=$search_txt;?>"><span class="label <?=$tags_colors[rand(0,5)]?>"><?=$tags[$i];?></span></a>
                                <?php 
								}
								?>   
                            </div>
                             
						</div>
						<!-- End .widget-content -->
                         
					</div>
					<!-- End .widget -->
                    
				</div>
				<!-- End .span4 -->
                <!-- end page tags -->
                <?php
				 }//end if
				?>
                 
			</div>
			<!-- End .row-fluid -->
            <!-- END BELOW PAGE -->
            
		</div>
		<!-- End .container-fluid -->
	</div>
	<!-- End .wrapper -->
	</section>
    
</div>
<!-- End .main --> 

<!-- Init plugins only for page -->  

<script> 
var orig_html = ""; 
var orig_txt = ""; 

$(function(){ 

	$("#form_content_search").validate({
		 submitHandler: function(form) { 
		 	highlightSearch();
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: {
		},
		messages: {
		}
	});  
	
	$("#comment_form").validate({
		 submitHandler: function(form) {  
		 	submitComment();//submit the comment
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: {
			comment_subject: {
				required: true 
			}, 
			comment_message: {
				required: true, 
				minlength: 10
			} 
		},
		messages: {
			comment_subject: {
				required: "Please provide subject" 
			},
			comment_message: {
				required: "Please provide message", 
				minlength: "Message is minimum of 10 characters. " 
			} 
			
		}
	}); 
	/*orig_html = $(".page_content").html();
	orig_txt = $(".page_content").text(); 
	
	$('#page_txtsearch').keyup(function(){
         var page = $('.page_content');
         var pageText = page.text().replace("<span class='search_highlight_txt'>","").replace("</span>");
         var searchedText = $('#page_txtsearch').val();
         var theRegEx = new RegExp("("+searchedText+")", "igm");    
         var newHtml = pageText.replace(theRegEx ,"<span class='search_highlight_txt' >$1</span>");
         page.html(newHtml);
    });*/
	
});


var highlightSearch = function() { 
	var search_string = $("#page_txtsearch").val();	
	$('div.page_content:contains('+search_string+')', document.body).each(function(){
		  //console.log(this);
		  $(this).html(orig_html.replace(
				new RegExp(search_string, 'g'), '<span class=search_highlight_txt>'+search_string+'</span>'
		  ));
	});  
} 


var submitComment = function() {
	$.ajax({
		data: $('#comment_form').serialize()+"&rand="+Math.random()+"&page_id=<?=$page->PageID?>",
		type:"POST",
		//url: "ajax/banners_ajax.php?rand="+Math.random()+"&image_file="+file, 
		url: "<?=base_url();?>operations/submitComment",
		beforeSend:function(){      
			$(".username_loader").remove();
			$("#BtnSendMessage").addClass("disabled");  
			$("#BtnSendMessage").attr("disabled", "disabled");  
			$("#BtnSendMessage").html("Please Wait ..."); 
			$("#UsernameHolder").append('<img src="media/images/loader.gif" class="username_loader" />');
		},
		success:function(msg){   
			var result = msg.split("|||"); 
			$(".username_loader").remove(); 
			$("#BtnSendMessage").removeClass("disabled"); 
			$("#BtnSendMessage").removeAttr("disabled", "disabled"); 
			$("#BtnSendMessage").html("Send Message");
		 
			if(result[0] > 0)
			 {   
				createMessageMini($("#CommentFormPanel"), result[1], "success"); 
				$('#comment_form')[0].reset();
			 } 
			else
			 { 
				createMessageMini($("#CommentFormPanel"), result[1], "error");   
			 }
			
			 
			 		
		}
		
	}); //end ajax 
}

</script>

