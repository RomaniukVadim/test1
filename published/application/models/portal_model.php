<?php

class Portal_Model extends CI_Model {

    private $db2;
    private $db;
    private $db3;
    private $db_name;
    private $unconfirm_days;

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->db2 = $this->load->database("csd_portal", true);
        $this->db_name = (getenv('HTTP_HOST') == 'psbcal.12csd.com' or getenv('HTTP_HOST') == 'psbcal.zzs33.com')? Array( "db" => "info_112log", "db2" => "csd_knowledge_portal" ) : Array( "db" => "psbcal", "db2" => "csd_portal" );
        $this->unconfirm_days = 5;
    }

    public function get_menu_by_id($menu_id) {
        $this->db2->select("csd_prtl_menu.*");
        $this->db2->select("(SELECT GROUP_CONCAT(market) FROM csd_prtl_menu_market WHERE menu_id = csd_prtl_menu.menu_id AND enabled = '1') target_markets", false);
        $this->db2->select("(SELECT GROUP_CONCAT(market) FROM csd_prtl_menu_market WHERE menu_id = csd_prtl_menu.menu_id) all_markets", false);
        $this->db2->from("csd_prtl_menu");
        $this->db2->where("csd_prtl_menu.menu_id", $menu_id);
        $resource = $this->db2->get();
        //echo $this->db2->last_query();
        return $resource->result();
    }

    public function get_menu_by_level($market, $level) {
        /*
          $this->db2->select("csd_menu.*");
          $this->db2->select("( SELECT COUNT(*) FROM csd_page WHERE csd_menu.menu_id = csd_page.page_menu_id ) has_page_content",false);
          $this->db2->from("csd_menu");
          $this->db2->where("menu_market",$market); // SPECIFIC Market Only
          //$this->db2->where("FIND_IN_SET('$market', `menu_market`)"); // Grouped Pages, available for two or more countries
          $this->db2->where("parentmenu_id",$level);
          $this->db2->order_by("csd_menu.menu_order, csd_menu.menu_id");
          $resource = $this->db2->get();
          //echo $this->db2->last_query();
          return $resource->result();
         */

        $this->db2->select("cpm.*, cpmm.*, cpp.*");
        $this->db2->from("csd_prtl_menu cpm");
        $this->db2->join("csd_prtl_menu_market cpmm", "cpm.menu_id = cpmm.menu_id", "inner");
        $this->db2->join("csd_prtl_page cpp", "cpp.page_menu_id = cpmm.menu_id AND cpp.page_market = cpmm.market", "left");
        $this->db2->where("cpmm.market", $market);
        $this->db2->where("cpm.parentmenu_id", $level);

        $this->db2->group_by("cpm.menu_id");
        $this->db2->order_by("cpm.sorting_index", "asc");
        $this->db2->order_by("cpm.sorting_datetime", "desc");
        $resource = $this->db2->get();
        //echo $this->db2->last_query();
        return $resource->result();
    }

    public function get_pages_by_menu_id($market_code, $page_menu_id, $parameters) {

        $this->db2->select("cpp.*,updated_datetime as last_updated,cat.name as category_name,dept.name as department_name");
        $this->db2->from("csd_prtl_page cpp USE INDEX(page_menu_id)");
        $this->db2->join("csd_prtl_category cat", "cat.category_id=cpp.category", "LEFT");
        $this->db2->join("csd_prtl_department dept", "dept.department_id=cpp.from_department", "LEFT");

        $this->db2->where("cpp.page_menu_id", $page_menu_id);
        $this->db2->where("cpp.page_status", '1');
        $this->db2->group_by("cpp.page_id");
        if ($parameters['category'] != '') {
            $this->db2->where('cpp.category', $parameters['category']);
        }
        if ($parameters['department'] != '') {
            $this->db2->where('cpp.from_department', $parameters['department']);
        }

        if ($parameters['from'] != '') {
            $this->db2->where('cpp.updated_datetime >= ', $parameters['from']);
        }
        if ($parameters['to'] != '') {
            $this->db2->where('cpp.updated_datetime <= ', $parameters['to']);
        }

        if ($parameters['keyword'] != '') {
            $this->db2->like('cpp.page_title', $parameters['keyword']);
        }
        $this->db2->order_by("cpp.updated_datetime", "desc");
        $this->db2->limit($parameters['limit'], $parameters['offset']);
        return $this->db2->get()->result();
        
    }

    public function get_page_by_id($page_id) {
        /*
          $this->db2->select("csd_page.*, csd_menu.menu_name");
          $this->db2->from("csd_page");
          $this->db2->join("csd_menu","csd_menu.menu_id = csd_page.page_menu_id","inner");
          $this->db2->where("csd_page.page_market",$market);
          $this->db2->where("csd_page.page_id",$page_id);
          $resource = $this->db2->get();
          //echo $this->db2->last_query();
          return $resource->result();
         */

        $this->db2->select("cpp.*, cpm.*,cpmm.market as menu_market,cpp.page_desc as page_desc,cpp.page_title as page_title,cpp.page_content as page_content,cpp.page_id as page_id");
        $this->db2->from("csd_prtl_page cpp");
        $this->db2->join("csd_prtl_menu cpm", "cpm.menu_id = cpp.page_menu_id", "left");
        $this->db2->join("csd_prtl_menu_market cpmm", "cpmm.menu_id = cpm.menu_id", "left");

        $this->db2->where("cpp.page_id", $page_id);

        $resource = $this->db2->get();
        return $resource->result();
    }

    public function get_home_page_by_market($market) {
        /*
          $this->db2->select("csd_page.*, 'Home' as menu_name", false);
          $this->db2->from("csd_page");
          $this->db2->where("csd_page.page_market",$market);
          $this->db2->where("csd_page.page_menu_id",0);
          $resource = $this->db2->get();
          //echo $this->db2->last_query();
          return $resource->result();
         */

        $this->db2->select("cpp.*, 'Announcement' as menu_name", false);
        $this->db2->from("csd_prtl_page cpp USE INDEX(page_market)");
        $this->db2->where("cpp.page_market", $market);
        $this->db2->where("cpp.page_menu_id", 0);
        $this->db2->order_by("cpp.updated_datetime", 'desc');
        $resource = $this->db2->get();
        //echo $this->db2->last_query();
        return $resource->result();
    }

    public function get_submenu_by_market($markets, $parent_menu_id) {
        /*
          foreach($markets as $market)
          $this->db2->where("menu_market",$market);
          $this->db2->where("parentmenu_id",$parent_menu_id);
          $this->db2->from("csd_menu");
          $this->db2->order_by("parentmenu_id","asc");
          $this->db2->order_by("menu_order","asc");
          $result = $this->db2->get();
         */

        /* foreach($markets as $market)
          $this->db2->where("market",$market);
          $this->db2->where("parentmenu_id",$parent_menu_id);
          $this->db2->from("csd_prtl_menu_market");
          $this->db2->join("csd_prtl_menu","csd_prtl_menu.menu_id = csd_prtl_menu_market.menu_id", "left");
          $this->db2->order_by("parentmenu_id","asc");
          $this->db2->order_by("csd_prtl_menu.menu_id","asc");
          $this->db2->group_by("csd_prtl_menu.menu_id");
          $result = $this->db2->get(); */

        $this->db2->select("csd_prtl_menu.*");
        $this->db2->select("(SELECT COUNT(*) FROM csd_prtl_menu_market WHERE market IN ('" . implode("','", $markets) . "') AND menu_id = csd_prtl_menu.menu_id AND enabled = '1') cnt", false);
        $this->db2->select("(SELECT GROUP_CONCAT(market) FROM csd_prtl_menu_market WHERE menu_id = csd_prtl_menu.menu_id AND enabled = '1') target_markets", false);
        $this->db2->from("csd_prtl_menu");
        $this->db2->where("csd_prtl_menu.parentmenu_id", $parent_menu_id);
        $this->db2->having("cnt", count($markets));
        $this->db2->order_by("csd_prtl_menu.parentmenu_id", "asc");
        $this->db2->order_by("csd_prtl_menu.menu_id", "asc");
        $result = $this->db2->get();
        //echo $this->db2->last_query();
        return $result->result();
    }

    public function get_page_by_menumarket($menu_id, $market) {
        if (empty($market) || !isset($market[0]))
            return array();
        $market = $market[0];
        $this->db2->select("csd_prtl_page.*");
        $this->db2->from("csd_prtl_page");
        $this->db2->where("csd_prtl_page.page_menu_id", $menu_id);
        $this->db2->where("csd_prtl_page.page_market", $market);
        $this->db2->order_by("csd_prtl_page.page_id", "desc");
        $result = $this->db2->get();
        return $result->result();
    }

    public function get_max_order($market, $parentmenu_id) {
        $this->db2->select_max("menu_order");
        $this->db2->from("csd_menu");
        $this->db2->where("parentmenu_id", $parentmenu_id);
        $this->db2->where("menu_market", $market);
        $result = $this->db2->get();
        return $result->result();
    }

    public function insert_menu($data) {
        $result = $this->db2->insert("csd_prtl_menu", $data);
        return $this->db2->insert_id();
    }

    public function update_menu($data, $param) {
        $result = $this->db2->update("csd_prtl_menu", $data, $param);
        return $result;
    }

    public function delete_menu($param) {
        $result = $this->db2->delete("csd_prtl_menu", $param);
        return $result;
    }

    public function delete_menu_market($param) {
        $result = $this->db2->delete("csd_prtl_menu_market", $param);
        return $result;
    }

    public function insert_menu_market($data, $param) {
        $result = $this->db2->insert("csd_prtl_menu_market", $data);
        return $this->db2->insert_id();
    }

    public function update_menu_market($data, $param) {
        $result = $this->db2->update("csd_prtl_menu_market", $data, $param);
        return $result;
    }

    public function insert_page($data) {

        $group_id = $data['group_id'];
        unset($data['group_id']);
        $data['batch_id'] = uniqid();
        if ($group_id != '') {
            $this->db2->trans_start();
            $menu_ids = $this->get_menu_in_group($group_id);
            foreach ($menu_ids as $menu_id) {
                $data['page_menu_id'] = $menu_id;
                $query[] = $data;
            }
            $this->db2->insert_batch('csd_prtl_page', $query);

            $this->db2->trans_complete();
            $this->update_menu_notif($menu_ids);
            return ($this->db2->trans_status() === FALSE) ? FALSE : $data['batch_id'];
        } else {
            $query[] = $data;
            $this->db2->trans_start();
            $this->db2->insert_batch('csd_prtl_page', $query);
            $this->db2->trans_complete();
            return ($this->db2->trans_status() === FALSE) ? FALSE : $data['batch_id'];
        }




        /* $result = $this->db2->insert("csd_prtl_page", $data);
          return $this->db2->insert_id(); */
    }

    public function update_page($data, $param) {
        $result = $this->db2->update("csd_prtl_page", $data, $param);
        return $result;
    }

    public function delete_page($param) {
        $result = $this->db2->delete("csd_prtl_page", $param);
        return $result;
    }

    public function increment_view($page_id, $mb_no) {
        $qry = "INSERT INTO csd_prtl_view SET page_id = '" . $page_id . "', mb_no = '" . $mb_no . "', date_viewed = NOW(), view_count = 1, date_lastviewed = NOW()
				ON DUPLICATE KEY
				  UPDATE date_lastviewed = NOW(), view_count = view_count+1;";
        $result = $this->db2->query($qry);
        return $result;
    }

    public function get_views_by_market($market) {
        $this->db2->select("sum(cpv.view_count) total_count")
                ->from("csd_prtl_view cpv")
                ->join("csd_prtl_page cpp", "cpp.page_id = cpv.page_id")
                ->where("cpp.page_market", $market);
        $data = $this->db2->get();
        $result_data = $data->result();
        $total_views = (empty($result_data) ? 0 : $result_data[0]->total_count);
        $result['total_views'] = $total_views;

        $this->db2->select("sum(cpv.view_count) total_count")
                ->from("csd_prtl_view cpv")
                ->join("csd_prtl_page cpp", "cpp.page_id = cpv.page_id")
                ->where("cpp.page_market", $market)
                ->where("cpv.date_viewed", "DATE(NOW())", false);
        $data = $this->db2->get();
        $result_data = $data->result();
        $total_views = (empty($result_data) ? 0 : $result_data[0]->total_count);
        $result['total_views_today'] = $total_views;
        return $result;
    }

    public function get_stat_per_market($market, $date_from, $date_to) {
        $this->db2->select("cpp.*,cpv.*,cpm.*,sum(cpv.view_count) total_count, MAX(date_lastviewed) date_lastviewed")
                ->from("csd_prtl_view cpv")
                ->join("csd_prtl_page cpp", "cpp.page_id = cpv.page_id")
                ->join("csd_prtl_menu cpm", "cpp.page_menu_id = cpm.menu_id")
                ->where("cpp.page_market", $market)
                ->where("cpv.date_viewed >= ", $date_from)
                ->where("cpv.date_viewed <= ", $date_to)
                ->group_by("cpp.page_id")
                ->order_by("cpm.menu_id")
                ->order_by("cpv.page_id");
        $result = $this->db2->get();
        return $result->result();
    }

    /* ------- Author:Bryan Espino ---------- */

    public function update_menu_index($indexes) {
        $query = array();
        $this->db2->trans_start();
        foreach ($indexes as $new_index => $id) {
            $query[] = array(
                "menu_id" => $id,
                "sorting_index" => $new_index
            );
        }
        $this->db2->update_batch('csd_prtl_menu', $query, 'menu_id');
        $this->db2->trans_complete();
        return ($this->db2->trans_status() === FALSE) ? FALSE : TRUE;
    }

    public function update_menu_notif($menu_hierarchy) {
        $query = array();
        $this->db2->trans_start();
        foreach ($menu_hierarchy as $menu) {
            $query[] = array(
                "menu_id" => $menu,
                "page_updated_datetime" => date('Y-m-d H:i:s')
            );
        }
        $this->db2->update_batch('csd_prtl_menu', $query, 'menu_id');
        $this->db2->trans_complete();
        return ($this->db2->trans_status() === FALSE) ? FALSE : TRUE;
    }

    public function generate_json_nav_parent($market) {
        $usertype = $this->session->userdata('mb_usertype');
        //,IF(cpm.page_updated_datetime >= "'.date('Y-m-d H:i:s',strtotime('-2 days')).'",CONCAT("<i class=i-new id=span_new_id",cpm.menu_id,">"),"")

        /* if (in_array($usertype, array(12, 11, 4))) {
          $this->db2->select('cpm.menu_id as id,"#" as parent,CONCAT("<span class=nav_item data-location=' . base_url("portal/market/" . $market) . '/",cpm.menu_id," data-id=",cpm.menu_id," data-hidden_to=",cpm.hidden_to," id=span_id_",cpm.menu_id," data-indexof",cpm.menu_id,"=",sorting_index," data-page_updated_datetime=",DATE_FORMAT(cpm.page_updated_datetime,"%Y-%m-%d/%H:%i:%s"),">",cpm.menu_name,"<span id=new_notif_span",cpm.menu_id,"></span></span>") as text,cpm.menu_name as title,IF(cpm.parentmenu_id=0,"i-books parent-folder","i-books parent-folder") as icon,sorting_index as sorting_index', FALSE);
          } else {
          $this->db2->select('cpm.menu_id as id,IF(cpm.parentmenu_id=0,"#",cpm.parentmenu_id) as parent,CONCAT("<span class=nav_item data-location=' . base_url("portal/market/" . $market) . '/",cpm.menu_id," data-id=",cpm.menu_id," data-hidden_to=",cpm.hidden_to," id=span_id_",cpm.menu_id," data-indexof",cpm.menu_id,"=",sorting_index," data-page_updated_datetime=",DATE_FORMAT(cpm.page_updated_datetime,"%Y-%m-%d/%H:%i:%s"),">",cpm.menu_name,"<span id=new_notif_span",cpm.menu_id,"></span></span>") as text,cpm.menu_name as title,IF(cpm.parentmenu_id=0,"i-books parent-folder","i-book-2 child-folder") as icon,sorting_index as sorting_index', FALSE);
          } */
        $this->db2->select('cpm.menu_id as id,IF(cpm.parentmenu_id=0,"#",cpm.parentmenu_id) as parent,CONCAT("<span class=nav_item data-location=' . base_url("portal/market/" . $market) . '/",cpm.menu_id," data-id=",cpm.menu_id," data-hidden_to=",cpm.hidden_to," id=span_id_",cpm.menu_id," data-indexof",cpm.menu_id,"=",sorting_index," data-page_updated_datetime=",DATE_FORMAT(cpm.page_updated_datetime,"%Y-%m-%d/%H:%i:%s"),">",cpm.menu_name,"<span id=new_notif_span",cpm.menu_id,"></span></span>") as text,cpm.menu_name as title,IF(cpm.parentmenu_id=0,"i-books parent-folder","i-book-2 child-folder") as icon,sorting_index as sorting_index', FALSE);

        $this->db2->from("csd_prtl_menu cpm ");
        $this->db2->join("csd_prtl_menu_market cpmm ", "cpmm.menu_id=cpm.menu_id ", "left ");
        $this->db2->where("cpmm.market ", $market);
        $this->db2->where("cpm.isActive ", '1');
        // $this->db2->where("cpm.menu_id NOT IN (SELECT menu_id from csd_prtl_menu where menu_id=cpm.menu_id and FIND_IN_SET($usertype,hidden_to))",null,false);
        $this->db2->where("FIND_IN_SET($usertype,cpm.hidden_to)", 0);
        $this->db2->group_by("cpm.menu_id");
        $this->db2->order_by("cpm.sorting_index", "asc");
        $result = $this->db2->get()->result();
        return $result;
    }

    public function generate_json_menu_tree($market) {
        $usertype = $this->session->userdata('mb_usertype');
        $this->db2->select('cpm.menu_id as id,IF(cpm.parentmenu_id=0,cpmm.market,cpm.parentmenu_id) as parent,CONCAT("<span style=font-size:15px;padding:10px;>",cpm.menu_name,"</span>") as text,cpm.menu_name as title,IF(cpm.parentmenu_id=0,"i-books icon20 parent-folder","i-book-2 icon20 child-folder") as icon,sorting_index as sorting_index', FALSE);

        $this->db2->from("csd_prtl_menu cpm ");
        $this->db2->join("csd_prtl_menu_market cpmm ", "cpmm.menu_id=cpm.menu_id ", "left ");
        //$this->db2->where("cpmm.market ", $market);
        $this->db2->where("cpm.isActive ", '1');
        // $this->db2->where("cpm.menu_id NOT IN (SELECT menu_id from csd_prtl_menu where menu_id=cpm.menu_id and FIND_IN_SET($usertype,hidden_to))",null,false);
        //  $this->db2->where("FIND_IN_SET($usertype,cpm.hidden_to)", 0);
        $this->db2->group_by("cpm.menu_id");
        $this->db2->order_by("cpm.sorting_index", "asc");
        $result = $this->db2->get()->result();
        return $result;
    }

    public function generate_json_nav_children($parent_id) {
        $this->db2->select('cpm.menu_id as id,IF(cpm.parentmenu_id=0,"#",cpm.parentmenu_id) as parent,CONCAT("<span class=nav_item data-location=' . base_url("portal/market/" . $market) . '/",cpm.menu_id,"data-id=",cpm.menu_id,">",cpm.menu_name,"</span>") as text,cpm.menu_name as title', false)
                ->from("csd_prtl_menu cpm")
                ->join("csd_prtl_menu_market cpmm", "cpmm.menu_id=cpm.menu_id", "left")
                ->where("cpm.parentmenu_id", "99999")
                ->where("cpm.isActive", '1')
                ->order_by("cpm.updated_datetime", "asc")
                ->group_by("cpm.menu_id");


        $result = $this->db2->get();
        return $result->result();
    }

    public function delete_page_v2($data, $param) {
        $result = $this->db2->update("csd_prtl_page", $data, $param);
        return $result;
    }

    public function delete_menu_v2($menu_id, $children_ids) {

        if (strpos($children_ids, ',') !== false) {
            $result = $this->db2->query("UPDATE csd_prtl_menu SET isActive='0',deleted_by=" . $this->session->userdata("mb_no") . ",deleted_datetime='" . date('Y-m-d H:i:s') . "' WHERE menu_id=$menu_id or menu_id IN ($children_ids)");
        } else if (strpos($children_ids, ',') !== true && trim($children_ids) != '') {
            $result = $this->db2->query("UPDATE csd_prtl_menu SET isActive='0',deleted_by=" . $this->session->userdata("mb_no") . ",deleted_datetime='" . date('Y-m-d H:i:s') . "' WHERE menu_id=$menu_id or menu_id = $children_ids");
        } else {
            $result = $this->db2->query("UPDATE csd_prtl_menu SET isActive='0',deleted_by=" . $this->session->userdata("mb_no") . ",deleted_datetime='" . date('Y-m-d H:i:s') . "' WHERE menu_id=$menu_id ");
        }

        return $result;
    }

    public function get_homepage_content($market_code) {
        $this->db2->select("cpp.page_content as page_content,page_id", false);
        $this->db2->from("csd_prtl_page cpp");
        $this->db2->where("cpp.page_market", $market_code);
        $this->db2->where("cpp.page_menu_id", 0);
        $this->db2->order_by("cpp.updated_datetime", 'desc');
        $this->db2->limit("1");
        $resource = $this->db2->get();
        return $resource->result();
    }

    public function get_category() {
        $this->db2->select("*", false);
        $this->db2->from("csd_prtl_category");
        $this->db2->where("status", 1);
        $resource = $this->db2->get();
        return $resource->result();
    }

    public function get_department() {
        $this->db2->select("*", false);
        $this->db2->from("csd_prtl_department");
        $this->db2->where("status", 1);
        $resource = $this->db2->get();
        return $resource->result();
    }

    public function get_menu_title($menu_id) {
        $this->db2->select("menu_name", false);
        $this->db2->from("csd_prtl_menu");
        $this->db2->where("menu_id", $menu_id);
        $result = $this->db2->get();
        $ret = $result->row();
        $return_val = str_replace("\n", " ", $ret->menu_name);
        return $return_val;
    }

    public function update_menu_settings($data, $param) {
        $result = $this->db2->update("csd_prtl_menu", $data, $param);
        return $result;
    }

    public function get_all_members() {
        $this->db = $this->load->database("default", true);
        $this->db->select("mb_no,mb_nick", false);
        $this->db->from("g4_member");

        $resource = $this->db->get();

        $result = $resource->result();

        $keys = array_map(function ($a) {
            return "mb_id_{$a->mb_no}";
        }, $result);

        return (array_combine($keys, $result));
    }

    public function get_all_user_type() {
        $this->db = $this->load->database("default", true);
        $this->db->select("Name,GroupID");
        $this->db->from("csa_users_group");
        $resource = $this->db->get();
        $return_html = "";
        if (($resource->result())) {


            $return_html.="<table>";
            $counter = 0;
            foreach ($resource->result() as $row) {
                $return_html.="<td>";
                $return_html.="<label class='radio-inline group-label' >";
                $return_html.="<input class='checker' type='checkbox' name='viewers_list_selection[]' value='" . $row->GroupID . "'  > " . $row->Name;
                $return_html.="</label>";
                $return_html.="</td>";

                if ($counter == 4) {
                    $counter = 0;
                    $return_html.="</tr>";
                }
                $counter++;
            }
            $return_html.="</table>";
        }
        return $return_html;
    }

    public function check_for_new_updates($market) {
        $usertype = $this->session->userdata('mb_usertype');
        $this->db2->select('cpm.menu_id,cpm.page_updated_datetime', FALSE);
        $this->db2->from("csd_prtl_menu cpm ");
        $this->db2->join("csd_prtl_menu_market cpmm ", "cpmm.menu_id=cpm.menu_id ", "left ");
        $this->db2->where("cpmm.market ", $market);
        $this->db2->where("cpm.isActive ", '1');
        $this->db2->where("cpm.page_updated_datetime >=", date('Y-m-d H:i:s', strtotime('-2 days')));
        $this->db2->where("'" . $this->session->userdata('mb_no') . "' NOT IN (SELECT viewed_by from csd_prtl_notif_view where menu_id=cpm.menu_id and viewed_by=" . $this->session->userdata('mb_no') . " and update_viewed=cpm.page_updated_datetime)", null, false);
        //$this->db2->where("FIND_IN_SET($usertype,cpm.hidden_to)", 0);
        $this->db2->group_by("cpm.menu_id");
        $result = $this->db2->get()->result();
        return $result;
    }
    
    public function check_notification($markets,$mb_no){
        $this->db2->_protect_identifiers=false;
        $this->db2->select('cpm.menu_id, cpm.menu_name, cpm.page_updated_datetime, cpmm.market, cpm.hidden_to', FALSE);
        $this->db2->from("csd_prtl_menu cpm ");
        $this->db2->join("csd_prtl_menu_market cpmm ", "cpmm.menu_id=cpm.menu_id ", "left ");
        $this->db2->join("csd_prtl_menu_grouping cpmg ", " find_in_set(cpm.menu_id,cpmg.menu_ids) ", "left ");
        $this->db2->where("cpm.isActive ", '1');
        $this->db2->where("cpm.page_updated_datetime >=", "2017-06-01");
        $this->db2->where_in("cpmm.market", $markets);
        $this->db2->where("'" . $mb_no . "' NOT IN (SELECT viewed_by from csd_prtl_notif_view where menu_id=cpm.menu_id and viewed_by=" . $mb_no . " and update_viewed=cpm.page_updated_datetime)", null, false);
        $this->db2->group_by(Array(" IFNULL(cpmg.group_id,cpm.menu_id) "));
        $this->db2->order_by("cpm.page_updated_datetime ASC");
        $result = $this->db2->get()->result();
        $this->db2->_protect_identifiers=true;
        return $result;
    }

    public function mark_as_viewed($menu_id, $updated_viewed) {

        $this->db2->trans_start();

        $query[] = array(
            "menu_id" => $menu_id,
            "viewed_by" => $this->session->userdata('mb_no'),
            "date_viewed" => date('Y-m-d H:i:s'),
            "update_viewed" => $updated_viewed
        );

        $this->db2->insert_batch('csd_prtl_notif_view', $query);
        $this->db2->trans_complete();
        return ($this->db2->trans_status() === FALSE) ? FALSE : TRUE;
    }

    public function save_menu_grouping($params) {
        $this->db2->trans_start();
        $query[] = array(
            "menu_ids" => implode(',', $params['checked_nodes']),
            "group_name" => $params['group_name'],
            "date_updated" => date('Y-m-d H:i:s'),
            "updated_by" => $this->session->userdata('mb_no')
        );

        $this->db2->insert_batch('csd_prtl_menu_grouping', $query);
        $this->db2->trans_complete();
        return ($this->db2->trans_status() === FALSE) ? FALSE : TRUE;
    }

    public function update_menu_grouping($params) {
        $query = array();
        $this->db2->trans_start();

        $query[] = array(
            "group_id" => $params['group_id'],
            "menu_ids" => implode(',', $params['checked_nodes'])
        );

        $this->db2->update_batch('csd_prtl_menu_grouping', $query, 'group_id');
        $this->db2->trans_complete();
        return ($this->db2->trans_status() === FALSE) ? FALSE : TRUE;
    }

    public function get_all_menu_groups($menu_id) {
        $this->db2->select('*', false)
                ->from("csd_prtl_menu_grouping")
                ->where("FIND_IN_SET('$menu_id',menu_ids) !=", 0);

        $result = $this->db2->get();
        return $result->result();
    }

    public function get_all_menu_groups_unrestricted() {
        $this->db2->select('*', false)
                ->from("csd_prtl_menu_grouping");
        $result = $this->db2->get();
        return $result->result();
    }

    public function get_menu_in_group($group_id) {
        $this->db2->select('menu_ids', false)
                ->from("csd_prtl_menu_grouping")
                ->where("group_id", $group_id);
        $query = $this->db2->get();
        $ret = $query->row();
        return explode(',',$ret->menu_ids);
    }

    public function delete_group($param) {
        $result = $this->db2->delete("csd_prtl_menu_grouping", $param);
        return $result;
    }

    public function get_pages_in_batch($param) {
        $this->db2->select('page_id as page_id,cpmm.market as market,cpmm.menu_id as menu_id, cpm.menu_name, cpm.hidden_to', false)
                ->from("csd_prtl_page cpp")
                ->join("csd_prtl_menu_market cpmm", "cpp.page_menu_id=cpmm.menu_id")
                ->join("csd_prtl_menu cpm", "cpp.page_menu_id=cpm.menu_id")
                ->where($param)
                ->group_by("cpp.page_id");
        return $this->db2->get()->result();
    }

    public function page_update($menu_id,$mb_no,$markets){
        return $this->db2->query("
            insert into csd_prtl_notif_view(menu_id,viewed_by,date_viewed,update_viewed)
            SELECT cpm.menu_id,".$mb_no.",NOW(),cpm.page_updated_datetime from csd_prtl_menu cpm
            LEFT JOIN csd_prtl_menu_market cpmm ON cpmm.menu_id=cpm.menu_id
            LEFT JOIN csd_prtl_menu_grouping cpmg ON FIND_IN_SET(cpm.menu_id,cpmg.menu_ids)
            where (cpm.menu_id = ".$menu_id." or FIND_IN_SET(".$menu_id.",cpmg.menu_ids))
            AND  cpmm.market IN('".implode("','",$markets)."')
                    ");
    }
    
    public function check_view_menu($menu_id,$mb_no){
        return $this->db2->select("pnv.*,cpm.page_updated_datetime",false)
                  ->from("csd_prtl_menu cpm")
                  ->join("csd_prtl_notif_view pnv","cpm.menu_id = pnv.menu_id and cpm.page_updated_datetime = pnv.update_viewed","LEFT")
                  ->where(Array( "cpm.menu_id" => $menu_id, "pnv.viewed_by" => $mb_no ))
                  ->get()->result()
                  ;
    }
    
    public function insert_confirm_post($data){
        //$query_string = $this->db2->insert_string('csd_prtl_post_view',$data);
        $query_string = "INSERT INTO csd_prtl_post_view(page_id,mb_no) 
                    select cpp.page_id,".$data['mb_no']." from csd_prtl_page cpp 
                    LEFT JOIN csd_prtl_menu_market cpmm ON cpp.page_menu_id = cpmm.menu_id  where cpp.batch_id 
                    IN(select batch_id from csd_prtl_page where page_id = ".$data['page_id'].") and cpmm.market IN(".$data['market'].")";

        return $this->db2->query($query_string." ON DUPLICATE KEY UPDATE is_confirm = 1");
        
        
    }
    
    public function update_confirm_post($page_id){
        $page_keys = array_keys($page_id);
        $page_values = array_values($page_id);
        return $this->db2->where( Array( "cpp.".$page_keys[0] => $page_values[0] ) )
                    ->update('csd_prtl_post_view cppv LEFT JOIN csd_prtl_page cpp ON cppv.page_id = cpp.page_id',Array('cppv.is_confirm'=>0));
    }
    
    public function view_confirm_post($sel,$where){
        return $this->db2->select($sel,false)
                         ->from('csd_prtl_post_view')
                         ->where($where)
                         ->get()
                         ->result();
    }
    
    public function get_confirm_list($unread = 1,$page_id,$market_id){
        $this->db = $this->load->database("default",true);
        $this->db2->_protect_identifiers=false;
        $this->db2->select("gm.mb_no,gm.mb_nick,sug.Name usertype, GROUP_CONCAT(cc.Abbreviation SEPARATOR ', ' )currencies,IFNULL(cppv.date_updated_confirm,'no confirmation') date_updated_confirm",false)
                        ->from($this->db->database.".g4_member gm")
                        ->JOIN($this->db2->database.".csd_prtl_post_view cppv","cppv.mb_no = gm.mb_no and cppv.is_confirm = 1 AND cppv.page_id = ".$page_id, "LEFT")
                        ->JOIN($this->db->database.".csa_currency cc","FIND_IN_SET(cc.CurrencyID, gm.mb_currencies) ","LEFT")
                        ->JOIN($this->db->database.".csa_users_group sug","gm.mb_usertype=sug.GroupID ","LEFT")
                        ->where("gm.mb_status",1)
                        ->where_not_in("gm.mb_no",Array(433,384,280,242,243,244,245,35,33,388,387,34,463,156,1)) // test and admin accounts
                        ->where_in("gm.mb_usertype", allow_post_view_notification(true)) // allowed usertype
                        ->where("cppv.view_id is ".($unread?"":"not")." null",NULL,FALSE);
        $where_or = "";
        foreach($market_id as $i => $key)
            $where_or = ($i?"or":"")." FIND_IN_SET(".$key.",gm.mb_currencies) ";
            
            
            $this->db2->where("( ". $where_or ." )",NULL,FALSE);
            $this->db2->order_by("gm.mb_nick ASC");
            $this->db2->group_by("gm.mb_nick");
        $this->db2->_protect_identifiers=true;
        $result = $this->db2->get()->result();
        return $result;
    }
    
    public function get_unconfirm_list( $market,$usertype = '0' ){
        $this->db2->_protect_identifiers=false;
        $result = $this->db2->select("cpmm.menu_id,cpmm.market,cpp.page_id,cpp.page_title,cpp.created_datetime,cpp.updated_datetime,cpp.batch_id",false)
                  ->from('csd_prtl_page cpp USE INDEX(page_date)')
                  ->join("csd_prtl_post_view cppv","cpp.page_id = cppv.page_id and cppv.is_confirm = 1 and cppv.mb_no = ".$this->session->userdata("mb_no"),"LEFT")
                  ->join("csd_prtl_menu_market cpmm","cpmm.menu_id=cpp.page_menu_id","LEFT")
                  ->where("(cpp.updated_datetime + interval ".$this->unconfirm_days." day) < NOW()",NULL,FALSE)
                  ->where("cpp.updated_datetime >= '2017-06-01 00:00:00'",NULL,FALSE)
                  ->where("cpp.page_menu_id IN(
                                select cpm.menu_id 
                                FROM
                                csd_prtl_menu cpm
                                LEFT JOIN csd_prtl_menu_market cpmm ON cpmm.menu_id=cpm.menu_id
                                WHERE
                                cpm.isActive = 1
                                AND
                                cpmm.market IN('".implode("','",$market)."')
                                AND
                                !FIND_IN_SET(".$usertype.",cpm.hidden_to)
                        )",NULL,FALSE)
                  ->where("cppv.view_id is null",NULL,FALSE)
                  ->group_by("cpp.batch_id");
        $this->db2->_protect_identifiers=true;
        
        return $result->get()->result();
    }
    
}

?>