<?php

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }

    // /**
    //  * @return Entreprise[] Returns an array of Entreprise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Entreprise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function serachLaureat($code)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT `user`.prenom,`user`.nom,`user`.addresse,`user`.photo_url,laureat.cin_num_sejour,laureat.nationalite,laureat.nom_arabe,laureat.prenom_arabe,diplome.fichier,diplome.date_obtention
            FROM demande
            INNER JOIN `user`
            ON demande.laureat_id = `user`.id
            INNER JOIN laureat
            ON demande.laureat_id = laureat.id
            INNER JOIN diplome
            ON demande.diplome_id = diplome.id
            where diplome.code = :code
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['code' => $code]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
}
