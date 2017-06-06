<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
  
<?php /*?><link href="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet"/>  
<script src="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.js"></script><?php */?>

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
<form id="validate" name="validate" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post" enctype="multipart/form-data" onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
      
    <!-- CHECKING Form -->
    <div id="CheckingContent" class="tab2-content"  > 
     
        <div class="control-group" >  
           
           <div class="span6" >   
           
               <label class="control-label" for="check_currency">* Currency</label>
               <div class="controls controls-row">  
                    <select name="check_currency" id="check_currency" class="required select2 myselect" > 
                        <optgroup label="Select Currency"> 
                            <option value="" <?php if($check->Currency=="") echo "selected='selected'";?> ></option>
                            <?php
                            foreach($currencies as $row => $currency){ 
                                ?>
                            <option value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $check->Currency) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
                                <?php 
                                }
                            ?>
                             
                        </optgroup>  
                        
                    </select> 
                    <input type="hidden" name="hidden_acurrency" id="hidden_acurrency" value="" />
                </div> 
             
            </div>    
            
            <div class="span6" >   
            	
                <label class="control-label" for="check_category">* Category</label>
                <div class="controls controls-row"   >
                    <select class="select2 myselect" name="check_category" id="check_category"  >
                        <optgroup label="" >    
                            <option value="" >- All Category -</option> 
                            <?php
                            foreach($checking_categories as $row=>$category) {
                            ?>
                            <option value="<?=$category->CategoryID?>" <?=($category->CategoryID==$check->CategoryID)?"selected='selected'":""?> ><?=$category->CategoryName?></option>
                            <?php	
                            }//end foreach
                            ?> 
                        </optgroup> 
                    </select> 
                    <input type="hidden" name="hidden_acategory" id="hidden_acategory" value="" />
                </div>  
                   
            </div>     
             
        </div>
        <!-- End .control-group --> 
        
        <div class="control-group" >   
             <label class="control-label" for="check_categoryx">* Checklist</label>
             <div class="controls controls-row" id="ChecklistDetails"  >
             	 <label class="info-label" for="check_category" >- - - - Select currency and category  - - - - </label> 
             </div> 
             <div class="controls controls-row" id="ChecklistDetails"  >
             	<input type="hidden" name="hidden_achecklist" id="hidden_achecklist" value="" /> 
             </div> 
             
        </div>
        <!-- End .control-group --> 
        
        <div class="control-group" > 
            <label class="control-label" for="check_remarks"> Remarks</label>
            <div class="controls controls-row" >  
                <textarea id="check_remarks" name="check_remarks" class="tip span12" rows="3"  maxlength="500" title="Enter remarks"  ></textarea>
            </div>
        </div>
        <!-- End .control-group --> 
        
    </div>
    <!-- END CHECKING Form -->
     
    
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
var selected_values = "";
var getCheckList = function() {  
	$.ajax({ 
		data: $("#validate").serialize(), 
		type:"POST",  
		url: "<?=base_url()?>check_12bet/getCheckList", 
		dataType: "JSON",   
		cache: false,
		beforeSend:function(){       
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
		},
		success:function(newdata){     
		  
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled"); 
			var new_string = "";
			if(newdata.success > 0)
			 {  
			 	if(newdata.checklist.length > 1) new_string += "<label class=\"radio-inline group-label url_list\" ><input type=\"checkbox\" name=\"checkbox_all\" value=\"1\" > &nbsp; <span class=\"tip\" title=\"Select all\" ><b>All</b></span></label>";
				
				$.each(newdata.checklist, function( index, value ) {   
					//selected = (default_result == value.mb_no)?'selected="selected"':'';
					//new_string = '<option value="'+value.mb_no+'" '+selected+'>'+value.mb_nick+'</option>';  
					//container.append(new_string); 
					if(value.UrlID)new_string += "<label class=\"radio-inline group-label url_list\" ><input type=\"checkbox\" name=\"check_item[]\" value=\""+value.UrlID+"\" > &nbsp; <span class=\"tip\" title=\""+value.UrlName+"\">"+value.UrlName+"</span></label>";
				});
				
				$("#ChecklistDetails").html(new_string);  
				
				$("input:checkbox[name=checkbox_all]").removeAttr("checked"); 
				$.uniform.update("input:checkbox[name=checkbox_all]");
			
				$("input:checkbox[name='check_item[]']").click(function(){  
					  selected_values = $("input[name='check_item[]']:checked").map(function() {return this.value;}).get().join(',');    
					  if(!$(this).is(':checked')) 
					  {  
					  	
						 $("input:checkbox[name=checkbox_all]").removeAttr("checked");  
						 $.uniform.update("input:checkbox[name=checkbox_all]");	 
					  }
					  
					  $("div.alert").remove();
					  $("#hidden_achecklist").val(selected_values);
					  
				 });  
				 
				 $("input:checkbox[name='checkbox_all']").click(function () {   
					 
					if ($("input:checkbox[name='checkbox_all']").is(':checked')) {
						$("input:checkbox[name='check_item[]']").prop("checked", true); 
					} else {
						$("input:checkbox[name='check_item[]']").prop("checked", false); 
					}
					//selected_values = $('input[name="\'check_item[]\'"]:checked').map(function() {return this.value;}).get().join(',');  
					selected_values = $("input[name='check_item[]']:checked").map(function() {return this.value;}).get().join(','); 
					 
					$.uniform.update("input[type=checkbox]"); 
					$("div.alert").remove(); 
					
					$("#hidden_achecklist").val(selected_values);
						
				});
				 
				 $('input[type=checkbox]').uniform(); 
				 $(".tip").tooltip ({placement: 'top'}); 
				
			 } 
			else
			 {
				createMessageMini($(".form-widget-content"), newdata.message, "error");  
			 } 
			  
		}
		 
	}); //end ajax
	
	//e.preventDefault();
	  
} 

 
var manage12betChecking = function() { 
	 
	$.ajax({ 
		data: $("#validate").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>check_12bet/manage12betChecking", 
		dataType: "JSON",  
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
		 	manage12betChecking();//check duplicate username 
		},
		invalidHandler: function(event, validator) { 
			//createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			check_currency: {
				required: true 
			}, 
			hidden_achecklist: {
				required: true	
			},
			check_category: {
				required: true 
			} 
		},
		messages: { 
			check_currency: {
				required: "Select currency" 
			},  
			hidden_achecklist: {
				required: "Select checklist"	
			},
			check_category: {
				required: "Select category" 
			}  	 
				
		}
	}); 
	  
	//------------- Form validation -------------//
	$('#validate select').select2({placeholder: "Select"});
	/*$('#check_currency').select2({placeholder: "Select"}); */
	   
	 
	$("#check_currency").change(function(){    
		var currency = $(this).val();
		var category = $("#check_category").val();   
		if(currency && category) getCheckList();  
	}); 
	$("#check_currency").trigger("change");   
	
	$("#check_category").change(function(){  
		 var category = $(this).val();
		 var currency = $("#check_currency").val()    
		 if(currency && category) getCheckList();     
	});
	//$("#check_category").trigger("change");   
	   
	
	$("#validate .tip").tooltip ({placement: 'top'});
	  
	$("#validate .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation(); 
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	    
	
}); 
 

</script> 