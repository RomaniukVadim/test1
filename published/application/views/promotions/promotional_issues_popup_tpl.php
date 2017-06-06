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
<form id="validate_issue" name="validate_issue" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_aissueid" id="hidden_aissueid" value="<?=$issue->IssueID;?>" >
    <input type="hidden" value="<?=($issue->IssueID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
      
    <div class="control-group" >   
        <label class="control-label" for="issue_name">* Category Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="issue_name" name="issue_name" class="tip span12" value="<?=htmlentities(stripslashes($issue->Name));?>" maxlength="80" title="Issue Name" >    
        </div>
    </div>
    <!-- End .control-group --> 
     
    <div class="control-group" > 
        <label class="control-label" for="issue_description">Description</label>
        <div class="controls controls-row" >  
            <textarea id="issue_description" name="issue_description" class="span12 tip" rows="4" maxlength="200" title="Issue Description" ><?=$issue->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >  
        <label class="control-label" for="issue_status">* Status</label>
        <div class="controls controls-row">  
            <select name="issue_status" id="issue_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($issue->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($issue->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($issue->IssueID)?"Update ":"Save new "?> issue</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var managePromotionCategory = function() { 
	 
	$.ajax({ 
		data: $("#validate_issue").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>promotional_issues/managePromotionIssue", 
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
				//$('#validate_issue')[0].reset();
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
	 
	$("#validate_issue").validate({
		 submitHandler: function(form) { 
		 	managePromotionCategory();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			issue_name: {
				required: true, 
				minlength: 2
			}, 
			issue_status: {
				required: true 
			} 
		},
		messages: {
			issue_status: {
				required: "Select status" 
			},  
			issue_name: {
				required: "Please type name" 
			}  
			  
			
		}
	}); 
	   
	
	$("#issue_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#issue_status").trigger("change");
	
	//clicking checkbox viewers
	$("input:checkbox[name=issue_viewer]").click(function(){  
		var viewers = $('input[name="issue_viewer"]:checked').map(function() {return this.value;}).get().join(',');  
		$("#issue_viewers").val(viewers); 
	});
	$('input[type=checkbox]').uniform();
	  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_issue .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 