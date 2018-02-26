<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/13/2018
 * Time: 10:53 AM
 */

class MOOBD_Form_Factory implements MOOBD_IForm_Factory {

	public static function create_form( $type, $id, $capability = '', $arg = '' ) {

		switch ( $type ) {
			case 'metabox':
				$form = new MOOBD_Metabox_Form( $id, $capability );
				$form->set_title( $arg );
				break;
			case 'post':
				$form = new MOOBD_Post_Form( $id, $capability );
				break;
			case 'option':
				$form = new MOOBD_Option_Form( $id, $capability, $arg );
				break;
		}

		return $form;
	}

}