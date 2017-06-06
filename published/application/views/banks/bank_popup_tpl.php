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

label.control-label {
	width: 175px !important; 	
} 

.form-horizontal .controls {
	margin-left: 195px; 	
}
</style>

<div class="row-fluid form-widget-content" >   

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
                <select name="act_currency" id="act_currency" class="required select2 span12" > 
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
                <input type="text" id="act_username" name="act_username" class="required span12 tip" value="<?=($activity->Username)?htmlentities(stripslashes($activity->Username)):$default_user->Username;?>" maxlength="100" title="Enter username" <?php if(count($acvitiy)<=0 && $default_user->UserID){?>readonly="readonly" <?php }?>  > 
            </div>
        </div> 
         
    </div>
    <!-- End .control-group --> 

    <div class="control-group" >  
        <div class="span4" >
            <label class="control-label" for="act_esupportid">E-Support ID</label>
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
            <label class="control-label" for="act_methodtype">* Method Type</label>
            <div class="controls controls-row">  
                <select name="act_methodtype" id="act_methodtype" class="required select2 span12" > 
                    <optgroup label="Select Source"> 
                        <option value="" <?php if($activity->Category=="") echo "selected='selected'";?> ></option>
                        <?php
						foreach($types as $row => $type){ 
                            ?>
                        <option  value="<?=$type->Category;?>" <?php if(strtolower($activity->Category) == strtolower($type->Category)) echo "selected='selected'";?> ><?=ucwords($type->Category);?></option>
                            <?php 
                            }
						?>
                    </optgroup>  
                    
                </select>  
                <input type="hidden" name="hidden_amethodtype" id="hidden_amethodtype" value="" />
            </div>
        </div> 
        
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <div class="span4" > 
            <label class="control-label" for="act_depmethod" >Deposit Method</label>
            <div class="controls controls-row">  
                <select name="act_depmethod" id="act_depmethod" class="select2 span12" disabled="disabled"> 
                    <optgroup label="" >    
                        <option value="" ></option> 
                    </optgroup> 
                </select>  
                <input type="hidden" name="hidden_adepmethod" id="hidden_adepmethod" value="" />
            </div>
        </div> 
        
        <div class="span4" > 
            <label class="control-label" for="act_transactionid">Transaction ID</label>
            <div class="controls controls-row">  
                <input type="text" id="act_transactionid" name="act_transactionid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->TransactionID));?>" maxlength="30" title="Enter Transaction ID" >
            </div>
        </div> 
        
        <div class="span4" > 
            <label class="control-label" for="act_amount">* <span class="amount-txt" ></span> Amount</label>
            <div class="controls controls-row">  
                <input type="text" id="act_amount" name="act_amount" class="required  span12 tip right" value="<?=htmlentities(stripslashes(number_format($activity->Amount, 2, '.', ',')));?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div> 
        
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
    	<div class="span4" >  
            <label class="control-label" for="act_method">* Issue</label>
            <div class="controls controls-row">  
                <select name="act_method" id="act_method" class="required select2 span12" disabled="disabled"> 
                    <optgroup label="" >    
                        <option value="" ></option> 
                    </optgroup> 
                </select>  
                <input type="hidden" name="hidden_amethod" id="hidden_amethod" value="" />
            </div>
        </div>
         
    	<div class="span4 sbf-method hide" > 
            <label class="control-label" for="act_bonusdeduct"> Bonus Deduct</label>
            <div class="controls controls-row">  
                <input type="text" id="act_bonusdeduct" name="act_bonusdeduct" class="span12 tip right" value="<?=htmlentities(stripslashes(number_format($activity->BonusDeduct, 2, '.', ',')));?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div> 
        
        <div class="span4 sbf-method hide" > 
            <label class="control-label" for="act_winningsdeduct"> Winnings Deduct</label>
            <div class="controls controls-row">  
                <input type="text" id="act_winningsdeduct" name="act_winningsdeduct" class="span12 tip right" value="<?=htmlentities(stripslashes(number_format($activity->WinningsDeduct, 2, '.', ',')));?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div> 
        
        <div class="span4 over-amt hide" > 
            <label class="control-label" for="act_actualamount" >* Act <span class="actual-amount" >Overpaid</span> Amt</label>
            <div class="controls controls-row">  
                <input type="text" id="act_actualamount" name="act_actualamount" class="span12 tip right adj-required-fields" value="<?=htmlentities(stripslashes(number_format($activity->ActualOverAmount, 2, '.', ',')));?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div> 
        
        <div class="span4 over-amt hide" > 
            <label class="control-label" for="act_adjustmentamount" >* Act Adjustment Amt</label>
            <div class="controls controls-row">  
                <input type="text" id="act_adjustmentamount" name="act_adjustmentamount" class="span12 tip right adj-required-fields" value="<?=htmlentities(stripslashes(number_format($activity->ActualAdjustmentAmount, 2, '.', ',')));?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div> 
         
    </div>
    <!-- End .control-group -->
    
    <div class="control-group wdrejected-amt hide" >     
        <div class="span4" > 
            <label class="control-label" for="act_amountmade" >* <span class="actual-amount" >DTO</span> Made</label>
            <div class="controls controls-row">  
                <input type="text" id="act_amountmade" name="act_amountmade" class="span12 tip right wdrejected-required-fields" value="<?=($activity->AmountMade > 0)?htmlentities(stripslashes(number_format($activity->AmountMade, 2, '.', ','))):"";?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div> 
        
        <div class="span4" > 
            <label class="control-label" for="act_amountneed" >* <span class="actual-amount" >DTO</span> Need</label>
            <div class="controls controls-row">  
                <input type="text" id="act_amountneed" name="act_amountneed" class="span12 tip right wdrejected-required-fields" value="<?=($activity->AmountNeed > 0)?stripslashes(number_format($activity->AmountNeed, 2, '.', ',')):"";?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div>
        
        <div class="span4" > 
            <label class="control-label" for="act_outstandingamount" >Outstanding Amount</label>
            <div class="controls controls-row">  
                <input type="text" id="act_outstandingamount" name="act_outstandingamount" class="span12 tip right" value="<?=($activity->OutstandingAmount > 0)?stripslashes(number_format($activity->OutstandingAmount, 2, '.', ',')):"";?>" maxlength="20" title="You can use decimal values. Ex. 100.50" >
            </div>
        </div> 
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" >  
        <?php /*?><div class="span4" > 
            <label class="control-label" for="act_idreceived">* ID Received</label> 
            <div class="controls controls-row">  
            	<label class="radio-inline" >
                    <input type="radio" name="act_idreceived" value="1"  <?=($activity->IdReceived=='1')?'checked="checked"':"";?> > Yes
                </label> 
                
                <label class="radio-inline">
                    <input type="radio" name="act_idreceived" value="0" <?=($activity->IdReceived==0 || $activity->IdReceived=='')?'checked="checked"':"";?> > No
                </label>
            </div> 
        </div> <?php */?>
       	<div class="span4" > 
            <label class="control-label" for="act_reason">Analysis Reason</label>
            <div class="controls controls-row">  
                <select name="act_reason" id="act_reason" class="select2 span12 gap-right10 important-txt reason-options" disabled="disabled"  > 
                    <optgroup label="" >    
                        <option value="" ></option> 
                    </optgroup> 
                </select>   
                <input type="hidden" name="hidden_areason" id="hidden_areason" value="" />
                <input type="text" id="act_reasonspecify" name="act_reasonspecify" class="span12 tip hide" value="<?=htmlentities(stripslashes($activity->ReasonSpecify));?>" maxlength="30" title="Specify reason" placeholder="Specify reason" >
            </div>
        </div>
         
        <div class="span4" >   
        	<?php
			if(admin_access() || csd_supervisor_access() || 1)
			 {
			?>
            <label class="radio-inline act-danger tip" title="Important" >
                <input type="checkbox" value="1" name="act_important" id="act_important" style="margin-bottom: 5px;" <?=($activity->Important==1)?"checked='checked'":"";?> /> Important 
            </label>
            
            <label class="radio-inline act-danger tip" title="Complaint" > 
                <input type="checkbox" value="1" name="act_iscomplaint" id="act_iscomplaint" style="margin-bottom: 5px; margin-left: 30px; " <?=($activity->IsComplaint==1)?"checked='checked'":"";?> /> Complaint
            </label> 
            <?php
			 }
			?>
            
            <label class="radio-inline act-danger tip" title="To follow up" > 
                <input type="checkbox" value="1" name="act_tofollowup" id="act_tofollowup" style="margin-bottom: 5px; margin-left: 30px; " <?=($activity->ToFollowup==1)?"checked='checked'":"";?> /> To Follow Up 
            </label>  
            
            <!-- Upload PM change to Updated No. -->
            <label class="radio-inline act-danger tip" title="Upload Personal Message" > 
                <input type="checkbox" value="1" name="act_isuploadpm" id="act_isuploadpm" style="margin-bottom: 5px; margin-left: 30px; " <?=($activity->IsUploadPM==1)?"checked='checked'":"";?> /> Updated No. 
            </label>         
        </div> 
         
        <div class="span4 account-adj hide" > 
            <label class="control-label" for="act_adjustment">* Account Adjustment</label>
            <div class="controls controls-row">  
                <select name="act_adjustment" id="act_adjustment" class="select2 span12 adj-required-fields" > 
                    <optgroup label="" >    
                        <option value="" <?php if($activity->AccountAdjustment=="" || $activity->AccountAdjustment==0) echo "selected='selected'";?> ></option>
                        <?php
						foreach($adjustments as $row => $adjustment){ 
                            ?>
                        <option  value="<?=$adjustment->AdjustmentID;?>" <?php if($activity->AccountAdjustment==$adjustment->AdjustmentID) echo "selected='selected'";?> ><?=ucwords($adjustment->AdjustmentName);?></option>
                            <?php 
                            }
						?>
                    </optgroup> 
                </select>  
                <input type="hidden" name="hidden_aadjustment" id="hidden_aadjustment" value="" />
            </div>
        </div>
         
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" > 
         
        <div class="span4" > 
            <label class="control-label" for="act_status">* Status</label>
            <div class="controls controls-row">  
                <select name="act_status" id="act_status" class="required select2 span12"   > 
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
         
        
    </div>
    <!-- End .control-group --> 
    
    <div class="control-group" > 
        <label class="control-label" for="act_remarks">* Remarks</label>
        <div class="controls controls-row" >  
            <textarea id="act_remarks" name="act_remarks" class="required span12" rows="3" maxlength="500" placeholder="<?=htmlentities(stripslashes($activity->Remarks))?>"  ></textarea>
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <label class="control-label" for="btn_addbanner">Attach File</label>
        <div class="controls controls-row"  id="AttachmentLoader"  >   
            <input type="file" name="act_attachfile[]" id="act_attachfile" value=""  multiple="multiple"  />   
            
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
var manageBankActivity = function() { 
	 
	$.ajax({ 
		data: new FormData($("#validate")[0]), 
		type:"POST",  
		url: "<?=base_url();?>banks/manageActivity", 
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
					$("#hidden_defassignee").val($("#act_assignee").val()); 
					$("#hidden_defstatus").val($("#act_status").val()); 
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
		url: "<?=base_url();?>banks/displayUploaded", 
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
				if(attach_id)window.location.href = "<?=base_url()?>banks/downloadAttachment/"+last_id+"/"+activity_type+"/"+attach_id;   
			});
			
			$(".uploaded-item .remove-uploaded").click(function(){
				var attach_id = $(this).closest(".uploaded-item").attr("attach-id");
				if(attach_id)deleteAttachment(attach_id,"<?=base_url();?>banks/deleteAttachment"); 
			});  
			  
		}
		 
	}); //end ajax
	 
}


function changeAnalysisReasons(url, type, default_reason, container, display){ 
 
	$.ajax({ 
		data: "rand="+Math.random()+"&type="+type,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		beforeSend:function(){   
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />'); 
			 if(type == "")
			  {	
			  	  container.html('<option value=""></option>');
			  	  container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }
		},
		success:function(newdata){    
			
			var method_analysis = [18,22,27,2,6,13];
			var method_sbf = [29,30]; 
			
			var account_adj = [13,27];
			var wd_rejected = [35,36]; 
			//var over_amt = [13,27]; 
			
			var method = parseInt(type);
			 
		 	container.html('<option value="">&nbsp;&nbsp;</option>');
		  	if(newdata.length > 0)
			 {    
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {  
					var new_string = ""; 
					new_string += "<optgroup label=\""+value.category_name+"\">";  
					$.each(value.values, function( index, value ){
						selected = (default_reason ==  value.ReasonID)?'selected="selected"':'';
						new_string += '<option value="'+value.ReasonID+'" '+selected+' is-specify="'+value.IsSpecify+'" >'+value.ReasonName+'</option>';  
						
						//alert(value.category_name + container);
					});
					
					new_string += "</optgroup>";   
					container.append(new_string);
				});
				   
				if($.inArray(method, method_analysis) !== -1)
				 {    
					 //make analysis important 
					 container.each(function (e) { 
						$(this).rules('add', {
							required: true
						});
					 });
				 }
				else
				 {
					container.each(function (e) { 
						$(this).rules('add', {
							required: false
						});
					});  
					
					//container.attr('disabled', true); 
					 
				 }
				  
			 }
		    else
			 {  
			 	 //set to important
				 container.each(function (e) { 
					$(this).rules('add', {
						required: false
					});
				 });	
				 
				 container.attr('disabled', true); 
			 } 
			 
			 if($.inArray(method, method_sbf) !== -1)
			 {   
			 	 //show bonus deduct and winning deduct   
				 $(".sbf-method").show();  
			 }
			else
			 {
				 $(".sbf-method").hide();  
			 }
			 
			  
			 //for account adjustment
			 if($.inArray(method, account_adj) !== -1)
			 {    
			 	 //show actual paid amount
				 $(".over-amt").show(); 
				 
				 //make analysis important 
				 $(".account-adj").show();  
				 $("select.adj-required-fields, input.adj-required-fields").each(function() {   
					$(this).rules('add', {
						required: true
					});
				 });
				 
				 /*$("input.adj-required-fields").each(function() {    
					$(this).rules('add', {
						required: true
					});
				 });*/
				  
			 }
			else
			 {
				 $(".over-amt").hide();  
				  
				 $(".account-adj").hide();   
				 $("select.adj-required-fields, input.adj-required-fields").each(function() { 
					$(this).rules('add', {
						required: false
					});
				 });
				 
				 /*$("input.adj-required-fields").each(function () {  
					$(this).rules('add', {
						required: false
					});
				 });*/
				  
			 }
			 
			 
			 //for WD REJECTED
			 if($.inArray(method, wd_rejected) !== -1)
			 {    
			 	 //show actual paid amount
				 $(".wdrejected-amt").show(); 
				 if(method == 35)
				  {	
				  	$(".wdrejected-amt").find(".actual-amount").html("BTO");
				  }
				 else
				  {
					$(".wdrejected-amt").find(".actual-amount").html("DTO");
				  }
				 //make analysis important 
				 $(".wdrejected-amt").show();  
				 $("select.wdrejected-required-fields, input.wdrejected-required-fields").each(function() {   
					$(this).rules('add', {
						required: true
					});
				 }); 
				  
			 }
			else
			 {
				 $(".wdrejected-amt").hide();  
				   
				 $("select.wdrejected-required-fields, input.wdrejected-required-fields").each(function() { 
					$(this).rules('add', {
						required: false
					});
				 }); 
				  
			 }
			 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			$("#act_reason").trigger("change");
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
		 	manageBankActivity();//check duplicate username 
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
			act_methodtype: {
				required: true 
			},
			act_method: {
				required: true  
			},
			act_amount: {
				required: true, 
				number: true
			}, 
			act_actualamount: {
				required: true, 
				number: true
			},
			act_bonusdeduct: { 
				number: true
			}, 
			act_winningsdeduct: { 
				number: true
			},  
			act_status: {
				required: true 
			},
			act_remarks: {
				required: true 
			},  
			act_amountneed: { 
				number: true
			}, 
			act_amountmade: { 
				number: true
			}, 
			act_outstandingamount: { 
				number: true
			}, 
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
			act_methodtype: {
				required: "Select method type" 
			},
			act_method: {
				required: "Select method" 
			},
			act_amount: {
				required: "Please provide amount", 
				number: "Please enter a valid amount. Ex. 100.50" 
			}, 
			act_status: {
				required: "Select status" 
			},
			act_reason: {
				required: "Select reason" 
			}, 
			act_remarks: {
				required: "Please provide remarks" 
			}, 
			act_adjustment: {
				required: "Select account adjustment" 
			 },   
			act_actualamount: {
				required: "Enter actual over amt" 	
			},
			act_adjustmentamount: {
				required: "Enter act adjustment amt" 	
			},
			act_amountmade: {
				required: "Enter amount made" 	
			},
			act_amountneed: {
				required: "Enter amount need" 	
			}
		}
	}); 
	
	$('#datepicker1').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii"
		pickTime: false
	}); 
	 
	
	//checking language
	$("input:checkbox[name=lang_option]").click(function(){
		 selected_lang = $('input[name="lang_option"]:checked').map(function() {return this.value;}).get().join(',');
		 $("#overpay_language").val(selected_lang); 
	});  
	
	
	//------------- Form validation -------------//
	 
	$("#act_methodtype").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		//actual-amount 
		var actual_amt_txt = ($(this).find(":selected").text() == "Deposit")?"Overcredit":"Overpaid";
		$("#hidden_amethodtype").val($(this).find(":selected").text());
		$(".amount-txt").html("Act " + $(this).find(":selected").text()); 
		$(".actual-amount").html(actual_amt_txt); 
		
		changeActivityMethods("<?=base_url()?>banks/getActivityMethods", $(this).val(), "<?=$activity->CategoryID;?>", $("#act_method"));   
	}); 
	$("#act_methodtype").trigger("change"); 
	
	$("#act_reason").change(function(){    
		var is_specify = $(this).find(":selected").attr("is-specify")
		//$("#act_reasonspecify").val("");
		if(is_specify > 0)
		 {
			$("#act_reasonspecify").removeClass("hide"); 
		 }
		else
		 {
			 $("#act_reasonspecify").addClass("hide"); 
		 }
		$("#hidden_areason").val($(this).find(":selected").text());  
	}); 
	$("#act_reason").trigger("change");
	
	
	$("#act_method").change(function(){   
		$("#hidden_amethod").val($(this).find(":selected").text());   
		changeAnalysisReasons("<?=base_url()?>banks/getAnalysisReasons", $(this).val(), "<?=$activity->AnalysisReason;?>", $("#act_reason"));  
	}); 
	//$("#act_method").trigger("change");
	
	$("#act_depmethod").change(function(){    
		$("#hidden_adepmethod").val($(this).find(":selected").text()); 
	}); 
	$("#act_depmethod").trigger("change");
	
	$("#act_currency").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>');  
		$("#hidden_acurrency").val($(this).find(":selected").text());
		changeDepositMethods("<?=base_url()?>banks/getDepositMethods", $(this).val(), "<?=$activity->DepositMethodID;?>", $("#act_depmethod"));
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
	 
	$("#act_adjustment").change(function(){   
		$("#hidden_aadjustment").val($(this).find(":selected").text());    
	}); 
	$("#act_adjustment").trigger("change");
	
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
	
	
	/*$('#act_currency').select2({placeholder: "Select"}); */
	 
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
		window.location.href = "<?=base_url()?>banks/activities"
	}); 
	
	$('#validate select').select2({placeholder: "Select"}); 
	
}); 
 

</script> 