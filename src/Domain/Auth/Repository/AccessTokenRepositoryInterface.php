<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Model\AccessToken;

interface AccessTokenRepositoryInterface
{
	public function find(string $accessTokenId): ?AccessToken;
	public function save(AccessToken $accessToken): void;
}
