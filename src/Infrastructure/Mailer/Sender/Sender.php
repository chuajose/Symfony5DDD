<?php

declare( strict_types=1 );

namespace App\Infrastructure\Mailer\Sender;

use App\Infrastructure\Mailer\Sender\Adapter\MailerInterface;
use App\Infrastructure\Mailer\Renderer\Adapter\EmailRendererInterface;

final class Sender implements SenderInterface
{
	/**
	 * @var EmailRendererInterface
	 */
	private EmailRendererInterface $emailRenderer;
	/**
	 * @var MailerInterface
	 */
	private MailerInterface $mailer;

	public function __construct(EmailRendererInterface $emailRenderer, MailerInterface $mailer)
	{
		$this->emailRenderer = $emailRenderer;
		$this->mailer = $mailer;
	}

	public function send(string $from, array $recipients, string $template, array $data): void
	{
		$renderedEmail = $this->emailRenderer->render($template, $data);
		$this->mailer->send($from, $recipients, $renderedEmail->subject(), $renderedEmail->body());
	}
}
