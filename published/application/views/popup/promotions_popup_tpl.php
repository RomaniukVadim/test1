<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet"/>  
<script src="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.js"></script>

<script src="<?=base_url();?>media/js/plugins/forms/ajax-upload/ajaxupload.3.5.js"></script>

<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}
</style>

<div class="row-fluid">
                        	 
<!-- form -->
<form id="validate" name="validate" class="form-horizontal" onsubmit="return false; " autocomplete="off" style="margin: 0px; padding: 0px; ">  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update_activity":"add_actvitity";?>" name="hidden_action" id="hidden_action" > 
     
    <div class="control-group" >  
    	<div class="span4" > 
            <label class="control-label" for="act_currency">* Currency</label>
            <div class="controls controls-row">  
                <select name="act_currency" id="act_currency" class="required  select2 span12" > 
                    <optgroup label="Select Currency"> 
                        <option value="" <?php if($activity->Currency=="") echo "selected='selected'";?> ></option>
                        <?php
						foreach($currencies as $row => $currency){ 
                            ?>
                        <option  value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $activity->Currency) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
                            <?php 
                            }
						?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_acurrency" id="hidden_acurrency" value="" />
            </div>
        </div> 
        
        <div class="span4" >
            <label class="control-label" for="act_username">* Username</label>
            <div class="controls controls-row"  >
                <input type="text" id="act_username" name="act_username" class="required span12 tip" value="<?=htmlentities(stripslashes($activity->Username));?>" maxlength="100" title="Enter username" > 
            </div>
        </div> 
        
        <div class="span4" >
            <label class="control-label" for="act_esupportid">E-Support Ticket ID</label>
            <div class="controls controls-row"  >
                <input type="text" id="act_esupportid" name="act_esupportid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->ESupportID));?>" maxlength="30" title="Enter E-Support Ticket ID" > 
            </div>
        </div> 
        
    </div>
    <!-- End .control-group --> 
 
     
    <div class="control-group" > 
        <label class="control-label" for="btn_addbanner">&nbsp;</label>
        <div class="controls controls-row">  
            <button id="BtnUpload" class="btn btn_addbanner " type="button">
                <i class="icon16 i-attachment"></i>
                Attach File
            </button>
            <span id="status"> </span>
        </div>
    </div>
    <!-- End .control-group -->       
    
    
    <div class="form-actions"> 
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" >Save changes</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button>
        <button type="button" class="btn" id="BtnBackList" style="float: right; " >Back to list</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageBankActivity = function() { 
	 
	$.ajax({
		data: $('#validate').serialize()+"&rand="+Math.random(), 
		type:"POST",  
		url: "<?=base_url();?>banks/manageActivity", 
		dataType: "JSON",
		beforeSend:function(){       
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
		},
		success:function(msg){     
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled");
			
			if(msg.success > 0)
			 {  
			 	createMessage(msg.message, "success"); 
				if($("#hidden_action").val()=="add_overpay")
				 {
					 $('#validate')[0].reset();
				 	 clearSelectbox($("div.controls"));  
					 $("ul.select2-choices li.select2-search-choice").remove(); 
				 }
			 } 
			else
			 {
				createMessage(msg.message, "error"); 
			 }
			 
		}
		
	}); //end ajax
} 

 
</script>

<script> 
var formdata; //for upload 
var to_upload = 0; 
var upload_error = 0; 
var selected_lang = ""; 
$(function() { 
 
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]");
	 
	/*$("#overpay_aperson_options").change(function(){  
		//$("#overpay_hiddenaperson").val($(this).find(":selected").text()); 
		var values = $.map($(this).find(":selected"), function(option) {
					   return option.text; 
					});
		var values_id = $.map($(this).find(":selected"), function(option) { 
					   return option.value; 
					});   		 
		$("#overpay_aperson").val(values_id)
		$("#overpay_hiddenaperson").val(values);  
	});
	$("#overpay_aperson_options").trigger("change"); */
	 
	 
	 
	 
	$("#validate").validate({
		 submitHandler: function(form) { 
		 	manageBankActivity();//check duplicate username
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: {
			act_username: {
				required: true 
			}, 
			act_currency: {
				required: true 
			},
			act_source: {
				required: true 
			},     
			act_methodtype: {
				required: true 
			},
			act_method: {
				required: true  
			},
			act_amount: {
				required: true, 
				number: true
			},  
			act_status: {
				required: true 
			},
			act_remarks: {
				required: true, 
				minlength: 10
			} 
		},
		messages: {
			act_username: {
				required: "Provide username" 
			},
			act_currency: {
				required: "Provide currency" 
			},
			act_source: {
				required: "Select source" 
			},
			act_methodtype: {
				required: "Select method type" 
			},
			act_method: {
				required: "Select method" 
			},
			act_amount: {
				required: "Please provide amount", 
				number: "Please enter a valid amount. Ex. 100.50" 
			}, 
			act_status: {
				required: "Select status" 
			}, 
			act_remarks: {
				required: "Please provide remarks" 
			}  
			
		}
	}); 
	
	$('#datepicker1').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii"
		pickTime: false
	}); 
	 
	
	//checking language
	$("input:checkbox[name=lang_option]").click(function(){
		 selected_lang = $('input[name="lang_option"]:checked').map(function() {return this.value;}).get().join(',');
		 $("#overpay_language").val(selected_lang); 
	  });  
	
	
	//------------- Form validation -------------//
	$('select').select2({placeholder: "Select"});
	/*$('#overpay_status').select2({placeholder: "Select"});
	$('#overpay_warninglevel').select2({placeholder: "Select"});
	$('#overpay_currency').select2({placeholder: "Select"});
	$('#overpay_aperson_options').select2({placeholder: "Select"});
	$('#overpay_aprocess').select2({placeholder: "Select"});*/
	
	$("#act_methodtype").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		changeActivityMethods("<?=base_url()?>banks/getActivityMethods", $(this).val(), "<?=$activity->CategoryID;?>", $("#act_method"));
	}); 
	$("#act_methodtype").trigger("change");
	
	$("#act_currency").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		changeDepositMethods("<?=base_url()?>banks/getDepositMethods", $(this).val(), "<?=$activity->DepositMethodID;?>", $("#act_depmethod"));
	}); 
	$("#act_currency").trigger("change");  
	
	$("#act_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#act_status").trigger("change");
	
	$("#act_source").change(function(){ 
		$("#hidden_asource").val($(this).find(":selected").text());
	});
	$("#act_source").trigger("change");
	
}); 
 

</script> 