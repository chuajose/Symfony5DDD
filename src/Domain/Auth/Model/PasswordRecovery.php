<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Model;


class PasswordRecovery {

	private $id;
	private $user;
	private $token;
	private $expired;

	/**
	 * PasswordRecovery constructor.
	 *
	 * @param $id
	 * @param $user
	 * @param $token
	 * @param $expired
	 */
	public function __construct( $id, $user, $token, $expired ) {
		$this->id      = $id;
		$this->user    = $user;
		$this->token   = $token;
		$this->expired = $expired;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ): void {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser( $user ): void {
		$this->user = $user;
	}

	/**
	 * @return mixed
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @param mixed $token
	 */
	public function setToken( $token ): void {
		$this->token = $token;
	}

	/**
	 * @return mixed
	 */
	public function getExpired() {
		return $this->expired;
	}

	/**
	 * @param mixed $expired
	 */
	public function setExpired( $expired ): void {
		$this->expired = $expired;
	}


}
