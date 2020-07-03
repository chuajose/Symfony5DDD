<?php

declare( strict_types=1 );

/**
 * Created by lawyersapp.
 * User: Jose Manuel Suárez Bravo
 * Date: 3/7/20
 * Time: 12:51
 */

namespace App\UI\Http\Command;


use App\Application\Auth\Events\UserWasRegistered;
use App\Domain\Shared\EventBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class RegisterUserCommand extends Command {
	protected static $defaultName = 'app:register-user';

	private EventBus $eventBus;

	public function __construct(EventBus $eventBus, string $name = null)
	{
		$this->eventBus = $eventBus;
		parent::__construct($name);
	}

	protected function configure()
	{
		$this
			->addOption('name', 't', InputOption::VALUE_REQUIRED, '')
			->addOption('username', 'd', InputOption::VALUE_REQUIRED, '');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);

		$name = $input->getOption('name');
		$username = $input->getOption('username');



		try {
			$this->eventBus->dispatch(new UserWasRegistered('jose', 'manuel@gmail.com', (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s')));

			$io->success('Example created');
		} catch (Throwable $e) {
			$io->error($e->getMessage());
		}

		return Command::SUCCESS;
	}
}
