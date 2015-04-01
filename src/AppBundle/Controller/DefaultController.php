<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository("AamantInvoiceBundle:Quotation");
        $quotations = $repository->findWaitInvoice($this->getUser()->getCompany());
        $wait = $repository->getWaitToInvoice($this->getUser()->getCompany());

        $repository = $this->getDoctrine()->getRepository("AamantInvoiceBundle:Invoice");
        $invoices = $repository->findWaitPaid($this->getUser()->getCompany());
        $paid = $repository->getWaitToPaid($this->getUser()->getCompany());
        return $this->render('default/index.html.twig',
            ['quotations' => $quotations, 'invoices' => $invoices, 'paid' => $paid, 'wait' => $wait]
        );
    }
}
