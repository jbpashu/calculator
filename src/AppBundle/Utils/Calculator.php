<?php
namespace AppBundle\Utils;

class Calculator {
	protected $param = [];
	protected $seperator = ",";

	public function __construct($param = "") {
		$param = str_replace('\n', ',', $param);
		$this->param = explode( $this->seperator, $param );
	}
	
	public function sum() {
		
		return array_sum( $this->param );
	}
}
