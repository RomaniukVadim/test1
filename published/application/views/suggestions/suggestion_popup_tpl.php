<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet"/>  
<script src="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.js"></script> 

<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.bootstrap-datetimepicker-widget {
	z-index: 999999; 	
} 

.form-horizontal .control-label { 
    width: 160px;
}

.form-horizontal .right-panel .control-label { 
    width: 190px;
}

.form-horizontal .right-panel .controls {
    margin-left: 200px;
}

</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate" name="validate" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post" enctype="multipart/form-data" onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
    
    <div class="control-group" >    
    
    	<div class="span4" >
        	<label class="control-label" for="act_assignee">* Assignee</label>
            <div class="controls controls-row">  
                <select name="act_assignee" id="act_assignee" class="required select2 span12" > 
                    <optgroup label="Select Assignee"> 
                        <?php if($activity->ActivityID == ""){ ?><option value="" <?php if($activity->Assignee=="") echo "selected='selected'";?> ></option> <?php } ?>
                        <?php
						foreach($assignees as $row => $assignee){ 
								$stat_class= ($assignee->Status != '1')?"act-danger":"";
                            ?>
                        <option value="<?=$assignee->GroupID;?>" <?php if($assignee->GroupID == $activity->GroupAssignee) echo "selected='selected'";?> class="<?=$stat_class?>" ><?=$assignee->UserTypeName;?></option>	 		
                            <?php 
                            }
						?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_aassignee" id="hidden_aassignee" value="" />
                <input type="hidden" name="hidden_defassignee" id="hidden_defassignee" value="<?=$activity->GroupAssignee?>" />
            </div> 
        </div>
        
        <?php
		$def_currency = ($activity->Currency)?$activity->Currency:$default_user->Currency; 
		?>
        <div class="span4" > 
            <label class="control-label" for="act_currency">* Currency</label>
            <div class="controls controls-row">  
                <select name="act_currency" id="act_currency" class="required  select2 span12" > 
                    <optgroup label="Select Currency"> 
                        <option value="" <?php if($activity->Currency=="") echo "selected='selected'";?> ></option>
                        <?php
                        foreach($currencies as $row => $currency){ 
                            ?>
                        <option value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $def_currency) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
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
                <input type="text" id="act_username" name="act_username" class="required span12 tip" value="<?=($activity->Username)?htmlentities(stripslashes($activity->Username)):$default_user->Username;?>" maxlength="100" title="Enter username" <?php if(count($acvitiy)<=0 && $default_user->UserID){?>readonly="readonly" <?php }?> > 
            </div>
        </div>  
        
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >  
    	
        <div class="span4" >
            <label class="control-label" for="act_esupportid">E-Support Ticket ID</label>
            <div class="controls controls-row"  >
                <input type="text" id="act_esupportid" name="act_esupportid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->ESupportID));?>" maxlength="30" title="Enter E-Support Ticket ID" > 
            </div>
        </div> 
          
        <div class="span4" >
            <label class="control-label" for="act_source">* Source</label>
            <div class="controls controls-row">  
                <select name="act_source" id="act_source" class="required select2 span12" > 
                    <optgroup label="Select Source"> 
                        <option value="" <?php if($activity->Source=="") echo "selected='selected'";?> ></option>
                        <?php
                        foreach($sources as $row => $source){ 
                            ?>
                        <option  value="<?=$source->SourceID;?>" <?php if($source->SourceID == $activity->Source) echo "selected='selected'";?> ><?=$source->Source;?></option>	 		
                            <?php 
                            }
                        ?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_asource" id="hidden_asource" value="" />
            </div>
        </div>  
        
        <div class="span4" >
            <label class="control-label" for="act_product">* Product</label>
            <div class="controls controls-row">  
                <select name="act_product" id="act_product" class="required select2 span12" > 
                    <optgroup label="Select Product"> 
                        <option value="" <?php if($activity->Product=="") echo "selected='selected'";?> ></option>
                        <?php
                        foreach($products as $row => $product){ 
                            ?>
                        <option  value="<?=$product->ProductID;?>" <?php if($product->ProductID == $activity->Product) echo "selected='selected'";?> ><?=$product->Name;?></option>	 		
                            <?php 
                            }
                        ?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_aproduct" id="hidden_aproduct" value="" />
            </div>
        </div> 
         
    </div>
    <!-- End .control-group -->
     
    <div class="control-group" >  
        
        <div class="span4" >
            <label class="control-label" for="act_complainttype">* Method</label>
            <div class="controls controls-row">  
                <select name="act_complainttype" id="act_complainttype" class="required select2 span12" > 
                    <optgroup label="Select Type"> 
                        <option value="" <?php if($activity->ComplaintID=="") echo "selected='selected'";?> ></option>
                        <?php
                        foreach($types as $row => $type){ 
                            ?>
                        <option  value="<?=$type->ComplaintID;?>" <?php if($type->ComplaintID == $activity->ComplaintType) echo "selected='selected'";?> ><?=$type->Name;?></option>	 		
                            <?php 
                            }
                        ?>
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_acomplainttype" id="hidden_acomplainttype" value="" />
            </div>
        </div>  
        
        <div class="span4" >
            <label class="control-label" for="act_blocked">* Account Blocked</label>
            <div class="controls controls-row">  
                <label class="radio-inline" >
                    <input type="radio" name="act_blocked" value="1" <?=($activity->AccountBlocked==1)?'checked="checked"':"";?> > Yes
                </label> 
                
                <label class="radio-inline">
                    <input type="radio" name="act_blocked" value="0" <?=($activity->AccountBlocked==0 || $activity->AccountBlocked=='')?'checked="checked"':"";?> > No
                </label>
            </div> 
        </div>  
        
        <div class="span4" >
            <label class="control-label" for="act_locked">* Account Locked</label>
            <div class="controls controls-row">  
                <label class="radio-inline" >
                    <input type="radio" name="act_locked" value="1" <?=($activity->AccountLocked==1)?'checked="checked"':"";?> > Yes
                </label> 
                
                <label class="radio-inline">
                    <input type="radio" name="act_locked" value="0" <?=($activity->AccountLocked==0 || $activity->AccountLocked=='')?'checked="checked"':"";?> > No
                </label>
            </div> 
        </div>  
         
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group hide" id="IfSelfExclusion" >    
        	  
         <div class="span4" >
            <label class="control-label" for="act_effectivedate">* Effective Date</label>
            <div class="controls controls-row">   
                <div id="datepicker1" class="input-append datepicker" > 
                     <span class="add-on">
                        <i class="icon16"></i>
                    </span>
                    <input type="text" value="<?=($activity->EffectiveDate != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->EffectiveDate)):"";?>"  name="act_effectivedate" id="act_effectivedate" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip exclusion_fields" title="Select effective date"  >
                </div>   
            </div>
        </div> 
        
        <div class="span4" > 
            <label class="control-label" for="act_enddate">* End Date</label>
            <div class="controls controls-row">   
                <div id="datepicker2" class="input-append datepicker" > 
                     <span class="add-on">
                        <i class="icon16"></i>
                    </span>
                    <input type="text" value="<?=($activity->EndDate != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->EndDate)):"";?>"  name="act_enddate" id="act_enddate" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip exclusion_fields" title="Select end date"  >
                </div>   
            </div>
        </div> 
        
        <div class="span4" > 
        	<label class="control-label" for="act_exclusionperiod">* Self Exclusion Period </label>
            <div class="controls controls-row">  
                <select name="act_exclusionperiod" id="act_exclusionperiod" class="required select2 span12 exclusion_fields_sel" > 
                    <optgroup label="Select Type"> 
                        <option value="" <?php if($activity->SelfExclusionPeriod=="") echo "selected='selected'";?> ></option>
                        <?php
                        foreach($periods as $row => $period){ 
                            ?>
                        <option  value="<?=$period->PeriodID;?>" <?php if($period->PeriodID == $activity->SelfExclusionPeriod) echo "selected='selected'";?> ><?=$period->PeriodName;?></option>	 		
                            <?php 
                            }
                        ?>
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_aexclusionperiod" id="hidden_aexclusionperiod" value="" />
            </div>
        </div>
         
    </div>
    <!-- End .control-group -->
    
    
    <div class="control-group" >  
    	
        <div class="span4" >
            <label class="control-label" for="act_locked">* Account Closed</label>
            <div class="controls controls-row">  
                <label class="radio-inline" >
                    <input type="radio" name="act_closed" value="1" <?=($activity->AccountClosed==1)?'checked="checked"':"";?> > Yes
                </label> 
                
                <label class="radio-inline">
                    <input type="radio" name="act_closed" value="0" <?=($activity->AccountClosed==0 || $activity->AccountClosed=='')?'checked="checked"':"";?> > No
                </label>
            </div> 
        </div> 
        
        <div class="span4" >
            <label class="control-label" for="act_idreceived">* ID Received</label> 
            <div class="controls controls-row">  
                <label class="radio-inline" >
                    <input type="radio" name="act_idreceived" value="1"  <?=($activity->IdReceived=='1')?'checked="checked"':"";?> > Yes
                </label> 
                
                <label class="radio-inline">
                    <input type="radio" name="act_idreceived" value="0" <?=($activity->IdReceived==0 || $activity->IdReceived=='')?'checked="checked"':"";?> > No
                </label>
            </div> 
        </div> 
        
         
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >  
    	 
        <label class="control-label" for="act_spacex"> </label>
        <div class="controls controls-row" style="padding-bottom: 10px !important; " >  
            <?php
            if(admin_access() || csd_supervisor_access() || 1)
             {
            ?>
            <label class="radio-inline act-danger" >
                <input type="checkbox" value="1" name="act_important" id="act_important" style="margin-bottom: 5px;" <?=($activity->Important==1)?"checked='checked'":"";?> /> Important 
            </label>
            
            <label class="radio-inline act-danger" > 
                <input type="checkbox" value="1" name="act_iscomplaint" id="act_iscomplaint" style="margin-bottom: 5px; margin-left: 30px; " <?=($activity->IsComplaint==1)?"checked='checked'":"";?> /> Complaint
            </label> 
             
            <?php
             }
            ?> 
            
            <label class="radio-inline act-danger tip" title="Updated No." > 
                <input type="checkbox" value="1" name="act_isuploadpm" id="act_isuploadpm" style="margin-left: 30px; " <?=($activity->IsUploadPM==1)?"checked='checked'":"";?> /> Updated No.
            </label> 
            
            <label class="radio-inline act-danger tip" title="To follow up" > 
                <input type="checkbox" value="1" name="act_tofollowup" id="act_tofollowup" style="margin-left: 30px; " <?=($activity->ToFollowup==1)?"checked='checked'":"";?> /> To Follow Up
            </label>
        </div> 
    
           
    </div>
    <!-- End .control-group -->  
        
    <div class="control-group" >  
         
        <div class="span4" >
            <label class="control-label" for="act_status">* Status</label>
            <div class="controls controls-row">  
                <select name="act_status" id="act_status" class="required select2 span12" > 
                    <optgroup label="" >    
                        <?php if($activity->Status == '0' || $activity->Status == "") {?><option value="0" <?php if($activity->Status=="0") echo "selected='selected'";?> >New</option><?php } ?>
                        <?php
						if($activity->ActivityID > 0)
						 {
						foreach($status_list as $row => $status){ 
                        	$users_list = explode(",", $status->Users);
							  if(in_array($this->session->userdata("mb_usertype"),$users_list))
							   {
						?>
                        <option value="<?=$status->StatusID;?>" <?php if($activity->Status==$status->StatusID) echo "selected='selected'";?>  ><?=ucwords($status->Name);?></option>
                            <?php 
							   }
                            }
						 }//end if
						?>
                    </optgroup> 
                </select>   
                <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" />
                <input type="hidden" name="hidden_defstatus" id="hidden_defstatus" value="<?=$activity->Status?>" />
            </div>
        </div>  
        
        <div class="span4" >
        
        </div>
        
        <div class="span4" >
        
        </div>
         
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" > 
        <label class="control-label" for="act_remarks">* Remarks</label>
        <div class="controls controls-row" >  
            <textarea id="act_remarks" name="act_remarks" class="required span12" rows="3" maxlength="500" placeholder="<?=htmlentities(stripslashes($activity->Remarks))?>" ></textarea>
        </div>
    </div>
    <!-- End .control-group -->
     
    <div class="control-group" > 
        <label class="control-label" for="btn_addbanner">Attach File</label>
        <div class="controls controls-row"  id="AttachmentLoader"  >   
            <input type="file" name="act_attachfile[]" id="act_attachfile" value=""  multiple="multiple" />   
            
            <div id="AttachmentContainer">
            <?php
            //display attachments
            foreach($attachments as $row=>$attach){
                
            }//end foreach
            ?>
            </div>
        
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
var manageActivity = function() { 
	 
	$.ajax({ 
		data: new FormData($("#validate")[0]), 
		type:"POST",  
		url: "<?=base_url();?>suggestions/manageActivity", 
		dataType: "JSON", 
		processData: false,
		contentType: false,  
		cache: false,
		beforeSend:function(){       
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
		},
		success:function(msg){     
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled");
			
			if(msg.success > 0)
			 {   
			 	is_change = (msg.is_change > 0)?1:0; 
			 	createMessageMini($(".form-widget-content"), msg.message, "success"); 
				if($("#hidden_action").val()=="add")
				 {
					 $('#validate')[0].reset();
				 	 clearSelectbox($("div.controls"));  
					 $("ul.select2-choices li.select2-search-choice").remove();  
					 $.uniform.update("input[type=checkbox], input[type=radio]");
				 }
				else
				 {
					if(msg.uploaded_id > 0)
					 {
						 displayUploaded(msg.uploaded_id, activity_type);
					 }
				 }
				 
			 } 
			else
			 {
				createMessageMini($(".form-widget-content"), msg.message, "error"); 
			 } 
			
			$(".uploader").find(".filename").text("No file selected"); 
			$("input[name^=act_attachfile]").val(""); 
			  
		}
		 
	}); //end ajax
	
	//e.preventDefault();
	  
} 


var displayUploaded = function(last_id, activity) { 
 
	$.ajax({ 
		data: "last_id="+last_id+"&activity="+activity, 
		type:"POST",  
		url: "<?=base_url();?>suggestions/displayUploaded", 
		dataType: "JSON",  
		cache: false,
		beforeSend:function(){       
			$("#AttachmentContainer").html("");
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
			$("#AttachmentLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="attachment_loader" />');
		},
		success:function(msg){   
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled");  
			$(".attachment_loader").remove();  
			
			if(msg.success > 0)
			 {  
				//display uploaded
				var uploads = msg.uploaded_data;//JSON.parse(msg.upload_data);   
				$.each(uploads, function( index, value ) { 
					//alert(value.full_path);
					var icon = "i-image-2";
					$("#AttachmentContainer").prepend("<span class=\"btn-group uploaded-item\" attach-id=\""+value.AttachID+"\" ><a class=\"btn uploaded-filename tip\" title=\""+value.ClientFilename+"\" ><i class=\"icon16 "+icon+"\"></i>"+value.ClientFilename+"</a><a class=\"btn btn-danger remove-uploaded tip\" title=\"delete attachment\" ><i class=\"icon16 i-close-2\"></i></a></span>");
				});
				 
			 } 
			else
			 {
				//createMessageMini($(".form-widget-content"), msg.message, "error"); 
			 } 
			
			$(".uploaded-item .uploaded-filename").click(function(){
				var attach_id = $(this).closest(".uploaded-item").attr("attach-id"); 
				if(attach_id)window.location.href = "<?=base_url()?>suggestions/downloadAttachment/"+last_id+"/"+activity_type+"/"+attach_id;   
			});
			
			$(".uploaded-item .remove-uploaded").click(function(){
				var attach_id = $(this).closest(".uploaded-item").attr("attach-id");
				if(attach_id)deleteAttachment(attach_id,"<?=base_url();?>suggestions/deleteAttachment"); 
			});  
			  
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
	//$.fn.modal.Constructor.prototype.enforceFocus = function() {};
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate").validate({
		 submitHandler: function(form) { 
		 	manageActivity();//check duplicate username 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: {
			act_assignee: {
				required: true 
			},
			act_username: {
				required: true 
			}, 
			act_currency: {
				required: true 
			},
			act_source: {
				required: true 
			},
			act_product: {
				required: true 
			},     
			act_complainttype: {
				required: true 
			},  
			act_status: {
				required: true 
			},
			act_remarks: {
				required: true 
			}
			 
		},
		messages: {
			act_assignee: {
				required: "Select assignee" 
			},
			act_username: {
				required: "Provide username" 
			},
			act_currency: {
				required: "Provide currency" 
			},
			act_source: {
				required: "Select source" 
			},
			act_product: {
				required: "Select product" 
			}, 
			act_complainttype: {
				required: "Select complaint type" 
			},
		   act_effectivedate: {
				required: "Select effective date" 
			},
           act_enddate: {
				required: "Select end date" 
			},			  
			act_exclusionperiod: {
				required: "Select self exclusion period" 
			}, 
			act_status: {
				required: "Select status" 
			}, 
			act_remarks: {
				required: "Please provide remarks" 
			} 
			
		}
	}); 
 
	  
	//------------- Form validation -------------//
	  
	$("#act_accountproblem").change(function(){    
		$("#hidden_aaccountproblem").val($(this).find(":selected").text()); 
	}); 
	$("#act_accountproblem").trigger("change");
	 
	$("#act_currency").change(function(){    
		$("#hidden_acurrency").val($(this).find(":selected").text()); 
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
	
	<?php
	if($activity->Status === '0')
	 {
	?> 
	$("#act_assignee").change(function(){  
		
		if($("#hidden_defassignee").val() > 0  )
		 {
			$("#act_status optgroup").find("option").removeAttr("selected");  
			if($("#hidden_defassignee").val() != $(this).val())
			 {  
				$("#act_status optgroup").find("option[value='<?=$settings_ids[new_status]?>']").remove();//attr('hidden', 'hidden');   
				$("#act_status optgroup").find("option[value='<?=$settings_ids[inprogress_status]?>']").attr('selected', 'selected');  
				$("#act_status").select2(); 
				$("#act_status").select2("val", "<?=$settings_ids[inprogress_status]?>"); //set the value
			 }
			else
			 {
				$("#act_status optgroup").find("option[value='<?=$settings_ids[new_status]?>']").remove(); 
				$("#act_status optgroup").prepend("<option value=\"<?=$settings_ids[new_status]?>\" selected=\"selected\" >New</option>")  
				$("#act_status").select2();  
				
			 }
			 	  
		 }  
		 
		$("#act_status").trigger("change");
		$("#hidden_aassignee").val($(this).find(":selected").text());
	}); 
	$("#act_assignee").trigger("change"); 
	<?php	 
	 }
	else
	 {
	?> 
	$("#act_assignee").change(function(){  
		
		if($("#hidden_defassignee").val() > 0  )
		 {
			if($("#hidden_defassignee").val() != $(this).val())
			 {      
				$("#act_status optgroup").find("option[value='<?=$settings_ids[inprogress_status]?>']").attr('selected', 'selected');  
				$("#act_status").select2(); 
				$("#act_status").select2("val", "<?=$settings_ids[inprogress_status]?>"); //set the value
			 }
			else
			 { 
				//$("#act_status").select2(); 
				$("#act_status").select2("val", $("#hidden_defstatus").val()); 
			 }
			 	  
		 } 
		
		$("#act_status").trigger("change");
		$("#hidden_aassignee").val($(this).find(":selected").text());
	});
	<?php	 
	 }
	?> 
	
	
	$("#act_complainttype").change(function(){     
		var method = $(this).val();  
		if(method == 3 || method== '3')
		 { 
		  	 $("#IfSelfExclusion").show();    
			 $('.exclusion_fields').each(function(e) { 
				$(this).rules('add', {
					required: true
				});
			 });
			 
			 $("#act_exclusionperiod").each(function(e) { 
				$(this).rules('add', {
					required: true
				});
			 });
			  
		 }
		else
		 {
			
			$("#IfSelfExclusion").hide();    
			$('.exclusion_fields').each(function(e) { 
				$(this).rules('add', {
					required: false
				});
			});
			
			 $("#act_exclusionperiod").each(function(e) { 
				$(this).rules('add', {
					required: false
				});
			 });
			   
		 }
		$("#hidden_acomplainttype").val($(this).find(":selected").text()); 
	}); 
	$("#act_complainttype").trigger("change"); 
	
	$("#act_exclusionperiod").change(function(){ 
		$("#hidden_aexclusionperiod").val($(this).find(":selected").text());
	});
	$("#act_exclusionperiod").trigger("change");
	
	 
	
	 
	//display attachment
	displayUploaded("<?=$activity->ActivityID?>", activity_type);  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation(); 
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	
	$("#BtnBackList").click(function(){
		window.location.href = "<?=base_url()?>suggestions/activities"
	}); 
	
	
	$('#datepicker1').datetimepicker({    
		pickTime: true,
		//todayBtn: true,  
		todayHighlight: false/*,
		autoclose: true, 
		pickerPosition: "bottom-left"*/ 
		
	}); 
	
	$('#datepicker2').datetimepicker({    
		pickTime: true,
		//todayBtn: true,  
		todayHighlight: false/*,
		autoclose: true, 
		pickerPosition: "bottom-left"*/ 
		
	}); 
	
	$('#validate select').select2({placeholder: "Select"}); 
	
}); 
 

</script> 