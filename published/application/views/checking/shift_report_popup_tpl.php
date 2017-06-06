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

#AttachmentLoader label.error { 
	position: absolute; 
	top: 90px; 
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
/*.wstep {
	cursor: pointer; 	
}*/
</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate" name="validate" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_reportid" id="hidden_reportid" value="<?=$report->ReportID;?>" >
    <input type="hidden" value="<?=($report->ReportID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
      
    <!-- CHECKING Form -->
    <div id="CheckingContent" class="tab2-content"  > 
     	
        <div class="control-group" >  
           <div class="span6" > 
               <label class="control-label" for="report_date">* Report Date</label>
               <div class="controls controls-row">  
                     <div id="datepicker1" class="input-append datepicker" > 
                         <span class="add-on">
                            <i class="icon16"></i>
                        </span>
                        <input type="text" value="<?=($report->ReportDate != "0000-00-00 00:00:00" && $report->ReportDate)?htmlentities(stripslashes($report->ReportDate)):date('Y-m-d h:i:s');?>"  name="report_date" id="report_date" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip call-fieldimportant" title="Select date"  >
                    </div> 
                </div>  
           </div> 
           
           <div class="span6" >
           		<label class="control-label" for="report_addedby"> Added By</label>
           		<div class="controls controls-row">
           			<label class="info-label highlight-detail" for="report_addedbyx" ><?=($report->AddedByNickname)?ucwords($report->AddedByNickname):""?></label>
                </div>
           </div>
           
        </div>
        <!-- End .control-group -->
         
        <div class="control-group" >  
           <div class="span6" >	
               <label class="control-label" for="report_shift">* Shift</label>
               <div class="controls controls-row">  
                    <select name="report_shift" id="report_shift" class="required select2 myselect" <?=(!can_post_shift_report())?"disabled='disabled'":""?> > 
                        <optgroup label="Select Shift">  
                            <?php
                            foreach($shifts as $row => $shift){ 
                                ?>
                            <option value="<?=$shift->ShiftID;?>" <?php if($shift->ShiftID == $default_shift) echo "selected='selected'";?> ><?=$shift->ShiftName;?></option>	 		
                                <?php 
                                }
                            ?>
                             
                        </optgroup>  
                        
                    </select> 
                    <input type="hidden" name="hidden_ashift" id="hidden_ashift" value="" />
                </div> 
            </div>
            
            <div class="span6" >	
            	<label class="control-label" for="report_currency">* Currency</label>
                <div class="controls controls-row">  
                    <select name="report_currency" id="report_currency" class="required select2 myselect" <?=(!can_post_shift_report())?"disabled='disabled'":""?> > 
                        <optgroup label="Select Currency">  
                        	<option value="" >&nbsp;</option>
                            <?php
                            foreach($currencies as $row => $currency){ 
                                ?>
                            <option value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $report->Currency) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
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
            <label class="control-label" for="report_info"> * Remarks</label>
            <div class="controls controls-row" >  
                <textarea id="report_info" name="report_info" class="tip span12" rows="3"  maxlength="300" title="Enter report" <?=(!can_post_shift_report())?"readonly='readonly'":""?> ><?=stripslashes($report->Report);?></textarea>
            </div>
        </div>
        <!-- End .control-group -->  
        
        <div class="control-group" >  
           <label class="control-label" for="report_status">* Status</label>
           <div class="controls controls-row">  
           		<?php  
				if(admin_access() || shift_report_all())
				 {  
				?>
                <select name="report_status" id="report_status" class="required select2 myselect" > 
                    <optgroup label="Select status">  
                        <?php
                        foreach($report_status as $row => $status){ 
                            ?>
                        <option value="<?=$status?>" <?php if($status == $report->Status) echo "selected='selected'";?> ><?=ucwords($row)?></option>	 		
                            <?php 
                            }
                        ?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" /> 
                <?php
				 }
				else
				 {
				?>
                <label class="info-label highlight-detail" for="report_statusx" ><?=($report->StatusName)?ucwords($report->StatusName):"Pending"?></label>
                <?php	 
				 }
				?>
            </div> 
        </div>
        <!-- End .control-group --> 
        
    </div>
    <!-- END CHECKING Form -->
     
    
    <div class="form-actions"> 
		<?php
		if(can_post_shift_report())
		 {
		?>
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" >Submit</button>
		<?php
		}
		?>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->


<!-- Init plugins only for page --> 
<script >   
var manageShiftReport = function() { 
	 
	$.ajax({ 
		data: $("#validate").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>shift_report/manageShiftReport", 
		dataType: "JSON",  
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
				$('#validate')[0].reset();
				clearSelectbox($("div.controls"));  
				$("ul.select2-choices li.select2-search-choice").remove();  
				$.uniform.update("input[type=checkbox]");  
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
	
	//e.preventDefault();
	  
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
		 	manageShiftReport();//check duplicate username 
		},
		invalidHandler: function(event, validator) { 
			//createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			report_date : {
				required: true
			},
			report_currency : {
				required: true
			},
			report_shift: {
				required: true 
			}, 
			report_info: {
				required: true, 
				minlength: 10
			}
		},
		messages: { 
			report_date : {
				required: "Select report date"
			},
			report_currency : {
				required: "Select currency"
			},
			report_shift: {
				required: "Select shift" 
			},   
			report_info: {
				required: "Enter remarks" 
			}  	 
				
		}
	});  
	//------------- Form validation -------------//  
	
	
	$("#report_shift").change(function(){   
		$("#hidden_ashift").val($(this).find(":selected").text());  
	}); 
	$("#report_shift").trigger("change");      
	   
	$("#report_status").change(function(){   
		$("#hidden_astatus").val($(this).find(":selected").text());  
	}); 
	$("#report_status").trigger("change");
	
	$("#report_currency").change(function(){   
		$("#hidden_acurrency").val($(this).find(":selected").text());  
	}); 
	$("#report_currency").trigger("change");
	
	
	$("#validate .tip").tooltip ({placement: 'top'});
	  
	$("#validate .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation(); 
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	
	$('#datepicker1').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii:ss", 
		pickTime: true,
		//todayBtn: true,  
		todayHighlight: true/*,
		autoclose: true, 
		pickerPosition: "bottom-left"*/ 
		
	}); 
	
	$('select').select2({placeholder: "Select"});
	    
	
}); 
 

</script> 