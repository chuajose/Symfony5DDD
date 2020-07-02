<?php

declare( strict_types=1 );

namespace App\Domain\Auth\ValueObject;

use App\Domain\Auth\Exception\EmailNotValidException;
use Assert\Assertion;

/**
 * Class Email
 * @package App\Domain\Auth\ValueObject
 */
final class Email {

	/**
	 * @param string $email
	 *
	 * @return Email
	 * @throws EmailNotValidException
	 */
	public static function fromString(string $email): self
	{
		try{
			Assertion::email($email, 'Not a valid email');

		}catch (\Throwable $e){

			throw  new EmailNotValidException(sprintf('%s not a valid Email',$email));
		}

		$mail = new self();

		$mail->email = $email;

		return $mail;
	}

	public function toString(): string
	{
		return $this->email;
	}

	public function __toString(): string
	{
		return $this->email;
	}

	public function __construct()
	{
	}

	/** @var string */
	private string $email;
}
