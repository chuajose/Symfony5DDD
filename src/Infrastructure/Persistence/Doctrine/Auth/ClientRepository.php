<?php

declare( strict_types=1 );

namespace App\Infrastructure\Persistence\Doctrine\Auth;

use App\Domain\Auth\Model\Client;
use App\Domain\Auth\Repository\ClientRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ClientRepository implements ClientRepositoryInterface
{
	private const ENTITY = Client::class;
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;
	/**
	 * @var ObjectRepository
	 */
	private $objectRepository;
	/**
	 * UserRepository constructor.
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(
		EntityManagerInterface $entityManager
	) {
		$this->entityManager = $entityManager;
		$this->objectRepository = $this->entityManager->getRepository(self::ENTITY);
	}
	/**
	 * @param string $clientId
	 * @return Client|null
	 */
	public function findActive(string $clientId): ?Client
	{
		return $this->objectRepository->findOneBy(['id' => $clientId, 'active' => 1]);
	}
}
