<!-- jsTree  -->
<script src="<?= base_url() ?>media/js/plugins/jsTree/dist/jstree.js?version=2"></script>
<link href="<?= base_url() ?>media/js/plugins/jsTree/dist/themes/proton/style.css" rel="stylesheet"/> 

<!-- Alertify-->
<script src="<?= base_url() ?>media/js/plugins/alertify/alertify.min.js"></script>
<link href="<?= base_url() ?>media/js/plugins/alertify/css/alertify.css" rel="stylesheet"/> 
<!-- Alertify-->
<link href="<?= base_url() ?>media/js/plugins/forms/select2/select2.css" rel="stylesheet"/> 
<script src="<?= base_url() ?>media/js/plugins/forms/select2/select2.js"></script>  



<style >
    .jstree-anchor {
        white-space : normal !important;
        /*ensure lower nodes move down*/
        height : auto !important;
        /*offset icon width*/
        padding-right : 15% !important;
        font-size:11px;

    }
    .nav_item{
        color:black;
        font-family: Verdana, Geneva, sans-serif;

    }
    .i-new{
        color:red !important;
        font-size:20px;
    }
    .sidebar-wrapper{


    }
    #cboxWrapper{
        background-color:black;
    }
    .ajs-dialog{
        background-color:white;
    }
    #main_navigation{

        overflow-x: auto;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: inherit !important;
        font-weight: 700;
        color: inherit;
    }
    #tree_manage_grouping .jstree-container-ul{
        color:black !important;
        font-size:30px !important;
    }
</style>

<!-- sibar -->                     
<aside id="sidebar"> 

    <div class="side-options">
        <ul>
            <li><a href="#" id="collapse-nav" class="act act-primary tip" title="Collapse navigation"><i class="icon16 i-arrow-left-7"></i></a></li>
        </ul>
    </div> 
    <form action='#' name="hidden_form" method='POST' id='hidden_form' style="display:none;" >
        <input type="hidden" name="id" value="" id="id">
        <input type="hidden" name="hidden_page_updated_datetime" value="" id="hidden_page_updated_datetime">
        <input type="submit" value="Submit" name="Submit" id="Submit">
    </form>
    <div class="sidebar-wrapper">
        <nav id="mainnav">
            <ul class="nav nav-list"> 
                <li><a href="<?= base_url("portal/dashboard"); ?>"><span class="icon"><i class="icon20 i-brain"></i></span><span class="txt">Knowledge Portal</span></a></li>         
            </ul>
        </nav>

        <!-- End #mainnav -->

        <? if (!empty($market)) { ?>

            <div class="sidebar-widget market-pages">
                <h4 class="sidebar-widget-header">
                    <i class="icon i-info"></i> <?= $market ?> 

                    <? if (admin_access() || csd_supervisor_access()) { ?>
                        <? if (admin_access()) { ?>
                            <i id="manage_grouping_button" title="Manage grouping" class="icon8  i-cog-2" style="cursor:pointer;float:right"></i>
                        <? } ?>

                        <i id="add_parent_button" title="Add new menu" class="icon8 i-plus" style="cursor:pointer;float:right;margin-right: 10px;"> </i>

                    <? } ?>



                </h4>
                <span id="side_bar_container">
                    <nav id="main_navigation" >

                    </nav>
                </span>
            </div>
        <? } ?>

    </div>
    <!-- End .sidebar-wrapper --> 




</aside>
<!-- End #sidebar -->
<div style="display:none">
    <div id="privacy_settings_modal">
        <div id="">
            <input type='hidden' value='' id='privacy_menu_id'>
            <label>Can be viewed by</label>
            <span id="viewers_list" multiple="multiple" ></span>
            <input type="hidden" name="viewers_list_val" id="viewers_list_val" value=""  /> 
        </div>
        <br><br><br>
        <button id="update_privacy_settings" class="btn btn-primary">Save</button>
        <button id="close_privacy_settings" class="btn btn-danger">Close</button>
    </div>
</div>


<div style="display:none">
    <div id="manage_grouping_modal">
        <select id="menu_group_select">
        </select><br>

        <input type="text" value="" id="group_name" placeholder="  Group Name" style='display:none'><br>
        <nav id="tree_manage_grouping" >
        </nav>
        <hr>
        <center><button id="save_grouping_button" class="btn btn-primary">Save</button> <button id="delete_group" style='display:none;' class="btn btn-danger">Delete</button> </center>

    </div>
</div>
<script>
    var jstree_nav;
    var admin_only =<?= admin_access() ?>;
    var csd_supervisor_access =<?= csd_supervisor_access() ?>;
    var newly_updated_menus = new Array();
    $(function() {
        get_all_menu_groups_unrestricted();
        $('#themer').css('margin-top', '8%');
        $.post("<?= base_url("portal/get_all_user_type") ?>",
                {
                    parentmenu_id: 0,
                },
                function(data) {
                    $('#viewers_list').html(data);
                    //$('input[type=checkbox]').uniform();
                }
        );

        $(document).on("click", "#delete_group", function() {
            $.post("<?= base_url("portal/delete_group") ?>",
                    {
                        group_id: $('#menu_group_select').val(),
                    },
                    function(data) {
                        if (data) {
                            alert('Deleted Successfully!');
                            get_all_menu_groups_unrestricted();
                            $('#menu_group_select').val('0').trigger('click');
                        } else {
                            alert('Failed to delete group');
                        }
                    }
            );
        });



        $(document).on("click", "input:checkbox[name='viewers_list_selection[]']", function() {
            var selected_viewers = '';
            selected_viewers = $("input[name='viewers_list_selection[]']:not(:checked)").map(function() {
                return this.value;
            }).get().join(',');
            $("#viewers_list_val").val(selected_viewers);
        });
        $('#update_privacy_settings').click(function() {
            $.post("<?= base_url("portal/update_menu_settings") ?>",
                    {
                        menu_id: $('#privacy_menu_id').val(),
                        hidden_to: $('#viewers_list_val').val()
                    },
            function(data) {
                if (data == 1) {
                    alert('Updated successfully...');
                    $("#main_navigation").jstree('refresh').on('refresh.jstree', function() {
                        initialize();
                    });
                } else {
                    alert('Failed to update menu, please try again...');
                }
            }
            );
        });
        var old_index;
        setTimeout(function() {
            jstree_nav = $('#main_navigation').jstree({
                //containment: "parent",
                "core": {
                    "animation": 0,
                    "check_callback": false,
                    "themes": {"proton": true},
                    'data': {
                        'url': "<?= base_url("portal/generate_json_nav_parent/$market") ?>"
                    },
                    'themes': {
                        'name': 'proton',
                        'responsive': true
                    },
                }, "plugins": [
                    "types", "contextmenu"
                ],
                'contextmenu': {
                    'items': customMenu
                },
            }).on('ready.jstree', function() {
                initialize();
                $(document).on("click", ".nav_item", function() {
                    var href = $(this).data('location');
                    var id = $(this).data('id');
                    var hidden_page_updated_datetime = $(this).data('page_updated_datetime');
                    $('#hidden_form').attr('action', href);
                    $('#id').val(id);
                    $('#hidden_page_updated_datetime').val(hidden_page_updated_datetime);
                    $('#Submit').click();
                }
                );
                var node_details = "<?= $this->input->post("id") ?>";
                var node_still_present = false;
                if ($('#main_navigation').jstree(true).get_node(node_details))
                {
                    node_still_present = true;
                } else {
                    node_still_present = false;
                }
                if (node_details != "" && node_details != 0 && node_still_present) {
                    expandNode(node_details);
                    initialize_child_sorting(node_details);
                }
                $.post("<?= base_url("portal/check_for_new_updates/$market_code") ?>",
                        {
                            parentmenu_id: 0
                        },
                function(data) {
                    if (data) {
                        newly_updated_menus = data;
                        $.each(newly_updated_menus, function(i, val) {
                            $('#new_notif_span' + val.menu_id).html("<i class='red icon20 i-new'></i>");
                            var tree_save_instance = $("#main_navigation").jstree(true);
                            var menu_node = tree_save_instance.get_node(val.menu_id);
                            var menu_parents = menu_node.parents;
                            try {
                                menu_parents.pop();
                                $.each(menu_parents, function(i2, val2) {
                                    $('#new_notif_span' + val2).html("<i class='red icon20 i-new'></i>");
                                });
                            } catch (e) {

                            }

                        });
                    }
                },
                        'json'
                        );
            }).on("open_node.jstree", function(e, data)
            {
                $.each(newly_updated_menus, function(i, val) {
                    $('#new_notif_span' + val.menu_id).html("<i class='red icon20 i-new'></i>");
                    var tree_save_instance = $("#main_navigation").jstree(true);
                    var menu_node = tree_save_instance.get_node(val.menu_id);
                    var menu_parents = menu_node.parents;
                    //  menu_parents.pop();
                    try {
                        $.each(menu_parents, function(i2, val2) {
                            $('#new_notif_span' + val2).html("<i class='red icon20 i-new'></i>");
                        });
                    } catch (e) {

                    }

                });
                initialize_child_sorting(data.node.id);
                // $('.jstree-leaf a i').removeClass('i-book-2').addClass('i-file-4');
            });
        }, 0);
        $("#add_parent_button").click(function() {
            $('#menu_name').val('');
            var pre = "<b>Input menu name: </b><textarea id='menu_name' cols=50 rows=2></textarea>";
            alertify.confirm(pre, function() {
                if ($('#menu_name').val() != '') {
                    $.post("<?= base_url("portal/configure/save_menu") ?>",
                            {
                                parentmenu_id: 0,
                                menuid: "new",
                                name: $('#menu_name').val(),
                                cmarket: "<?= $market ?>"
                            },
                    function(data) {
                        $("#main_navigation").jstree('refresh').on('refresh.jstree', function() {
                            expandNode(data);
                            initialize();
                        });
                    }
                    );
                } else {
                    alert("Please input menu name");
                }

            }, function() {
                $('#menu_name').val('');
            }).setting('labels', {'ok': 'Add', 'cancel': 'Cancel'});
        });
        alertify.dialog('privacySettings', function() {
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
                            title: "<h3>Privacy Settings</h3>",
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
        $(document).on("click", "#close_privacy_settings", function() {
            alertify.privacySettings($('#privacy_settings_modal')[0]).close('selector');
        });
        $(document).on("click", "#manage_grouping_button", function() {
            generate_menu_management();
            setTimeout(function() {
                $('#menu_group_select').val('0').trigger('click');
            }, 0);

            alertify.manageGrouping($('#manage_grouping_modal')[0]).set('selector');
            $('.ajs-dialog').attr("style", "height: 600px; min-height: 112px; width: 500px; min-width: 200px; max-width: none; left: 41px; top: -11px;");
        });
        $(document).on("click", "#save_grouping_button", function() {
            var checked_nodes = [];
            $.each($("#tree_manage_grouping").jstree("get_checked", true), function() {

                if (!isNaN(this.id)) {
                    checked_nodes.push(this.id);
                }
            });
            if ($.trim($('#group_name').val()) == '' && $('#menu_group_select').val() == 'new') {
                alert("Please input group name");

            } else if ($('#menu_group_select').val() == '0') {
                alert("Please select a group to update");
            } else if (!checked_nodes[0]) {
                alert("Please select atleast one menu to add in a group.");
            } else {
                $.post("<?= base_url("portal/save_menu_grouping") ?>",
                        {
                            checked_nodes: checked_nodes,
                            group_name: $('#group_name').val(),
                            group_id: $('#menu_group_select').val(),
                        },
                        function(data) {
                            if (data) {

                                alert("Saved successfully...");
                                setTimeout(function() {
                                    get_all_menu_groups_unrestricted();
                                    $('#menu_group_select').val('0').trigger('click');
                                }, 0);
                            } else {
                                alert("Failed to add group");
                            }
                        }
                );
            }


        });
        alertify.dialog('manageGrouping', function() {
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
                            title: "<h3>Menu Grouping</h3>",
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
        $(document).on("click", "#close_manage_grouping", function() {
            alertify.manageGrouping($('#manage_grouping_modal')[0]).close('selector');
        });
    });
    function expandNode(nodeID) {
        //Expand all nodes up to the root (the id of the root returns as '#')
        var real_nodeID = nodeID;
        while (nodeID != '#') {
            // Open this node
            $("#main_navigation").jstree("open_node", nodeID)
            // Get the jstree object for this node
            var thisNode = $("#main_navigation").jstree("get_node", nodeID);
            // Get the id of the parent of this node

            nodeID = $("#main_navigation").jstree("get_parent", thisNode);
            $('#main_navigation').jstree('select_node', thisNode);
            initialize_child_sorting(nodeID.id);
        }


    }

    function customMenu(node) {
        if (admin_only || csd_supervisor_access) {
            var tree = $("#main_navigation").jstree(true);
            var items = {
                createItem: {
                    "label": "Add",
                    "action": function() {
                        /*  node = tree.create_node(node,false );
                         tree.edit(node);*/
                        var pre = "<b>Input menu name: </b><textarea id='menu_name' cols=50 rows=2></textarea>";
                        $('#menu_name').val('');
                        alertify.confirm(pre, function() {
                            if ($('#menu_name').val() != '') {
                                $.post("<?= base_url("portal/configure/save_menu") ?>",
                                        {
                                            parentmenu_id: node.id,
                                            menuid: "new",
                                            name: $('#menu_name').val(),
                                            cmarket: "<?= $market ?>"
                                        },
                                function(data) {
                                    $("#main_navigation").jstree('refresh').on('refresh.jstree', function() {
                                        expandNode(data);
                                        initialize();
                                    });
                                }
                                );
                            } else {
                                alert('Please input menu name');
                            }
                        }, function() {
                            $('#menu_name').val('');
                        }).setting('labels', {'ok': 'Add', 'cancel': 'Cancel'});
                    }
                },
                Rename: {// The "rename" menu item
                    label: "Rename",
                    action: function() {

                        /*  node = tree.create_node(node,false );
                         tree.edit(node);*/
                        var pre = "<b>Update name: </b><textarea id='update_menu_name' cols=50 rows=2>" + node.original.title + " </textarea>";
                        alertify.confirm(pre, function() {


                            $.post("<?= base_url("portal/configure/save_menu") ?>",
                                    {
                                        menuid: node.id,
                                        name: $('#update_menu_name').val(),
                                        cmarket: "<?= $market ?>"
                                    },
                            function(data) {
                                $("#main_navigation").jstree('refresh').on('refresh.jstree', function() {
                                    expandNode(data);
                                    initialize();
                                });
                            }
                            );
                        }, function() {

                        }).setting('labels', {'ok': 'Update', 'cancel': 'Cancel'});
                    }
                },
                deleteItem: {// The "delete" menu item
                    label: "Delete",
                    action: function() {

                        if (true) {
                            var pre = "Delete this menu and all it's sub menu?"
                            alertify.confirm(pre, function() {
                                $.post("<?= base_url("portal/delete_menu_v2") ?>",
                                        {
                                            menu_id: node.id,
                                            children_ids: node.children_d.join(),
                                        },
                                        function(data) {

                                            if (data == '1') {
                                                $.jGrowl("Deleted successfully...",
                                                        {
                                                            position: 'center',
                                                            group: 'success',
                                                            life: 3000,
                                                        });
                                                $("#main_navigation").jstree('refresh').on('refresh.jstree', function() {
                                                    initialize();
                                                });
                                            } else {
                                                $.jGrowl("Failed to delete menu, please try again...",
                                                        {
                                                            position: 'center',
                                                            group: 'error',
                                                        });
                                            }


                                        }
                                );
                            }, function() {

                            }).setting('labels', {'ok': 'Yes', 'cancel': 'No'});
                        }
                    }
                }, privacySettings: {
                    "label": "Privacy Settings",
                    "action": function() {
                        $('#privacy_menu_id').val(node.id);
                        $('#viewers_list_val').val($('#span_id_' + node.id).data('hidden_to'));
                        var to_tick = $('#span_id_' + node.id).data('hidden_to');
                        $("input[name='viewers_list_selection[]']").prop('checked', true);
                        try {
                            to_tick = to_tick.split(',');
                            to_tick.forEach(function(entry) {
                                $("input[name='viewers_list_selection[]'][value='" + entry + "']").prop('checked', false);
                            });
                        } catch (e) {
                            $("input[name='viewers_list_selection[]'][value='" + to_tick + "']").prop('checked', false);
                        }

                        //$('.radio-inline .checker span').trigger('click');
                        alertify.privacySettings($('#privacy_settings_modal')[0]).set('selector');
                        $('.ajs-dialog').attr("style", "height: 400px; min-height: 112px; width: 800px; min-width: 100px; max-width: none; left: 41px; top: -11px;");
                    }
                }

            };
            if ($(node).hasClass("folder")) {
                // Delete the "delete" menu item
                delete items.deleteItem;
            }

            return items;
        }
    }

    function initialize() {
        if (admin_only) {

            $(".jstree-container-ul").sortable({
                appendTo: document.body,
                containment: "document",
                start: function(event, ui) {
                    var data = $(this).sortable('toArray');
                    old_index = data.indexOf($(ui.item).attr('id'));
                },
                stop: function(event, ui) {


                    var data = $(this).sortable('toArray');
                    $.post("<?= base_url() ?>portal/update_menu_index",
                            {
                                indexes: data
                            },
                    function(data) {
                        if (data != '1') {
                            alert('Failed to update sorting, please try again');
                            $(this).sortable('cancel');
                            $(this).sortable("refreshPositions");
                        }
                    }
                    );
                }

            });
        }


    }

    function initialize_child_sorting(nodeID) {
        if (admin_only) {
            setTimeout(function() {
                $("#" + nodeID).sortable({
                    containment: "parent",
                    items: " > ul > li ",
                    start: function(event, ui) {
                        var data = $(this).sortable('toArray');
                        old_index = data.indexOf($(ui.item).attr('id'));
                    },
                    stop: function(event, ui) {
                        var data = $(this).sortable('toArray');
                        $.post("<?= base_url() ?>portal/update_menu_index",
                                {
                                    indexes: data
                                },
                        function(data) {
                            if (data != '1') {
                                alert('Failed to update sorting, please try again');
                                $(this).sortable('cancel');
                                $(this).sortable("refreshPositions");
                            }
                        }
                        );
                    }

                });
            }, 500);
        }
    }
    function set_as_read(menu_id) {
        var tree_save_instance = $("#main_navigation").jstree(true);
        var menu_node = tree_save_instance.get_node(menu_id);
        var menu_parents = menu_node.parents;
        try {
            menu_parents = menu_parents.join();
        } catch (e) {

        }

        return menu_parents;
    }

    function generate_menu_management() {
        var manage_tree = $('#tree_manage_grouping').jstree({
            //containment: "parent",
            "check_callback": true,
            "core": {
                "animation": 0,
                "check_callback": true,
                'data': {
                    'url': "<?= base_url("portal/generate_json_menu_tree/$market") ?>"
                },
                'themes': {
                    'name': 'proton',
                    'responsive': true
                },
            }, "plugins": [
                "types", "contextmenu", "checkbox"
            ],
            "checkbox": {"three_state": false},
            'contextmenu': {
                'items': customMenu
            },
        });
    }
    function get_all_menu_groups_unrestricted() {
        $.post("<?= base_url("portal/get_all_menu_groups_unrestricted") ?>",
                {
                    parentmenu_id: 0
                },
        function(data) {
            $('#menu_group_select').html(data);
            $(document).on("change", "#menu_group_select", function() {


                if ($(this).val() == 'new') {
                    $('#group_name').css('display', '');
                    $('#delete_group').css('display', 'none');
                } else {
                    $('#group_name').css('display', 'none');
                    $('#delete_group').css('display', '');
                }
                if ($('#menu_group_select').val() == 0 || $('#menu_group_select').val() == 'new') {
                    $('#delete_group').css('display', 'none');
                }
                //    $("#tree_manage_grouping").jstree('refresh');
                $('#tree_manage_grouping').jstree(true).deselect_all();
                $('#tree_manage_grouping').jstree('close_all');
                try {
                    var nodes_to_select = $('option[value=' + $(this).val() + ']', this).data('menu_list');
                    nodes_to_select = nodes_to_select.split(",");
                    $('#tree_manage_grouping').jstree(true).select_node(nodes_to_select);
                } catch (e) {
                }
            });


        }
        );
    }


</script>   