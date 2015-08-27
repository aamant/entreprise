<?php namespace Aamant\SettingsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('invoice_export', 'text', ['label' => 'Chemin des factures']);
        $builder->add('invoice_increment', 'text', ['label' => 'Numero de facture']);
        $builder->add('deposit_invoice_text', 'text', ['label' => 'Texte des factures d\'acompte']);
        $builder->add('deposit_invoice_percent', 'text', ['label' => 'Pourcentage des facture d\'acompte']);
        $builder->add('Enregistrer', 'submit');
    }

    public function getName()
    {
        return 'config';
    }
}