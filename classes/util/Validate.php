<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to validate user input against input requirements
 *
**/

class Validate {
	private $_passed = false;
	private $_errors = array();
	private $_db = null;


	//Called when Validate is instanciated
	public function __construct() {
		$this->_db = DB::getInstance();
	}

	//Function to check user inputs against validation rules
	public function check($source, $items = array()) {
		//Loop through validation group
		foreach($items as $item => $rules) {
			//Loop through each rule
			foreach($rules as $rule => $rule_value) {

				$value = trim($source[$item]);
				$item = escape($item);

				//Go through validation rules
				if($rule === 'required' && empty($value)) {
					$this->addError("{$item} is required");
				} else if(!empty($value)) {
					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must be a minimum of {$rule_value} characters.");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must be a maximum of {$rule_value} characters.");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}.");
							}
						break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} already exists.");
							}
						break;
					}
				}
			}
		}

		if(empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	//Function to add errors to an array
	private function addError($error) {
		$this->_errors[] = $error;
	}

	//Function to return all errors
	public function errors() {
		return $this->_errors;
	}

	//Function to return the _passed variable
	public function passed() {
		return $this->_passed;
	}
}

?>