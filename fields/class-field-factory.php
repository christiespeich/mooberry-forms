<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 11:00 AM
 */

class MOOBD_Field_Factory implements MOOBD_IField_Factory {

	public static function create_field( $type, $id, MOOBD_IValidation_Behavior $validation_behavior = null ) {

		$prefix = 'moobd-forms-';

		switch ( $type ) {
			case 'text':
				$field = new MOOBD_Textbox_Field( $id, $prefix, $validation_behavior );
				break;
			case 'select':
				$field = new MOOBD_Select_Field( $id, $prefix, $validation_behavior );
				break;
			case 'radio':
				$field = new MOOBD_Radio_Field( $id, $prefix, $validation_behavior );
				break;
			case 'repeater':
				$field = new MOOBD_Repeater_Field( $id, $prefix );
				break;
			case 'hidden':
				$field = new MOOBD_Hidden_Field( $id, $prefix, $validation_behavior );
				break;
			case 'checkbox':
				$field = new MOOBD_Checkbox_Field( $id, $prefix, $validation_behavior );
				break;
			case 'textarea':
				$field = new MOOBD_Textarea_Field( $id, $prefix );
				break;
				case 'wysiwyg':
				$field = new MOOBD_WYSIWYG_Field( $id, $prefix );
				break;
			case 'phone':
				$field = new MOOBD_Phone_Field( $id, $prefix );
				break;
			case 'address':
				$field = new MOOBD_Address_Field( $id, $prefix );
				break;
			case 'url':
				$field = new MOOBD_URL_Field( $id, $prefix );
				break;
			case 'email':
				$field = new MOOBD_Email_Field( $id, $prefix );
				break;
			default:
				$field = new MOOBD_Textbox_Field( $id, $prefix );
		}

		return $field;
	}
}