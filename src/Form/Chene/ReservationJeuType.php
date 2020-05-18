<?php

namespace App\Form\Chene;

use App\Entity\Chene\ReservationJeu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReservationJeuType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options['administration'] == true) {
            $builder
                    ->add('dateDemande', DateType::class)
                    ->add('dateFinPrevue', DateType::class)
                    ->add('dateRendu', DateType::class)
                    ->add('avisPublic', TextareaType::class, [
                        'required' => false
                    ])
                    ->add('avisPriveDifficulte', TextareaType::class, [
                        'required' => false
                    ])
                    ->add('avisPriveTechnique', TextareaType::class, [
                        'required' => false
                    ])
                    ->add('reussi', CheckboxType::class, [
                        'attr' => ['class' => "custom-control-input"],
                        'label_attr' => ['class' => 'custom-control-label']
                    ])
                    ->add('tempsJeuEstime')
                    ->add('user', EntityType::class, [
                        'class' => User::class,
                        'choice_label' => 'username',
                        'query_builder' => function (UserRepository $er) {
                            return $er->createQueryBuilder('t')
                                    ->orderBy('t.username', 'ASC');
                        },
                        'required' => false
                    ])
                    ->add('jeu', EntityType::class, [
                        'class' => JeuEnChene::class,
                        'choice_label' => function ($jeu) {
                            return $jeu->getNomEtCollection();
                        },
                        'query_builder' => function (JeuEnCheneRepository $er2) {
                            return $er2->createQueryBuilder('j')
                                    ->orderBy('j.nom', 'ASC');
                        },
                        'required' => false
                    ])
            ;
        }

        if ($options['administration'] == true ||
                $options['etape'] == 1) {
            $builder
                    ->add('retraitRDV', CheckboxType::class,
                            [
                                'attr' => ['class' => "custom-control-input only-one"],
                                'label' => false,
                                'label_attr' =>
                                ['class' => 'custom-control-label',
                                    'name' => 'checkboxValidation'],
                                'required' => false
                    ])
                    ->add('retraitDomicile', CheckboxType::class, [
                        'attr' => ['class' => "custom-control-input only-one"],
                        'label' => false,
                        'label_attr' =>
                        ['class' => 'custom-control-label',
                            'name' => 'checkboxValidation'],
                        'required' => false
                    ])
            ;
        }

        if ($options['administration'] == true ||
                $options['etape'] == 2) {
            $builder
                    ->add('dateRetrait', DateType::class)
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ReservationJeu::class,
            'translation_domain' => 'forms',
            'administration' => false,
            'etape' => 0,
        ]);
    }

}
