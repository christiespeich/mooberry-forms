<?php

class MOOBD_Min_Length_Validation implements MOOBD_IValidation_Behavior {

	protected $min_length;

	public function __construct( $field ) {
	/*	$this->min_length =  $args;
		if ( $args != '' ) {
			$this->min_length = intval( $this->min_length );
		}*/
		$this->min_length = $field->get_min_length();
	}

	public function validate_value( $value ) {
		if ( $this->min_length != '' ) {
			$valid = strlen( $value ) >= $this->min_length;
			if ( ! $valid ) {
				$error          = new stdClass();
				$error->code    = 'too-short';
				$error->message = sprintf( __( 'Must be %s or more characters long.', 'mooberry-forms' ), $this->min_length );

				return $error;
			}
		}

		return true;

	}

}