<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Carbon\Carbon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $company = $this->getUser()->getCompany();
        /** @var \AppBundle\Service\Dashbord $statistics */
        $statistics = $this->get('statistics.dashbord');

        $recipeByMonth = $statistics->recipeByMonths($company);
        $recipeAnnualPerMonth = $statistics->recipeAnnualPerMonth($company);

        return $this->render('default/index.html.twig',
            compact('recipeByMonth', 'recipeAnnualPerMonth')
        );
    }
}
