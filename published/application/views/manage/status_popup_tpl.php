<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 110px !important; 
}

.form-horizontal .controls {
    margin-left: 130px;
}

.group-label {
	width: 23% !important; 	 
	margin-bottom: 3px;
} 

.checkbox label.error { 
	width: 90% !important;  	
}

</style> 

<!-- color picker -->
<link rel="stylesheet" href="<?=base_url();?>media/js/plugins/forms/colorpicker/spectrum.css" />
<script src="<?=base_url();?>media/js/plugins/forms/colorpicker/spectrum.js"></script>  
<style>
.sp-container {
	z-index: 999999; 	
	position: fixed !important;
}

.sp-container input.sp-input {    
	font-size: 9pt !important;  
	z-index: 999999999;  
	padding-top: 5px;  
	height: 28px !important; 
	width: 80%;
}/*sp-input-container sp-cf*/

.sp-initial span {
	height: 34px !important;	
}

.sp-input-container {
	width: 50% !important;
} 
 
.sp-container .sp-cf{
	 
}

</style>
<!-- end color picker -->

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_status" name="validate_status" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_astatusid" id="hidden_astatusid" value="<?=$status->StatusID;?>" >
    <input type="hidden" value="<?=($status->StatusID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 
    <div class="control-group" >   
    	<div class="span6" >
        	<label class="control-label" for="status_name">* Status Name</label>
            <div class="controls controls-row"  >
                <input type="text" id="status_name" name="status_name" class="tip span12" value="<?=htmlentities(stripslashes($status->Name));?>" maxlength="80" title="Status name" >    
            </div>
        </div>
        
        <div class="span6" >
        	<label class="control-label" for="status_color">Color</label>
            <div class="controls controls-row"  >
                <input type="text" id="status_color" name="status_color" class="tip span12" value="<?=($status->Color)?$status->Color:"#000000"?>" maxlength="80" title="Status color" >    
            </div>
        </div> 
    </div>
    <!-- End .control-group -->  
    
    <div class="control-group" > 
        <label class="control-label" for="status_users">* Users</label>
        <div class="controls controls-row checkbox" >  
            <?php
			$user_arr = explode(',', $status->Users);
			foreach($user_types as $row=>$type){
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="status_user[]" value="<?=$type->GroupID?>"  <?=(in_array($type->GroupID, $user_arr))?'checked="checked"':"";?> > <?=$type->UserTypeName?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="status_users" id="status_users" value=""  /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <label class="control-label" for="status_view">* Viewers</label>
        <div class="controls controls-row checkbox" >  
            <?php
			$viewer_arr = explode(',', $status->Viewers);
			foreach($user_types as $row=>$type){
			?>
            <label class="radio-inline group-label" >
                <input type="checkbox" name="status_viewer[]" value="<?=$type->GroupID?>"  <?=(in_array($type->GroupID, $viewer_arr))?'checked="checked"':"";?> > <?=$type->UserTypeName?>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="status_viewers" id="status_viewers" value=""  /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <label class="control-label" for="status_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="status_desc" name="status_desc" class="span12 tip" rows="3" maxlength="200" title="Status description" ><?=$status->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group --> 
       
       
    <div class="control-group" >  
    	<div class="span6" >
        	<label class="control-label" for="status_status">* Status</label>
            <div class="controls controls-row">  
                <select name="status_status" id="status_status" class="required myselect" > 
                    <optgroup label="" >    
                         <option value="" <?php if($status->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                        <?php
                        foreach($status_list as $row => $stat){  
                         
                        ?>
                        <option  value="<?=$stat[Value];?>" <?php if($status->Status==$stat[Value]) echo "selected='selected'";?> ><?=ucwords($stat[Label]);?></option>
                        <?php
                        }
                        ?>
                    </optgroup> 
                </select>     
                <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" /> 
            </div>
        </div>
        
        <div class="span6" >
        	<label class="radio-inline act-danger" >
                <input type="checkbox" value="1" name="status_ishighlight" id="status_ishighlight" style="margin-bottom: 5px;" <?=($status->IsHighlight==1)?"checked='checked'":"";?> /> Highlight 
            </label>
        </div>
        
        
    </div>
    <!-- End .control-group --> 
    
    <div class="form-actions"> 
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($status->StatusID)?"Update ":"Save new "?> status</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var manageStatus = function() { 
	 
	$.ajax({ 
		data: $("#validate_status").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>status/manageStatus", 
		dataType: "JSON",    
		cache: false,
		beforeSend:function(){       
			//$("#BtnSubmitForm").addClass("disabled");  
			//$("#BtnSubmitForm").attr("disabled", "disabled");    
			$("html, body").animate({ scrollTop: 0 }, "slow"); 
		},
		success:function(msg){     
			//$("#BtnSubmitForm").removeClass("disabled"); 
			//$("#BtnSubmitForm").removeAttr("disabled", "disabled");
			
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
	
	//e.preventDefault();
	  
} 
 
</script>

<script> 
$(function() {  
	
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate_status").validate({
		 submitHandler: function(form) { 
		 	manageStatus();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			status_name: {
				required: true, 
				minlength: 2
			}, 
			status_users: {
				required: true 
			},
			status_viewers: {
				required: true 
			}, 
			status_status: {
				required: true 
			} 
		},
		messages: { 
		   status_name: {
				required: "Please enter result name" 
			},   
		   status_users: {
				required: "Please select atleast one user" 
			}, 
		   status_viewers: {
				required: "Please select atleast one viewer" 
			}, 
		   status_status: {
				required: "Select status" 
			}
		}
	}); 
	   
	
	$("#status_result").change(function(){ 
		$("#hidden_aresult").val($(this).find(":selected").text());
	});
	$("#status_result").trigger("change");
	
	$("#status_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#status_status").trigger("change");
	
	
	//clicking checkbox viewer 
	 var viewers = $("input[name='status_viewer[]']:checked").map(function() {return this.value;}).get().join(','); 
	$("input:checkbox[name='status_viewer[]']").click(function(){ 
		 viewers = $("input[name='status_viewer[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#status_viewers").val(viewers);   
	});
	$("#status_viewers").val(viewers);  
	
	//clicking checkbox user
	var users = $("input[name='status_user[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("input:checkbox[name='status_user[]']").click(function(){ 
		 users = $("input[name='status_user[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#status_users").val(users);   
	});
	$("#status_users").val(users);   
	
	$('input[type=checkbox]').uniform();
	
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_status .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
	//------------- Color picker -------------//
   $("#status_color").spectrum({
   	 	preferredFormat: "hex6",
	    //color: "#000000", //declared inline
	    showInput: true,
	    showInitial: true,	 
	    clickoutFiresChange: true,
		allowEmpty:true,
	    //chooseText: "Select",
    	//cancelText: "Close" 
		//flat:true 
		//flat: true
		move: function(tinycolor) { },
		show: function(tinycolor) {},
		hide: function(tinycolor) {
				//tinycolor.toHex()
			},
		beforeShow: function(tinycolor) {},
	});
	
}); 
 

</script> 