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
	width: 30% !important; 	 
	margin-bottom: 5px;
} 

.select2-drop {
	/*width: none !important;*/      
}

</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_group" name="validate_group" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_agroupid" id="hidden_agroupid" value="<?=$group->GroupID;?>" >
    <input type="hidden" value="<?=($group->GroupID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
        <label class="control-label" for="group_name">* Group Name</label>
        <div class="controls controls-row"  >
            <input type="text" id="group_name" name="group_name" class="tip span6" value="<?=htmlentities(stripslashes($group->Name));?>" maxlength="80" title="Group Name" >    
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >   
        <label class="control-label" for="group_desc">Description</label>
        <div class="controls controls-row">
            <textarea name="group_desc" id="group_desc" class="span10 tip" rows="3"  maxlength="300"  ><?=htmlentities(stripslashes($group->Description));?></textarea>
        </div>
    </div>
    <!-- End .control-group -->
     
    <div class="control-group" >   
        <label class="control-label" for="group_type"> User Type</label>
        <div class="controls controls-row ">
            <?php
			$types_arr = explode(',', $group->UserTypes);
			foreach($user_types as $row=>$user_type){
				
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="group_type[]" value="<?=$user_type->GroupID?>"  <?=(in_array($user_type->GroupID, $types_arr))?'checked="checked"':"";?> > <?=ucwords($user_type->UserTypeName)?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <input type="hidden" name="group_usertypes" id="group_usertypes" value="" style="display: block;" /> 
        </div>
    </div>
    <!-- End .control-group --> 

    <?php /*?><div class="control-group" >  
        <label class="control-label" for="group_icon">Icon</label>
        <div class="controls controls-row">  
            <select name="group_icon" id="group_icon" class="required myselect" > 
                <optgroup label="" >    
                      
                </optgroup> 
            </select>     
            <input type="hidden" name="hidden_aicon" id="hidden_aicon" value="" /> 
        </div>
    </div>
    <!-- End .control-group --><?php */?> 
     
    <div class="control-group" >  
    	<label class="control-label" for="group_currency">Currency</label>
        <div class="controls controls-row">  
            <select name="group_currency" id="group_currency" class="myselect" > 
                <optgroup label="Select Currency"> 
                    <option value="0" <?php if($group->Currency=="" || $group->Currency <= 0) echo "selected='selected'";?> >-- All --</option>
                    <?php
                    foreach($currencies as $row => $currency){ 
                        ?>
                    <option value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $group->Currency) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
                        <?php 
                        }
                    ?>
                     
                </optgroup>  
                
            </select> 
            <input type="hidden" name="hidden_acurrency" id="hidden_acurrency" value="" />
        </div>    
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >  
    	<label class="control-label" for="group_specific">Specific Users</label>
        <div class="controls controls-row">  
            <select name="group_specific[]" id="group_specific" class="span12" multiple > 
                <optgroup label="Select Currency"> 
                     
                   <?php /*?> <?php
					$specific_arr = explode(',', $group->SpecificUsers);
                    foreach($agents as $row => $agent){ 
                        ?>
                    <option value="<?=$agent->mb_no;?>" <?php if(in_array($agent->mb_no, $specific_arr)) echo "selected='selected'";?> ><?=$agent->mb_nick;?></option>	 		
                        <?php 
                        }
                    ?><?php */?>
                     
                </optgroup>  
                
            </select> 
            <input type="hidden" name="hidden_aspecific" id="hidden_aspecific" value="" />
        </div>    
    </div>
    <!-- End .control-group --> 
             
    <div class="control-group" >  
        <label class="control-label" for="group_status">* Status</label>
        <div class="controls controls-row">  
            <select name="group_status" id="group_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($group->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($group->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($group->GroupID)?"Update ":"Save new "?> group</button>
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

var manageChatGroup = function() { 
	 
	$.ajax({ 
		data: $("#validate_group").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>chat_groups/manageChatGroup", 
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
				//$('#validate_group')[0].reset();
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


function changeAgentList(url, result, default_result, container, display){ 
  
	$.ajax({ 
		data: $("#validate_group").serialize()+"&rand="+Math.random(),//+"&result="+result,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		beforeSend:function(){    
			 container.html(''); 
			 $(".select2-drop").hide();
			 $('#group_specific').select2('data', null);
		},
		success:function(newdata){         
			 var new_string = "";  
		 	//container.html('<option value="">All</option>');   
		  	if(newdata.length > 0)
			 {  
				//container.removeAttr("disabled"); 
				var x = []; 
				var all_values = ""; 
				var c_typename = ""; 
				var last_typename;
				
				$.each(newdata, function( index, value ) {  
					//selected = (default_result == value.mb_no)?'selected="selected"':'';  
					/*all_values += value.mb_no + ',';  
					if($.inArray(value.UserTypeName, x) !== -1)
					 {
						 x.push(value.UserTypeName); 
						 c_typename = value.UserTypeName;
					 }
					else
					 {
						new_string = '<option value="'+all_values+'" >All '+c_typename+'</option>';   
						container.append(new_string);
						all_values = "";
					 }*/
					last_typename = value.UserTypeName; 
					
					if(c_typename != "" && (c_typename != value.UserTypeName) )
					 {
						new_string += '</optgroup>';    
					 }
					 
					if(c_typename != value.UserTypeName)
					 { 
						new_string += '<optgroup label="'+value.UserTypeName+'" >';   
						c_typename = value.UserTypeName; 
					 }
					 
					selected = ($.inArray((value.mb_no*1), default_result) !== -1)?'selected="selected"':'';  
					new_string += '<option value="'+value.mb_no+'" '+selected+'>'+value.mb_nick+'</option>';  
					//container.append(new_string);
				});
				
				if(c_typename != "" && (c_typename != last_typename) )
				 {
					new_string += '</optgroup>';   
				 } 
				 container.append(new_string);
				 
				
			 }
		    else
			 {  
				 //container.attr('disabled', true); 
			 } 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			 
		}
			
	}); //end ajax
}

 
</script>

<script> 
$(function() {  
	 
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_group").validate({
		 submitHandler: function(form) {  
		 	manageChatGroup();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			group_name: {
				required: true, 
				minlength: 2
			}, 
			/*group_usertypes: {
				required: true 
			},*/ 
			group_status: {
				required: true 
			} 
		},
		messages: { 
		   group_name: {
				required: "Please enter group name" 
			},   
		   /*group_usertypes: {
				required: "Select user types" 
			},*/ 
		   group_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	
	$("#group_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#group_status").trigger("change");
	
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_group .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
	/*$("input:checkbox[name='group_type[]']").click(function(){ 
		 selected_values = $("input[name='group_type[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#group_usertypes").val(selected_values);  
	}); */
	selected_values = $("input[name='group_type[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("#group_usertypes").val(selected_values);  
	 
	  
	$("input:checkbox[name='group_type[]']").click(function(){  
		 var def_specific = [<?=$group->SpecificUsers?>];
		 selected_values = $("input[name='group_type[]']:checked").map(function() {return this.value;}).get().join(',');  
	     
		 /*$(".select2-drop").hide();
		 $('#group_specific').html('');
		 $('#group_specific').select2('data', null);
			  
		 changeAgentList("<?=base_url()?>chat_groups/getAgentList", selected_values, def_specific, $("#group_specific"));   */
	}); 
	
	$("#group_currency").change(function(){      
		$("#hidden_acurrency").val($(this).find(":selected").text()); 
		 var def_specific = [<?=$group->SpecificUsers?>];
		 selected_values = $("input[name='group_type[]']:checked").map(function() {return this.value;}).get().join(',');  
	     
		 $(".select2-drop").hide();
		 $('#group_specific').html('');
		 $('#group_specific').select2('data', null);
			  
		 changeAgentList("<?=base_url()?>chat_groups/getAgentList", selected_values, def_specific, $("#group_specific"));
		 
	}); 
	$("#group_currency").trigger("change");
	
	  
		
	$('select').select2({placeholder: "Select"});
	 
	
}); 
 

</script> 