<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/14/2018
 * Time: 1:17 PM
 */

class MOOBD_Option_Form extends MOOBD_Front_End_Form {

	protected $key;

	public function __construct( $id, $capability = 'read', $arg = '' ) {
		parent::__construct( $id, $capability );
		$this->key = $arg;
		if ( $this->key != '' ) {
			$this->values = get_option( $this->key, array() );
		}

	}


	public function display_message() {
		// TODO: Implement display_message() method.
	}

	public function process_other_buttons() {
		// TODO: Implement process_other_buttons() method.
	}

	protected function save_fields( $fields ) {
		// TODO: Implement save_form() method.
	}

}