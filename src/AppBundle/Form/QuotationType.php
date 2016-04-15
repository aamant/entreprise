<?php namespace AppBundle\Form;

use AppBundle\Form\Quotation\ItemType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $quotation = $options['data'];
        $company = $quotation->getCompany();
        $builder->add('customer', EntityType::class, array(
            'class' => 'AppBundle:Customer',
            'property' => 'name',
            'empty_value' => 'Choisissez une option',
            'label' => 'Client',
            'query_builder' => function(EntityRepository $er) use($company) {
                return $er->createQueryBuilder('u')
                    ->join('u.company', 'c')
                    ->where('c.id = :id')
                    ->setParameter('id', $company->getId());
            }
        ));
        $builder->add('number', TextType::class, ['label' => 'Numéro']);
        $builder->add('date', DateType::class, ['input' => 'datetime']);
        $builder->add('description');
        $builder->add('items', CollectionType::class, array(
            'type' => new ItemType(),
            'allow_add' => true,
            'by_reference' => false,
        ));
        $builder->add('total');

        $builder->add('draft', SubmitType::class, ['label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-default')]);
        $builder->add('save', SubmitType::class, ['label' => 'Terminer', 'attr' => array('class' => 'btn btn-danger')]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Quotation',
        ));
    }
}