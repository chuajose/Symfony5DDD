<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Model;

class Client
{
	/**
	 * @var string
	 */
	private string $id;
	/**
	 * @var string
	 */
	private string $name;
	/**
	 * @var string
	 */
	private string $secret;
	/**
	 * @var string
	 */
	private string $redirect;
	/**
	 * @var bool
	 */
	private bool $active;
	/**
	 * Client constructor.
	 * @param ClientId $clientId
	 * @param string $name
	 */
	public function __construct(ClientId $clientId, string $name)
	{
		$this->id = $clientId->toString();
		$this->name = $name;
	}
	public static function create(string $name): Client
	{
		$clientId = ClientId::fromString(Uuid::uuid4()->toString());
		return new self($clientId, $name);
	}
	public function getId(): ClientId
	{
		return ClientId::fromString($this->id);
	}
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	/**
	 * @return string
	 */
	public function getSecret(): string
	{
		return $this->secret;
	}
	/**
	 * @param string $secret
	 */
	public function setSecret(string $secret): void
	{
		$this->secret = $secret;
	}
	/**
	 * @return string
	 */
	public function getRedirect(): string
	{
		return $this->redirect;
	}
	/**
	 * @param string $redirect
	 */
	public function setRedirect(string $redirect): void
	{
		$this->redirect = $redirect;
	}
	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->active;
	}
	/**
	 * @param bool $active
	 */
	public function setActive(bool $active): void
	{
		$this->active = $active;
	}
}
