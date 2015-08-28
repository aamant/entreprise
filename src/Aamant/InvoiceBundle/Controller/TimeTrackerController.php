<?php

namespace Aamant\InvoiceBundle\Controller;

use Aamant\InvoiceBundle\Entity\TimeTracker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TimeTrackerController extends Controller
{
    /**
     * @Route("timetracker/add/{id}", name="rest.timetracker.add", requirements={"id"="\d+"})
     */
    public function addAction(Request $request, $id)
    {
        $value = $request->request->getDigits('value');
        $em = $this->getDoctrine()->getEntityManager();
        /** @var \Aamant\InvoiceBundle\Entity\Quotation\Item $item */
        $item = $this->getDoctrine()->getRepository('AamantInvoiceBundle:Quotation\Item')->find($id);
        if (!$item || !$value){
            return new JsonResponse([
                'status'    => 'KO',
                'message'   => 'Not found'
            ]);
        }

        $time = new TimeTracker();
        $time->setCreatedAt(new \DateTime());
        $time->setTime($value);
        $time->setQuotationItem($item);

        $item->setPastTime($item->getPastTime() + $value);

        $em->persist($time);
        $em->persist($item);
        $em->flush();

        return new JsonResponse([
            'status'    => 'OK',
            'value'     => number_format($item->getPastTime(), 2)
        ]);
    }
}
