<?php

declare( strict_types=1 );

namespace App\Domain\Auth\ValueObject;

use App\Domain\Auth\Exception\PasswordNotValidException;

/**
 * Class Password
 * @package App\Domain\Auth\ValueObject
 */
final class Password {

	/**
	 *
	 */
	public const MINIMUM_LENGTH = 6;

	/**
	 * @var string
	 */
	private string $password;

	/**
	 * Password constructor.
	 *
	 * @param string $password
	 *
	 * @throws PasswordNotValidException
	 */
	public function __construct(string $password) {
		$this->password = $password;
		$this->isValid();
	}

	/**
	 * @throws PasswordNotValidException
	 */
	private function isValid(): void {
		$errors = [];
		if (strlen($this->password) < self::MINIMUM_LENGTH) {
			$errors[] = "Password too short!";
		}

		if (!preg_match("#[0-9]+#", $this->password)) {
			$errors[] = "Password must include at least one number!";
		}

		if (!preg_match("#[a-zA-Z]+#", $this->password)) {
			$errors[] = "Password must include at least one letter!";
		}

		if (!preg_match("/[A-Z]/", $this->password)) {
			$errors[] = "Password must include at least one letter!";
		}

		if (!preg_match("/[\W]/", $this->password)) {
			$errors[] = "Password must include at symbol!";
		}

		if(!empty($errors)){
			throw new PasswordNotValidException(
				json_encode($errors), 'password_insecure', 422);
		}
	}

	/**
	 * @return string
	 */
	public function toString(): string {

		return $this->password;
	}

	/**
	 * @return string
	 */
	public function __toString() {

		return $this->password;
	}
}
