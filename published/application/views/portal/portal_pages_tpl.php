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
    /** post readers **/
    .post-read-box{
        padding: 15px;
    }
    .post-read-box .checkbox{
        display: inline-block;
    }
    .post-read-box label{
        margin:0 !important;
        color: red;
        font-weight: 700;
        line-height: 1.5;
        
    }
    .post-read-box input[type="checkbox"] {
        margin-right: 5px;
    }
    .widget .widget-title .icon{
        line-height: 2.2;
    }
    .post-readers{
        display: inline-block;
        float:right;
    }    
</style>
<!-- main -->

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
            <div id='heading' class='page-header'>
                <h1>
                <i class='icon20 i-info'></i>
                <span id='menu_title' data-marketcode="<?= $market_code ?>" data-page_menu_id="<?= $page_menu_id ?>" data-market="<?= $market ?>"></span>
                </h1>



                <span class="input-append filtering_form pull-right " style="margin-right:1%;">
                    <?php if (admin_access() || csd_supervisor_access() || additional_page_admins()) { ?>
                    <button class="btn"  title= 'Add a new page' id='show_add_form' data-marketcode="<?= $market_code ?>" data-page_menu_id="<?= $page_menu_id ?>" data-market="<?= $market ?>"><i class="i-file-plus icon20" style="font-size:25px;" data-shown='no'  ></i></button>
                    <?php } ?>
                    <div id="reportrange" class=" btn " > 
                        <i class="icon24 i-calendar"></i><span></span><!--<b class="caret"></b>  --><i class="icon19 i-arrow-down-2"></i>
                    </div>  
                    <input type='text' value='' id='filter_keyword' style='width:200px;' placeholder='  Search by page title'>

                    <select id="filter_category" style="width:120px;">
                        <option value=''>-ALL-</option>
                        <?php
                        $s_fromdate = date("2013-09-01 00:00:00");
                        $s_todate = date('Y-m-d 23:59:59');                        
                        foreach ($category_list as $category) {
                            echo "<option value='" . $category->category_id . "'>" . strtoupper($category->name) . "</option>";
                        }
                        ?>
                    </select>

                    <select id="filter_department" style="width:120px;">
                        <option value=''>-ALL-</option>
                        <?php
                        foreach ($department_list as $department) {
                            echo "<option value='" . $department->department_id . "'>" . strtoupper($department->name) . "</option>";
                        }
                        ?>
                    </select>

                    <button class="btn" id='search_button'  title='Search'><i class=' i-search-5' style="font-size:25px;"></i></button>

                </span>
            </div>



                <div id="page_list" style="margin-right:2%;overflow-x:auto;">

                </div>
                <span class="loading_image"></span>


                <!-- End .container-fluid -->

            </div>
        </div>
        <!-- End .wrapper -->
    </section>
    <body>
        <div style="display:none;">
            <div id="add_new_modal">
                <div id="add_new_form">

                    <form action="#" method="POST" id="frm_add_new_page">
                        <table width="100%">
                            <tr>
                                <td>
                                    <label> Title:</label>
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
                                        <option value=''>-ALL-</option>
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
                                        <option value=''>-ALL-</option>
                                        <?php
                                        foreach ($department_list as $department) {
                                            echo "<option value='" . $department->department_id . "'>" . strtoupper($department->name) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>


                                <td>
                                    <label>Post In Group</label>  
                                    <select id="group_post" >
                                        <option value=''>-NONE-</option>
                                        <?php
                                        foreach ($group_menu_list as $group_item) {
                                            echo "<option value='" . $group_item->group_id . "'>" . strtoupper($group_item->group_name) . "</option>";
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
                <input type="checkbox" value="notify_users" id="add_notify_users" name="add_notify_users"> Notify Users
                <br><br>
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
                                    <label> Title:</label>
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
                                        <option value=''>-ALL-</option>
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
                                        <option value=''>-ALL-</option>
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

                    <input type="checkbox" value="update_all" id="update_all" name="update_all"> Update batch 
                    <input type="checkbox" value="notify_users" id="notify_users" name="notify_users"> Notify Users
                    <br><br>
                </div>
                <button id="edit_save_button" class="btn btn-primary">Save</button> <button id="edit_close" class="btn btn-danger">Close</button>

            </div>
        </div>




</div>

<!-- Modal -->
<div class="modal fade" id="confirmation_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation History</h4>
      </div>
      <div class="modal-body no-padding">
          <div class="history-content">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="confirmation_tbl" style="width:100%; max-height:300px;">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Usertype</th>
                  <th>Currency</th>
                  <th>Confirm Date</th>
                </tr>                    
                <tr>
                  <th><input data-num=0 type="text" placeholder="Search Name"></th>
                  <th>
                        <!--<input data-num=1 type="text" placeholder="Search Usertype" class="hidden">-->
                        <select data-num=1>
                            <option value="" selected>Search Usertype</option>
                            <? foreach($user_types as $data) if($data->GroupID !== "6" and in_array($data->GroupID,allow_post_view_notification(true)))echo '<option value="'.$data->UserTypeName.'">'.$data->UserTypeName.'</option>'; ?>
                         </select>
                  </th>
                  <th>
                      <select data-num=2>
                          <option value="" selected>Search Currency</option>
                          <? foreach($currencies as $data) echo '<option value="'.$data->Abbreviation.'">'.$data->Abbreviation.'</option>'; ?>
                      </select>
                      <!--<input data-num=2 type="text" placeholder="Search Currency" class="hidden">-->
                  </th>
                  <th></th>
                </tr>                         
                 

            
                </thead>
                <tbody >
                    
                </tbody>
            </table>
          </div>
        <div class="tabbable tabs-below hidden" style="height:300px; overflow: hidden;">
            <div class="tab-content" style="overflow-y: auto;"> 
                <div class="tab-pane active" id="confirm_list">
                </div>
                <div class="tab-pane" id="unconfirm_list">
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
         <ul class="pager pull-left no-margin"> <li class="active"> <a href="#confirm_list" id="confirm_list_btn" data-config="confirm" data-toggle="tab" >CONFIRM(<b id="confirm_list_count"></b>)</a> </li> <li> <a href="#unconfirm_list" id="unconfirm_list_btn" data-config="unconfirm" data-toggle="tab">UNCONFIRM(<b id="unconfirm_list_count"></b>)</a> </li> </ul>
         <form id="exportForm" role="form" class="no-margin" action="<?=base_url("portal/confirmationExport")?>" method="POST">
                   <input name="export-page" value="" type="hidden">
                   <input name="export-market" value="" type="hidden">
                   <button type="submit" class="btn btn-mini btn-success" target="_blank">Export</button>
                </form>        
      </div>
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
    var current_offset = 0;
    var limit = 10;
    set_up_dialogs();
    function generate_page_list(page_menu_id, offset, limit) {
        if (offset == 0 && limit == 10) {
            //$('#page_list').html('<br><br><br><br><br><br><br><br><center><img style="text-align:center;position:fixed;margin-left:-2%;" src=<?= base_url(); ?>media/images/preloaders/dark/7.gif height="100" width="100"></center>');
        } else {

            $('.loading_image').html('<center style="padding-bottom:5%;"><img src=<?= base_url(); ?>media/images/preloaders/dark/1.gif></center>');
        }

        $.post(base_url + "portal/generate_page_list",
                {
                    page_menu_id: page_menu_id,
                    market_code: "<?= $market_code ?>",
                    category: $('#filter_category').val(),
                    department: $('#filter_department').val(),
                    from: $("[name='daterangepicker_start']").val(),
                    to: $("[name='daterangepicker_end']").val(),
                    keyword: $('#filter_keyword').val(),
                    offset: offset,
                    limit: limit,
                },
                function(data) {
                    if (current_offset == 0) {
                        $("#page_list").html(data);
                    } else {

                        if (data.indexOf('No Pages Found</h1>') == -1) {
                            $("#page_list").append(data);
                        }
                    }
                    $('.loading_image').html('');
                    // $('#page_list').oLoader('hide');
                    if (page_menu_id == 0 || page_menu_id == null) {
                        $.post(base_url + "portal/get_homepage_content",
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
                                onImageUpload: function(files, editor, welEditable) {
                                    sendFile(files[0], editor, welEditable);
                                },
                                oninit: function() {
                                    var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                                    if (!is_chrome) {
                                        $('.note-editable').attr("onpaste", 'handlepaste(this, event)');
                                    }
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
                                onImageUpload: function(files, editor, welEditable) {
                                    sendFile(files[0], editor, welEditable);
                                },
                                oninit: function() {
                                    var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                                    if (!is_chrome) {
                                        $('.note-editable').attr("onpaste", 'handlepaste(this, event)');
                                    }
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
                        

                    }

            <? if ($this->input->post("hidden_page_updated_datetime")) {     ?>
                
                <? $date_formatted = str_replace("/", " ", $this->input->post("hidden_page_updated_datetime"));
                 if ($date_formatted >= date('Y-m-d H:i:s', strtotime('-2 days'))) { ?>
                            $.post("<?= base_url("portal/mark_as_viewed") ?>",
                                    {
                                        page_menu_id: page_menu_id,
                                        date_updated: "<?= $date_formatted ?>"
                                    },
                            function(data) {
                                setTimeout(function() {
                                    $("#new_notif_span" + page_menu_id).html("");
                                }, 3000);
                            }
                            );
                <? } ?>
            <? } ?>


                }
        );
    }
    

        
    $(function() {
        var page_menu_id = "<?= trim($page_menu_id) ?>";
        $('#search_button').click(function() {
            current_offset = 0;
            generate_page_list(page_menu_id, current_offset, limit);
        });
        generate_page_list(page_menu_id, current_offset, limit);


        $(window).scroll(function() {
            if(page_menu_id.length > 0){    
                if ( $(window).scrollTop() + $(window).height() == $(document).height() ) {
                    current_offset += 10;
                    generate_page_list(page_menu_id, current_offset, limit);

                }
            }
        });    

        

        
        
        
        $('#reportrange').daterangepicker({
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
            //$('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
            $("#s_fromdate").val(start.format('YYYY-MM-DD HH:mm:ss'));
            $("#s_todate").val(end.format('YYYY-MM-DD HH:mm:ss'));
        }
        );

    });
    
    function set_up_dialogs() {

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
        
        alertify.dialog('editDialog', function() {
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
    }

    function sendFile(file, editor, welEditable) {
        data = new FormData();
        data.append("file", file);
        $('.note-editor').oLoader({
            backgroundColor: 'black',
            fadeInTime: 500,
            fadeLevel: 0.2,
            image: base_url + 'media/images/loader.gif',
            style: 3,
            imagePadding: 5,
            imageBgColor: 'transparent'
        });
        $.ajax({
            data: data,
            type: "POST",
            url: base_url+"portal/configure/upload_image",
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                if (url.indexOf("media/uploads/portal/") >= -1) {
                    editor.insertImage(welEditable, url);
                } else {
                    $.jGrowl("Failed to attach image,please try again.", {position: 'center', group: 'error'});
                }
                $('.note-editor').oLoader('hide');
            }
        });
    }

    function sendBlob(elem, blob, filetype) {
        data = new FormData();
        data.append("file", blob, "<?= uniqid() ?>." + filetype);
        $('.note-editor').oLoader({
            backgroundColor: 'black',
            fadeInTime: 500,
            fadeLevel: 0.2,
            image: base_url+'media/images/loader.gif',
            style: 3,
            imagePadding: 5,
            imageBgColor: 'transparent'
        });
        $.ajax({
            data: data,
            type: "POST",
            url: base_url+"portal/configure/upload_image",
            cache: false,
            contentType: false,
            processData: false,
            dataType: "text",
            success: function(url) {
                $('.note-editor').oLoader('hide');
                if (url.indexOf("media/uploads/portal/") >= -1) {
                    $(elem).attr('src', url).attr('width', $(elem).width());
                }
            }
        });
    }
    
    function handlepaste(elem, e) {
        var savedcontent = elem.innerHTML;
        if (e && e.clipboardData && e.clipboardData.getData) {// Webkit - get data from clipboard, put into editdiv, cleanup, then cancel event

            if (e.clipboardData.getData('text/html')) {
                return false;
            }
            else if (e.clipboardData.getData('text/plain')) {
                return false;
            }
            else {

                setTimeout(function() {
                    try {

                        $('img', elem).each(function() {
                            var that = this;
                            if ($(that).attr('src').contains('base64')) {
                                convert_to_blob(that, $(that).attr('src'));
                            }

                        });
                    } catch (error) {

                    }
                }, 500);
                return false;
            }


        }
        else {
            return true;
        }
    }


    function convert_to_blob(elem, code) {
        var rawdata = code;
        rawdata = rawdata.split(",", 2);
        var contentType = rawdata[0].split(";")[0].split(":")[1];
        var b64Data = rawdata[1];
        var byteCharacters = atob(b64Data);
        var byteNumbers = new Array(byteCharacters.length);
        for (var i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        var byteArray = new Uint8Array(byteNumbers);
        var filetype = contentType.split("/")[1];
        var blob = new Blob([byteArray], {type: contentType});
        sendBlob(elem, blob, filetype);
    }
    
    
    function run_confirmed_tools(){
        $(".post-readers.search-string.get_confirmation_list").qtip({
            prerender: true,
            position: {
                my: 'center right',  // Position my top left...
                at: 'center left'
            },
            style: {
                classes: 'qtip-light qtip-rounded'
            },          
            content:{
                title: 'Confirm',
                text: function(event, api) {
                   $.ajax({ 
                       url: base_url + 'portal/get_confirmed'
                       ,data:{ page_id:$(this).data("id") }
                       ,method :'POST'
                       ,dataType:'json'
                       ,cache: false
                       })
                       .done(function(data) {
                           api.set('content.text', 'data')
                       })
                       .fail(function(xhr, status, error) {
                           api.set('content.text', status + ': ' + error)
                       })

                   return 'Loading...';
               }
            },
            show: {
                event: 'click'
            },
            hide: {
                inactive: 3000,
                event: 'unfocus'
            }            
        });
    }

    <?php if( !empty($notice) ){ ?>
        var pub = <?= $notice ?>;
        function publis_post(){
            if(typeof kn_publish === 'function' && typeof NOTIFS === 'object'){
                kn_publish(pub);
            }else{
                setTimeout(function(){
                    publis_post();
                },300);
            }
        }
        window.onload = publis_post();
    <?php } ?>
</script>

<style>
    .search-string{
        cursor: -moz-zoom-in; 
        cursor: -webkit-zoom-in; 
        cursor: zoom-in;
    }
    .modal-footer .pager li {
        line-height:10px;
    }
    .unconfirm-list .nav li{
        border-bottom: 1px solid #d5d5d5;
    }    
    label, input, button, select, textarea{
        margin-bottom: 0px !important;
        line-height: 1.5;
    }
    .alertify.ajs-resizable .ajs-body .ajs-content, .alertify.ajs-maximized .ajs-body .ajs-content {    
         bottom:10px;
    }
    .modal-content #confirmation_tbl input{
            width:90%;
    }    
    .dataTables_paginate.paging_bootstrap.pagination,
    .dataTables_paginate.paging_bootstrap.pagination ul li a{
            float:initial !important;

    }
    .DataTables_sort_icon.css_right {
            display:inline-block;
            float:right;
    }    
    div.dataTables_wrapper td, div.dataTables_wrapper th {
        padding: 5px;
    }
    .table th, .table td{ line-height: 1; }
    div.selector span{
      border-right-width: 5px;
      padding-right: 22px;
      border-left-width: 5px;
      width:100% !important;
    }
    div.selector{
      width:100% !important;
    }
    div.ajs-footer{

        min-height: 0 !important;

    }
</style>