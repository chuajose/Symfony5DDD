<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Model;

use DateTime;

class AccessToken
{
	/**
	 * @var string
	 */
	private string $id;
	/**
	 * @var string
	 */
	private string $userId;
	/**
	 * @var string
	 */
	private string $clientId;
	/**
	 * @var array
	 */
	private array $scopes;
	/**
	 * @var bool
	 */
	private bool $revoked;
	/**
	 * @var DateTime
	 */
	private DateTime $createdAt;
	/**
	 * @var DateTime
	 */
	private DateTime $updatedAt;
	/**
	 * @var DateTime
	 */
	private DateTime $expiresAt;
	/**
	 * Token constructor.
	 * @param string $id
	 * @param string $userId
	 * @param string $clientId
	 * @param array $scopes
	 * @param bool $revoked
	 * @param DateTime $createdAt
	 * @param DateTime $updatedAt
	 * @param DateTime $expiresAt
	 */
	public function __construct(
		string $id,
		string $userId,
		string $clientId,
		array $scopes,
		bool $revoked,
		DateTime $createdAt,
		DateTime $updatedAt,
		DateTime $expiresAt
	) {
		$this->id = $id;
		$this->userId = $userId;
		$this->clientId = $clientId;
		$this->scopes = $scopes;
		$this->revoked = $revoked;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
		$this->expiresAt = $expiresAt;
	}
	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}
	/**
	 * @return string
	 */
	public function getUserId(): string
	{
		return $this->userId;
	}
	/**
	 * @return string
	 */
	public function getClientId(): string
	{
		return $this->clientId;
	}
	/**
	 * @return array
	 */
	public function getScopes(): array
	{
		return $this->scopes;
	}
	/**
	 * @return bool
	 */
	public function isRevoked(): bool
	{
		return $this->revoked;
	}
	public function revoke(): void
	{
		$this->revoked = true;
	}
	/**
	 * @return DateTime
	 */
	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}
	/**
	 * @return DateTime
	 */
	public function getUpdatedAt(): DateTime
	{
		return $this->updatedAt;
	}
	/**
	 * @param DateTime $updatedAt
	 */
	public function setUpdatedAt( DateTime $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}
	/**
	 * @return DateTime
	 */
	public function getExpiresAt(): DateTime
	{
		return $this->expiresAt;
	}
}
