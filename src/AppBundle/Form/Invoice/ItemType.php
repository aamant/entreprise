<?php namespace AppBundle\Form\Invoice;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('quantity');
        $builder->add('price');
        $builder->add('total');
        $builder->add('past_time', 'text', [
            'disabled'  => true
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Invoice\Item',
        ));
    }

    public function getName()
    {
        return 'item';
    }
}