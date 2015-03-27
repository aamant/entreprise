<?php

namespace Aamant\UserBundle\Controller;

use Aamant\UserBundle\Entity\Company;
use Aamant\UserBundle\Form\CompanyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CompanyController extends Controller
{
    /**
     * @Route("/company", name="company")
     * @Template()
     */
    public function companyAction(Request $request)
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
}
