<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   
class Chat extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model("dashboard_model","dashboard");
		$this->load->model("common_model","common"); 
		$this->load->model("manage_model","manage");
		$this->load->helper('text');  
		
		$date_start = "2013-09-01 00:00:00"; 
		
		$this->date_to = date("Y-m-d", strtotime("+1 day") );  
		//$this->date_to = date("Y-m-d");  
		//$this->date_from = date("Y-m-d", strtotime("-10 day", strtotime($this->date_to)));  
		$this->date_from = (view_management())?date("Y-m-d", strtotime("-2 month", strtotime($this->date_to))):date("Y-m-d", strtotime("-10 day", strtotime($this->date_to)));
		
		//$this->date_to = date("Y-m-d");
		//$this->date_from = date("Y-m-d", strtotime($date_start));
		
		//$this->date_to = date("Y-m-d", strtotime("-5 day") );
		//$this->date_from = date("Y-m-d", strtotime("-1 month", strtotime($this->date_to)));
		
	}
	
	public function index() {
	
		$currs = array();
		$currs2 = array();
		
		$res = $this->_currency->getAbbreviation(explode(',', $this->session->userdata('mb_currencies')));
		$res2 = $this->_currency->getAbbreviation($this->config->item('chat_eng'));
		$res3 = $this->_currency->getAbbreviation($this->config->item('chat_kr'));
		
		foreach($res as $c)
			$currs[] = $c->Abbreviation;
		
		foreach($res2 as $c2)
			$currs2[] = $c2->Abbreviation;
		
		foreach($res3 as $c3)
			$currs3[] = $c3->Abbreviation;
		
		$currency_groups = array(
				array(
						'title' => 'ENG',
						'currencies' => $currs2
					),
				array(
						'title' => 'KR',
						'currencies' => $currs3
					)
			);
		
		$not_eng = $currs2;
		$not_kr = $currs3;
		
		array_push($not_eng, 'N/A');
		array_push($not_kr, 'N/A');
		
		$titles = array();
		
		foreach($currency_groups as $v)
			foreach($currs as $k => $v2)
				if(in_array($currs[$k], $v['currencies'])) {
				
					$titles[] = $v['title'];
					
					break;
				}
		
		$currencies = array();
		
		foreach($currs as $user_currency)
			if(!in_array($user_currency, $not_eng) && !in_array($user_currency, $not_kr))
				$currencies[] = $user_currency;
		
		$conditions_array = array('a.Status ='=>'1' ); 
		$groups = $this->manage->createCustomChatGroup_($conditions_array, $this->common->chat_default); 
		
		$chat = array(); 
		if(count($groups) > 0)$chat = custom_group_chat($groups);

		$this->load->view('chat', array(
			'titles' => $titles,
			'currencies' => $currencies,
			'custom_groups' => $chat
		));
	}
	
}

/* End of file chat.php */
/* Location: ./application/controllers/chat.php */