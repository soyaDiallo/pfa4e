<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    // /**
    //  * @return Demande[] Returns an array of Demande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demande
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function updateDemande($id,$datee,$etat)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
         SET date_validation_de = :datee ,
         etat_directeur_gn = :etat
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'datee' => $datee->format('Y-m-d H:i:s'),'etat' => $etat]);

        return true;
    }

    public function annulerDemande($id,$etat)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
         SET etat_directeur_gn = :etat
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'etat' => $etat]);

        return true;
    }

    public function getEtab($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT etablissement_id FROM demande d
        WHERE d.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchColumn();
    }
}
