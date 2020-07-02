<?php
/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 29/01/19
 * Time: 17:00
 */

namespace App\Infrastructure\Persistence\Doctrine\Auth;


use App\Domain\Auth\Model\User;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class AuthRepository implements AuthRepositoryInterface
{
	private const ENTITY = User::class;
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
	public function find(UuidInterface $id): ?User
	{
		return $this->entityManager->find(self::ENTITY, $id->toString());
	}

	public function findAll(): ?array
	{
		return $this->objectRepository->findBy(['active' => 1]);
	}
	public function findOneByEmail(string $email): ?User
	{
		return $this->objectRepository->findOneBy(['email' => $email]);
	}

	public function findOneByUsername(string $username): ?User
	{
		return $this->objectRepository->findOneBy(['username' => $username]);
	}

	public function findOneByResetToken(string $token): ?User
	{
		return $this->objectRepository->findOneBy(['reset_token' => $token]);
	}
	public function save(User $user): void
	{
		$user->setUpdatedAt(new \DateTimeImmutable('now'));
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}
	public function remove(User $user): void
	{
		$this->entityManager->remove($user);
		$this->entityManager->flush();
	}
	public function findRandom(): ?User {
		$sql = "SELECT u.* FROM user as u ORDER BY RAND() LIMIT 1";

		$rsm = new ResultSetMapping();
		$rsm->addEntityResult(self::ENTITY, 'u');
		$rsm->addFieldResult('u', 'id', 'id');
		$rsm->addFieldResult('u', 'name', 'name');
		$rsm->addFieldResult('u', 'email', 'email');
		$rsm->addFieldResult('u', 'password', 'password');
		$rsm->addFieldResult('u', 'username', 'username');
		$rsm->addFieldResult('u', 'active', 'active');
		$rsm->addFieldResult('u', 'created_at', 'created_at');
		$rsm->addFieldResult('u', 'updated_at', 'updated_at');
		//$rsm->addFieldResult('j', 'image', 'image');

		$query = $this->entityManager->createNativeQuery($sql, $rsm);

		$email = $query->getResult();
		return  $this->find(Uuid::fromString($email[0]->getId()));

	}
}
