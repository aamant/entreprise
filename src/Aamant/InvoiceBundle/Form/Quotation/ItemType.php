<?php namespace Aamant\InvoiceBundle\Form\Quotation;

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
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aamant\InvoiceBundle\Entity\Quotation\Item',
        ));
    }

    public function getName()
    {
        return 'item';
    }
}