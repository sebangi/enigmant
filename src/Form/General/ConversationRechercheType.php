<?php

namespace App\Form\General;

use App\Entity\General\ConversationRecherche;
use App\Entity\General\Conversation;
use Symfony\Component\Form\AbstractType;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ConversationRechercheType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
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
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix() {
        return '';
    }
    
}
