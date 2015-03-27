<?php namespace Aamant\SettingsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('invoice_export', 'text', ['label' => 'Chemin des factures']);
        $builder->add('invoice_increment', 'text', ['label' => 'Numero de facture']);
        $builder->add('save', 'submit');
    }

    public function getName()
    {
        return 'config';
    }
}