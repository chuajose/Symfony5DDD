<?php

declare( strict_types=1 );

namespace App\Infrastructure\Mailer\Sender\Adapter;

use Swift_Mailer;

final class SwiftMailer implements MailerInterface
{
	/**
	 * @var Swift_Mailer
	 */
	private Swift_Mailer $mailer;
	public function __construct( Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
	}
	public function send(string $from, array $recipients, string $subject, string $body): void
	{
		$swiftMessage = (new \Swift_Message($subject))
			->setFrom($from)
			->setTo($recipients)
			->setBody($body, 'text/html');
		$this->mailer->send($swiftMessage);
	}
}
