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
	width: 30% !important; 	 
	margin-bottom: 0px;
} 

/*.user-type label:last-child {
	margin-bottom: 5px !important; 	
	background-color: red !important; 
}*/

.user-type label.error { 
	width: 90% !important;  	
}

</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_type" name="validate_type" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_atypeid" id="hidden_atypeid" value="<?=$type->GroupID;?>" >
    <input type="hidden" value="<?=($type->GroupID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
        <label class="control-label" for="type_name">* User Type</label>
        <div class="controls controls-row"  >
            <input type="text" id="type_name" name="type_name" class="tip span8" value="<?=htmlentities(stripslashes($type->Name));?>" maxlength="80" title="User Type" >    
        </div>
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" >   
        <label class="control-label" for="type_group">* Can Assign</label>
        <div class="controls controls-row user-type">
            <?php
			$user_types = explode(',', $type->CanAssign);
			foreach($types as $row=>$typex){
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="type_group[]" value="<?=$typex->GroupID?>" <?=(in_array($typex->GroupID, $user_types))?'checked="checked"':"";?> > <?=$typex->UserTypeName?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="type_canassign" id="type_canassign" value=""  /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
        <label class="control-label" for="type_override"> Can Override</label>
        <div class="controls controls-row user-type">
            <?php
			$canoverride_types = explode(',', $type->CanOverride);
			foreach($types as $row=>$typex){
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="type_override[]" value="<?=$typex->GroupID?>" <?=(in_array($typex->GroupID, $canoverride_types))?'checked="checked"':"";?> > <?=$typex->UserTypeName?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="type_canoverride" id="type_canoverride" value=""  /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <label class="control-label" for="type_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="type_desc" name="type_desc" class="span12 tip" rows="2" maxlength="200" title="User type description" ><?=$type->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group -->  
     
     
    <div class="control-group" >  
        <label class="control-label" for="type_status">* Status</label>
        <div class="controls controls-row">  
            <select name="type_status" id="type_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($type->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($type->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
					<?php
                    }
                    ?>
                </optgroup> 
            </select>     
            <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" /> 
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="form-actions"> 
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($type->GroupID)?"Update ":"Save new "?> user type</button>
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
var selected_values_override = "";
var manageUserType = function() { 
	 
	$.ajax({ 
		data: $("#validate_type").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>user_types/manageUserType", 
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
				//$('#validate_type')[0].reset();
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
	
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_type").validate({
		 submitHandler: function(form) { 
		 	manageUserType();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			type_name: {
				required: true, 
				minlength: 2
			},  
			type_status: {
				required: true 
			}, 
			type_canassign: {
				required: true 
			}  
		},
		messages: { 
		   type_name: {
				required: "Please enter user type name" 
			},    
		   type_status: {
				required: "Select status" 
			}, 
		   type_canassign: {
				required: "Please select atleast one can assign" 
			}
		}
	}); 
	    
	
	$("#source_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#source_status").trigger("change");
	 
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_type .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	
	$("input:checkbox[name='type_group[]']").click(function(){ 
		 selected_values = $("input[name='type_group[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#type_canassign").val(selected_values);   
	}); 
	selected_values = $("input[name='type_group[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("#type_canassign").val(selected_values);
	
	$("input:checkbox[name='type_override[]']").click(function(){ 
		 selected_values_override = $("input[name='type_override[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#type_canoverride").val(selected_values_override);   
	}); 
	selected_values_override = $("input[name='type_override[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("#type_canoverride").val(selected_values_override); 
	
}); 
 

</script> 