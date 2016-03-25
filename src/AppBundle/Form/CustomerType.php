<?php
/**
 * Arnaud Amant <contact@arnaudamant.fr>
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CustomerType
 * @package AppBundle\Form
 */
class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', ['label' => 'Entreprise']);
        $builder->add('gender', 'choice', array(
            'choices'   => array('m' => 'Homme', 'f' => 'Femme'),
            'required'  => false,
        ));
        $builder->add('firstname', 'text', array('required' => false));
        $builder->add('lastname', 'text', array('required' => false));
        $builder->add('email', 'email', array('required' => false));
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