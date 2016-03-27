<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;

/**
 * Company controller.
 *
 * @Route("/company")
 */
class CompanyController extends Controller
{
    /**
     * @Route("/company/profile", name="company_profile")
     * @Template()
     */
    public function profileAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $company = $user->getCompany();
        if (!$company) {
            $company = new Company();
        }

        $form = $this->createForm(new CompanyType(), $company);
        $form->handleRequest($request);

        if ($form->isValid()){
            if (!$user->getCompany()){
                $em->persist($company);
                $user->setCompany($company);
            }
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );

            return $this->redirect($this->generateUrl('homepage'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/", name="company")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Company')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Company entity.
     *
     * @Route("/", name="company_create")
     * @Method("POST")
     * @Template("AppBundle:Company:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Company();
        $entity->setCountry('FR');

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setConfig(new Config());

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('company'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Company entity.
     *
     * @param Company $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Company $entity)
    {
        $form = $this->createForm(new CompanyType(), $entity, array(
            'action' => $this->generateUrl('company_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Company entity.
     *
     * @Route("/new", name="company_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Company();
        $entity->setCountry('FR');

        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
}
