<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/23/2018
 * Time: 12:45 PM
 */

class MOOBD_Phone_Field extends MOOBD_Text_Field {

	public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->max_length = false;
	    $this->min_length = false;
	    $this->add_class( $this->prefix . 'phone' );

	}

	public function sanitize_value( $value ) {
		return sanitize_text_field( $value );
	}

	public function render_field( $object_id ) {

		$required = '';
	    if ( $this->required ) {
		    $required = ' required="required" ';
		    $this->add_class( $this->prefix . 'required' );
	    }

		$maxlength = ($this->has_max_length() ) ? ' maxlength="' . esc_attr($this->max_length) . '" ' : '';


	?>
		<input type="tel" id="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value); ?>" name="<?php echo esc_attr($this->id);?>" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required . $maxlength; ?> /><span class="moobd-forms-phone-valid-msg" class="hide">✓ Valid</span>	<span class="moobd-forms-phone-error-msg" class="hide">Invalid number</span>
	<?php

	}

}