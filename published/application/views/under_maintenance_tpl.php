<script type="text/javascript" src="<?=base_url();?>media/js/plugins/countdown-timer/jquery.countdown.min.js"></script>
<style>
.error_btncontainer {
	margin: 0px;  
	padding: 20px 0px 20px 0px !important;	
}   

.error_btncontainer button{
	margin: 0 auto !important;
} 

.no_float {
	float:none !important; 	
	margin: 0px !important;  
	padding: 0px !important;
}
</style>

<!-- container-fluid -->  
<div class="container-fluid"> 

	<div class="errorContainer" style="display: block; " >  
    
    	<div class="page-header"> 
        	<h1 class="center offline"><?=($header_title)?$header_title:"503 Service Unavailable";?></h1> 
        </div> 
        
        <h2 class="center gap20"><?=($error_message)?$error_message:"Some big improvements be made. Please come back later!";?> </h2> 
        
        <div class="center gap-bottom5"> 
        	
            <div class="or center">
            
            	<strong>Keep in touch</strong>
            
            </div> 
            
            <hr class="seperator"> 
            
            <?php /*?><a href="#" class="btn btn-primary pull-left gap-left20" style="margin-right:10px;"><i class="icon16 i-facebook gap-left0"></i> Facebook</a> 
            <a href="#" class="btn btn-info"><i class="icon16 i-twitter gap-left0"></i> Twitter</a> 
            <a href="#" class="btn btn-danger pull-right gap-right20"><i class="icon16 i-google-plus gap-left0"></i> Google</a> <?php */?> 
            <h1>
            	<i class="icon50 i-clock-6"></i> <span class="countdown-container center offline act-primary" id="timer"></span> 
            </h1>
        </div> 
        
   </div> 
   
</div> 


<!-- End .container-fluid -->  
 
<script type="text/javascript">  
//var redirect_url = "<?=base_url("dashboard")?>";
var redirect_url = "http://psbcal.12csd.com/dashboard";
function redirect() {
	//window.location = "<?=base_url("dashboard")?>";	 
	$.ajax({ 
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>error/checkUptime",  
		beforeSend:function(){   
			//show loading 
			searchLoading("show");  
		},
		success:function(newdata){   
			 if(newdata.live_system == 1)
			  {
				 window.location = redirect_url;	 
			  }
			 else
			  {
				 $("#timer")
					.countdown(newdata.maintenance_uptime, function(event) {
						$(this).text(
							event.strftime('%H : %M : %S') //event.strftime('%D days %H:%M:%S')
						);
					}).on('finish.countdown', redirect); 
			  }
		}
			
	}); //end ajax
}

$("#timer")
.countdown("<?=$maintenance_uptime?>", function(event) {
	$(this).text(
		event.strftime('%H : %M : %S') //event.strftime('%D days %H:%M:%S')
	);
}).on('finish.countdown', redirect);

</script> 

<script>
$(function(){
	$("#themerBtn").hide(); 
})
</script>