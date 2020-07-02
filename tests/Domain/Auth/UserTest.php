<?php

declare( strict_types=1 );

/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 30/03/19
 * Time: 10:37
 */

namespace App\Tests\Domain\Auth;


use App\Domain\Auth\Model\User;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Username;
use App\Domain\User\Model\Follow;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase {

	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function given_valid_user_instance_when_create_static(): void {

		$user = User::create(Email::fromString('email@email.com'), 'usuario', Username::fromString('usuario1'), true ,'');

		$this->assertInstanceOf(User::class, $user);

	}




}
