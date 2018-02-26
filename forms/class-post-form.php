<?php

class MOOBD_Post_Form extends MOOBD_Front_End_Form {
	
	protected $wp_post_fields;
	protected $post_type;
	protected $allow_save_as_draft;

	public function __construct ( $id, $capability = 'edit_post' ) {
		parent::__construct( $id, $capability);
		
		$this->item_id = 0;
		$this->post_type = 'post';
		$this->allow_save_as_draft = true;
		$this->values = array();

		$this->wp_post_fields = array_flip(array_keys( get_class_vars( 'WP_Post' ) ));
		
		$id_field = MOOBD_Field_Factory::create_field( 'hidden', 'ID' );
		$id_field->set_value( $this->item_id );
		$this->fields[] = $id_field;


		if ( $this->item_id != '0' ) {
			$post = get_post(  $this->item_id, ARRAY_A ); //array( ''fields' => 'all_with_meta' ) );
			
			$meta = get_post_meta( $this->item_id );
			$data = array();
			
			foreach ( $post as $key=>$value ) {
				$this->values[$key] = $value;
			//	$this->wp_post_fields[ $key] = $value;
			}
			foreach ( $meta as $key => $value ) {
				$this->values[ $key ] = $value[0];
			}
			
		}

		if ( $this->allow_save_as_draft ) {
			add_action( 'moobd_forms_after_save_button', array( $this, 'add_draft_button' ));
			add_action( 'moobd_forms_form_process_buttons', array( $this, 'process_draft_button' ) );
		}
	}

	public function set_post_type( $value ) {
		$this->post_type = $value;
	}
	
	protected function save_fields( $fields ) {
		
			$post_id = $fields['ID']->get_value();
			// post_id = 0 means add new
			$post_args = array();
			foreach ( array_keys($this->wp_post_fields) as $key ) {
				if ( array_key_exists( $key, $fields ) ) {
					$post_args[ $key ] = $fields[ $key ]->get_value();
				}
			}
			// grab just the meta fields
			$meta_fields = array_diff_key( $fields, $this->wp_post_fields );
			$meta_args = array();
			foreach ( $meta_fields as $key => $value ) {
				// ignore the thumbnail because we will handle that separately
				if ( $key != '_thumbnail_id' ) {
					$meta_args[ $key ] = $fields[ $key ]->get_value();
				}
			}
			$post_args['meta_input'] =  $meta_args;
			$post_args['post_type']	=	$this->post_type;

			if ( isset($_POST['moobd_forms_btn_save_draft']) ) {
				$post_args['post_status'] = 'draft';
			} else {
				$post_args['post_status'] = 'publish';
			}
			
			$new_post_id = wp_insert_post( $post_args, true );
			if ( is_wp_error($new_post_id) ) {
				// TO DO: Error handing
				print_r($new_post_id);
				return false;
			}

			// if there is not a value and there is a file name, a new file has been uploaded
			if ( empty( $_POST['_thumbnail_id'] ) && !empty($_FILES['_thumbnail_id_file']['name'] ) ) {
				$this->remove_featured_thumbnail( $post_id );
				$this->upload_featured_thumbnail( $new_post_id );
			}

			// if there is not a value and there is not a file name, a file has been deleted
			if ( empty( $_POST['_thumbnail_id'] ) && empty($_FILES['_thumbnail_id_file']['name'] )  ) {
				$this->remove_featured_thumbnail( $post_id );
			}


			//return true;
        return $new_post_id;
	}

	protected function upload_featured_thumbnail( $post_id ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');


		$attach_id = media_handle_upload( '_thumbnail_id_file', $post_id );


		update_post_meta($post_id,'_thumbnail_id',$attach_id);
		set_post_thumbnail( $post_id, $attach_id );
	}

	protected function remove_featured_thumbnail( $post_id ) {
		$attach_id = get_post_meta( $post_id, '_thumbnail_id', true);
		if ( $attach_id != '' ) {
			delete_post_thumbnail( $post_id );
			delete_post_meta( $post_id, '_thumbnail_id' );
			wp_delete_attachment( $attach_id, true);
		}
	}

	protected function delete() {
		$post_author_id = get_post_field( 'post_author', $this->item_id );
		if ( $post_author_id == wp_get_current_user()->ID ) {
			$capability = 'delete_others_posts';
		} else {
			$capability = 'delete_posts';
		}
	    if ( current_user_can( $capability ) ) {
	        wp_delete_post( $this->item_id );
	    }
	}

	public function add_draft_button() {
		?>
		<button type="submit" name="moobd_forms_btn_save_draft" value="draft" class="btn btn-primary btn-large button-next mfef-btn mfef-btn-save-draft" formnovalidate><?php echo esc_html( __('Save Draft', 'mooberry-front-end-forms') ); ?></button>
<?php

    }

    public function process_draft_button( $form ) {
	    // at least title or content or excerpt has to be filled in, otherwise
	    // skip validation on all fields for a draft

        $title = isset( $_POST[ 'post_title' ] ) ? sanitize_title($_POST['post_title']) : '';
        $content = isset( $_POST[ 'post_content' ] ) ? sanitize_text_field($_POST['post_content']) : '';
        $excerpt = isset( $_POST[ 'post_excerpt' ] ) ? sanitize_text_field($_POST['post_excerpt']) : '';

        if ( $title == '' && $content == '' && $excerpt == '' ) {
	        $this->message[] = __( 'At least title, content, or excerpt is required to save a draft.', 'mooberry-front-end-forms' );
        }

        foreach ( $form->fields as $field ) {
            if ( in_array('MFEF_Single_Field' , class_parents($field) ) ) {
	            $field->required            = false;
	            $field->validation_callback = '';
            } else {
	            foreach ( $field->fields as $repeater_field ) {
		            $repeater_field->required            = false;
		            $repeater_field->validation_callback = '';
	            }
            }

        }

    }

    public function process_other_buttons() {
	    // TODO: Implement process_other_buttons() method.
    }

  /*  public function display_message() {
	    // TODO: Implement display_message() method.
    }*/

    public function sanitize_form() {
	    return parent::sanitize_form(); // TODO: Change the autogenerated stub
    }



}
