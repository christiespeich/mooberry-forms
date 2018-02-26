<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:47 PM
 */

abstract class MOOBD_Field implements MOOBD_IField  {

	protected $label;
	protected $id;
	protected $classes;
	protected $description;
	protected $value;
	protected $prefix;
	protected $default_value;

	abstract protected function render_field( $object_id  );

	public function __construct( $id, $prefix ) {
		$this->id = $id;
		$this->label = '';
		$this->classes = array( $prefix . 'field' );
		$this->description = '';
		$this->value = '';
		$this->prefix = $prefix;
		$this->default_value = '';
	}

	public function get_id() {
		return $this->id;
	}

	public function get_value() {
		return $this->value;
	}

	public function render( $object_id ) {
		?>
		<div class="<?php echo esc_attr( implode(' ', $this->classes) ) . ' ' . $this->prefix . $this->id ; ?>">
            <div class="<?php echo esc_attr( $this->prefix . 'label ' . $this->prefix . 'label-' . $this->id ); ?>">
		<label for="<?php echo esc_attr($this->id); ?>" class="label-<?php echo esc_attr($this->id); ?>"><?php echo esc_attr($this->label); ?></label>
		<?php if ( $this->description != '' ) { ?>
			<div class="<?php echo esc_attr( $this->prefix); ?>description <?php echo esc_attr( $this->prefix ); ?>field-<?php echo esc_attr($this->id); ?>-description"><?php echo $this->description; ?></div>

		<?php } ?>
                 </div>
		<?php

		$this->render_field( $object_id );

		?>
		</div>
		<?php
	}

	public function set_value( $value ) {
		$this->value = $value;
	}

	public function set_label( $value ) {
		$this->label = $value;
	}

	public function set_description( $value ) {
		$this->description = $value;
	}

	public function add_class( $class ) {
		$this->classes[] = $class;
	}

}