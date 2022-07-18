<?php

namespace App\Repository;

use App\Entity\ProjectSlide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectSlide>
 *
 * @method ProjectSlide|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectSlide|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectSlide[]    findAll()
 * @method ProjectSlide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectSlideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectSlide::class);
    }

    public function add(ProjectSlide $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProjectSlide $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
