<?php

declare( strict_types=1 );

namespace App\Application\Auth;

use App\Application\Auth\Dto\RegisterUserDto;
use App\Application\Auth\Exceptions\RegisterUserException;
use App\Domain\Auth\Model\User;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


final class RegisterUseCase {

	private AuthRepositoryInterface $authRepository;
	private UserPasswordEncoderInterface $encodePassword;
	private $dispatcher;

	public function __construct( AuthRepositoryInterface $authRepository, UserPasswordEncoderInterface $encodePassword ) {
		$this->authRepository = $authRepository;
		$this->encodePassword = $encodePassword;
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

			/*$event = new UserWasRegister($user);
			$this->dispatcher->dispatch($event);*/
			return $user;

		}catch ( Exception $e ) {
			throw new RegisterUserException($e->getMessage(), 2);
		}







	}
}
