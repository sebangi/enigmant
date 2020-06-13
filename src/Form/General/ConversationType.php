<?php

namespace App\Form\General;

use App\Entity\General\Conversation;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use App\Entity\Chene\JeuEnChene;
use App\Entity\General\Theme;
use App\Repository\General\ThemeRepository;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Entity\Chene\ReservationJeu;
use App\Repository\Chene\ReservationJeuRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ConversationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options['administration'] == true) {
            $builder
                    ->add('user', EntityType::class, [
                        'class' => User::class,
                        'choice_label' => 'username',
                        'query_builder' => function (UserRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.username', 'ASC');
                        },
                        'required' => true
                    ])
                    ->add('question', CheckboxType::class, [
                        'attr' => ['class' => "custom-control-input"],
                        'label_attr' => ['class' => 'custom-control-label'],
                        'required' => false
                    ])
                    ->add('questionReussi', CheckboxType::class, [
                        'attr' => ['class' => "custom-control-input"],
                        'label_attr' => ['class' => 'custom-control-label'],
                        'required' => false
                    ])
                    ->add('theme', EntityType::class, [
                        'class' => Theme::class,
                        'placeholder' => 'Aucun thème',
                        'choice_label' => 'nom',
                        'query_builder' => function (ThemeRepository $er) {
                            return $er->createQueryBuilder('t')
                                    ->orderBy('t.nom', 'ASC');
                        },
                        'required' => false
                    ])
                    ->add('lienReservation', EntityType::class, [
                        'class' => ReservationJeu::class,
                        'placeholder' => 'Aucune référence',
                        'choice_label' => function ($reservation) {
                            return $reservation->getIntitule();
                        },
                        'query_builder' => function (ReservationJeuRepository $er ) {
                            return $er->createQueryBuilder('r')
                                    ->Join('r.user', 'user')
                                    ->orderBy('r.dateDemande', 'DESC');
                        },
                        'required' => false
                    ])
            ;
        }

        $builder
                ->add('sujet')
                ->add('lienJeuEnChene', EntityType::class, [
                    'class' => JeuEnChene::class,
                    'placeholder' => 'Aucune référence',
                    'choice_label' => function ($jeuEnChene) {
                        return $jeuEnChene->getNomEtCollection();
                    },
                    'query_builder' => function (JeuEnCheneRepository $er) {
                        return $er->createQueryBuilder('j')
                                ->orderBy('j.nom', 'ASC');
                    },
                    'required' => false
                ])
                ->add('cancel', SubmitType::class, array(
                    'label' => 'Annuler',
                    'attr' => array(
                        'class' => "btn-danger",
                        'formnovalidate' => 'formnovalidate'
                    ))
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Conversation::class,
            'translation_domain' => 'forms',
            'administration' => false,
            'user_id' => null,
        ]);
    }

}
