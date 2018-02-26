<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:46 PM
 */

class MOOBD_Options_Field extends MOOBD_Single_Field {

    protected $options;

     public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->options = array();


	}

	public function set_options( $options ) {
	    if ( !is_array( $options) ) {
		    return;
	    }
		$this->options = $options;
	}

	public function add_option( $key, $value ) {
		$this->options[ $key ] = $value;
	}

	protected function sanitize_value( $value ) {
		if ( array_key_exists( $value, $this->options ) ) {
			return $value;
		} else {
			return $this->default_value;
		}
	}


}

class MOOBD_Options_List_Field extends MOOBD_Options_Field {

    protected $type;
    protected $inline;
    protected $name;

    public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->inline = true;
	    $this->type = 'checkbox';
	    $this->name = $id;

	}

	public function set_name( $value ) {
		$this->name = $value;
	}

	public function set_inline( $value ) {
		$this->inline = $value;
	}

	protected function render_field( $object_id ) {
	    parent::render_field( $object_id );
		if ( $this->inline ) {
	        $inline_class = ' ' . $this->prefix . 'show-inline ';
        } else {
	        $inline_class = '';
        }

        // allow plugins to hook into options to change based on object_id if necessary.
        // This can't be done at the time options are set because there is not object_id at that point
        $this->options = apply_filters( 'moob_forms_options_list', $this->options, $this, $object_id );

		?><ul class="<?php echo $inline_class; ?>"><?php
		foreach ( $this->options as $key => $value ) {
		    if ( is_array( $this->value ) ){
		        $checked = in_array( $key, $this->value ) ? ' checked="checked" ' : '';
		    } else {
			    $checked = ( $key == $this->value ) ? ' checked="checked" ' : '';
		    }
		?>
		<li class="<?php echo $inline_class; ?>">
			<input type="<?php echo esc_attr($this->type); ?>" value="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($this->name); ?>" class="<?php echo implode(' ', $this->classes); ?> <?php echo esc_attr( $this->prefix . $this->type ); ?>-option <?php echo esc_attr( $this->prefix . $this->type ); ?>-option-<?php echo esc_attr($key); ?>" <?php echo  $checked; ?> />
			<label class="<?php echo esc_attr($this->prefix . 'option-label ' . $this->prefix . $this->type . '-label ' . $this->prefix . $this->type . '-label-' . $this->id ); ?>" for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
		</li>
		<?php
		}
		?></ul><?php
	}



}




class MOOBD_Radio_Field extends MOOBD_Options_List_Field {

    public function __construct( $id, $prefix ) {

		  parent::__construct( $id, $prefix );

		  $this->type = 'radio';
		  $this->add_class( $this->prefix . 'radio' );
    }
}