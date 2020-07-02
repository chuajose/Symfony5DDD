<?php

declare( strict_types=1 );

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Model\Client;

interface ClientRepositoryInterface
{
	public function findActive(string $clientId): ?Client;
}
