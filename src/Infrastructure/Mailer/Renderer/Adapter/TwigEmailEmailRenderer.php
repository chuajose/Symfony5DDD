<?php

declare( strict_types=1 );

namespace App\Infrastructure\Mailer\Renderer\Adapter;

use App\Infrastructure\Mailer\Renderer\RenderedEmail;
use Twig\Environment;

final class TwigEmailEmailRenderer implements EmailRendererInterface
{

	private Environment $twig;

	public function __construct(Environment $twig)
	{
		$this->twig = $twig;
	}

	public function render(string $template, array $data): RenderedEmail
	{
		$data = $this->twig->mergeGlobals($data);
		$template = $this->twig->load($template);
		$subject = $template->renderBlock('subject', $data);
		$body = $template->renderBlock('body', $data);
		return new RenderedEmail($subject, $body);
	}
}
