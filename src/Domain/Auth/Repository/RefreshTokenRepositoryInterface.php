<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Model\RefreshToken;

interface RefreshTokenRepositoryInterface
{
	public function find(string $refreshTokenId): ?RefreshToken;
	public function save(RefreshToken $refreshToken): void;
}
