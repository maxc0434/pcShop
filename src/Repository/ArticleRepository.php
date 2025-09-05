<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /*
     


Recherche les produits dont le nom ou la description contient la chaîne passée en paramètre.
@param string $query Le mot-clé recherché par l'utilisateur
@return Product[] Retourne un tableau d'objets Product correspondant à la recherche */

            public function searchEngine(string $query){
                //  Crée un QueryBuilder pour construire dynamiquement la requête de recherche
            return $this->createQueryBuilder('p')

                // Recherche les produits dont le nom contient la requête
                ->where('p.name LIKE :query')

                // Ou dont la description contient la requête
                ->orWhere('p.caption LIKE :query')

                // Définit la valeur du paramètre "query" avec des % pour la recherche partielle
                ->setParameter('query', '%' . $query . '%')

                // Exécute la requête et retourne les résultats
                ->getQuery()
                ->getResult();
        }
}
