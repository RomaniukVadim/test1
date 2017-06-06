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
    <input type="hidden" value="<?=($activity->ActivityID)?"update":"add";?>" name="hidden_action" id="hidden_action" > 
      
    <!-- CSA Form -->
    <div id="CsaContent" class="tab2-content"  > 
     	
        <div class="control-group" >
        	<label class="control-label" for="act_actualdateuploaded">* Call Date</label>
            <div class="controls controls-row">  
            
                    <div id="datepicker1" class="input-append datepicker" > 
                         <span class="add-on">
                            <i class="icon16"></i>
                        </span>
                        <input type="text" value="" name="act_actualdateuploaded" id="act_actualdateuploaded" data-format="yyyy-MM-dd" readonly="readonly"  class="myselect tip call-fieldimportant" title="Select call date"  >
                    </div>  
                 
            </div> 
        </div>
        
        <div class="control-group" >  
           <label class="control-label" for="act_currency">* Currency</label>
           <div class="controls controls-row">  
                <select name="act_currency" id="act_currency" class="required select2 myselect" > 
                    <optgroup label="Select Currency"> 
                        <option value="" <?php if($activity->Currency=="") echo "selected='selected'";?> ></option>
                        <?php
                        foreach($currencies as $row => $currency){ 
                            ?>
                        <option value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $activity->Currency) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
                            <?php 
                            }
                        ?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_acurrency" id="hidden_acurrency" value="" />
            </div>      
             
        </div>
        <!-- End .control-group --> 
         
        
        <?php /*?><div class="control-group" >  
            <label class="control-label" for="act_product">* Product</label>
            <div class="controls controls-row"  >
                <select name="act_product" id="act_product" class="required select2 myselect" > 
                    <optgroup label="Select Product"> 
                        <option value="" <?php if($activity->Product=="") echo "selected='selected'";?> >-- All --</option>
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
            </div>     
        </div>
        <!-- End .control-group --><?php */?>
        
        
        <div class="control-group" >  
             <label class="control-label" for="act_category">* Category</label>
             <div class="controls controls-row"> 
                <select name="act_category" id="act_category" class="required select2 myselect" > 
                    <optgroup label="Select Category"> 
                        <option value="" <?php if($activity->Category=="") echo "selected='selected'";?> ></option>
                        <?php 
                        $activity->CategoryID = ($activity->CategoryID > 0)?$activity->CategoryID:$activity->PromotionCategoryID;  
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
        <!-- End .control-group --> 
                         
        <div class="control-group" >  
            <label class="control-label" for="act_promotion">* Promotion</label>
            <div class="controls controls-row">  
                <select name="act_promotion" id="act_promotion" class="required select2 myselect" disabled="disabled" > 
                    <optgroup label="Select Promotion"> 
                        <option value=""  <?php if($activity->Promotion=="" || $activity->Promotion==0) echo "selected='selected'";?>  formula="" wageringformula=""  minimum="" maximum="" turnover="" bonusrate="" type="" ></option>  
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
                
            </div>
        </div>
        <!-- End .control-group -->  
        
        <div class="control-group" >  
           <label class="control-label" for="act_assignee">* Assignee</label>
           <div class="controls controls-row">  
                <select name="act_assignee" id="act_assignee" class="required select2 myselect" > 
                    <optgroup label="Select Assignee"> 
                        <option value=""  ></option>  
						<?php
                        foreach($utypes as $row =>$type){ 
							if(in_array($type->GroupID, $upload_assignee))
							 {
                            ?>
                        <option value="<?=$type->GroupID;?>"  ><?=$type->Name;?></option>	 		
                            <?php 
							 }
                         }
                        ?>
                         
                    </optgroup>  
                    
                </select> 
                <input type="hidden" name="hidden_aassignee" id="hidden_aassignee" value="" />
            </div>      
             
        </div>
        <!-- End .control-group --> 
        
        <div class="control-group" > 
            <label class="control-label" for="btn_addbanner">Attach File</label>
            <div class="controls controls-row"  id="AttachmentLoader"  >   
                <input type="file" name="act_attachfile" id="act_attachfile" value=""  />  
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
var uploadPromotionalActivities = function() { 
	 
	$.ajax({ 
		data: new FormData($("#validate")[0]), 
		type:"POST",  
		url: "<?=base_url();?>promotions_uploaded/uploadPromotionalActivities", 
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
		 	uploadPromotionalActivities();//check duplicate username 
		},
		invalidHandler: function(event, validator) { 
			//createMessageMini($(".form-widget-content"), "Please check all errors.", "error"); 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			act_actualdateuploaded: {
				required: true 
			}, 
			act_currency: {
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
			act_assignee: {
				required: true 
			}
			/*act_attachfile: {
			    required: true,
			    extension: "csv"
			}*/
		},
		messages: { 
			act_actualdateuploaded: {
				required: "Select call date" 
			},
			act_currency: {
				required: "Provide currency" 
			}, 
			/*act_product: {
				required: "Select product" 
			}, */
			act_category: {
				required: "Select category" 
			}, 
			act_promotion: {
				required: "Select promotion" 
			}, 
			act_assignee: {
				required: "Select assignee" 
			} 
			/*act_attachfile: {
			    required: "Select file to upload",
			    extension: "Select CSV file type"
			}*/			
		}
	}); 
	  
	//------------- Form validation -------------//
	  
	$("#act_currency").change(function(){    
		var currency = ($(this).val())?$(this).val():""; 
		var product = ($("#act_product").val())?$("#act_product").val():"";  
		var category = ($("#act_category").val())?$("#act_category").val():"";  
		
		$("#hidden_acurrency").val($(this).find(":selected").text()); 
		changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
	}); 
	$("#act_currency").trigger("change");   
	
	<?php /*?>$("#act_product").change(function(){   
		 var product = ($(this).val())?$(this).val():""; 
		 var currency = ($("#act_currency").val())?$("#act_currency").val():"";  
		 var category = ($("#act_category").val())?$("#act_category").val():""; 
		 $("#hidden_aproduct").val($(this).find(":selected").text());   
		 changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
	});
	//$("#act_product").trigger("change"); <?php */?> 
	
	$("#act_category").change(function(){   
		var currency = ($("#act_currency").val())?$("#act_currency").val():"";
		var product = ($("#act_product").val())?$("#act_product").val():"";
		var category = ($(this).val())?$(this).val():"";
		$("#hidden_acategory").val($(this).find(":selected").text()); 
		changePromotions("<?=base_url()?>promotions/getPromotionsList", product, currency, '<?=$activity->Promotion?>', $("select[name=act_promotion]"), '', category); 
	}); 
	   
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation(); 
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	 
	
	//promotion change 
	$("#act_promotion").change(function(){  
		
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
			
			$("#PromoDetails").html("Bonus Name: <b> " + promotion +"</b><br>Minimum Amount: " + minimum + "<br>Maximum Amount: " + maximum + "<br>Turnover: " + turnover + "<br>Bonus Rate: " + bonusrate + "%" );
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
			$(".cb_amount").show();
		 }
	  
	});
	//$("#act_promotion").trigger("change"); 
	
	
	$('#datepicker1').datetimepicker({    
		format: "yyyy-MM-dd",  
		pickTime: false,  
		//todayBtn: true,  
		todayHighlight: true/*, 
		autoclose: true, 
		pickerPosition: "bottom-left"*/ 
		
	});
	 
	 
	$('#validate select').select2({placeholder: "Select"});
	/*$('#act_currency').select2({placeholder: "Select"}); */   
	
}); 
 

</script> 