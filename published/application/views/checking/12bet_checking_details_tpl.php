<?php /*?><link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script><?php */?>
  
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

<div class="row-fluid form-widget-content" id="ActivityDetails" >   

<!-- form -->
<form id="validate_check" name="validate_check" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_CheckID" id="hidden_CheckID" value="<?=$check->CheckID;?>" >
    <input type="hidden" value="<?=($check->CheckID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
  
    <div class="control-group" >   
        <div class="span6" > 
            <label class="control-label" for="act_updatedx">Date Updated</label> 
            <div class="controls controls-row"   >
                <label class="info-label" for="act_updated" >
					<?=date("F d, Y", $check->DateCheckedInt)?> &nbsp;
                	<i class="icon16 i-clock-6 orange"></i> <?=date("H:i:s", $check->DateCheckedInt)?>
                </label>
            </div>
        </div> 
        
        <div class="span6" >
            <label class="control-label" for="act_updatedbyx">Checked By</label> 
            <div class="controls controls-row"   >
                <label class="info-label highlight-detail" for="act_updatedby" ><?=strtolower($check->UpdatedByNickname)?></label>
            </div>
        </div>
         
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
        <label class="control-label" for="act_currencyx">Category</label> 
        <div class="controls controls-row"   >
            <label class="info-label" for="act_currency" ><?="<b>".$check->Abbreviation ."</b> (".$check->CategoryName.")"?></label>
        </div> 
    </div>
    <!-- End .control-group -->
    
    <?php /*?><div class="control-group" >   
        <label class="control-label" for="act_urls">Checked</label> 
        <div class="controls controls-row"   >
        <?php  
		if($check->Checked)
		 {
			$urls = explode(',', $check->Checked);  
			foreach($urls as $row=>$url){
		?> 
        <label class="radio-inline url_list" ><span class="tip" title="<?=$url?>" ><i class="icon12 i-checkmark-3 green"></i> <?=$url?></span> </label>
        <?php	
			}//end foreach
		 }
		?>
            
        </div> 
    </div>
    <!-- End .control-group --><?php */?>
    
    <div class="control-group" >   
        <label class="control-label" for="act_urls">Checked</label> 
        <div class="controls controls-row"   >
        <?php   
		$urls = explode(',', $check->Urls);  
		foreach($check_list as $row=>$list){
			$icon_color = (in_array($list->UrlID, $urls))?" i-checkmark-3 green ":"i-close act-danger";
		?> 
        <label class="radio-inline url_list" ><span class="tip" title="<?=$list->UrlName?>" ><i class="icon12 <?=$icon_color?>"></i> <?=$list->UrlName?></span> </label>
        <?php	
		}//end foreach
		?> 
        </div> 
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >   
        <label class="control-label" for="act_remarksx">Remarks</label> 
        <div class="controls controls-row"   >
            <label class="info-label" for="act_remarks" ><?=htmlentities(stripslashes($check->Remarks))?></label>
        </div> 
    </div>
    <!-- End .control-group -->
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
function exportCheck12Bet(){
	xhr = $.ajax({ 
		data: $("#search_form").serialize(),
		type:"POST",  
		dataType: "JSON",
		url: "<?=base_url();?>check_12bet/exportCheck12Bet",  
		beforeSend:function(){   
			//show loading  
		 	exportLoading("show");   	
			
		},
		success:function(newdata){   
			//alert(JSON.stringify(newdata));
			exportLoading("hide"); 
			if(newdata.success == 1)
			 {
				 window.location = "<?=base_url();?>download/"+newdata.download_link;
			 }
			else
			 {
				 
			 }
				  
		}
			
	}); //end ajax	
} 
 
</script>

<script> 
var formdata; //for upload 
var to_upload = 0; 
var upload_error = 0; 
var selected_lang = "";  

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
	
	$(".tip").tooltip ({placement: 'top'}); 
	 
	<?php /*?>$("#validate_check").validate({
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
	}); <?php */?>
	
	
	//------------- Form validation -------------//
	<?php /*?>$("#act_methodtype").change(function(){   
		//$("#MethodTd").find("td_loading").remove(); 
		//$("#MethodTd").append('<div class="td_loading" ></div>'); 
		$("#hidden_amethodtype").val($(this).find(":selected").text());
		changeActivityMethods("<?=base_url()?>banks/getActivityMethods", $(this).val(), "<?=$check->CategoryID;?>", $("#act_method"));
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
		changeDepositMethods("<?=base_url()?>banks/getDepositMethods", $(this).val(), "<?=$check->DepositMethodID;?>", $("#act_depmethod"));
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
	displayUploaded("<?=$check->CheckID?>", "deposit_withdrawal");  
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_check .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); <?php */?> 
 
	$(".btn_export").click(function(){
		exportCheck12Bet();
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
	 
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
}); 
 

</script> 