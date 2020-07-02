<?php

declare( strict_types=1 );

namespace App\Infrastructure\Mailer\Sender\Adapter;

interface MailerInterface {
	public function send(string $from, array $recipients, string $subject, string $body): void;
}
