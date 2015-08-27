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
        }
        elseif ($type == 'quotation' && $id){
            /** @var \Aamant\InvoiceBundle\Entity\QuotationRepository $quotation_rep */
            $quotation_rep = $em->getRepository('AamantInvoiceBundle:Quotation');
            /** @var \Aamant\InvoiceBundle\Entity\Quotation $quotation */
            $quotation = $quotation_rep->find($id);

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
                    $invoice->addItem($item);
                }
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
