<?php

namespace AppBundle\Controller;

use Carbon\Carbon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $company = $this->getUser()->getCompany();
        $repository = $this->getDoctrine()->getRepository("AamantInvoiceBundle:Quotation");
        $quotations = $repository->findWaitInvoice($company);
        $wait = 0;
        foreach ($quotations as $quotation){
            $wait += $quotation->getTotal() - $quotation->getPaid();
        }

        $repository = $this->getDoctrine()->getRepository("AamantInvoiceBundle:Invoice");
        $invoices = $repository->findWaitPaid($company);
        $paid = $repository->getWaitToPaid($company);

        $repository = $this->getDoctrine()->getRepository("AamantInvoiceBundle:Payment");
        $recettes = $repository->paymentPerYear($company);

        $tmp = $repository->paymentPerMonthForYear($company, date('Y'));
        $one_year = array_fill(1, 12, ['total' => 0, 'cumule' => 0]);
        $total = 0;
        foreach ($tmp as $value){
            $one_year[$value['month']]['total'] = $value['total'];
            $total += $value['total'];
            $one_year[$value['month']]['cumule'] = $total;
        }

        $tmp = $repository->paymentPerMonthForYear($company, Carbon::now()->subYear()->format('Y'));
        $two_year = array_fill(1, 12, ['total' => 0, 'cumule' => 0]);
        $total = 0;
        foreach ($tmp as $value){
            $two_year[$value['month']]['total'] = $value['total'];
            $total += $value['total'];
            $two_year[$value['month']]['cumule'] = $total;
        }

        $tmp = $repository->paymentPerMonthForYear($company, Carbon::now()->subYears(2)->format('Y'));
        $three_year = array_fill(1, 12, ['total' => 0, 'cumule' => 0]);
        $total = 0;
        foreach ($tmp as $value){
            $three_year[$value['month']]['total'] = $value['total'];
            $total += $value['total'];
            $three_year[$value['month']]['cumule'] = $total;
        }

        $tmp = $repository->paymentPerYearAndMonth($company);
        $stats = array_fill(1, 12, ['2015' => 0, '2014' => 0, '2013' => 0]);
        $cumul = array_fill(1, 12, ['2015' => 0, '2014' => 0, '2013' => 0]);
        $total = ['2015' => 0, '2014' => 0, '2013' => 0];
        foreach ($tmp as $value){
            $total[$value['year']] += $value['total'];
            $cumul[$value['month']][$value['year']] = $total[$value['year']];
            $stats[$value['month']][$value['year']] = $value['total'];
        }

        $month = [
            1 => "Jan", 2 => "Fev", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin", 7 => "Juillet", 8 => "Aout",
            9 => "Sept", 10 => "Oct", 11 => "Nov", 12 => "Dec"
        ];

        return $this->render('default/index.html.twig',
            compact('quotations', 'invoices', 'paid', 'wait', 'recettes', 'one_year', 'two_year',
                'three_year', 'stats', "cumul", "month")
        );
    }
}
