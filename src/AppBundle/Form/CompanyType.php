<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('address');
        $builder->add('address_comp', 'text', array('required' => false));
        $builder->add('postcode');
        $builder->add('city');
        $builder->add('country', 'country');
        $builder->add('email', 'email');
        $builder->add('phone');
        $builder->add('siren');
        $builder->add('siret');
        $builder->add('website', 'url');
        $builder->add('bank');
        $builder->add('indicatif');
        $builder->add('compte');
        $builder->add('keyrib');
        $builder->add('domiciliation');
        $builder->add('iban');
        $builder->add('bic');
        $builder->add('save', 'submit');
    }

    public function getName()
    {
        return 'company';
    }
}