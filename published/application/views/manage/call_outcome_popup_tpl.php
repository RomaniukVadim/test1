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
	width: 45% !important; 	 
	margin-bottom: 5px;
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_outcome" name="validate_outcome" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_aoutcomeid" id="hidden_aoutcomeid" value="<?=$outcome->outcome_id;?>" >
    <input type="hidden" value="<?=($outcome->outcome_id)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
        <label class="control-label" for="outcome_name">* Outcome Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="outcome_name" name="outcome_name" class="tip span12" value="<?=htmlentities(stripslashes($outcome->outcome_name));?>" maxlength="80" title="Outcome Name" >    
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >  
        <label class="control-label" for="outcome_result">* Result</label>
        <div class="controls controls-row">  
            <select name="outcome_result" id="outcome_result" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($outcome->result_id=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($results as $row => $result){  
					 
                    ?>
                    <option  value="<?=$result->result_id;?>" <?php if($outcome->result_id==$result->result_id) echo "selected='selected'";?> ><?=ucwords($result->result_name);?></option>
					<?php
                    }
                    ?>
                </optgroup> 
            </select>     
            <input type="hidden" name="hidden_aresult" id="hidden_aresult" value="" /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <?php /*?><div class="control-group" > 
        <label class="control-label" for="outcome_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="outcome_description" name="outcome_description" class="span12 tip" rows="4" maxlength="200" title="Category Description" ><?=$outcome->Description?></textarea>
        </div>
    </div><?php */?>
    <!-- End .control-group -->
    
    <div class="control-group" >  
        <label class="control-label" for="outcome_status">* Status</label>
        <div class="controls controls-row">  
            <select name="outcome_status" id="outcome_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($outcome->outcome_status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($outcome->outcome_status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($outcome->outcome_id)?"Update ":"Save new "?> outcome</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageCallOutcome = function() { 
	 
	$.ajax({ 
		data: $("#validate_outcome").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>call_outcomes/manageCallOutcome", 
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
				//$('#validate_outcome')[0].reset();
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
	 
	$("#validate_outcome").validate({
		 submitHandler: function(form) { 
		 	manageCallOutcome();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			outcome_name: {
				required: true, 
				minlength: 2
			}, 
			outcome_result: {
				required: true 
			}, 
			outcome_status: {
				required: true 
			} 
		},
		messages: { 
		   outcome_name: {
				required: "Please enter outcome name" 
			},   
		   outcome_result: {
				required: "Select result" 
			}, 
		   outcome_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	
	$("#outcome_result").change(function(){ 
		$("#hidden_aresult").val($(this).find(":selected").text());
	});
	$("#outcome_result").trigger("change");
	
	$("#outcome_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#outcome_status").trigger("change");
	
	//clicking checkbox viewers
	$("input:checkbox[name=outcome_viewer]").click(function(){  
		var viewers = $('input[name="outcome_viewer"]:checked').map(function() {return this.value;}).get().join(',');  
		$("#outcome_viewers").val(viewers); 
	});
	$('input[type=checkbox]').uniform();
	  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_outcome .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 