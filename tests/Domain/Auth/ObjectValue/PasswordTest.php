<?php

declare( strict_types=1 );

/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 7/03/19
 * Time: 14:39
 */

namespace App\Tests\Domain\Auth\ObjectValue;

use App\Application\Auth\Exceptions\ValidationPasswordException;
use App\Domain\Auth\Exception\PasswordNotValidException;
use App\Domain\Auth\ValueObject\Password;
use PHPUnit\Framework\TestCase;


final class PasswordTest extends TestCase{

	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \InvalidArgumentException
	 */
	public function given_short_password_return_error(): void
	{
		$this->expectException(PasswordNotValidException::class);

		$password = new Password('1245');
	}

	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \InvalidArgumentException
	 */
	public function given_only_text_password_return_error(): void
	{
		$this->expectException(PasswordNotValidException::class);

		$password = new Password('sssssssssssssss');
	}


	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \InvalidArgumentException
	 */
	public function given_only_numbers_password_return_error(): void
	{
		$this->expectException(PasswordNotValidException::class);

		$password = new Password('123456789');
	}


	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \InvalidArgumentException
	 */


	public function given_strong_password_return_instance_password(): void
	{
		$string = 'Comercaracol.12';
		$password = new Password($string);
		$this->assertSame($password->toString(), $string);
	}
}
