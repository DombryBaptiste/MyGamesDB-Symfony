<?php

namespace App\Repository;

use App\Entity\Games;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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

    public function findAllGamesOrderByName(string $platform): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT g
            FROM App\Entity\Games g
            WHERE g.platform = :p
            ORDER BY g.name ASC'
        )->setParameter('p', $platform);

        return $query->getResult();
    }

    /**
     * @throws Exception
     */
    public function findLastGameAdded($id_user): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM user_data INNER JOIN games ON user_data.id_game = games.id WHERE user_data.id_user ='.$id_user.' ORDER BY added DESC LIMIT 18';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function findSearch($like): array{
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM games WHERE'.$like.' ORDER BY name';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getPlatforms(): array{
        $query = $this->getEntityManager()->createQuery(
            'SELECT g.platform
            FROM App\Entity\Games g
            GROUP BY g.platform'
        );
        return $query->getResult();
    }

    public function getGamesUser($userid): array{
        $query = $this->getEntityManager()->createQuery(
            'SELECT x, g
            FROM App\Entity\UserData x
            INNER JOIN App\Entity\Games g
            ON x.id_game = g.id
            WHERE x.id_user = :id
            ORDER BY g.name'
        )->setParameter('id', $userid);
        dd($query->getResult());
        return $query->getResult();
    }

    /*public function findAllGamesStartedByCharOrderByName(string $char, string $platform): array{
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT g
            FROM App\Entity\Games g
            WHERE g.platform = :p AND g.name LIKE :c
            ORDER BY g.name ASC'
        )->setParameter('p', $platform)
        ->setParameter('c', $char.'%');

        return $query->getResult();
    }*/

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
