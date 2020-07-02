<?php

declare( strict_types=1 );

namespace App\Infrastructure\Mailer\Renderer;

final class RenderedEmail
{
	/**
	 * @var string
	 */
	private string $subject;
	/**
	 * @var string
	 */
	private string $body;

	public function __construct(string $subject, string $body)
	{
		$this->subject = $subject;
		$this->body = $body;
	}

	public function subject(): string
	{
		return $this->subject;
	}

	public function body(): string
	{
		return $this->body;
	}
}
