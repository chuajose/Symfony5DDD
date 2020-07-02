<?php

declare( strict_types=1 );

namespace App\Infrastructure\Mailer\Sender;

interface SenderInterface {
	public function send(string $from, array $recipients, string $template, array $data): void;
}
