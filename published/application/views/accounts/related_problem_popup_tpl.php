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
<form id="validate_category" name="validate_category" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_aproblemid" id="hidden_aproblemid" value="<?=$problem->ProblemID;?>" >
    <input type="hidden" value="<?=($problem->ProblemID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
      
    <div class="control-group" >   
        <label class="control-label" for="problem_name">* Problem Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="problem_name" name="problem_name" class="tip span12" value="<?=htmlentities(stripslashes($problem->ProblemName));?>" maxlength="80" title="Problem Name" > 
        </div>
    </div>
    <!-- End .control-group --> 
   
    <div class="control-group" > 
        <label class="control-label" for="problem_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="problem_desc" name="problem_desc" class="span12" rows="4" maxlength="200" ><?=$problem->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >  
        <label class="control-label" for="problem_status">* Status</label>
        <div class="controls controls-row">  
            <select name="problem_status" id="problem_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($problem->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($problem->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($problem->ProblemID)?"Update ":"Save new "?> problem</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageRelatedProblem = function() { 
	 
	$.ajax({ 
		data: $("#validate_category").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>related_problems/manageRelatedProblem", 
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
		 	manageRelatedProblem();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			problem_name: {
				required: true, 
				minlength: 2
			}, 
			problem_status: {
				required: true 
			} 
		},
		messages: {
			problem_status: {
				required: "Select status" 
			},  
			problem_name: {
				required: "Please provide category name" 
			}  
			  
			
		}
	}); 
	   
	
	$("#problem_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#problem_status").trigger("change");
	
	
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