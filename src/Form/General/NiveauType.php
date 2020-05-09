<?php

namespace App\Form\General;

use App\Entity\General\Niveau;
use App\Entity\General\Theme;
use App\Repository\General\ThemeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NiveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('num', IntegerType::class, [
               'attr' => [
                   'min' => 1
                ]
            ])
            ->add('disponible')
            ->add('nomCache')
            ->add('conditionTexte')
            ->add('raison')
            ->add('couleur', ColorType::class)
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'nom',
                'query_builder' => function (ThemeRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->orderBy('t.nom', 'ASC');
                    },                            
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Niveau::class,
            'translation_domain' => 'forms'
        ]);
    }
}
