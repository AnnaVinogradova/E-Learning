<?php

namespace ELearning\CompanyPortalBundle\Repository;

/**
 * UserHomeworkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserHomeworkRepository extends \Doctrine\ORM\EntityRepository
{
    public function getActiveHomeworks($ids)
    {
        $qb = $this->createQueryBuilder('uh');
        $qb->where('uh.checked = 0')
            ->andWhere('uh.homework IN (:ids)')
            ->setParameter('ids', $ids);

        return $qb->getQuery()
            ->getResult();
    }
}
