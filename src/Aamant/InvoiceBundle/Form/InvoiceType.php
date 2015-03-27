<?php namespace Aamant\InvoiceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Aamant\InvoiceBundle\Form\Invoice\ItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $invoice = $options['data'];
        $company = $invoice->getCompany();
        $builder->add('customer', 'entity', array(
            'class' => 'AamantCustomerBundle:Customer',
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
        $builder->add('items', 'collection', array(
            'type' => new ItemType(),
            'allow_add' => true,
            'by_reference' => false,
        ));
        $builder->add('sub_total', 'text', ['label' => 'Sous-total']);
        $builder->add('total');

        if (!$invoice->getNumber()){
            $builder->add('save', 'submit', ['label' => 'Enregistrer']);
            $builder->add('create', 'submit', ['label' => 'Creation', 'attr' => array('class' => 'btn btn-danger')]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aamant\InvoiceBundle\Entity\Invoice',
        ));
    }

    public function getName()
    {
        return 'invoice';
    }
}