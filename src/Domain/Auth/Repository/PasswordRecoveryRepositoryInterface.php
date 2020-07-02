<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Model\PasswordRecovery;
use App\Domain\Auth\Model\User;

interface PasswordRecoveryRepositoryInterface {

	public function findOneByUser(User $user): ?PasswordRecovery;
	public function findOneByToken(string $token): ?PasswordRecovery;
	public function save(PasswordRecovery $user): void;
	public function remove(PasswordRecovery $user): void;
}
