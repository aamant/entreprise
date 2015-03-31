<?php namespace Aamant\InvoiceBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

class Payment
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof \Aamant\InvoiceBundle\Entity\Payment){
            $invoice = $entity->getInvoice();
            if ($entity->getTotal() >= $invoice->getTotal()){
                $invoice->setStatus(\Aamant\InvoiceBundle\Entity\Invoice::STATUS_CLOSE);
            } else {
                $invoice->setStatus(\Aamant\InvoiceBundle\Entity\Invoice::STATUS_PARTIAL);
            }

            $em = $args->getEntityManager();
            $em->persist($invoice);
            $em->flush();
        }
    }
}