<?php

namespace App\Repository;

use App\Entity\Diplome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Diplome|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diplome|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diplome[]    findAll()
 * @method Diplome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiplomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Diplome::class);
    }

    // /**
    //  * @return Diplome[] Returns an array of Diplome objects
    //  */
    
    public function findDiplomeByCodeField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.code = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Diplome
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    public function getNameDiplome($id): string
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT fichier FROM diplome d
        WHERE d.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchColumn();
    }

    public function getCode($id): string
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT code FROM diplome d
        WHERE d.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchColumn();
    }

    public function updateDiplome($id,$fichier,$datee)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update diplome
         SET date_obtention = :datee,
             fichier = :fichier
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'fichier' => $fichier,'datee' => $datee->format('Y-m-d H:i:s')]);

        return true;
    }
}
