<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Company;
use Doctrine\ORM\EntityRepository;

/**
 * TaxRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaxRepository extends EntityRepository
{
    public function getLast(Company $company)
    {
        try {
            $query = $this->getEntityManager()->createQuery('
              SELECT t.year, t.month FROM AppBundle:Tax t 
              WHERE t.company = :company
              ORDER BY t.id DESC
            ')
                ->setParameter('company', $company->getId());
            
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
