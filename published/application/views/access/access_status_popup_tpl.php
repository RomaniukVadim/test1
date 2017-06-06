<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
 
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 100px !important; 
}

.form-horizontal .controls {
    margin-left: 120px;
}

</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate_status_popup" name="validate_status_popup" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
         
    <div class="control-group" >  
        <div class="span12" > 
            <label class="control-label" for="act_status">* Status</label>
            <div class="controls controls-row">  
                <select name="act_status" id="act_status" class="required myselect" > 
                    <optgroup label="" >    
                         <option value="" <?php if($activity->Status=="") echo "selected='selected'";?> ></option>
                        <?php
						foreach($status_list as $row => $status){ 
                        	$users_list = explode(",", $status->Users);
							  if(in_array($this->session->userdata("mb_usertype"),$users_list))
							   {
						?>
                        <option  value="<?=$status->StatusID;?>" <?php if($activity->Status==$status->StatusID) echo "selected='selected'";?> ><?=ucwords($status->Name);?></option>
                            <?php 
							   }
                            }
						?>
                    </optgroup> 
                </select>   
                <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" />
            </div>
        </div> 
         
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" > 
        <label class="control-label" for="act_remarks">* Remarks</label>
        <div class="controls controls-row" >  
            <textarea id="act_remarks" name="act_remarks" class="required span12" rows="4" maxlength="500" placeholder="<?=$activity->Remarks?>" ></textarea>
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
var manageActivityStatus = function() { 
	 
	$.ajax({ 
		data: new FormData($("#validate_status_popup")[0]), 
		type:"POST",  
		url: "<?=base_url();?>access/manageActivityStatus", 
		dataType: "JSON", 
		processData: false,
		contentType: false,  
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
				//$('#validate_status_popup')[0].reset();
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
	 
	$("#validate_status_popup").validate({
		 submitHandler: function(form) { 
		 	manageActivityStatus();//check duplicate username 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			act_status: {
				required: true 
			},
			act_remarks: {
				required: true, 
				minlength: 10
			} 
		},
		messages: {
			act_status: {
				required: "Select status" 
			}, 
			act_remarks: {
				required: "Please provide remarks" 
			}  
			
		}
	}); 
	 
	
	$("#act_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#act_status").trigger("change");
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_status_popup .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 