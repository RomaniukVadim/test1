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
	width: 15% !important; 	 
	margin-bottom: 5px;
} 

.currency label.error { 
	width: 90% !important;  	
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_url" name="validate_url" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_urlid" id="hidden_urlid" value="<?=$url->UrlID;?>" >
    <input type="hidden" value="<?=($url->UrlID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
    	<label class="control-label" for="url_name">* URL</label>
        <div class="controls controls-row"  >
            <input type="text" id="url_name" name="url_name" class="tip span8" value="<?=htmlentities(stripslashes($url->Url));?>" maxlength="150" title="URL" >    
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >   
    	<label class="control-label" for="url_description">Description</label>
        <div class="controls controls-row" >  
            <textarea id="url_description" name="url_description" class="span12 tip" rows="2" maxlength="500" placeholder="<?=htmlentities(stripslashes($url->Description))?>"   title="Description" ></textarea>
        </div>
    </div>
    <!-- End .control-group --> 
     
    <div class="control-group" >   
        <label class="control-label" for="url_cur">* Market</label>
        <div class="controls controls-row currency">
            <?php
			$cur_arr = explode(',', $url->Currencies);
			foreach($currencies as $row=>$currency){  
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="url_cur[]" value="<?=$currency->CurrencyID?>"  <?=(in_array($currency->CurrencyID, $cur_arr))?'checked="checked"':"";?> > <?=$currency->Abbreviation?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="url_currencies" id="url_currencies" value=""  /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
        <label class="control-label" for="url_blo">Blocked Market</label>
        <div class="controls controls-row currency">
            <?php
			$cur_arr_blo = explode(',', $url->BlockedCurrencies);
			foreach($currencies as $row=>$currency){
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="url_blo[]" value="<?=$currency->CurrencyID?>"  <?=(in_array($currency->CurrencyID, $cur_arr_blo))?'checked="checked"':"";?> > <?=$currency->Abbreviation?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="url_blocked" id="url_blocked" value=""  /> 
        </div>
    </div> 
    <!-- End .control-group --> 
  	  
    <div class="control-group" >  
        <label class="control-label" for="url_status">* Status</label>
        <div class="controls controls-row">  
            <select name="url_status" id="url_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($url->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 $def_stat = ($url->Status)?$url->Status:$def_stat; 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($def_stat==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($url->UrlID)?"Update ":"Save new "?> URL</button>
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
var selected_values_blo = "";

var manageBackupUrl = function() { 
 
	$.ajax({ 
		data: $("#validate_url").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>backup_url/manageBackupUrl", 
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
				//$('#validate_url')[0].reset();
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
	 
	$("#validate_url").validate({
		 submitHandler: function(form) {  
		 	manageBackupUrl();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			url_name: {
				required: true,  
				url: true 
			},   
			url_currencies: {
				required: true 
			},   
			url_status: {
				required: true 
			} 
		},
		messages: { 
		   url_name: {
				required: "Please enter URL"
			},  
			url_currencies: {
				required: "Please select atleast one currency" 
			},  
			url_status: {
				required: "Please select status" 
			} 
		}
	}); 
	   
	
	$("#url_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#url_status").trigger("change");
	
	$("#url_type").change(function(){ 
		$("#hidden_atype").val($(this).find(":selected").text());
	});
	$("#url_type").trigger("change");
	
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_url .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
	$("input:checkbox[name='url_cur[]']").click(function(){ 
		 selected_values = $("input[name='url_cur[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#url_currencies").val(selected_values);   
	}); 
	selected_values = $("input[name='url_cur[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("#url_currencies").val(selected_values);
	
	$("input:checkbox[name='url_blo[]']").click(function(){ 
		 selected_values_blo = $("input[name='url_blo[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#url_blocked").val(selected_values);   
	}); 
	selected_values_blo = $("input[name='url_blo[]']:checked").map(function() {return this.value;}).get().join(',');   
	
	
	$("#CheckboxAll").click(function () {  
        if ($("#CheckboxAll").is(':checked')) {
            $("input:checkbox[name='check_url[]']").prop("checked", true); 
        } else {
            $("input:checkbox[name='check_url[]']").prop("checked", false); 
        }
	
	}); 
	
	
	
	$("#url_blocked").val(selected_values); 
	
}); 
 

</script> 