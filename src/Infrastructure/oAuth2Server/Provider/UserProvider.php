<?php

declare( strict_types=1 );

namespace App\Infrastructure\oAuth2Server\Provider;

use App\Domain\Auth\Model\User;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 * @package App\Infrastructure\oAuth2Server\Provider
 */
final class UserProvider implements UserProviderInterface
{
	/**
	 * @var AuthRepositoryInterface
	 */
	private AuthRepositoryInterface $authRepository;

	/**
	 * UserProvider constructor.
	 *
	 * @param AuthRepositoryInterface $authRepository
	 */
	public function __construct(AuthRepositoryInterface $authRepository)
	{
		$this->authRepository = $authRepository;
	}

	/**
	 * @param string $username
	 *
	 * @return UserInterface
	 */
	public function loadUserByUsername($username): UserInterface
	{
		return $this->findUsername($username);
	}

	/**
	 * @param string $username
	 *
	 * @return User
	 */
	private function findUsername(string $username): User
	{
		$user = $this->authRepository->findOneByEmail($username);
		if ($user !== null) {
			return $user;
		}
		throw new UsernameNotFoundException(
			sprintf('Username "%s" does not exist.', $username)
		);
	}

	/**
	 * @param UserInterface $user
	 *
	 * @return UserInterface
	 */
	public function refreshUser(UserInterface $user): UserInterface
	{
		if (!$user instanceof User) {
			throw new UnsupportedUserException(
				sprintf('Instances of "%s" are not supported.', \get_class($user))
			);
		}
		//TODO cambiado para poder devolver el username en la funcion getUsername
		//$username = $user->getUsername();
		$username = $user->getEmail();
		return $this->findUsername($username);
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	public function supportsClass($class): bool
	{
		return User::class === $class;
	}
}
