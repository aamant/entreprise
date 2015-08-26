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
        /** @var \Aamant\StatisticBundle\Service\Statistic $statistics */
        $statistics = $this->get('statistics.dashbord');
        $doughnut = $statistics->annual($company);
        $recipeByMonth = $statistics->recipeByMonths($company);
        $recipeAnnualPerMonth = $statistics->recipeAnnualPerMonth($company);

        // Attente de facturation
        $repository = $this->getDoctrine()->getRepository("AamantInvoiceBundle:Quotation");
        $quotations = $repository->findWaitInvoice($company);
        $wait = 0;
        foreach ($quotations as $quotation){
            $wait += $quotation->getTotal() - $quotation->getPaid();
        }

        // Attente de paiement
        $repository = $this->getDoctrine()->getRepository("AamantInvoiceBundle:Invoice");
        $invoices = $repository->findWaitPaid($company);
        $paid = $repository->getWaitToPaid($company);

        return $this->render('default/index.html.twig',
            compact('doughnut', 'recipeByMonth', 'recipeAnnualPerMonth', 'quotations', 'invoices', 'paid', 'wait')
        );
    }
}
