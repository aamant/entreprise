<?php
/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */
namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RegistrationType
 * @package AppBundle\Form
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('company', 'entity', array(
            'class' => 'AppBundle:Company',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
            },
            'property' => 'name'
//            'choice_label' => 'company',
        ));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}