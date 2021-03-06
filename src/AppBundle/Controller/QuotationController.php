<?php namespace AppBundle\Controller;

use AppBundle\Entity\Config;
use AppBundle\Entity\Quotation;
use AppBundle\Form\QuotationType;
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
        $quotations = $this->getDoctrine()->getRepository('AppBundle:Quotation')
            ->findForList($this->getUser()->getCompany());

        return ['quotations' => $quotations];
    }

    /**
     * @Route("quotation/create", name="invoice_quotation")
     */
    public function createAction(Request $request)
    {
        $quotation = new Quotation();
        $quotation->setCompany($this->getUser()->getCompany());
        $quotation->addItem(new Quotation\Item());

        $form = $this->createForm(QuotationType::class, $quotation);
        $form->handleRequest($request);

        if ($form->isValid()){

            $em = $this->getDoctrine()->getManager();

            if ($form->get('draft')->isClicked()){
                $quotation->setStatus(Quotation::STATUS_DRAFT);
            } else {
                $quotation->setStatus(Quotation::STATUS_WAIT);
                /** @var Config $config */
                $config = $this->getUser()->getCompany()->getConfig();
                $increment = $config->getQuotationIncrement();

                $quotation->create($increment);
                $config->setQuotationIncrement(++$increment);
                $em->persist($config);
            }

            $em->persist($quotation);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );

            if ($form->get('draft')->isClicked()){
                return $this->redirect($this->generateUrl('quotation.edit', ['quotation' => $quotation->getId()]));
            }

            return $this->redirect($this->generateUrl('quotation.view', ['id' => $quotation->getId()]));
        }

        return $this->render('AppBundle:Quotation:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("quotation/edit/{quotation}", name="quotation.edit")
     */
    public function editAction(Request $request, Quotation $quotation)
    {
        $form = $this->createForm(QuotationType::class, $quotation);
        $form->handleRequest($request);

        if ($form->isValid()){

            $em = $this->getDoctrine()->getManager();

            if ($form->get('draft')->isClicked()){
                $quotation->setStatus(Quotation::STATUS_DRAFT);
                $quotation->setNumber('');
            } else {
                $quotation->setStatus(Quotation::STATUS_WAIT);
                /** @var Config $config */
                $config = $this->getUser()->getCompany()->getConfig();
                $increment = $config->getQuotationIncrement();

                $quotation->create($increment);
                $config->setQuotationIncrement(++$increment);
                $em->persist($config);
            }

            $em->persist($quotation);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );

            if ($form->get('draft')->isClicked()){
                return $this->redirect($this->generateUrl('quotation.edit', ['quotation' => $quotation->getId()]));
            }
            return $this->redirect($this->generateUrl('quotation.view', ['id' => $quotation->getId()]));
        }

        return $this->render('AppBundle:Quotation:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("quotation/change/{id}/{status}", name="invoice_quotation_change")
     */
    public function changeAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();
        $quotation = $em->getRepository('AppBundle:Quotation')->find($id);
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
     * @Route("quotation/duplicate/{id}", name="quotation.duplicate")
     * @Template()
     */
    public function duplicateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Quotation $quotation */
        $quotation = $em->getRepository('AppBundle:Quotation')->find($id);
        /** @var Quotation $new */
        $new = clone $quotation;

        foreach ($quotation->getItems() as $item){
            $new->addItem(clone $item);
        }

        $em->persist($new);
        $em->flush();

        return $this->redirectToRoute('quotation.edit', ['quotation' => $new->getId()]);
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
        $quotation = $em->getRepository('AppBundle:Quotation')->find($id);
        if ($quotation->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
            $this->createAccessDeniedException();
        }

        return [
            'quotation' => $quotation
        ];
    }

    /**
     * @Route("quotation/pdf/{id}", name="quotation_pdf")
     */
    public function pdfAction($id)
    {
        try {
            $quotation = $this->getDoctrine()
                ->getRepository('AppBundle:Quotation')
                ->find($id);
            $user = $this->getUser();
            $config = $user->getCompany()->getConfig();

            $filename = $config->getQuotationExport().'/Devis-'.$quotation->getNumber().'.pdf';

            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView(
                    'AppBundle:Quotation:pdf.html.twig',
                    [
                        'quotation' => $quotation,
                        'user'      => $user,
                        'company'   => $user->getCompany(),
                        'customer'  => $quotation->getCustomer(),
                        'base_path' => realpath($this->get('kernel')->getRootDir() . '/../web/')
                    ]
                ),
                $filename
            );

            $this->get('session')->getFlashBag()->add(
                'success',
                'Le pdf est générée'
            );

            return $this->redirectToRoute('quotation.view', ['id' => $quotation->getId()]);
        }
        catch (\Exception $e){
            $this->get('session')->getFlashBag()->add(
                'error',
                $e->getMessage()
            );

            if (isset($quotation)){
                return $this->redirectToRoute('quotation.view', ['id' => $quotation->getId()]);
            }
            
            return $this->redirectToRoute('invoices_list');
        }
    }
}