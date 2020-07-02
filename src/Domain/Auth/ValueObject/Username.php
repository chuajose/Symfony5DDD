<?php

declare( strict_types=1 );

namespace App\Domain\Auth\ValueObject;

use App\Domain\Auth\Exception\UsernameNotValidException;

final class Username {

	private string $username;

	public const MINIMUM_LENGTH = 3;
	public const MAX_LENGTH = 20;

	public function __construct(string $username) {

		if(preg_match('/[^A-Za-z0-9_.-]/', $username)){
			throw new UsernameNotValidException('Only alphanumeric and - are valid');
		}
		if (strlen($username) < self::MINIMUM_LENGTH) {
			throw new UsernameNotValidException('This value is too short. It should have 6 characters or more');

		}
		if (strlen($username) > self::MAX_LENGTH) {
			throw new UsernameNotValidException('This value is too long. It should have 20 characters or less');

		}
		$this->username = $username;
	}

	public static function fromString(string $username): self
	{

		$usernameVo = new self($username);

		$usernameVo->username = $username;

		return $usernameVo;
	}
	public function value(): string {

		return $this->username;
	}

	public function __toString() {
		return $this->username;
	}

}
