<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:46 PM
 */

abstract class MOOBD_Text_Field extends MOOBD_Single_Field {

	protected $max_length;
	protected $min_length;

	public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->max_length = false;
	    $this->min_length = false;
	}

	public function set_max_length( $value ) {
		$this->max_length = $value;
		$this->add_validation_behavior( new MOOBD_Max_Length_Validation( $this ) );
	}

	public function set_min_length( $value ) {
		$this->min_length = $value;
		$this->add_validation_behavior( new MOOBD_Min_Length_Validation( $this ) );
	}

	public function has_max_length() {
		return $this->max_length !== false;
	}

	public function has_min_length() {
		return $this->min_length !== false;
	}

	public function get_max_length() {
		return $this->max_length;
	}

	public function get_min_length() {
		return $this->min_length;
	}

}