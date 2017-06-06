<link href="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="<?=base_url();?>media/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet"/>  
<script src="<?=base_url();?>media/js/plugins/forms/datepicker/bootstrap-datetimepicker.min.js"></script>

<!-- main -->
<div class="main">
    <?=$sidebar_view?>
	<section id="content">
	<div class="wrapper">
		<div class="crumb">
			<ul class="breadcrumb">
				<li><a href="<?=base_url("portal/dashboard")?>"><i class="icon16 i-home-4"></i>Countries</a><span class="divider">/</span></li>
				<li><a href="<?=base_url("portal/configure/page/statistics")?>">Statistics</a></li>
			</ul>
		</div>
		<div class="container-fluid">
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-info"></i> Statistics</h1>
			</div> 
            <!-- overpay board -->
            <div class="row-fluid"> 
			    <div class="span12"  >
					<div class="widget panel">  
                        <div class="widget-title">
							<div class="icon blue"  id="UpdateActivityStatisticBtn">
								<i class="icon20 i-clipboard-2"></i>
							</div>	
							<h4>Summary</h4>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						
                        <div class="widget-content"> 
						<table class="table table-bordered table-condensed table-striped">
						<thead>
						<tr><td>Market</td><td class="center">Total Views</td><td class="center">Total Views Today</td></tr>
						</thead>
						<tbody>
						<? foreach($stat_table as $market=>$view_data) { ?>
						<!-- activity statistic filechart span12 -->
							<tr><td><?=$market?></td><td class="center"><?=$view_data['total_views']?></td><td class="center"><?=$view_data['total_views_today']?></td></tr>
						<!-- end activity statistic filechart span12 -->
						<? } ?>
						</tbody>
						</table>
						</div>
						<!-- End .widget-content --> 
					</div>
					<!-- End .widget -->
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12"  >
					<div class="widget panel">  
                        <div class="widget-title">
							<div class="icon blue"  id="UpdateActivityStatisticBtn">
								<i class="icon20 i-clipboard-2"></i>
							</div>	
							<h4>Detailed</h4>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						<div class="widget-content panel-body"> 
							<div class="span10"  >
								<div class="span3">
								<select id="market_drop" name="market_drop" class="select2 select2-offscreen" tabindex="-1">
								  <? foreach ($market_list as $key=>$val) { ?>
								  <option value="<?=$key?>"><?=$val?></option>
								  <? } ?>
								</select>
								</div>
								<div id="datefrom" class="input-append datepicker span3" > 
									 <span class="add-on">
										<i class="icon16"></i>
									</span>
									<input type="text" value=""  name="stat_start" id="stat_start" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip call-fieldimportant" title="Select start date"  >
								</div>
								<div id="dateto" class="input-append datepicker span3" > 
									 <span class="add-on">
										<i class="icon16"></i>
									</span>
									<input type="text" value=""  name="stat_end" id="stat_end" data-format="yyyy-MM-dd hh:mm:ss" readonly="readonly"  class="myselect tip call-fieldimportant" title="Select end date"  >
								</div>
							</div>
							<div class="span2"  >
								<input type="button" class="btn btn-primary" id="searchBtn" value="Search"/>
							</div>
							<table class="table table-bordered table-condensed table-striped">
							  <thead>
							    <tr><td>Page</td><td>Menu</td><td class='center'>Views Count</td><td class='center'>Date Last Viewed</td></tr>
							  </thead>
							  <tbody id="stat_dtl">
							  </tbody>
							</table>
							<div class="clearfix"></div>
						</div>
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
var requestSent = false;
$(function(){
  
  $('.datepicker').datetimepicker({    
    format: "yyyy-MM-dd", 
    pickTime: false
  });
  
  $("#searchBtn").click(function(){
    getNumberOfViews();
  });
  
});

function getNumberOfViews() {
  $.ajax({
    url: "<?=base_url("portal/configure/get_statistics")?>",
	type: "post",
	dataType: "json",
	data: {
			market		: $("#market_drop").val(),
			date_from	: $("#stat_start").val(),
			date_to		: $("#stat_end").val()
		  },
	cache: false,
	beforeSend: function(){
	  if(requestSent)
	    return false;
	  if($("#stat_start").val() == ""){
	    alert("Please set Date From");
		return false;
	  }
	  if($("#stat_end").val() == ""){
	    alert("Please set Date To");
		return false;
	  }
	  requestSent = true;
	},
	success: function(response){
	  $("#stat_dtl").html(response.content);
	},
	complete: function(){
	  requestSent = false;
	}
  });
}
</script>