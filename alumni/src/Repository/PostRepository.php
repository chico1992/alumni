<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;
use App\Entity\VisibilityGroup;
use Doctrine\Common\Collections\Collection;
use App\DTO\PostSearch;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of the 10 next oldest posts 
     */
    public function findByDate(PostSearch $dto)
    {
        $queryBuilder =$this->createQueryBuilder('p')
            ->andWhere('p.creationDate < :date')
            ->setParameter('date', $dto->creationDate)
            ->andWhere('p.status = :status')
            ->setParameter('status', $dto->status);
        if(!empty($dto->user)){
            $queryBuilder->andWhere(
                'p.author = :author'
            );
            $queryBuilder->setParameter('author',$dto->user);
        }
        if(!empty($dto->groups)){
            $queryBuilder->andWhere(
                'p.visibility in (:visibility)'
            );
            $queryBuilder->setParameter('visibility',$dto->groups);
        }
        
        $queryBuilder->orderBy('p.creationDate', 'DESC')
            ->setMaxResults(10);
            
        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
