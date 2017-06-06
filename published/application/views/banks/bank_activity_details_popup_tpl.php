<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
  
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 180px !important; 
}

.form-horizontal .controls {
    margin-left: 200px;
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
</style>

<div class="row-fluid form-widget-content" id="ActivityDetails" >   

<!-- form -->
<form id="validate_status" name="validate_status" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
  
    <?php 
	if($view_only != 1)
	 {
	?>
    <div class="control-group" >  
        <div class="span4" > 
            <label class="control-label" for="act_assignee">* Assignee</label> 
            <div class="controls controls-row"   >
                <select name="act_assignee" id="act_assignee" class="required select2 myselect" > 
                <optgroup label="Select Assignee"> 
                    <?php if($activity->ActivityID == ""){ ?><option value="" <?php if($activity->Assignee=="") echo "selected='selected'";?> ></option> <?php } ?>
                    <?php
                    foreach($assignees as $row => $assignee){ 
							$stat_class= ($assignee->Status != '1')?"act-danger":"";
                        ?>
                    <option value="<?=$assignee->GroupID;?>" <?php if($assignee->GroupID == $activity->GroupAssignee) echo "selected='selected'";?>  class="<?=$stat_class?>" ><?=$assignee->UserTypeName;?></option>	 		
                        <?php 
                        }
                    ?>
                     
                </optgroup>  
                
            </select> 
            <input type="hidden" name="hidden_aassignee" id="hidden_aassignee" value="" />
            <input type="hidden" name="hidden_defassignee" id="hidden_defassignee" value="<?=$activity->GroupAssignee?>" />
            </div>
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
    <?php	 
	 }
	else
	 {
	?>
    <div class="control-group" >  
        <div class="span4" > 
            <label class="control-label" for="act_assigneex">Assignee</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_assigneex" ><?=ucwords($activity->GroupAssigneeName)?></label>
            </div>
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
    <?php	 
	 }
	?>
    
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
                <label class="info-label" for="act_statusx" ><?=$activity->StatusName?></label>
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
            <label class="control-label" for="act_methodtypex">Method Type</label> 
            <div class="controls controls-row"   >
                <label class="control-label info-label" for="act_methodtypex" ><?=ucfirst($activity->Category)?></label>
            </div>
        </div> 
         
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
    	
        <div class="span4" >
            <label class="control-label" for="act_method">Deposit Method</label> 
            <div class="controls controls-row"   >
                <label class="info-label" for="act_method" ><?=($activity->DepositMethodName)?$activity->DepositMethodName:"_ _ _ _ _ _ _ _ _ _"?></label>
            </div>    
        </div>
         
        <div class="span4" >
            <label class="control-label" for="act_transactionidx">Transaction ID</label> 
            <div class="controls controls-row"   >
                <label class="info-label" for="act_transactionidx" ><?=$activity->TransactionID?></label>
            </div>
        </div> 
        
        <div class="span4" > 
            <label class="control-label" for="act_amountx">Act <?=ucwords($activity->Category)?> Amount</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_amountx" ><?=htmlentities(stripslashes(number_format($activity->Amount, 2, '.', ',')));?></label>
            </div>
        </div> 
         
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
    	<div class="span4" > 
            <label class="control-label" for="act_methodx">Issue</label> 
            <div class="controls controls-row"   >
                <label class="info-label" for="act_methodx" ><?=$activity->Method?></label>
            </div>
        </div>
         
    	<div class="span4 sbf-method hide" > 
            <label class="control-label" for="act_bonusdeductx">Bonus Deduct</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_bonusdeductx" ><?=htmlentities(stripslashes(number_format($activity->BonusDeduct, 2, '.', ',')));?></label>
            </div>
        </div> 
        
        <div class="span4 sbf-method hide" > 
            <label class="control-label" for="act_winningsdeductx">Winnings Deduct</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_bonusdeductedx" ><?=htmlentities(stripslashes(number_format($activity->WinningsDeduct, 2, '.', ',')));?></label>
            </div>
        </div>
        
        <div class="span4 over-amt hide" > 
            <label class="control-label" for="act_actualamountx">Act <span class="actual-amount" ><?=$activity->Method?></span> Amt</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_actualamountx" ><?=htmlentities(stripslashes(number_format($activity->ActualOverAmount, 2, '.', ',')));?></label>
            </div>
        </div>
        
        <div class="span4 over-amt hide" > 
            <label class="control-label" for="act_adjustmentamountx"> Act Adjustment Amt</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_adjustmentamountx" ><?=htmlentities(stripslashes(number_format($activity->ActualAdjustmentAmount, 2, '.', ',')));?></label>
            </div>
        </div>  
         
    </div>
    <!-- End .control-group -->
    
    <div class="control-group wdrejected-amt hide" >    
        <div class="span4" > 
            <label class="control-label" for="act_amountmadex" >* <span class="actual-amount" >DTO</span> Made</label>
            <div class="controls controls-row">   
                <label class="info-label highlight-detail" for="act_amountmadex" ><?=htmlentities(stripslashes(number_format($activity->AmountMade, 2, '.', ',')));?></label>
            </div>
        </div> 
        
        <div class="span4" > 
            <label class="control-label" for="act_amountneedx" >* <span class="actual-amount" >DTO</span> Need</label>
            <div class="controls controls-row">  
                <label class="info-label highlight-detail" for="act_amountneedx" ><?=htmlentities(stripslashes(number_format($activity->AmountNeed, 2, '.', ',')));?></label>
            </div>
        </div>
        
        <div class="span4" > 
            <label class="control-label" for="act_outstandingamountx" >Outstanding Amount</label>
            <div class="controls controls-row">  
                <label class="info-label highlight-detail" for="act_outstandingamountx" ><?=htmlentities(stripslashes(number_format($activity->OutstandingAmount, 2, '.', ',')));?></label>
            </div>
        </div> 
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
        <?php /*?><div class="span4" >
            <label class="control-label" for="act_transactionidx">ID Received</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_transactionidx" ><?=($activity->IdReceived=='1')?"YES":"NO"?></label>
            </div>
        </div> <?php */?>
        
        <div class="span4" >
            <label class="control-label" for="act_analysisx">Analysis Reason</label> 
            <div class="controls controls-row"   > 
                <label class="info-label" for="act_analysisx" >
				<?php
				if($activity->AnalysisReason <= 0)
				 {
					echo "_ _ _ _ _ _ _ _ _ _"; 
				 }
				else
				 {
					echo ($activity->ReasonSpecify)?$activity->ReasonName." (".$activity->ReasonSpecify.")":$activity->ReasonName; 
				 }
				?>
			 
                </label>
            </div>
        </div>
          
        <div class="span4 over-amt hide" >
            <label class="control-label" for="act_adjustmentx">Account Adjustment</label> 
            <div class="controls controls-row"   >
                <label class="info-label" for="act_adjustmentx" ><?=($activity->AdjustmentName)?$activity->AdjustmentName:"_ _ _ _ _ _ _ _ _ _"?></label>
            </div>
        </div>
        
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" >   
         
        <div class="span4" >
            <label class="control-label" for="act_isuploadpmx">Updated No.</label> <!-- from Upload PM to Updated No. -->
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_isuploadpmx" ><?=($activity->IsUploadPM=='1')?"YES":"NO"?></label>
            </div>
        </div> 
         
        <div class="span4 over-amt hide" >
            <label class="control-label" for="act_tofollowup">To Followup</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_tofollowup" ><?=($activity->ToFollowup=='1')?"YES":"NO"?></label>
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
        
        <?php /*?><div class="span4" > 
            <label class="control-label" for="act_reasonx">Analysis Reason</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_reasonx" ><?=($activity->ReasonName)?$activity->ReasonName."<span class='green' > ({$activity->ReasonSpecify})</span>":"_ _ _ _ _ _ _ _ _ _"?></label>
            </div>
			
        </div> <?php */?>
    </div>
    <!-- End .control-group -->  
    
	<?php /*?><tr >
		<td class="caption" >Attachment</td> 
		<td  valign="bottom" colspan="5" > 
		   
			<div id="attch_file_<?=$activity->ActivityID?>" class="attachment-bg">
				<a href="javascript:file_download('<?=$g4['upload_path'].$activity->AttachFilename;?>', '<?=$activity->OldFilename;?>','');" title="download attachment" >
				<span class="download_disp" ><?=($activity->OldFilename)?$activity->OldFilename:$activity->AttachFilename?></span>
				</a> 
			</div>  
		</td>
	</tr><?php */?>
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
                <textarea id="act_remarks" name="act_remarks" class="required span12" rows="3" maxlength="500"  placeholder="<?=htmlentities(stripslashes($activity->Remarks))?>" ></textarea>   
            </div>
        </div> 
        
        <div class="span4"  >  
        		<button type="submit" class="btn btn-primary" id="BtnSubmitForm" style="margin-top: 50px; " align="bottom" >Update Status</button> 
        </div>
    </div>
    <!-- End .control-group -->  
     
	<?php
	 }
	else
	 {
	?>	  
    <div class="control-group" style="margin-bottom: 20px; " >  
         
        <div class="span12" >
            <label class="control-label" for="act_remarksx">Last Remarks</label> 
            <div class="controls controls-row"   >
                <label class="info-label" for="act_remarksx" ><?=($activity->Remarks)?$activity->Remarks:"_ _ _ _ _ _ _ _ _ _"?></label>
            </div>
        </div> 
    </div>
    <!-- End .control-group -->  
	<?php
	 }
	?> 
    
    <!-- TABLE LIST -->
    <div id="TableList"  >
    
        <div > 
             <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable" style="margin-bottom: 0px !important; " >
                <thead>
                    <tr> 
                        <th class="center" width="10%" > Updated By </th>
                        <th class="center" width="15%" > Status </th>
                        <th class="center" > Remarks </th>
                         <th class="center" width="12%" > Assignee </th>
                        <th class="center" width="15%" > Date Updated </th>
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
						 /*foreach($histories as $row=>$history){
							 $s_string = "Activity changed to Important";	 
							 $s_string_complain = "Activity changed to Complain"; 
							 $is_important = ((strpos($history->Changes,$s_string) !== false) || ($history->Important==1 && $history->Changes==""))?1:0; 
							 $is_complaint = ((strpos($history->Changes,$s_string_complain) !== false) || ($history->IsComplaint==1 && $history->Changes=="")); 
							 //$changes = trim($history->Changes,"|||"); 
							 //$changes = str_replace("|||", "<br>", $changes);
							 $changes = "";  
							 $changes_arr = explode("|||", trim($history->Changes,"|||"));  
							 $remarks = "";
							 foreach($changes_arr as $row=>$change){
								$changes .= ($row+1).'. '.$change.'<br>';
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
							  }*/
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
							  
							 if(end($histories) )
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
                    <tr class="history_item" history-id="<?=$history->HistoryID?>" >
                        <td class="center <?=($is_important == 1)?" act-danger ":""?>" width="10%" > 
                        	<i class='icon16 <?=($is_important == 1)?"i-flag-4":""?> tip' title="Activity changed to important" ></i> 
                            <i class="<?=($is_complaint)?"i-warning tip":""?> tip" title="Activity changed to complaint" ></i>  
                            <i class="<?=($history->MainUpdated)?"i-pencil":""?> tip" title="<?=htmlentities($changes)?>" data-html="true" ></i>  
							<?=strtolower($history->UpdatedUser)?> 
                        </td>
                        <td class="center" width="15%" ><?=$history->StatusName?></td>
                        <td ><? //=nl2br($remarks)?>
                        	<div class="remarks-content widget-content" id="HistoryDetails<?=$history->HistoryID?>" > 
                            	<?php if($changes_list != ""){ ?><i class="icon12 tip i-plus pull-right show-changes hide btnimg green" title="View changes" ></i> <?php } ?>
								<?=nl2br($remarks)?>
                                <div class="history-details-list hide">
                                	<p><h4>Changes</h4></p>
                                    <small><ul class="changes-ul" ><?=$changes_list?></ul></small>
                                </div>
                            </div>
                        </td>
                        <td class="center" width="12%"  ><?=$history->GroupAssigneeName?></td>
                        <td class="center" width="15%" ><?=$history->DateUpdated?></td>
                    </tr>
                    <?php		 
						 }//end foreach
					 }
					else
					 {
					?>
                        <tr>
                            <?php /*?><td colspan="5" class="center" ><img src="<?=base_url();?>media/images/loader.gif" /><br />Loading data from Server</td><?php */?>
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
var manageBankActivityStatus = function() { 
	 
	$.ajax({ 
		data: $("#validate_status").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>banks/manageActivityStatus", 
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
				$("#hidden_defassignee").val($("#act_assignee").val());
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
var formdata; //for upload 
var to_upload = 0; 
var upload_error = 0; 
var selected_lang = "";  

var method_sbf = [29,30]; 
var account_adj = [13,27];  
var wd_rejected = [35,36]; 
			
			
			
var issue = "<?=$activity->CategoryID?>";
issue = (issue)?parseInt(issue):issue; 
	 
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
	 
	if($.inArray(issue, method_sbf) !== -1)
	 {     
		 //show bonus deduct and winning deduct   
		 $(".sbf-method").show();  
	 }
	else
	 {  	
		 $(".sbf-method").hide();  
	 }
	  
	 //for account adjustment
	 if($.inArray(issue, account_adj) !== -1)
	 {     
		 //show actual paid amount
		 $(".over-amt").show();   
	 }
	else
	 { 
		 $(".over-amt").hide();  
	 }
	 
	 //for WD REJECTED
	 if($.inArray(issue, wd_rejected) !== -1)
	 {    
		 //show actual paid amount
		 $(".wdrejected-amt").show();   
		 if(issue == 35)
		  {	
			$(".wdrejected-amt").find(".actual-amount").html("BTO");
		  }
		 else
		  {
			$(".wdrejected-amt").find(".actual-amount").html("DTO");
		  }
	 }
	else
	 {
		 $(".wdrejected-amt").hide();    
	 } 
	 
		
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_status").validate({
		 submitHandler: function(form) { 
		 	manageBankActivityStatus();//check duplicate username 
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
	
	
	//------------- Form validation -------------//
	<?php /*?>$("#act_methodtype").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		$("#hidden_amethodtype").val($(this).find(":selected").text());
		changeActivityMethods("<?=base_url()?>banks/getActivityMethods", $(this).val(), "<?=$activity->CategoryID;?>", $("#act_method"));
	}); 
	$("#act_methodtype").trigger("change");
	
	$("#act_method").change(function(){    
		$("#hidden_amethod").val($(this).find(":selected").text()); 
	}); 
	$("#act_method").trigger("change");
	
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
	
	//display attachment
	displayUploaded("<?=$activity->ActivityID?>", "deposit_withdrawal");  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_status .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); <?php */?> 
	
	//download  
	$(".uploaded-filename").click(function(){
		var attach_id = $(this).attr("attach-id"); 
		if(attach_id)
		 {
			if(attach_id == "all")
			 {
				window.location.href = "<?=base_url()?>banks/downloadAttachment/"+<?=$activity->ActivityID?>+"/"+activity_type;   
			 }
			else
			 {
				window.location.href = "<?=base_url()?>banks/downloadAttachment/"+<?=$activity->ActivityID?>+"/"+activity_type+"/"+attach_id;    
			 }
		 } 
	}); 
	
	verticalScroll();
	$(".modal_scroll").mouseover(function() {
	  $(".modal_scroll").getNiceScroll().resize();
	});
	
	$("#ViewedList .tip, .info-label .tip").tooltip ({placement: 'right'});  
	
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
	
	$("#validate_status select").select2({placeholder: "Select"});
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