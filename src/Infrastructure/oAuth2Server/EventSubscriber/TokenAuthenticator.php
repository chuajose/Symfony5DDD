<?php

declare( strict_types=1 );

namespace App\Infrastructure\oAuth2Server\EventSubscriber;


use App\Domain\Auth\Model\User;
use App\Infrastructure\Persistence\Doctrine\Auth\AccessTokenRepository;
use League\OAuth2\Server\ResourceServer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

/**
 * Class TokenAuthenticator
 * @package App\Infrastructure\oAuth2Server\EventSubscriber
 */
class TokenAuthenticator extends AbstractGuardAuthenticator {

	/**
	 * @var AuthRepositoryInterface
	 */
	private AuthRepositoryInterface $userRepository;
	/**
	 * @var AccessTokenRepository
	 */
	private AccessTokenRepository $accessTokenRepository;
	/**
	 * @var ResourceServer
	 */
	private ResourceServer $resourceServer;
	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * TokenAuthenticator constructor.
	 *
	 * @param AuthRepositoryInterface $userRepository
	 * @param AccessTokenRepository $accessTokenRepository
	 * @param ResourceServer $resourceServer
	 * @param LoggerInterface $logger
	 */
	public function __construct( AuthRepositoryInterface $userRepository, AccessTokenRepository $accessTokenRepository, ResourceServer $resourceServer, LoggerInterface $logger ) {
		$this->userRepository = $userRepository;
		$this->accessTokenRepository = $accessTokenRepository;
		$this->resourceServer = $resourceServer;
		$this->logger = $logger;
	}

	/**
	 * Called on every request to decide if this authenticator should be
	 * used for the request. Returning false will cause this authenticator
	 * to be skipped.
	 *
	 * @param Request $request
	 *
	 * @return bool
	 */
	public function supports( Request $request ):bool {

		//return $request->headers->has( 'X-AUTH-TOKEN' );
		return $request->headers->has( 'Authorization' );
	}

	/**
	 * Called on every request. Return whatever credentials you want to
	 * be passed to getUser() as $credentials.
	 *
	 * @param Request $request
	 *
	 * @return array
	 * @throws OAuthServerException
	 */
	public function getCredentials( Request $request ):array {
		$psr17Factory = new Psr17Factory();
		$psrRequest = (new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory))->createRequest($request);
//		$psrRequest = (new DiactorosFactory())->createRequest($request);

//		dd($psrRequest);
		try {
			$psrRequest = $this->resourceServer->validateAuthenticatedRequest($psrRequest);

		} catch (OAuthServerException $exception) {
			throw $exception;
			//throw  new \Exception($exception->getMessage());
		} catch (\Exception $exception) {
			throw new OAuthServerException($exception->getMessage(), 0, 'unknown_error', Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		return [
			'token' => $psrRequest->getAttribute('oauth_user_id'),
		];
	}

	/**
	 * @param mixed $credentials
	 * @param UserProviderInterface $userProvider
	 *
	 * @return User|bool|UserInterface|null
	 */
	public function getUser( $credentials, UserProviderInterface $userProvider ) {

		$apiToken = $credentials['token'];
		if ( null === $apiToken ) {
			return false;
		}

		// if a User object, checkCredentials() is called
		return $this->userRepository->find(Uuid::fromString($apiToken));
	}

	/**
	 * @param mixed $credentials
	 * @param UserInterface $user
	 *
	 * @return bool
	 */
	public function checkCredentials( $credentials, UserInterface $user ):bool {
		// check credentials - e.g. make sure the password is valid
		// no credential check is needed in this case
		// return true to cause authentication success
		return true;
	}

	/**
	 * @param Request $request
	 * @param TokenInterface $token
	 * @param string $providerKey
	 *
	 * @return Response|null
	 */
	public function onAuthenticationSuccess( Request $request, TokenInterface $token, $providerKey ):?Response {
		// on success, let the request continue
		$this->logger->critical('login');
		return null;
	}

	/**
	 * @param Request $request
	 * @param AuthenticationException $exception
	 *
	 * @return JsonResponse|Response|null
	 */
	public function onAuthenticationFailure( Request $request, AuthenticationException $exception ) {
		$data = [
			'message' => strtr( $exception->getMessageKey(), $exception->getMessageData() )

			// or to translate this message
			// $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
		];

		$this->logger->critical('error');
		return new JsonResponse( $data, Response::HTTP_FORBIDDEN );
	}

	/**
	 * Called when authentication is needed, but it's not sent
	 *
	 * @param Request $request
	 * @param AuthenticationException|null $authException
	 *
	 * @return JsonResponse
	 */
	public function start( Request $request, AuthenticationException $authException = null ) {
		$data = [
			// you might translate this message
			'message' => 'Authentication Required'
		];
		return new JsonResponse( $data, Response::HTTP_UNAUTHORIZED );
	}

	/**
	 * @return bool
	 */
	public function supportsRememberMe():bool {
		return false;
	}
}
