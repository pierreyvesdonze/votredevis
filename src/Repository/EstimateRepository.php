<?php

namespace App\Repository;

use App\Entity\Estimate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Estimate>
 *
 * @method Estimate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estimate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estimate[]    findAll()
 * @method Estimate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estimate::class);
    }

    public function add(Estimate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Estimate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Estimate[] Returns an array of Estimate objects
     */
    public function findByIdDesc($value): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :val')
            ->setParameter('val', $value)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
