<?php
namespace App\Infrastructure\oAuth2Server\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Repository\AuthRepositoryInterface as AppUserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class UserRepository implements UserRepositoryInterface
{
	/**
	 * @var AppUserRepositoryInterface
	 */
	private $appUserRepository;
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $userPasswordEncoder;
	/**
	 * @var EventDispatcherInterface
	 */
	private $dispatcher;
	/**
	 * UserRepository constructor.
	 * @param AppUserRepositoryInterface $appUserRepository
	 * @param UserPasswordEncoderInterface $userPasswordEncoder
	 */
	public function __construct(
		AppUserRepositoryInterface $appUserRepository,
		UserPasswordEncoderInterface $userPasswordEncoder
		//\App\Infrastructure\EventDispatcher\EventDispatcherInterface $dispatcher
	) {
		$this->appUserRepository = $appUserRepository;
		$this->userPasswordEncoder = $userPasswordEncoder;
		//$this->dispatcher = $dispatcher;

	}
	/**
	 */
	public function getUserEntityByUserCredentials(
		$username,
		$password,
		$grantType,
		ClientEntityInterface $clientEntity
	): ?UserEntityInterface {
		$appUser = $this->appUserRepository->findOneByEmail($username);

		if ($appUser === null) {
			return null;
		}

		//$encodedPassword = $this->userPasswordEncoder->encodePassword($appUser, $password);
		$isPasswordValid = $this->userPasswordEncoder->isPasswordValid($appUser, $password);

		if (!$isPasswordValid) {
			return null;
		}
		//$event = new UserWasLogin($appUser);
		//$this->dispatcher->dispatch($event);
		return new User($appUser->getId());
		//return $appUser;
	}
}
