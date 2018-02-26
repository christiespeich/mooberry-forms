<?php

class MOOBD_Default_Validation_Behavior implements MOOBD_IValidation_Behavior {

	public function __construct( $field ) {
		// nothing to do
	}

	public function validate_value( $value ) {
		return true;
	}

}