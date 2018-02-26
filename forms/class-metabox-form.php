<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 10:34 AM
 */
if ( !class_exists( 'MOOBD_Metabox_Form' ) ) {
	class MOOBD_Metabox_Form extends MOOBD_Form {


		protected $title;
		protected $objects;
		protected $context;
		protected $priority;
		protected $instructions;

		public function __construct(  $id, $capability = 'edit_post' ) {
			parent::__construct( $id, $capability );

			$this->objects  = null;
			$this->context  = 'advanced';
			$this->priority = 'default';
			$this->instructions = '';

			add_action( 'add_meta_boxes', array( $this, 'create' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );

		}

		public function set_title( $title ) {
			$this->title = $title;
		}

		public function set_instructions( $value ) {
			$this->instructions = $value;
		}


		/*	public function add_field( MOOBD_IField $field ) {
				$key = $field->get_key();
				$this->fields[ $key ] = $field;
			}*/


		public function set_objects( $value ) {
			$this->objects = $value;
		}

		public function add_object( $value ) {
			if ( ! is_array( $this->objects ) ) {
				if ( $this->objects === null ) {
					$this->objects = array();
				} else {
					$this->objects = array( $this->objects );
				}
			}
			$this->objects[] = $value;
		}



		public function set_context( $value ) {
			$this->context = $value;
		}

		public function set_priority( $value ) {
			$this->priority = $value;
		}

		public function create() {
			add_meta_box( $this->id,
				$this->title,
				array( $this, 'display' ),
				$this->objects,
				$this->context,
				$this->priority );
		}

		public function display( $post ) {
			$this->object_id = $post->ID;
			$values = get_post_meta( $this->object_id );
			foreach ( $values as $key => $value ) {
				$this->values[ $key ] = maybe_unserialize( $value[0] );
			}
			$this->render();
		}

		public function render() {

			$allowed = $this->check_capability();
			if ( $allowed ) {
				$this->output_nonce();
				$this->output_instructions();
				$this->render_fields( $this->object_id );
			}


			/*	$field_values = array();
				foreach ( $this->fields as $field_key => $field ) {
					$field_values[ $field_key ] = $field->get_value( $post->ID );
				}
				wp_nonce_field( $this->nonce_value, $this->nonce_field );*/
			//call_user_func( $this->display_callback, $field_values );

		}

		protected function output_instructions() {
			echo '<div class="' . implode(' ', $this->classes ) . ' moobdir-forms-instructions">' . esc_html( $this->instructions )  . '</div>';
		}

		protected function verify_form() {
			$valid = parent::verify_form();

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				$valid = false;
			}

			return $valid;
		}

		public function save_post( $post_id ) {
			$this->object_id = $post_id;
			$this->process_form();
			/*if ( ! isset( $_POST[ $this->nonce_field ] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce_value ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			foreach ( $this->fields as $field_key => $field ) {
				// Check if $_POST field(s) are available
				if ( array_key_exists( $field_key, $_POST ) ) {
					$field->save( $post_id, $_POST[ $field_key ] );
				}
			}


		}*/

		}

		protected function save_form( $fields ) {
			// TODO: Implement save_form() method.
			foreach ( $fields as $field ) {
				$value = $field->get_value();
				/*if ( $field->multiple ) {
					if ( ! is_array( $value ) ) {
						$value = array( $value );
					}
					delete_post_meta( $this->object_id, $field->id );
					foreach ( $value as $single_value ) {
						add_post_meta( $this->object_id, $field->id, $single_value );
					}

				} else {*/
					update_post_meta( $this->object_id, $field->get_id(), $value );
				//}
			}
		}

		protected function process_other_buttons() {
			// TODO: Implement process_other_buttons() method.
		}

		protected function display_message() {
			// TODO: Implement display_message() method.
		}
	}


}