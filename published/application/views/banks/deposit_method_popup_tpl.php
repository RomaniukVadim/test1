<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 120px !important; 
}

.form-horizontal .controls {
    margin-left: 140px;
}

</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate_method" name="validate_method" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_amethodid" id="hidden_amethodid" value="<?=$method->MethodID;?>" >
    <input type="hidden" value="<?=($method->MethodID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
    
    <div class="control-group" >   
        <label class="control-label" for="method_name">* Method Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="method_name" name="method_name" class="tip span12" value="<?=htmlentities(stripslashes($method->Name));?>" maxlength="80" title="Method Name" > 
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >  
        <label class="control-label" for="act_status">* Currency</label>
        <div class="controls controls-row">  
            <select name="method_currency" id="method_currency" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($method->CurrencyID=="") echo "selected='selected'";?> ></option>
                    <?php
                    foreach($currencies as $row => $currency){  
                    ?>
                    <option  value="<?=$currency->CurrencyID;?>" <?php if($method->CurrencyID==$currency->CurrencyID) echo "selected='selected'";?> ><?=ucwords($currency->Abbreviation);?></option>
                    <?php     
                        }
                    ?>
                </optgroup> 
            </select>     
            <input type="hidden" name="hidden_astatus" id="hidden_acurrency" value="" /> 
        </div>
    </div>
    <!-- End .control-group --> 
          
    <div class="control-group" > 
        <label class="control-label" for="act_remarks">Description</label>
        <div class="controls controls-row" >  
            <textarea id="method_desc" name="method_desc" class="span12" rows="4" maxlength="200" ><?=$method->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >  
        <label class="control-label" for="method_status">* Status</label>
        <div class="controls controls-row">  
            <select name="method_status" id="method_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($method->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($method->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" >Save changes</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageDepositMethod = function() { 
	 
	$.ajax({ 
		data: $("#validate_method").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>deposit_methods/manageDepositMethod", 
		dataType: "JSON",    
		cache: false,
		beforeSend:function(){       
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
			$("html, body").animate({ scrollTop: 0 }, "slow"); 
		},
		success:function(msg){     
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled");
			
			if(msg.success > 0)
			 {  
			 	is_change = (msg.is_change > 0)?1:0;  
			 	createMessageMini($(".form-widget-content"), msg.message, "success"); 
				//$('#validate_method')[0].reset();
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
var formdata; //for upload 
var to_upload = 0; 
var upload_error = 0; 
var selected_lang = ""; 
$(function() {  
	
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_method").validate({
		 submitHandler: function(form) { 
		 	manageDepositMethod();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			method_name: {
				required: true, 
				minlength: 2
			},
			method_currency: {
				required: true 
			},
			method_status: {
				required: true 
			} 
		},
		messages: {
			method_status: {
				required: "Select status" 
			}, 
			method_currency: {
				required: "Please provide remarks" 
			}, 
			method_name: {
				required: "Please method name" 
			}  
			  
			
		}
	}); 
	  
	$("#method_currency").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>');  
		$("#hidden_acurrency").val($(this).find(":selected").text()); 
	}); 
	$("#method_currency").trigger("change");  
	
	$("#method_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#method_status").trigger("change");
	
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_method .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 