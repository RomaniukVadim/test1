<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>

<style>
.portal-selection .items {
  display: inline-block;
  margin: 15px;
}

.portal-selection .items:hover {
  cursor: pointer;
}
.portal-selection .txt {
  text-align: center;
  margin-top: 5px;
  font-weight: bold;
}

.select2-drop {
  width: 575px !important;
}

</style>
<!-- main -->
<div class="main">
		
    <?=$sidebar_view?>
    
	<section id="content">
	<div class="wrapper">
		<div class="crumb">
			<ul class="breadcrumb">
				<li class="active"><i class="icon16 i-home-4"></i>Home</li>
			</ul>
		</div>
		<div class="container-fluid">
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-brain"></i> Menu Management</h1>
			</div> 
			<!-- End .row-fluid --> 
            <div class="row-fluid"> 
				<div class="span12"  >
					<div class="widget panel">  
                        <div class="widget-title">
							<div class="icon blue">
								<i class="icon20 i-globe"></i>
							</div>	
							<h4 >Edit Menu</h4>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						<div class="widget-content panel-body">
							<form id="menu_form" class="form-horizontal form-widget-content" role="form" autocomplete="off" onsubmit="return false; ">
								<input id="menuid" name="menuid" type="hidden" value="<?=$menu_dtl->menu_id?>"/>
								<div class="control-group">
									<div class="span12">
										<label class="control-label" for="name">Menu Name</label>
										<div class="controls controls-row">
											<input id="name" name="name" type="text" class="required span4" maxlength="100" value="<?=$menu_dtl->menu_name?>"/>
										</div>
									</div>
								</div>
								<div class="control-group">
									<div class="span2">
										<label class="control-label">Market</label>
									</div>
									<? foreach($market_list as $key=>$val) { ?>
									<div class="span1">
										<label class="radio-inline center">
											<div class="checker" id="uniform-act_important">
												<input class="cmarket" <?=(stripos($menu_dtl->target_markets,$key) > -1?"checked='checked'":"")?> name="cmarket[]" type="checkbox" value="<?=$key?>" style="margin-bottom: 5px;">
											</div> <?=$val?>
										</label>
									</div>
									<? } ?>
									<input type="hidden" id="marketlist" name="marketlist" value="<?=strlen($menu_dtl->target_markets)?>" />
								</div>
								<div class="control-group">
									<div class="span12">
										<label class="control-label" for="parentmenu">Submenu of</label>
										<div class="controls controls-row">
											<select name="parentmenu" id="parentmenu" class="select2 span8 select2-offscreen" tabindex="-1">
												<option value='0'>Not Applicable</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-actions">
								  <button type="submit" class="btn btn-primary" id="BtnSubmitForm">Save</button>
								  <input type="button" class="btn btn-danger" id="BtnDeleteForm" value="Delete"/>
								  <a href="<?=base_url("portal/configure/menu/edit")?>" class="btn btn-success">Back To List</a>
								</div>
							</form>
                            <div class="clearfix"></div>
						</div>
						<!-- End .widget-content --> 
					</div>
					<!-- End .widget -->
				</div>
			</div>
			<!-- End .row-fluid --> 
		</div>
		<!-- End .container-fluid -->
	</div>
	<!-- End .wrapper -->
	</section>
</div>
<!-- End .main --> 
<script>     
  var marketTimer;
  var selectedParent = "<?=$menu_dtl->parentmenu_id?>";
  $(function() {
    $(".cmarket").click(function() {
	  clearTimeout(marketTimer);
	  $("#marketlist").val($("#menu_form .cmarket:checked").length);
	  $("#parentmenu").html("<option value='0'>Not Applicable</option>");
	  if($("#menu_form .cmarket:checked").serialize())
		marketTimer = setTimeout(getSubmenuList, 500);
	});
	
	$("#parentmenu").select2({placeholder: "Select"});
	
	$("#BtnResetForm").click(function(){
		formreset();
	});
	if($("#menu_form .cmarket:checked").serialize())
		marketTimer = setTimeout(getSubmenuList, 0);
		
	$("#menu_form").validate({
	  submitHandler: function(form) {
	 	manageMenu();
	  },
	  ignore: null,
	  rules: {
		name: {
	      required: true
		},
		  marketlist: {
			min: 1
		}
	  },
	  messages: {
		name: {
		  required: "Specify Menu Name" 
		}, 
		  marketlist: {
			min: "Please select at least one Market" 
		}  	
	  }
	});
	
	$("#BtnDeleteForm").click(function(){
	  if(confirm("Are you sure that you want to delete this menu?")) {
		deleteMenu($("#menuid").val());
	  }
	});
	
  });
  
  
  var manageMenu = function() { 
	$.ajax({ 
	  data: $("#menu_form").serialize(), 
	  type: "POST",  
	  url: "<?=base_url("portal/configure/save_menu")?>", 
	  dataType: "JSON",
	  cache: false,
	  beforeSend: function(){       
		$("#BtnSubmitForm").addClass("disabled");
		$("#BtnDeleteForm").addClass("disabled");
		$("#BtnSubmitForm").attr("disabled", "disabled");
		$("#BtnDeleteForm").attr("disabled", "disabled");
		$("html, body").animate({ scrollTop: 0 }, "slow");
	  },
	  error: function(){     
	    $("#BtnSubmitForm").removeClass("disabled"); 
		$("#BtnDeleteForm").removeClass("disabled");
		$("#BtnSubmitForm").removeAttr("disabled", "disabled");
		$("#BtnDeleteForm").removeAttr("disabled", "disabled");
	  },
	  success: function(response){     
		$("#BtnSubmitForm").removeClass("disabled"); 
		$("#BtnDeleteForm").removeClass("disabled");
		$("#BtnSubmitForm").removeAttr("disabled", "disabled");
		$("#BtnDeleteForm").removeAttr("disabled", "disabled");
			
		if(response.error)
		{
		  createMessageMini($(".form-widget-content"), response.msg, "error");
		}
		else
		{
		  createMessageMini($(".form-widget-content"), response.msg, "success"); 
		}
	  }
	});
  }
  
  function getSubmenuList() {
	$.ajax({
	  url: "<?=base_url("portal/configure/get_submenu_by_market")?>",
	  type: "post",
	  dataType: "json",
	  data: $("#menu_form .cmarket:checked").serialize()+"&menu_id="+$("#menuid").val(),
	  error: function() {
	    $("#parentmenu").html("<option value=''>Not Applicable</option>");
	  },
	  success: function(response) {
	    if(response.error) {
		  $("#parentmenu").html("<option value='0'>Not Applicable</option>");
		}
		else {
		  $("#parentmenu").html("<option value='0'>Not Applicable</option>"+response.content);
		}
		/*$("#parentmenu option[value="+selectedParent+"]").attr("selected","selected");
		alert(selectedParent);*/
		$("#parentmenu").select2("val",selectedParent);
		
		selectedParent = 0;
	  }
	});
  }
  
  function formreset() {
	$('#menu_form')[0].reset();
    clearSelectbox($("div.controls"));  
    $("ul.select2-choices li.select2-search-choice").remove();  
    $.uniform.update("input[type=checkbox], input[type=radio]");
	$("#parentmenu").html("<option value='0'>Not Applicable</option>");
  }

  function deleteMenu(menu_id) {
    $.ajax({ 
	  data: $("#menu_form").serialize(), 
	  type: "POST",  
	  url: "<?=base_url("portal/configure/delete_menu")?>", 
	  dataType: "JSON",
	  cache: false,
	  beforeSend: function(){       
		$("#BtnSubmitForm").addClass("disabled");
		$("#BtnDeleteForm").addClass("disabled");
		$("#BtnSubmitForm").attr("disabled", "disabled");
		$("#BtnDeleteForm").attr("disabled", "disabled");
		$("html, body").animate({ scrollTop: 0 }, "slow");
	  },
	  error: function(){     
	    $("#BtnSubmitForm").removeClass("disabled"); 
		$("#BtnDeleteForm").removeClass("disabled");
		$("#BtnSubmitForm").removeAttr("disabled");
		$("#BtnDeleteForm").removeAttr("disabled");
	  },
	  success: function(response){     
		$("#BtnSubmitForm").removeClass("disabled"); 
		$("#BtnDeleteForm").removeClass("disabled");
		$("#BtnSubmitForm").removeAttr("disabled");
		$("#BtnDeleteForm").removeAttr("disabled");
			
		if(response.error)
		{
		  createMessageMini($(".form-widget-content"), response.msg, "error");
		}
		else
		{
		  createMessageMini($(".form-widget-content"), response.msg, "success"); 
		  alert("Menu Deleted");
		  window.location = "<?=base_url("portal/configure/menu/edit")?>";
		}
	  }
	});
  }
</script>