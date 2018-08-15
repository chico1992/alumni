<?php

namespace App\Repository;

use App\DTO\UserSearch;
use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{

    public function findByUserSearch(UserSearch $dto)
    {
        $queryBuilder = $this->createQueryBuilder('us');
    
        if(!empty($dto->search)) {
            $queryBuilder
                ->andWhere('us.username like :search')
                ->orWhere('us.firstname like :search')
                ->orWhere('us.lastname like :search');
            $queryBuilder->setParameter('search', '%' . $dto->search . '%');
        }
        return $queryBuilder->getQuery()->execute();
    }
}


