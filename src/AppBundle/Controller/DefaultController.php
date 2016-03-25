<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
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
        /** @var \AppBundle\Service\Dashbord $statistics */
        $statistics = $this->get('statistics.dashbord');
        $doughnut = $statistics->annual($company);
        $recipeByMonth = $statistics->recipeByMonths($company);
        $recipeAnnualPerMonth = $statistics->recipeAnnualPerMonth($company);

        /** @var \AppBundle\Repository\Quotation $repository */
        $repository = $this->getDoctrine()->getRepository("AppBundle:Quotation");
        $quotations = $repository->findCurrent($company);
        $wait = 0;
        foreach ($repository->findWaitInvoice($company) as $quotation){
            $wait += $quotation->getTotal() - $quotation->getPaid();
        }

        // Attente de paiement
        $repository = $this->getDoctrine()->getRepository("AppBundle:Invoice");
        $invoices = $repository->findWaitPaid($company);
        $paid = $repository->getWaitToPaid($company);

        return $this->render('default/index.html.twig',
            compact('doughnut', 'recipeByMonth', 'recipeAnnualPerMonth', 'quotations', 'invoices', 'paid', 'wait')
        );
    }
}
