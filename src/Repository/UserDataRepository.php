<?php

namespace App\Repository;

use App\Entity\UserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserData>
 *
 * @method UserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserData[]    findAll()
 * @method UserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserData::class);
    }

    public function save(UserData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function removeGame($id_user, $id_game){
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE FROM user_data'.$id_user.' WHERE id_game = '.$id_game;
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        //return $result->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function addGame($id_user, $id_game){
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT INTO user_data'.$id_user.'(id_game) VALUES ('.$id_game.')';
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        //return $result->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function gameIsPossessed($id_user, $id_game): bool
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM user_data WHERE id_game = '.$id_game.' AND id_user = '.$id_user;
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery();
            return $result->rowCount() == 1;

    }

//    /**
//     * @return UserData[] Returns an array of UserData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserData
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
