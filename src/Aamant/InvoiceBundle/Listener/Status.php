<?php namespace Aamant\InvoiceBundle\Listener;

use Aamant\InvoiceBundle\Entity\Payment;
use Aamant\InvoiceBundle\Entity\Invoice;
use Aamant\InvoiceBundle\Entity\Quotation;
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
                if ($entity->getTotal() >= $quote->getTotal()){
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