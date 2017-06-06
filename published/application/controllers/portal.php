<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Portal extends MY_Controller {
 
    private $valid_markets;

    public function __construct() {
        parent::__construct();
        $this->load->model("portal_model", "portal");
        $this->load->model("common_model", "common");
        $this->load->model("manage_model", "manage");
        //$this->load->library('WS/WS', array('tag' => getenv('HTTP_HOST') == 'psbcal.12csd.com' ? 'intra' : 'intratest'));
        //$this->ws->load('pagenotice');
        $this->load->helper('text');

        $this->date_to = date("Y-m-d", strtotime("+1 day"));
        $this->date_from = date("Y-m-d", strtotime("-10 day", strtotime($this->date_to)));

        $this->valid_markets = array("ch" => "China", "id" => "Indonesia", "my" => "Malaysia", "th" => "Thailand", "vn" => "Vietnam", "kr" => "Korea", "jp" => "Japan", "hi" => "India", "sup" => "Supervisor");

        $this->market_map = array("1" => "my", "2" => "ch", "3" => "vn", "4" => "th", "5" => "id", "7" => "kr", "8" => "kr", "9" => "jp", "15" => "hi", "16" => "hi");
    }

    public function dashboard($market = "") {
        $this->db = $this->load->database("default", true);
        $data2 = array(
            "main_page" => "dashboard",
            "links" => array(),
            "market" => null
        );
        $sidebar = "portal/sidebar_tpl";
        $can_configure = in_array($this->session->userdata("mb_usertype"), array(2, 6, 7)); // Supervisor, Admin, Super Admin
        $currencies = explode(",", $this->session->userdata("mb_currencies"));

        $valid_markets = array();
        if (in_array($this->session->userdata("mb_usertype"), array(2, 6, 7, 13)) || supervisor_rights() || admin_access()) {
            $valid_markets["sup"] = "Supervisor";
        }

        foreach ($currencies as $curr) {
            if (isset($this->market_map[$curr]))
                $valid_markets[$this->market_map[$curr]] = $this->valid_markets[$this->market_map[$curr]];
        }

        $data = array("page_title" => "12Bet - Knowledge Portal");
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('portal/portal_home_tpl', array(
            "can_configure" => false,
            "market_list" => $valid_markets,
            "sidebar_view" => $this->load->view($sidebar, $data2, true))
        );
        $this->db = $this->load->database("default", true);
        $this->load->view('footer',Array(
            "js" => Array("portal_home.js")
        ));
    }

    public function generate_page_list() {
        set_time_limit(300);//5mins timeout
        $page_menu_id = $this->input->post('page_menu_id');
        $market_code = $this->input->post('market_code');
        $category = $this->input->post('category');
        $department = $this->input->post('department');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $keyword = $this->input->post('keyword');
        $parameters = array('category' => $category, 'department' => $department, 'from' => $from, 'to' => $to, 'keyword' => $keyword, 'offset' => $this->input->post('offset'), 'limit' => $this->input->post('limit'));
        $menu_content =  $this->portal->get_pages_by_menu_id($market_code, $page_menu_id, $parameters);
        $menu_title = $this->portal->get_menu_title($page_menu_id);
        
        $return = "";
        if ($menu_title != '') {
            //" . strip_tags(nl2br($this->get_menu_title($page_menu_id))) . "
            $menu_name_to_display = substr(strip_tags(nl2br($menu_title)), 0, 30);

            $menu_name_to_hover = (strip_tags(nl2br($menu_title)));
            if (strlen($menu_name_to_display) != strlen($menu_name_to_hover)) {
                $return.="<script>$('#menu_title').html('" . $menu_name_to_display . "&hellip;').attr('title','" . $menu_name_to_hover . "');</script>";
            } else {
                $return.="<script>$('#menu_title').html('" . $menu_name_to_display . "');</script>";
            }
        } else {
            $return.="<script>$('#menu_title').html('HOMEPAGE')</script>";
        }

        $users = ($this->portal->get_all_members());
        if ($page_menu_id != '0' && $page_menu_id != '') {

            if (($menu_content)) {
                
                foreach ($menu_content as $row) {
                    $confirmed = $this->portal->view_confirm_post("SUM(IF(is_confirm = 1 and date_confirm > '".$row->last_updated."',1,0))Confirmed,SUM((IF(mb_no = ".$this->session->userdata("mb_no")." and is_confirm = 1 and date_confirm > '".$row->last_updated."',1,0)))mb_no",Array('page_id'=>$row->page_id));
                    $r = Array(
                        "page_id" => $row->page_id,
                        "page_title" => $row->page_title,
                        "last_updated" => $row->last_updated,
                        "category_name" => $row->category_name,
                        "department_name" => $row->department_name,
                        "category" => $row->category,
                        "from_department" => $row->from_department,
                        "batch_id" => $row->batch_id,
                        "viewers" => ($confirmed[0]->Confirmed ? $confirmed[0]->Confirmed : 0),
                        "confirmation_box" => ($confirmed[0]->mb_no ? $confirmed[0]->mb_no : 0)
                    );
                    
                    $updated_by = $row->updated_by;
                    
                    $tools = "";
                    if (admin_access() || csd_supervisor_access() || additional_page_admins()) {
                        $tools.="<span title='Edit' data-id='" . $r['page_id'] . "' data-category='" . $r['category'] . "'  data-department='" . $r['from_department'] . "'  class='edit_page btn'><i class='icon16  i-pencil-4' style='font-weight:bold;cursor:pointer;'></i></span>";
                        $tools.="<span title='Delete' data-id='" . $r['page_id'] . "' data-category='" . $r['category'] . "'  data-department='" . $r['from_department'] . "'  class='delete_page btn'><i class='icon16 i-remove-4' style='font-weight:bold;cursor:pointer;'></i></span>";
                    }
                    
                    $return .= "
                        <div class='row-fluid' id='" . $r['page_id'] . "' >
                        <div class='span12'>
                            <div class='widget panel'>
                                <div class='widget-title'>
                                    <div class='icon'>
                                        <i class='icon20 i-file-4'></i>
                                    </div>
                                    <h4>
                                        <span style='color:#7090c8' id='page_title_" . $r['page_id'] . "'>" . ($r['page_title']) . "</span>
                                        <br/><span  class='badge'>
                                                <span style='font-size:11px;'>
                                                        <i class='icon12 i-tag'></i>
                                                        <b>updated last: </b>
                                                            <u><i>" . date("M d,y h:i:s a", strtotime($r['last_updated'])) . "</i></u> | 
                                                        by: </b><u>" . trim($users["mb_id_" . $updated_by]->mb_nick) . "</u>|
                                                </span>
                                                <span style='font-size:11px;font-weight:bold;' >
                                                category: <u>" . ( $r['category_name'] ? $r['category_name'] : "N/A" ) . "</u> | 
                                                department: <u>" . ( $r['department_name'] ? $r['department_name'] : "N/A" ) . "</u>
                                                </span>
                                        </span>
                                    </h4>
                                    <span class='btn-group' style='float:right;'>
                                        <span title='Hide' data-id='" . $r['page_id'] . "' data-category='" . $r['category'] . "'  data-department='" . $r['from_department'] . "'  class='hide_page btn' data-visible='true'>
                                            <i class='icon16 i-eye-5'></i>
                                        </span>
                                        ". $tools ."
                                    </span>
                                </div>
                                
                                <div class='widget-content' id='page_content_" . $r['page_id'] . "' style='  word-wrap: break-word;' >
                                ". str_replace('http://10.120.10.130/csa/', base_url(), $row->page_content) ."
                                <span id='batch_" . $r['page_id'] . "' data-batch='" . $r['batch_id'] . "'></span>
                                </div>
                                
                                ". ( allow_post_view_notification() ? "
                                <div class='form-actions full post-read-box has-error'>
                                    <div class='checkbox ".( $r['confirmation_box'] ? 'hidden' : '' )."'>
                                      <label> <input type='checkbox' value=" . $r['page_id'] . "> <span class='confirmation-box'> I have read and understood the contents of this message </span></label>
                                    </div>
                                    <div class='post-readers ".( (intval($r['viewers']) > 0 and (csd_supervisor_access() or admin_access()) )? 'search-string get_confirmation_list' : '' )."' data-id='" . $r['page_id'] . "' data-market='" . $market_code . "'  >
                                    Confirm: <span class='post-readers-count'> ". $r['viewers'] ." </span>
                                    </div>
                                </div>" : "" ) ." 
                            </div>
                        </div>
                        </div>
                    ";
                    
                }
                $return.="<script>slide_to_page();</script>";
            } else {
                $return .= '<div class="page-header"> <h1 class="center offline">No Pages Found</h1> ';
            }
        } else {

            if (admin_access() || csd_supervisor_access()) {
                $return.="<div class='btn-group' style='float:right !important;'>";
                $return.="<span id = 'home_page_buttons'><button class = 'btn' id = 'edit_home_page'><i class = 'i-pencil-4'></i></button> </span>";
                $return.="</div><br><br>";
            }
            $return.="<div id='homepage_panel'><div id='homepage_content'></div>";

            $return.="</div>";
            $return.="<script>$('.filtering_form').hide();</script>";
            
            
        }
        
        
        echo $return;
        
    }
    
    public function market($market = null, $page_menu_id = null, $page_id = null) {
        set_time_limit(300);//5mins timeout

        if ($market == null || !isset($this->valid_markets[$market]))
            redirect("portal");
        $data2 = array(
            "main_page" => "dashboard",
            "links" => null,
            "market" => $this->valid_markets[$market],
            "market_code" => $market
        );
        $sidebar = "portal/sidebar_tpl";
        //$menus = $this->portal->get_menu_by_level($market, 0);
        $links = "";
        if (count($menus)) {
            //$links = $this->generate_json_nav($menus, $market, 0, $page_menu_id);
        }
        $data2["links"] = $links;
        
        $page_data = array();
        $notice = "";
        if ($page_menu_id != null && $market != null && $page_id != null) {
            $page_data = $this->portal->get_page_by_id($page_id);
            if (in_array($this->session->userdata("mb_usertype"), array(1, 2)))
                $this->portal->increment_view($page_id, $this->session->userdata("mb_no"));
        }
        else if ($page_menu_id != null && $market != null) {
            //$page_data = $this->portal->get_pages_by_menu_id($market, $page_menu_id);
            $notice = $this->page_visit($page_menu_id,1);
        } else {
            $page_data = $this->portal->get_home_page_by_market($market);
        }
        $data = array("page_title" => "12Bet - Knowledge Portal");
        $this->load->view('header', $data);
        $this->load->view('header_nav');

        if ($page_id != null) {
            $this->load->view('portal/portal_page_content_tpl', array(
                "sidebar_view" => $this->load->view($sidebar, $data2, true),
                "page_data" => $page_data,
                "page_menu_id" => $page_menu_id,
                "category_list" => $this->portal->get_category(),
                "department_list" => $this->portal->get_department($page_menu_id)
            ));
        } else {
            $this->load->view('portal/portal_pages_tpl', array(
                "sidebar_view" => $this->load->view($sidebar, $data2, true),
                "page_data" => $page_data,
                "page_menu_id" => $page_menu_id,
                "category_list" => $this->portal->get_category(),
                "user_types"=>$this->common->getUserTypes_(array("Status ="=>1)), 
                "currencies"=>$this->manage->getCurrencyAll_(array("Status ="=>1)),
                "department_list" => $this->portal->get_department(),
                "group_menu_list" => $this->portal->get_all_menu_groups($page_menu_id),
                "notice" => $notice
            ));
        }
        $this->db = $this->load->database("default", true);
        $this->load->view('footer',Array(
            'css' => Array(
                'jquery.qtip.min.css',
                '../js/plugins/tables/datatables/jquery.dataTables.css'
                ),
            'js' => Array(
                'jquery.qtip.min.js',
                'knowledge_portal_page.js',
                'plugins/tables/datatables/jquery.dataTables.min.js'
                )   
        ));
    }

    public function menu($action, $menu_id = null) {
        $menu_dtl = array();
        if ($action == "view") {
            $menu_dtl = $this->portal->get_menu_by_id($menu_id);
            if (isset($menu_dtl[0]))
                $menu_dtl = $menu_dtl[0];
            else
                redirect("portal/configure/menu/edit");
        }

        $data2 = array(
            "main_page" => "config",
            "links" => $this->generate_config_links(),
            "market" => "Configuration"
        );
        $sidebar = "portal/sidebar_tpl";

        $data = array("page_title" => "12Bet - Knowledge Portal");
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('portal/portal_menu_' . $action . '_tpl', array(
            "menu_dtl" => $menu_dtl,
            "market_list" => $this->valid_markets,
            "sidebar_view" => $this->load->view($sidebar, $data2, true))
        );
        $this->db = $this->load->database("default", true);
        $this->load->view('footer');
    }

    public function page($action, $id = null, $market = null) {
        $stat_table = array();
        if ($action == "view") {
            $page_dtl = $this->portal->get_page_by_id($id);
            if (isset($page_dtl[0])) {
                $page_dtl = $page_dtl[0];
                $menu_dtl = $this->portal->get_menu_by_id($page_dtl->page_menu_id);
                if (count($menu_dtl))
                    $page_dtl->menu_name = $menu_dtl[0]->menu_name;
                else
                    $page_dtl->menu_name = "Home";
            } else
                redirect("portal/configure/page/edit");
        }
        else if ($action == "home") {
            if ($id == "view") {
                $action .= "_" . $id;
            } else if ($id == "add") {
                $action .= "_" . $id;
            }
        } else if ($action == "statistics") {
            foreach ($this->valid_markets as $market_code => $market) {
                $result = $this->portal->get_views_by_market($market_code);
                $stat_table[$market]['total_views'] = (int) $result['total_views'];
                $stat_table[$market]['total_views_today'] = (int) $result['total_views_today'];
            }
        }
        $data2 = array(
            "main_page" => "config",
            "links" => $this->generate_config_links(),
            "market" => "Configuration"
        );
        $sidebar = "portal/sidebar_tpl";

        $data = array("page_title" => "12Bet - Knowledge Portal");
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('portal/portal_page_' . $action . '_tpl', array(
            "market" => $market,
            "page_dtl" => $page_dtl,
            "stat_table" => $stat_table,
            "market_list" => $this->valid_markets,
            "sidebar_view" => $this->load->view($sidebar, $data2, true))
        );
        $this->db = $this->load->database("default", true);
        $this->load->view('footer');
    }

    public function configure() {
        $data2 = array(
            "main_page" => "config",
            "links" => $this->generate_config_links(),
            "market" => "Configuration"
        );
        $sidebar = "portal/sidebar_tpl";

        $data = array("page_title" => "12Bet - Knowledge Portal");
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('portal/portal_config_tpl', array(
            "sidebar_view" => $this->load->view($sidebar, $data2, true))
        );
        $this->db = $this->load->database("default", true);
        $this->load->view('footer');
    }

    public function get_submenu_by_market() {
        $markets = $this->input->post("cmarket");
        $menu_id = $this->input->post("menu_id");
//$parent_menus = $this->portal->get_submenu_by_market($markets,0);
        $links = $this->generate_submenu_options($markets, 0, 0, $menu_id, false);
        echo json_encode(array("error" => 0, "msg" => "", "content" => $links));
    }

    public function get_menu_list() {
        $market = $this->input->post("cmarket");
        $links = $this->generate_menu_list_table($market, 0, 0);
        echo json_encode(array("error" => 0, "msg" => "", "content" => $links));
    }

    public function save_menu() {
        $post = $this->input->post();
        $date_today = new DateTime();

        if (($post['menuid']) != 'new') {
            $data = array(
                "menu_name" => $post['name'],
                "updated_by" => $this->session->userdata("mb_no"),
                "updated_datetime" => $date_today->format("Y-m-d H:i:s"),
                "page_updated_datetime" => date('0000-00-00 00:00:00')
            );
            $param = array(
                "menu_id" => $post['menuid']
            );
            $result = $this->portal->update_menu($data, $param);
            echo $post['menuid'];
        } else {
            $data = array(
                "menu_name" => $post['name'],
                "parentmenu_id" => $post['parentmenu_id'],
                "created_by" => $this->session->userdata("mb_no"),
                "created_datetime" => $date_today->format("Y-m-d H:i:s"),
                "updated_by" => $this->session->userdata("mb_no"),
                "updated_datetime" => $date_today->format("Y-m-d H:i:s"),
                "sorting_index" => "8888",
                "sorting_datetime" => $date_today->format("Y-m-d H:i:s"),
                "page_updated_datetime" => date('0000-00-00 00:00:00')
            );
            $menu_id = $this->portal->insert_menu($data);

            if ($menu_id) {
                $market = array_search($post['cmarket'], $this->valid_markets);
                $data = array(
                    "menu_id" => $menu_id,
                    "market" => $market
                );
                $this->portal->insert_menu_market($data);
            }

            //  echo json_encode(array("error" => 0, "msg" => "Menu has been added", 'menu_id' => "$menu_id"));
            echo $menu_id;
        }
    }

    public function delete_menu() {
        $post = $this->input->post();
        if (is_array($post['cmarket'])) {
            foreach ($post['cmarket'] as $market) {
                $this->delete_menu_tree($market, $post['menuid']);
            }
        } else
            $this->delete_menu_tree($post['cmarket'], $post['menuid']);

        $this->get_menu_list();
    }

    public function delete_menu_tree($market, $parent_menu_id) {
        $menus = $this->portal->get_submenu_by_market(array($market), $parent_menu_id);
        foreach ($menus as $menu) {
            $this->delete_menu_tree($market, $menu->menu_id);
        }
        $this->portal->delete_menu_market(array("menu_id" => $parent_menu_id, "market" => $market));
        $this->portal->delete_page(array("page_menu_id" => $parent_menu_id, "page_market" => $market));

        $menu_dtl = $this->portal->get_menu_by_id($parent_menu_id);

        if (count($menu_dtl) && $menu_dtl[0]->all_markets == "") {
            $this->portal->delete_menu(array("menu_id" => $parent_menu_id));
        }
    }

    public function upload_image() {
        $date = new DateTime();
        $upload_folder = "media/uploads/portal/" . $date->format("Ym");
        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        if (is_uploaded_file($_FILES['file']['tmp_name'])) {


            $filename = uniqid() . "_" . $_FILES['file']['name'];
            $filename = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $filename);
            $filetype = $_FILES['file']['type'];
            $filetype = explode('/', $filetype);

            if ($filetype[0] == 'image' || true) {
                $filename.="." . $filetype[1];
                $this->load->library('thumbnailer/PhpThumbFactory');
                $thumb = PhpThumbFactory::create($_FILES['file']['tmp_name']);
                $percentage = 100;
                if ($_FILES['file']['size'] > (400000)) {
                    $percentage = 80;
                }
                $thumb->resizePercent($percentage)->save($upload_folder . "/" . $filename);
                // move_uploaded_file($_FILES['file']['tmp_name'], "./" . $upload_folder . "/" . $filename);
                echo base_url($upload_folder . "/" . $filename);
            } else {
                echo "failed";
            }
        }
        echo "";
    }

    public function get_image_list() {
        $this->load->helper('directory');
        $html_content = "";
        $folder_path = "media/uploads/portal/";
        $map = directory_map('./' . $folder_path, 2);
        foreach ($map as $folder => $files) {
            foreach ($files as $file) {
                $underscore_index = strpos($file, "_") + 1;
//echo $underscore_index;
                $html_content .= "<div class='img-container'>"
                        . "<img src='" . base_url($folder_path . $folder . "/" . $file) . "' width='150'/>"
                        . "<span style=''>" . substr($file, $underscore_index) . "</span>"
                        . "</div>";
            }
        }
        echo $html_content;
    }

    public function save_page() {
        include('simple_html_dom.php');
        set_time_limit(300);//5mins timeout
        $date = new DateTime();
        $upload_folder = "media/uploads/portal/" . $date->format("Ym");
        $post = $this->input->post();

        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        if ($post['pageid'] != 'new') {
            $content = $post['content'];
            // $regexp = '/<img[^>]*[^>]*>/i';
            $regexp = '/<img .*?(?=src)src=\"([^\"]+)\"/si';
            $result = preg_match_all($regexp, $content, $img_tags);
            $img_tags = $img_tags[0];
            $html = str_get_html($content);

            if($html->find('img')){
                foreach ($html->find('img') as $element) {
                    if($element == "")break;
                    $filename = uniqid() . ".jpg";
                    $filedata = explode(',', $element->src);
                    if ($filedata[1]) {
                        $file_type = explode(';', $filedata[0]);
                        $file_type = $file_type[0];
                        $file_type = explode('/', $file_type);
                        $file_type = $file_type[1];
                        $filename = uniqid() . "." . $file_type;
                        $ifp = fopen("./" . $upload_folder . "/" . $filename, "wb");
                        fwrite($ifp, base64_decode($filedata[1]));
                        fclose($ifp);
                        $element->src = base_url($upload_folder . "/" . $filename);
                        // $content = str_replace($img, "<img src='" . base_url($upload_folder . "/" . $filename) . "'", $content);
                    }
                }
            }
            $data = array(
                "page_title" => $post['pagename'],
                "page_desc" => $post['shortdesc'],
                "page_content" => $html->outertext,
                "updated_by" => $this->session->userdata("mb_no"),
                "updated_datetime" => $date->format("Y-m-d H:i:s"),
                "category" => $post['edit_category'],
                "from_department" => $post['edit_department']
            );
            
            if ($post['is_update_all'] == 'true') {
                $param = array(
                    "batch_id" => $post['batch_id']
                );
            } else {
                $param = array(
                    "page_id" => $post['pageid']
                );
            }

            $stat="false";
            $notice_post = "";
            
            if ($this->portal->update_page($data, $param)) {
                $this->portal->update_confirm_post($param);

                $menu_affected = $post['menu_parents'];
                if (strpos($menu_affected, ',') !== false) {
                    $menu_affected = explode(",", $menu_affected);
                    if (($key = array_search("#", $menu_affected)) !== false) {
                        unset($menu_affected[$key]);
                    }
                    array_push($menu_affected, $post['page_menu_id']);
                } else {
                    $menu_affected = array($post['page_menu_id']);
                }
                $this->portal->update_menu_notif($menu_affected);

                if ($post['notify_users'] == 'true') {
                    
                    $pages_in_batch = $this->portal->get_pages_in_batch($param);
                    $data = Array( "update" => (($post['is_update_all'] == 'true')?1:0), "label"=> "", "data"=> []);
                    foreach ($pages_in_batch as $page) {
                        $data["label"] = $page->menu_name;
                        $data["data"][] = Array("market" => $page->market, "menu_id" => $page->menu_id, "hidden_to" => $page->hidden_to );
                        
                    }
                   //PAGENOTICE::publish("csdportalnotice", $data);
                    $notice_post = Array("channel"=>"csdportalnotice", "msg"=>$data);
                }
                $stat= "true";
            } 
        } 
        else {
            $content = $post['content'];
            $regexp = '/<img[^>]*[^>]*>/i';
            $result = preg_match_all($regexp, $content, $img_tags);
            $img_tags = $img_tags[0];
            $html = str_get_html($content);
            foreach ($html->find('img') as $element) {
                $filename = uniqid() . ".jpg";
                $filedata = explode(',', $element->src);
                if ($filedata[1]) {
                    $file_type = explode(';', $filedata[0]);
                    $file_type = $file_type[0];
                    $file_type = explode('/', $file_type);
                    $file_type = $file_type[1];
                    $filename = uniqid() . "." . $file_type;
                    $ifp = fopen("./" . $upload_folder . "/" . $filename, "wb");
                    fwrite($ifp, base64_decode($filedata[1]));
                    fclose($ifp);
                    $element->src = base_url($upload_folder . "/" . $filename);
                    // $content = str_replace($img, "<img src='" . base_url($upload_folder . "/" . $filename) . "'", $content);
                }
            }

            $data = array(
                "page_title" => $post['pagename'],
                "page_desc" => $post['shortdesc'],
                "page_content" => $html->outertext,
                "created_by" => $this->session->userdata("mb_no"),
                "created_datetime" => $date->format("Y-m-d H:i:s"),
                "updated_by" => $this->session->userdata("mb_no"),
                "updated_datetime" => $date->format("Y-m-d H:i:s"),
                "page_market" => $post['market'],
                "page_menu_id" => $post['page_menu_id'],
                "category" => $post['category'],
                "from_department" => $post['department'],
                "group_id" => $post['group_post']
            );

            $last_insert_id = $this->portal->insert_page($data);

            if ($post['page_menu_id'] != '0') {
                if ($last_insert_id > 0) {
                    $menu_affected = $post['menu_parents'];
                    if (strpos($menu_affected, ',') !== false) {
                        $menu_affected = explode(",", $menu_affected);
                        if (($key = array_search("#", $menu_affected)) !== false) {
                            unset($menu_affected[$key]);
                        }
                        array_push($menu_affected, $post['page_menu_id']);
                    } else {
                        $menu_affected = array($post['page_menu_id']);
                    }
                    $this->portal->update_menu_notif($menu_affected);
                    $data = Array( "update" => (($post['is_update_all'] == 'true')?1:0), "label"=> "", "data"=> []);
                    if ($post['notify_users'] == 'true') {
                        $pages_in_batch = $this->portal->get_pages_in_batch(array('batch_id' => $last_insert_id));
                        foreach ($pages_in_batch as $page) {
//                            PAGENOTICE::update($page->page_id, $data['page_title'] . " (" . $page->market . ")", $this->session->userdata('mb_username'), null, "csdportalnotice-" . $page->market);
//                            PAGENOTICE::read($page->page_id, $this->session->userdata('mb_username'), "csdportalnotice-" . $page->market);
                                $data["label"] = $page->menu_name;
                                $data["data"][] = Array("market" => $page->market, "menu_id" => $page->menu_id, "hidden_to" => $page->hidden_to );
                        }
                        if($post['update_all'] !== 'true') $data["data"] = Array("market" => $post['market'],"menu_id" => $post['page_menu_id']);
                        
                        //PAGENOTICE::publish("csdportalnotice", $data); 
                        $notice_post = Array("channel"=>"csdportalnotice", "msg"=>$data);
                    }

                    $stat= "true";
                } 
            } else {
                if ($last_insert_id > 0) {
                    $menu_affected = $post['menu_parents'];
                    if (strpos($menu_affected, ',') !== false) {
                        $menu_affected = explode(",", $menu_affected);
                        if (($key = array_search("#", $menu_affected)) !== false) {
                            unset($menu_affected[$key]);
                        }
                        array_push($menu_affected, $post['page_menu_id']);
                    } else {
                        $menu_affected = array($post['page_menu_id']);
                    }
                    $this->portal->update_menu_notif($menu_affected);
                    $stat = $last_insert_id;
                } 
            }
        }
        
        echo json_encode(Array("status"=>$stat,"notice"=>$notice_post));
    }
    

    
    public function delete_page() {
        $post = $this->input->post();

        $this->portal->delete_page(array("page_id" => $post['pageid']));
        $this->get_page_list();
    }

    public function delete_group() {
        $post = $this->input->post();
        echo $this->portal->delete_group(array("group_id" => $post['group_id']));
    }

    public function get_page_list() {
        $market = $this->input->post("market");
        $menu = $this->input->post("menu");
        $links = $this->generate_page_list_table($market, $menu);
        echo json_encode(array("error" => 0, "msg" => "", "content" => $links));
    }

    public function get_statistics() {
        $post = $this->input->post();
        $html_content = $this->generate_page_stat_table($post['market'], $post['date_from'], $post['date_to']);
        echo json_encode(array("content" => $html_content));
    }

    private function generate_config_links() {
        $links = "";
        $links .= "<li class='hasSub'><a href='#'><span class='txt'>Menu Management</span></a>";
        $links .= "<ul class='sub'>";
        $links .= "<li><a href='" . base_url("portal/configure/menu/add") . "'><span class='txt'>Add Menu</span></a></li>";
        $links .= "<li><a href='" . base_url("portal/configure/menu/edit") . "'><span class='txt'>Edit Menus</span></a></li>";
        $links .= "</ul>";
        $links .= "</li>";
        $links .= "<li class='hasSub'><a href='#'><span class='txt'>Page Management</span></a>";
        $links .= "<ul class='sub'>";
        $links .= "<li><a href='" . base_url("portal/configure/page/add") . "'><span class='txt'>Add Page</span></a></li>";
        $links .= "<li><a href='" . base_url("portal/configure/page/home") . "'><span class='txt'>Edit Home Page</span></a></li>";
        $links .= "<li><a href='" . base_url("portal/configure/page/edit") . "'><span class='txt'>Edit Pages</span></a></li>";
        $links .= "<li><a href='" . base_url("portal/configure/page/statistics") . "'><span class='txt'>Statistics</span></a></li>";
        $links .= "</ul>";
        $links .= "</li>";
        return $links;
    }

    private function generate_links($resource, $market, $parent_id, $page_menu_id) {
        $links = "";
        $menu_counter = 1;
        if ($parent_id != 0)
            $links .= "<ul class='sub sortable'>";
        foreach ($resource as $row) {
            $menus = $this->portal->get_menu_by_level($market, $row->menu_id);
            if (count($menus)) {
                $links .= "<li id='$menu_counter' data-menu_id='" . $row->menu_id . "' data-market_id='$market' class='hasSub' ><a  href='" . ((true) ? base_url("portal/market/" . $market . "/" . $row->menu_id) : "#") . "'><span class='txt' title=\"" . htmlentities($row->menu_name) . "\">" . $row->menu_name . "</span></a></span>";
                $links .= $this->generate_links($menus, $market, $row->menu_id, $page_menu_id);
            } else {
                $links .= "<li id='$menu_counter' data-menu_id='" . $row->menu_id . "' data-market_id='$market' ><a href='" . ((true) ? base_url("portal/market/" . $market . "/" . $row->menu_id) : "#") . "'><span class='txt' title=\"" . htmlentities($row->menu_name) . "\">" . $row->menu_name . "</span></a>";
            }
            $links .= "<span class='sub_menu_container'></span></li>";
            $menu_counter++;
        }
        if ($parent_id != 0)
            $links .= "</ul>";
        return $links;
    }

    public function generate_json_nav_parent($market) {
        $market = array_search($market, $this->valid_markets);
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($this->portal->generate_json_nav_parent($market)));
    }

    public function generate_json_menu_tree($market) {
        $market = array_search($market, $this->valid_markets);
        $json_menu_tree = (json_encode($this->portal->generate_json_menu_tree($market)));
        $json_menu_tree = json_decode($json_menu_tree, true);
        foreach ($this->valid_markets as $key => $val) {
            $json_menu_tree[] = array("icon" => "i-globe icon20", "id" => $key, "parent" => "#", "sorting_index" => 0, "text" => "<span style='font-size:17px;padding:10px;'>" . $val . "</span>", "title" => $val);
        }
        $json_menu_tree = json_encode($json_menu_tree);
        $this->output
                ->set_content_type('application/json')
                ->set_output($json_menu_tree);
    }

    public function save_menu_grouping() {


        if ($this->input->post('group_id') == 'new') {
            $params = array(
                "group_name" => $this->input->post('group_name'),
                'checked_nodes' => $this->input->post('checked_nodes')
            );
            echo $this->portal->save_menu_grouping($params);
        } else {
            $params = array(
                "group_id" => $this->input->post('group_id'),
                'checked_nodes' => $this->input->post('checked_nodes')
            );
            echo $this->portal->update_menu_grouping($params);
        }
    }

    public function generate_json_nav_parent_sample($market) {
        $market = array_search($market, $this->valid_markets);
        print_r($this->portal->generate_json_nav_parent($market));
    }

    public function generate_json_nav_children($parent_id) {
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($this->portal->generate_json_nav_children($parent_id)));
    }

    private function generate_submenu_options($markets, $parent_id, $padding, $menu_id, $disabled) {
        $links = "";
        $menus = $this->portal->get_submenu_by_market($markets, $parent_id);
        foreach ($menus as $menu) {
            $option_disabled = false;
            if ($menu->menu_id == $menu_id || $disabled) {
                $option_disabled = true;
            }
            $links .= "<option value='" . $menu->menu_id . "' " . ($option_disabled ? "disabled" : "") . ">" . str_repeat("&nbsp;", $padding) . $menu->menu_name . "</option>";
            $links .= $this->generate_submenu_options($markets, $menu->menu_id, $padding + 3, $menu_id, $option_disabled);
        }
        return $links;
    }

    private function generate_menu_list_table($market, $parent_id, $padding) {
        $links = "";
        $menus = $this->portal->get_submenu_by_market(array($market), $parent_id);
        foreach ($menus as $menu) {
            $links .= "<tr>" .
                    "  <td width=\"70%\">" . str_repeat("&nbsp;", $padding) . $menu->menu_name . "</td>" .
                    "  <td width=\"15%\" class=\"center\">" . $menu->target_markets . "</td>" .
                    "  <td width=\"15%\" class=\"center\"><a href='" . base_url("portal/configure/menu/view/" . $menu->menu_id) . "' class='btn btn-primary'>Edit</a>&nbsp;<a href='javascript:void(0)' class='btn btn-danger delete-btn' page-id=\"" . $menu->menu_id . "\">Delete</a></td>" .
                    "</tr>";
            $links .= $this->generate_menu_list_table($market, $menu->menu_id, $padding + 3);
        }
        return $links;
    }

    private function generate_page_list_table($market, $menu_id) {
        $links = "";
        $pages = $this->portal->get_page_by_menumarket($menu_id, array($market));
        if (count($pages)) {
            foreach ($pages as $page) {
                $links .= "<tr>" .
                        "  <td width=\"85%\">" . $page->page_title . "</td>" .
                        ($page->page_id ?
                                "  <td width=\"15%\" class=\"center\"><a href='" . base_url("portal/configure/page/view/" . $page->page_id) . "' class='btn btn-primary'>Edit</a>&nbsp;<a href='javascript:void(0)' class='btn btn-danger delete-btn' page-id=\"" . $page->page_id . "\">Delete</a></td>" :
                                "  <td width=\"15%\" class=\"center\"><a href='" . base_url("portal/configure/page/add/" . $menu_id) . "' class='btn btn-primary'>Add</a></td>"
                        ) .
                        "</tr>";
            }
        } else {
            $links .= "<tr>" .
                    "  <td width=\"100%\" class=\"center\" colspan=\"2\">- No Data -</td>" .
                    "</tr>";
        }
        return $links;
    }

    private function generate_page_stat_table($market, $date_from, $date_to) {
        $market_stat = $this->portal->get_stat_per_market($market, $date_from, $date_to);
        if (count($market_stat)) {
            $html_return = "";
            foreach ($market_stat as $market_dtl) {
                $html_return .= "<tr>
							<td>" . $market_dtl->page_title . "</td>
							<td>" . $market_dtl->menu_name . "</td>
							<td class='center'>" . $market_dtl->total_count . "</td>
							<td class='center'>" . $market_dtl->date_lastviewed . "</td>
							</tr>";
            }
        } else {
            $html_return = "<tr><td colspan='4' class='center'>No Pages have been viewed.</td></tr>";
        }
        return $html_return;
    }

    /* -------  Author: Bryan Espino ----------- */

    public function update_menu_index() {
        $indexes = $this->input->post("indexes");
        echo $this->portal->update_menu_index($indexes);
    }

    public function show_sub_menu() {
        $menu_id = $this->input->post('menu_id');
        $market = $this->input->post('market');
        $menus = $this->portal->get_menu_by_level($market, $menu_id);
        if (count($menus)) {
            echo $this->generate_links($menus, "ch", $menu_id, $menu_id);
        }
    }
    
    public function page_visit($menu_id,$ret = 0){
        $currencies = explode(",", $this->session->userdata("mb_currencies"));

        $valid_markets = array();
        if (in_array($this->session->userdata("mb_usertype"), array(2, 6, 7, 13)) || supervisor_rights() || admin_access()) {
            $valid_markets["sup"] = "sup";
        }

        foreach ($currencies as $curr){
            if (isset($this->market_map[$curr])) $valid_markets[] = $this->market_map[$curr];
        }
        
        $menu = $this->portal->check_view_menu($menu_id,$this->session->userdata("mb_no"));
        $pagenotice = "";
        if(count($menu) == 0){      
            $this->portal->page_update($menu_id,$this->session->userdata("mb_no"),$valid_markets);
            $pagenotice = json_encode(Array("channel"=>"csdportalnotice_".$this->session->userdata("mb_username")."_read", "msg"=>Array( "menu_id" => $menu_id )));
        }
            if($ret == 1) return $pagenotice;
            echo $pagenotice;
    }
    
    public function delete_page_v2() {
        $page_id = $this->input->post('page_id');
        $post = $this->input->post();
        $data = array(
            "page_status" => '0',
            "deleted_by" => $this->session->userdata("mb_no"),
            "deleted_datetime" => date('Y-m-d H:i:s'),
        );
        $menu_id = Array();
        if ($post['delete_all'] == 'true') {
            $param = array(
                "batch_id" => $post['batch_id'],
            );
            $pages_in_batch = $this->portal->get_pages_in_batch($param);
            foreach ($pages_in_batch as $page) {
                //PAGENOTICE::remove($page->page_id, "csdportalnotice-" . $post['market']);
                $menu_id[] = $page->page_id;
            }
        } else {
            $param = array(
                "page_id" => $page_id,
            );
            $menu_id[] = $page_id;
            //PAGENOTICE::remove($page_id, "csdportalnotice-" . $post['market']);
        }

        if($this->portal->delete_page_v2($data, $param)){
            echo json_encode(Array("status"=>'1', "notice"=>Array("channel"=>"csdportalnotice_remove","msg"=>$menu_id)));
        }else{
            echo json_encode(Array("status"=>'0'));
        }
            
    }

    public function delete_menu_v2() {
        $menu_id = $this->input->post('menu_id');
        $children_ids = $this->input->post('children_ids');

        echo $this->portal->delete_menu_v2($menu_id, $children_ids);
    }

    public function get_homepage_content() {
        $market = $this->input->post("market_code");
        $result = $this->portal->get_homepage_content($market);
        $to_return = "";
        $page_id = "0";
        foreach ($result as $row) {
            $page_id = $row->page_id;
            $to_return = $row->page_content;
        }
        if ($to_return == "") {
            $to_return = "no_home_page";
        }

        echo json_encode(array("page_id" => $page_id, "content" => str_replace('http://10.120.10.130/csa/', base_url(), $to_return)));
    }

    public function get_all_user_type() {
        echo $this->portal->get_all_user_type();
    }

    public function update_menu_settings() {
        $menu_id = $this->input->post('menu_id');
        $hidden_to = $this->input->post('hidden_to');
        $data = array(
            "hidden_to" => $hidden_to,
            "updated_datetime" => date('Y-m-d H:i:s'),
            "updated_by" => $this->session->userdata('mb_no')
        );

        $parameters = array(
            "menu_id" => $menu_id
        );

        echo $this->portal->update_menu_settings($data, $parameters);
    }

    public function check_for_new_updates($market) {
        echo json_encode($this->portal->check_for_new_updates($market));
    }

    public function mark_as_viewed() {
        $menu_id = $this->input->post('page_menu_id');
        $page_updated_datetime = $this->input->post('date_updated');
        $this->portal->mark_as_viewed($menu_id, $page_updated_datetime);
    }

    public function get_all_menu_groups_unrestricted() {
        $menu_group_list = $this->portal->get_all_menu_groups_unrestricted();
        $option = "";
        $option .= "<option class='group_option_item' data-menu_list='' value='0'>-SELECT-</option>";
        foreach ($menu_group_list as $row) {
            $option.="<option class='group_option_item' data-menu_list='" . $row->menu_ids . "' value='" . $row->group_id . "'>" . $row->group_name . "</option>";
        }
        $option.="<option class='group_option_item' data-menu_list='' value='new'>-NEW-</option>";

        echo $option;
    }

    public function get_pages_in_batch() {
        
    }

    public function post_confirm(){
        $post = $this->input->post();
        $mb_no = $this->session->userdata("mb_no");
        $currencies = explode(",", $this->session->userdata("mb_currencies"));
        $valid_markets = array();
        if (in_array($user_type, array(2, 6, 7, 13)) || supervisor_rights() || admin_access()) $valid_markets["sup"] = "'sup'";

        foreach ($currencies as $curr){
            if (isset($this->market_map[$curr])) $valid_markets[] = "'".$this->market_map[$curr]."'";
        }
        
        //insert confirmation
        if($post['page_id'] and $this->portal->insert_confirm_post(Array('page_id'=>$post['page_id'], 'mb_no'=>$mb_no, 'market'=> implode(",",$valid_markets) )) ){
            $confirmed = $this->portal->view_confirm_post("COUNT(*)Confirmed",Array('page_id'=>$post['page_id'],'is_confirm'=>1));
            echo json_encode(Array("confirmed"=>$confirmed[0]->Confirmed, "msg"=>"","supervisor"=>(( admin_access() or csd_supervisor_access() )?1:0)));
        }else{
            echo json_encode(Array("confirmed"=>0, "msg"=>"Error On saving Confirmed Data"));
        }
    }
    public function get_confirm(){     
        $page_id = $this->input->post("page_id");
        $market = $this->input->post("market");
        $market_id = array_keys($this->market_map,$this->input->post("market"));
        
        echo json_encode(
                Array(
                    "unconfirm"=>$this->portal->get_confirm_list(1,$page_id,$market_id),
                    "confirm"=>$this->portal->get_confirm_list(0,$page_id,$market_id)
                ));
    }
    
    public function get_unconfirm_list(){
        $user_type = $this->session->userdata("mb_usertype");

        $mb_currencies = $this->session->userdata("mb_currencies");
        
        $currencies = explode(",", $mb_currencies);
        $valid_markets = array();
        if (in_array($user_type, array(2, 6, 7, 13)) || supervisor_rights() || admin_access()) $valid_markets["sup"] = "sup";

        foreach ($currencies as $curr){
            if (isset($this->market_map[$curr])) $valid_markets[] = $this->market_map[$curr];
        }
        
        $data = allow_post_view_notification()? $this->portal->get_unconfirm_list($valid_markets,$user_type) : Array();
        //print_r($data);
        echo json_encode(Array("data"=>$data));
    }
    
    public function confirmationExport(){
        $post = $this->input->post();
        $page_id = $post['export-page'];
        $market_id = array_keys($this->market_map,$this->input->post("export-market"));
        
        $this->load->library('excel');
        
        $file_name = "Confirmation List-".$post['export-page'].".xls";

        $headerSchedStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'c5d9f1')
            ),
            'borders' => array('outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                )),
            'alignment' => array('wrap' => true,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array('bold' => true)
        );

        $defaultSchedStyle = array('borders' => array('outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                )),
            'alignment' => array('wrap' => true,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );


        $sheetIndex = Array("Confirm","Unconfirm");
        foreach ($sheetIndex as $key => $value){
            $row = 1;
            $activeSheet = $this->excel->setActiveSheetIndex($key);
            $data = $this->portal->get_confirm_list($key,$page_id,$market_id);
                foreach($data as $list){
                    $cell = "A" . $row;                    
                    $activeSheet->setCellValue($cell, $list->mb_nick);
                    $cell = "B" . $row;                    
                    $activeSheet->setCellValue($cell, $list->usertype);                    
                    $cell = "C" . $row;                    
                    $activeSheet->setCellValue($cell, $list->currencies);                    
                    $cell = "D" . $row;       
                    if($list->date_updated_confirm !== 'no confirmation')$activeSheet->setCellValue($cell, $list->date_updated_confirm);
                    $row++;
                }

            $column_start = 'A';
            $total_columns = 4;
            for ($col = 0; $col < $total_columns; $col++) {
                $column_start = PHPExcel_Cell::stringFromColumnIndex($col);
                $activeSheet->getColumnDimension($column_start)->setAutoSize(true);
                //$column_start++; 
            }            
            $activeSheet->setTitle($value);
            if($value !== end($sheetIndex))$activeSheet = $this->excel->createSheet($key+1); 
        }
        $activeSheet = $this->excel->setActiveSheetIndex(0);



        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');        
    }
}

/* End of file portal.php */
/* Location: ./application/controllers/portal.php */