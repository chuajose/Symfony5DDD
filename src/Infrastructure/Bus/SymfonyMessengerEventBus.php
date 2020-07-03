<?php

declare( strict_types=1 );

/**
 * Created by lawyersapp.
 * User: Jose Manuel Suárez Bravo
 * Date: 3/7/20
 * Time: 11:23
 */

namespace App\Infrastructure\Bus;


use App\Domain\Shared\EventBus;
use App\Domain\Shared\EventMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessengerEventBus implements EventBus {

	/**
	 * @var MessageBusInterface
	 */
	private MessageBusInterface $eventBus;

	public function __construct(MessageBusInterface $eventBus)
	{
		$this->eventBus = $eventBus;
	}

	public function dispatch(EventMessage $message): void
	{
		$this->eventBus->dispatch($message);
	}
}
