<script src="<?=base_url()?>media/js/jquery.form.js" type="text/javascript"></script>

<!-- container-fluid -->
<div class="container-fluid">
     
     <!-- login -->
     <div id="login" >
		<div class="login-wrapper" data-active="log">
        
			<a class="brand" >
            	<span class="compname_log"  > 
                    <span ><i class="icon20 i-phone-6"></i>ustOmer </span>  
                    <div class="text" >Activity Log</div> 
                </span>   
            </a>
			
            <div id="log" >
             
				<div class="page-header">
					<h3 class="center">Please login</h3>
				</div>
				
                <form id="loginForm" name="loginForm" class="form-horizontal widget-content" method="POST" >
                    
					<div class="row-fluid">
						<div class="control-group">
							<div class="controls-row">
								<div class="icon">
									<i class="icon20 i-user"></i>
								</div>
								<input class="span12" type="text" name="username" id="username" placeholder="Username" value="">
							</div>
						</div>
						<!-- End .control-group -->
						<div class="control-group">
							<div class="controls-row">
								<div class="icon">
									<i class="icon20 i-key"></i>
								</div>
								<input class="span12" type="password" name="password" id="password" placeholder="Password" value="">
							</div>
						</div>
						<!-- End .control-group -->
						<div class="form-actions full">
                            <button id="BtnSubmitLogin" type="submit" class="btn btn-primary pull-right span5" >Login</button>
						</div>
					</div>
					<!-- End .row-fluid -->
				</form>
				
                
			</div>
            
		</div>
		
		<div class="clearfix">
		</div>
        
	</div>
    <script>  
	 
	
	$(function(){
		  
		 var btn = $('#BtnSubmitLogin');
		 // To initially run the function:
		 $(window).resize();
		 
		 $("#username, #password").keypress(function(e){
			if(e.which == 13) {
				$("#loginForm").submit();
			}
		 });
		 
		 $("#BtnSubmitLogin").click(function(){
			//$("#loginForm").submit();
		 });
		
		 $("#loginForm").ajaxForm({
			url: "<?=base_url()?>login/validate_user",
			type: "POST",
			dataType: 'json', 
			cache: false,
			async: false,
			beforeSubmit: function(){  
				var toSubmit = true; 
				var error = "";  
				
				btn.removeClass('btn-primary');
				btn.addClass('btn-danger');
				btn.text('Checking ...');
				btn.attr('disabled', 'disabled');  
				
				if($.trim($("#username").val()) == "" || $("#password").val() == ""  ){ 
					error += "Enter your username and password! ";
				 } 
				else{
					toSubmit = false; 
					$("#username, #password").addClass("valid");
				}
				
				if(error)
				 {
					toSubmit = false; 
					$("#username, #password").removeClass("valid");
					createMessage(error, "error", ""); 
					btn.removeClass('btn-danger');  
					btn.addClass('btn-primary');
					btn.text('Login');
					btn.removeAttr('disabled');  
					return false
				 }
				
			},
			success: function(response){       
				if(response.has_err){ 
					$("#username, #password").removeClass("valid");
					createMessage("", response.msg, "error", "");   
					btn.removeClass('btn-danger');  
					btn.addClass('btn-primary');
					btn.text('Login');
					btn.removeAttr('disabled'); 
				}
				else{ 
					//login success
					//var controller = (response.is_admin > 0)?"dashboard":"operations"; 
					setTimeout(function() {
						btn.removeClass('btn-danger');
						btn.addClass('btn-success');
						btn.text('User find ...'); 
						fadeOutRedirect("<?=base_url()?>" + response.url);    
					}, 1500);
					
				}
			}//end success
			
		 }); 
		 
	});
	</script> 
    <!-- end login -->
    
    
</div>
<!-- End .container-fluid --> 