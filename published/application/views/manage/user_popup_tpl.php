<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 130px !important; 
}

.form-horizontal .controls {
    margin-left: 150px;
}

.group-label {
	width: 20% !important; 	 
	margin-bottom: 5px;
} 

.currency label.error { 
	width: 90% !important;  	
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_group" name="validate_group" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_auserid" id="hidden_auserid" value="<?=$user->mb_no;?>" >
    <input type="hidden" value="<?=($user->mb_no)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
    	<div class="span6" > 
        	<label class="control-label" for="user_name">* Username</label>
            <div class="controls controls-row"  >
                <input type="text" id="user_name" name="user_name" class="tip span12" value="<?=htmlentities(stripslashes($user->mb_username));?>" maxlength="80" title="Username" >    
            </div>
        </div>
        
        <div class="span6" > 
        	<label class="control-label" for="user_password"><?=($user->mb_no)?"":"*";?> Password</label>
            <div class="controls controls-row"  >
                <input type="password" id="user_password" name="user_password" class="tip span12" value="" maxlength="80" title="Password" >    
            </div>
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >   
    	<div class="span6" > 
        	<label class="control-label" for="user_nickname">* Nickname</label>
            <div class="controls controls-row"  >
                <input type="text" id="user_nickname" name="user_nickname" class="tip span12" value="<?=htmlentities(stripslashes($user->mb_nick));?>" maxlength="80" title="Nickname" >    
            </div>
        </div>
        
        <div class="span6" > 
        	<label class="control-label" for="user_email">* Email Address</label>
            <div class="controls controls-row"  >
                <input type="text" id="user_email" name="user_email" class="tip span12" value="<?=htmlentities(stripslashes($user->mb_email));?>" maxlength="80" title="Email address" >    
            </div>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
    	<div class="span6" > 
        	<label class="control-label" for="user_idno">* ID No.</label>
            <div class="controls controls-row"  >
                <input type="text" id="user_idno" name="user_idno" class="tip span12" value="<?=htmlentities(stripslashes($user->mb_id));?>" maxlength="80" title="ID No." >    
            </div>
        </div>
        
        <div class="span6" > 
        	<label class="control-label" for="user_internal">Internal Username</label>
            <div class="controls controls-row"  >
                <input type="text" id="user_internal" name="user_internal" class="tip span12" value="<?=htmlentities(stripslashes($user->mb_internal_user));?>" maxlength="80" title="Internal System Username" >    
            </div>
        </div>
         
    </div>
    <!-- End .control-group --> 
     
    <div class="control-group" >   
        <label class="control-label" for="user_cur">* Market</label>
        <div class="controls controls-row currency">
            <?php
			$cur_arr = explode(',', $user->mb_currencies);
			foreach($currencies as $row=>$currency){
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="user_cur[]" value="<?=$currency->CurrencyID?>"  <?=(in_array($currency->CurrencyID, $cur_arr))?'checked="checked"':"";?> > <?=$currency->Abbreviation?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="user_currencies" id="user_currencies" value=""  /> 
        </div>
    </div>
    <!-- End .control-group --> 
  	
    <div class="control-group" >  
       
       <div class="span6" > 
        	<label class="control-label" for="user_type">* User Type</label>
            <div class="controls controls-row">  
                <select name="user_type" id="user_type" class="required myselect" > 
                    <optgroup label="" >    
                         <option value="" <?php if($user->user_status=='') echo "selected='selected'";?> >&nbsp;</option>
                        <?php
						$restrict_type = explode(',' ,$this->access[$this->session->userdata('mb_usertype')]);   
                        foreach($user_types as $row => $user_type){  
                         	if( (admin_only() || admin_access()) || (in_array($user_type->GroupID,$restrict_type)) || manage_user() )
							 {
                        ?>
                        <option  value="<?=$user_type->GroupID;?>" <?php if($user->mb_usertype==$user_type->GroupID) echo "selected='selected'";?> ><?=ucwords($user_type->UserTypeName);?></option>
                        <?php
							 }
                        }
                        ?>
                    </optgroup> 
                </select>     
                <input type="hidden"  name="hidden_atype" id="hidden_atype" value="" /> 
            </div>
        </div>
        
        <div class="span6" > 
        	<label class="control-label" for="user_level">* Level</label>
            <div class="controls controls-row"  >
                <select name="user_level" id="user_level" class="required myselect" > 
                    <optgroup label="" >     
                        <?php
                        for($i=1; $i<=10; $i++){   
                        ?>
                        <option  value="<?=$i;?>" <?php if($user->mb_level==$i) echo "selected='selected'";?> ><?=$i?></option>
                        <?php
                        }
                        ?>
                    </optgroup> 
                </select>     
                <input type="hidden"  name="hidden_alevel" id="hidden_alevel" value="" />
            </div>
        </div>
        
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >  
        <label class="control-label" for="user_status">* Status</label>
        <div class="controls controls-row">  
            <select name="user_status" id="user_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($user->mb_status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($user->mb_status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
					<?php
                    }
                    ?>
                </optgroup> 
            </select>   
            <input type="hidden"  name="hidden_astatus" id="hidden_astatus" value="" /> 
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="form-actions"> 
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($user->mb_no)?"Update ":"Save new "?> user</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script > 
var selected_values = "";

var managUser = function() { 
	 
	$.ajax({ 
		data: $("#validate_group").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>users/manageUser", 
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
				//$('#validate_group')[0].reset();
				clearSelectbox($("div.controls"));  
				$("ul.select2-choices li.select2-search-choice").remove();
				setTimeout(function(){
					$('.modal').find('.close').trigger("click");
			    }, 2000);
				$('input[type=checkbox]').uniform();   
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
	
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_group").validate({
		 submitHandler: function(form) {  
		 	managUser();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			user_name: {
				required: true, 
				minlength: 2, 
				alphaUsername: true 
			}, 
			user_password: {
				<?php
				if($user->mb_no == "")
				 {
				?>
				required: true, 
				<?php	 
				 }
				?>	
				minlength: 5
			}, 
			user_nickname: {
				required: true 
			}, 
			user_email: {
				required: true, 
				email: true  
			}, 
			user_idno: {
				required: true
			}, 
			user_currencies: {
				required: true 
			}, 
			user_type: {
				required: true 
			}, 
			user_level: {
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
			<?php
			if($user->mb_no == "")
			 {
			?>
			user_password: {
				required: "Please enter password"
			},
			<?php	 
			 }
			?>
			user_nickname: {
				required: "Please enter nickname" 
			}, 
			user_email: {
				required: "Please enter email address", 
				email: "Please enter correct email address"  
			}, 
			user_idno: {
				required: "Please enter ID No."
			}, 
			user_currencies: {
				required: "Please select atleast one currency" 
			}, 
			user_type: {
				required: "Please select user type"
			}, 
			user_level: {
				required: "Please select level" 
			}, 
			user_status: {
				required: "Please select status" 
			} 
		}
	}); 
	   
	
	$("#user_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#user_status").trigger("change");
	
	$("#user_type").change(function(){ 
		$("#hidden_atype").val($(this).find(":selected").text());
	});
	$("#user_type").trigger("change");
	
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_group .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
	$("input:checkbox[name='user_cur[]']").click(function(){ 
		 selected_values = $("input[name='user_cur[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#user_currencies").val(selected_values);   
	}); 
	selected_values = $("input[name='user_cur[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("#user_currencies").val(selected_values); 
	
}); 
 

</script> 