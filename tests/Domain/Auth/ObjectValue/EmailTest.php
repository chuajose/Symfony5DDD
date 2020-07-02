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
use PHPUnit\Framework\TestCase;
use App\Domain\Auth\ValueObject\Email;

final class EmailTest extends TestCase{

	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function given_a_valid_email_it_should_create_a_valid_email(): void
	{
		$emailString = 'lol@aso.maximo';
		$email = Email::fromString($emailString);
		self::assertSame($emailString, $email->__toString());
	}


	/**
	 * @test
	 *
	 * @group unit
	 *
	 * @throws \Exception
	 * @throws \Assert\AssertionFailedException
	 */
	public function given_a_invalid_email_it_should_return_exception(): void
	{
		$this->expectException(EmailNotValidException::class);
		$emailString = 'lolaso.maximo';
		$email = Email::fromString($emailString);
		self::assertSame($emailString, $email->__toString());
	}
}
