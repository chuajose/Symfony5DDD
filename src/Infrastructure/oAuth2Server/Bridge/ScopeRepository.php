<?php

declare( strict_types=1 );

namespace App\Infrastructure\oAuth2Server\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Class ScopeRepository
 * @package App\Infrastructure\oAuth2Server\Bridge
 */
final class ScopeRepository implements ScopeRepositoryInterface
{

	/**
	 * @param string $identifier
	 *
	 * @return ScopeEntityInterface
	 */
	public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface
	{
		if (Scope::hasScope($identifier)) {
			return new Scope($identifier);
		}
		return null;
	}

	/**
	 * @param array $scopes
	 * @param string $grantType
	 * @param ClientEntityInterface $clientEntity
	 * @param null $userIdentifier
	 *
	 * @return array
	 */
	public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null): array
	{
		$filteredScopes = [];
		/** @var Scope $scope */
		foreach ($scopes as $scope) {
			$hasScope = Scope::hasScope($scope->getIdentifier());
			if ($hasScope) {
				$filteredScopes[] = $scope;
			}
		}
		return $filteredScopes;
	}
}
