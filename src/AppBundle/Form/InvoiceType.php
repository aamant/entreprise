<?php namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use AppBundle\Form\Invoice\ItemType;
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
            'class' => 'AppBundle:Customer',
            'property' => 'name',
            'empty_value' => 'Choisissez un client',
            'label' => 'Client',
            'query_builder' => function(EntityRepository $er) use($company) {
                return $er->createQueryBuilder('u')
                    ->join('u.company', 'c')
                    ->where('c.id = :id')
                    ->setParameter('id', $company->getId());
            }
        ));
        $builder->add('quotation', 'entity', array(
            'class' => 'AppBundle:Quotation',
            'property' => 'fullname',
            'empty_value' => 'Choisissez un devis',
            'label' => 'Devis',
            'required' => false,
            'query_builder' => function(EntityRepository $er) use($company) {
                return $er->createQueryBuilder('q')
                    ->join('q.company', 'c')
                    ->where('c.id = :id')
                    ->andWhere("q.status IN ('accept', 'partial_invoiced', 'wait')")
                    ->setParameter('id', $company->getId());
            }
        ));
        $builder->add('items', 'collection', array(
            'type' => new ItemType(),
            'allow_add' => true,
            'by_reference' => false,
        ));
        $builder->add('sub_total', 'text', ['label' => 'Sous-total']);
        $builder->add('advance', 'text', ['label' => 'Acompte']);
        $builder->add('total');

        if (!$invoice->getNumber()){
            $builder->add('save', 'submit', ['label' => 'Enregistrer comme brouillon', 'attr' => array('class' => 'btn btn-default')]);
            $builder->add('create', 'submit', ['label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Invoice',
        ));
    }

    public function getName()
    {
        return 'invoice';
    }
}