<?php namespace Aamant\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gender', 'choice', array(
            'choices'   => array('m' => 'Homme', 'f' => 'Femme'),
            'required'  => false,
        ));
        $builder->add('name');
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('email', 'email');
        $builder->add('address');
        $builder->add('address_comp', 'text', array('required' => false));
        $builder->add('postcode');
        $builder->add('city');
        $builder->add('country', 'country');
        $builder->add('save', 'submit');
    }

    public function getName()
    {
        return 'customer';
    }
}