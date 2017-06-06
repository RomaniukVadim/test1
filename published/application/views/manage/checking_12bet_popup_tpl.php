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
<form id="validate_checking" name="validate_checking" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_acheckingid" id="hidden_acheckingid" value="<?=$check->UrlID;?>" >
    <input type="hidden" value="<?=($check->UrlID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
        <label class="control-label" for="checking_name">* Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="checking_name" name="checking_name" class="tip span12" value="<?=htmlentities(stripslashes($check->UrlName));?>" maxlength="80" title="Abbreviation" >    
        </div>
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" >  
        <label class="control-label" for="checking_category">* Category</label>
        <div class="controls controls-row">  
            <select name="checking_category" id="checking_category" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($check->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($categories as $row => $category){  
					 
                    ?>
                    <option  value="<?=$category->CategoryID;?>" <?php if($check->Category==$category->CategoryID) echo "selected='selected'";?> ><?=ucwords($category->CategoryName);?></option>
					<?php
                    }
                    ?>
                </optgroup> 
            </select>     
            <input type="hidden" name="hidden_acategory" id="hidden_acategory" value="" /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >  
        <label class="control-label" for="checking_currency">* Currency</label>
        <div class="controls controls-row">  
            <select name="checking_currency" id="checking_currency" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($check->Currency=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($currencies as $row => $currency){  
					 
                    ?>
                    <option  value="<?=$currency->CurrencyID;?>" <?php if($check->Currency==$currency->CurrencyID) echo "selected='selected'";?> ><?=ucwords($currency->Abbreviation);?></option>
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
        <label class="control-label" for="checking_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="checking_desc" name="checking_desc" class="span12 tip" rows="3" maxlength="200" title="Description" ><?=$check->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group --> 
       
    <div class="control-group" >  
        <label class="control-label" for="checking_status">* Status</label>
        <div class="controls controls-row">  
            <select name="checking_status" id="checking_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($check->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($check->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($check->UrlID)?"Update ":"Save new "?> checking</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageChecking12bet = function() { 
	 
	$.ajax({ 
		data: $("#validate_checking").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>checking_12bet/manageChecking12bet", 
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
				//$('#validate_checking')[0].reset();
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
	 
	$("#validate_checking").validate({
		 submitHandler: function(form) { 
		 	manageChecking12bet();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			checking_name: {
				required: true, 
				minlength: 2
			}, 
		   checking_categroy: {
				required: true 
			}, 
		   checking_currency: {
				required: true 
			}, 
			checking_status: {
				required: true 
			} 
		},
		messages: { 
		   checking_name: {
				required: "Please enter name" 
			},   
		   checking_category: {
				required: "Select category" 
			},
		   checking_currency: {
				required: "Select currency" 
			},	 
		   checking_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	
	$("#checking_category").change(function(){ 
		$("#hidden_acategory").val($(this).find(":selected").text());
	});
	$("#checking_category").trigger("change");
	
	$("#checking_currency").change(function(){ 
		$("#hidden_acurrency").val($(this).find(":selected").text());
	});
	$("#checking_currency").trigger("change");
	
	$("#checking_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#checking_status").trigger("change");
	 
	 
	 
	$('input[type=checkbox]').uniform();
	  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_checking .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 