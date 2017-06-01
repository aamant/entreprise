<?php

namespace AppBundle\Form;

use Carbon\Carbon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', ChoiceType::class, [
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
            ->add('month', ChoiceType::class, [
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
            ->add('total', TextType::class, [
                'label' => 'Chiffre d\'affaire',
//                'disabled' => true,
                'attr' => [
                    'disabled' => true
                ]
            ])
            ->add('value', TextType::class, [
                'label' => 'Montant'
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Tax'
        ));
    }
}
