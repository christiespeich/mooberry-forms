<?php
class MOOBD_Hidden_Field extends MOOBD_Single_Field {

	public function __construct( $id, $prefix ) {
		parent::__construct( $id, $prefix );

		$this->classes[] = $this->prefix . 'field-hidden';
	}

	protected function render_field( $object_id ) {
		?>
        <input type="hidden" id="<?php echo $this->id; ?>" name="<?php echo $this->id; ?>"
               value="<?php echo $this->value; ?>">
		<?php
	}

	/*
		public function sanitize( $value ) {
			$this->value = sanitize_text_field( $value );
			return $this->value;
		}
		*/

	public function sanitize_value( $value ) {
        return sanitize_text_field( $value );
	}

}