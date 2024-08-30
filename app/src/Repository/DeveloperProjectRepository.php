<?php

namespace App\Repository;

use App\Entity\DeveloperProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeveloperProject>
 */
class DeveloperProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeveloperProject::class);
    }
    /**
     * Возвращает количество проектов на каждого разработчика.
     *
     * @return array Массив с идентификаторами разработчиков и количеством проектов.
     */
    public function getProjectsPerDeveloper(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT d.id AS developer_id, d.full_name, COUNT(dp.project_id) AS project_count
            FROM App\Entity\Developer d
            LEFT JOIN App\Entity\DeveloperProject dp WITH d.id = dp.developer_id
            GROUP BY d.id'
        );

        return $query->getResult();
    }
 /**
     * Возвращает количество разработчиков, работающих над проектами.
     *
     * @return int Количество уникальных разработчиков.
     */
    public function getDevelopersInProjects(): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(DISTINCT dp.developer_id) as developers_in_projects
            FROM App\Entity\DeveloperProject dp'
        );

        return $query->getSingleScalarResult();
    }

}
