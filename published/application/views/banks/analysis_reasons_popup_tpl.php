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

.url_list {
	max-width: 48%;
	min-width: 48%;	
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;	
}

.radio-inline {
	margin: 3px 0 0;
    padding: 0 0 0 20px;	
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_reason" name="validate_reason" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_areasonid" id="hidden_areasonid" value="<?=$reason->ReasonID;?>" >
    <input type="hidden" value="<?=($reason->ReasonID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
      
    <div class="control-group" >   
        <label class="control-label" for="reason_name">* Reason</label>
        <div class="controls controls-row"  >
            <input type="text" id="reason_name" name="reason_name" class="tip span8" value="<?=htmlentities(stripslashes($reason->ReasonName));?>" maxlength="80" title="Reason" > 
        </div>
    </div>
    <!-- End .control-group --> 
   
    <div class="control-group" > 
        <label class="control-label" for="reason_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="reason_desc" name="reason_desc" class="span12" rows="2" maxlength="200" ><?=$reason->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >  
    	<div class="span6" >
            <label class="control-label" for="reason_category">* Category</label>
            <div class="controls controls-row" >  
                <select name="reason_category" id="reason_category" class="required myselect"  >
                    <optgroup label="" >    
                        <option value="" <?php if($reason->CategoryID=='') echo "selected='selected'";?> ></option>
                        <?php   
                        foreach($categories as $row=>$category) {
                        ?>
                        <option value="<?=$category->CategoryID?>" <?php if($reason->CategoryID==$category->CategoryID) echo "selected='selected'";?> ><?=ucwords($category->CategoryName)?></option>
                        <?php	
                        }//end foreach
                        ?> 
                    </optgroup>  
                </select> 
                <input type="hidden" name="hidden_acategory" id="hidden_acategory" value="" />  
            </div>   
        </div>
        
        <div class="span6" >
        	<label class="control-label" for="reason_type">* Method Type</label>
            <div class="controls controls-row">  
                <select name="reason_type" id="reason_type" class="required myselect" > 
                    <optgroup label="" >    
                         <option value="" <?php if($reason->Status=='') echo "selected='selected'";?> ></option>
                        <?php
                        foreach($types as $row => $type){  
                         
                        ?>
                        <option  value="<?=$type[Value];?>" <?php if($reason->Type==$type[Value]) echo "selected='selected'";?> ><?=ucwords($type[Label]);?></option>
                        <?php
                        }
                        ?>
                    </optgroup> 
                </select>     
                <input type="hidden" name="hidden_atype" id="hidden_atype" value="" /> 
            </div>  
        </div>
        
    </div>
    <!-- End .control-group -->
    
     
    <div class="control-group" >   
       <?php /*?> <label class="control-label" for="reason_method">* Method</label>
        <div class="controls controls-row">  
            <select name="reason_method" id="reason_method" class="required select2 span12" disabled="disabled"> 
                <optgroup label="" >    
                    <option value="" ></option> 
                </optgroup> 
            </select>  
            <input type="hidden" name="hidden_amethod" id="hidden_amethod" value="" />
        </div>	<?php */?> 
        
        
         <div class="control-group" >   
             <label class="control-label" for="reason_method">* Method List</label>
             <div class="controls controls-row" id="ChecklistDetails"  >
             	 <label class="info-label" for="reason_method" >- - - - Select method type  - - - - </label> 
             </div> 
             <div class="controls controls-row" id="ChecklistDetails"  >
             	<input type="hidden" name="hidden_amethodlist" id="hidden_amethodlist" value="<?=$reason->Methods?>" /> 
             </div> 
             
        </div>
        <!-- End .control-group --> 
          
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <label class="control-label" for="reason_isspecify">* Is Specify?</label>
        <div class="controls controls-row" >  
            <label class="radio-inline blue" >
                <input type="radio" name="reason_isspecify" value="1"  <?=($reason->IsSpecify=='1')?'checked="checked"':"";?> > Yes
            </label> 
            
            <label class="radio-inline blue">
                <input type="radio" name="reason_isspecify" value="0" <?=($reason->IsSpecify==0 || $reason->IsSpecify=='')?'checked="checked"':"";?> > No
            </label>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >  
        <label class="control-label" for="reason_status">* Status</label>
        <div class="controls controls-row">  
            <select name="reason_status" id="reason_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($reason->Status=='') echo "selected='selected'";?> ></option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($reason->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($reason->CategoryID)?"Update ":"Save new "?> reason</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script > 
var default_methods = [<?=trim($reason->Methods)?>];  
 
var selected_values = "";  

var getMethodList = function() {  
	$.ajax({ 
		data: $("#validate_reason").serialize(), 
		type:"POST",  
		url: "<?=base_url()?>banks/getActivityMethodsList", 
		dataType: "JSON",   
		cache: false,
		beforeSend:function(){       
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
		},
		success:function(newdata){     
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled"); 
			var new_string = "";
			if(newdata.success > 0)
			 {  
			 	if(newdata.checklist.length > 1) new_string += "<label class=\"radio-inline group-label url_list\" ><input type=\"checkbox\" name=\"checkbox_all\" value=\"1\" > &nbsp; <span class=\"tip\" title=\"Select all\" ><b>All</b></span></label>";
				
				$.each(newdata.checklist, function( index, value ) {   
					//container.append(new_string); 
					var is_check = ($.inArray(parseInt(value.CategoryID), default_methods) !== -1)?"checked='checked' ":"";    
					
					if(value.CategoryID)new_string += "<label class=\"radio-inline group-label url_list\" ><input type=\"checkbox\" name=\"check_item[]\" value=\""+value.CategoryID+"\""+is_check+"\ > &nbsp; <span class=\"tip\" title=\""+value.Name+"\">"+value.Name+"</span></label>";
				});
				
				$("#ChecklistDetails").html(new_string);  
				
				$("input:checkbox[name=checkbox_all]").removeAttr("checked"); 
				$.uniform.update("input:checkbox[name=checkbox_all]");
			
				$("input:checkbox[name='check_item[]']").click(function(){  
					  selected_values = $("input[name='check_item[]']:checked").map(function() {return this.value;}).get().join(',');    
					  if(!$(this).is(':checked')) 
					  {  
					  	
						 $("input:checkbox[name=checkbox_all]").removeAttr("checked");  
						 $.uniform.update("input:checkbox[name=checkbox_all]");	 
					  }
					  
					  $("div.alert").remove();
					  $("#hidden_amethodlist").val(selected_values);
					  
				 });  
				 
				 $("input:checkbox[name='checkbox_all']").click(function () {   
					 
					if ($("input:checkbox[name='checkbox_all']").is(':checked')) {
						$("input:checkbox[name='check_item[]']").prop("checked", true); 
					} else {
						$("input:checkbox[name='check_item[]']").prop("checked", false); 
					}
					//selected_values = $('input[name="\'check_item[]\'"]:checked').map(function() {return this.value;}).get().join(',');  
					selected_values = $("input[name='check_item[]']:checked").map(function() {return this.value;}).get().join(','); 
					 
					$.uniform.update("input[type=checkbox]"); 
					$("div.alert").remove(); 
					
					$("#hidden_amethodlist").val(selected_values);
						
				});
				 
				 $('input[type=checkbox]').uniform(); 
				 $(".tip").tooltip ({placement: 'top'}); 
				
			 } 
			else
			 {
				createMessageMini($(".form-widget-content"), newdata.message, "error");  
			 } 
			  
		}
		 
	}); //end ajax
	
	//e.preventDefault();
	  
} 

var manageReason = function() { 
	 
	$.ajax({ 
		data: $("#validate_reason").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>analysis_reasons/manageReason", 
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
	/*$('#reason_currency').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=reason_idreceived]"); 
	 
	$("#validate_reason").validate({
		 submitHandler: function(form) { 
		 	manageReason();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			reason_name: {
				required: true, 
				minlength: 2
			}, 
		   reason_category: {
				required: true 
			},
		   reason_type: {
				required: true 
			} ,
		   hidden_amethodlist: {
				required: true	
			},
		   reason_status: {
				required: true 
			} 
		},
		messages: {
		   reason_name: {
				required: "Please provide reason" 
		    },
		   reason_category: {
				required: "Please provide category" 
			},
		   reason_type: {
				required: "Please provide method type" 
			},
		   hidden_amethodlist: {
				required: "Pease select method" 
			} ,
		   reason_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	$("#reason_category").change(function(){  
		$("#hidden_acategory").val($(this).find(":selected").text());
	});
	$("#reason_category").trigger("change");
		  
	$("#reason_status").change(function(){   
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#reason_status").trigger("change");
	
	$("#reason_method").change(function(){   
		$("#hidden_amethod").val($(this).find(":selected").text());
	});
	$("#reason_method").trigger("change");
	
	$("#reason_type").change(function(){   
		$("#hidden_atype").val($(this).find(":selected").text());
		//changeActivityMethods("<?=base_url()?>banks/getActivityMethods", $(this).val(), "<?=$reason->Method;?>", $("#reason_method")); 
		getMethodList(); 
	}); 
	$("#reason_type").trigger("change");
	 
	
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