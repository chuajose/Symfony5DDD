<?php

declare( strict_types=1 );

/**
 * Created by lawyersapp.
 * User: Jose Manuel Suárez Bravo
 * Date: 3/7/20
 * Time: 11:30
 */

namespace App\Application\Auth\Events;


use App\Domain\Shared\EventMessage;

final class UserWasRegistered implements EventMessage {

	private string $username;
	private string $email;
	private string $createdAt;
	private string $ocurredOn;

	public function __construct(string $username, string $email, string $createdAt)
	{
		$this->username  = $username;
		$this->email     = $email;
		$this->createdAt = $createdAt;
		$this->ocurredOn = (new \DateTimeImmutable())->format(\DateTimeImmutable::ATOM);
	}

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @return string
	 */
	public function getCreatedAt(): string {
		return $this->createdAt;
	}

	/**
	 * @return string
	 */
	public function getOcurredOn(): string {
		return $this->ocurredOn;
	}


}
