<?php

namespace SamUser\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getMainRoles()
    {
        return $querybuilder = $this->getEntityManager()->getRepository('\SamUser\Entity\User')
                ->createQueryBuilder('r')
                ->where('r.roleId' != 'guest')
                ->getQuery()
                ->getResult();
    }
    
    protected function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }    
}