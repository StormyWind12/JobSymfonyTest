<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }
    
  /**
   * 
     * Возвращает количество уникальных клиентов.
     *
     * @return int Количество уникальных клиентов.
     */
    public function getUniqueClients(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(DISTINCT p.client) as unique_clients')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
