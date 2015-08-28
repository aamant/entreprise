<?php namespace Aamant\InvoiceBundle\Controller;

use Aamant\InvoiceBundle\Entity\Invoice;
use Aamant\InvoiceBundle\Form\InvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("invoices", name="invoices_list")
     * @Template()
     */
    public function indexAction()
    {
        $invoices = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Invoice')
            ->findForList($this->getUser()->getCompany());

        return ['invoices' => $invoices];
    }

    /**
     * @Route("invoice/{type}/{id}", name="invoice.invoice.create", requirements={"type"="create|invoice|quotation","id"="\d+"}, defaults={"id"=0})
     * @Template()
     */
    public function invoiceAction(Request $request, $type, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if ($type == 'invoice' && $id) {
            $invoice = $em->getRepository('AamantInvoiceBundle:Invoice')
                ->findFullById($id);

            if ($invoice->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
                $this->createAccessDeniedException();
            }
        }
        elseif ($type == 'quotation' && $id){
            /** @var \Aamant\InvoiceBundle\Entity\QuotationRepository $quotation_rep */
            $quotation_rep = $em->getRepository('AamantInvoiceBundle:Quotation');
            /** @var \Aamant\InvoiceBundle\Entity\Quotation $quotation */
            $quotation = $quotation_rep->find($id);

            if ($quotation->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
                $this->createAccessDeniedException();
            }

            $invoice = new Invoice();
            $invoice->setCompany($this->getUser()->getCompany());
            $invoice->setCustomer($quotation->getCustomer());
            $invoice->setQuotation($quotation);

            $items_invoice = $quotation_rep->getAllInvoiceItemForTheQuote($quotation);
            foreach ($quotation->getItems() as $i){
                $invoiced = false;
                foreach ($items_invoice as $ii){
                    if ($ii->getName() == $i->getName()) $invoiced = true;
                }

                if (!$invoiced){
                    $item = new Invoice\Item();
                    $item->setName($i->getName());
                    $item->setQuantity($i->getQuantity());
                    $item->setPrice($i->getPrice());
                    $item->setTotal($i->getTotal());
                    $item->setPastTime($i->getPastTime());
                    $invoice->addItem($item);
                }
            }

            $deposit_invoice = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Invoice')->findOneBy([
                'quotation'      => $quotation,
                'deposit_invoice'   => true
            ]);
            if ($deposit_invoice){
                $invoice->setAdvance($deposit_invoice->getTotal());
            }

            $invoice->calculate();
        }
        else {
            $invoice = new Invoice();
            $invoice->setCompany($this->getUser()->getCompany());
            $invoice->addItem(new Invoice\Item());
        }

        $form = $this->createForm(new InvoiceType(), $invoice);
        $form->handleRequest($request);

        if ($form->isValid()){

            if ($form->get('create')->isClicked()){
                $config = $this->getUser()->getCompany()->getConfig();
                $increment = $config->getInvoiceIncrement();

                $invoice->create($increment);
                $config->setInvoiceIncrement(++$increment);
                $em->persist($config);
            } else {
                $invoice->createDraft();
            }

            $em->persist($invoice);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );

            return $this->redirect($this->generateUrl('invoices_list'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("invoice/advance/{id}", name="invoice.invoice.advance", requirements={"id"="\d+"})
     * @Template()
     */
    public function invoiceAdvanceAction(Request $request, $id)
    {
        /** @var \Aamant\InvoiceBundle\Entity\Quotation $quotation */
        $quotation = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Quotation')->find($id);
        if ($quotation->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
            $this->createAccessDeniedException();
        }

        if ($this->getDoctrine()->getRepository('AamantInvoiceBundle:Invoice')->findBy([
            'quotation'      => $quotation,
            'deposit_invoice'   => true
        ])){
            $this->get('session')->getFlashBag()->add(
                'warning',
                'L\'acompte est déjà facturé'
            );

            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        /** @var \Aamant\SettingsBundle\Entity\Config $config */
        $config = $this->getUser()->getCompany()->getConfig();
        $percent = floor($quotation->getTotal() * $config->getDepositInvoicePercent());

        $invoice = new Invoice();
        $invoice->setCompany($this->getUser()->getCompany());
        $invoice->setCustomer($quotation->getCustomer());
        $invoice->setQuotation($quotation);
        $invoice->setDepositInvoice(true);

        $item = new Invoice\Item();
        $item->setName(
            sprintf($config->getDepositInvoiceText(), $quotation->getNumber(), $quotation->getTotal())
        );
        $item->setQuantity(1);
        $item->setPrice($percent);
        $item->setTotal($percent);
        $invoice->addItem($item);
        $invoice->calculate();

        $increment = $config->getInvoiceIncrement();

        $invoice->create($increment);
        $config->setInvoiceIncrement(++$increment);
        $this->getDoctrine()->getEntityManager()->persist($config);
        $this->getDoctrine()->getEntityManager()->persist($invoice);
        $this->getDoctrine()->getEntityManager()->flush();


        $this->get('session')->getFlashBag()->add(
            'success',
            'Facture créée sous le numero '.$invoice->getNumber()
        );
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Route("invoice/detail/{id}", name="invoice_detail")
     * @Template()
     */
    public function detailAction($id)
    {
        return ['id' => $id];
    }

    /**
     * @Route("invoice/view/{id}", name="invoice_view")
     * @Template()
     */
    public function viewAction(Request $request, $id)
    {
        $invoice = $this->getDoctrine()
            ->getRepository('AamantInvoiceBundle:Invoice')
            ->findFullWithUserById($id);
        $user = $this->get('security.context')->getToken()->getUser();

        return [
            'invoice'   => $invoice,
            'user'      => $user,
            'company'   => $user->getCompany(),
            'customer'  => $invoice->getCustomer(),
            'base_path' => ''
        ];
    }

    /**
     * @Route("invoice/pdf/{id}", name="invoice_pdf")
     */
    public function pdfAction($id)
    {
        try {
            $invoice = $this->getDoctrine()
                ->getRepository('AamantInvoiceBundle:Invoice')
                ->findFullWithUserById($id);
            $user = $this->get('security.context')->getToken()->getUser();
            $config = $user->getCompany()->getConfig();

            $filename = $config->getInvoiceExport().'/Facture-'.$invoice->getNumber().'.pdf';

            $html = $this->render('AamantInvoiceBundle:Default:view.html.twig', [
                'invoice'   => $invoice,
                'user'      => $user,
                'company'   => $user->getCompany(),
                'customer'  => $invoice->getCustomer(),
                'base_path'      => realpath($this->get('kernel')->getRootDir() . '/../web/')
            ])->getContent();

            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView(
                    'AamantInvoiceBundle:Default:view.html.twig',
                    [
                        'invoice'   => $invoice,
                        'user'      => $user,
                        'company'   => $user->getCompany(),
                        'customer'  => $invoice->getCustomer(),
                        'base_path'      => realpath($this->get('kernel')->getRootDir() . '/../web/')
                    ]
                ),
                $filename
            );

            $this->get('session')->getFlashBag()->add(
                'success',
                'La facture est générée'
            );

            return $this->redirectToRoute('invoices_list');
        }
        catch (\Exception $e){
            $this->get('session')->getFlashBag()->add(
                'error',
                $e->getMessage()
            );

            return $this->redirectToRoute('invoices_list');
        }
    }

    /**
     * @Route("invoice/delete", name="invoice_delete")
     */
    public function deleteAction()
    {

    }

    /**
     * @param $id
     * @return array
     *
     * @Route("/invoice/payments/{id}", name="invoice.payments")
     * @Template()
     */
    public function paymentsAction($id)
    {
        $invoice = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Invoice')->find($id);
        if ($invoice->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
            $this->createAccessDeniedException();
        }
        $repository = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Payment');
        $payments = $repository->findBy(['invoice' => $invoice]);
        return [
            'invoice'   => $invoice,
            'payments'  => $payments
        ];
    }
}
