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
