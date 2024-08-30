<?php

namespace App\Repository;

use App\Entity\Developer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Developer>
 */
class DeveloperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Developer::class);
    }

        /**
     * Получает средний возраст всех разработчиков.
     *
     * @return float Средний возраст.
     */
    public function findAverageAge(): float
    {
        return $this->createQueryBuilder('d')
            ->select('AVG(d.age) as avg_age')
            ->getQuery()
            ->getSingleScalarResult();
    }

     /**
     * Возвращает количество разработчиков по каждой должности.
     *
     * @return array Массив с должностями и количеством разработчиков.
     */
    public function getDeveloperCountByPosition()
    {
        $qb = $this->createQueryBuilder('d')
            ->select('d.Position, COUNT(d.id) as developer_count')
            ->groupBy('d.Position');

        return $qb->getQuery()->getResult();
    }
      /**
     * Возвращает количество разработчиков по полу.
     *
     * @return array Массив с полами и количеством разработчиков.
     */
    public function getDevelopersByGender(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.gender as gender, COUNT(d.id) as developer_count')
            ->groupBy('d.gender')
            ->getQuery()
            ->getResult();
    }
}
