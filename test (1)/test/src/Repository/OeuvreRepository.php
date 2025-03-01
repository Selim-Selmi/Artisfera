<?php

namespace App\Repository;

use App\Entity\Oeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Oeuvre>
 */
class OeuvreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvre::class);
    }

    public function findBySearchQuery(string $query): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->where($queryBuilder->expr()->like('o.nom', ':query'))
            ->orWhere($queryBuilder->expr()->like('o.description', ':query'))
            ->orWhere($queryBuilder->expr()->like('o.type', ':query'))
            ->orWhere($queryBuilder->expr()->like('o.matiere', ':query'))
            ->orWhere($queryBuilder->expr()->like('o.couleur', ':query'))
            // ->orWhere($queryBuilder->expr()->like('o.createur', ':query'))
            ->orWhere($queryBuilder->expr()->like('o.categorie', ':query'))
            ->setParameter('query', '%' . $query . '%');
        
        return $queryBuilder->getQuery()->getResult();
        
    }
    

    //    /**
    //     * @return Oeuvre[] Returns an array of Oeuvre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Oeuvre
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
