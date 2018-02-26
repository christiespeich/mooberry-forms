<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 10:16 AM
 */

interface MOOBD_IForm {

	public function set_nonce_field( $field );
	public function set_nonce_value( $value );
	public function add_field( MOOBD_IField $field );
	public function render();
	public function count_fields();
	//public function process_form();
	//public function set_capability();
	public function __construct( $id, $capability );

}