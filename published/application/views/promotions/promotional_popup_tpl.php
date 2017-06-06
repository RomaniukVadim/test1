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
}

#PromoDetails {
	padding: 10px; 	
} 

.done {
	/*color: #7B110;*/
	color: #F96C1A !important;	
}

.wizard-steps .wstep.done .donut {
    border-color: #F96C1A; 
	color: #F96C1A;
}

.wizard-steps .wstep.done {
    color: #F96C1A;
}

.wizard-steps .wstep.done .donut i {
    color: #F96C1A;
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
    
    <div class="wizard-steps show">
        <div class="wstep wstep-click details-tab current done" data-step-num="0" target="CsaContent" >
            <div class="donut">
            	<i class="icon24 i-user"></i>
            </div>
        	<span class="txt">CSA</span>
        </div>
        
        <div class="wstep <?=($activity->OfferedBy > 0)?"":"wstep-click current details-tab"?>" data-step-num="1" target="CrmContent" >
            <div class="donut">
            	<i class="icon24 i-phone-2"></i>
            </div>
            <span class="txt">CRM</span>
        </div> 
    </div>
	
    <!-- CSA Form -->
    <div id="CsaContent" class="tab2-content"  > 
     
        <div class="row-fluid"  >  
            
            <!-- left -->
            <div class="span6" >
                 
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
                    
                    <?php
					$def_currency = ($activity->Currency)?$activity->Currency:$default_user->Currency; 
					?> 
                    <div class="span6" > 
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
                            <input type="text" id="act_username" name="act_username" class="required span12 tip" value="<?=($activity->Username)?htmlentities(stripslashes($activity->Username)):$default_user->Username;?>" maxlength="100" title="Enter username" <?php if(count($activity)<=0 && $default_user->UserID){?>readonly="readonly" <?php }?>  > 
                        </div>
                    </div>
                    
                    <div class="span6" > 
                         <label class="control-label" for="act_esupportid">E-Support Ticket ID</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_esupportid" name="act_esupportid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->ESupportID));?>" maxlength="30" title="Enter E-Support Ticket ID" > 
                        </div>
                    </div>  
                </div>
                <!-- End .control-group -->
                
                <div class="control-group" >  
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
                    
                    <div class="span6" > 
                         <label class="control-label" for="act_transactionid">Deposit Txn. ID</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_transactionid" name="act_transactionid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->TransactionID));?>" maxlength="30" title="Enter Deposit Transaction ID" > 
                        </div>
                    </div>  
                </div>
                <!-- End .control-group --> 
                
                <div class="control-group" >   
                    <div class="span6" > 
                         <label class="control-label" for="act_casinotransferid">Casino Transfer ID</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_casinotransferid" name="act_casinotransferid" class="span12 tip" value="<?=htmlentities(stripslashes($activity->CasinoTransferID));?>" maxlength="30" title="Enter Casino Transfer ID" > 
                        </div>
                    </div>  
                </div>
                <!-- End .control-group -->
                
                <div class="control-group" >  
                	<div class="span6" >
                        <label class="control-label" for="act_systemid">* System ID</label>
                        <div class="controls controls-row">  
                            <input type="text" id="act_systemid" name="act_systemid" class="span12 tip" value="<?=($activity->SystemID)?htmlentities(stripslashes($activity->SystemID)):$default_user->SystemID;?>" maxlength="30" title="Enter System ID" > 
                        </div>
                    </div>
                    
                    <div class="span6" > 
                        <?php /*?><label class="control-label" for="act_product">* Product</label>
                        <div class="controls controls-row"  >
                            <select name="act_product" id="act_product" class="required select2 span12" > 
                                <optgroup label="Select Product"> 
                                    <option value="" <?php if($activity->Product=="" || $activity->Product=='0') echo "selected='selected'";?> >-- All --</option>
                                    <?php
                                    foreach($products as $row => $product){ 
                                        ?>
                                    <option  value="<?=$product->ProductID;?>" <?php if($product->ProductID == $activity->Product) echo "selected='selected'";?> ><?=$product->ProductName;?></option>	 		
                                        <?php 
                                        }
                                    ?>
                                     
                                </optgroup>  
                                
                            </select> 
                            <input type="hidden" name="hidden_aproduct" id="hidden_aproduct" value="" />
                        </div><?php */?>  
                        
                        <label class="control-label" for="act_issue">Issue</label>
                        <div class="controls controls-row" >
                            <select name="act_issue" id="act_issue" class="select2 span12" > 
                                <optgroup label="Select Issue"> 
                                    <option value="" <?php if($activity->Issue=="" || $activity->Issue=='0') echo "selected='selected'";?> ></option>
                                    <?php 
									foreach($issues as $row => $issue){ 
                                    ?>
                                    <option  value="<?=$issue->IssueID;?>" is-regularize="<?=(in_array($issue->IssueID,$issue_allow_regularize))?1:0?>" <?php if($issue->IssueID == $activity->Issue) echo "selected='selected'";?> ><?=$issue->Name;?></option>	 		
                                     <?php 
                                      }
                                    ?>
                                     
                                </optgroup>  
                                
                            </select> 
                            <input type="hidden" name="hidden_aissue" id="hidden_aissue" value="" />
                        </div>
                        
                    </div>  
                </div>
                <!-- End .control-group -->
                 
                <div class="control-group" >  
                	<div class="span6" >
                        <label class="control-label" for="act_category">* Category</label>
                         <div class="controls controls-row"> 
                            <select name="act_category" id="act_category" class="required select2 span12" > 
                                <optgroup label="Select Category"> 
                                    <option value="" <?php if($activity->Category=="") echo "selected='selected'";?> ></option>
                                    <?php 
									 
									$activity->Category = ($activity->Category > 0)?$activity->Category:$activity->PromotionCategoryID;
                                    foreach($categories as $row => $category){ 
                                        ?>
                                    <option  value="<?=$category->CategoryID;?>" <?php if($category->CategoryID == $activity->Category) echo "selected='selected'";?> ><?=$category->Name;?></option>	 		
                                        <?php 
                                        }
                                    ?>
                                     
                                </optgroup>  
                                
                            </select> 
                            <input type="hidden" name="hidden_acategory" id="hidden_acategory" value="" />
                         </div>  	   
                    </div> 
                    
                    <div class="span6" >  
                        <label class="control-label" for="act_promotion">* Promotion</label>
                        <div class="controls controls-row">  
                            <select name="act_promotion" id="act_promotion" class="required select2 span12" disabled="disabled" > 
                                <optgroup label="Select Promotion"> 
                                    <option value=""  <?php if($activity->Promotion=="" || $activity->Promotion==0) echo "selected='selected'";?>  formula="" wageringformula=""  minimum="" maximum="" turnover="" bonusrate="" type="" start-date="" end-date="" ></option>  
                                    <?php
                                    foreach($promotions as $row => $promotion){ 
                                        ?>
                                    <option  value="<?=$promotion->PromotionID;?>" <?php if($promotion->PromotionID == $activity->Promotion) echo "selected='selected'";?> ><?=$promotion->Name;?></option>	 		
                                        <?php 
                                        }
                                    ?>
                                </optgroup>  
                            </select>  
                            <input type="hidden" name="hidden_apromotion" id="hidden_apromotion"  value="<?=$activity->PromotionName;?>" />
                            <input type="hidden" name="hidden_aformula" id="hidden_aformula"  value="<?=$activity->Formula;?>" />
                            <input type="hidden" name="hidden_awageringformula" id="hidden_awageringformula"  value="<?=$activity->WageringFormula;?>" />
                            <input type="hidden" name="hidden_aminimum" id="hidden_aminimum"  value="<?=$activity->MinimumAmount;?>" />
                            <input type="hidden" name="hidden_amaximum" id="hidden_amaximum"  value="<?=$activity->MaximumAmount;?>" />
                            <input type="hidden" name="hidden_aturnover" id="hidden_aturnover"  value="<?=$activity->TurnOver;?>" /> 
                            <input type="hidden" name="hidden_abonusrate" id="hidden_abonusrate"  value="<?=$activity->BonusRate;?>" />
                            <input type="hidden" name="hidden_atype" id="hidden_atype"  value="<?=$activity->PromotionType;?>" />
                            <input type="hidden" name="hidden_apromotionstart" id="hidden_apromotionstart"  value="<?=($activity->PromotionStartDate != "0000-00-00")?$activity->PromotionStartDate:"";?>" />
                            <input type="hidden" name="hidden_apromotionend" id="hidden_apromotionend"  value="<?=($activity->PromotionEndDate != "")?$activity->PromotionEndDate:"";?>" />
                            
                        </div>
                        
                    </div>  
                     
                </div>
                <!-- End .control-group --> 
                
                
                <div class="control-group" style="padding-bottom: 10px !important;"  >   
                	<label class="control-label" for="act_spacexx"> </label>  
                    <div class="controls controls-row">
                    <?php
					if(admin_access() || csd_supervisor_access() || 1) 
					 {
					?>
                    <label class="radio-inline act-danger tip" title="Important"  >
                        <input type="checkbox" value="1" name="act_important" id="act_important"  <?=($activity->Important==1)?"checked='checked'":"";?> /> Important 
                    </label>
                    
                    <label class="radio-inline act-danger tip" title="Complaint"  > 
                        <input type="checkbox" value="1" name="act_iscomplaint" id="act_iscomplaint" style=" margin-left: 30px; " <?=($activity->IsComplaint==1)?"checked='checked'":"";?> /> Complaint
                    </label>     
                    <?php
					 }
					?>
                    
                    <label class="radio-inline act-danger tip" title="Upload Personal Message" > 
                        <input type="checkbox" value="1" name="act_isuploadpm" id="act_isuploadpm" style="margin-left: 30px; " <?=($activity->IsUploadPM==1)?"checked='checked'":"";?> /> Updated No.
                    </label>
                    
                     <label class="radio-inline act-danger tip" title="To followup" > 
                        <input type="checkbox" value="1" name="act_tofollowup" id="act_tofollowup" style="margin-left: 30px; " <?=($activity->ToFollowup==1)?"checked='checked'":"";?> /> To Follow Up
                    </label>
                    </div> 
                </div>
                <!-- End .control-group -->
                 
                <div class="control-group" >   
                	<div class="span6" >
                        <label class="control-label" for="act_status">* Status</label>
                        <div class="controls controls-row"> 
                            <select name="act_status" id="act_status" class="required select2"   > 
                                <optgroup label="" >     
                                    <?php if($activity->Status == 0 || $activity->Status == "" || $activity->Status == '0') {?><option value="0" <?php if($activity->Status=="0") echo "selected='selected'";?> >New</option><?php } ?> 
                                    <?php
                                    //if($activity->ActivityID > 0)
                                     //{
                                    foreach($status_list as $row => $status){ 
                                        $users_list = explode(",", $status->Users);
                                          if(in_array($this->session->userdata("mb_usertype"),$users_list) || ($status->StatusID==$activity->Status) )
                                           {
                                    ?>
                                    <option value="<?=$status->StatusID;?>" <?php if($activity->Status==$status->StatusID) echo "selected='selected'";?>  ><?=ucwords($status->Name);?></option>
                                        <?php 
                                           }
                                        }
                                     //}//end if
                                    ?>
                                </optgroup> 
                            </select>   
                            <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" />
                            <input type="hidden" name="hidden_defstatus" id="hidden_defstatus" value="<?=$activity->Status?>" />
                        </div>    
                    </div> 
                    
                    <div class="span6" >
                    	<?php 
						$is_call = (($activity->CallStart != "0000-00-00 00:00:00" && $activity->CallStart != "" && $activity->CallEnd != "0000-00-00 00:00:00" && $activity->CallEnd != "") )?1:0; 
						if(!isset($activity->ActivityID) )
						 { 
						 	$disabled_offer = (!can_offer_promotion() )?"disabled='disabled'":"";
						?>
                    	<label class="radio-inline tip" title="Promotion offered"  >
                            <input type="checkbox" value="1" name="act_offer" id="act_offer"  <?=($activity->OfferedBy > 0)?"checked='checked'":"";?> <?=(!can_offer_promotion() || $is_call == 1)?"disabled='disabled'":""?> /> Promotion Offered 
                        </label> 
                        <?php  
						 }
						else
						 {
							if($activity->OfferedBy > 0)
							 {
								 $disabled_offer = (!admin_access())?"disabled='disabled'":"";
							?>
                            <label class="radio-inline tip" title="Promotion offered by <?=$activity->OfferedByName;?>"   >
                                <input type="checkbox" value="1" name="act_offer" id="act_offer" checked="checked"  <?=$disabled_offer?> /> Promotion Offered 
                            </label>
                            <?php	 
							 }
							else
							 {
								  
							?>
                            <label class="radio-inline tip" title="Promotion offered"  >
                                <input type="checkbox" value="1" name="act_offer" id="act_offer"  <?=($activity->OfferedBy > 0)?"checked='checked'":"";?> <?=(!can_offer_promotion() || $is_call == 1)?"disabled='disabled'":""?> /> Promotion Offered 
                            </label> 
                            <?php	 
							 }
						?>
                         
                        <?php	 
						 }
						?>
                    </div>
                    
                </div>
                <!-- End .control-group -->  
                   
            </div>
            <!-- end left -->
            
            <!-- right -->
            <div class="span6" >
                 
                <div class="control-group" >  
                    <div class="span6" > 
                        <label class="control-label" for="act_currentbalance">* Current Balance</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_currentbalance" name="act_currentbalance" class="required span12 tip txt-currency txt-compute" value="<?=$activity->CurrentBalance;?>" maxlength="30" title="Enter current balance" <?=($activity->PromotionType=="")?"readonly='readonly'":"";?> > 
                        </div>
                    </div> 
                    
                    <div class="span6" >
                        <label class="control-label" for="act_outstandingbets">Outstanding Bets</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_outstandingbets" name="act_outstandingbets" class="span12 tip txt-currency txt-compute" value="<?=$activity->OutstandingBets;?>" maxlength="30" title="Enter outstanding bets"  <?=($activity->OutstandingBets=="")?"readonly='readonly'":"";?>  > 
                        </div>
                    </div> 
                     
                </div>
                <!-- End .control-group -->
                
                <div class="control-group" >  
                    <div class="span6" > 
                        <label class="control-label" for="act_depositamount">* Deposit Amount</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_depositamount" name="act_depositamount" class="required span12 tip txt-currency txt-compute" value="<?=$activity->DepositAmount;?>"  title="Enter deposit amount"  <?=($activity->PromotionType=="")?"readonly='readonly'":"";?> maxlength="30" > 
                        </div>
                    </div> 
                    
                    <div class="span6" >
                        <label class="control-label" for="act_bonusamountx">* Bonus Amount</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_bonusamountx" name="act_bonusamountx" class="required span12 tip notread txt-currency txt-compute" value="<?=$activity->BonusAmount;?>" maxlength="30" title="Enter bonus amount" <?=($activity->PromotionType=="promotion" || $activity->PromotionType=="")?"readonly='readonly'":"";?> >  
                            <input type="hidden" name="act_bonusamount" id="act_bonusamount"  value="<?=$activity->BonusAmount;?>" />
                        </div>
                    </div> 
                     
                </div>
                <!-- End .control-group -->
                
                <div class="control-group" >  
                    <div class="span6" > 
                        <label class="control-label" for="act_wageringamount">* Wagering Amount</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_wageringamount" name="act_wageringamount" class="required span12 tip notread txt-currency txt-compute" value="<?=$activity->WageringAmount;?>" maxlength="30" title="Enter wagering amount"  <?=($activity->PromotionType=="promotion" || $activity->PromotionType=="")?"readonly='readonly'":"";?>  > 
                        </div>
                    </div> 
                    
                    <div class="span6 cb_amount <?=($activity->PromotionType=="promotion" || $activity_id == "")?" hide ":"";?>"  >
                        <label class="control-label" for="act_turnoveramount">Turnover Amount</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_turnoveramount" name="act_turnoveramount" class="span12 tip notread txt-currency txt-compute" value="<?=$activity->TurnoverAmount;?>" maxlength="30" title="Enter turnover amount" > 
                        </div>
                    </div> 
                     
                </div>
                <!-- End .control-group --> 
                
                <div class="control-group cb_amount <?=($activity->PromotionType=="promotion" || $activity_id == "")?" hide ":"";?>" >  
                    <div class="span6" > 
                        <label class="control-label" for="act_cashbackamount">Cashback Amount</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_cashbackamount" name="act_cashbackamount" class="span12 tip notread txt-currency txt-compute" value="<?=$activity->CashbackAmount;?>" maxlength="30" title="Enter cashback amount"  <?php if($activity->PromotionType=="promotion" || $activity->PromotionType=="") echo "readonly='readonly'";?>  > 
                        </div> 
                    </div> 
                    
                    <div class="span6"  >
                        
                    </div> 
                     
                </div>
                <!-- End .control-group --> 
                
                <div class="control-group regularize-amount hide" >  
                    <div class="span6" > 
                        <label class="control-label" for="act_bonusdeduct">* Bonus Deduct</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_bonusdeduct" name="act_bonusdeduct" class="span12 tip txt-currency issue-regularize" value="<?=$activity->BonusDeduct;?>"  title="Enter bonus deduct" maxlength="30"  > 
                        </div>
                    </div> 
                    
                    <div class="span6" >
                        <label class="control-label" for="act_winningsdeduct">* Winnings Deduct</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_winningsdeduct" name="act_winningsdeduct" class="span12 tip txt-currency issue-regularize" value="<?=$activity->WinningsDeduct;?>" maxlength="30" title="Enter winnings deduct" >   
                        </div>
                    </div> 
                     
                </div>
                <!-- End .control-group -->
                
                <div class="control-group regularize-amount hide"  >  
                    <div class="span6" > 
                        <label class="control-label" for="act_regularizeamount">* Regularize Amount</label>
                        <div class="controls controls-row"  >
                            <input type="text" id="act_regularizeamount" name="act_regularizeamount" class="required span12 tip txt-currency issue-regularize" value="<?=($activity->RegularizeAmount > 0)?$activity->RegularizeAmount:"";?>" maxlength="30" title="Enter regularize amount" <?=(in_array($activity->Issue, $issue_allow_regularize))?"readonly='readonly'":"";?> > 
                        </div>
                    </div>  
                     
                </div>
                <!-- End .control-group -->
                
                 
                <!-- Details --> 
                <div id="PromoDetails" > 
                
                    <?php
                     if($activity->ActivityID)
                      {
                         echo "Bonus Name <b>: " . $activity->PromotionName . "</b><br>Minimum Bonus Amount: " . $activity->MinimumAmount . "<br>Maximum Bonus Amount: " . $activity->MaximumAmount . "<br>Turnover: " . $activity->TurnOver . "<br>Bonus Rate: " . $activity->BonusRate . "%"; 
                      }
                         ?>	 
                </div>
                <!-- End Details  -->
                
            </div>
            <!-- end right -->
             
        </div> 
        
        <div class="control-group" > 
             
        </div>
        <!-- End .control-group -->
        
        <div class="control-group" > 
            <label class="control-label" for="act_remarks">* Remarks</label>
            <div class="controls controls-row" >  
                <textarea id="act_remarks" name="act_remarks" class="required span12 tip" rows="3"  maxlength="500" title="Enter remarks" placeholder="<?=htmlentities(stripslashes($activity->Remarks));?>"  ></textarea>
            </div>
        </div>
        <!-- End .control-group --> 
         
        <div class="control-group" > 
            <label class="control-label" for="btn_addbanner">Attach File</label>
            <div class="controls controls-row"  id="AttachmentLoader" >   
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
                    
                            <div id="datepicker1" class="input-append datepicker" > 
                                 <span class="add-on">
                                    <i class="icon16"></i>
                                </span>
                                <input type="text" value="<?=($activity->CallStart != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->CallStart)):"";?>"  name="act_callstart" id="act_callstart" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip call-fieldimportant call-field" title="Select start call"  >
                            </div> 
                        
                            <?php /*?><div class="input-append date form_datetime">
                            <input size="16" type="text" value="" readonly>
                            <span class="add-on"><i class="icon16 icon-calendar"></i></span>
                            </div>
                             
                            <script type="text/javascript">
                            $(".form_datetime").datetimepicker({
                            format: "yyyy-mm-dd hh:ii:ss",
                            autoclose: true,
                            todayBtn: true,
                            pickerPosition: "bottom-left"
                            });
                            </script><?php */?> 
                         
                    </div> 
                </div> 
                
                <div class="span4" >
                    <label class="control-label" for="act_callend">End Call</label>
                    <div class="controls controls-row">
                        <div id="datepicker2" class="input-append datepicker"  > 
                             <span class="add-on">
                                <i class="icon16 "></i>
                            </span>
                            <input type="text" value="<?=($activity->CallEnd != "0000-00-00 00:00:00")?htmlentities(stripslashes($activity->CallEnd)):"";?>"  name="act_callend" id="act_callend" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip call-fieldimportant call-field" title="Select end call" >
                        </div>
                    </div>
                </div> 
                
                <div class="span4" >
                    <label class="control-label" for="act_callerid"> Ameyo Caller ID</label>
                    <div class="controls controls-row"  >
                        <input type="text" id="act_callerid" name="act_callerid" class="tip call-fieldimportant call-field" value="<?=htmlentities(stripslashes($activity->AmeyoCallerID));?>" maxlength="20" title="Enter ameyo caller ID" <?php if(count($activity)<=0 && $default_user->UserID){?>readonly="readonly" <?php }?>  > 
                    </div>
                </div> 
                 
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group"  >   
            	<div class="span4" >
                    <label class="control-label" for="act_callresult">Result</label>
                    <div class="controls controls-row call-select-input" > 
                        <select name="act_callresult" id="act_callresult" class="select2 myselect call-fieldimportant call-field"  > 
                            <option value="" >- Select Result -</option>  
							<?php
                            foreach($results as $row=>$result) {
                            ?> 
                            <option value="<?=$result->result_id;?>" <?php if($activity->CallResultID==$result->result_id) echo "selected='selected'";?>  ><?=$result->result_name;?></option>
                            <?php	
                            }//end foreach
                            ?> 
                        </select>  
                        <input type="hidden" name="hidden_acallresult" id="hidden_acallresult"  value="<?=$activity->ResultName;?>" class="call-field" /> 
                    </div>
                </div>
                
                <div class="span4 hide result-category-field" id="CallResultCategoryHolder">
                    <label class="control-label" for="act_callresultcategory"><span class="lbl-result-category" >Result</span> Category</label>
                    <div class="controls controls-row call-select-input" > 
                        <select name="act_callresultcategory" id="act_callresultcategory" class="select2 myselect call-result-category call-field"  > 
                            <option value="" >- Select Result Category -</option>     
                        </select>  
                        <input type="hidden" name="hidden_acallresultcategory" id="hidden_acallresultcategory"  value="<?=$activity->CallResultCategoryName;?>" class="call-field" /> 
                    </div>
                </div> 
                
                <div class="span4" > 
                    <label class="control-label" for="act_calloutcome">Outcome</label>
                    <div class="controls controls-row call-select-input">
                        <select name="act_calloutcome" id="act_calloutcome" class="select2 myselect call-fieldimportant call-field" disabled="disabled"  > 
                            <option value="" >- Select Outcome -</option> 
							<?php
                            foreach($outcomes as $row=>$outcome) {
                            ?> 
                            <option value="<?=$outcome->outcome_id;?>" <?php if($activity->CallOutcomeID==$outcome->outcome_id) echo "selected='selected'";?> result-id="<?=$outcome->result_id?>" result-name="<?=$outcome->result_name?>" ><?=$outcome->outcome_name;?></option>
                            
                            <?php	
                            }//end foreach
                            ?> 
                        </select>  
                        <input type="hidden" name="hidden_acalloutcome" id="hidden_acalloutcome"  value="<?=$activity->OutcomeName;?>" class="call-field" />
                    </div> 
                </div> 
                  
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" style="padding-bottom: 10px; " >   
            	
                <div class="span4" > 
                	<label class="control-label" for="act_callsendsmsemail"></label>
                    <div class="controls controls-row" style="margin: 0" >
                        <label class="radio-inline" >
                            <input type="checkbox" value="1" name="act_callsendsms" id="act_callsendsms" <?=($activity->CallSendSMS==1)?"checked='checked'":"";?> class="call-field" /> Send SMS 
                        </label> 
                        
                        <label class="radio-inline" >
                            <input type="checkbox" value="1" name="act_callsendemail" id="act_callsendemail"  <?=($activity->CallSendEmail==1)?"checked='checked'":"";?> class="call-field" /> Send Email
                        </label>     
                    </div>
                </div>
                     
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group result-remarks hide"  > 
                <label class="control-label" for="act_custremarks">Customer Comments</label>
                <div class="controls controls-row"  >
                  <textarea id="act_custremarks" class="span12 tip result-remarks-field call-field" placeholder="<?=$activity->CustomerRemarks?>" title="Enter customer remarks" maxlength="250" rows="3" name="act_custremarks" ><?=$activity->CustomerRemarks?></textarea>  
                </div> 
                  
            </div>             
            <!-- End .control-group -->
            
            <div class="control-group" > 
                <label class="control-label" for="act_callsendsms">&nbsp;</label>
                <div class="controls controls-row"  >
                    <label class="radio-inline" >
                        <input type="radio" value="account_active" name="act_callproblem" class="call-field" <?=($activity->CallProblem=="account_active")?"checked='checked'":"";?> /> Account Active
                    </label> 
                    
                    <label class="radio-inline" >
                        <input type="radio" value="number_invalid" name="act_callproblem" class="call-field" <?=($activity->CallProblem=="number_invalid")?"checked='checked'":"";?> /> Number Invalid
                    </label> 
                    
                    <label class="radio-inline" >
                        <input type="radio" value="account_frozen" name="act_callproblem" class="call-field" <?=($activity->CallProblem=="account_frozen")?"checked='checked'":"";?> /> Account Frozen
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
		url: "<?=base_url();?>promotions/manageActivity", 
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
					 $("#PromoDetails").html("");
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
		url: "<?=base_url();?>promotions/displayUploaded", 
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


function changeSelectValues(url, result, default_result, container, display){ 
 	 
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
					selected = (default_result == value.ID)?'selected="selected"':'';
					new_string = '<option value="'+value.ID+'" '+selected+'>'+value.Name+'</option>';  
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

function clearCallFields() {
	 
	$("input[type=text].call-field, input[type=hidden].call-field, select.call-field").val(""); 
	$("select.call-field").trigger("change");
	$("input[type=checkbox].call-field, input[type=radio].call-field").prop('checked', false);
	
	clearSelectbox($("div.call-select-input"));  
	$.uniform.update("input[type=checkbox].call-field, input[type=radio].call-field"); 
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
			act_systemid: {
				required: true 
			},
			/*act_product: {
				required: true 
			},*/ 
			act_category: {
				required: true 
			},  
			act_promotion: {
				required: true 
			},     
			act_status: {
				required: true 
			},
			act_remarks: {
				required: true 
			},
			act_currentbalance: {
				required: true 
			},
			act_depositamount: {
				required: true 
			},
			act_bonusamountx: {
				required: true 
			},
			act_wageringamount: {
				required: true 
			}, 
			act_bonusdeduct: { 
				number: true
			}, 
			act_winningsdeduct: { 
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
			act_systemid: {
				required: "Provide system ID" 
			},
			/*act_product: {
				required: "Select product" 
			},*/
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
			}, 
			act_regularizeamount: {
				required: "Enter regularize amount" 
			}, 
			act_bonusdeduct: {
				required: "Enter bonus deduct" 
			}, 
			act_winningsdeduct: {
				required: "Enter winnings deduct" 
			}, 
			act_callstart: { 
				required: "Enter start call" 
			}, 
			act_callend: {
				required: "Enter end call" 
			}, 
			act_calloutcome: {
				required: "Select outcome" 
			},
			act_callerid: {
				required: "Enter ameyo caller ID" 
			}, 
			act_callresult: {
				required: "Select result" 
			}, 
			act_callresultcategory: {
				required: "Select result category" 
			}, 
			act_custremarks: {
				required: "Enter customer remarks" 
			}	
			
		}
	}); 
	  
	//------------- Form validation -------------//
	  
	
	//changePromotions("<?=base_url()?>promotions/getPromotionsList", '<?=$activity->Product?>', '<?=$activity->Currency?>', '<?=$activity->Promotion?>', $("select[name=act_promotion]")); 
	    
	
	$("#act_category").change(function(){ 
		var currency = ($("#act_currency").val())?$("#act_currency").val():"";
		var product = ($("#act_product").val())?$("#act_product").val():"";
		var category = ($(this).val())?$(this).val():"";
		$("#hidden_acategory").val($(this).find(":selected").text()); 
		changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
		
		<?php
		if(count($activity->ActivityID) <= 0) 
		 {
		?>
		$("#act_status optgroup").find("option").removeAttr("selected");
		if($(this).val() == '<?=$settings_ids[cashback_application_category]?>')
		 {
			$("#act_status optgroup").find("option[value='<?=$settings_ids[inprogress_status]?>']").attr('selected', 'selected');
			$("#act_status").select2();
			$("#act_status").select2("val", "<?=$settings_ids[cashback_application_category]?>"); //set the value
		 } 
		$("#act_status").trigger("change");
		<?php
		}
		?>
		
	});
	$("#act_category").trigger("change"); 
	
	  
	$("#act_currency").change(function(){    
		var currency = ($(this).val())?$(this).val():""; 
		var product = ($("#act_product").val())?$("#act_product").val():"";  
		var category = ($("#act_category").val())?$("#act_category").val():"";
		$("#hidden_acurrency").val($(this).find(":selected").text()); 
		changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
	}); 
	$("#act_currency").trigger("change");   
	
	$("#act_product").change(function(){  
		 var product = ($(this).val())?$(this).val():""; 
		 var currency = ($("#act_currency").val())?$("#act_currency").val():"";  
		 var category = ($("#act_category").val())?$("#act_category").val():"";  //$("#act_category").val(); 
		 $("#hidden_aproduct").val($(this).find(":selected").text());   
		 changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
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
	
	$("#act_issue").change(function(){   
		$("#hidden_aissue").val($(this).find(":selected").text()); 
		if($(this).find(":selected").attr('is-regularize') == 1)
		 {   
		 	$(".issue-regularize").each(function (e) {  
				$(this).rules('add', {
					required: true
				});   
			});
			
			$("#act_regularizeamount").removeAttr("readonly"); 
			$("#act_regularizeamount").removeClass("readonly"); 
		 	$(".regularize-amount").show();
		 }
		else
		 { 
			$(".issue-regularize").each(function (e) {  
				$(this).rules('add', {
					required: false
				});   
			});
			$("#act_regularizeamount").attr("readonly", "readonly"); 
			$("#act_regularizeamount").addClass("readonly"); 
			$(".regularize-amount").hide(); 
		 }
		   
	}); 
	$("#act_issue").trigger("change");
	 
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
		window.location.href = "<?=base_url()?>promotions/activities"
	});  
	
	
	//promotion change 
	$("#act_promotion").change(function(){  
		$("#PromoDetails").html("");
		$("#hidden_apromotion").val("");
		$("#hidden_aminimum").val("");
		$("#hidden_amaximum").val("");
		$("#hidden_aturnover").val(""); 
		$("#hidden_abonusrate").val("");
		$("#hidden_aformula").val("");
		$("#hidden_awageringformula").val("");
		$("#hidden_atype").val(""); 
		$("#act_depositamount").val("")
		$("#act_bonusamount").val("");
		$("#act_bonusamountx").val(""); 
		$("#act_wageringamount").val(""); 
		$("#act_turnoveramount").val(""); 
		$("#act_cashbackamount").val(""); 
		
		$("#hidden_apromotionstart").val("");
		$("#hidden_apromotionend").val("");
		
		$(".txt-compute").val("");  
		$("#PromoDetails").html("");
		
		var promotion = $("#act_promotion option:selected").text();
		var minimum = $("#act_promotion option:selected").attr("minimum");
		var maximum = $("#act_promotion option:selected").attr("maximum"); 
		var turnover = $("#act_promotion option:selected").attr("turnover"); 
		var bonusrate = $("#act_promotion option:selected").attr("bonus-rate"); 
		var formula = $("#act_promotion option:selected").attr("formula");
		var wageringformula = $("#act_promotion option:selected").attr("wagering-formula");
		var type = $("#act_promotion option:selected").attr("type");  
		
		var promotion_start = $("#act_promotion option:selected").attr("start-date");
	 	var promotion_end = $("#act_promotion option:selected").attr("end-date");
		
		if(type)
		 {
			 $("#act_currentbalance").removeAttr("readonly");
			 $("#act_outstandingbets").removeAttr("readonly");
			 $("#act_depositamount").removeAttr("readonly"); 
			 
			 $("#act_currentbalance").removeClass("readonly");
			 $("#act_outstandingbets").removeClass("readonly");
			 $("#act_depositamount").removeClass("readonly");
		 }
		else
		 {
			 //$("#act_currentbalance").attr("readonly");
			 //$("#act_outstandingbets").attr("readonly"); 
			 //$("#act_depositamount").attr("readonly"); 
			 //$("#act_currentbalance").addClass("readonly");
			 //$("#act_depositamount").addClass("readonly"); 
		 }
		 
		if(type == "promotion")
		 {
			$("#hidden_apromotion").val(promotion);
			$("#hidden_aminimum").val(minimum);
			$("#hidden_amaximum").val(maximum);
			$("#hidden_aturnover").val(turnover); 
			$("#hidden_abonusrate").val(bonusrate);
			$("#hidden_aformula").val(formula);
			$("#hidden_awageringformula").val(wageringformula);
			$("#hidden_atype").val(type); 
			
			$("#hidden_apromotionstart").val(promotion_start);
			$("#hidden_apromotionend").val(promotion_end);
			
			$("#PromoDetails").html("Bonus Name: <b> " + promotion +"</b><br>Minimum Bonus Amount: " + minimum + "<br>Maximum Bonus Amount: " + maximum + "<br>Turnover: " + turnover + "<br>Bonus Rate: " + bonusrate + "%" );
		 	$(".notread").attr("readonly", "readonly"); 
			$(".notread").addClass("readonly"); 
			
			$(".cb_amount").hide(); 
		 }
		else
		 { 
			$(".notread").removeAttr("readonly"); 
			$(".notread").removeClass("readonly");
			$("#PromoDetails").html("");  
			$("#act_currentbalance").val("0.00");
			$("#act_outstandingbets").val("0.00");
			$("#act_depositamount").val("0.00");
			$("#act_bonusamount").val("0.00");
			$("#act_bonusamountx").val("0.00");
			$("#act_wageringamount").val("0.00"); 
			
			$("#hidden_apromotionstart").val("");
			$("#hidden_apromotionend").val("");
		
			$(".cb_amount").show();
		 }
	  
	});
	//$("#act_promotion").trigger("change");
	
	
	$("#act_depositamount").keyup(function() { 
		var $deposit_amt = $("#act_depositamount").val(); 
		var $min_amt = $("#hidden_aminimum").val();
		var $max_amt = $("#hidden_amaximum").val();
		var $reqt = $("#hidden_aturnover").val(); 
		var $bonus = $("#hidden_abonusrate").val()/100;
		var $formula = $("#hidden_aformula").val();
		var $wagering_formula = $("#hidden_awageringformula").val();    
		var type = $("#hidden_atype").val(); 
		
		if(type=="promotion"){ 
			 
			var $formula = replaceAll('round', 'Math.round',$formula);
			var $formula = replaceAll('min[\(]', 'Math.min(',$formula); 
			$wagering_formula = replaceAll("round", "Math.round", $wagering_formula);
			$wagering_formula = replaceAll("min[\(]", "Math.min(", $wagering_formula);
			var $bonus_amt = eval($formula);
			
			var $bonus_amt = Math.round($bonus_amt*100)/100;  
			var $wagering_amt = eval($wagering_formula);   
			$wagering_amt = ($wagering_amt > 0)?$wagering_amt:0; 
			$("#act_bonusamountx").val(Math.round($bonus_amt));
			$("#act_wageringamount").val($wagering_amt);  
			$("#act_bonusamount").val($bonus_amt);
		 }
		else
		 {
			$("#act_bonusamountx").val($("#act_bonusamount").val()); 
		 }
		
	});
	
	$("#act_bonusamountx").keyup(function() {   
		var type = $("#hidden_atype").val();
		 
		if(type!="cashback"){ 
			$("#act_bonusamount").val($(this).val()); 
		}
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
	var can_custremarks = [1];
	var can_result_category = [1];
	
	$("#act_callresult").change(function(){   
		var result =  parseInt($(this).val());
	 
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		$("#hidden_acallresult").val($(this).find(":selected").text());  
		changeSelectValues("<?=base_url()?>promotions/getCallOutcomeList", $(this).val(), "<?=$activity->CallOutcomeID;?>", $("#act_calloutcome"));   
		
		if($.inArray(result, can_custremarks) !== -1)
		 {    
		 	 //result-remarks-field 
			 //make analysis important 
			 $(".result-remarks").show();  
			 /*$(".result-remarks-field").each(function() {   
				$(this).rules('add', {
					required: true
				});
			 });*/ 
		 }
		else
		 {   
			 $(".result-remarks").hide();  
			 /*$(".result-remarks-field").each(function() {   
				$(this).rules('add', {
					required: false
				});
			 }); */
		 }
		 
		 var res_name = $(this).find('option:selected').text();
		 if($.inArray(result, can_result_category) !== -1)
		 {  
		 	$(".result-category-field").show();    
		 	changeSelectValues("<?=base_url()?>promotions/getResultCategoriesList", $(this).val(), "<?=$activity->CallResultCategoryID;?>", $("#act_callresultcategory"));     
			$("select.call-result-category").each(function (e) { 
				$(this).rules('add', {
					required: true
				});
			});	
		 }
		else
		 {   
			 $(".result-category-field").hide();  
			 $("select.call-result-category").each(function (e) { 
				$(this).rules('add', {
					required: false
				});
			 });	
		 } 
		 
		 $(".lbl-result-category").text(res_name);
		 
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
		if(!$(this).hasClass("wstep-click")) return false; 
		
		$(".select2-drop, .select2-drop-mask").hide();  
		var target = $(this).attr("target");
		$(".tab2-content").addClass("hide"); 
		$(".wstep").removeClass("done");
		$("#"+target).removeClass("hide");
		$(this).addClass("done");  
		 
	}); 
	
	$('#datepicker1').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii:ss", 
		pickTime: true,
		//todayBtn: true,  
		todayHighlight: true/*,
		autoclose: true, 
		pickerPosition: "bottom-left"*/ 
		
	});
	
	$('#datepicker2').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii:ss", 
		pickTime: true
		//todayBtn: false 
	});
	
	$("#datepicker1").on("dp.change",function (e) {  
	   $('#datepicker2').data("DateTimePicker").setMinDate(e.date);  
	}); 
	
	$("#datepicker2").on("dp.change",function (e) {
	   $('#datepicker1').data("DateTimePicker").setMaxDate(e.date);  
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
	
	 
	$("input:checkbox[name=act_offer]").click(function(){   
		
		if($(this).is(":checked"))
		 {
			 $("div.wstep[target=CrmContent]").removeClass("wstep-click");
			 $("div.wstep[target=CrmContent]").removeClass("current"); 
			 $("div.wstep[target=CrmContent]").css("cursor","default");   
			 clearCallFields(); 
		 }
		else
		 {
			 $("div.wstep[target=CrmContent]").addClass("wstep-click");
			 $("div.wstep[target=CrmContent]").addClass("current");  
			 $("div.wstep[target=CrmContent]").css("cursor","pointer"); 
		 } 
		//$.uniform.update("input:checkbox[name=act_offer]");   
		
		//$.uniform.update("input[type=checkbox]"); 
	});
	
	
	$("select").change(function(){
		$(".select2-drop").hide(); 
	});
	
	$('#validate select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
	<?php
	if($activity->Status === '0' || $activity->Status == '0')
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
	
}); 
 

</script> 