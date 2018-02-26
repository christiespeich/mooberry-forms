<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:46 PM
 */

class MOOBD_Textbox_Field extends MOOBD_Text_Field {


	public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->add_class( $this->prefix . 'text' );

	}

	protected function render_field( $object_id ) {
	    parent::render_field( $object_id );

	    $required = '';
	    if ( $this->required ) {
		    $required = ' required="required" ';
		    $this->add_class( $this->prefix . 'required' );
	    }

		$maxlength = ($this->has_max_length() ) ? ' maxlength="' . esc_attr($this->max_length) . '" ' : '';


	?>
		<input type="text" id="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value); ?>" name="<?php echo esc_attr($this->id);?>" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required . $maxlength; ?> />
	<?php

	}

	protected function sanitize_value( $value ) {
		return sanitize_text_field( $value );
	}

	/*protected function validate_value() {
		if ( $this->max_length !== false ) {
			if ( strlen( $this->value ) > $this->max_length ) {
				return false;
			}
		}

		if ( $this->min_length !== false ) {
			if ( strlen( $this->value ) < $this->min_length ) {
				return false;
			}
		}

		return true;
	}*/


}