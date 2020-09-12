<?php

namespace App\Repository;

use App\Entity\Secretaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Secretaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Secretaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Secretaire[]    findAll()
 * @method Secretaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecretaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Secretaire::class);
    }

    // /**
    //  * @return Secretaire[] Returns an array of Secretaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Secretaire
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



    public function getIdEtab($id): string
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
       SELECT etablissement_id
       FROM secretaire
       where secretaire.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchColumn();
    }
    

}
