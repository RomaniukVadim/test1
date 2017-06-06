/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function(){
    $.ajax({
        url:base_url+'portal/get_unconfirm_list/'
        ,method :'POST'
        ,dataType:'json'
        ,cache: true
    }).done(function(post){ 
        if(post.data.length !== 0){
            $.each(post.data,function(keys,data){
               $("#unconfirm_post ul").prepend("<li>\
                                    <a href='"+base_url+"portal/market/"+data.market+"/"+data.menu_id+"/#"+data.page_id+"' >\
                                    <h3 class='center'>"+data.page_title+"</h3>\
                                    <small style='font-size: x-small;'><b>Date Created:</b> "+data.created_datetime+" | <b>Last Updated:</b> "+data.updated_datetime+"</small>\
                                    </a></li>");
            });
            $("#unconfirm_post").modal("show");
            $("#unconfirm_post .modal-content .modal-body").niceScroll(".unconfirm-list",{cursorcolor:"#D5D5D5",cursorwidth:"10px",cursorborderradius :"2px",autohidemode:false});
        }
    });
    $("#unconfirm_post").on("shown.bs.modal",function(){
        $("#unconfirm_post .modal-content .modal-body").getNiceScroll().resize(); 
    });
});

