<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:46 PM
 */

class MOOBD_Email_Field extends MOOBD_Text_Field {


	public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->add_class( $this->prefix . 'url' );


	}

	protected function render_field( $object_id ) {
	    parent::render_field( $object_id  );

	    $required = '';
	    if ( $this->required ) {
		    $required = ' required="required" ';
		    $this->add_class( $this->prefix . 'required' );
	    }




	?>
        <input id="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr($this->value); ?>" name="<?php echo esc_attr($this->id); ?>" type="email" placeholder="bob@example.com" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required; ?> />

	<?php

	}

	protected function sanitize_value( $value ) {
		return sanitize_email( $value );

	}

	public function validate() {

		if ( filter_var($this->value, FILTER_VALIDATE_EMAIL) === false ) {
            $this->classes[] = 'error';
            return new WP_Error( 'bad-email', $this->label . ' is not a valid email address.', $this->label);

		}

		return parent::validate();
	}


}