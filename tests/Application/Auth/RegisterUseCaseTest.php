<?php

declare( strict_types=1 );

/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 17/12/19
 * Time: 11:25
 */

namespace App\Tests\Application\Auth;

use App\Application\Auth\Dto\RegisterUserDto;
use App\Application\Auth\Events\UserWasRegister;
use App\Application\Auth\Exceptions\RegisterUserException;
use App\Application\Auth\Exceptions\ValidationException;
use App\Application\Auth\RegisterUseCase;
use App\Domain\Auth\Model\User;
use App\Domain\Auth\Model\UserId;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Password;
use App\Infrastructure\EventDispatcher\EventDispatcherInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class RegisterUseCaseTest extends KernelTestCase {

	private $encodePassword;
	private $eventDispatcher;
	private $authRepository;
	private $user;
	private $eventRegister;

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
		//$this->eventDispatcher->expects($this->once())->method('dispatch');


		$create          = new RegisterUseCase( $this->authRepository,$this->encodePassword );
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
		//$this->eventDispatcher->expects($this->never())->method('dispatch');


		$create          = new RegisterUseCase( $this->authRepository,$this->encodePassword );
		$register = $create( $registerUserDtop );
	}

	protected function setUp():void {
		self::bootKernel();
		$container = self::$kernel->getContainer();
		$this->encodePassword            = self::$container->get(UserPasswordEncoderInterface::class);
		$this->authRepository            = self::createMock( AuthRepositoryInterface::class );
		$this->user = self::createMock(User::class);
		//$this->eventRegister = self::createMock(UserWasRegister::class);


	}

}
