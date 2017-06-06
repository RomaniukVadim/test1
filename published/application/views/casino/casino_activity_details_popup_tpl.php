<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
  

<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}
 
.inline-display {
	margin-right: 15px; 	
}

a.inline-display {
	cursor: pointer; 	
} 

.modal .control-group {
    margin-bottom: 5px !important; 
}

.modal .control-group label {
    margin-bottom: 5px !important; 
} 

#TableList .col1 {
	
}

#ViewedList .tooltip {
	width: auto !important; 	
} 

/*.form-horizontal .right-panel .control-label { 
    width: 190px;
}

.form-horizontal .right-panel .controls {
    margin-left: 200px;
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

 
</style>

<div class="row-fluid form-widget-content" id="ActivityDetails" >   
 
<!-- form -->
<form id="validate_status" name="validate_status" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
  	
    <?php /*?><div class="wizard-steps show">
        <div class="wstep current details-tab" data-step-num="0" target="CsaContentDetails" >
            <div class="donut">
            	<i class="icon24 i-user"></i>
            </div>
        	<span class="txt">CSA</span>
        </div>
         
        <div class="wstep <?=($activity->CallStart !="0000-00-00 00:00:00" || $activity->CallEnd !="0000-00-00 00:00:00")?"current details-tab":""?>" data-step-num="1" target="CrmContentDetails" >
            <div class="donut">
            	<i class="icon24 i-phone-2"></i>
            </div>
            <span class="txt">CRM</span>
        </div> 
    </div><?php */?>
    
    <!-- CSA Form -->
    <div id="CsaContentDetails" class="tab2-content"  > 
    	
        
		<div class="control-group" >  
			<div class="span4" > 
            	<?php    
				if($view_only != 1)
				 { 
				 
				?>
				<label class="control-label" for="act_assignee">* Assignee</label> 
				<div class="controls controls-row"   >
					<select name="act_assignee" id="act_assignee" class="required select2 myselect" > 
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
                <?php
				 }
				else
				 {
				?>
                <label class="control-label" for="act_assigneex">Assignee</label> 
				<div class="controls controls-row"   >
					<label class="info-label highlight-detail" for="act_assigneex" ><?=ucwords($activity->GroupAssigneeName)?></label>
				</div> 
                <?php	 
				 }
				?>
			</div>  
            
             <div class="span4" > 
                <label class="control-label" for="act_updatedx">Date Updated</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_updatedx" ><?=date("F d, Y", $activity->DateUpdatedInt)?></label>
                </div>
            </div> 
            
            <div class="span4" >
                <label class="control-label" for="act_updatedbyx">Updated By</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_updatedbyx" ><?=strtolower($activity->UserUpdated)?></label>
                </div>
            </div>
			 
		</div>
		<!-- End .control-group -->
		 
		
        <div class="control-group" >  
        	
            <div class="span4" >  
			<?php
			if($view_only != 1)
			 {
			?>
                <label class="control-label" for="act_status">* Status</label>
                <div class="controls controls-row">  
                    <select name="act_status" id="act_status" class="required myselect" >  
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
             
            <?php	 
			 }
			else
			 {
			?>
                <label class="control-label" for="act_statusx">Status</label> 
                <div class="controls controls-row"   >
                    <label class="info-label highlight-detail" for="act_statusx" ><?=$activity->StatusName?></label>
                </div> 
            <?php	 
			 }
			?>
            </div>
            
            <div class="span4" > 
                <label class="control-label" for="act_currencyx">Currency</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_currencyx" ><?=strtoupper($activity->CurrencyName)?></label>
                </div>
            </div> 
            
            <div class="span4" >
                <label class="control-label" for="act_usernamex">Username</label> 
                <div class="controls controls-row"   >
                    <label class="info-label highlight-detail" for="act_usernamex" >
                        <span <?=($activity->Important=='1')?" class=\"act-danger tip\" title=\"important\" ":"";?> ><?=strtolower($activity->Username)?></span>
                        <?=($activity->IsComplaint=='1')?'<i class="icon16 i-warning act-danger tip" title="complaint" ></i>':''?>
                    </label>
                </div>   
            </div>
            
        </div>
        <!-- End .control-group -->
        
        <div class="control-group" >  
               
            <div class="span4" >
            	<label class="control-label" for="act_esupportidx">E-Support ID</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_esupportidx" ><?=($activity->ESupportID)?$activity->ESupportID:"_ _ _ _ _ _ _ _ _ _"?></label>
                </div> 
            </div>
            
            <div class="span4" >
            	<label class="control-label" for="act_sourcex">Source</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_sourcex" ><?=$activity->ActivitySource?></label>
                </div> 
            </div> 
            
            <div class="span4" > 
                <label class="control-label" for="act_casinoproductx">Casino Product</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_casinoproductx" ><?=$activity->SubProductName?></label>
                </div>
            </div>
        </div>
        <!-- End .control-group -->
        
        <div class="control-group" >  
            
            <div class="span4" >
                <label class="control-label" for="act_issuecategoryx">Issue Category</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_issuecategoryx" ><?=$activity->CategoryName?></label>
                </div> 
            </div>   
            
            <div class="span4" > 
                <label class="control-label" for="act_trasactionidx">Transaction ID</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_trasactionidx" ><?=($activity->TransactionID)?$activity->TransactionID:"_ _ _ _ _ _ _ _ _ _"?></label>
                </div>
            </div>  
            
            <div class="span4" >
            	<label class="control-label" for="act_playtechticketidx">Playtech Ticket ID</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_playtechticketidx" ><?=($activity->PlaytechTicketID)?$activity->PlaytechTicketID:"_ _ _ _ _ _ _ _ _ _"?></label>
                </div> 
            </div>
            
        </div>
        <!-- End .control-group -->  
        
        <div class="control-group" >   
            
            <div class="span4" >
            	<label class="control-label" for="act_daterequestedx">Date Requested</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_daterequestedx" >
                    <?=($activity->DateRequested != "0000-00-00 00:00:00")?date("F d, Y", strtotime($activity->DateRequested))." &nbsp;<span class='green' >".date("H:i:s", strtotime($activity->DateRequested))."</span>":"_ _ _ _ _ _ _ _ _ _"?> 
                    </label> 
                    
                </div> 
            </div> 
            
            <div class="span4" > 
                <label class="control-label" for="act_datesolvedx">Date Solved</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_datesolvedx" >
                    <?=($activity->DateSolved != "0000-00-00 00:00:00")?date("F d, Y", strtotime($activity->DateSolved))." &nbsp;<span class='green' >".date("H:i:s", strtotime($activity->DateSolved))."</span>":"_ _ _ _ _ _ _ _ _ _"?> 
                    </label>
                </div>
            </div> 
            
            <div class="span4" >
                <label class="control-label" for="act_tofollowupx">To Follow Up</label> 
                <div class="controls controls-row"   >
                    <label class="info-label highlight-detail" for="act_tofollowupx" ><?=($activity->ToFollowup=='1')?"YES":"NO"?></label>
                </div>
            </div>
            
        </div>
        <!-- End .control-group --> 
        
        <div class="control-group" >  
            
            <div class="span4" >
                <label class="control-label" for="act_amountx">Amount</label> 
                <div class="controls controls-row"   >
                    <label class="info-label highlight-detail" for="act_amountx" ><?=$activity->Amount?></label>
                </div> 
            </div>   
            
            <div class="span4" > 
                <label class="control-label" for="act_viplevelx">VIP Level</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_viplevelx" ><?=($activity->VIPLevel > 0)?"<span class='highlight-detail'>".$activity->VIPLevel."</span>":"_ _ _ _ _ _ _ _ _ _"?></label>
                </div>
            </div>   
            
             <div class="span4" >
                <label class="control-label" for="act_isuploadpmx">Updated No.</label> 
                <div class="controls controls-row"   >
                    <label class="info-label highlight-detail" for="act_isuploadpmx" ><?=($activity->IsUploadPM=='1')?"YES":"NO"?></label>
                </div>
            </div>
            
        </div>
        <!-- End .control-group -->  
        
        <?php
        if($activity->AttachFilename && (file_exists("./media/uploads/".$activity->AttachFilename)) || count($attachments) > 0)
         {
        ?>
        <div class="control-group" >  
            <div class="span12" > 
                <label class="control-label" for="act_attachment">Attachment</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_attachment" > 
                    <?php
                    foreach($attachments as $row=>$attach){
                    ?>
                    <a class="uploaded-filename inline-display tip" attach-id="<?=$attach->AttachID?>" title="download <?=$attach->ClientFilename?>" ><?=$attach->ClientFilename;?></a>
                    <?php	
                    }//end foreach
                    ?>
                    
                    <?php
                    if(count($attachments) > 1)
                     {
                    ?>
                    <a class="uploaded-filename inline-display tip green" attach-id="all" title="download all attachments" >Download All</a>
                    <?php	 
                     }
                    ?>
                    </label> 
                </div>
            </div>  
        </div>
        <!-- End .control-group -->   
        <?php	 
         }
        ?>
        
        <?php
        if($view_only != 1)
         {
        ?> 
        
        <div class="control-group" > 
        	<div class="span8" >
                <label class="control-label" for="act_remarks">* Remarks</label>
                <div class="controls controls-row" >  
                    <textarea id="act_remarks" name="act_remarks" class="required span12" rows="3" maxlength="500" placeholder="<?=htmlentities(stripslashes($activity->Remarks));?>" ></textarea>
                </div> 
            </div>
            
            <div class="span4" >
            	<button type="submit" class="btn btn-primary" id="BtnSubmitForm" style="margin-top: 50px; " >Update Status</button>
            </div>
        </div>
        <!-- End .control-group -->  
         
        <?php
         }
        else
         {
        ?>	    
        <div class="control-group" >  
          
            <label class="control-label" for="act_remarksx">Last Remarks</label> 
            <div class="controls controls-row"   >
                <label class="info-label" for="act_remarksx" ><?=($activity->Remarks)?$activity->Remarks:"_ _ _ _ _ _ _ _ _ _"?></label>
            </div>
     
        </div>
        <!-- End .control-group -->  
        <?php
         }
        ?>
    
    </div>
    <!-- end CSA content -->
    
    <!-- CSA Form -->
    <div id="CrmContentDetails" class="tab2-content hide"  >
    
    	<div class="control-group" >  
            <div class="span4" > 
                <label class="control-label" for="act_callstartedx">Call Started</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_callstartedx" >
                        <?=date("F d, Y", strtotime($activity->CallStart))?>
                        &nbsp;
                        <span class="green">
                            <?=date("H:i:s", strtotime($activity->CallStart))?>
                        </span>
                    </label> 
                </div>
            </div> 
            
            <div class="span4" >
                <label class="control-label" for="act_usernamex">Call Ended</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_usernamex" > 
						<?=date("F d, Y", strtotime($activity->CallEnd))?> 
                        &nbsp;
                        <span class="act-danger">
                            <?=date("H:i:s", strtotime($activity->CallEnd))?>
                        </span>
                    </label>
                </div>   
            </div>  
            
            <div class="span4" >
                <label class="control-label" for="act_durationx">Duration</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_durationx" ><?=$activity->CallDuration;?></label>
                </div>   
            </div>
        </div>
        <!-- End .control-group -->
        
        <div class="control-group" >   
        	<div class="span4" >
                <label class="control-label" for="act_resultx">Result</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_resultx" ><?=$activity->ResultName?></label>
                </div>   
            </div> 
            
            <div class="span4" > 
                <label class="control-label" for="act_outcomex">Outcome</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_outcomex" ><?=$activity->OutcomeName?></label> 
                </div>
            </div>  
        </div>
        <!-- End .control-group -->
        
        <div class="control-group" >  
            <div class="span4" > 
                <label class="control-label" for="act_sendemailx">Send SMS</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_sendemailx" ><?=($activity->CallSendSMS == '1')?"YES":"NO";?></label> 
                </div>
            </div> 
            
            <div class="span4" >
                <label class="control-label" for="act_sendemailx">Send Email</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_sendemailx" ><?=($activity->CallSendEmail == '1')?"YES":"NO";?></label>
                </div>   
            </div>  
            
            <div class="span4" > 
              	<label class="control-label" for="act_callproblemx">Call Problem</label> 
                <div class="controls controls-row"   >
                    <label class="info-label" for="act_callproblemx" ><?=ucwords(str_replace("_"," ", $activity->CallProblem));?></label>
                </div> 
            </div>
        </div>
        <!-- End .control-group -->
        
    </div>
    <!-- end CRM -->
    	
    <div class="control-group" >  
        &nbsp;
    </div>
    <!-- End .control-group -->
    
    <!-- TABLE LIST -->
    <div id="TableList"  >
    
        <div > 
             <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                <thead>
                    <tr> 
                        <th class="center" width="15%" > Updated By </th>
                        <th class="center" width="15%" > Status </th>
                        <th class="center" > Remarks </th>
                        <th class="center" width="12%" > Assignee </th>
                        <th class="center" width="20%" > Date Updated </th>
                    </tr>
                </thead>
            </table>
        </div>
        
        <!-- scroll -->   
        <div class="modal_scroll" style="max-height: 250px; overflow:auto; margin-top: -1px; " >	  
         
            <div id="ScrollWrap"  style="display: block !important; position: relative !important; "  > 
                 <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                    <tbody id="ViewedList" >
					<?php
					if(count($histories) > 0)
					 {
						 $first_remarks = "";
						 $ctr = 0; 
						 foreach($histories as $row=>$history){
							 $s_string = "Activity changed to Important";	 
							 $s_string_complain = "Activity changed to Complain"; 
							 $is_important = ((strpos($history->Changes,$s_string) !== false) || ($history->Important==1 && $history->Changes==""))?1:0; 
							 $is_complaint = ((strpos($history->Changes,$s_string_complain) !== false) || ($history->IsComplaint==1 && $history->Changes==""));  
							 //$changes = trim($history->Changes,"|||"); 
							 //$changes = str_replace("|||", "<br>", $changes);
							 $changes = ""; 
							 $changes_list = "";
							 $changes_arr = explode("|||", trim($history->Changes,"|||"));  
							 $remarks = "";
							 foreach($changes_arr as $row=>$change){
								$changes .= ($row+1).'. '.$change.'<br>'; 
								if($change != "")$changes_list .= "<li>{$change}</li>";
							 } 
							 
							 if(trim($history->Remarks))
							  {
								  $remarks = $history->Remarks; 
							  }
							 elseif(trim($history->RMRemarks))
							  {
								  $remarks = $history->RMRemarks; 
							  }
							 elseif(trim($history->SRemarks))
							  {
								  $remarks = $history->SRemarks; 
							  }
							 elseif(trim($history->MRemarks))
							  {
								  $remarks = $history->MRemarks; 
							  }
							 else
							  {
								  $remarks = ""; 	  
							  } 
							  
							 if($ctr == (count($histories)-1))$before_remarks = trim($remarks);  
							  
							 if(end($histories) && ($activity->IsUpload=='1'))
							  {   
								  $arr_firstremarks = (strpos($history->Changes,'Remarks changed to') !== false)?explode("Remarks changed to", $history->Changes, 2):array();    
								  if(count($arr_firstremarks) > 0)
								   {   
									  $clean_string = trim(strstr($arr_firstremarks[1], "|||", true));  
									  //$arr_firstremarks_1 = preg_replace("/.* from (.*)$/", "$1", $clean_string, 1);  
									  //$arr_firstremarks_1 = preg_replace('/' . preg_quote($before_remarks." from ", '/') . '/', "", $clean_string, 1);    
									  $arr_firstremarks_1 = (strpos($clean_string, $before_remarks) !== false)?trim(str_replace($before_remarks, "", $clean_string)):"";     								   									  
									  $from_words = explode("from", $arr_firstremarks_1);   
									  
									  $first_remarks = trim(str_replace("|||", "", $from_words[1])); // can remove   
								   }  
							  }     
							 $ctr++; 
							  
					?>
                    <tr class="history_item">
                        <td class="center <?=($is_important == 1)?" act-danger ":""?>" width="15%" > 
                        	<i class='icon16 <?=($is_important == 1)?"i-flag-4":""?> tip' title="Activity changed to important" ></i> 
                            <i class="<?=($is_complaint)?"i-warning tip":""?> tip" title="Activity changed to complaint" ></i>  
                            <i class="<?=($history->MainUpdated)?"i-pencil":""?> tip" title="<?=htmlentities($changes)?>" data-html="true" ></i>  
							<?=strtolower($history->UpdatedUser)?> 
                        </td>
                        <td class="center" width="15%" ><?=$history->StatusName?></td>
                        <td >
                        	<div class="remarks-content widget-content" id="HistoryDetails<?=$history->HistoryID?>" > 
                            	<?php if($changes_list != ""){ ?><i class="icon12 tip i-plus pull-right show-changes hide btnimg green" title="View changes" ></i> <?php } ?>
								<?=nl2br($remarks)?>
                                <div class="history-details-list hide">
                                	<p><h4>Changes</h4></p>
                                    <small><ul class="changes-ul" ><?=$changes_list?></ul></small>
                                </div>
                            </div>
                        </td> 
                        <td class="center" width="12%" ><?=$history->GroupAssigneeName?></td>
                        <td class="center" width="20%" ><?=$history->DateUpdated?></td>
                    </tr>
                    <?php		 
						 }//end foreach
					 }
					else
					 {
					?>
                        <tr>
                            <td colspan="5" class="center" >No activity history found.</td>
                        </tr>
                    <?php	 
					 }
					?>
                    <!--<thead>
                        <tr> 
                            <th width="35%" class="center" >Nickname</th>
                            <th class="center" > Date Last Viewed</th>
                        </tr>
                    </thead>--> 
                        
                    </tbody>
                    
                </table>  
                  
            </div>  
        </div>
        <!-- END scroll -->
        
        <div style="margin-top: -1px;" > 
             <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; border-top: 0px !important; " >
                <tfoot>
                    <tr>
                        <th class="center" colspan="5" style="border-top: 0px !important;" >&nbsp;</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    </div>
    <!-- END TABLE LIST -->
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageActivityStatus = function() { 
	 
	$.ajax({ 
		data: $("#validate_status").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>casino/manageActivityStatus", 
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
				//$('#validate_status')[0].reset();
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

//------------- Custom scroll in widget box  -------------//
var verticalScroll = function() {
	$(".modal_scroll").niceScroll("#ScrollWrap",{
		cursoropacitymax: 0.8,
        cursorborderradius: 0,
        cursorwidth: "10px", 
		bouncescroll: false, 
		zindex: 999999, 
		autohidemode: true //true, cursor 
	});
}

$(function() {  
	 
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_status").validate({
		 submitHandler: function(form) { 
		 	manageActivityStatus();//check duplicate username 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			act_assignee: {
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
			act_status: {
				required: "Select status" 
			}, 
			act_remarks: {
				required: "Please provide remarks" 
			}  
			
		}
	}); 
	 
	 
	//download  
	$(".uploaded-filename").click(function(){
		var attach_id = $(this).attr("attach-id"); 
		if(attach_id)
		 {
			if(attach_id == "all")
			 {
				window.location.href = "<?=base_url()?>access/downloadAttachment/<?=$activity->ActivityID?>/"+activity_type;   
			 }
			else
			 {
				window.location.href = "<?=base_url()?>access/downloadAttachment/<?=$activity->ActivityID?>/"+activity_type+"/"+attach_id;    
			 }
		 } 
	}); 
	
	verticalScroll();
	$(".modal_scroll").mouseover(function() {
	  $(".modal_scroll").getNiceScroll().resize();
	});
	
	$("#ViewedList .tip, .info-label .tip").tooltip ({placement: 'right'}); 
	
	$(".details-tab").click(function(){  
		$(".select2-drop, .select2-drop-mask").hide();  
		var target = $(this).attr("target");
		$(".tab2-content").addClass("hide"); 
		$(".wstep").removeClass("done");
		$("#"+target).removeClass("hide");
		$(this).addClass("done");
	}); 
	
	
	$("#act_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#act_status").trigger("change");
	
	
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
	
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
	$(".remarks-content").hover(
	  function() { 
	  	$(this).find(".show-changes").show(); 
	  }, function() {
		$(this).find(".show-changes").hide(); 
	  }
	); 
	
	$(".show-changes").click(function(){     
		 if($(this).hasClass("i-plus")) 
		  {
		  	 $(this).closest(".remarks-content").find(".history-details-list").show();  
		 	 $(this).removeClass("i-plus").addClass("i-minus"); 
		  }
		 else
		  {
			 $(this).closest(".remarks-content").find(".history-details-list").hide();  
		 	 $(this).removeClass("i-minus").addClass("i-plus"); 
		  }
		  
		 
	});
	
}); 
 

</script> 