<?php

namespace App\Repository;

use App\Entity\Games;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Games>
 *
 * @method Games|null find($id, $lockMode = null, $lockVersion = null)
 * @method Games|null findOneBy(array $criteria, array $orderBy = null)
 * @method Games[]    findAll()
 * @method Games[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Games::class);
    }

    public function save(Games $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Games $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllGamesOrderByName(string $platform): array{
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT g
            FROM App\Entity\Games g
            WHERE g.platform = :p
            ORDER BY g.name ASC'
        )->setParameter('p', $platform);

        return $query->getResult();
    }

    public function findAllGamesStartedByCharOrderByName(string $char, string $platform): array{
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT g
            FROM App\Entity\Games g
            WHERE g.platform = :p AND g.name LIKE :c
            ORDER BY g.name ASC'
        )->setParameter('p', $platform)
        ->setParameter('c', $char.'%');

        return $query->getResult();
    }

//    /**
//     * @return Games[] Returns an array of Games objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Games
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
