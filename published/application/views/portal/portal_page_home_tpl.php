<link href="<?= base_url(); ?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?= base_url(); ?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?= base_url(); ?>media/js/plugins/forms/validation/jquery.validate.js"></script>
<style>
    .select2-drop {
        width: 243px !important;
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
                    <h1><i class="icon20 i-brain"></i> Page Management</h1>
                </div> 
                <!-- End .row-fluid --> 
                <div class="row-fluid"> 
                    <div class="span12"  >
                        <div class="widget panel">  
                            <div class="widget-title">
                                <div class="icon blue">
                                    <i class="icon20 i-globe"></i>
                                </div>	
                                <h4 >Page List</h4>
                                <a href="#" class="minimize"></a>
                            </div> 
                            <!-- End .widget-title -->
                            <div class="widget-content panel-body">
                                <div class="span12"  >
                                    <select id="market_drop" name="market_drop" class="select2 span3 select2-offscreen" tabindex="-1">
                                        <? foreach ($market_list as $key => $val) { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                                <div class="widget-content">
                                    <table id="page_table" class="table">
                                        <thead>
                                            <tr>
                                                <td width="85%">Page Title</td>
                                                <td width="15%" class="center">Action</td>
                                            </tr>
                                        </thead>
                                        <tbody id="page_table_content">
                                            <tr>
                                                <td colspan="2" width="100%" class="center">- No Data -</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" id="BtnAdd">Add</button>
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
        $(".select2").select2({placeholder: "Select"});

        $("#market_drop").change(function() {
            loadPages();
        });

        $("#market_drop").trigger("change");

        $("#BtnAdd").click(function() {
            window.location = "<?= base_url("portal/configure/page/home/add") ?>/" + $("#market_drop").val();
        });

    });

    function loadPages() {
        $.ajax({
            url: "<?= base_url("portal/configure/get_page_list") ?>",
            type: "post",
            dataType: "json",
            data: {"market": $("#market_drop").val(),
                "menu": 0},
            error: function() {
                $("#page_table_content").html("<tr><td width=\"100%\" class=\"center\">- No Data -</td></tr>");
            },
            success: function(response) {
                $("#page_table_content").html(response.content);
            }
        });
    }

</script>