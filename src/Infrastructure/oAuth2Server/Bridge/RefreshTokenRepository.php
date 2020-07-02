<?php
namespace App\Infrastructure\oAuth2Server\Bridge;


use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use App\Domain\Auth\Repository\RefreshTokenRepositoryInterface as AppRefreshTokenRepositoryInterface;
use App\Domain\Auth\Model\RefreshToken as AppRefreshToken;

final class RefreshTokenRepository implements RefreshTokenRepositoryInterface {


	private $appRefreshTokenRepository;
	private $accessTokenRepository;


	public function __construct(AppRefreshTokenRepositoryInterface $appRefreshTokenRepository, AccessTokenRepository $accessTokenRepository)
	{
		$this->appRefreshTokenRepository = $appRefreshTokenRepository;
		$this->accessTokenRepository = $accessTokenRepository;
	}

	/**
	 */
	public function getNewRefreshToken(): RefreshTokenEntityInterface {
		return new RefreshToken();
	}

	/**
	 */
	public function persistNewRefreshToken( RefreshTokenEntityInterface $refreshTokenEntity): void {
		$id                        = $refreshTokenEntity->getIdentifier();
		$accessTokenId             = $refreshTokenEntity->getAccessToken()->getIdentifier();
		$expiryDateTime            = $refreshTokenEntity->getExpiryDateTime();
		$refreshTokenPersistEntity = new AppRefreshToken( $id, $accessTokenId, $expiryDateTime );
		$this->appRefreshTokenRepository->save( $refreshTokenPersistEntity );
	}

	/**
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
	 */
	public function isRefreshTokenRevoked( $tokenId ): bool {
		$refreshTokenPersistEntity = $this->appRefreshTokenRepository->find( $tokenId );
		if ( $refreshTokenPersistEntity === null || $refreshTokenPersistEntity->isRevoked() ) {
			return true;
		}

		return $this->accessTokenRepository->isAccessTokenRevoked( $refreshTokenPersistEntity->getAccessTokenId() );
	}
}
