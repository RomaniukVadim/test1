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

/*
.form-horizontal .control-label { 
    width: 140px;
}

.form-horizontal .controls {
    margin-left: 150px;
}

.form-horizontal .right-panel .control-label { 
    width: 200px;
}

.form-horizontal .right-panel .controls {
    margin-left: 210px;
}*/

 

.done {
	/*color: #7B110;*/
	color: #cb1112 !important;	
}

.wizard-steps .wstep.done .donut {
    border-color: #cb1112; 
	color: #cb1112;
}

.wizard-steps .wstep.done {
    color: #cb1112;
}

.wizard-steps .wstep.done .donut i {
    color: #cb1112;
}

/*.wstep {
	cursor: pointer; 	
}*/
</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate" name="validate" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post" enctype="multipart/form-data" onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
    
    <?php /*?><div class="wizard-steps show">
        <div class="wstep details-tab current done" data-step-num="0" target="CsaContent" >
            <div class="donut">
            	<i class="icon24 i-user"></i>
            </div>
        	<span class="txt">CSA</span>
        </div>
        
        <div class="wstep current details-tab" data-step-num="1" target="CrmContent" >
            <div class="donut">
            	<i class="icon24 i-phone-2"></i>
            </div>
            <span class="txt">CRM</span>
        </div> 
    </div><?php */?>
	
    <!-- CSA Form -->
    <div id="CsaContent" class="tab2-content"  > 
     
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
                            <option value="<?=$assignee->GroupID;?>" <?php if($assignee->GroupID == $activity->GroupAssignee) echo "selected='selected'";?> class="<?=$stat_class?>"  ><?=$assignee->UserTypeName;?></option>	 		
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
                <label class="control-label" for="act_casinoproduct">* Casino Product</label>
                <div class="controls controls-row"  >
                    <select name="act_casinoproduct" id="act_casinoproduct" class="required select2 span12" > 
                        <optgroup label="Select Product"> 
                            <option value="" <?php if($activity->SubProductID=="") echo "selected='selected'";?> ></option>
                            <?php
                            foreach($sub_products as $row => $sub_product){ 
                                ?>
                            <option  value="<?=$sub_product->SubID;?>" <?php if($sub_product->SubID == $activity->SubProductID) echo "selected='selected'";?> ><?=$sub_product->Name;?></option>	 		
                                <?php 
                                }
                            ?>
                             
                        </optgroup>  
                        
                    </select> 
                    <input type="hidden" name="hidden_acasinoproduct" id="hidden_acasinoproduct" value="" />
                </div>
            </div> 
             
        </div>
        <!-- End .control-group -->
        
        <div class="control-group" >   
        	<div class="span4" >
                <label class="control-label" for="act_issuecategory">* Issue Category</label>
                <div class="controls controls-row">  
                    <select name="act_issuecategory" id="act_issuecategory" class="required select2 span12" > 
                        <optgroup label="Select Category"> 
                            <option value="" <?php if($activity->IssueCategory=="") echo "selected='selected'";?> ></option>
                            <?php
                            foreach($issue_categories as $row => $issue_category){ 
                                ?>
                            <option  value="<?=$issue_category->CategoryID;?>" <?php if($issue_category->CategoryID == $activity->IssueCategory) echo "selected='selected'";?> ><?=$issue_category->Name;?></option>	 		
                                <?php 
                                }
                            ?>
                             
                        </optgroup>  
                        
                    </select> 
                    <input type="hidden" name="hidden_aissuecategory" id="hidden_aissuecategory" value="" />
                </div>
            </div>
            
            <div class="span4" > 
                 <label class="control-label" for="act_transactionid">Transaction ID</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_transactionid" name="act_transactionid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->TransactionID));?>" maxlength="30" title="Enter Transaction ID" > 
                </div>
            </div> 
            
            <div class="span4" > 
                <label class="control-label" for="act_playtechticketid">Playtech Ticket ID</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_playtechticketid" name="act_playtechticketid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->PlaytechTicketID));?>" maxlength="30" title="Enter E-Support Ticket ID" > 
                </div>
            </div> 
              
        </div>
        <!-- End .control-group --> 
        
        <div class="control-group" >    
        	  
        	 <div class="span4" >
                <label class="control-label" for="act_daterequested">Date Requested</label>
                    <div class="controls controls-row">   
                        <div id="datepicker3" class="input-append datepicker" > 
                             <span class="add-on">
                                <i class="icon16"></i>
                            </span>
                            <input type="text" value="<?=($activity->DateRequested != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->DateRequested)):"";?>"  name="act_daterequested" id="act_daterequested" data-format="yyyy-MM-dd hh:mm:ss"   class="myselect tip call-fieldimportant" title="Select date requested"  >
                        </div>   
                </div>
            </div> 
            
            <div class="span4" > 
                <label class="control-label" for="act_datesolved">Date Solved</label>
                <div class="controls controls-row">
                    <div id="datepicker4" class="input-append datepicker"  > 
                         <span class="add-on">
                            <i class="icon16 "></i>
                        </span>
                        <input type="text" value="<?=($activity->DateSolved != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->DateSolved)):"";?>"  name="act_datesolved" id="act_datesolved" data-format="yyyy-MM-dd hh:mm:ss"   class="myselect tip call-fieldimportant" title="Select date solved" >
                    </div>
                </div>
            </div> 
             
        </div>
        <!-- End .control-group -->
        
        <div class="control-group" >    
        	  
            <div class="span4" > 
                <label class="control-label" for="act_amount">Amount</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_amount" name="act_amount" class="span12 tip txt-currency" value="<?=htmlentities(stripslashes($activity->Amount));?>" maxlength="15" title="Enter Amount" > 
                </div>
            </div>
 
        	 <div class="span4" >
                <label class="control-label" for="act_viplevel">VIP Level</label>
                <div class="controls controls-row">   
                	<select name="act_viplevel" id="act_viplevel" class="select2 span12" > 
                        <optgroup label="Select VIP Level"> 
                            <option value="0" <?php if($activity->VIPLevel=="" || $activity->VIPLevel==0) echo "selected='selected'";?> ></option>
                            <?php
                            for($i=1; $i<=$this->max_vip_level; $i++){ 
                                ?>
                            <option  value="<?=$i;?>" <?php if($i == $activity->VIPLevel) echo "selected='selected'";?> ><?=$i;?></option>	 		
                                <?php 
                                }
                            ?>
                             
                        </optgroup>  
                        
                    </select>        
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
            
            <div class="span8"  > 
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
                    <input type="checkbox" value="1" name="act_tofollowup" id="act_tofollowup" style="margin-left: 30px; " <?=($activity->ToFollowup==1)?"checked='checked'":"";?> /> Fo Follow Up
                </label>
                
            </div>
              
        </div>
        <!-- End .control-group -->  
        
        <div class="control-group" > 
            <label class="control-label" for="act_remarks">* Remarks</label>
            <div class="controls controls-row" >  
                <textarea id="act_remarks" name="act_remarks" class="required span12 tip" rows="3" title="Enter remarks" maxlength="500" placeholder="<?=htmlentities(stripslashes($activity->Remarks));?>" ></textarea>
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
        
    </div>
    <!-- END  CSA Form -->
    
    <!-- CRM Form -->
    <div id="CrmContent" class="tab2-content hide"  > 
     
        <div class="row-fluid"  >  
        	 
            <div class="control-group" >  
                <div class="span4" > 
                    <label class="control-label" for="act_callstart">Start Call</label>
                    <div class="controls controls-row">
                        <div id="datepicker1" class="input-append datepicker"  > 
                             <span class="add-on">
                                <i class="icon16 "></i>
                            </span>
                            <input type="text" value="<?=($activity->CallStart != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->CallStart)):"";?>"  name="act_callstart" id="act_callstart" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip call-fieldimportant" title="Select start call"  >
                        </div>
                    </div> 
                </div> 
                
                <div class="span4" >
                    <label class="control-label" for="act_callend">End Call</label>
                    <div class="controls controls-row">
                        <div id="datepicker2" class="input-append datepicker"  > 
                             <span class="add-on">
                                <i class="icon16 "></i>
                            </span>
                            <input type="text" value="<?=($activity->CallEnd != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->CallEnd)):"";?>"  name="act_callend" id="act_callend" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip call-fieldimportant" title="Select end call" >
                        </div>
                    </div>
                </div> 
                
                <div class="span4" > 
                    
                </div> 
                 
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" >   
            	<div class="span4" >
                    <label class="control-label" for="act_callresult">Result</label>
                    <div class="controls controls-row"> 
                        <select name="act_callresult" id="act_callresult" class="select2 myselect call-fieldimportant"  > 
                            <option value="" >- Select Result -</option> 
							<?php
                            foreach($results as $row=>$result) {
                            ?> 
                            <option value="<?=$result->result_id;?>" <?php if($activity->CallResultID==$result->result_id) echo "selected='selected'";?>  ><?=$result->result_name;?></option>
                            <?php	
                            }//end foreach
                            ?> 
                        </select>  
                        <input type="hidden" name="hidden_acallresult" id="hidden_acallresult"  value="<?=$activity->ResultName;?>" /> 
                    </div>
                </div>
                
                <div class="span4" > 
                    <label class="control-label" for="act_calloutcome">Outcome</label>
                    <div class="controls controls-row">
                        <select name="act_calloutcome" id="act_calloutcome" class="select2 myselect call-fieldimportant" disabled="disabled"  > 
                            <option value="" >- Select Outcome -</option> 
							<?php
                            foreach($outcomes as $row=>$outcome) {
                            ?> 
                            <option value="<?=$outcome->outcome_id;?>" <?php if($activity->CallOutcomeID==$outcome->outcome_id) echo "selected='selected'";?> result-id="<?=$outcome->result_id?>" result-name="<?=$outcome->result_name?>" ><?=$outcome->outcome_name;?></option>
                            
                            <?php	
                            }//end foreach
                            ?> 
                        </select>  
                        <input type="hidden" name="hidden_acalloutcome" id="hidden_acalloutcome"  value="<?=$activity->OutcomeName;?>" />
                    </div> 
                </div> 
                 
                
                <div class="span4" > 
                    <div class="controls controls-row" style="margin: 0" >
                        <label class="radio-inline" >
                            <input type="checkbox" value="1" name="act_callsendsms" id="act_callsendsms" <?=($activity->CallSendSMS==1)?"checked='checked'":"";?>  /> Send SMS 
                        </label> 
                        
                        <label class="radio-inline" >
                            <input type="checkbox" value="1" name="act_callsendemail" id="act_callsendemail"  <?=($activity->CallSendEmail==1)?"checked='checked'":"";?> /> Send Email
                        </label>     
                    </div>
                </div> 
                 
            </div>
            <!-- End .control-group -->  
             
            
            <div class="control-group" > 
                <label class="control-label" for="act_callsendsms">&nbsp;</label>
                <div class="controls controls-row"  >
                    <label class="radio-inline" >
                        <input type="radio" value="account_active" name="act_callproblem" <?=($activity->CallProblem=="account_active")?"checked='checked'":"";?> /> Account Active
                    </label> 
                    
                    <label class="radio-inline" >
                        <input type="radio" value="number_invalid" name="act_callproblem" <?=($activity->CallProblem=="number_invalid")?"checked='checked'":"";?> /> Number Invalid
                    </label> 
                    
                    <label class="radio-inline" >
                        <input type="radio" value="account_frozen" name="act_callproblem" <?=($activity->CallProblem=="account_frozen")?"checked='checked'":"";?> /> Account Frozen
                    </label> 
                    
                    <label class="radio-inline" >
                        <a href="#" class="uncheck_callproblem" >uncheck</a>
                    </label> 
                </div> 
                  
            </div>             
            <!-- End .control-group --> 
 
                 
        </div> 
        
    </div>
    <!-- END CRM Form -->
    
      
    <div class="control-group" > 
         
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
		url: "<?=base_url();?>casino/manageActivity", 
		dataType: "JSON", 
		processData: false,
		contentType: false,  
		cache: false,
		beforeSend:function(){       
			//$("#BtnSubmitForm").addClass("disabled");  
			//$("#BtnSubmitForm").attr("disabled", "disabled");    
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
		url: "<?=base_url();?>casino/displayUploaded", 
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
				if(attach_id)window.location.href = "<?=base_url()?>access/downloadAttachment/"+last_id+"/"+activity_type+"/"+attach_id;   
			});
			  
			$(".uploaded-item .remove-uploaded").click(function(){
				var attach_id = $(this).closest(".uploaded-item").attr("attach-id");
				if(attach_id)deleteAttachment(attach_id,"<?=base_url();?>access/deleteAttachment"); 
			});  
			  
		}
		 
	}); //end ajax
	 
} 

function changeCallOutcomes(url, result, default_result, container, display){ 
 	 
	$.ajax({ 
		data: "rand="+Math.random()+"&result="+result,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		beforeSend:function(){   
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />'); 
			 if(result == "")
			  {	
			  	  container.html('<option value=""></option>');
			  	  container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }
		},
		success:function(newdata){      
		 	container.html('<option value=""></option>');
		  	if(newdata.length > 0)
			 {  
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {   
					selected = (default_result == value.outcome_id)?'selected="selected"':'';
					new_string = '<option value="'+value.outcome_id+'" '+selected+'>'+value.outcome_name+'</option>';  
					container.append(new_string);
				});
				
			 }
		    else
			 {  
				 container.attr('disabled', true); 
			 } 
			 container.select2({placeholder: "Select"});
			 //if(display==1)getActivities(); 
			 
		}
			
	}); //end ajax
}

</script>

<script>  
$(function() {  
	//$.fn.modal.Constructor.prototype.enforceFocus = function() {}; 
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate").validate({
		 submitHandler: function(form) { 
		 	manageActivity();//check duplicate username 
		},
		invalidHandler: function(event, validator) { 
			createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
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
			act_casinoproduct: {
				required: true 
			}, 
			act_issuecategory: {
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
			act_casinoproduct: {
				required: "Select casino product" 
			},
			act_issuecategory: {
				required: "Select issue category" 
			}, 
			act_product: {
				required: "Select product" 
			},
			act_category: {
				required: "Select category" 
			}, 
			act_promotion: {
				required: "Select promotion" 
			},   
			act_status: {
				required: "Select status" 
			}, 
			act_remarks: {
				required: "Please provide remarks" 
			}, 
			act_currentbalance: {
				required: "Enter current balance" 
			},
			act_depositamount: {
				required: "Enter deposit amount" 
			},
			act_bonusamountx: {
				required: "Enter bonus amount" 
			},
			act_wageringamount: {
				required: "Enter wagering amount" 
			}
			
		}
	}); 
	  
	//------------- Form validation -------------//
	  
	
	//changePromotions("<?=base_url()?>casino/getPromotionsList", '<?=$activity->Product?>', '<?=$activity->Currency?>', '<?=$activity->Promotion?>', $("select[name=act_promotion]")); 
	 
	 
	$("#act_issuecategory").change(function(){    
		$("#hidden_aissuecategory").val($(this).find(":selected").text()); 
	}); 
	$("#act_category").trigger("change");
	 
	$("#act_currency").change(function(){     
		$("#hidden_acurrency").val($(this).find(":selected").text());  
	}); 
	$("#act_currency").trigger("change");   
	
	$("#act_casinoproduct").change(function(){    
		 $("#hidden_acasinoproduct").val($(this).find(":selected").text());   
	});
	//$("#act_product").trigger("change");   
	
	$("#act_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	}); 
	$("#act_status").trigger("change");
	
	$("#act_source").change(function(){ 
		$("#hidden_asource").val($(this).find(":selected").text());
	});
	$("#act_source").trigger("change"); 
	 
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
		window.location.href = "<?=base_url()?>casino/activities"
	});  
	
	  
	$(".txt-currency").keydown(function(event) { 
	 
        // Allow: backspace, delete, tab, escape, and enter  - period 
		// (event.keyCode == 65 && event.ctrlKey === true) 
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||  event.keyCode == 110 ||  event.keyCode == 190 || 
             // Allow: Ctrl+A
            (event.ctrlKey === true) ||  
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything 
				   
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
	
	 
	//FOR CRM
	$("#act_callresult").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		$("#hidden_acallresult").val($(this).find(":selected").text());
		changeCallOutcomes("<?=base_url()?>promotions/getCallOutcomeList", $(this).val(), "<?=$activity->CallOutcomeID;?>", $("#act_calloutcome")); 
	}); 
	$("#act_callresult").trigger("change");
	
	$("#act_calloutcome").change(function(){  
		var selected = $(this).find('option:selected'); 
		$("#hidden_acalloutcome").val(selected.text());  
		//$("#act_callresult").val(selected.attr("result-id"));  
		//$("#hidden_acallresult").val(selected.attr("result-name"));  
		
		if($(this).val()!="")$(this).removeClass("error_input");   
	});  
	$("#act_calloutcome").trigger("change"); 
	//end outcome change 
	
	$(".wstep").click(function(){ 
		$(".select2-drop, .select2-drop-mask").hide();  
		var target = $(this).attr("target");
		$(".tab2-content").addClass("hide"); 
		$(".wstep").removeClass("done");
		$("#"+target).removeClass("hide");
		$(this).addClass("done");
	}); 
	
	$('.datepicker').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii"
		pickTime: true
	});
	
	$(".uncheck_callproblem").click(function(){   
		$('input:radio[name=act_callproblem]:checked').prop("checked", false); 
		$.uniform.update("input:radio[name=act_callproblem]");  
		//$.uniform.update("input[type=checkbox]"); 
	}); 
	 
	 
	 
	$("#BtnSubmitForm").click(function(){ 
		//add rules to validate
		if($('#act_calloutcome').val()==="" && $('#act_callresult').val()==="" && $('#act_callstart').val()==="" && $('#act_callend').val()==="" && $('input:radio[name=act_callproblem]:checked').length <=0 && $('input:checkbox[name=act_callsendsms]:checked').length <= 0 && $('input:checkbox[name=act_callsendemail]:checked').length <= 0 )
		 {   
			$("select.call-fieldimportant, input.call-fieldimportant").each(function (e) { 
				$(this).rules('add', {
					required: false
				});
			});	
		 }
		else
		 {  
			$("select.call-fieldimportant, input.call-fieldimportant").each(function (e) {  
				$(this).rules('add', {
					required: true
				}); 
			});	 
		 }
		   
	}); 
	
	
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
	
	$('#validate select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
	
	/*$('.modal').click(function(){ 
		setTimeout(function(){  
			//$('.select2-drop').remove(); 
			$("select").select2("close");
			$('#tsearch').focus(); 	 
			$('.select2-container').remove("select2-drop-active");
		}, 1); 
		//e.stopPropagation(); 
	});  */
	 
	/*$(".select2-container").click(function(e){    
		e.stopPropagation();   	  
		$("select").not(this).select2("close");
	}); */
	
	
	
	 
}); 
 

</script> 