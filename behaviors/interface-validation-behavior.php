<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/21/2018
 * Time: 1:04 PM
 */

interface MOOBD_IValidation_Behavior {

	public function __construct( $field   );

	public function validate_value( $value );

}

