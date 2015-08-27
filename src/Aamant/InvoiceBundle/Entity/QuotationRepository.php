<?php

namespace Aamant\InvoiceBundle\Entity;

use Aamant\CustomerBundle\Entity\Customer;
use Aamant\UserBundle\Entity\Company;
use Doctrine\ORM\EntityRepository;

/**
 * QuotationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuotationRepository extends EntityRepository
{
    public function findForList(Company $company)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT q, c, i FROM AamantInvoiceBundle:Quotation q
                JOIN q.customer c
                LEFT JOIN q.invoices i
                WHERE q.company = :company
                ORDER BY q.date DESC
            ")->setParameter('company', $company);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findWaitInvoice(Company $company)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT q, c, i FROM AamantInvoiceBundle:Quotation q
                JOIN q.customer c
                LEFT JOIN q.invoices i
                WHERE q.company = :company
                AND q.status IN ('accept', 'partial_invoiced')
                ORDER BY q.date DESC
            ")->setParameter('company', $company);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @param Company $company
     * @return array|null
     */
    public function findWaitInvoiceValue(Company $company)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT q.total - SUM(i.total) FROM AamantInvoiceBundle:Quotation q
                LEFT JOIN q.invoices i
                WHERE q.company = :company
                AND q.status IN ('accept', 'partial_invoiced')
                GROUP BY q.company
            ")->setParameter('company', $company);

        try {
            return $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * @param Company $company
     * @return int
     */
    public function getMaxNumber(Company $company)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT MAX(q.number) AS number FROM AamantInvoiceBundle:Quotation q
                WHERE q.company = :company
            ')->setParameter('company', $company);

        try {
            $result = $query->getSingleScalarResult();
            if (!$result) return 0;
            list($before, $increment) = explode('-', $result);
            return $increment;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

    public function findWithCustomerById($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT q, c FROM AamantInvoiceBundle:Quotation q
                JOIN q.customer c
                WHERE q.id = :id'
            )->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAllForCustomer(Customer $customer)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT q FROM AamantInvoiceBundle:Quotation q
                WHERE q.customer = :customer'
            )->setParameter('customer', $customer);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return [];
        }
    }

    /**
     * @param Quotation $quotation
     * @return array
     */
    public function getAllInvoiceItemForTheQuote(Quotation $quotation)
    {
        try {
            $query = $this->getEntityManager()
                ->createQuery('
                SELECT q, i, it FROM AamantInvoiceBundle:Quotation q
                JOIN q.invoices i
                JOIN i.items it
                WHERE q.id = :quotation
            ')->setParameter('quotation', $quotation->getId());

            return $query->getResult();
        }
        catch (\Doctrine\ORM\NoResultException $e){
            return [];
        }
    }
}
