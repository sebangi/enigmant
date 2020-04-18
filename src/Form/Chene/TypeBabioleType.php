<?php

namespace App\Form\Chene;

use App\Entity\Chene\TypeBabiole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeBabioleType extends AbstractType
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
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeBabiole::class,
            'translation_domain' => 'forms'
        ]);
    }
}
