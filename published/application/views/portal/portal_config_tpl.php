<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.pie.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.resize.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/jquery.flot.time.min.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/sparklines/jquery.sparkline.min.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/flot/date.js"></script> <!-- Only for generating random data delete in production site-->
<script src="<?=base_url();?>media/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
<script src="<?=base_url();?>media/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>
 
<style>
.category {
	margin-right: 0px !important; 
	font-weight: normal !important;
}

.priority {
    margin-left: 0px !important;
}
 
</style>
<!-- main -->
<div class="main">
		
    <?=$sidebar_view?>
    
	<section id="content">
	<div class="wrapper">
		<div class="crumb">
			<ul class="breadcrumb">
				<li><a href="<?=base_url("portal")?>"><i class="icon16 i-home-4"></i>Countries</a><span class="divider">/</span></li>
				<li><a href="<?=base_url("portal/market/".$market_code)?>"><?=$market;?></a></li>
			</ul>
		</div>
		<div class="container-fluid">
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-info"></i> <?=htmlentities($page_data[0]->menu_name);?></h1>
			</div> 
            
            <!-- overpay board -->
            <div class="row-fluid"> 
            	<? foreach($page_data as $page_dtl) { ?>
                <!-- activity statistic filechart span8 -->
				<div class="span12"  >
					<div class="widget panel">  
                        <div class="widget-title">
							<div class="icon blue"  id="UpdateActivityStatisticBtn">
								<i class="icon20 i-clipboard-2"></i>
							</div>	
							<? if($page_dtl->page_menu_id!=0) { ?>
							<a href="<?=base_url("portal/market/".$market_code."/".$page_dtl->page_menu_id."/".$page_dtl->page_id)?>"><h4><?=htmlentities($page_dtl->page_title);?></h4></a>
							<? } else { ?>
							<h4><?=htmlentities($page_dtl->page_title);?></h4>
							<? } ?>
							<a href="#" class="minimize"></a>
						</div> 
                        <!-- End .widget-title -->
						
                        <div class="widget-content" style="overflow: auto; max-height: 40em;"> 
                        	 <?=($page_dtl->page_desc);?>
						</div>
						<!-- End .widget-content --> 
					</div>
					<!-- End .widget -->
				</div>
				<!-- end activity statistic filechart span8 -->
                <? } ?>
               
			
			</div>
			<!-- End .row-fluid --> 
             
            
		</div>
		<!-- End .container-fluid -->
	</div>
	<!-- End .wrapper -->
	</section>
    
</div>
<!-- End .main --> 