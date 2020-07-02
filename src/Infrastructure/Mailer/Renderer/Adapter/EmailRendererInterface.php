<?php

declare( strict_types=1 );

namespace App\Infrastructure\Mailer\Renderer\Adapter;

use App\Infrastructure\Mailer\Renderer\RenderedEmail;

interface EmailRendererInterface
{
	public function render(string $template, array $data): RenderedEmail;
}
