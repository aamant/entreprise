<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Company;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * PaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaymentRepository extends EntityRepository
{
    public function findForList(Company $company)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT payment, customer, invoice FROM AppBundle:Payment payment
                JOIN payment.customer customer
                JOIN payment.invoice invoice
                ORDER BY payment.date DESC
            ');

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function paymentPerYear(Company $company)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT YEAR(p.date) as year, SUM(p.total) as total FROM payment p
                WHERE company_id = :company
                GROUP BY YEAR(p.date)
                ORDER BY YEAR(p.date) DESC
            ');
        $query->execute(['company' => $company->getId()]);

        try {
            return $query->fetchAll();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function paymentForCurrentYear(Company $company)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT SUM(p.total) as total FROM payment p
                WHERE company_id = :company
                AND YEAR(p.date) = YEAR(CURRENT_DATE)
            ');
        $query->execute(['company' => $company->getId()]);

        try {
            return $query->fetchColumn();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function paymentPerMonthForYear(Company $company, $year)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT MONTH(p.date) as month, SUM(p.total) as total FROM payment p
                WHERE company_id = :company
                AND YEAR(p.date) = :year
                GROUP BY MONTH(p.date)
            ');
        $query->execute(['company' => $company->getId(), 'year' => $year]);

        try {
            return $query->fetchAll();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function paymentForYearAndMonth(Company $company, $year, $month)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT SUM(p.total) as total FROM payment p
                WHERE company_id = :company
                AND YEAR(p.date) = :year AND MONTH(p.date) = :month
            ');
        $query->execute(['company' => $company->getId(), 'year' => $year, 'month' => $month]);

        try {
            return $query->fetchColumn();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function paymentPerYearAndMonth(Company $company)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT YEAR(p.date) as year, MONTH(p.date) as month, SUM(p.total) as total FROM payment p
                WHERE company_id = :company
                AND YEAR(p.date) > YEAR(CURRENT_DATE) - 2
                GROUP BY YEAR(p.date), MONTH(p.date)
            ');
        $query->execute(['company' => $company->getId()]);

        try {
            return $query->fetchAll();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function totalRecipeForCustomer(Customer $customer)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT SUM(p.total) as total FROM payment p
                WHERE customer_id = :customer
            ');
        $query->execute(['customer' => $customer->getId()]);

        try {
            return $query->fetchColumn();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function recipeForCustomerForCurrentYear(Customer $customer)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT SUM(p.total) as total FROM payment p
                WHERE customer_id = :customer
                AND YEAR(p.date) = YEAR(CURRENT_DATE)
            ');
        $query->execute(['customer' => $customer->getId()]);

        try {
            return $query->fetchColumn();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function recipeForCustomerForLastYear(Customer $customer)
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
                SELECT SUM(p.total) as total FROM payment p
                WHERE customer_id = :customer
                AND YEAR(p.date) = YEAR(CURRENT_DATE) - 1
            ');
        $query->execute(['customer' => $customer->getId()]);

        try {
            return $query->fetchColumn();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function getFirst(Company $company)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT p.date FROM AppBundle:Payment p
            WHERE p.company = :company
            ORDER BY p.date ASC
        ')
            ->setMaxResults(1)
            ->setParameter('company', $company->getId());

        try {
            return $query->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
