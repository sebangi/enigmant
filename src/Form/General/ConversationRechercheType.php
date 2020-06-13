<?php

namespace App\Form\General;

use App\Entity\General\ConversationRecherche;
use App\Entity\General\Conversation;
use Symfony\Component\Form\AbstractType;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use App\Entity\General\Theme;
use App\Repository\General\ThemeRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ConversationRechercheType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options["admin"])
            $builder
                    ->add('question', CheckboxType::class, [
                        'attr' => ['class' => "custom-control-input"],
                        'label_attr' => ['class' => 'custom-control-label'],
                        'required' => false
                    ])
                    ->add('user', EntityType::class, [
                        'class' => User::class,
                        'choice_label' => 'username',
                        'label' => false,
                        'placeholder' => 'Tous les joueurs',
                        'query_builder' => function (UserRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.username', 'ASC');
                        },
                        'required' => false
            ]);

        $builder
                ->add('theme', EntityType::class, [
                    'class' => Theme::class,
                    'choice_label' => 'nom',
                    'label' => false,
                    'placeholder' => 'Tous les thèmes',
                    'query_builder' => function (ThemeRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->where('t.disponible = true')
                                ->orderBy('t.nom', 'ASC');
                    },
                    'required' => false
                ])
                ->add('recherche', SubmitType::class, array(
                    'label' => 'Rechercher',
                    'attr' => array(
                        'class' => "btn-success",
                        'formnovalidate' => 'formnovalidate'
                    ))
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ConversationRecherche::class,
            'translation_domain' => 'forms',
            'method' => 'get',
            'admin' => false,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix() {
        return '';
    }

}
