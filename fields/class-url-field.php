<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:46 PM
 */

class MOOBD_URL_Field extends MOOBD_Text_Field {


	public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->add_class( $this->prefix . 'url' );


	}

	protected function render_field( $object_id ) {
	    parent::render_field( $object_id );

	    $required = '';
	    if ( $this->required ) {
		    $required = ' required="required" ';
		    $this->add_class( $this->prefix . 'required' );
	    }




	?>
        <input id="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr($this->value); ?>" name="<?php echo esc_attr($this->id); ?>" type="url" placeholder="http://www.example.com" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required; ?> />

	<?php

	}

	protected function sanitize_value( $value ) {
		return sanitize_text_field( $value );

	}

	protected function validate_value() {
		// TODO: Validate to URL format
		return true;
	}


}