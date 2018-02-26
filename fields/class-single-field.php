<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:55 PM
 */

abstract class MOOBD_Single_Field extends MOOBD_Field {

	protected $required;
	protected $validation_callback;
	protected $sanitization_callback;
	protected $empty_value;
	protected $validation_behaviors;
	protected $default_value;

	abstract protected function sanitize_value ( $value );

	public function __construct( $id, $prefix ) {
		parent::__construct( $id, $prefix );
		// default behavior
		$this->validation_behaviors = array();

		$this->add_validation_behavior( new MOOBD_Default_Validation_Behavior( $this ) );

	}

	public function set_default_value( $value ) {
		$this->default_value = $value;
	}


	public function set_required( $value ) {
		$this->required = $value;
	}

	public function add_validation_behavior( MOOBD_IValidation_Behavior $behavior ) {
		$this->validation_behaviors[ get_class( $behavior ) ] = $behavior;
	}

	public function validate() {
		if ( $this->required  ) {
			if ( $this->value == $this->empty_value ) {
				$this->classes[] = 'missing';
				return new WP_Error( 'missing-field', $this->label . ' is required.', $this->label);
			}
		}

		foreach ( $this->validation_behaviors as $validation_behavior ) {
			$result = $validation_behavior->validate_value( $this->value );
			if ( $result !== true ) {
				$this->classes[] = 'error';
				return new WP_Error( $result->code, $this->label . ': ' . $result->message, $this->label);
			}
		}

		if ( $this->validation_callback != '' && is_callable( $this->validation_callback ) ) {
			$result =call_user_func( $this->validation_callback );
			if ( $result !== true ) {
				$this->classes[] = 'error';
				return new WP_Error( $result->code, $this->label . ': ' . $result->message, $this->label);
			}
		}

		return true;
	}

	public function sanitize( $value) {
		if ( $this->sanitization_callback != '' && is_callable( $this->sanitization_callback ) ) {
			return call_user_func( $this->sanitization_callback, $this->value, $this );
		} else {
			return $this->sanitize_value( $value );
		}
	}

	protected function validate_value() {
		return true;
	}

	protected function render_field( $object_id  ) {
	    /*if ( is_array($this->value) ) {
			$this->value = $this->value[0];
		}*/

		if ( $this->value == '' && $this->default_value != '' ) {
			$this->value = $this->default_value;
		}

	    if ( $this->required ) {

		    $this->add_class( $this->prefix . 'required' );
	    }



	}

}