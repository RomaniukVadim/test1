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
<!-- date range -->
<link rel="stylesheet" type="text/css" media="all" href="<?= base_url(); ?>media/js/plugins/bootstrap-daterangepicker/daterangepicker-bs2.css" /> 
<script type="text/javascript" src="<?= base_url(); ?>media/js/plugins/bootstrap-daterangepicker/moment.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>media/js/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end date range -->

<style>
    .category {
        margin-right: 0px !important; 
        font-weight: normal !important;
    }

    .priority {
        margin-left: 0px !important;
    }

    #jGrowl{
        z-index:99999 !important;
    }
    .ajs-maximized{
        position:absolute !important;
        width: 80% !important;
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

            <div class="container-fluid" >
                <?php
                echo "<div id='heading' class='page-header'>
                         <h1>";

                echo "<i class='icon20 i-info'></i>&nbsp;&nbsp;";
                echo "<span id='menu_title'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                echo "</h1>";
                ?>
                <?php
                echo "</span>";
                ?>
                <span class="btn-group filtering_form pull-right" style="margin-right:5%;">
                    <?php if (admin_only() || csd_supervisor_access()) { ?>
                        <button class="btn"  title= 'Add a new page' id='show_add_form'><i class="i-file-plus icon20" style="font-size:25px;" data-shown='no'  ></i></button>
                    <?php } ?>
                    <div id="reportrange" class=" btn " > 
                        <i class="icon24 i-calendar"></i>
                        <?php /* ?><span><?php echo date("F j, Y", strtotime('-30 day')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b> <?php */ ?>
                        <span>
                            <?php
                            $s_fromdate = date("2013-09-01 00:00:00");
                            $s_todate = date('Y-m-d 23:59:59');
                            echo date('Y/m/d', strtotime($s_fromdate)) . "-" . date('Y/m/d', strtotime($s_todate));
                            ?> 
                        </span> 
                        <!--<b class="caret"></b>  -->
                        <i class="icon19 i-arrow-down-2"></i>
                    </div>  


                    <select id="filter_category" style="width:120px;">
                        <option value=''>-SELECT-</option>
                        <?php
                        foreach ($category_list as $category) {
                            echo "<option value='" . $category->category_id . "'>" . strtoupper($category->name) . "</option>";
                        }
                        ?>
                    </select>

                    <select id="filter_department" style="width:120px;">
                        <option value=''>-SELECT-</option>
                        <?php
                        foreach ($department_list as $department) {
                            echo "<option value='" . $department->department_id . "'>" . strtoupper($department->name) . "</option>";
                        }
                        ?>
                    </select>

                    <button class="btn" id='search_button'  title='Search'><i class=' i-search-5' style="font-size:25px;"></i></button>

                </span>
                <?php
                echo "</div>";
                ?>




                <div id="page_list" style="margin-right:2%;">

                </div>

                <!-- End .container-fluid -->


            </div>
            <!-- End .wrapper -->
    </section>
    <body>
        <div style="display:none">
            <div id="add_new_modal">
                <div id="add_new_form">

                    <form action="#" method="POST" id="frm_add_new_page">
                        <table width="100%">
                            <tr>
                                <td>
                                    <label> Name:</label>
                                    <input type="text" value="" name="" id="pagename">
                                </td>
                                <td style='display:none'>
                                    <label>Short Description:</label>  
                                    <textarea id="shortdesc" cos="20" rows="1" style="width: 300px;">                           
                                    </textarea>
                                </td>
                                <td>
                                    <label>Category:</label>  
                                    <select id="category">
                                        <option value=''>-SELECT-</option>
                                        <?php
                                        foreach ($category_list as $category) {
                                            echo "<option value='" . $category->category_id . "'>" . strtoupper($category->name) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label>From Department:</label>  
                                    <select id="from_department" >
                                        <option value=''>-SELECT-</option>
                                        <?php
                                        foreach ($department_list as $department) {
                                            echo "<option value='" . $department->department_id . "'>" . strtoupper($department->name) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                </td>
                            </tr>
                        </table>
                        <label>  Content:</label> 

                        <div id="pagecontent">
                            <br><br> <br><br>
                        </div>
                    </form>

                </div>

                <button id="save_button" class="btn btn-primary">Save</button> <button id="close_save" class="btn btn-danger">Close</button>

            </div>
        </div>

        <div style="display:none">
            <div id="edit_modal">
                <div id="edit_form">

                    <form action="#" method="POST" id="frm_edit_page">
                        <table width="100%">
                            <tr>
                                <td>
                                    <label> Name:</label>
                                    <input type="hidden" value="" name="" id="edit_page_id">
                                    <input type="text" value="" name="" id="edit_pagename">
                                </td>
                                <td style='display:none'>
                                    <label>Short Description:</label>  
                                    <textarea id="edit_shortdesc" cos="20" rows="1" style="width: 300px;">                           
                                    </textarea>
                                </td>
                                <td>
                                    <label>Category:</label>  
                                    <select id="edit_category">
                                        <option value=''>-SELECT-</option>
                                        <?php
                                        foreach ($category_list as $category) {
                                            echo "<option value='" . $category->category_id . "'>" . strtoupper($category->name) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label>From Department:</label>  
                                    <select id="edit_department" >
                                        <option value=''>-SELECT-</option>
                                        <?php
                                        foreach ($department_list as $department) {
                                            echo "<option value='" . $department->department_id . "'>" . strtoupper($department->name) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                </td>
                            </tr>
                        </table>
                        <label>  Content:</label> 

                        <div id="edit_pagecontent">
                            <br><br> <br><br>
                        </div>
                    </form>

                </div>

                <button id="edit_save_button" class="btn btn-primary">Save</button> <button id="edit_close" class="btn btn-danger">Close</button>

            </div>
        </div>




</div>

</body>
<!-- End .main --> 

<script>
    var page_id = 0;
    var edit_page_id = 0;
    var home_page_content = "";
    var is_updated = false;
    var has_home_page = false;
    function generate_page_list(page_menu_id) {
        $('#page_list').html('<br><br><br><br><br><br><br><br>');
        $('#page_list').oLoader({
            backgroundColor: 'black',
            fadeInTime: 500,
            fadeLevel: 0.2,
            image: '<?= base_url(); ?>media/images/loader.gif',
            style: 3,
            imagePadding: 5,
            imageBgColor: 'transparent'


        });
        $.post("<?= base_url("portal/generate_page_list") ?>",
                {
                    page_menu_id: page_menu_id,
                    market_code: "<?= $market_code ?>",
                    category: $('#filter_category').val(),
                    department: $('#filter_department').val(),
                    from: $("[name='daterangepicker_start']").val(),
                    to: $("[name='daterangepicker_end']").val()
                },
        function(data) {

            $("#page_list").html(data);
            $('#page_list').oLoader('hide');
            if (page_menu_id == 0 || page_menu_id == null) {
                $.post("<?= base_url("portal/get_homepage_content") ?>",
                        {
                            market_code: "<?= $market_code ?>",
                        },
                        function(data) {

                            page_id = data.page_id;
                            home_page_content = data.content;

                            if (page_id != 0) {
                                $('#homepage_content').html(data.content);
                                has_home_page = true;
                            } else {
                                has_home_page = false;
                                page_id = "new";
                                $('#homepage_content').html("<center><h1>No Content Found</h1></center>");
                                $("#home_page_buttons").html('<button class="btn btn-primary" id="create_home_page"><i class=" i-plus"></i>Create New</button>');

                            }
                        }, 'json'
                        );


                $(document).on("click", "#edit_home_page", function() {
                    $("#home_page_buttons").html('<button class="btn" id="save_home_page"><i class=" i-disk"></i></button> <button class="btn" id="cancel_edit_home_page"><i class=" i-blocked"></i></button>');
                    $("#homepage_content").summernote({
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

                $(document).on("click", "#create_home_page", function() {
                    $("#home_page_buttons").html('<button class="btn" id="save_home_page"><i class=" i-disk"></i></button> <button class="btn" id="cancel_edit_home_page"><i class=" i-blocked"></i></button>');
                    $("#homepage_content").summernote({
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

                $(document).on("click", "#cancel_edit_home_page", function() {
                    $("#homepage_content").destroy();
                    if (has_home_page) {
                        $("#home_page_buttons").html('<button class="btn" id="edit_home_page"><i class=" i-pencil-4"></i></button>');
                        if (is_updated == false) {
                            $('#homepage_content').html(home_page_content);
                        }

                    } else {
                        $("#home_page_buttons").html('<button class="btn btn-primary" id="create_home_page"><i class=" i-plus"></i>Create New</button>');
                        if (is_updated == false) {
                            $('#homepage_content').html('<center><h1>No Content Found</h1></center>');
                        }
                    }

                });

                $(document).on("click", "#save_home_page", function() {
                    $('#homepage_panel').oLoader({
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
                                    pageid: page_id,
                                    content: $('#homepage_content').code(),
                                    pagename: "Homepage-" + "<?= $market ?>",
                                    shortdesc: "Homepage-" + "<?= $market ?>",
                                    market: "<?= $market_code ?>",
                                    page_menu_id: "0"
                                },
                        function(data) {
                            if (data != 'false') {
                                $.jGrowl("Updated successfully...", {position: 'center', group: 'success'});
                                $('#homepage_panel').oLoader('hide');
                                is_updated = true;
                            } else {
                                $.jGrowl("Failed to update page, please try again...", {position: 'center', group: 'error'});
                                $('#homepage_panel').oLoader('hide');
                                is_updated = false;
                            }

                        }
                        );
                    }, 0);

                });


            }
        }
        );
    }
    $(function() {

        var page_menu_id = "<?= trim($page_menu_id) ?>";
        $('#search_button').click(function() {
            generate_page_list(page_menu_id);
        });
        generate_page_list(page_menu_id);
        $(document).on("click", ".hide_page", function() {
            var that = this;
            var hide_id = $(that).data('id');
            if ($(that).attr("data-visible") == 'true') {
                // $('#page_content_' + hide_id).hide("slide", {direction: "up"}, 0);
                $('#page_content_' + hide_id).slideUp();
                $(that).html("<i class='icon16   i-eye-4'></i>");
                $(that).attr("title", 'Show');
                $(that).attr("data-visible", 'false');
            } else {
                //$('#page_content_' + hide_id).show("slide", {direction: "up"}, 0);
                $('#page_content_' + hide_id).slideDown();
                $(that).html("<i class='icon16   i-eye-5'></i>");
                $(that).attr("title", 'Hide');
                $(that).attr("data-visible", 'true');
            }

        });

        $(document).on("click", ".delete_page", function() {
            var that = this;
            var pre = "Are you sure you want to delete this page?";

            alertify.confirm(pre, function() {
                $.post("<?= base_url("portal/delete_page_v2") ?>",
                        {
                            page_id: $(that).data("id"),
                        },
                        function(data) {
                            if (data == '1') {
                                $.jGrowl("Deleted successfully...",
                                        {
                                            position: 'center',
                                            group: 'success',
                                            life: 3000,
                                        });
                                generate_page_list(page_menu_id);
                                /*$("#" + $(that).data("id")).remove();
                                 if ($('.search-results').is(':empty')) {
                                 $('.search-results').html("<center><h3>No Page Found</h3></center>");
                                 $('.search-results').attr("isempty", "yes");
                                 }*/
                            } else {
                                $.jGrowl("Failed to delete page, please try again...",
                                        {
                                            position: 'center',
                                            group: 'error',
                                            life: 3000,
                                        });
                            }

                        }
                );
            }).setting('labels', {'ok': 'Yes', 'cancel': 'Cancel'});
        });


        alertify.dialog('genericDialog', function() {
            return {
                main: function(content) {
                    this.setContent(content);
                },
                setup: function() {

                    return {
                        focus: {
                            element: function() {
                                return this.elements.body.querySelector(this.get('selector'));
                            },
                            select: true
                        },
                        options: {
                            title: "<h3>Page Details</h3>",
                            startMaximized: false,
                            closable: true,
                            frameless: false

                        }
                    };
                },
                settings: {
                    selector: undefined,
                }

            };
        });


        $(document).on("click", ".page_item", function() {
            var href = $(this).data('location');
            var id = $(this).data('id');
            $('#hidden_form').attr('action', href);
            $('#id').val(id);
            $('#hidden_form').submit();

        });

        $(document).on("click", "#show_add_form", function() {
            $('#pagecontent').code("<p><br></p>");
            $('#shortdesc,#pagename').val('');
            alertify.genericDialog($('#add_new_modal')[0]).set('selector');
            $('.ajs-dialog').attr("style", "height: 643px; min-height: 112px; width: 1133px; min-width: 548px; max-width: none; left: 41px; top: -11px;");
        });

        $(document).on("click", "#close_save", function() {
            alertify.genericDialog($('#add_new_modal')[0]).close('selector');
            $('.alertify').remove();
        });


        $(document).on("click", ".edit_page", function() {
            var that = this;
            var edit_id = $(that).data('id');
            $('#edit_page_id').val(edit_id);
            $('#edit_pagecontent').code($('#page_content_' + edit_id).html());
            $('#edit_pagename').val($('#page_title_' + edit_id).html());
            $('#edit_category').prop('selectedIndex', $(that).data('category'));
            $('#edit_department').prop('selectedIndex', $(that).data('department'));


            alertify.genericDialog($('#edit_modal')[0]).set('selector');
            $('.ajs-dialog').attr("style", "height: 643px; min-height: 112px; width: 1133px; min-width: 548px; max-width: none; left: 41px; top: -11px;");
        });
        $(document).on("click", "#edit_close", function() {
            alertify.genericDialog($('#edit_modal')[0]).close('selector');
            $('.alertify').remove();
        });



        $("#pagecontent,#edit_pagecontent").summernote({
            height: '250',
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





        $(document).on("click", "#save_button", function() {
            $('#add_new_form').oLoader({
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
                            pageid: "new",
                            content: $('#pagecontent').code(),
                            pagename: $('#pagename').val(),
                            shortdesc: $('#shortdesc').val(),
                            market: "<?= $market_code ?>",
                            page_menu_id: "<?= $page_menu_id ?>",
                            category: $('#category').val(),
                            department: $('#from_department').val()

                        },
                function(data) {

                    if (data != 'false') {
                        if ($('.search-results').attr('isempty') == 'yes') {
                            $('.search-results').html(data);
                            $('.search-results').attr('isempty', 'no');
                        } else {
                            generate_page_list(page_menu_id);
                            // $('.search-results').prepend(data);
                        }

                        $.jGrowl("Added successfully...",
                                {
                                    position: 'center',
                                    group: 'success',
                                    life: 3000,
                                    close: function(e, m, o) {


                                    }


                                });
                        $('#add_new_form').oLoader('hide');
                        /*setTimeout(function() {
                         alertify.genericDialog($('#add_new_modal')[0]).close('selector');
                         }, 1000);*/

                    } else {
                        $.jGrowl("Failed to add page, please try again...", {position: 'center', group: 'error'});
                        $('#add_new_form').oLoader('hide');

                    }

                }
                );
            }, 0);

        });



        $(document).on("click", "#edit_save_button", function() {
            $('#frm_edit_page').oLoader({
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
                            pageid: $('#edit_page_id').val(),
                            content: $('#edit_pagecontent').code(),
                            pagename: $('#edit_pagename').val(),
                            shortdesc: $('#edit_shortdesc').code(),
                            edit_category: $('#edit_category').val(),
                            edit_department: $('#edit_department').val()
                        },
                function(data) {
                    if (data == 'true') {
                        $.jGrowl("Updated successfully...", {position: 'center', group: 'success'});
                        generate_page_list(page_menu_id);
                        $('#frm_edit_page').oLoader('hide');
                        is_updated = true;
                    } else {
                        $.jGrowl("Failed to update page, please try again...", {position: 'center', group: 'error'});
                        $('#frm_edit_page').oLoader('hide');
                        is_updated = false;
                    }

                }
                );
            }, 0);

        });




        $('#reportrange').daterangepicker(
                {
                    ranges: {
                        //'Today': [moment(), moment()], 
                        'Today': ["<?= date('Y-m-d 00:00:00') ?>", "<?= date('Y-m-d 23:59:59') ?>"],
                        //'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                        'Yesterday': ["<?= date('Y-m-d 00:00:00', strtotime('-1 day')) ?>", "<?= date('Y-m-d 23:59:59', strtotime('-1 day')) ?>"],
                        'Last 7 Days': [moment().subtract('days', 6).hour(0).minute(0)],
                        'Last 30 Days': [moment().subtract('days', 29).hour(0).minute(0), moment()],
                        'This Month': [moment().startOf('month').hour(0).minute(0), moment().endOf('month')],
                        'Last Month': [moment().subtract('month', 1).hour(0).minute(0).startOf('month'), moment().subtract('month', 1).endOf('month')],
                        'From the beginning': ["<?= date('2013-09-01 00:00:00') ?>", "<?= date('Y-m-d 23:59:59') ?>"]
                    },
                    //startDate: moment().subtract('days', 29),
                    startDate: "<?= $s_fromdate; ?>", //moment(),
                    endDate: "<?= $s_todate; ?>", //moment(),
                    timePicker: true,
                    timePickerIncrement: 1, //minutes default 30
                    selected_hour: 24,
                    //format: 'MM/DD/YYYY h:mm A'
                    format: 'YYYY-MM-DD H:mm:ss '
                },
        function(start, end) {
            $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
            $("#s_fromdate").val(start.format('YYYY-MM-DD HH:mm:ss'));
            $("#s_todate").val(end.format('YYYY-MM-DD HH:mm:ss'));
        }
        );













    });


</script>
<style>
    .search-string{
        cursor:pointer;
    }
</style>