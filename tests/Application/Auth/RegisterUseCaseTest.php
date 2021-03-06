<?php

declare( strict_types=1 );

namespace App\Tests\Application\Auth;

use App\Application\Auth\Dto\RegisterUserDto;
use App\Application\Auth\Exceptions\RegisterUserException;
use App\Application\Auth\RegisterUseCase;
use App\Domain\Auth\Model\User;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Shared\EventBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class RegisterUseCaseTest extends KernelTestCase {

	private $encodePassword;
	private $authRepository;
	private $user;
	private $eventBus;

	/**
	 *
	 * @test
	 *
	 * @group unit
	 */
	public function register_new_user() {
		$registerUserDtop = new RegisterUserDto(
			'pepe',
			'paquitos',
			'example@example.com',
			'Madfasf.s54634',

		);
		$this->authRepository->expects($this->once())->method('findOneByEmail')->willReturn(null);
		$this->eventBus->expects($this->once())->method('dispatch');


		$create          = new RegisterUseCase( $this->authRepository,$this->encodePassword, $this->eventBus );
		$register = $create( $registerUserDtop );

		return self::assertSame($registerUserDtop->getEmail()->toString(), $register->getEmail());
	}


	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function register_new_user_when_email_exist() {
		$this->expectException(RegisterUserException::class);
		$registerUserDtop = new RegisterUserDto(
			'pepe',
			'paquitos',
			'example@example.com',
			'Madfasf.s54634',
		);
		$this->authRepository->expects($this->once())->method('findOneByEmail')->willReturn($this->user);
		$this->eventBus->expects($this->never())->method('dispatch');


		$create          = new RegisterUseCase( $this->authRepository, $this->encodePassword, $this->eventBus );
		$register = $create( $registerUserDtop );
	}

	protected function setUp():void {
		self::bootKernel();
		$container = self::$kernel->getContainer();
		$this->encodePassword            = self::$container->get(UserPasswordEncoderInterface::class);
		$this->authRepository            = self::createMock( AuthRepositoryInterface::class );
		$this->user = self::createMock(User::class);
		$this->eventBus = self::createMock(EventBus::class);


	}

}
