<?php namespace Aamant\InvoiceBundle\Controller;

use Aamant\InvoiceBundle\Entity\Payment;
use Aamant\InvoiceBundle\Form\PaymentType;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * @Route("payment/list", name="invoice_payment_list")
     * @Template
     */
    public function listAction()
    {
        $payments = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Payment')
            ->findForList($this->getUser()->getCompany());

        return ['payments' => $payments];
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Route("payment/create", name="invoice_payment_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $payment = new Payment();
        $payment->setCompany($this->getUser()->getCompany());
        $payment->setDate(Carbon::now());

        $form = $this->createForm(new PaymentType(), $payment);
        $form->handleRequest($request);

        if ($form->isValid()){
            $payment->setCustomer($payment->getInvoice()->getCustomer());

            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Paiement enregistré'
            );

            return $this->redirect($this->generateUrl('invoice_payment_list'));
        }

        return ['form' => $form->createView()];
    }
}