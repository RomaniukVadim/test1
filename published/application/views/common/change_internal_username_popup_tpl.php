<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>

<style>
.form-horizontal .control-label { 
    width: 140px;
}

.form-horizontal .controls {
    margin-left: 160px;
}

  
#ChangePasswordLoader label.error { 
	position: absolute; 
	top: 90px; 
}
/*.wstep {
	cursor: pointer; 	
}*/
</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate" name="validate" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >   
    <input type="hidden" value="update" name="hidden_action" id="hidden_action" >   
    <div class="control-group" >  
       <label class="control-label" for="user_internal">Internal Username</label>
       <div class="controls controls-row"  >
            <input type="text" id="user_internal" name="user_internal" class="span12 tip" value="<?=$this->session->userdata('mb_internal_user')?>" maxlength="40" title="Internal System Username" > 
       </div>    
         
    </div>
    <!-- End .control-group --> 
     
    
    <div class="form-actions"> 
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" >Submit</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->


<!-- Init plugins only for page --> 
<script >
var changePassword = function() { 
	 
	$.ajax({   
		data: $("#validate").serialize(),
		type:"POST",  
		url: "<?=base_url();?>manage/changeInternaUsername", 
		dataType: "JSON",    
		cache: false,
		beforeSend:function(){       
			//$("#BtnSubmitForm").addClass("disabled");  
			//$("#BtnSubmitForm").attr("disabled", "disabled");    
		},
		success:function(msg){      
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled"); 
			if(msg.success > 0)
			 {  
			 	is_change = (msg.is_change > 0)?1:0;  
			 	createMessageMini($(".form-widget-content"), msg.message, "success");  
				//$('#validate')[0].reset();
				setTimeout(function(){
					$('.modal').find('.close').trigger("click");
			    }, 2000);
				
				//clearSelectbox($("div.controls"));  
				//$("ul.select2-choices li.select2-search-choice").remove();  
				//$.uniform.update("input[type=checkbox], input[type=radio]");  
			 } 
			else
			 {
				createMessageMini($(".form-widget-content"), msg.message, "error"); 
			 } 
			  
		}
		 
	}); //end ajax
	
	//e.preventDefault();
	  
} 

 

</script>

<script>  
$(function() {  
	//$.fn.modal.Constructor.prototype.enforceFocus = function() {};
	
 	//$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate").validate({
		 submitHandler: function(form) { 
		 	changePassword();//check duplicate username 
		},
		invalidHandler: function(event, validator) { 
			//createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			user_internal: {  
				minlength: 3, 
				maxlength: 20, 
				required: true
			} 
		},
		messages: { 
			user_internal: {
				required: "Enter username in Internal System"	
			}
		}
	}); 
	  
	//------------- Form validation -------------//
	//$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
	   
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation(); 
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	 
	
	
}); 
 

</script> 