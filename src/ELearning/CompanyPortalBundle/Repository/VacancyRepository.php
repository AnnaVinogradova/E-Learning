<?php

namespace ELearning\CompanyPortalBundle\Repository;

/**
 * VacancyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VacancyRepository extends \Doctrine\ORM\EntityRepository
{
    public function getVacanciesByCategory($category)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.categories', 'c')
            ->where($qb->expr()->eq('c.id', $category->getId()));
        return $qb->getQuery()->getResult();
    }

    public function getVacancyRecommendation($company)
    {
        $qb = $this->createQueryBuilder('v');
        $qb->where('v.company = '. $company->getId())
            ->setMaxResults(3)
            ->add('orderBy','v.id DESC');

        return $qb->getQuery()
            ->getResult();
    }
}
