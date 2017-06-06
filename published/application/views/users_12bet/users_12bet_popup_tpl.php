<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 150px !important; 
}

.form-horizontal .controls {
    margin-left: 170px;
}

.group-label {
	/*width: 45% !important;*/ 	 
	margin-bottom: 5px;
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_user" name="validate_user" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_auserid" id="hidden_auserid" value="<?=$user->UserID;?>" >
    <input type="hidden" value="<?=($user->UserID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
        <label class="control-label" for="user_name">* Username</label>
        <div class="controls controls-row"  >
            <input type="text" id="user_name" name="user_name" class="tip span12" value="<?=htmlentities(stripslashes($user->Username));?>" maxlength="80" title="Username" >    
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >   
        <label class="control-label" for="user_systemid">* SystemID</label>
        <div class="controls controls-row"  >
            <input type="text" id="user_systemid" name="user_systemid" class="tip span12" value="<?=htmlentities(stripslashes($user->SystemID));?>" maxlength="80" title="System ID" >    
        </div>
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" >   
        <label class="control-label" for="user_currency">* Currency</label>
        <div class="controls controls-row"  >
            <select name="user_currency" id="user_currency" class="required myselect" > 
                <optgroup label="Select Currency"> 
                    <option value="" <?php if($activity->Currency=="") echo "selected='selected'";?> ></option>
                    <?php
                    foreach($currencies as $row => $currency){ 
                        ?>
                    <option value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $user->Currency) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
                        <?php 
                        }
                    ?>
                     
                </optgroup>  
                
            </select> 
            <input type="hidden" name="hidden_acurrency" id="hidden_acurrency" value="" /> 
        </div>
    </div>
    <!-- End .control-group -->  
      
    <div class="control-group" >  
        <label class="control-label" for="user_status">* Status</label>
        <div class="controls controls-row">  
            <select name="user_status" id="user_status" class="required myselect" > 
                <optgroup label="" >    
                	<option value="1" >Active</option>
                    <?php /*?><option value="" <?php if($user->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($user->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
					<?php
                    }
                    ?><?php */?>
                </optgroup> 
            </select>     
            <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" /> 
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="form-actions"> 
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($user->UserID)?"Update ":"Save new "?> user</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageUser12Bet = function() { 
	 
	$.ajax({ 
		data: $("#validate_user").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>users_12bet/manageUser12Bet", 
		dataType: "JSON",    
		cache: false,
		beforeSend:function(){       
			//$("#BtnSubmitForm").addClass("disabled");  
			//$("#BtnSubmitForm").attr("disabled", "disabled");    
			$("html, body").animate({ scrollTop: 0 }, "slow"); 
		},
		success:function(msg){     
			//$("#BtnSubmitForm").removeClass("disabled"); 
			//$("#BtnSubmitForm").removeAttr("disabled", "disabled");
			
			if(msg.success > 0)
			 {   
				is_change = (msg.is_change > 0)?1:0;   				 
			 	createMessageMini($(".form-widget-content"), msg.message, "success"); 
				//$('#validate_user')[0].reset();
				clearSelectbox($("div.controls"));  
				$("ul.select2-choices li.select2-search-choice").remove();
				setTimeout(function(){
					$('.modal').find('.close').trigger("click");
			    }, 2000);
				 
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
	 
	/*$('#act_user').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_user").validate({
		 submitHandler: function(form) { 
		 	manageUser12Bet();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			user_name: {
				required: true 
			}, 
			user_systemid: {
				required: true 
			}, 
			user_currency: {
				required: true 
			}, 
			user_status: {
				required: true 
			} 
		},
		messages: { 
		   user_name: {
				required: "Please enter username" 
			},
		   user_systemid: {
				required: "Please enter System ID" 
			},   
		   user_currency: {
				required: "Select currency" 
			}, 
		   user_status: {
				required: "Select status" 
			}
		}
	}); 
	    
	
	$("#user_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#user_status").trigger("change");
	
	$("#user_currency").change(function(){     
		$("#hidden_acurrency").val($(this).find(":selected").text());  
	}); 
	$("#user_currency").trigger("change"); 
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_user .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	
	$('select').select2({placeholder: "Select"});
	
}); 
 

</script> 