<?php

declare( strict_types=1 );

namespace App\Infrastructure\oAuth2Server\Bridge;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use App\Domain\Auth\Repository\RefreshTokenRepositoryInterface as AppRefreshTokenRepositoryInterface;
use App\Domain\Auth\Model\RefreshToken as AppRefreshToken;

/**
 * Class RefreshTokenRepository
 * @package App\Infrastructure\oAuth2Server\Bridge
 */
final class RefreshTokenRepository implements RefreshTokenRepositoryInterface {


	/**
	 * @var AppRefreshTokenRepositoryInterface
	 */
	private AppRefreshTokenRepositoryInterface $appRefreshTokenRepository;
	/**
	 * @var AccessTokenRepository
	 */
	private AccessTokenRepository $accessTokenRepository;


	/**
	 * RefreshTokenRepository constructor.
	 *
	 * @param AppRefreshTokenRepositoryInterface $appRefreshTokenRepository
	 * @param AccessTokenRepository $accessTokenRepository
	 */
	public function __construct(AppRefreshTokenRepositoryInterface $appRefreshTokenRepository, AccessTokenRepository $accessTokenRepository)
	{
		$this->appRefreshTokenRepository = $appRefreshTokenRepository;
		$this->accessTokenRepository = $accessTokenRepository;
	}


	/**
	 * @return RefreshTokenEntityInterface
	 */
	public function getNewRefreshToken(): RefreshTokenEntityInterface {
		return new RefreshToken();
	}

	/**
	 * @param RefreshTokenEntityInterface $refreshTokenEntity
	 */
	public function persistNewRefreshToken( RefreshTokenEntityInterface $refreshTokenEntity): void {
		$id                        = $refreshTokenEntity->getIdentifier();
		$accessTokenId             = $refreshTokenEntity->getAccessToken()->getIdentifier();
		$expiryDateTime            = $refreshTokenEntity->getExpiryDateTime();
		$refreshTokenPersistEntity = new AppRefreshToken( $id, $accessTokenId, $expiryDateTime );
		$this->appRefreshTokenRepository->save( $refreshTokenPersistEntity );
	}

	/**
	 * @param $tokenId
	 */
	public function revokeRefreshToken( $tokenId ): void {
		$refreshTokenPersistEntity = $this->appRefreshTokenRepository->find( $tokenId );
		if ( $refreshTokenPersistEntity === null ) {
			return;
		}
		$refreshTokenPersistEntity->revoke();
		$this->appRefreshTokenRepository->save( $refreshTokenPersistEntity );
	}

	/**
	 * @param $tokenId
	 *
	 * @return bool
	 */
	public function isRefreshTokenRevoked( $tokenId ): bool {
		$refreshTokenPersistEntity = $this->appRefreshTokenRepository->find( $tokenId );
		if ( $refreshTokenPersistEntity === null || $refreshTokenPersistEntity->isRevoked() ) {
			return true;
		}

		return $this->accessTokenRepository->isAccessTokenRevoked( $refreshTokenPersistEntity->getAccessTokenId() );
	}
}
