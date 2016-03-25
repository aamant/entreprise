<?php namespace AppBundle\Listener;

use AppBundle\Entity\Payment;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Quotation;
use Doctrine\ORM\Event\LifecycleEventArgs;

class Status
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Payment){
            $invoice = $entity->getInvoice();
            if ($invoice->getPaid() >= $invoice->getTotal()){
                $invoice->setStatus(Invoice::STATUS_CLOSE);
            } else {
                $invoice->setStatus(Invoice::STATUS_PARTIAL);
            }

            $em = $args->getEntityManager();
            $em->persist($invoice);
            $em->flush();
        }
        elseif ($entity instanceof Invoice){
            $quote = $entity->getQuotation();
            if ($entity->getNumber() && $quote){
                if ($quote->getBilled() >= $quote->getTotal()){
                    $quote->setStatus(Quotation::STATUS_INVOICED);
                } else {
                    $quote->setStatus(Quotation::STATUS_PARTIAL_INVOICED);
                }

                $em = $args->getEntityManager();
                $em->persist($quote);
                $em->flush();
            }
        }
    }
}