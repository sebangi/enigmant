<?php

namespace App\Form\Chene;

use App\Entity\Chene\CategorieBabiole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieBabioleType extends AbstractType
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
            'data_class' => CategorieBabiole::class,
            'translation_domain' => 'forms'
        ]);
    }
}
