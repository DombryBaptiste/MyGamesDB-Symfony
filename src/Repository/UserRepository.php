<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function changePseudo($pseudo, $userid){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "UPDATE user SET pseudo = '".$pseudo."' WHERE id = ".$userid;
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
    }

    public function changeEmail($email, $userid){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "UPDATE user SET email = '".$email."' WHERE id = ".$userid;
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
    }

    /**
     * @throws Exception
     */
    public function deleteUser($iduser){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "DELETE FROM user_data WHERE id_user = ".$iduser;
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();

        $sql = "DELETE FROM user WHERE id = ".$iduser;
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
    }

    public function changePass($iduser, $pass){
        $conn = $this->getEntityManager()->getConnection();
        $shapass = sha1($pass);
        $sql = "UPDATE user SET password = '".$shapass."' WHERE id = ".$iduser;
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
    }

//    /**
//     * @return User[] Returns an array of User objects
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

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
