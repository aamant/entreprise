<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('invoice_export', TextType::class, ['label' => 'Chemin des factures']);
        $builder->add('quotation_export', TextType::class, ['label' => 'Chemin des devis']);
        $builder->add('invoice_increment', TextType::class, ['label' => 'Numero de facture']);
        $builder->add('quotation_increment', TextType::class, ['label' => 'Numero de devis']);
        $builder->add('deposit_invoice_text', TextType::class, ['label' => 'Texte des factures d\'acompte']);
        $builder->add('deposit_invoice_percent', TextType::class, ['label' => 'Pourcentage des factures d\'acompte']);
        $builder->add('tax_rate', TextType::class, ['label' => 'Ration de cotisation']);
        $builder->add('payment_transfert', CheckboxType::class, [
            'label'     => 'Activer le réglement par virement',
            'required'  => false
        ]);
        $builder->add('Enregistrer', SubmitType::class);
    }
}