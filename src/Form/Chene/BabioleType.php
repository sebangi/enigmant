<?php

namespace App\Form\Chene;

use App\Entity\Chene\Babiole;
use App\Entity\Chene\CategorieBabiole;
use App\Repository\Chene\CategorieBabioleRepository;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use App\Entity\Chene\TypeBabiole;
use App\Repository\Chene\TypeBabioleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
            ->add('typeBabiole', EntityType::class, [
                'class' => TypeBabiole::class,
                'placeholder' => 'Non défini',
                'choice_label' => 'nom',
                'query_builder' => function (TypeBabioleRepository $er) {
                        return $er->createQueryBuilder('b')
                            ->orderBy('b.nom', 'ASC');
                    }
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ] )                
            ->add('categorieBabiole', EntityType::class, [
                'class' => CategorieBabiole::class,
                'placeholder' => 'Non défini',
                'choice_label' => 'nom',
                'query_builder' => function (CategorieBabioleRepository $er) {
                        return $er->createQueryBuilder('b')
                            ->orderBy('b.nom', 'ASC');
                    }
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'Aucun',
                'label' => "Joueur l'ayant offert",
                'query_builder' => function (UserRepository $er) {
                        return $er->createQueryBuilder('b')
                            ->orderBy('b.username', 'ASC');
                    },                            
                'required' => false
            ])
            ->add('commentaireGourou', TextareaType::class, [
                'required' => false
                ]
            )
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
