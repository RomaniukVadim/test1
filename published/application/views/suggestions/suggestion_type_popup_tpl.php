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

</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_category" name="validate_category" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_atypeid" id="hidden_atypeid" value="<?=$type->ComplaintID;?>" >
    <input type="hidden" value="<?=($type->ComplaintID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
      
    <div class="control-group" >   
        <label class="control-label" for="type_name">* Complaint Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="type_name" name="type_name" class="tip span12" value="<?=htmlentities(stripslashes($type->Name));?>" maxlength="80" title="Complaint Name" > 
        </div>
    </div>
    <!-- End .control-group --> 
   
    <div class="control-group" > 
        <label class="control-label" for="type_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="type_desc" name="type_desc" class="span12" rows="4" maxlength="200" ><?=$type->Description?></textarea>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($type->ComplaintID)?"Update ":"Save new "?> type</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageSuggetionType = function() { 
	 
	$.ajax({ 
		data: $("#validate_category").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>suggestion_types/manageSuggetionType", 
		dataType: "JSON",    
		cache: false,
		beforeSend:function(){       
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
			$("html, body").animate({ scrollTop: 0 }, "slow"); 
		},
		success:function(msg){     
			is_change = (msg.is_change > 0)?1:0;  
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled");
			
			if(msg.success > 0)
			 {   
			 	createMessageMini($(".form-widget-content"), msg.message, "success"); 
				//$('#validate_category')[0].reset();
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
	
	e.preventDefault();
	  
} 
 
</script>

<script> 
$(function() {  
	
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_category").validate({
		 submitHandler: function(form) { 
		 	manageSuggetionType();//
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
			} 
		},
		messages: {
			type_status: {
				required: "Select status" 
			},  
			type_name: {
				required: "Please type name" 
			}  
			  
			
		}
	}); 
	   
	
	$("#type_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#type_status").trigger("change");
	
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_category .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 