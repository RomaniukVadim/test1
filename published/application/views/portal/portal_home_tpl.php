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

</style>
<!-- main -->
<div class="main">

    <?= $sidebar_view ?>

    <section id="content">
        <div class="wrapper">
            <div class="crumb">
                <ul class="breadcrumb">
                    <li class="active"><i class="icon16 i-home-4"></i>Home</li>
                </ul>
            </div>
            <div class="container-fluid">
                <div id="heading" class="page-header">
                    <h1><i class="icon20 i-brain"></i> Knowledge Portal</h1>
                </div> 
                <!-- End .row-fluid --> 
                <div class="row-fluid"> 
                    <div class="span12"  >
                        <div class="widget panel">  
                            <div class="widget-title">
                                <div class="icon blue">
                                    <i class="icon20 i-globe"></i>
                                </div>	
                                <h4 >Country Selection</h4>
                                <a href="#" class="minimize"></a>
                            </div> 
                            <!-- End .widget-title -->
                            <div class="widget-content center">
                                <div class="portal-selection center"> 

                                    <? foreach ($market_list as $abbrv => $country) { ?>
                                        <a class="items" href="<?= base_url("portal/market/" . $abbrv) ?>">
                                            <img width="42" src="<?= base_url("media/images/portal/flags/" . $abbrv . ".png"); ?>"/>
                                            <div class="txt"><?= $country ?></div>
                                        </a>
                                    <? } ?>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <!-- End .widget-content --> 
                        </div>
                        <!-- End .widget -->
                    </div>
                </div>
                <? if ($can_configure) { ?>
                    <div class="row-fluid"> 
                        <div class="span4"  >
                            <div class="widget panel">  
                                <div class="widget-title">
                                    <div class="icon blue">
                                        <i class="icon20 i-key-2"></i>
                                    </div>	
                                    <h4 >Other Pages</h4>
                                    <a href="#" class="minimize"></a>
                                </div> 
                                <!-- End .widget-title -->
                                <div class="widget-content center">
                                    <div class="portal-selection center"> 
                                        <a class="items" href="<?= base_url("portal/configure") ?>">
                                            <img width="42" src="http://10.120.10.125/intra/skin/board/csd_guide/images/cfg01.jpg"/>
                                            <div class="txt">Configure</div>
                                        </a>
                                    </div>

                                    <div class="clearfix"></div>
                                </div>
                                <!-- End .widget-content --> 
                            </div>
                            <!-- End .widget -->
                        </div>
                    </div>
                    <!-- End .row-fluid --> 
                <? } ?>
            </div>
            <!-- End .container-fluid -->
        </div>
        <!-- End .wrapper -->
    </section>
</div>
<!-- Modal -->
<div class="modal fade" id="unconfirm_post" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close hidden" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title center">Unconfirmed Post</h4>
      </div>
      <div class="modal-body no-padding" style="overflow-y: hidden; max-height:500px;">
          <div class="unconfirm-list" style="overflow-y: auto;">
            <ul class="nav nav-list">

            </ul>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- End .main --> 
<script>

</script>