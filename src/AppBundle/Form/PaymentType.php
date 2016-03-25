<?php namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $payment = $options['data'];
        $company = $payment->getCompany();

        $builder->add('invoice', 'entity', array(
            'class' => 'AppBundle:Invoice',
            'property' => 'fullname',
            'empty_value' => 'Choisissez une facture',
            'label' => 'Facture',
            'query_builder' => function(EntityRepository $er) use($company) {
                return $er->createQueryBuilder('i')
                    ->join('i.company', 'c')
                    ->where('c.id = :id')
                    ->andWhere("i.status IN ('wait', 'partial')")
                    ->setParameter('id', $company->getId());
            }
        ));
        $builder->add('date', 'date', ['input' => 'datetime']);
        $builder->add('method', 'choice', [
            'choices'   => array('check' => 'Chèque', 'transfer' => 'Virement', 'cart' => 'CB', 'money' => 'Espèce', 'avoir' => 'Avoir'),
        ]);
        $builder->add('total');
        $builder->add('comment');

        $builder->add('save', 'submit', ['label' => 'Enregistrer']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Payment',
        ));
    }

    public function getName()
    {
        return 'payment';
    }
}