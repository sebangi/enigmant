<?php

namespace App\Form\Chene;

use App\Entity\Chene\JeuEnCheneRecherche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JeuEnCheneRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrix', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix maximal (en babiole)',
                    'min' => 0
                ]
            ])
            ->add('minDifficulteRaisonnement', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Difficulté de raisonnement minimale',
                    'min' => 0,
                    'max' => 10
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JeuEnCheneRecherche::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
    
    public function getBlockPrefix() 
    {
        return '';
    }
}
