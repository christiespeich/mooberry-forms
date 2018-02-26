<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 10:18 AM
 */

interface MOOBD_IField {


	public function sanitize( $value );
	public function validate();
	public function render( $object_id );

	public function set_value( $value );
	public function set_label( $value );
	public function set_description( $value );
	public function add_class( $class );

	public function get_id();
	public function get_value();


	public function __construct( $id, $prefix );


}