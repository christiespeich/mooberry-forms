<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/23/2018
 * Time: 2:33 PM
 */

abstract class MOOBD_Composite_Field extends MOOBD_Field {

	protected $fields;
	protected $required;

	abstract protected function set_fields();
	abstract protected function set_required_fields();


	public function __construct( $id, $prefix ) {
		parent::__construct( $id, $prefix );

		$this->required = false;
		$this->fields   = array();
	}

	public function set_value( $values ) {

		foreach ( $values as $key => $value ) {
			if ( array_key_exists( $key, $this->fields ) ) {
				$this->fields[ $key ]->set_value( $value );
			}
		}
	}

	public function set_required( $value ) {
		$this->required = $value;
		$this->set_required_fields();
	}

	public function render_field( $object_id ) {
		//$this->set_fields();
		foreach ( $this->fields as $field ) {
			$field->render();
		}

	}

	public function sanitize( $values ) {

		$sanitized_fields = array();
		foreach ( $this->fields as $field ) {
			$id = $field->get_id();
			if ( array_key_exists( $id, $values ) ) {
				$sanitized_fields[ $id ] = $field->sanitize( $values[ $id ] );

			}

		}
		$field->set_value( $sanitized_fields );
		return $sanitized_fields;
	}
}



class MOOBD_Address_Field extends MOOBD_Composite_Field {

	protected $usa_only;
	protected $display_countries;



	public function __construct( $id, $prefix ) {
		parent::__construct( $id, $prefix );

		$this->set_fields();

		$this->set_usa_only( true );
		$this->set_display_countries( false );
		$this->set_required( false );

	}

	public function set_usa_only( $value ) {
		$this->usa_only = $value;
		$this->set_state_field();
	}

	public function set_display_countries( $value ) {
		$this->display_countries = $value;
	}


	public function get_fields() {
		return $this->fields;
	}

	protected function set_state_field() {
		$id = $this->prefix . 'state';
		if (  $this->usa_only ) {
			$field = MOOBD_Field_Factory::create_field( 'select', $id );
			$field->set_options( array( '' => '' ) + MOOBD_References::$states );
			$field->set_label( __( 'State', 'mooberry-forms' ) );
			$field->set_required( $this->required );
		} else {
			$field = MOOBD_Field_Factory::create_field( 'text', $id );
			$field->set_label( __( 'State / Province / Region', 'mooberry-forms' ) );
			$field->set_required( $this->required );
		}
		$this->fields[ $id ] = $field;
	}

	protected function set_country_field() {
		$id = $this->prefix . 'country';
		if ( $this->display_countries ) {
			$field = MOOBD_Field_Factory::create_field( 'select', $id );
			$field->set_options( array( '' => '' ) + MOOBD_References::$countries );
			$field->set_label( __( 'Country', 'mooberry-forms' ) );
			$this->fields[ $id ] = $field;
		} else {
			if ( array_key_exists( $id, $this->fields ) ) {
				unset( $this->fields[ $id ] );
			}
		}
	}

	protected function set_address1_field() {
		$id = $this->prefix . 'address-line-1';
		$field = MOOBD_Field_Factory::create_field( 'text', $id );
		$field->set_label( __( 'Address Line 1', 'mooberry-forms' ) );
		$field->set_description( __( 'street address, PO Box, company name, c/o', 'mooberry-forms' ) );
		$field->set_required( $this->required );
		//$field->set_value( $this->value[ $id ] );
		$this->fields[ $id ] = $field;
	}

	protected function set_address2_field() {
		$id = $this->prefix . 'address-line-2';
		$field = MOOBD_Field_Factory::create_field( 'text', $id );
		$field->set_label( __( 'Address Line 2', 'mooberry-forms' ) );
		$field->set_description( __( 'apt, suit, unit, building, etc.', 'mooberry-forms' ) );
		$this->fields[ $id ] = $field;
	}


	protected function set_city_field() {
		$id = $this->prefix . 'city';
		$field = MOOBD_Field_Factory::create_field( 'text', $id );
		$field->set_label( __( 'City', 'mooberry-forms' ) );
		$field->set_required( $this->required );
		$this->fields[ $id ] = $field;
	}

	protected function set_zip_field() {
		$id = $this->prefix . 'postal-code';
		$field = MOOBD_Field_Factory::create_field( 'text', $id );
		$field->set_label( __( 'Zip / Postal Code', 'mooberry-forms' ) );
		$this->fields[ $id ] = $field;
	}


	protected function set_required_fields() {
		$this->set_address1_field();
		$this->set_city_field();
		$this->set_state_field();
		$this->set_zip_field();
	}

	protected function set_fields() {

		$this->set_address1_field();

		$this->set_address2_field();

		$this->set_city_field();

		$this->set_state_field();

		$this->set_zip_field();

		$this->set_country_field();

	}



	public function validate() {
		// TODO: Implement validate() method.
	}
}