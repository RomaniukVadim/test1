<script src="<?= base_url(); ?>media/js/plugins/charts/flot/jquery.flot.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/flot/jquery.flot.pie.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/flot/jquery.flot.resize.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/flot/jquery.flot.time.min.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/sparklines/jquery.sparkline.min.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/flot/date.js"></script> <!-- Only for generating random data delete in production site-->
<script src="<?= base_url(); ?>media/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>

<link href="<?= base_url(); ?>media/css/summernote-fonts/font-awesome.min.css" rel="stylesheet"/> 
<link href="<?= base_url(); ?>media/js/plugins/forms/summernote/summernote.css" rel="stylesheet"/> 
<script src="<?= base_url(); ?>media/js/plugins/forms/summernote/summernote.js"></script>
<script src="<?= base_url(); ?>media/js/jquery.oLoader.min.js"></script>

<style>
    .category {
        margin-right: 0px !important; 
        font-weight: normal !important;
    }

    .priority {
        margin-left: 0px !important;
    }
    .hide{
        display:none;
    }

</style>
<!-- main -->
<form action='#' name="hidden_form" method='POST' id='hidden_form' style="display:none">
    <input type="text" name="id" value="" id="id">
    <input type="submit" value="Submit" name="Submit" id="Submit">
</form>
<div class="main">
    <?= $sidebar_view ?>
    <section id="content">
        <div class="wrapper">
            <div class="crumb">
                <ul class="breadcrumb">
                    <li><a href="<?= base_url("portal/dashboard") ?>"><i class="icon16 i-home-4"></i>Countries</a><span class="divider">/</span></li>
                    <li><a href="<?= base_url("portal/market/" . $market_code) ?>"><?= $market; ?></a></li>
                </ul>
            </div>
            <div class="container-fluid">
                <div id="heading" class="page-header">
                    <h1><i class="icon20 i-info"></i> <?= htmlentities($page_data[0]->menu_name ? $page_data[0]->menu_name : "Home Page"); ?></h1>
                </div> 
                <!-- overpay board -->
                <div class="row-fluid"> 
                    <? foreach ($page_data as $page_dtl) { ?>
                        <!-- activity statistic filechart span12 -->
                        <? $page_id = $page_data[0]->page_id ?>
                        <div class="span12"  >
                            <div class="widget panel" id="page_panel">  
                                <div class="widget-title">
                                    <div class="icon blue"  id="UpdateActivityStatisticBtn">
                                        <i class="icon20 i-clipboard-2"></i>
                                    </div>	

                                    <h4 id='title'><span id='current_title'><?= $page_dtl->page_title; ?></span><input class='hide' value='<?= $page_dtl->page_title; ?>' id='edit_title' style='width:300px;'>
                                        <br>
                                        <div id='edit_desc' class='hide'>
                                            <?= $page_dtl->page_desc ?>
                                        </div>

                                        <select id="edit_category" style="width:200px;" class='hide'>
                                            <option value=''> -Category- </option>
                                            <?php
                                            foreach ($category_list as $category) {
                                                if ($page_dtl->category == $category->category_id) {

                                                    echo "<option value='" . $category->category_id . "' selected>" . $category->name . "</option>";
                                                } else {
                                                    echo "<option value='" . $category->category_id . "'>" . $category->name . "</option>";
                                                }
                                            }
                                            ?>
                                        </select><br>

                                        <select id="edit_department" style="width:200px;" class='hide'>
                                            <option value=''> -Department- </option>
                                            <?php
                                            foreach ($department_list as $department) {
                                                if ($page_dtl->from_department == $department->department_id) {
                                                    echo "<option value='" . $department->department_id . "' selected>" . $department->name . "</option>";
                                                } else {
                                                    echo "<option value='" . $department->department_id . "' >" . $department->name . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </h4>
                                    <? if (admin_only() || csd_supervisor_access()) { ?>
                                        <div class="btn-group"  style='float:right;'>
                                            <a class="btn back" data-id="<?= $page_dtl->page_menu_id ?>" data-location="<?= base_url() . "portal/market/" . $page_dtl->page_market . "/" . $page_dtl->page_menu_id ?>"><i    class="i-undo-2" title="Back"></i></a>
                                            <span id="top-button" ><button class="btn" id="edit"><i    class="i-pencil-4" title="Edit"></i></button></span>
                                        </div>
                                    <? } ?>
                                </div> 
                                <!-- End .widget-title -->

                                <div class="widget-content">

                                    <div id="pagecontent" style="overflow-x:auto;overflow-y:auto;">
                                        <?= stripslashes($page_dtl->page_content); ?>

                                    </div>
                                </div>
                                <!-- End .widget-content --> 
                                <div class="widget-content">
                <!-- <a href="<? //= base_url("portal/market/" . $market_code . "/" . ($page_data[0]->menu_id ? $page_data[0]->menu_id : ""))                                                                                                                                                                                ?>" class="btn btn-primary">Back</a>-->

                                </div>
                            </div>
                            <!-- End .widget -->
                        </div>
                        <!-- end activity statistic filechart span12 -->
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

<script>
    var is_updated = false;
    $(function() {

        $(document).on("click", ".back", function() {
            var href = $(this).data('location');
            var id = $(this).data('id');
            $('#hidden_form').attr('action', href);
            $('#id').val(id);
            $('#Submit').click();
        });
        $(document).on("click", "#edit", function() {
            $('#current_title').addClass('hide');
            $('#edit_title,#edit_desc,#edit_category,#edit_department').removeClass('hide');
            $('.selector').css('display', '');

            $('#top-button').html("<button class='btn' id='save'><i  class='i-disk' title='Save'></i></button><button id='cancel' class='btn'><i  class='i-cancel-circle' title='Cancel Edit'></i></button>");
            $("#edit_desc").summernote({
                height: 'relative',
                oninit: function() {
                },
                toolbar: []
            });
            $("#pagecontent").summernote({
                height: 'relative',
                /*  onImageUpload: function(files, editor, welEditable) {
                 sendFile(files[0], editor, welEditable);
                 },*/
                oninit: function() {
                    // getFileList();
                },
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough']],
                    ['font', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['picture', 'table', 'hr', 'link']],
                    ['action', ['undo', 'redo']]
                ]
            });
        });

        $(document).on("click", "#cancel", function() {

            if (!is_updated) {
                $("#pagecontent").html("<?= addslashes(preg_replace('/[\n\r]/', "", $page_dtl->page_content)) ?>");
                $("#edit_desc").html("<?= addslashes(preg_replace('/[\n\r]/', "", $page_dtl->page_desc)) ?>");
                $('#current_title').html($('#current_title').html());
            } else {
                $('#current_title').html($('#edit_title').val());
            }
            $('#edit_title,#edit_desc,#edit_category,#edit_department').addClass('hide');
            $('.selector').css('display', 'none');
            $('#current_title').removeClass('hide');
            $('#top-button').html("<button id='edit' class='btn'><i  class='i-pencil-4' title='Edit'></i></button>");
            $("#pagecontent,#edit_desc").destroy();
            $("#edit_desc").css('display', 'none');

        });
        $(document).on("click", "#save", function() {
            $('#page_panel').oLoader({
                backgroundColor: 'black',
                fadeInTime: 500,
                fadeLevel: 0.2,
                image: '<?= base_url(); ?>media/images/loader.gif',
                style: 3,
                imagePadding: 5,
                imageBgColor: 'transparent'


            });

            setTimeout(function() {
                $.post("<?= base_url("portal/configure/save_page") ?>",
                        {
                            pageid: "<?= $page_id ?>",
                            content: $('#pagecontent').code(),
                            pagename: $('#edit_title').val(),
                            shortdesc: $('#edit_desc').code(),
                            edit_category: $('#edit_category').val(),
                            edit_department: $('#edit_department').val()
                        },
                function(data) {
                    if (data == 'true') {
                        $.jGrowl("Updated successfully...", {position: 'center', group: 'success'});
                        $('#page_panel').oLoader('hide');
                        is_updated = true;
                    } else {
                        $.jGrowl("Failed to update page, please try again...", {position: 'center', group: 'error'});
                        $('#page_panel').oLoader('hide');
                        is_updated = false;
                    }

                }
                );
            }, 0);

        });



    });

</script>