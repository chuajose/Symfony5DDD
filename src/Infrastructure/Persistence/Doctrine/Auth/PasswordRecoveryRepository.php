<?php

declare( strict_types=1 );

namespace App\Infrastructure\Persistence\Doctrine\Auth;

use App\Domain\Auth\Model\PasswordRecovery;
use App\Domain\Auth\Model\User;
use App\Domain\Auth\Repository\PasswordRecoveryRepositoryInterface;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class PasswordRecoveryRepository implements PasswordRecoveryRepositoryInterface
{
	private const ENTITY = PasswordRecovery::class;
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
	public function findOneByToken( string $token ): ?PasswordRecovery {
		return $this->objectRepository->findOneBy(['token' => $token]);
	}

	public function findOneByUser( User $user ): ?PasswordRecovery {
		return $this->objectRepository->findOneBy(['user' => $user]);

	}

	public function save(PasswordRecovery $passwordRecovery): void
	{
		$this->entityManager->persist($passwordRecovery);
		$this->entityManager->flush();
	}
	public function remove(PasswordRecovery $passwordRecovery): void
	{
		$this->entityManager->remove($passwordRecovery);
		$this->entityManager->flush();
	}
}
