
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
     
     <!-- login -->
     <div id="login" >
		<div class="login-wrapper" data-active="log">
        
			<!--<a class="brand" href="dashboard.html"><img src="images/logodark.png" alt="Genyx admin"></a>-->
			
            <div id="log" >
             
				<div class="page-header">
					<h3 class="center"><?=($header_title)?$header_title:"404 Page Not Found";?></h3>
				</div>
				
                <div class="row-fluid">
                	<?=($error_message)?$error_message:"The page you requested was not found.";?> 
                    
                     <div class="form-actions full error_btncontainer" >
                        <?php /*?><button id="BtnSubmitLogin" type="submit" class="btn btn-primary span5 no_float" onclick='window.location = "<?=base_url();?>dashboard";' >Home</button> <?php */?>
                        <button id="BtnSubmitLogin" type="submit" class="btn btn-primary span5 no_float" onclick='window.location = "<?=base_url();?>dashboard";' >Home</button> 
                    </div>
                    
                </div>
               
                
			</div>
            
		</div>
		
		<div class="clearfix">
		</div>
        
	</div>
    
    
</div>
<!-- End .container-fluid --> 