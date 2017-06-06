<link href="<?= base_url(); ?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?= base_url(); ?>media/js/plugins/forms/select2/select2.js"></script> 
<script src="<?= base_url(); ?>media/js/plugins/forms/validation/jquery.validate.js"></script>
<script src="<?= base_url(); ?>media/js/plugins/forms/inputlimit/jquery.inputlimiter.1.3.min.js"></script>
<link href="<?= base_url(); ?>media/css/summernote-fonts/font-awesome.min.css" rel="stylesheet"/> 
<link href="<?= base_url(); ?>media/js/plugins/forms/summernote/summernote.css" rel="stylesheet"/> 
<script src="<?= base_url(); ?>media/js/plugins/forms/summernote/summernote.js"></script>

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
                                <h4 >Add New Page</h4>
                                <a href="#" class="minimize"></a>
                            </div> 
                            <!-- End .widget-title -->
                            <div class="widget-content panel-body">
                                <form id="page_form" class="form-horizontal form-widget-content" role="form" autocomplete="off" onsubmit="return false;
                                      ">
                                    <div class="control-group">
                                        <div class="span12">
                                            <label class="control-label" for="pagename">Page Title</label>
                                            <div class="controls controls-row">
                                                <input id="pagename" name="pagename" type="text" class="required span8" maxlength="50"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="span12">
                                            <label class="control-label" for="shortdesc">Short Description</label>
                                            <div class="controls controls-row">
                                                <textarea id="shortdesc" name="shortdesc" class="form-control limit span8" rows="5" maxlength="250"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="span2">
                                            <label class="control-label">Market</label>
                                        </div>
                                        <? foreach ($market_list as $key => $val) { ?>
                                            <div class="span1">
                                                <label class="radio-inline center">
                                                    <div class="checker" id="uniform-act_important">
                                                        <input class="cmarket" name="cmarket[]" type="checkbox" value="<?= $key ?>" style="margin-bottom: 5px;">
                                                    </div> <?= $val ?>
                                                </label>
                                            </div>
                                        <? } ?>
                                        <input type="hidden" id="marketlist" name="marketlist" value="0" />
                                    </div>
                                    <div class="control-group">
                                        <div class="span12">
                                            <label class="control-label" for="parentmenu">Page for</label>
                                            <div class="controls controls-row">
                                                <select name="parentmenu" id="parentmenu" class="select2 span8 select2-offscreen" tabindex="-1">
                                                    <option value='0'>Please select Menu</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="span10">
                                            <label class="control-label" for="pagecontent">Page Content</label>
                                            <div class="controls controls-row">
                                                <div id="pagecontent"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary" id="BtnSubmitForm">Save</button>
                                        <input type="button" class="btn btn-danger" id="BtnResetForm" value="Reset"/>
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
    $(function() {
        $('textarea.limit').inputlimiter({limit: $(this).attr("maxlength")});
        $("#pagecontent").summernote({
            height: 300,
          /*  onImageUpload: function(files, editor, welEditable) {
                sendFile(files[0], editor, welEditable);
            },*/
            oninit: function() {
                getFileList();
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
        $(".cmarket").click(function() {
            clearTimeout(marketTimer);
            $("#marketlist").val($("#page_form .cmarket:checked").length);
            $("#parentmenu").html("<option value='0'>Not Applicable</option>");
            if ($("#page_form .cmarket:checked").serialize())
                marketTimer = setTimeout(getSubmenuList, 500);
        });

        $("#parentmenu").select2({placeholder: "Select"});

        $("#page_form").validate({
            submitHandler: function(form) {
                var error = false;
                if ($("#shortdesc").val() == "") {
                    $("#shortdesc").removeClass("valid");
                    $("#shortdesc").addClass("error");
                    $("#shortdesc-error").removeClass("hidden");
                    $("#shortdesc-error").show();
                    $("#shortdesc-error").focus();
                    error = true;
                }
                else {
                    $("#shortdesc").removeClass("error");
                    $("#shortdesc").addClass("valid");
                    $("#shortdesc-error").addClass("hidden");
                    $("#shortdesc-error").hide();
                }
                if ($("#pagename").val() == "") {
                    $("#pagename").removeClass("valid");
                    $("#pagename").addClass("error");
                    $("#pagename-error").removeClass("hidden");
                    $("#pagename-error").show();
                    $("#pagename-error").focus();
                    error = true;
                }
                else {
                    $("#pagename").removeClass("error");
                    $("#pagename").addClass("valid");
                    $("#pagename-error").addClass("hidden");
                    $("#pagename-error").hide();
                }
                if (error)
                    return false;
                managePage();
            },
            /*ignore: null,
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
             }*/
        });

        $("#BtnResetForm").click(function() {
            formreset();
        });
    });

    var managePage = function() {
        $.ajax({
            data: $("#page_form").serialize() + "&content=" + encodeURIComponent($("#pagecontent").code()),
            type: "POST",
            url: "<?= base_url("portal/configure/save_page") ?>",
            dataType: "JSON",
            cache: false,
            beforeSend: function() {
                $("#BtnSubmitForm").addClass("disabled");
                $("#BtnResetForm").addClass("disabled");
                $("#BtnSubmitForm").attr("disabled", "disabled");
                $("#BtnResetForm").attr("disabled", "disabled");
                $("html, body").animate({scrollTop: 0}, "slow");
            },
            error: function() {
                $("#BtnSubmitForm").removeClass("disabled");
                $("#BtnResetForm").removeClass("disabled");
                $("#BtnSubmitForm").removeAttr("disabled", "disabled");
                $("#BtnResetForm").removeAttr("disabled", "disabled");
            },
            success: function(response) {
                $("#BtnSubmitForm").removeClass("disabled");
                $("#BtnResetForm").removeClass("disabled");
                $("#BtnSubmitForm").removeAttr("disabled", "disabled");
                $("#BtnResetForm").removeAttr("disabled", "disabled");

                if (response.error)
                {
                    createMessageMini($(".form-widget-content"), response.msg, "error");
                }
                else
                {
                    createMessageMini($(".form-widget-content"), response.msg, "success");
                    formreset();
                }
            }
        });
    }

    function getSubmenuList() {
        $.ajax({
            url: "<?= base_url("portal/configure/get_submenu_by_market") ?>",
            type: "post",
            dataType: "json",
            data: $("#page_form .cmarket:checked").serialize(),
            error: function() {
                $("#parentmenu").html("<option value=''>Not Applicable</option>");
            },
            success: function(response) {
                if (response.error) {
                    $("#parentmenu").html("<option value='0'>Not Applicable</option>");
                }
                else {
                    $("#parentmenu").html("<option value='0'>Not Applicable</option>" + response.content);
                }
            }
        });
    }

    function formreset() {
        $('#page_form')[0].reset();
        clearSelectbox($("div.controls"));
        $("ul.select2-choices li.select2-search-choice").remove();
        $.uniform.update("input[type=checkbox], input[type=radio]");
        $("#parentmenu").html("<option value='0'>Not Applicable</option>");
        $("#pagecontent").code("");
    }

    function sendFile(file, editor, welEditable) {
        data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            url: "<?= base_url("portal/configure/upload_image") ?>",
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                editor.insertImage(welEditable, url);
                getFileList();
            }
        });
    }

    function getFileList() {
        $(".note-group-select-from-uploaded .uploaded-list").html("No Files Found");
        $.ajax({
            url: "<?= base_url("portal/configure/get_image_list") ?>",
            type: "html",
            cache: false,
            success: function(html) {
                if (html.length > 1) {
                    $(".note-group-select-from-uploaded .uploaded-list").html(html);

                    $(".note-group-select-from-uploaded .uploaded-list img").click(function() {
                        $("#pagecontent").code("<p><br/></p><img src='" + $(this).attr("src") + "'/>" + $("#pagecontent").code());
                        $('.modal').modal('hide');
                    });
                }
            }
        });
    }
</script>