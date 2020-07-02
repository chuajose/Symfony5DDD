<?php

declare( strict_types=1 );

namespace App\Infrastructure\oAuth2Server\Bridge;

use App\Domain\Auth\Repository\AccessTokenRepositoryInterface as AppAccessTokenRepositoryInterface;
use App\Domain\Auth\Model\AccessToken as AppAccessToken;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * Class AccessTokenRepository
 * @package App\Infrastructure\oAuth2Server\Bridge
 */
final class AccessTokenRepository implements AccessTokenRepositoryInterface {
	/**
	 * @var AppAccessTokenRepositoryInterface
	 */
	private AppAccessTokenRepositoryInterface $appAccessTokenRepository;

	/**
	 * AccessTokenRepository constructor.
	 *
	 * @param AppAccessTokenRepositoryInterface $appAccessTokenRepository
	 */
	public function __construct( AppAccessTokenRepositoryInterface $appAccessTokenRepository ) {
		$this->appAccessTokenRepository = $appAccessTokenRepository;
	}

	/**
	 * @param ClientEntityInterface $clientEntity
	 * @param array $scopes
	 * @param null $userIdentifier
	 *
	 * @return AccessTokenEntityInterface
	 */
	public function getNewToken( ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null ): AccessTokenEntityInterface {
		//dd(new AccessToken($userIdentifier, $scopes));
		return new AccessToken( $userIdentifier, $scopes );
	}

	/**
	 * @param AccessTokenEntityInterface $accessTokenEntity
	 */
	public function persistNewAccessToken( AccessTokenEntityInterface $accessTokenEntity ): void {

		$appAccessToken = new AppAccessToken(
			$accessTokenEntity->getIdentifier(),
			$accessTokenEntity->getUserIdentifier(),
			$accessTokenEntity->getClient()->getIdentifier(),
			$this->scopesToArray( $accessTokenEntity->getScopes() ),
			false,
			new \DateTime(),
			new \DateTime(),
			$accessTokenEntity->getExpiryDateTime()
		);
		$this->appAccessTokenRepository->save( $appAccessToken );


	}

	/**
	 * @param array $scopes
	 *
	 * @return array
	 */
	private function scopesToArray( array $scopes ): array {
		return array_map( function ( $scope ) {
			return $scope->getIdentifier();
		}, $scopes );
	}

	/**
	 * @param $tokenId
	 */
	public function revokeAccessToken( $tokenId ): void {
		$appAccessToken = $this->appAccessTokenRepository->find( $tokenId );
		if ( $appAccessToken === null ) {
			return;
		}
		$appAccessToken->revoke();
		$this->appAccessTokenRepository->save( $appAccessToken );
	}

	/**
	 * @param $tokenId
	 *
	 * @return bool|null
	 */
	public function isAccessTokenRevoked( $tokenId ): ?bool {
		$appAccessToken = $this->appAccessTokenRepository->find( $tokenId );
		if ( $appAccessToken === null ) {
			return true;
		}

		return $appAccessToken->isRevoked();
	}
}
