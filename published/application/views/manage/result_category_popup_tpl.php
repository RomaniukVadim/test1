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
<form id="validate_result" name="validate_result" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_acategoryid" id="hidden_acategoryid" value="<?=$category->CategoryID;?>" >
    <input type="hidden" value="<?=($category->CategoryID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
        <label class="control-label" for="category_name">* Category Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="category_name" name="category_name" class="tip span12" value="<?=htmlentities(stripslashes($category->Name));?>" maxlength="80" title="Category Name" >    
        </div>
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" > 
        <label class="control-label" for="category_description">Description</label>
        <div class="controls controls-row" >  
            <textarea id="category_description" name="category_description" class="span12 tip" rows="3" maxlength="200" title="Category Description" ><?=$category->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >    
        <label class="control-label" for="category_result">* Result</label>
        <div class="controls controls-row"> 
            <select name="category_result" id="category_result" class="select2 myselect call-fieldimportant"  > 
                <option value="" >- Select Result -</option> 
                <?php 
				$cat_result = ($category->Result)?$category->Result:$this->common->ids[reached_result]; 
                foreach($results as $row=>$result) {
                ?> 
                <option value="<?=$result->result_id;?>" <?php if($cat_result==$result->result_id) echo "selected='selected'";?>  ><?=ucwords($result->result_name)?></option>
                <?php	
                }//end foreach
                ?> 
            </select>  
            <input type="hidden" name="hidden_aresult" id="hidden_aresult"  value="<?=$category->ResultName?>" /> 
        </div>   
    </div>
    <!-- End .control-group --> 
     
    <div class="control-group" >  
        <label class="control-label" for="category_status">* Status</label>
        <div class="controls controls-row">  
            <select name="category_status" id="category_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($result->result_status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($result->result_status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($result->result_id)?"Update ":"Save new "?> result</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageResultCategory = function() { 
	 
	$.ajax({ 
		data: $("#validate_result").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>result_categories/manageResultCategory", 
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
				//$('#validate_result')[0].reset();
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
	 
	$("#validate_result").validate({
		 submitHandler: function(form) { 
		 	manageResultCategory();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			category_name: {
				required: true, 
				minlength: 2
			}, 
			category_result: {
				required: true 
			}, 
			category_status: {
				required: true 
			} 
		},
		messages: { 
		   category_name: {
				required: "Please enter category name" 
			},   
		   category_result: {
				required: "Select result" 
			}, 
		   category_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	
	$("#category_result").change(function(){ 
		$("#hidden_aresult").val($(this).find(":selected").text());
	});
	$("#category_result").trigger("change");
	
	$("#result_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#result_status").trigger("change");
	
	//clicking checkbox viewers
	$("input:checkbox[name=result_viewer]").click(function(){  
		var viewers = $('input[name="result_viewer"]:checked').map(function() {return this.value;}).get().join(',');  
		$("#result_viewers").val(viewers); 
	});
	$('input[type=checkbox]').uniform();
	  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_result .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 