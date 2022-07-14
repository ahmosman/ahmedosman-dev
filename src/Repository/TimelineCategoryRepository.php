<?php

namespace App\Repository;

use App\Entity\TimelineCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimelineCategory>
 *
 * @method TimelineCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimelineCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimelineCategory[]    findAll()
 * @method TimelineCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimelineCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimelineCategory::class);
    }

    public function add(TimelineCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TimelineCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
