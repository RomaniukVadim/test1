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
/*.wstep {
	cursor: pointer; 	
}*/
</style>

<div class="row-fluid form-widget-content"  >   

<!-- form -->
<form id="validate" name="validate" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post" enctype="multipart/form-data" onsubmit="return false; " >  
    <input type="hidden" name="hidden_activityid" id="hidden_activityid" value="<?=$activity->ActivityID;?>" >
    <input type="hidden" value="<?=($post[s_action] == "deposited")?"deposited":"clamied";?>" name="hidden_action" id="hidden_action" > 
    
    <input type="hidden" value="<?=trim($post[s_fromdate])?>" name="act_fromdate" id="act_fromdate" > 
    <input type="hidden" value="<?=trim($post[s_todate])?>" name="act_todate" id="act_todate" >
    <input type="hidden" value="<?=trim($post[s_subproductid])?>" name="act_subproductid" id="act_subproductid" > 
      
    <!-- CSA Form -->
    <div id="CsaContent" class="tab2-content"  > 
           
        <div class="control-group" >  
             <label class="control-label" for="act_date">* Date</label>
             <div class="controls controls-row"> 
                <label class="info-label highlight-detail" for="act_date"><?=date("F d, Y", strtotime($post[s_fromdate]))." to ".date("F d, Y", strtotime($post[s_todate]))?></label>
             </div>     
        </div>
        <!-- End .control-group --> 
        
        <div class="control-group" >  
             <label class="control-label" for="act_category">* Category</label>
             <div class="controls controls-row"> 
                <select name="act_category" id="act_category" class="required select2 myselect"  > 
                    <optgroup label="Select Category"> 
                        <option value="" <?php if($post[s_categoryid]=="") echo "selected='selected'";?> ></option>
                        <?php 
                        //$activity->CategoryID = ($activity->CategoryID > 0)?$activity->CategoryID:$activity->PromotionCategoryID;   
						
                        foreach($categories as $row => $category){ 
                            ?>
                        <option  value="<?=$category->CategoryID;?>" <?php if($category->CategoryID == $post[s_categoryid]) echo "selected='selected'";?> ><?=$category->Name;?></option>	 		
                            <?php 
                            }
                        ?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_acategory" id="hidden_acategory" value="" />
             </div>     
        </div>
        <!-- End .control-group --> 
                   
          
        <div class="control-group" > 
            <label class="control-label" for="act_attachfile">* Attach File</label>
            <div class="controls controls-row"  id="AttachmentLoader"  >   
                <input type="file" name="act_attachfile" id="act_attachfile" value="" />  
            </div> 
             
        </div>
        <!-- End .control-group --> 
        
    </div>
    <!-- END  CSA Form -->
     
    
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
var uploadCrmRecord = function() { 
	 
	$.ajax({ 
		data: new FormData($("#validate")[0]), 
		type:"POST",  
		url: "<?=base_url();?>crm_conversions/uploadCrmRecord", 
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
				//$('#validate')[0].reset();
				clearSelectbox($("div.controls"));  
				$("ul.select2-choices li.select2-search-choice").remove();  
				$.uniform.update("input[type=checkbox], input[type=radio]"); 
				 
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

 

</script>

<script>  
$(function() {  
	//$.fn.modal.Constructor.prototype.enforceFocus = function() {};
	
 	$("[type='file']").not('.toggle, .select2, .multiselect').uniform();
	
	$("[type='radio'], [type='checkbox']").uniform();
	//$.uniform.update("input:radio[name=act_idreceived]"); 
	 
	$("#validate").validate({
		 submitHandler: function(form) { 
		 	uploadCrmRecord();//check duplicate username 
		},
		invalidHandler: function(event, validator) { 
			//createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			act_category: {
				required: true 
			}
		},
		messages: { 
			act_category: {
				required: "Select category" 
			}		
		}
	}); 
	  
	//------------- Form validation -------------//
	$('select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */
	   
	 
	<?php /*?>$("#act_currency").change(function(){    
		var currency = ($(this).val())?$(this).val():""; 
		var product = ($("#act_product").val())?$("#act_product").val():"";  
		var category = ($("#act_category").val())?$("#act_category").val():"";  
		
		$("#hidden_acurrency").val($(this).find(":selected").text()); 
		changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
	}); 
	$("#act_currency").trigger("change");<?php */?>   
	
	<?php /*?>$("#act_product").change(function(){   
		 var product = ($(this).val())?$(this).val():""; 
		 var currency = ($("#act_currency").val())?$("#act_currency").val():"";  
		 var category = ($("#act_category").val())?$("#act_category").val():""; 
		 $("#hidden_aproduct").val($(this).find(":selected").text());   
		 changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
	});
	//$("#act_product").trigger("change"); <?php */?> 
	
	<?php /*?>$("#act_category").change(function(){   
		var currency = ($("#act_currency").val())?$("#act_currency").val():"";
		var product = ($("#act_product").val())?$("#act_product").val():"";
		var category = ($(this).val())?$(this).val():"";
		$("#hidden_acategory").val($(this).find(":selected").text()); 
		changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
	}); <?php */?>
	 
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation(); 
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	 
	 
	   
	
}); 
 

</script> 