<?php

class MOOBD_Checkbox_Field extends MOOBD_Options_List_Field {

    public function __construct( $id, $prefix ) {

		  parent::__construct( $id, $prefix );

		  $this->type = 'checkbox';
		  $this->add_class( $this->prefix . 'checkbox' );
		  $this->name = $this->id . '[]';
    }

    protected function sanitize_value( $value ) {
	    if ( is_array( $value ) ) {
		    foreach ( $value as $k => $v ) {
			    if ( ! array_key_exists( $v, $this->options ) ) {
				    unset( $value[ $k ] );
			    }
		    }
		    return $value;
	    } else {
		    return parent::sanitize( $value );
	    }
    }
}
