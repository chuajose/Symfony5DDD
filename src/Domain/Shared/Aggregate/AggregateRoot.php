<?php

declare( strict_types=1 );

namespace App\Domain\Shared\Aggregate;


abstract class AggregateRoot {

	private array $domainEvents = [];

	final public function pullDomainEvents(): array
	{
		$domainEvents       = $this->domainEvents;
		$this->domainEvents = [];
		return $domainEvents;
	}

	final public function record(\App\Infrastructure\EventDispatcher\Event $domainEvent): void
	{
		$this->domainEvents[] = $domainEvent;
	}

	final  public function getEvents(){

	    return $this->domainEvents;
}

}
