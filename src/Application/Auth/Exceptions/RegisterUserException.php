<?php

declare( strict_types=1 );

/**
 * Created by lawyersapp.
 * User: Jose Manuel Suárez Bravo
 * Date: 2/7/20
 * Time: 12:15
 */

namespace App\Application\Auth\Exceptions;


final class RegisterUserException extends \Exception {

	public function __construct( string $message = "", $code = 0) {
		parent::__construct( $message, $code );

	}
}
