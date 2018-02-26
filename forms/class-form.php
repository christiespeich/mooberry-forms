<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 10:19 AM
 */

abstract class MOOBD_Form implements MOOBD_IForm {

	protected $id;
	//protected $field_factory;
	protected $fields;
	protected $nonce_field;
	protected $nonce_value;
	protected $object_id;
	protected $capability;
	protected $message;
	protected $values;
	protected $classes;
	protected $contains_repeater;

	public function __construct(  $id, $capability = 'manage_options' ) {
		//$this->field_factory = $field_factory;
		$this->id          = $id;
		$this->fields      = array();
		$this->nonce_field = $id . '-nonce';
		$this->nonce_value = $id. '-nonce';
		$this->capability  = $capability;
		$this->message     = array();
		$this->values      = array();
		$this->classes = array();
		$this->contains_repeater = false;
	}

	abstract protected function save_form( $fields );
	//abstract protected function display_message();

	abstract protected function process_other_buttons();

	final public function set_nonce_value( $value ) {
		$this->nonce_value = $value;
	}

	final public function set_nonce_field( $field ) {
		$this->nonce_field = $field;
	}

	final public function add_field( MOOBD_IField $field ) {
		$this->fields[ $field->get_id() ] = $field;
		if ( get_class( $field ) == 'MOOBD_Repeater_Field' ) {
			$this->contains_repeater = true;
		}
	}

	final protected function check_capability() {
		return current_user_can( $this->capability, $this->object_id );

	}

	final protected function output_nonce() {
		wp_nonce_field( $this->nonce_value, $this->nonce_field );
	}

	final protected function render_fields( $object_id ) {


		foreach ( $this->fields as $field ) {
			if ( array_key_exists( $field->get_id(), $this->values ) ) {
				$field->set_value( $this->values[ $field->get_id() ] );

			} else {
				$field->set_value( '' );
			}
			$field->render( $object_id );
		}

	}

	final public function count_fields() {
		return count( $this->fields );
	}

	final protected function process_form() {


		$verified = $this->verify_form();
		if ( $verified ) {
			$this->process_other_buttons();
			$clean_fields = $this->sanitize_form();
			$valid = $this->validate_form( $clean_fields );
			if ( $valid ) {
				$this->save_form( $clean_fields );
			} else {
				$this->display_message();
			}
		}

	/*	$phone_numbers = array();
		foreach ( $this->fields as $field ) {
			if ( $field instanceof MOOBD_Phone_Field ) {
				$phone_numbers[ $field->get_id() ] = $field->get_value();
			}
		}
		wp_localize_script( 'moob-forms','phone_number', $phone_numbers );*/

	}

	protected function verify_form() {
		return isset( $_POST[ $this->nonce_field ] ) && wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce_value );
	}

	protected function sanitize_form() {
		$clean_fields = array();
		foreach ( $this->fields as $field ) {
			$id = $field->get_id();
			if ( ! $field instanceof MOOBD_Composite_Field ) {
				if ( array_key_exists( $id, $_POST ) ) {
					$field->set_value( $field->sanitize( $_POST[ $id ] ) );
					$clean_fields[ $id ] = $field;
				}
			} else {
				$field->set_value( $field->sanitize( $_POST ) );
				$clean_fields[ $id ] = $field;
			}
		}
		return $clean_fields;
	}
/*
	private function sanitize_fields( $field ) {
		$clean_fields = '';
		$id           = $field->get_id();
		if ( $field instanceof MOOBD_Single_Field ) {
			if ( array_key_exists( $id, $_POST ) ) {
				$field->set_value( $field->sanitize( $_POST[ $id ] ) );
				$clean_fields = $field;
			}
		} else {
			$fields = $field->get_fields();
			$clean_fields = array();
			foreach ( $fields as $inner_field ) {
				$clean_fields[ $inner_field->get_id() ] = $this->sanitize_fields( $inner_field );
			}
		}

		return $clean_fields;
	}*/

	protected function validate_form( $fields ) {
		foreach ( $fields as $field ) {
			$result = $field->validate();
			if ( is_wp_error( $result ) ) {
				$this->message[] = $result->get_error_message();
			}
		}
		return ( count( $this->message ) == 0 ) ;

	}



	protected function display_message() {
		echo '<div class="moobd-forms-error alert alert-danger ui-widget ui-state-error ui-corner-all"><p>' . implode('</p><p>', $this->message) . '</p></div>';
	}


}