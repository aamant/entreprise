<?php
/**
 * Arnaud Amant <contact@arnaudamant.fr>
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CustomerType
 * @package AppBundle\Form
 */
class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['label' => 'Entreprise']);
        $builder->add('gender', ChoiceType::class, array(
            'choices'   => array(
                null    => '',
                'm'     => 'Homme',
                'f'     => 'Femme'
            )
        ));
        $builder->add('firstname', TextType::class, array('required' => false));
        $builder->add('lastname', TextType::class, array('required' => false));
        $builder->add('email', EmailType::class, array('required' => false));
        $builder->add('address');
        $builder->add('address_comp', TextType::class, array('required' => false));
        $builder->add('postcode');
        $builder->add('city');
        $builder->add('country', CountryType::class);
        $builder->add('save', SubmitType::class);
    }
}