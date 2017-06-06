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
    width: 130px !important; 
}

.form-horizontal .controls {
    margin-left: 150px;
}

.modal {
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.0);	
}
</style>

<div class="row-fluid form-widget-content"  >   
 
<!-- form -->  
<form id="validate_promotion" name="validate_promotion" class="form-horizontal"  autocomplete="off" style="margin: 0px; padding: 0px; "  method="post" onsubmit="return false; " >
    <input type="hidden" name="hidden_apromotionid" id="hidden_apromotionid" value="<?=$promotion->PromotionID;?>" >
    <input type="hidden" value="<?=($promotion->PromotionID)?"update":"add";?>" name="hidden_action" id="hidden_action" >  
    
    <input type="hidden" class="input-medium" name="promotion_formula" id="promotion_formula" value="<?=(htmlentities(stripslashes($promotion->Formula)))?htmlentities(stripslashes($promotion->Formula)):"((\$deposit_amt*\$bonus)<\$min_amt)?0:min((\$bonus*\$deposit_amt), \$max_amt)";?>" >
    <input type="hidden" class="input-medium" name="promotion_wageringformula" id="promotion_wageringformula" value="<?=(htmlentities(stripslashes($promotion->WageringFormula)))?htmlentities(stripslashes($promotion->WageringFormula)):"round((\$deposit_amt-(min(\$deposit_amt,\$bonus_amt/\$bonus)))+((min(\$deposit_amt,\$bonus_amt/\$bonus))+\$bonus_amt)*\$reqt)";?>" >
             
    <ul id="myTab" class="nav nav-tabs">
        <li class="active" ><a href="#PromotionDetails" data-toggle="tab" ><i class="icon14 i-clipboard-4"></i> Details</a></li>
        <li><a href="#PromotionFormula"  data-toggle="tab" ><i class="icon14 i-calculate"></i> Formula</a></li>  
        <li><a href="#PromotionTerms"  data-toggle="tab" ><i class="icon14 i-certificate"></i> Terms</a></li>  
    </ul>
    
    <div class="tab-content row-fluid" >  
    
        <!-- promotion details -->
        <div class="tab-pane active" id="PromotionDetails">  
        
            <div class="control-group" > 
            	<div class="span6"  >
                    <label class="control-label" for="pro_name">* Name</label>
                    <div class="controls controls-row">  
                        <input type="text" id="pro_name" name="pro_name" class="span12 tip" value="<?=htmlentities(stripslashes($promotion->Name));?>" maxlength="150" title="Enter promotion name" >
                    </div>
                </div>
                 
                <div class="span6" >
                	<label class="control-label" for="pro_product">* Product</label>
                    <div class="controls controls-row"  >
                        <select name="pro_product" id="pro_product" class="required  select2 span12" > 
                            <optgroup label="Select Product"> 
                                <option value="" <?php if($promotion->ProductID=="") echo "selected='selected'";?> ></option>
                                <?php
                                foreach($products as $row => $product){ 
                                    ?>
                                <option value="<?=$product->ProductID;?>" <?php if($product->ProductID == $promotion->ProductID) echo "selected='selected'";?> ><?=$product->ProductName;?></option>	 		
                                    <?php 
                                    }
                                ?>
                                 
                            </optgroup>  
                            
                        </select> 
                        <input type="hidden" name="hidden_aproduct" id="hidden_aproduct" value="" /> 
                    </div>
                </div>
                
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" style="padding-bottom: 10px !important;"  >   
            
            	<div class="span6" >
                	<label class="control-label" for="pro_category">* Category</label>
                    <div class="controls controls-row">  
                        <select name="pro_category" id="pro_category" class="required select2 span12" > 
                            <optgroup label="Select Category"> 
                                <option value="" <?php if($promotion->CategoryID=="") echo "selected='selected'";?> ></option>
                                <?php
                                foreach($categories as $row => $category){ 
                                    ?>
                                <option value="<?=$category->CategoryID;?>" <?php if($category->CategoryID == $promotion->CategoryID) echo "selected='selected'";?> ><?=$category->Name;?></option>	 		
                                    <?php 
                                    }
                                ?>
                                 
                            </optgroup>  
                            
                        </select> 
                        <input type="hidden" name="hidden_acategory" id="hidden_acategory" value="" />
                    </div>
                </div>
                
                <div class="span6" >
                	<label class="control-label" for="pro_subproduct">* Sub Product</label>
                    <div class="controls controls-row"  >
                        <select name="pro_subproduct" id="pro_subproduct" class="required  select2 span12" disabled="disabled" > 
                            <optgroup label="Select Sub Product"> 
                                <option value="" <?php if($promotion->SubProductID=="") echo "selected='selected'";?> ></option>
                                <?php /*?><?php
                                foreach($products as $row => $product){ 
                                    ?>
                                <option value="<?=$product->ProductID;?>" <?php if($product->ProductID == $promotion->SubProductID) echo "selected='selected'";?> ><?=$product->ProductName;?></option>	 		
                                    <?php 
                                    }
                                ?><?php */?>
                                 
                            </optgroup>  
                            
                        </select> 
                        <input type="hidden" name="hidden_aproduct" id="hidden_aproduct" value="" /> 
                    </div>
                </div>  
                 
            </div>
            <!-- End .control-group -->
            
            <div class="control-group" >  
                <div class="span6" > 
                    <label class="control-label" for="pro_bonuscode">* Bonus Code</label>
                    <div class="controls controls-row">  
                    	<input type="text" id="pro_bonuscode" name="pro_bonuscode" class="required span12 tip" value="<?=htmlentities(stripslashes($promotion->BonusCode));?>" maxlength="30" title="Enter bonus code" >    
                    </div>
                </div> 
                
                <div class="span6" >
                    <label class="control-label" for="pro_type">* Type</label>
                    <div class="controls controls-row"  > 
                        <select class="input-medium" name="pro_type" id="pro_type" >
                            <option value="promotion" <?php if($promotion->Type=="promotion") echo "selected='selected'";?> >Promotion</option>
                            <option value="cashback" <?php if($promotion->Type=="cashback") echo "selected='selected'";?> >Cashback</option> 
                        </select>
                    </div>
                </div>  
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" >  
                <div class="span6" > 
                    <label class="control-label" for="pro_startdate">* Start Date</label>
                    <div class="controls controls-row">
                        <div id="datepicker1" class="input-append datepicker"  > 
                             <span class="add-on">
                                <i class="icon16 "></i>
                            </span>
                            <input type="text" value="<?=($promotion->StartedDate != "0000-00-00" && $promotion->StartedDate != "")?$promotion->StartedDate:"";?>"  name="pro_startdate" id="pro_startdate" data-format="yyyy-MM-dd" readonly="readonly"  class="myselect required tip" title="Select date started" >
                        </div>
                    </div> 
                </div> 
                
                <div class="span6" >
                    <label class="control-label" for="pro_enddate">* End Date</label>
                    <div class="controls controls-row">
                        <div id="datepicker1" class="input-append datepicker"  > 
                             <span class="add-on">
                                <i class="icon16 "></i>
                            </span>
                            <input type="text" value="<?=($promotion->EndDate != "0000-00-00" && $promotion->EndDate != "")?$promotion->EndDate:"";?>"  name="pro_enddate" id="pro_enddate" data-format="yyyy-MM-dd" readonly="readonly"  class="myselect required tip" title="Select date end" >
                        </div>
                    </div> 
                </div>  
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" > 
                <label class="control-label" for="pro_description">Description</label>
                <div class="controls controls-row">  
                    <textarea name="pro_description" id="pro_description" class="span12 tip" rows="2"  maxlength="500"  ><?=htmlentities(stripslashes($promotion->Description));?></textarea>
                </div>
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" >  
                <div class="span6" > 
                    <label class="control-label" for="pro_currency">* Currency</label>
                    <div class="controls controls-row">  
                        <select name="pro_currency" id="pro_currency" class="required  select2 span12" > 
                            <optgroup label="Select Currency"> 
                                <option value="" <?php if($promotion->Currency=="") echo "selected='selected'";?> ></option>
                                <?php
                                foreach($currencies as $row => $currency){ 
                                    ?>
                                <option value="<?=$currency->CurrencyID;?>" <?php if($currency->CurrencyID == $promotion->CurrencyID) echo "selected='selected'";?> ><?=$currency->Abbreviation;?></option>	 		
                                    <?php 
                                    }
                                ?>
                                 
                            </optgroup>  
                            
                        </select> 
                        <input type="hidden" name="hidden_acurrency" id="hidden_acurrency" value="" />
                    </div>
                </div> 
                
                <div class="span6" >
                      
                    <label class="radio-inline act-danger tip" title="Display in Web Promotion"  >
                        <input type="checkbox" value="1" name="pro_webpromotion" id="pro_webpromotion"  <?=($promotion->IsWebPromotion==1)?"checked='checked'":"";?>  /> Display in Web Promotion 
                    </label>
                    
                </div>  
            </div>
            <!-- End .control-group -->  
            
            <div class="control-group" style="padding-bottom: 10px !important;" >  
                 
                <div class="span12" >
                    
                    <label class="control-label" for="act_spacexx"></label>   
                    
                    <label class="radio-inline tip" title="For CSD"  >
                        <input type="radio" value="1" name="pro_forusertype" id="pro_forusertype_1"  <?=($promotion->ForUserType == 1 || !isset($promotion->ForUserType) )?"checked='checked'":"";?>  /> For CSA  
                    </label>
                    
                    <label class="radio-inline tip" title="For CRM"  >
                        <input type="radio" value="10" name="pro_forusertype" id="pro_forusertype_10"  <?=($promotion->ForUserType==10)?"checked='checked'":"";?>  /> For CRM  
                    </label>
                </div>  
            </div>
            <!-- End .control-group -->
             
            <div class="control-group" >  
            	<div class="span6" >
                    <label class="control-label" for="pro_status">* Status</label>
                    <div class="controls controls-row">  
                        <select name="pro_status" id="pro_status" class="required span12" > 
                            <optgroup label="" >    
                                 <option value="" <?php if($promotion->Status=='') echo "selected='selected'";?> >&nbsp;</option>
                                <?php
                                foreach($status_list as $row => $status){  
                                 
                                ?>
                                <option  value="<?=$status[Value];?>" <?php if($promotion->Status==$status[Value]) echo "selected='selected'";?> ><?=ucwords($status[Label]);?></option>
                                <?php
                                }
                                ?>
                            </optgroup> 
                        </select>     
                        <input type="hidden" name="hidden_astatus" id="hidden_astatus" value="" /> 
                    </div>
                </div>
                 
            </div>
            <!-- End .control-group --> 
                     
        </div>
        <!-- end promotion details --> 
        
        <!-- promotion formula -->
        <div class="tab-pane hide" id="PromotionFormula">
        	
            <div class="control-group" >  
                <div class="span6" > 
                    <label class="control-label" for="pro_minimum">* Minimum Bonus</label>
                    <div class="controls controls-row">  
                    	<input type="text" id="pro_minimum" name="pro_minimum" class="required span12 tip" value="<?=$promotion->MinimumAmount;?>" maxlength="30" title="Enter minimum amount" >    
                    </div>
                </div> 
                
                <div class="span6" >
                    <label class="control-label" for="pro_maximum">* Maximum Bonus</label>
                    <div class="controls controls-row">  
                    	<input type="text" id="pro_maximum" name="pro_maximum" class="required span12 tip" value="<?=$promotion->MaximumAmount;?>" maxlength="30" title="Enter maximum amount" >    
                    </div>
                </div>  
            </div>
            <!-- End .control-group --> 
            
            <div class="control-group" >  
                <div class="span6" > 
                    <label class="control-label" for="pro_turnover">* Turnover</label>
                    <div class="controls controls-row">  
                    	<input type="text" id="pro_turnover" name="pro_turnover" class="required span12 tip" value="<?=$promotion->Turnover;?>" maxlength="30" title="Enter turnover" >    
                    </div>
                </div> 
                
                <div class="span6" >
                    <label class="control-label" for="pro_bonusrate">* Bonus Rate (%)</label>
                    <div class="controls controls-row">  
                    	<input type="text" id="pro_bonusrate" name="pro_bonusrate" class="required span12 tip" value="<?=$promotion->BonusRate;?>" maxlength="30" title="Enter bonus rate" >  
                    </div>
                </div>  
                
            </div>
            <!-- End .control-group --> 
            
        </div> 
        <!-- promotion formula -->
        
        <!-- promotion terms -->
        <div class="tab-pane hide" id="PromotionTerms"> 
        	
            <div class="control-group" >  
            	<label class="control-label" for="pro_terms">Terms</label>
                <div class="controls controls-row">  
                    <textarea name="pro_terms" id="pro_terms" class="span12 tip" rows="3"  maxlength="500"  title="Enter terms and conditions" ><?=$promotion->Terms;?></textarea>
                </div>
            </div>
            <!-- End .control-group -->
            
        </div>  
        <!-- promotion terms -->
     	
        <div class="form-actions"> 
            <button type="submit" class="btn btn-primary" id="BtnSubmitForm" ><?=($promotion->PromotionID)?"Update ":"Save new "?> promotion</button>
            &nbsp;&nbsp;
            <button type="button" class="btn" id="BtnCancel" data-dismiss="modal" >Cancel</button> 
        </div> 
        
    </div> 
     
    
</form> 
<!-- end form -->  

</div>
<!-- End .widget-content -->

<!-- Init plugins only for page --> 
<script >
var managePromotion = function() { 
	 
	$.ajax({ 
		data: $("#validate_promotion").serialize(), 
		type:"POST",  
		url: "<?=base_url();?>manage_promotions/managePromotion", 
		dataType: "JSON",    
		cache: false,
		beforeSend:function(){       
			$("#BtnSubmitForm").addClass("disabled");  
			$("#BtnSubmitForm").attr("disabled", "disabled");    
			$("html, body").animate({ scrollTop: 0 }, "slow"); 
		},
		success:function(msg){     
			is_change = (msg.is_change > 0)?1:0; 
			
			$("#BtnSubmitForm").removeClass("disabled"); 
			$("#BtnSubmitForm").removeAttr("disabled", "disabled");
			
			if(msg.success > 0)
			 {   
			 	createMessageMini($(".form-widget-content"), msg.message, "success"); 
				//$('#validate_promotion')[0].reset();
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
	 
	$("#validate_promotion").validate({
		 submitHandler: function(form) {   
		 	managePromotion();//
		},
		invalidHandler: function(event, validator) {
			createMessageMini($(".form-widget-content"), "Please check all tabs.", "error"); 
		},
		ignore: null,
		//ignore: 'input[type="hidden"]',
		rules: { 
			pro_name: {
				required: true, 
				minlength: 2
			}, 
			pro_bonuscode: {
				required: true 
			},
		    pro_type: {
				required: true 
			}, 
			pro_startdate: {
				required: true 
			},
			pro_enddate: {
				required: true 
			}, 
			pro_currency: {
				required: true 
			}, 
			pro_product: {
				required: true 
			},
			pro_subproduct: {
				required: true 
			},
			pro_category: {
				required: true 
			},
			pro_status: {
				required: true 
			}, 
			pro_minimum: {
				required: true, 
				number: true 
			},
			pro_maximum: {
				required: true, 
				number: true
			},
			pro_turnover: {
				required: true, 
				number: true 
			},
			pro_bonusrate: {
				required: true, 
				number: true
			}
		},
		messages: {
			pro_name: {
				required: "Enter promotion name"  
			}, 
			pro_bonuscode: {
				required: "Enter bonus code" 
			},
		    pro_type: {
				required: "Select type" 
			}, 
			pro_startdate: {
				required: "Enter start date" 
			},
			pro_enddate: {
				required: "Enter end date" 
			}, 
			pro_currency: {
				required: "Select currency" 
			}, 
			pro_product: {
				required: "Select product" 
			}, 
			pro_subproduct: {
				required: "Select sub product" 
			},
			pro_category: {
				required: "Select category" 
			},
			pro_status: {
				required: "Select status" 
			}, 
			pro_minimum: {
				required: "Enter minimum" 
			},
			pro_maximum: {
				required: "Enter maximum" 
			},
			pro_turnover: {
				required: "Enter turnover" 
			},
			pro_bonusrate: {
				required: "Enter bonus rate"
			}  
			  
			
		}
	}); 
	   
	$("#pro_currency").change(function(){ 
		$("#hidden_acurrency").val($(this).find(":selected").text());
	});
	$("#pro_currency").trigger("change");
	
	$("#pro_status").change(function(){ 
		$("#hidden_astatus").val($(this).find(":selected").text());
	});
	$("#pro_status").trigger("change");
	
	$("#pro_product").change(function(){  
		var product = ($("#pro_product").val())?$("#pro_product").val():"";  
		//var category = ($("#act_category").val())?$("#act_category").val():"";
		$("#hidden_aproduct").val($(this).find(":selected").text());
		changeProduct("<?=base_url()?>manage_promotions/getSubProductList", product, '<?=$promotion->SubProductID?>', $("select[name=pro_subproduct]"), '');  
	});
	$("#pro_product").trigger("change"); 
	
 
	$("#pro_category").change(function(){ 
		$("#hidden_acategory").val($(this).find(":selected").text());
	});
	$("#pro_category").trigger("change");
	
	//$("#validate .tip").tooltip ({placement: 'top'});  
	$("#validate_promotion .tip").tooltip({
    	placement: 'top'     
	}).on('show', function(e) {
		e.stopPropagation();
	}).on('hide', function(e) {
		e.stopPropagation();
	});  
	
	// Select first tab
	//$('#TabPromotionsContent a:first').tab('show') 
	 
	$('.datepicker').datetimepicker({    
		//format: "yyyy-mm-dd hh:ii"
		pickTime: false
	});
	
	$(".nav-tabs a").click(function(){
		$(".select2-drop, .select2-drop-mask").hide();  
	}); 
	
}); 
 

</script> 