<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
  
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet"/>  
<script src="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.js"></script>

<style>
.group-label {
	width: 15% !important; 	 
	margin-bottom: 5px;
} 

.currency label.error { 
	width: 90% !important;  	
}

.uploader .error {
	position: absolute; 
	right: -100px !important;  	
}
</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate" name="validate" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post" enctype="multipart/form-data" onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
      
    <div class="control-group" >   
        <label class="control-label" for="url_cur">Market</label>
        <div class="controls controls-row currency">
            <?php 
            $cur_arr = explode(',', $url->Currencies);
            foreach($currencies as $row=>$currency){  
            ?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="url_cur[]" value="<?=$currency->CurrencyID?>"  <?=(in_array($currency->CurrencyID, $cur_arr))?'checked="checked"':"";?> > <?=$currency->Abbreviation?>
            </label> 
            <?php	
            }//end foreach
            ?> 
            <br />
            <input type="hidden" name="url_currencies" id="url_currencies" value=""  /> 
        </div>
    </div>
    <!-- End .control-group -->
        
    <div class="control-group" > 
        <label class="control-label" for="btn_addbanner">Attach File</label>
        <div class="controls controls-row"  id="AttachmentLoader"  >   
            <input type="file" name="url_attachfile" id="url_attachfile" value=""  />  
        </div> 
         
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" >  
        <label class="control-label" for="url_status">* Status</label>
        <div class="controls controls-row">  
            <select name="url_status" id="url_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($url->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php  
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($def_stat==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
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
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" >Submit</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->


<!-- Init plugins only for page --> 
<script >
var uploadBackupUrl = function() { 
	 
	$.ajax({ 
		data: new FormData($("#validate")[0]), 
		type:"POST",  
		url: "<?=base_url();?>backup_url/uploadBackupUrl", 
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
				$('#validate')[0].reset();
				clearSelectbox($("div.controls"));  
				$("ul.select2-choices li.select2-search-choice").remove();  
				$.uniform.update("input[type=checkbox], input[type=radio]"); 
				 
			 } 
			else
			 {
				createMessageMini($(".form-widget-content"), msg.message, "error"); 
			 } 
			
			$(".uploader").find(".filename").text("No file selected"); 
			$("input[name^=url_attachfile]").val(""); 
			  
		}
		 
	}); //end ajax
	
	//e.preventDefault();
	  
} 

</script>

<script>   
var selected_values = "";

$(function() {  
	//$.fn.modal.Constructor.prototype.enforceFocus = function() {};
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate").validate({
		 submitHandler: function(form) { 
		 	uploadBackupUrl();//check duplicate username 
		},
		invalidHandler: function(event, validator) { 
			createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			url_status: {
				required: true 
			}/*, 
			url_attachfile: {
			    required: true,
			    extension: "csv"
			}*/
		},
		messages: { 
			url_status: {
				required: "Select status" 
			}/*, 
			url_attachfile: {
			    required: "Select file to upload",
			    extension: "Select CSV file type"
			}*/			
		}
	}); 
	   
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation(); 
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	
	
	$("input:checkbox[name='url_cur[]']").click(function(){ 
		 selected_values = $("input[name='url_cur[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#url_currencies").val(selected_values);   
	}); 
	selected_values = $("input[name='url_cur[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("#url_currencies").val(selected_values);
	
	  
	$('select').select2({placeholder: "Select"});    
	
}); 
 

</script> 