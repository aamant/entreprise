<?php

namespace Aamant\SettingsBundle\Controller;

use Aamant\SettingsBundle\Entity\Config;
use Aamant\SettingsBundle\Form\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="settings_settings")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $config = $user->getCompany()->getConfig();
        if (!$config){
            $config = new Config();
        }

        $form = $this->createForm(new ConfigType(), $config);
        $form->handleRequest($request);

        if ($form->isValid()){
            $user->getCompany()->setConfig($config);
            $em = $this->getDoctrine()->getManager();
            $em->persist($config);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos changements ont été sauvegardés!'
            );
        }

        return ['form' => $form->createView()];
    }
}
