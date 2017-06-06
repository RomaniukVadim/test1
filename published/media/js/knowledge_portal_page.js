/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 var confirmation_tbl = $("#confirmation_tbl");
 var dataTableConfig = {
        sDom: "rt<'row-fluid center'<'span12'i><'span12 no-margin'p>>",
        sPaginationType : "bootstrap",
        bJQueryUI : true,
        iDisplayLength: 10,
        aaData: [],
        aoColumns: [
            {mDataProp : "mb_nick", bSortable:false},
            {mDataProp : "usertype", bSortable:false, sClass:"center"},
            {mDataProp : "currencies", bSortable:false},
            {mDataProp : "date_updated_confirm", bSortable:true, sClass: "nowrap"}
        ]
    };
function slide_to_page(){
  if(document.location.hash !== "" && typeof $(document.location.hash).offset() !== "undefined")
        $('html, body').animate({
             scrollTop: ($(document.location.hash).offset().top - 60)
        }, 2000); 
}

$(function() {
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
    $(document).on("click", "#show_add_form", function() {
        $('#pagecontent').code("<p><br></p>");
        $('#shortdesc,#pagename').val('');
        alertify.genericDialog($('#add_new_modal')[0]).set('selector').set('closable', false);
        $('.ajs-dialog').attr("style", "height: 85%; min-height: 112px; width: 80%; min-width: 548px; max-width: none; ");
    });


    $(document).on("click", "#close_save", function() {
        alertify.genericDialog($('#add_new_modal')[0]).close('selector');
    });

    $(document).on("click", ".edit_page", function() {

        alertify.editDialog($('#edit_modal')[0]).set('selector').set('closable', false);
        $('.ajs-dialog').attr("style", "height: 85%; min-height: 112px; width: 80%; min-width: 548px; max-width: none; ");
        var that = this;
        var edit_id = $(that).data('id');
        $('#edit_page_id').val(edit_id);
        $('#edit_pagecontent').code($('#page_content_' + edit_id).html());
        $('#edit_pagename').val($('#page_title_' + edit_id).html());
        $('#edit_category').val($(that).attr('data-category'));
        $('#edit_department').val($(that).attr('data-department'));
        $('#edit_department,#edit_category').trigger('change');
        if ($('#update_all').is(":checked")) {
            $('#update_all').trigger('click');
        }

    });

    $(document).on("click", "#edit_close", function() {
        alertify.editDialog($('#edit_modal')[0]).close('selector');
    });

    $("#pagecontent,#edit_pagecontent").summernote({
        height: '250',
        onImageUpload: function(files, editor, welEditable) {
            sendFile(files[0], editor, welEditable);
            // console.log(editor)
        }, oninit: function() {
            var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
            if (!is_chrome) {
                $('.note-editable').attr("onpaste", 'handlepaste(this, event)');
            }

        }, toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough']],
            ['font', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['picture', 'table', 'hr', 'link']],
            ['action', ['undo', 'redo']]
        ]
    });
    
    
    $(document).on("click",".post-readers.search-string.get_confirmation_list",function(){
        $("#confirmation_details").data({"id":$(this).data("id"), "market":$(this).data("market")}).modal("show");
    }).on("show.bs.modal","#confirmation_details",function(){
        $("input[name='export-page']").val($(this).data("id"));
        $("input[name='export-market']").val($(this).data("market"));
        
//        confirmation_tbl.find("tbody").html("<tr><td colspan=3 class='center'>LOADING . . </td></tr>");
        $.ajax({
            url:base_url+"portal/get_confirm"
            ,data:{ page_id: $(this).data("id"), market:$(this).data("market") }
            ,method :'POST'
            ,dataType:'json'
            ,cache: false
        }).done(function(data){
//            $("#confirmation_tbl tbody").html("");
//            $.each(data.unconfirm,function(key,tbl){
//                $("#confirmation_tbl tbody").prepend(
//                        "<tr><td class='center'>"+tbl.mb_nick+"</td>\
//                        <td class='center'>"+tbl.mb_nick+"</td>\
//                        <td class='center'>"+tbl.date_updated_confirm+"</td></tr>"
//                        );
//            });
           if(confirmation_tbl.find("tbody").html().trim() !== ""){
                confirmation_tbl.fnClearTable();
                confirmation_tbl.fnDestroy();
                confirmation_tbl.find("tbody").empty();
           }
            
           dataTableConfig.aaData = data.confirm;
           
           confirmation_tbl.dataTable(dataTableConfig);
           confirmation_tbl.css("width","100%");

            $.each(data,function(key,item){
//                var divForm = $("#"+key+"_list");
                $("#"+key+"_list_count").html(item.length);
//                divForm.html("");
//                $.each(item,function(i,data){
//                    divForm.prepend(
//                            '<div class="well well-small hr-bottom-5"><blockquote class="no-margin">\
//                            <p>'+data.mb_nick+'</p>\
//                            <small>'+data.date_updated_confirm+'</small>\
//                            </blockquote></div>'
//                    );
//                });
            });
            /*
            $("div.modal-body div.tabbable.tabs-below").niceScroll(".tab-content",{cursorcolor:"#D5D5D5",cursorwidth:"10px",cursorborderradius :"2px",autohidemode:false});
            */
        });
    });
    
  $("#confirmation_tbl input").on("input", function() {
    confirmation_tbl.fnFilter( this.value, $("#confirmation_tbl input").index(this) );
  });    
  
  $("#confirmation_tbl select").on("change", function() {
    confirmation_tbl.fnFilter( this.value, $("#confirmation_tbl select").index(this)+1 );
  });  
  
  $("#confirm_list_btn, #unconfirm_list_btn").on("click",function(){
      confirmation_tbl.find("tbody").html("<tr><td colspan=3 class='center'>LOADING . . </td></tr>");
      var that = this;
        $.ajax({
            url:base_url+"portal/get_confirm"
            ,data:{ page_id: $("#confirmation_details").data("id"), market:$("#confirmation_details").data("market") }
            ,method :'POST'
            ,dataType:'json'
            ,cache: false
        }).done(function(data){
           if(confirmation_tbl.find("tbody").html().trim() !== ""){
                confirmation_tbl.fnClearTable();
                confirmation_tbl.fnDestroy();
                confirmation_tbl.find("tbody").empty();
           }

           dataTableConfig.aaData = $(that).data("config")=="confirm"?data.confirm:data.unconfirm;
           
           confirmation_tbl.dataTable(dataTableConfig);
           confirmation_tbl.css("width","100%");

        });     
  });
    
//        $("#confirmation_details div.modal-footer ul li a").on("click",function(){
//            setTimeout(function(){ // create a delay on loading the confirmation history
//                $("div.modal-body div.tabbable.tabs-below").getNiceScroll().resize();    
//            },100);
//        });

    $(document).on('change',".post-read-box input[type='checkbox']",function(){
        var that = this;
        if($(this).prop("checked"))
            if(confirm("Are you sure that you fully understand the entire content?")){
                $.ajax({
                    url:base_url+'portal/post_confirm/'
                    ,data: { page_id:$(this).val() }
                    ,method :'POST'
                    ,dataType:'json'
                }).done(function(ret){ 
                    if(typeof ret.confirmed !== 'undefined' && parseInt(ret.confirmed) > 0 ){
                        $("#"+$(that).val()+" .post-readers .post-readers-count").html(ret.confirmed);
                        if(ret.supervisor == 1)$("#"+$(that).val()+" .post-readers").addClass("search-string").addClass("get_confirmation_list");
                        $("#"+$(that).val()+" .checkbox").addClass("hidden");
                    }else{
                        if(typeof ret.confirmed !== 'undefined' && parseInt(ret.confirmed) == 0 ){
                           alert(ret.confirmed);
                        }
                    }
                });

            }else{
                $(this).prop("checked",false);
            }

    });            

        $(document).on("click", "#save_button", function() {
            if ($('#pagename').val() == '') {
                $.jGrowl("Please input the page name", {position: 'center', group: 'error'});
                return false;
            }

            $('#add_new_form').oLoader({
                backgroundColor: 'black',
                fadeInTime: 500,
                fadeLevel: 0.2,
                image: base_url+'media/images/loader.gif',
                style: 3,
                imagePadding: 5,
                imageBgColor: 'transparent'
            });
            var tree_save_instance = $("#main_navigation").jstree(true);
            var menu_node = tree_save_instance.get_node($("span#menu_title").data("page_menu_id"));
            var menu_parents = new Array();
            try {
                menu_parents = menu_parents.join();
            } catch (e) {

            }
            setTimeout(function() {
                $.post(base_url+"portal/configure/save_page",
                        {
                            pageid: "new",
                            content: $('#pagecontent').code(),
                            pagename: $('#pagename').val(),
                            shortdesc: $('#shortdesc').val(),
                            market: $("span#menu_title").data("marketcode"),
                            page_menu_id: $("span#menu_title").data("page_menu_id"),
                            category: $('#category').val(),
                            department: $('#from_department').val(),
                            menu_parents: menu_parents,
                            notify_users: $('#add_notify_users').is(":checked"),
                            group_post: $('#group_post').val()

                        },
                function(data) {
                    if (data.status !== 'false' && data.status != '' && data.status != null) {
                        if ($('.search-results').attr('isempty') == 'yes') {
                            $('.search-results').html(data.status);
                            $('.search-results').attr('isempty', 'no');
                        } else {
                            current_offset = 0;
                            generate_page_list($("span#menu_title").data("page_menu_id"), current_offset, limit);
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

                    } else {
                        $.jGrowl("Failed to add page, please try again...", {position: 'center', group: 'error'});
                        $('#add_new_form').oLoader('hide');
                    }
                    if(typeof data.notice.channel !== "undefined"){
                        kn_publish(data.notice);
                    }
                }
                        ,'json'
                );
            }, 0);
        });

    $(document).on("click", "#save_home_page", function() {
        $('#homepage_panel').oLoader({
            backgroundColor: 'black',
            fadeInTime: 500,
            fadeLevel: 0.2,
            image: base_url + 'media/images/loader.gif',
            style: 3,
            imagePadding: 5,
            imageBgColor: 'transparent'
        });
        $.post(base_url + "portal/configure/save_page",
                {
                    pageid: page_id,
                    content: $('#homepage_content').code(),
                    pagename: "Homepage-" + $("#show_add_form").data("market"),
                    shortdesc: "Homepage-" + $("#show_add_form").data("market"),
                    market: $("#show_add_form").data("marketcode"),
                    page_menu_id: "0"
                },
                "json").done(function(data){
                    if (data.status != 'false') {
                        $.jGrowl("Updated successfully...", {position: 'center', group: 'success'});
                        $('#homepage_panel').oLoader('hide');
                        is_updated = true;
                    } else {
                        $.jGrowl("Failed to update page, please try again...", {position: 'center', group: 'error'});
                        $('#homepage_panel').oLoader('hide');
                        is_updated = false;
                    }
                    if(typeof data.notice.channel !== "undefined"){
                        kn_publish(data.notice);
                    }

                });
    });
    
    $(document).on("click", "#edit_save_button", function() {
            $('#frm_edit_page').oLoader({
                backgroundColor: 'black',
                fadeInTime: 500,
                fadeLevel: 0.2,
                image: base_url + 'media/images/loader.gif',
                style: 3,
                imagePadding: 5,
                imageBgColor: 'transparent'
            });
            var tree_save_instance = $("#main_navigation").jstree(true);
            var menu_node = tree_save_instance.get_node($("span#menu_title").data("page_menu_id"));
            var menu_parents = new Array();
            try {
                menu_parents = menu_parents.join();
            } catch (e) {

            }
            $.post(base_url + "portal/configure/save_page",
                    {
                        pageid: $('#edit_page_id').val(),
                        content: $('#edit_pagecontent').code(),
                        pagename: $('#edit_pagename').val(),
                        shortdesc: $('#edit_shortdesc').code(),
                        edit_category: $('#edit_category').val(),
                        edit_department: $('#edit_department').val(),
                        page_menu_id: $("span#menu_title").data("page_menu_id"),
                        market: $("span#menu_title").data("marketcode"),
                        menu_parents: menu_parents,
                        is_update_all: $('#update_all').is(":checked"),
                        notify_users: $('#notify_users').is(":checked"),
                        batch_id: $('#batch_' + $('#edit_page_id').val()).data('batch')
                    },
            function(data) {
                if (data.status !== 'false') {
                    $.jGrowl("Updated successfully...", {position: 'center', group: 'success'});
                    current_offset = 0;
                    limit = 10;
                    generate_page_list($("span#menu_title").data("page_menu_id"), current_offset, limit);
                    $('#frm_edit_page').oLoader('hide');
                    is_updated = true;
                } else {
                    $.jGrowl("Failed to update page, please try again...", {position: 'center', group: 'error'});
                    $('#frm_edit_page').oLoader('hide');
                    is_updated = false;
                }
                
                if(typeof data.notice.channel !== "undefined"){
                    kn_publish(data.notice);
                }

            },"json"
            );

        });

    $(document).on("click", ".delete_page", function() {
         var that = this;
         try {
             if ($('#delete_all').is(":checked")) {
                 $('#delete_all').removeAttr('checked');
             }
         } catch (e) {
         }
         
         var content = '<div class="row-fluid"><div class="span12">Are you sure you want to delete this page?</div>';
            content += '<div class="span12"><input type="checkbox" value="delete_all" id="delete_all"><sub> Delete all in batch</sub></div></div>';
         
         var pre = "<br><br>";
         alertify.confirm(content, function() {
             $.post(base_url+"portal/delete_page_v2",
                     {
                         page_id: $(that).data("id"),
                         batch_id: $('#batch_' + $(that).data("id")).data('batch'),
                         market: $("span#menu_title").data("marketcode"),
                         delete_all: $('#delete_all').is(":checked")
                     },
             function(data) {
                 if (parseInt(data.status) == 1) {
                     $.jGrowl("Deleted successfully...",
                             {
                                 position: 'center',
                                 group: 'success',
                                 life: 3000,
                             });
                     current_offset = 0;
                     $('#' + $(that).data("id")).remove();

                 } else {
                     $.jGrowl("Failed to delete page, please try again...",
                             {
                                 position: 'center',
                                 group: 'error',
                                 life: 3000,
                             });
                 }
                 if(typeof data.notice.channel !== "undefined"){
                     kn_publish(data.notice);
                 }

             },"json"
             );
         }).setting('labels', {'ok': 'Yes', 'cancel': 'Cancel'});
     });
});



function kn_publish(_data){
    NOTIFS
        .global(true)
        .onconnect(function() {
            if(typeof _data.channel == "undefined"){
                $.each(_data,function(key,data){
                    NOTIFS.publish( data["channel"],(typeof data["msg"] == "string"? JSON.parse(data["msg"]) : data["msg"] ) );
                });
            }else{
                NOTIFS.publish( _data.channel,(typeof _data.msg == "string"? JSON.parse(_data.msg) : _data.msg ) );
            }
        
    });
}