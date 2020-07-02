<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Model;

use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Username;
use Symfony\Component\Security\Core\User\UserInterface;
use Ramsey\Uuid\Uuid;

class User implements UserInterface {
	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var string
	 */
	private $email;
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var array
	 */
	private $roles;
	/**
	 * @var string
	 */
	private $password;
	/**
	 * @var bool
	 */
	private $active;

	/**
	 * @var bool
	 */
	private $private;

	/**
	 * @var string
	 */
	private $description;
	/**
	 * @var string
	 */
	private $created_at;

	/**
	 * @var string
	 */
	private $updated_at;

	private $reset_token;


	/**
	 * User constructor.
	 *
	 * @param UserId $userId
	 * @param Email $email
	 * @param string $name
	 * @param Username|null $username
	 * @param bool $private
	 * @param String $description
	 */
	public function __construct( UserId $userId, Email $email, string $name, ?Username $username, bool $private, String $description ) {
		$this->id            = $userId;
		$this->email         = $email->toString();
		$this->name          = $name;
		$this->username      = $username ? $username->value() : '';
		$this->created_at    = new \DateTimeImmutable( 'now' );
		$this->private       = $private;
		$this->description   = $description;
		//$this->image = new File();

	}


	/**
	 * @param Email $email
	 * @param string $name
	 *
	 * @param Username|null $username
	 * @param bool $private
	 * @param string $description
	 *
	 * @return User
	 */
	public static function create( Email $email, string $name, ?Username $username, bool $private, string $description ): User {
		$userId = UserId::fromString( Uuid::uuid4()->toString() );

		return new self( $userId, $email, $name, $username, $private, $description );
	}


	/**
	 * @return mixed
	 */
	public function getResetToken() {
		return $this->reset_token;
	}

	/**
	 * @param mixed $reset_token
	 */
	public function setResetToken( $reset_token ): void {
		$this->reset_token = $reset_token;
	}



	/**
	 * @return UserId
	 */
	public function getId(): string {
		return $this->id->toString();
	}

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}

	public function setUsername( Username $username ): void {
		$this->username = $username->value();
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( string $email ): void {
		$this->email = $email;
	}

	/**
	 * @return null|string
	 */
	public function getName(): ?string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void {
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public function getRoles() {
		return $this->roles;

	}

	/**
	 * @param array $roles
	 */
	public function setRoles( array $roles ): void {
		$this->roles = $roles;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ): void {
		$this->password = $password;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool {
		return $this->active ?? false;
	}

	/**
	 * @param bool $active
	 */
	public function setActive( bool $active ): void {
		$this->active = $active;
	}

	/**
	 * @return null|string
	 */
	public function getSalt(): ?string {
		return null;
	}

	/**
	 *
	 */
	public function eraseCredentials(): void {
		// Nothing
	}

	/**
	 * @return mixed
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * @param $createdAt
	 */
	public function setCreatedAt( $createdAt ) {
		$this->created_at = $createdAt;
		//return $this;
	}

	/**
	 * @return string
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}

	/**
	 * @param $updatedAt
	 *
	 * @return $this
	 */
	public function setUpdatedAt( $updatedAt ) {
		$this->updated_at = $updatedAt;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isPrivate(): bool {
		if ( ! $this->private ) {
			return false;
		}

		return $this->private;
	}

	/**
	 * @param bool $private
	 */
	public function setPrivate( bool $private ): void {
		$this->private = $private;
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {

		if ( ! $this->description ) {
			return '';
		}

		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ): void {
		$this->description = $description;
	}
}
