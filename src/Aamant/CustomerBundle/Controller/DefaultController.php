<?php

namespace Aamant\CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aamant\CustomerBundle\Form\CustomerType;
use Aamant\CustomerBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/list", name="customers_list")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $customers = $user->getCompany()->getCustomers();

        return ['customers' => $customers];
    }

    /**
     * @Route("/customer/view/{id}", name="customer.view")
     * @Template()
     *
     * @param $id
     * @return array
     */
    public function viewAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var \Aamant\CustomerBundle\Entity\Customer $customer */
        $customer = $em->getRepository('AamantCustomerBundle:Customer')
            ->find($id);

        if ($customer->getCompany()->getId() != $this->getUser()->getCompany()->getId()){
            $this->createAccessDeniedException();
        }

        /** @var \Aamant\InvoiceBundle\Entity\QuotationRepository $quotations_repository */
        $quotations_repository = $em->getRepository('AamantInvoiceBundle:Quotation');
        $quotations = $quotations_repository->findAllForCustomer($customer);

        /** @var \Aamant\InvoiceBundle\Entity\InvoiceRepository $invoice_repository */
        $invoice_repository = $em->getRepository('AamantInvoiceBundle:Invoice');
        $invoices = $invoice_repository->findAllByCustomer($customer);

        /** @var \Aamant\InvoiceBundle\Entity\PaymentRepository $payment_repository */
        $payment_repository = $em->getRepository('AamantInvoiceBundle:Payment');
        $total_recipe = $payment_repository->totalRecipeForCustomer($customer);
        $recipe_current_year = $payment_repository->recipeForCustomerForCurrentYear($customer);
        $recipe_last_year = $payment_repository->recipeForCustomerForLastYear($customer);

        return [
            'customer'      => $customer,
            'quotations'    => $quotations,
            'invoices'      => $invoices,
            'total_recipe'  => $total_recipe,
            'recipe_current_year'   => $recipe_current_year,
            'recipe_last_year'      => $recipe_last_year
        ];
    }

    /**
     * @Route("/customer/edit/{id}", name="customer_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('AamantCustomerBundle:Customer')
            ->find($id);

        if (!$customer) {
            throw $this->createNotFoundException(
                'Aucun produit client pour cet id : '.$id
            );
        }

        $form = $this->createForm(new CustomerType(), $customer);
        $form->handleRequest($request);

        if ($form->isValid()){
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );

            return $this->redirect($this->generateUrl('customers_list'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("customer/new", name="customer_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $customer = new Customer();

        $form = $this->createForm(new CustomerType(), $customer);
        $form->handleRequest($request);

        if ($form->isValid()){
            $customer->setCompany($user->getCompany());
            $em->persist($customer);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );

            return $this->redirect($this->generateUrl('customers_list'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/customer/edit/$id", name="customer_delete")
     * @Template()
     */
    public function deleteAction()
    {
        return [];
    }
}
