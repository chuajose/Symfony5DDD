<?php

declare( strict_types=1 );

namespace App\Application\Auth;

use App\Domain\Auth\Model\PasswordRecovery;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Auth\Repository\PasswordRecoveryRepositoryInterface;
use App\Domain\Auth\ValueObject\Email;
use App\Infrastructure\Mailer\Sender\SenderInterface;
use App\Infrastructure\Messenger\Adapter\MessengerBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

final class RecoveryPasswordUseCase {

	/**
	 * @var PasswordRecoveryRepositoryInterface
	 */
	private $passwordRecovery;
	/**
	 * @var AuthRepositoryInterface
	 */
	private $userRepository;
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $userPasswordEncoder;
	/**
	 * @var MessengerBusInterface
	 */
	private $bus;
	/**
	 * @var SenderInterface
	 */
	private $mailer;
	/**
	 * @var TokenGeneratorInterface
	 */
	private $tokenGenerator;
	public function __construct(PasswordRecoveryRepositoryInterface $passwordRecovery, AuthRepositoryInterface $userRepository,TokenGeneratorInterface $tokenGenerator, SenderInterface $mailer ) {

		$this->userRepository      = $userRepository;
		$this->mailer              = $mailer;
		$this->tokenGenerator      = $tokenGenerator;
		$this->passwordRecovery    = $passwordRecovery;
	}

	public function execute(Email $email){


			$user = $this->userRepository->findOneByEmail($email->toString());

			if(!$user){
				throw new \Exception('user not found');
			}

			$token = $this->tokenGenerator->generateToken();

			$expired =new \DateTimeImmutable('now');
			$expired = $expired->add(new \DateInterval('PT45M')); // added 45 minutes

			$passwordRecovery = new PasswordRecovery(rand(1,10), $user, $token, $expired );


			if($existOld = $this->passwordRecovery->findOneByUser($user)){

				$this->passwordRecovery->remove($existOld);
			}

			$this->passwordRecovery->save($passwordRecovery);

			$this->mailer->send( $_ENV['MAILER_FROM'], [ $user->getEmail() ], 'emails/user/password_recovery.html.twig', [
				'token' =>$token,
			] );
		/*if ( $this->authRepository->findOneByUsername( $userDto->getUsername() ) ) {

			throw new ValidationException( 'Username already exist', 'username_exist', 1 );
		}*/


	}

}
