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
<form id="validate_reason" name="validate_reason" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
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
        <label class="control-label" for="category_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="category_desc" name="category_desc" class="span12" rows="4" maxlength="200" ><?=$category->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group -->
         
    <div class="control-group" >  
        <label class="control-label" for="category_status">* Status</label>
        <div class="controls controls-row">  
            <select name="category_status" id="category_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($category->Status=='') echo "selected='selected'";?> ></option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($category->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($category->CategoryID)?"Update ":"Save new "?> category</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageCategory = function() { 
	 
	$.ajax({ 
		data: $("#validate_reason").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>analysis_categories/manageCategory", 
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
				//$('#validate_reason')[0].reset();
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
	/*$('#category_currency').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=category_idreceived]"); 
	 
	$("#validate_reason").validate({
		 submitHandler: function(form) { 
		 	manageCategory();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			category_name: {
				required: true, 
				minlength: 2
			}, 
		   category_category: {
				required: true 
			},
		   category_type: {
				required: true 
			} ,
		   category_status: {
				required: true 
			} 
		},
		messages: {
		   category_name: {
				required: "Please provide reason" 
		    },
		   category_category: {
				required: "Please provide category" 
			},
		   category_type: {
				required: "Please provide type" 
			},
		   category_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	$("#category_category").change(function(){  
		$("#hidden_acategory").val($(this).find(":selected").text());
	});
	$("#category_category").trigger("change");
		 
	$("#category_type").change(function(){  
		$("#hidden_atype").val($(this).find(":selected").text());
	});
	$("#category_type").trigger("change");
	 
	$("#category_status").change(function(){   
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#category_status").trigger("change");
	
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_reason .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 