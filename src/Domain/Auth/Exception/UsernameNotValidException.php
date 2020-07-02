<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Exception;

use Throwable;

class UsernameNotValidException extends \Exception {

	private string $detail;

	public function __construct( string $message, $code = 422, Throwable $previous = null ) {
		$this->detail = $message;
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * @return mixed
	 */
	public function getError() {
		return [
			'title'  => 'An error occurred',
			'field'  => 'username',
			'detail' => $this->detail,
		];
	}
}
