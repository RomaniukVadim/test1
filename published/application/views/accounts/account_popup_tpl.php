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
    
    <div class="row-fluid"  >   
    
        <div class="span8" >
            
            <div class="control-group" >  
                <div class="span6" > 
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
                
                <div class="span6" >  
                	<?php
					$def_currency = ($activity->Currency)?$activity->Currency:$default_user->Currency; 
					?> 
                    
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
                
            </div>
            <!-- End .control-group --> 
                 
            <div class="control-group" >   
             
                <div class="span6" >
                    <label class="control-label" for="act_username">* Username</label>
                    <div class="controls controls-row"  >
                        <input type="text" id="act_username" name="act_username" class="required span12 tip" value="<?=($activity->Username)?htmlentities(stripslashes($activity->Username)):$default_user->Username;?>" maxlength="100" title="Enter username" <?php if(count($acvitiy)<=0 && $default_user->UserID){?>readonly="readonly" <?php }?> > 
                    </div>
                </div> 
                
                <div class="span6" > 
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
                
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" >  
            
                <div class="span6" >
                    <label class="control-label" for="act_esupportid">E-Support Ticket ID</label>
                    <div class="controls controls-row"  >
                        <input type="text" id="act_esupportid" name="act_esupportid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->ESupportID));?>" maxlength="30" title="Enter E-Support Ticket ID" > 
                    </div>
                </div> 
                  
                <div class="span6" >
                	<label class="control-label" for="act_accountproblem">* Account Problem</label>
                    <div class="controls controls-row">  
                        <select name="act_accountproblem" id="act_accountproblem" class="required select2 span12" > 
                            <optgroup label="Select Source"> 
                                <option value="" <?php if($activity->AccountProblem=="") echo "selected='selected'";?> ></option>
                                <?php
                                foreach($problems as $row => $problem){ 
                                    ?>
                                <option  value="<?=$problem->ProblemID;?>" <?php if($problem->ProblemID == $activity->AccountProblem) echo "selected='selected'";?> is-regularize="<?=$problem->IsRegularizeAmount;?>" ><?=$problem->ProblemName;?></option>	 		
                                    <?php 
                                    }
                                ?> 
                            </optgroup>  
                            
                        </select> 
                        <input type="hidden" name="hidden_aaccountproblem" id="hidden_aaccountproblem" value="" />
                    </div>    
                </div>  
                
            </div>
            <!-- End .control-group -->
             
            <div class="control-group" >  
            	
                <div class="span6" >
                	<label class="control-label" for="act_emailsent">* Email Sent?</label> 
                    <div class="controls controls-row">  
                        <label class="radio-inline" >
                            <input type="radio" name="act_emailsent" value="1"  <?=($activity->EmailSent=='1')?'checked="checked"':"";?> > Yes
                        </label> 
                        
                        <label class="radio-inline">
                            <input type="radio" name="act_emailsent" value="0" <?=($activity->EmailSent==0 || $activity->EmailSent=='')?'checked="checked"':"";?> > No
                        </label>
                    </div>    
                </div>  
                
                <div class="span6" >
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
    		 
            <div class="control-group" id="IfIdReceived" >  
            
                <div class="span6" >
                    <label class="control-label tip" for="act_idtype" <?=($activity->IDType > 0)?"title='Last value {$activity->IDTypeName}'":""?> >* ID Type</label>
                    <div class="controls controls-row">  
                        <select name="act_idtype" id="act_idtype" class="select2 span12 id_fields" > 
                            <optgroup label="Select Source"> 
                                <option value="" <?php if($activity->IDType=="") echo "selected='selected'";?> ></option>
                                <?php
                                foreach($id_types as $row => $id){ 
                                    ?>
                                <option  value="<?=$id->TypeID;?>" <?php if($id->TypeID == $activity->IDType) echo "selected='selected'";?> ><?=$id->Type;?></option>	 		
                                    <?php 
                                    }
                                ?> 
                            </optgroup>   
                        </select> 
                        <input type="hidden" name="hidden_aidtype"  id="hidden_aidtype" value="" class="hidden_aidfields" />
                    </div> 
                </div> 
                  
                <div class="span6" >
                	<label class="control-label tip" for="act_issuedcountry"  <?=($activity->IssuedCountry > 0)?"title='Last value {$activity->CountryName}'":""?> >* Issued Country</label>
                    <div class="controls controls-row">  
                        <select name="act_issuedcountry" id="act_issuedcountry" class="select2 span12 id_fields" > 
                            <optgroup label="Select Source"> 
                                <option value="" <?php if($activity->IssuedCountry=="") echo "selected='selected'";?> ></option>
                                <?php
                                foreach($countries as $row => $country){ 
                                    ?>
                                <option  value="<?=$country->CountryID;?>" <?php if($country->CountryID == $activity->IssuedCountry) echo "selected='selected'";?> ><?=$country->CountryName;?></option>	 		
                                    <?php 
                                    }
                                ?>
                                 
                            </optgroup>  
                            
                        </select> 
                        <input type="hidden" name="hidden_aissuedcountry" id="hidden_aissuedcountry" value="" class="hidden_aidfields" />
                    </div>    
                </div>  
                
            </div>
            <!-- End .control-group --> 
            
            
            <div class="control-group" >  
            	 
                <label class="control-label" for="act_spacex"></label>
                <div class="controls controls-row" style="padding-bottom: 10px !important; ">  
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
                        <input type="checkbox" value="1" name="act_isuploadpm" id="act_isuploadpm" style="margin-bottom: 5px; margin-left: 30px; " <?=($activity->IsUploadPM==1)?"checked='checked'":"";?> /> Updated No.
                    </label>
                    
                    <label class="radio-inline act-danger tip" title="To follow up" > 
                        <input type="checkbox" value="1" name="act_tofollowup" id="act_tofollowup" style="margin-bottom: 5px; margin-left: 30px; " <?=($activity->ToFollowup==1)?"checked='checked'":"";?> /> To Follow Up
                    </label>
                </div>
             
                 
            </div>
            <!-- End .control-group --> 
            
            
            <div class="control-group" >  
            	
                <div class="span6" >
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
                    
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" > 
                <label class="control-label" for="act_remarks">* Remarks</label>
                <div class="controls controls-row" >  
                    <textarea id="act_remarks" name="act_remarks" class="required span12" rows="3" maxlength="500" placeholder="<?=htmlentities(stripslashes($activity->Remarks));?>" ></textarea>
                </div>
            </div>
            <!-- End .control-group -->
             
        </div>      
        
        <div class="span4 right-panel" >
        	
            <div class="control-group limit-8 limits-txt hide" >    
                <label class="control-label" for="act_blpbet">Current Bet Limit Per Bet</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_blpbet" name="act_blpbet" class="span12 tip txt-currency" value="<?=$activity->BetLimitPerBet;?>" maxlength="30" title="Bet Limit Per Bet" default-value="<?=$activity->BetLimitPerBet;?>" > 
                </div> 
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group limit-8 limits-txt hide" >    
                <label class="control-label" for="act_blpmatch">Current Bet Limit Per Match</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_blpmatch" name="act_blpmatch" class="span12 tip txt-currency" value="<?=$activity->BetLimitPerMatch?>" maxlength="30" title="Bet Limit Per Match" default-value="<?=$activity->BetLimitPerMatch?>" > 
                </div> 
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group limit-8 limits-txt hide" >    
                <label class="control-label" for="act_plpbet">Proposed Limit Per Bet</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_plpbet" name="act_plpbet" class="span12 tip txt-currency" value="<?=$activity->ProposedLimitPerBet?>" maxlength="30" title="Proposed Limit Per Bet" default-value="<?=$activity->ProposedLimitPerBet?>" > 
                </div> 
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group limit-8 limits-txt hide" >    
                <label class="control-label" for="act_plpmatch">Proposed Limit Per Match</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_plpmatch" name="act_plpmatch" class="span12 tip txt-currency" value="<?=$activity->ProposedLimitPerMatch?>" maxlength="30" title="Proposed Limit Per Match" default-value="<?=$activity->ProposedLimitPerMatch?>" > 
                </div> 
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group limit-8 limits-txt hide" >    
                <label class="control-label" for="act_rlpbet">Revised Limit Per Bet</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_rlpbet" name="act_rlpbet" class="span12 tip txt-currency" value="<?=$activity->RevisedLimitPerBet?>" maxlength="30" title="Revised Limit Per Bet" default-value="<?=$activity->RevisedLimitPerBet?>" > 
                </div> 
            </div>
            <!-- End .control-group -->
            
            <div class="control-group limit-8 limits-txt hide" >    
                <label class="control-label" for="act_rlpmatch">Revised Limit Per Match</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_rlpmatch" name="act_rlpmatch" class="span12 tip txt-currency" value="<?=$activity->RevisedLimitPerMatch?>" maxlength="30" title="Revised Limit Per Match" default-value="<?=$activity->RevisedLimitPerMatch?>" > 
                </div> 
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group limit-24 limits-txt hide" id="RegularizeAmount" >    
                <label class="control-label" for="act_regularizeamount">* Regularize Amount</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_regularizeamount" name="act_regularizeamount" class="span12 tip txt-currency txt-important" value="<?=$activity->RegularizeAmount?>" maxlength="20" title="Regularize Amount" default-value="<?=$activity->RegularizeAmount?>" > 
                </div> 
            </div>
            <!-- End .control-group -->  
            
            <div class="control-group limit-24 limits-txt hide" id="CurrentBalance" >    
                <label class="control-label" for="act_currentbalance">* Current Balance</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_currentbalance" name="act_currentbalance" class="span12 tip txt-currency allow-negative txt-important" value="<?=$activity->CurrentBalance?>" maxlength="20" title="Current Balance" default-value="<?=$activity->CurrentBalance?>" > 
                </div> 
            </div>
            <!-- End .control-group -->  
            
            <!-- REQUEST HIGHER DEPOSIT -->
            <div class="control-group limit-9 limit-10 limits-txt hide" >    
                <label class="control-label" for="act_currentlimit">Current <span class="limit-9-dynamic" >Deposit</span> Limit</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_currentlimit" name="act_currentlimit" class="span12 tip txt-currency" value="<?=$activity->CurrentLimit?>" maxlength="30" title="Current Limit" default-value="<?=$activity->CurrentLimit?>" > 
                </div> 
            </div>
            
            <div class="control-group limit-9 limit-10 limits-txt hide" >    
                <label class="control-label" for="act_proposedlimit">Proposed <span class="limit-9-dynamic" >Deposit</span> Limit</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_proposedlimit" name="act_proposedlimit" class="span12 tip txt-currency" value="<?=$activity->ProposedLimit?>" maxlength="30" title="Proposed Limit" default-value="<?=$activity->ProposedLimit?>" > 
                </div> 
            </div>
            
            <div class="control-group limit-9 limit-10 limits-txt hide" >    
                <label class="control-label" for="act_revisedlimit">Revised <span class="limit-9-dynamic" >Deposit</span> Limit</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_revisedlimit" name="act_revisedlimit" class="span12 tip txt-currency" value="<?=$activity->RevisedLimit?>" maxlength="30" title="Revised Limit" default-value="<?=$activity->RevisedLimit?>" > 
                </div> 
            </div> 
            <!-- End .control-group -->  
            <!-- END REQUEST HIGHER DEPOSIT --> 
            
            <!-- TGP ISSUES -->
            <div class="control-group limit-32 limits-txt hide" >    
                <label class="control-label" for="act_category">* Category</label>
                <div class="controls controls-row"  >
                
                	<select name="act_category" id="act_category" class="select2 span12 txt-important" > 
                        <optgroup label="Select Currency"> 
                            <option value="" <?php if($activity->ProblemCategory=="") echo "selected='selected'";?> ></option>   
                        </optgroup>  
                        
                    </select> 
                    <input type="hidden" name="hidden_acategory" id="hidden_acategory" value="" />  
                </div> 
            </div> 
            
            <div class="control-group limit-32 limits-txt hide" >
                <label class="control-label" for="act_withdocuments">* With Documents?</label> 
                <div class="controls controls-row">  
                    <label class="radio-inline" >
                        <input type="radio" name="act_withdocuments" value="1"  <?=($activity->WithDocuments=='1')?'checked="checked"':"";?> > Yes
                    </label> 
                    
                    <label class="radio-inline">
                        <input type="radio" name="act_withdocuments" value="0" <?=($activity->WithDocuments=='0' || $activity->WithDocuments=='')?'checked="checked"':"";?> > No
                    </label>
                </div>    
            </div>
            
            <div class="control-group limit-32 limits-txt hide" >    
                <label class="control-label" for="act_tgpamount">Amount</label>
                <div class="controls controls-row"  >
                    <input type="text" id="act_tgpamount" name="act_tgpamount" class="span12 tip txt-currency" value="<?=$activity->TGPAmount?>" maxlength="20" title="Amount" default-value="<?=$activity->TGPAmount?>" > 
                </div> 
            </div>
            <!-- End .control-group -->  
            
             
        </div> 
    
    </div> 
    
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
		url: "<?=base_url();?>accounts/manageActivity", 
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
						 displayUploaded(msg.uploaded_id, "account_issues");
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
		url: "<?=base_url();?>accounts/displayUploaded", 
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
				if(attach_id)window.location.href = "<?=base_url()?>accounts/downloadAttachment/"+last_id+"/"+activity_type+"/"+attach_id;   
			});
			
			$(".uploaded-item .remove-uploaded").click(function(){
				var attach_id = $(this).closest(".uploaded-item").attr("attach-id");
				if(attach_id)deleteAttachment(attach_id,"<?=base_url();?>accounts/deleteAttachment"); 
			});  
			  
		}
		 
	}); //end ajax
	 
}
	 
function changeAccountProblemCategory(url, default_result, container, display){ 
 	 
	$.ajax({  
		data: $("#validate").serialize()+"&rand="+Math.random(),//+"&result="+result,//$(this).serialize(),
		type:"POST",  
		dataType: "JSON",
		url: url,  
		beforeSend:function(){   
			 //$("#CountProcessActivityLoader .header_loader").remove();
			 //$("#CountProcessActivityLoader").append('<img src="<?=base_url()?>media/images/loader.gif" class="header_loader"  />'); 
			 if($("#act_problem").val() == "")
			  {	
			  	  container.html('<option value=""></option>');
			  	  container.attr('disabled', true); 
				  container.select2({placeholder: "Select"});
				  return false; 
			  }
		},
		success:function(newdata){      
		 	container.html('<option value="">&nbsp;</option>');
		  	if(newdata.length > 0)
			 {  
				container.removeAttr("disabled");
				$.each(newdata, function( index, value ) {   
					selected = (default_result == value.CategoryID)?'selected="selected"':'';
					new_string = '<option value="'+value.CategoryID+'" '+selected+'>'+value.Name+'</option>';  
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
		invalidHandler: function(event, validator) {
			//alert(JSON.stringify(validator));  
			//createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
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
			act_accountproblem: {
				required: true 
			},   
			act_status: {
				required: true 
			},
			act_remarks: {
				required: true 
			}, 
			act_blpbet: {
				number: true 
			}, 
			act_blpmatch: {
				number: true 
			}, 
			act_plpbet: {
				number: true 
			}, 
			act_plpmatch: {
				number: true 
			}, 
			act_rlpbet: {
				number: true 
			}, 
			act_rlpmatch: {
				number: true 
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
			act_accountproblem: {
				required: "Select account problem" 
			},  
			act_status: {
				required: "Select status" 
			}, 
			act_remarks: {
				required: "Please provide remarks" 
			}, 
		    act_blpbet: {
				number: "Please enter a valid amount" 
			}, 
			act_blpmatch: {
				number: "Please enter a valid amount" 
			}, 
			act_plpbet: {
				number: "Please enter a valid amount" 
			}, 
			act_plpmatch: {
				number: "Please enter a valid amount" 
			}, 
			act_rlpbet: {
				number: "Please enter a valid amount" 
			}, 
			act_rlpmatch: {
				number: "Please enter a valid amount" 
			},	
			act_regularizeamount: {
				required: "Enter regularize amount"
			}, 
			act_currentbalance: {
				required: "Enter current balance"
			}, 
			act_idtype: {
				required: "Select ID type"	
			},
			act_issuedcountry: {
				required: "Select issued country"	
			}, 
			act_category: {
				required: "Select category"	
			}
			
		}
	}); 
	
	$('#datepicker1').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii"
		pickTime: false
	}); 
	 
	  
	//------------- Form validation -------------//
	  
	$("#act_accountproblem").change(function(){    
		$("#hidden_aaccountproblem").val($(this).find(":selected").text()); 
  		 
		var actproblem = $(this).val(); 
		var dyna_txt = "";
	    
		$(".limits-txt").hide();  
		$(".limit-"+actproblem).show(); 
		
		$(".limits-txt input.txt-important, .limits-txt select.txt-important").each(function() {   
			$(this).rules('add', {
				required: false
			});
		});
		
		if(actproblem == 24)
		 {  
			$(".limit-"+actproblem+" .txt-important").each(function() {  
				$(this).rules("add", {
					required: true
				});
			 });
			 
			 $(".limit-"+actproblem+" .txt-currency").each(function() { 
				 $(this).val($(this).attr('default-value'));  
			 });  
		 }
		else if(actproblem == 32)
		 {
			changeAccountProblemCategory("<?=base_url()?>accounts/getAccountProblemCategory", "<?=$activity->ProblemCategory;?>", $("#act_category"));
			$(".limit-"+actproblem+" select.txt-important").each(function() {  
				$(this).rules("add", {
					required: true
				});
			 });
			
		 } 
		else
		 {
			 
			dyna_txt = (actproblem == 10)?"Withdrawal":"Deposit";
			$(".limit-" + actproblem).find(".limit-9-dynamic").html(dyna_txt); 
			
			/*$(".limit-"+actproblem+" .txt-important").each(function() { 
				$(this).rules('add', {
					required: false
				});
			});*/  
			
			$(".limit-"+actproblem+" .txt-currency").each(function() { 
				 $(this).val($(this).attr('default-value'));  
			}); 
			  
			//$(".limit-" + actproblem).find(".txt-currency").val($(".limit-" + actproblem).find(".txt-currency").attr('default-value'));  
		 }
		
		$(".select2-drop, .select2-drop-mask").hide();   
		 
		/*
		if ($(this).find(":selected").attr('is-regularize') == 1) {
			$("#RegularizeAmount").show();  
			$("#act_regularizeamount").rules('add', {
				required: true
			}); 
			$("#act_regularizeamount").val('<?=$activity->RegularizeAmount?>'); 
		}
		else
		{ 
			$("#RegularizeAmount").hide(); 
			$("#act_regularizeamount").rules('add', {
				required: false
			}); 
			$("#act_regularizeamount").val('');
		}*/
		
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
	
	$("#act_idtype").change(function(){    
		$("#hidden_aidtype").val($(this).find(":selected").text()); 
	}); 
	$("#act_idtype").trigger("change");
	
	$("#act_issuedcountry").change(function(){    
		$("#hidden_aissuedcountry").val($(this).find(":selected").text()); 
	}); 
	$("#act_issuedcountry").trigger("change");
	
	 
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
		window.location.href = "<?=base_url()?>accounts/activities"
	}); 
	
	$(".txt-currency").keydown(function(event) {   
	  
        // Allow: backspace, delete, tab, escape, and enter  - period 
		// (event.keyCode == 65 && event.ctrlKey === true) 
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||  event.keyCode == 110 ||  event.keyCode == 190 || 
             // Allow: Ctrl+A
            (event.ctrlKey === true) ||  
             // Allow: home, end, left, right,
            (event.keyCode >= 35 && event.keyCode <= 39) || ( (event.keyCode == 109 || event.keyCode == 173) && $(this).hasClass("allow-negative") == true) ) {
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
	 
	$("input:radio[name='act_idreceived']").click(function(){ 
		var is_received = $(this).val();     
		if(is_received == 1 || is_received == '1')
		 {  
			 $("#IfIdReceived").show();    
			 $('select.id_fields').each(function() { 
				$(this).rules('add', {
					required: true
				});
			 });
				 
		 }
		else
		 { 
			 $("#IfIdReceived").hide();  
			 $('select.id_fields').each(function () { 
				$(this).rules('add', {
					required: false
				});
			 });
			clearSelectbox($("#IfIdReceived div.controls"));  
			$("#IfIdReceived ul.select2-choices li.select2-search-choice").remove(); 
			$('.id_fields option').prop("selected", false); 
			$("#IfIdReceived").find(".hidden_aidfields").val("");
		 }
	}); 
	$("input[name=act_idreceived][value=<?=($activity->IdReceived)?$activity->IdReceived:0?>]").trigger("click"); 
	
	
	//FOR TGP ISSUES
	$("#act_category").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		$("#hidden_acategory").val($(this).find(":selected").text());  
	}); 
	$("#act_category").trigger("change");
	
	
	$('#validate select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
}); 
 

</script> 