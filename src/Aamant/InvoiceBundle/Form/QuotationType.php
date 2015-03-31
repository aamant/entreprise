<?php namespace Aamant\InvoiceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $quotation = $options['data'];
        $company = $quotation->getCompany();
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
        $builder->add('number', 'text', ['label' => 'Numéro']);
        $builder->add('date', 'date', ['input' => 'datetime']);
        $builder->add('total');

        $builder->add('save', 'submit', ['label' => 'Enregistrer']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aamant\InvoiceBundle\Entity\Quotation',
        ));
    }

    public function getName()
    {
        return 'quotation';
    }
}