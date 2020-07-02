<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Model\User;
use Ramsey\Uuid\UuidInterface;

interface AuthRepositoryInterface {

	public function find(UuidInterface $id): ?User;
	public function findAll(): ?array ;
	public function findOneByEmail(string $email): ?User;
	public function findOneByUsername(string $username): ?User;
	public function findOneByResetToken(string $token): ?User;
	public function save(User $user): void;
	public function remove(User $user): void;
	public function findRandom(): ?User;
}
