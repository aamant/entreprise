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
     * @Route("invoice/{id}", name="invoice_invoice", defaults={"id"=null})
     * @Template()
     */
    public function invoiceAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id){
            $invoice = $em->getRepository('AamantInvoiceBundle:Invoice')
                ->findFullById($id);
        } else {
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
    public function viewAction($id)
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
}
