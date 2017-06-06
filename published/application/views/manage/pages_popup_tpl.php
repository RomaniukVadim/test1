<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
 
<style>
.datepicker table thead {
    border-left: 1px solid #262626;
    border-right: 1px solid #262626;
    border-top: 1px solid #262626;
}

.form-horizontal .control-label { 
    width: 140px !important; 
}

.form-horizontal .controls {
    margin-left: 160px;
}
 
.group-label {
	width: 30% !important; 	 
	margin-bottom: 3px; 
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;  
} 

.checkbox label.error { 
	width: 90% !important;  	
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_source" name="validate_source" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post"  onsubmit="return false; " >  
    <input type="hidden" name="hidden_apageid" id="hidden_apageid" value="<?=$page->PageID;?>" >
    <input type="hidden" value="<?=($page->PageID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
 	 
    <div class="control-group" >   
        
        <div class="span6" >
        	<label class="control-label" for="page_name">* Page Name</label>
            <div class="controls controls-row"  >
                <input type="text" id="page_name" name="page_name" class="tip span12" value="<?=htmlentities(stripslashes($page->Name));?>" maxlength="80" title="Source name" >    
            </div>
        </div> 
        
        <div class="span6" >
        	<label class="control-label" for="page_controller">* Controller</label>
            <div class="controls controls-row"  >
                <input type="text" id="page_controller" name="page_controller" class="tip span12" value="<?=htmlentities(stripslashes($page->FileUsed));?>" maxlength="80" title="Controller" >    
            </div>
        </div>
        
        
    </div>
    <!-- End .control-group -->  
      
    <div class="control-group" > 
        <label class="control-label" for="page_statuslist">* Status List</label>
        <div class="controls controls-row checkbox" >  
            <?php
			$statuslist_arr = explode(',', $page->StatusList);
			foreach($stats as $row=>$stat){
			?>
            <label class="radio-inline group-label tip"  title="<?=$stat->Name?>" >
                <input type="checkbox" name="page_stat[]" value="<?=$stat->StatusID?>"  <?=(in_array($stat->StatusID, $statuslist_arr))?'checked="checked"':"";?> > <span ><?=$stat->Name?></span>
            </label> 
            <?php	
			}//end foreach
			?> 
            <br />
            <input type="hidden" name="page_stats" id="page_stats" value=""  /> 
        </div>
    </div>
    <!-- End .control-group -->
    
    <div class="control-group" > 
        <label class="control-label" for="page_desc">Description</label>
        <div class="controls controls-row" >  
            <textarea id="page_desc" name="page_desc" class="span12 tip" rows="2" maxlength="200" title="Source description" ><?=$page->Description?></textarea>
        </div>
    </div>
    <!-- End .control-group --> 
    
     
    <div class="control-group" >  
        <label class="control-label" for="page_status">* Status</label>
        <div class="controls controls-row">  
            <select name="page_status" id="page_status" class="required myselect" > 
                <optgroup label="" >    
                     <option value="" <?php if($page->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                    <?php
                    foreach($status_list as $row => $status){  
					 
                    ?>
                    <option  value="<?=$status[Value];?>" <?php if($page->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
					<?php
                    }
                    ?>
                </optgroup> 
            </select>     
            <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" /> 
        </div>
    </div>
    <!-- End .control-group --> 
    
    <div class="form-actions"> 
        <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($page->PageID)?"Update ":"Save new "?> page</button>
        &nbsp;&nbsp;
        <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
    </div> 
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var managePage = function() { 
	 
	$.ajax({ 
		data: $("#validate_source").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>pages/managePage", 
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
				//$('#validate_source')[0].reset();
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
	 
	$("#validate_source").validate({
		 submitHandler: function(form) { 
		 	managePage();//
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			page_name: {
				required: true, 
				minlength: 3
			},  
			page_controller: {
				required: true 
			},
			page_stats: {
				required: true 
			},
			page_status: {
				required: true 
			} 
		},
		messages: { 
		   page_name: {
				required: "Please enter page name" 
			}, 
		   page_controller: {
				required: "Please enter controller" 
			},   
		   page_stats: {
				required: "Select atleast one status" 
			},
		   page_status: {
				required: "Select status" 
			}
		}
	}); 
	    
	
	$("#page_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#page_status").trigger("change");
	
	//clicking page_stat
	var status = $("input[name='page_stat[]']:checked").map(function() {return this.value;}).get().join(',');  
	$("input:checkbox[name='page_stat[]']").click(function(){ 
		 status = $("input[name='page_stat[]']:checked").map(function() {return this.value;}).get().join(',');  
		 $("div.alert").remove();
		 $("#page_stats").val(status);    
	});
	$("#page_stats").val(status); 
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_source .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	}); 
	
}); 
 

</script> 