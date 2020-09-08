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
    public function findDemandeDirecteur($valueEtab, $valueValid)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.etablissement = :val')
            ->andWhere('d.etatSecretaire = :val2')
            ->setParameters(array('val' => $valueEtab, 'val2' => $valueValid))
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findDemandeEtablissment($valueEtab, $valueValid)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.etablissement = :val')
            ->andWhere('d.etatSecretaire = :val2')
            ->andWhere('d.etatDirecteurPd = :val3')
            ->setParameters(array('val' => $valueEtab, 'val2' => $valueValid, 'val3' => $valueValid))
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function updateDemande($id,$datee,$etat)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
        SET date_validation_de = :datee,
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
    
    public function getIdDiplome($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT diplome_id FROM demande d
        WHERE d.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchColumn();
    }

    public function getIdLaureat($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT laureat_id FROM demande d
        WHERE d.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchColumn();
    }

}
