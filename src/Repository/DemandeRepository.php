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

    public function annulerDemande($id,$etat,$datee)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
        SET etat_directeur_gn = :etat,date_validation_de = :datee
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'etat' => $etat,'datee' => $datee->format('Y-m-d H:i:s')]);

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

    public function getSecDemandes($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
       SELECT demande.id,date_validation_secretaire,date_validation_dp,date_validation_de,etat_secretaire,etat_directeur_pd,etat_directeur_gn,diplome.fichier
       FROM demande
       INNER JOIN etablissement
       ON demande.etablissement_id = etablissement.id
       INNER JOIN secretaire
       ON etablissement.id = secretaire.etablissement_id
       INNER JOIN diplome
       ON demande.diplome_id = diplome.id
       where secretaire.etablissement_id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function validateDemandeBySec($id,$datee,$etat,$sec)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
        SET date_validation_secretaire = :datee,
        etat_secretaire = :etat,secretaire_id = :sec
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'datee' => $datee->format('Y-m-d H:i:s'),'etat' => $etat, 'sec' => $sec]);

        return true;
    }

    public function annulerDemandeBySec($id,$etat,$datee,$sec)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
        SET etat_secretaire = :etat,secretaire_id = :sec,date_validation_secretaire = :datee
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'etat' => $etat,'datee' => $datee->format('Y-m-d H:i:s'),'sec' => $sec]);

        return true;
    }

    public function getDirDemandes($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
       SELECT demande.id,date_validation_secretaire,date_validation_dp,date_validation_de,etat_secretaire,etat_directeur_pd,etat_directeur_gn,diplome.fichier
       FROM demande 
       INNER JOIN etablissement 
       ON demande.etablissement_id = etablissement.id 
       INNER JOIN directeur_pedagogique
       ON etablissement.id = directeur_pedagogique.etablissement_id
       INNER JOIN diplome
       ON demande.diplome_id = diplome.id 
       where directeur_pedagogique.etablissement_id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function validateDemandeByDir($id,$datee,$etat,$dir)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
        SET date_validation_dp = :datee,
        etat_directeur_pd = :etat,
            directeur_pedagogique_id = :dir
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'datee' => $datee->format('Y-m-d H:i:s'),'etat' => $etat,'dir' => $dir]);

        return true;
    }

    public function annulerDemandeByDir($id,$etat,$datee,$dir)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        Update demande
        SET etat_directeur_pd = :etat,date_validation_dp = :datee,directeur_pedagogique_id = :dir
        WHERE id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'etat' => $etat,'datee' => $datee->format('Y-m-d H:i:s'),'dir'=>$dir]);

        return true;
    }

    public function getDiplomesEtab($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT demande.id,date_validation_secretaire,date_validation_dp,date_validation_de,etat_secretaire,etat_directeur_pd,etat_directeur_gn,diplome.fichier
       FROM demande 
       INNER JOIN etablissement 
       ON demande.etablissement_id = etablissement.id 
       INNER JOIN diplome
       ON demande.diplome_id = diplome.id 
       where etablissement.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetchAll();;
    }

}
