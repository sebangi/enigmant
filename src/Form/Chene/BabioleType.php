<?php

namespace App\Form\Chene;

use App\Entity\Chene\Babiole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BabioleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('valeur', IntegerType::class, [
               'attr' => [
                   'min' => 0
                ]
            ])
            ->add('commentaireGourou')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Babiole::class,
            'translation_domain' => 'forms'
        ]);
    }
}
