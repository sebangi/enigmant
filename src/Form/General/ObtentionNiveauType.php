<?php

namespace App\Form\General;

use App\Entity\General\ObtentionNiveau;
use App\Entity\General\Niveau;
use App\Repository\General\NiveauRepository;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ObtentionNiveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class)
            ->add('vu')
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => function ($niveau) {
                                    return $niveau->getGrade();
                                    },
                'query_builder' => function (NiveauRepository $er) {
                        return $er->findAllByThemeQueryBuilder();
                    },                            
                'required' => false
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'query_builder' => function (UserRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->orderBy('t.username', 'ASC');
                    },                            
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ObtentionNiveau::class,
            'translation_domain' => 'forms'
        ]);
    }
}
