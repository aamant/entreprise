<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TimeTracker;
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
        $value = (float)preg_replace('/,/', '.', $request->request->get('value'));
        $em = $this->getDoctrine()->getEntityManager();
        /** @var \AppBundle\Entity\Quotation\Item $item */
        $item = $this->getDoctrine()->getRepository('AppBundle:Quotation\Item')->find($id);
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
