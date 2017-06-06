<?php

class Currency_Model extends CI_Model {
	
	public function __construct() {
		parent::__construct(); 
	}
	
	public function getAbbreviation($currencies) {

		return $this->db
						->select('Abbreviation, CurrencyID')
						->where('CurrencyID !=', 0)
						->where('Status', 1)
						->where_in('CurrencyID', $currencies)
						->order_by('Abbreviation')
						->get('csa_currency')
						->result();

	}
	
}