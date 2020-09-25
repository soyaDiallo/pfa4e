<?php

namespace App\Repository;

use App\Entity\Laureat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Laureat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Laureat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Laureat[]    findAll()
 * @method Laureat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaureatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Laureat::class);
    }

    // /**
    //  * @return Laureat[] Returns an array of Laureat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Laureat
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getcin($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT cin_num_sejour FROM laureat l
        WHERE l.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchColumn();
    }

    public function getDiplomesAuth($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
       SELECT diplome.fichier,
              diplome.date_obtention,
              diplome.date_depot,
              diplome.code,
              demande.etat_secretaire,
              demande.etat_directeur_pd,
              demande.etat_directeur_gn
       FROM diplome
       INNER JOIN demande
       ON diplome.id = demande.diplome_id
       INNER JOIN laureat
       ON demande.laureat_id = laureat.id
       where laureat.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
}
