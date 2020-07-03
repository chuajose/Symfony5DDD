<?php

declare( strict_types=1 );

/**
 * Created by lawyersapp.
 * User: Jose Manuel Suárez Bravo
 * Date: 3/7/20
 * Time: 11:37
 */

namespace App\Infrastructure\Events\Auth;

use App\Application\Auth\Events\UserWasRegistered;

final class DemoEventHandler {

	public function __invoke(UserWasRegistered $event) {
		dump('Envento captuardo 1');

	}
}
