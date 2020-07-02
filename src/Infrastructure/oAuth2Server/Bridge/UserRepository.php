<?php

declare( strict_types=1 );

namespace App\Infrastructure\oAuth2Server\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Repository\AuthRepositoryInterface as AppUserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserRepository implements UserRepositoryInterface
{
	/**
	 * @var AppUserRepositoryInterface
	 */
	private AppUserRepositoryInterface $appUserRepository;
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private UserPasswordEncoderInterface $userPasswordEncoder;
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
	 * @param $username
	 * @param $password
	 * @param $grantType
	 * @param ClientEntityInterface $clientEntity
	 *
	 * @return UserEntityInterface|null
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
