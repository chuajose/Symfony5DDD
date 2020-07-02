<?php

declare( strict_types=1 );

/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 7/4/20
 * Time: 10:53
 */

namespace App\Tests\Application\Auth;


use App\Application\Auth\RecoveryPasswordUseCase;
use App\Domain\Auth\Model\PasswordRecovery;
use App\Domain\Auth\Model\User;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Auth\Repository\PasswordRecoveryRepositoryInterface;
use App\Domain\Auth\ValueObject\Email;
use App\Infrastructure\EventDispatcher\EventDispatcherInterface;
use App\Infrastructure\Mailer\Sender\SenderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Twig\Environment;

final class RecoverPasswordTest extends TestCase {
	private $passwordRecovery;
	private $authRepository;
	private $tokenGenerator;
	private $user;
	private $mailer;
	private $userPasswordRecovery;
	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function recovery_password() {

		$registerUseCase = new RecoveryPasswordUseCase($this->passwordRecovery, $this->authRepository, $this->tokenGenerator, $this->mailer);
		$this->authRepository->expects($this->once())->method('findOneByEmail')->willReturn($this->user);


		$this->user->expects($this->once())->method('getEmail')->willReturn('example@example.com');
		$this->passwordRecovery->expects($this->once())->method('save');
		$this->tokenGenerator->expects($this->once())->method('generateToken')->willReturn('mitoken');
		$this->mailer->expects($this->once())->method('send')->with('info@jeek.io', ['example@example.com' ], 'emails/user/password_recovery.html.twig', [
			'token' =>'mitoken',
		]);
		$registerUseCase->execute(Email::fromString('example@example.com'));



	}
	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function recovery_password_second_time77() {

		$registerUseCase = new RecoveryPasswordUseCase($this->passwordRecovery, $this->authRepository, $this->tokenGenerator, $this->mailer);
		$this->authRepository->expects($this->once())->method('findOneByEmail')->willReturn($this->user);


		$this->user->expects($this->once())->method('getEmail')->willReturn('example@example.com');
		$this->passwordRecovery->expects($this->once())->method('findOneByUser')->willReturn($this->userPasswordRecovery);
		$this->passwordRecovery->expects($this->once())->method('remove');
		$this->passwordRecovery->expects($this->once())->method('save');
		$this->tokenGenerator->expects($this->once())->method('generateToken')->willReturn('mitoken');
		$this->mailer->expects($this->once())->method('send')->with('info@jeek.io', ['example@example.com' ], 'emails/user/password_recovery.html.twig', [
			'token' =>'mitoken',
		]);
		$registerUseCase->execute(Email::fromString('example@example.com'));



	}
	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function recovery_password_when_user_not_exist() {
		$this->expectException(\Exception::class);
		$registerUseCase = new RecoveryPasswordUseCase($this->passwordRecovery, $this->authRepository, $this->tokenGenerator, $this->mailer);		$this->authRepository->expects($this->once())->method('findOneByEmail')->willReturn(null);
		$registerUseCase->execute(Email::fromString('example@example.com'));

	}


	protected function setUp():void {
		$this->twig = self::createMock(Environment::class);
		//$this->eventDispatcher = self::createMock(EventDispatcherInterface::class);
		$this->authRepository = self::createMock(AuthRepositoryInterface::class);
		$this->mailer = self::createMock(SenderInterface::class);
		$this->user = self::createMock(User::class);
		$this->tokenGenerator = self::createMock(TokenGeneratorInterface::class);
		$this->passwordRecovery = self::createMock(PasswordRecoveryRepositoryInterface::class);
		$this->userPasswordRecovery = self::createMock(PasswordRecovery::class);
	}
}
