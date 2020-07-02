<?php
/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 30/01/19
 * Time: 10:06
 */

namespace App\Infrastructure\Persistence\Doctrine\Auth;

use App\Domain\Auth\Model\RefreshToken;
use App\Domain\Auth\Repository\RefreshTokenRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
	private const ENTITY = RefreshToken::class;
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
	public function find(string $accessTokenId): ?RefreshToken
	{
		return $this->entityManager->find(self::ENTITY, $accessTokenId);
	}
	public function save(RefreshToken $accessToken): void
	{
		$this->entityManager->persist($accessToken);
		$this->entityManager->flush();
	}
}
