<?php
namespace AppBundle\Utils;

class Calculator {
	protected $param = [];
	protected $seperator = ",";
	protected $delimeter = '';

	public function __construct($delimeter = '\\;\\', $param = '') {
		if(!empty($delimeter)) {
			$this->seperator = str_replace('\\', '', $delimeter);
		}
		$param = str_replace('\n', ',', $param);
		$this->param = explode( $this->seperator, $param );
	}
	
	public function sum() {
		
		return array_sum( $this->param );
	}
}
