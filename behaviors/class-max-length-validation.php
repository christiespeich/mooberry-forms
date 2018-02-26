<?php

class MOOBD_Max_Length_Validation implements MOOBD_IValidation_Behavior {

	protected $max_length;

	public function __construct( $field ) {
		/*$this->max_length = $args;
		if ( $args != '' ) {
			$this->max_length = intval( $this->max_length );
		}*/
		$this->max_length = $field->get_max_length();
	}

	public function validate_value( $value ) {
		if ( $this->max_length != '' ) {
			$valid = strlen( $value ) <= $this->max_length;
			if ( ! $valid ) {
				$error          = new stdClass();
				$error->code    = 'too-long';
				$error->message = sprintf( __( 'Must be %s or less characters long.', 'mooberry-forms' ), $this->max_length );

				return $error;
			}
		}

		return true;
	}

}