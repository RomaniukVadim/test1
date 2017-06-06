<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 170px !important; 
}

.form-horizontal .controls {
    margin-left: 180px;
}

.group-label {
	/*width: 45% !important;*/ 	 
	margin-bottom: 5px;
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_currency" name="validate_currency" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_acurrencyid" id="hidden_acurrencyid" value="<?=$currency->CurrencyID;?>" >
    <input type="hidden" value="<?=($currency->CurrencyID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
  
    <div class="control-group" >   
        <label class="control-label" for="currency_abbreviation">* Abbreviation</label>
        <div class="controls controls-row"  >
            <input type="text" id="currency_abbreviation" name="currency_abbreviation" class="tip span12" value="<?=htmlentities(stripslashes($currency->Abbreviation));?>" maxlength="8" title="Abbreviation" >    
        </div>
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" >   
        <label class="control-label" for="currency_internal">* Internal System Abb.</label>
        <div class="controls controls-row"  >
            <input type="text" id="currency_internal" name="currency_internal" class="tip span12" value="<?=htmlentities(stripslashes($currency->InternalAbbreviation));?>" maxlength="8" title="Abbreviation" >    
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <label class="control-label" for="currency_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="currency_desc" name="currency_desc" class="span12 tip" rows="4" maxlength="200" title="Currency description" ><?=$currency->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" > 
        <label class="control-label" for="currency_ischecking">Include in Checking</label>
        <div class="controls controls-row" >  
            <label class="radio-inline group-label" >
                <input type="radio" name="currency_ischecking" value="1"  <?=($currency->IsChecking==1)?'checked="checked"':"";?> > YES 
            </label>
            <label class="radio-inline group-label" > 
                <input type="radio" name="currency_ischecking" value="0"  <?=($currency->IsChecking==0 || $currency->IsChecking=="")?'checked="checked"':"";?> > NO 
            </label>
        </div>
    </div>
    <!-- End .control-group --> 
     
     
    <div class="control-group" >  
        <label class="control-label" for="currency_status">* Status</label>
        <div class="controls controls-row">  
            <select name="currency_status" id="currency_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($currency->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($currency->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($currency->CurrencyID)?"Update ":"Save new "?> currency</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageCurrency = function() { 
	 
	$.ajax({ 
		data: $("#validate_currency").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>currencies/manageCurrency", 
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
				//$('#validate_currency')[0].reset();
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
	 
	$("#validate_currency").validate({
		 submitHandler: function(form) { 
		 	manageCurrency();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			currency_abbreviation: {
				required: true, 
				minlength: 2
			}, 
			currency_internal: {
				required: true, 
				minlength: 2
			}, 
			/*currency_result: {
				required: true 
			},*/ 
			currency_status: {
				required: true 
			} 
		},
		messages: { 
		   currency_abbreviation: {
				required: "Please enter currency abbreviation" 
			},
		   currency_internal: {
				required: "Please enter abbreviation in Internal System" 
			},	  
		   /*currency_result: {
				required: "Select result" 
			},*/ 
		   currency_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	
	/*$("#currency_result").change(function(){ 
		$("#hidden_aresult").val($(this).find(":selected").text());
	});
	$("#currency_result").trigger("change");*/
	
	$("#currency_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#currency_status").trigger("change");
	
	//clicking checkbox viewers
	$("input:checkbox[name=currency_viewer]").click(function(){  
		var viewers = $('input[name="currency_viewer"]:checked').map(function() {return this.value;}).get().join(',');  
		$("#currency_viewers").val(viewers); 
	});
	$('input[type=checkbox]').uniform();
	  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_currency .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 