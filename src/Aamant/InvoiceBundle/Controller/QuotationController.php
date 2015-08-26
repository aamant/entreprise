<?php namespace Aamant\InvoiceBundle\Controller;

use Aamant\InvoiceBundle\Entity\Quotation;
use Aamant\InvoiceBundle\Form\QuotationType;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuotationController extends Controller
{
    /**
     * @Route("quotations", name="invoice_quotations_list")
     * @Template()
     */
    public function indexAction()
    {
        $quotations = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Quotation')
            ->findForList($this->getUser()->getCompany());

        return ['quotations' => $quotations];
    }

    /**
     * @Route("quotation/create", name="invoice_quotation")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $quotation = new Quotation();
        $quotation->setCompany($this->getUser()->getCompany());
        $quotation->setStatus(Quotation::STATUS_WAIT);
        $increment = $this->getDoctrine()
            ->getRepository('AamantInvoiceBundle:Quotation')
            ->getMaxNumber($this->getUser()->getCompany());
        $quotation->setNumber(date('Ym-').sprintf("%'.03d", ++$increment));
        $quotation->setDate(Carbon::now());
        $quotation->addItem(new Quotation\Item());

        $form = $this->createForm(new QuotationType(), $quotation);
        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($quotation);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );

            return $this->redirect($this->generateUrl('invoice_quotations_list'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("quotation/change/{id}/{status}", name="invoice_quotation_change")
     */
    public function changeAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();
        $quotation = $em->getRepository('AamantInvoiceBundle:Quotation')->find($id);
        if ($quotation->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
            $this->createAccessDeniedException();
        }

        switch ($status){
            case 'accept':
                $quotation->setStatus('accept');
                break;
            case 'cancelled':
                $quotation->setStatus('cancelled');
                break;
            case 'refused':
                $quotation->setStatus('refused');
                break;
            case 'closed':
                if ($quotation->getStatus() == Quotation::STATUS_PARTIAL_INVOICED){
                    $quotation->setStatus('invoiced');
                } elseif ($quotation->getStatus() == Quotation::STATUS_ACCEPT) {
                    $quotation->setStatus(Quotation::STATUS_CANCELLED);
                }
                break;
        }

        $em->persist($quotation);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            'Vos changements ont été sauvegardés!'
        );
        return $this->redirect($this->generateUrl('invoice_quotations_list'));
    }

    /**
     * @param $id
     * @return array
     *
     * @Route("quotation/view/{id}", name="quotation.view")
     * @Template()
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $quotation = $em->getRepository('AamantInvoiceBundle:Quotation')->find($id);
        if ($quotation->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
            $this->createAccessDeniedException();
        }

        return [
            'quotation' => $quotation
        ];
    }
}