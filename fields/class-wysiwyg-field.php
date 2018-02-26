<?php
class MOOBD_WYSIWYG_Field extends MOOBD_Text_Field {
	
	public function __construct( $id, $prefix ) {
		parent::__construct( $id, $prefix );
		
		$this->add_class( $this->prefix . 'wysiwyg' );
	}
	
	protected function render_field( $object_id) {

		wp_editor( $this->value, $this->id );
	?>	

	<?php
	}
	
	public function sanitize_value( $value ) {
		$this->value = wp_kses_post( $value );
		return $this->value;
	}
	
}
