<link href="<?=base_url();?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?=base_url();?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?=base_url();?>media/js/plugins/forms/validation/jquery.validate.js"></script>
<style>
.select2-drop {
  width: 243px !important;
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
							<h4 >Menu List</h4>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						<div class="widget-content panel-body">
							<select id="market_drop" name="market_drop" class="select2 span3 select2-offscreen" tabindex="-1">
							  <? foreach ($market_list as $key=>$val) { ?>
							  <option value="<?=$key?>"><?=$val?></option>
							  <? } ?>
							</select>
							<div class="widget-content">
							<table id="menu_table" class="table">
							<thead>
							<tr>
								<td width="70%">Menu Title</td>
								<td width="15%" class="center">Market</td>
								<td width="15%" class="center">Action</td>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td colspan="3" width="100%" class="center">- No Data -</td>
							</tr>
							</tbody>
							</table>
							</div>
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
  
  $(function() {
    $("#market_drop").select2({placeholder: "Select"});
	
	$("#market_drop").change(function(){
	  loadMenuList($(this).val());
	});
	$("#market_drop").trigger("change");
  });
  
  function loadMenuList(market) {
    $.ajax({
	  url: '<?=base_url("portal/configure/get_menu_list")?>',
	  data: {cmarket: market},
	  type: "post",
	  dataType: "json",
	  cache: false,
	  beforeSend: function() {
	    if(!market) {
		  $("#menu_table > tbody").html("<tr><td colspan=\"3\" width=\"100%\" class=\"center\">- No Data -</td></tr>");
		  return false;
		}
		else
		  $("#menu_table > tbody").html("");
	  },
	  error: function () {
	    console.log("Error!!!");
	  },
	  success: function(response) {
	    if(response.error){
		}
		else {
		  if(response.content){
		    $("#menu_table > tbody").html(response.content);
		  }
		  else {
		    $("#menu_table > tbody").html("<tr><td colspan=\"3\" width=\"100%\" class=\"center\">- No Data -</td></tr>");
		  }
		}
		$(".delete-btn").click(function(){
		  if(confirm("Are you sure that you want to delete this page?")) {
			deleteMenu($(this).attr("page-id"));
		  }
		});
	  }
	});
  }
  
  function deleteMenu(menuId) {
    $(".btn").disabled = true;
    $.ajax({
	  url: "<?=base_url("portal/configure/delete_menu")?>",
	  type: "post",
	  dataType: "json",
	  data: {"menuid" : menuId,
	         "cmarket" : $("#market_drop").val()},
	  error: function() {
	    console.log("Error!!!");
	  },
	  success: function(response) {
	    if(response.error){
		}
		else {
		  if(response.content){
		    $("#menu_table > tbody").html(response.content);
		  }
		  else {
		    $("#menu_table > tbody").html("<tr><td colspan=\"3\" width=\"100%\" class=\"center\">- No Data -</td></tr>");
		  }
		}
		$(".delete-btn").click(function(){
		  if(confirm("Are you sure that you want to delete this page?")) {
			deleteMenu($(this).attr("page-id"));
		  }
		});
	  }
	});
  }
  
 </script>