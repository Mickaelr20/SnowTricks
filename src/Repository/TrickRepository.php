<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Trick::class);
	}

	public function add(Trick $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(Trick $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	/**
	 * @return Trick Returns a trick with the id in param or null
	 */
	public function get($id): Trick|null
	{
		return $this->createQueryBuilder('tricks')
			->andWhere('tricks.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getOneOrNullResult();
	}

	public function listPage($page, $limit = 12): array
	{
		$offset = $limit * $page;

		return $this->createQueryBuilder('tricks')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}
}
