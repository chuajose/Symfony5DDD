<?php

declare( strict_types=1 );

namespace App\Application\Auth;

use App\Application\Auth\Dto\RegisterUserDto;
use App\Application\Auth\Events\UserWasRegistered;
use App\Application\Auth\Exceptions\RegisterUserException;
use App\Domain\Auth\Model\User;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Shared\EventBus;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


final class RegisterUseCase {

	private AuthRepositoryInterface $authRepository;
	private UserPasswordEncoderInterface $encodePassword;
	private $eventBus;

	public function __construct( AuthRepositoryInterface $authRepository, UserPasswordEncoderInterface $encodePassword, EventBus $eventBus ) {
		$this->authRepository = $authRepository;
		$this->encodePassword = $encodePassword;
		$this->eventBus = $eventBus;
	}

	public function __invoke(RegisterUserDto $userDto ) {

		try {
			$user = User::create(
				$userDto->getEmail(),
				$userDto->getName(),
				$userDto->getUsername(),
				false,
				''
			);
			$password = $this->encodePassword->encodePassword( $user, $userDto->getPassword()->toString() );
			if ( $this->authRepository->findOneByEmail( $userDto->getEmail()->toString() ) ) {
				throw new \UnexpectedValueException( sprintf('Email %s already exist', $userDto->getEmail()->toString()), 422 );
			}
			$user->setPassword( $password );
			$user->setRoles( [ 'ROLE_USER' ] );
			$user->setActive( false );
			$user->setCreatedAt( new \DateTimeImmutable( 'now' ) );
			$user->setUpdatedAt( new \DateTimeImmutable( 'now' ) );
			$this->authRepository->save( $user );

			$event = new UserWasRegistered($user->getUsername(), $user->getEmail(), $user->getCreatedAt()->format('Y-m-d H:i:s'));
			$this->eventBus->dispatch($event);
			return $user;

		}catch ( Exception $e ) {
			throw new RegisterUserException($e->getMessage(), 2);
		}







	}
}
