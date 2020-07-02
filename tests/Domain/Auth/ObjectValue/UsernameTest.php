<?php

declare( strict_types=1 );

/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 8/03/19
 * Time: 12:57
 */

namespace App\Tests\Domain\Auth\ObjectValue;

use App\Domain\Auth\Exception\EmailNotValidException;
use App\Domain\Auth\Exception\UsernameNotValidException;
use App\Domain\Auth\ValueObject\Username;
use PHPUnit\Framework\TestCase;
use App\Domain\Auth\ValueObject\Email;

final class UsernameTest extends TestCase{

	public const MINIMUM_LENGTH = 3;
	public const MAX_LENGTH = 20;
	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function given_a_valid_username_it_should_create_a_valid_username(): void
	{
		$usernameString = 'lolaso.maximo';
		$username = Username::fromString($usernameString);
		self::assertSame($usernameString, $username->__toString());
	}


	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function given_a_short_username_it_should_return_exception(): void
	{
		$this->expectException(UsernameNotValidException::class);
		$usernameString = 'ab';
		$username = Username::fromString($usernameString);
	}

	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function given_a_invalid_character_on_username_it_should_return_exception(): void
	{
		$this->expectException(UsernameNotValidException::class);
		$usernameString = 'a?b';
		$username = Username::fromString($usernameString);
	}

	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function given_a_long_username_it_should_return_exception(): void
	{
		$this->expectException(UsernameNotValidException::class);
		$usernameString = '123456789012345678901';
		$username = Username::fromString($usernameString);
	}
}
