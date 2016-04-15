<?php namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $payment = $options['data'];
        $company = $payment->getCompany();

        $builder->add('invoice', EntityType::class, array(
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
        $builder->add('date', DateType::class, ['input' => 'datetime']);
        $builder->add('method', ChoiceType::class, [
            'choices'   => array('check' => 'Chèque', 'transfer' => 'Virement', 'cart' => 'CB', 'money' => 'Espèce', 'avoir' => 'Avoir'),
        ]);
        $builder->add('total');
        $builder->add('comment');

        $builder->add('save', SubmitType::class, ['label' => 'Enregistrer']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Payment',
        ));
    }
}