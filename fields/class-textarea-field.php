<?php
class MOOBD_Textarea_Field extends MOOBD_Text_Field {

	public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->add_class( $this->prefix . 'textarea' );

	}
	protected function render_field( $object_id ) {
		echo '<textarea id="' . esc_attr($this->id) . '" name="' . esc_attr($this->id) . '"';
		if ( $this->max_length !== false ) {
			echo ' maxlength="' . esc_attr($this->max_length) . '" ';
		}
		echo '>' . $this->value . '</textarea>';
	}
	
	public function sanitize_value( $value ) {
		$this->value = sanitize_textarea_field( $value );
		return $this->value;
	}
	
}
