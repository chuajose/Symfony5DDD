<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Model;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserId
{
	/**
	 * @var UuidInterface
	 */
	private $uuid;

	public function __construct(UuidInterface $uuid)
	{
		Assertion::uuid($uuid);
		$this->uuid = $uuid;
	}
	public static function fromString(string $userId): UserId
	{
		return new self(Uuid::fromString($userId));
	}
	public function uuid(): UuidInterface
	{
		return $this->uuid;
	}
	public function toString(): string
	{
		return $this->uuid->toString();
	}
	public function equals($other): bool
	{
		return $other instanceof self && $this->uuid->equals($other->uuid);
	}
	public function __toString(): string
	{
		return $this->uuid->toString();
	}
}
