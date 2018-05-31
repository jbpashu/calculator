<?php
namespace AppBundle\Utils;

class Calculator {
	protected $param = [];
	protected $seperator = ',';

	public function __construct($param = [0]) {
		$this->param = explode($this->seperator, $param);
	}
	
	public function sum() {
		return array_sum( $this->param );
	}
}
