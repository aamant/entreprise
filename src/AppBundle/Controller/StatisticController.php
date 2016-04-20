<?php
/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class StatisticController extends Controller
{
    /**
     * @param Request $request
     * @return array
     *
     * @Route("statistic/sales", name="statistic.sales")
     * @Template()
     * @Method("GET")
     */
    public function salesAction(Request $request)
    {
        /** @var \AppBundle\Service\Dashbord $statistics */
        $statistics = $this->get('statistics.dashbord');
        $sales = $statistics->annual($this->getCompany());

        return [
            'sales' => $sales
        ];
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Route("statistic/quotation/{status}", name="statistic.quotation")
     * @Template()
     * @Method("GET")
     */
    public function quotationAction(Request $request, $status)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Quotation');
        $quotations = $repository->findByStatus($status);

        return [
            'quotations' => $quotations
        ];
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Route("statistic/invoice/{status}", name="statistic.invoice")
     * @Template()
     * @Method("GET")
     */
    public function invoiceAction(Request $request, $status)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Invoice');
        $status = explode(',', $status);
        $invoices = $repository->matching(Criteria::create()->where(Criteria::expr()->in('status', $status)));

        return [
            'invoices' => $invoices
        ];
    }

    /**
     * @return \AppBundle\Entity\Company
     */
    protected function getCompany()
    {
        return $this->getUser()->getCompany();
    }
}