<?php

declare( strict_types=1 );

namespace App\Application\Auth\Dto;

use App\Domain\Auth\Exception\EmailNotValidException;
use App\Domain\Auth\Model\UserId;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Password;
use App\Domain\Auth\ValueObject\Username;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;

/**
 * Class RegisterUserDto
 * @package App\Application\Auth\Dto
 */
class RegisterUserDto{

	/**
	 * @var UserId
	 */
	private UserId $id;
	/**
	 * @var string
	 */
	private string $name;
	/**
	 * @var Username
	 */
	private Username $username;
	/**
	 * @var Email
	 */
	private Email $email;
	/**
	 * @var Password
	 */
	private Password $password;


	/**
	 * RegisterUserDto constructor.
	 *
	 * @param string $name
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 *
	 * @throws EmailNotValidException
	 * @throws AssertionFailedException
	 */
	public function __construct( string $name, string $username, string $email, string $password ) {
		$this->id = UserId::fromString(Uuid::uuid4()->toString());
		$this->name = $name;
		$this->username = Username::fromString($username);
		$this->email    = Email::fromString($email);
		$this->password = new Password($password);
	}

	/**
	 * @return UserId
	 */
	public function getId(): UserId {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return Username
	 */
	public function getUsername(): Username {
		return $this->username;
	}


	/**
	 * @return Email
	 */
	public function getEmail(): Email {
		return $this->email;
	}


	/**
	 * @return Password
	 */
	public function getPassword(): Password {
		return $this->password;
	}

}
