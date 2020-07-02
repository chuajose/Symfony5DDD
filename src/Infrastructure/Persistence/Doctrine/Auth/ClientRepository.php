<?php
/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 30/01/19
 * Time: 10:06
 */

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
	private $entityManager;
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
