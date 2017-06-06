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
<form id="validate_source" name="validate_source" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_asourceid" id="hidden_asourceid" value="<?=$source->SourceID;?>" >
    <input type="hidden" value="<?=($source->SourceID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
        <label class="control-label" for="source_name">* Source Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="source_name" name="source_name" class="tip span12" value="<?=htmlentities(stripslashes($source->Source));?>" maxlength="80" title="Source name" >    
        </div>
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" > 
        <label class="control-label" for="source_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="source_desc" name="source_desc" class="span12 tip" rows="4" maxlength="200" title="Source description" ><?=$source->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group --> 
     
    <div class="control-group" >  
        <label class="control-label" for="source_status">* Status</label>
        <div class="controls controls-row">  
            <select name="source_status" id="source_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($source->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($source->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($source->SourceID)?"Update ":"Save new "?> source</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageActivitySource = function() { 
	 
	$.ajax({ 
		data: $("#validate_source").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>activity_source/manageActivitySource", 
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
				//$('#validate_source')[0].reset();
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
	 
	$("#validate_source").validate({
		 submitHandler: function(form) { 
		 	manageActivitySource();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			source_name: {
				required: true, 
				minlength: 2
			},  
			source_status: {
				required: true 
			} 
		},
		messages: { 
		   source_name: {
				required: "Please enter source name" 
			},    
		   source_status: {
				required: "Select status" 
			}
		}
	}); 
	    
	
	$("#source_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#source_status").trigger("change");
	 
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_source .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 