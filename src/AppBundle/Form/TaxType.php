<?php

namespace AppBundle\Form;

use Carbon\Carbon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaxType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', 'choice', [
                'choices'   => call_user_func(function(){
                    $years = [];
                    $date = Carbon::now();
                    for ($i = 1; $i <= 5; $i++){
                        $years[$date->year] = $date->year;
                        $date->subYear();
                    }
                    return $years;
                }),
                'label' => 'Année'
            ])
            ->add('month', 'choice', [
                'choices'   => [
                    1 => 'Janvier',
                    2 => 'Fevrier',
                    3 => 'Mars',
                    4 => 'Avril',
                    5 => 'Mai',
                    6 => 'Juin',
                    7 => 'Juillet',
                    8 => 'Aout',
                    9 => 'Septembre',
                    10 => 'Octobre',
                    11 => 'Novembre',
                    12 => 'Décembre'
                ],
                'label' => 'Mois'
            ])
            ->add('value', 'text', [
                'label' => 'Montant'
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Tax'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_tax';
    }
}
