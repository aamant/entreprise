<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('address');
        $builder->add('address_comp', TextType::class, array('required' => false));
        $builder->add('postcode');
        $builder->add('city');
        $builder->add('country', CountryType::class);
        $builder->add('email', EmailType::class);
        $builder->add('phone');
        $builder->add('siren');
        $builder->add('siret');
        $builder->add('website', UrlType::class);
        $builder->add('bank');
        $builder->add('indicatif');
        $builder->add('compte');
        $builder->add('keyrib');
        $builder->add('domiciliation');
        $builder->add('iban');
        $builder->add('bic');
        $builder->add('save', SubmitType::class);
    }
}