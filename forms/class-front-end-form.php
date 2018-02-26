<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/14/2018
 * Time: 10:56 AM
 */

abstract class MOOBD_Front_End_Form extends MOOBD_Form {

    protected $redirect_after_save;
	protected $button_name;
	protected $button_value;
	protected $button_text;
	protected $button_classes;
	protected $save_callback;
	protected $redirect_after_cancel;
	protected $redirect_after_delete;
	protected $allow_delete;
	protected $allow_cancel;
	protected $message;

	abstract protected function save_fields( $fields );

	public function __construct( $id, $capability = 'read' ) {
		parent::__construct( $id, $capability );

		$this->button_classes = array();
		$this->redirect_after_save	=	'';
		$this->redirect_after_cancel	=	'';
        $this->redirect_after_delete	=	'';
		$this->button_name	= 'moob_forms_btn_save';
        $this->button_value	=	'save';
		$this->button_text	=	__('Save', 'mooberry-forms');
        $this->button_classes	=	array();
		$this->save_callback	=	'';
        $this->allow_delete  =  false;
        $this->allow_cancel  =  true;
	}

	public function set_save_callback( $value ) {
		$this->save_callback = $value;
	}

	public function render() {
		$access = $this->check_capability();
		 if ( $access ) {

			 // ob_start();


			// $content = ob_get_contents();
			// ob_end_clean();

			// return $content;
			$this->process_form();

		/*	if ( $this->contains_repeater() ) {
				$this->class .= ' repeater ';
			}*/

			?>
			<form id="<?php echo esc_attr($this->id); ?>" class="<?php echo esc_attr( implode(' ' , $this->classes ) ); ?> moobd-forms-form moobd-forms-form-<?php echo esc_attr($this->id); ?>" action="" method="post"  enctype="multipart/form-data">
				<?php
				foreach ( $this->fields as $field ) {
					/* $value = null;
					if ( array_key_exists( $field->id, $this->values ) ) {
						$value = $this->values[ $field->id ];
					} */
					$field->render( $this->object_id );
				}

				 wp_nonce_field( $this->nonce_field , $this->nonce_value);
				do_action( 'moobd_forms_before_save_button');
					?>
				<div id="moobd_forms_button_block">
                    <?php
               //     if ( is_admin() ) {
	           //         echo submit_button();
                //    } else {
	                    ?>
                        <button type="submit" value="<?php echo esc_attr( $this->button_value ); ?>"
                                name="<?php echo esc_attr( $this->button_name ); ?>"
                                class="btn btn-primary btn-large button-next moobd_forms-btn-save <?php echo implode( ' ', $this->button_classes ); ?>"><?php echo esc_html( $this->button_text ); ?></button>
	                    <?php
                //    }
                        do_action( 'moobd_forms_after_save_button');
                    if ( $this->allow_cancel ) {
	                    ?>
                        <button type="submit" value="cancel" id="moobd_forms_btn_cancel" name="moobd_forms_btn_cancel"
                                class="btn btn-primary btn-large button-next moobd_forms-btn-cancel <?php echo implode( ' ', $this->button_classes ); ?>"
                                formnovalidate><?php echo esc_html( __( 'Cancel', 'mooberry-front-end-forms' ) ); ?></button>
	                    <?php
                        do_action('moobd_forms_after_cancel_button');
                    }
                    if ( $this->allow_delete ) {
	                    ?>
                        <button type="submit" value="delete" id="moobd_forms_btn_delete" name="moobd_forms_btn_delete"
                                class="btn btn-danger btn-large button-next moobd_forms-btn-delete <?php echo implode( ' ', $this->button_classes ); ?>"
                                onclick="return confirm('Are you sure to delete?');"
                                formnovalidate><?php echo esc_html( __( 'Delete', 'mooberry-front-end-forms' ) ); ?></button>
	                    <?php
                        do_action( 'moobd_forms_after_delete_button');
                    }
                        ?>
                </div>
			</form>
			<?php
		 }
	}

	protected function save_form( $fields ) {
		if ( $this->check_capability() ) {
			do_action('moobd_forms_pre_save', $this, $fields );
			$success = $this->save_fields( $fields );
			if ( $success !== false ) {
				$this->item_id  = $success;
			}
			$callback_success = true;
			do_action('moobd_forms_post_save_pre_callback', $this, $fields );
			if ( $this->save_callback != ''  ) {
				if ( is_callable( $this->save_callback) ) {
					$callback_success = call_user_func ( $this->save_callback,  $this, $fields  );
					if ( $callback_success == null ) {
						$callback_success = true;
					}
				}
			}
			$success = $success && $callback_success;
			do_action('moobd_forms_post_save_post_callback', $this, $fields );
			if ( $success &&  $this->redirect_after_save != '' ) {
				do_action('moobd_forms_post_save_pre_redirect', $this, $fields);
				wp_safe_redirect( $this->redirect_after_save );
				exit();
			}
		}
		return $success;
	}



}