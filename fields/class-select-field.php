<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 12:46 PM
 */

class MOOBD_Select_Field extends MOOBD_Options_Field {


	public function __construct( $id, $prefix ) {

	    parent::__construct( $id, $prefix );

	    $this->add_class( $this->prefix . 'select' );

	}

	protected function render_field( $object_id ) {
	    parent::render_field( $object_id );
		$required = ( $this->required ) ? ' required="required" ' : '';

		?>
		<select id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="<?php echo implode(' ', $this->classes); ?>" <?php echo $required; ?>>
		<?php
		foreach ( $this->options as $key => $value ) {
			$selected = ( $key == $this->value ) ? ' selected="selected" ' : '';

			?>
			<option value="<?php echo esc_attr($key); ?>" class="<?php echo esc_attr( $this->prefix ); ?>select-option <?php echo esc_attr( $this->prefix ); ?>select-option-<?php echo esc_attr($this->id); ?>" <?php echo $selected; ?>><?php echo esc_html($value); ?></option>
			<?php
		}
		?>
		</select>
		<?php
	}


}