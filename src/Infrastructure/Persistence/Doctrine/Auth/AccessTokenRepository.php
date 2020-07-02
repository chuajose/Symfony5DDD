<?php
/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 30/01/19
 * Time: 10:06
 */

namespace App\Infrastructure\Persistence\Doctrine\Auth;

use App\Domain\Auth\Model\AccessToken;
use App\Domain\Auth\Repository\AccessTokenRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class AccessTokenRepository implements AccessTokenRepositoryInterface
{
	private const ENTITY = AccessToken::class;
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
	public function find(string $accessTokenId): ?AccessToken
	{
		return $this->entityManager->find(self::ENTITY, $accessTokenId);
	}
	public function save(AccessToken $accessToken): void
	{
		$this->entityManager->persist($accessToken);
		$this->entityManager->flush();

	}
}
