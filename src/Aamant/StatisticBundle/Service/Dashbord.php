<?php namespace Aamant\StatisticBundle\Service;
use Aamant\UserBundle\Entity\Company;
use Doctrine\ORM\EntityManager;

/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */
class Dashbord
{
    /**
     * @var EntityManager
     */
    private $em;
    private $max_invoiced_per_year;

    /**
     * @param EntityManager $em
     * @param $max_invoiced_per_year
     */
    public function __construct(EntityManager $em, $max_invoiced_per_year)
    {
        $this->em = $em;
        $this->max_invoiced_per_year = $max_invoiced_per_year;
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->em;
    }

    /**
     * Chifre d'affaire et encours
     *
     * @return array
     */
    public function annual(Company $company)
    {
        $paid = $this->getEm()
            ->getRepository('AamantInvoiceBundle:Payment')
            ->paymentForCurrentYear($company);
        $wait_paid = $this->getEm()
            ->getRepository('AamantInvoiceBundle:Invoice')
            ->getWaitToPaid($company);
        $quotations = $this->getEm()
            ->getRepository("AamantInvoiceBundle:Quotation")
            ->findWaitInvoice($company);
        $wait_invoiced = 0;
        foreach ($quotations as $quotation){
            $wait_invoiced += $quotation->getTotal() - $quotation->getPaid();
        }

        $data = [
            [
                'label' => 'Encaissé',
                'value' => $paid,
                'color' => '#bf616a',
                'highlight' => '#bf616a'
            ], [
                'label' => 'Attente de paiement',
                'value' => number_format($wait_paid, 2),
                'color' => '#d08770',
                'highlight' => '#d08770'
            ], [
                'label' => 'Attente de facturation',
                'value' => $wait_invoiced,
                'color' => '#ab7967',
                'highlight' => '#ab7967'
            ], [
                'label' => 'Reste',
                'value' => $this->max_invoiced_per_year - ($paid + $wait_paid + $wait_invoiced),
                'color' => '#96b5b4',
                'highlight' => '#96b5b4'
            ]
        ];

        return $data;
    }

    /**
     * Chiffre d'affaire par mois
     *
     * @param Company $company
     * @return array
     */
    public function recipeByMonths(Company $company)
    {
        $repository = $this->getEm()->getRepository("AamantInvoiceBundle:Payment");
        $tmp = $repository->paymentPerYearAndMonth($company);

        $data = [];
        foreach ($tmp as $item){
            if (!array_key_exists($item['year'], $data)){
                $data[$item['year']] = array_fill(1, 12, 0);
            }
            $data[$item['year']][$item['month']] = $item['total'];
        }

        $key = 0;
        foreach ($data as $k => $value){
            $data[$key++] = $value;
        }

        return $data;
    }

    /**
     * Chiffre d'affaire annuel par mois
     *
     * @param Company $company
     * @return array
     */
    public function recipeAnnualPerMonth(Company $company)
    {
        $repository = $this->getEm()->getRepository("AamantInvoiceBundle:Payment");
        $tmp = $repository->paymentPerYearAndMonth($company);

        $data = [];
        foreach ($tmp as $item){
            if (!array_key_exists($item['year'], $data)){
                $data[$item['year']] = array_fill(1, 12, 0);
            }
            $data[$item['year']][$item['month']] = $item['total'];
        }

        foreach ($data as $year => $item){
            $amount = 0;
            foreach ($item as $month => $value){
                $amount += $value;
                $data[$year][$month] = $amount;
            }
        }

        $key = 0;
        foreach ($data as $k => $value){
            $data[$key++] = $value;
        }

        return $data;
    }
}