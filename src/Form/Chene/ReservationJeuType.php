<?php

namespace App\Form\Chene;

use App\Entity\Chene\ReservationJeu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Chene\Babiole;
use App\Repository\Chene\BabioleRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationJeuType extends AbstractType {

    private function buildCancel(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('cancel', SubmitType::class, array(
                    'label' => 'Annuler',
                    'attr' => array(
                        'class' => "btn-danger",
                        'formnovalidate' => 'formnovalidate'
                    ))
        );
    }

    private function buildEtat(FormBuilderInterface $builder, array $options) {
        $this->buildCancel($builder, $options);
        $builder->add('etat', ChoiceType::class, [
            'choices' => $this->getChoixEtat()
        ]);
    }

    private function buildLieuRetrait(FormBuilderInterface $builder, array $options) {
        $this->buildCancel($builder, $options);
        $builder
                ->add('retraitRDV', CheckboxType::class,
                        [
                            'attr' => ['class' => "custom-control-input only-one1 case_a_cocher1"],
                            'label' => false,
                            'label_attr' =>
                            ['class' => 'custom-control-label',
                                'name' => 'checkboxValidation'],
                            'required' => false
                ])
                ->add('retraitDomicile', CheckboxType::class, [
                    'attr' => ['class' => "custom-control-input only-one1"],
                    'label' => false,
                    'label_attr' =>
                    ['class' => 'custom-control-label',
                        'name' => 'checkboxValidation'],
                    'required' => false
                ])
                ->add('lieuRDV', TextareaType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Lieu proposé'
                    ]
                ])
        ;
    }

    private function buildDateRetrait(FormBuilderInterface $builder, array $options) {
        $this->buildCancel($builder, $options);
        $builder
                ->add('dateRetrait', TextType::class, [
                    'required' => true,
                    'label' => false,
                    'translation_domain' => 'AppBundle',
                    'attr' => array(
                        'class' => 'datetimepicker-input',
                        'data-target' => "#dateRetrait"
                    )
                ])
        ;

        $builder->get('dateRetrait')
                ->addModelTransformer(new DateTimeTransformer());
    }

    private function buildLieuRetour(FormBuilderInterface $builder, array $options) {
        $this->buildCancel($builder, $options);
        $builder
                ->add('retourRDV', CheckboxType::class,
                        [
                            'attr' => ['class' => "custom-control-input only-one2 case_a_cocher1"],
                            'label' => false,
                            'label_attr' =>
                            ['class' => 'custom-control-label',
                                'name' => 'checkboxValidation'],
                            'required' => false
                ])
                ->add('retourDomicile', CheckboxType::class, [
                    'attr' => ['class' => "custom-control-input only-one2"],
                    'label' => false,
                    'label_attr' =>
                    ['class' => 'custom-control-label',
                        'name' => 'checkboxValidation'],
                    'required' => false
                ])
                ->add('lieuRetourRDV', TextareaType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Lieu proposé'
                    ]
                ])
        ;
    }

    private function buildDateRendu(FormBuilderInterface $builder, array $options) {
        $this->buildCancel($builder, $options);
        $builder
                ->add('dateRendu', TextType::class, [
                    'required' => true,
                    'label' => false,
                    'translation_domain' => 'AppBundle',
                    'attr' => array(
                        'class' => 'datetimepicker-input',
                        'data-target' => "#dateRendu"
                    )
                ])
        ;

        $builder->get('dateRendu')
                ->addModelTransformer(new DateTimeTransformer());
    }

    private function buildAvis(FormBuilderInterface $builder, array $options) {
        $this->buildCancel($builder, $options);

        if ($options['administration'] == true) {
            $builder
                    ->add('avisPublic', TextareaType::class, [
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'Votre avis SANS DONNER DE SOLUTIONS',
                            'class' => 'mon-area'
                        ]
            ]);
        } else {
            $builder
                    ->add('avisPublic', TextareaType::class, [
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Votre avis SANS DONNER DE SOLUTIONS',
                            'class' => 'mon-area'
                        ]
            ]);
        }


        $builder
                ->add('avisPriveDifficulte', TextareaType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Y a-t-il des étapes trop faciles ou trop difficiles ?',
                        'class' => 'mon-area'
                    ]
                ])
                ->add('avisPriveTechnique', TextareaType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Avez-vous eu des soucis techniques ?',
                        'class' => 'mon-area'
                    ]
                ])
        ;
    }

    private function buildBabioles(FormBuilderInterface $builder, array $options) {
        $this->buildCancel($builder, $options);
        $builder
                ->add('babioles', EntityType::class, [
                    'class' => Babiole::class,
                    'choice_label' => 'nom',
                    'query_builder' => function (BabioleRepository $er) {
                        return $er->createQueryBuilder('b')
                                ->orderBy('b.nom', 'ASC');
                    },
                    'multiple' => true,
                    'required' => false
                ])
        ;
    }

    private $avisPublic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $avisPriveDifficulte;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $avisPriveTechnique;

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options['administration'] == true) {
            $builder
                    ->add('dateDemande', TextType::class, [
                        'required' => true,
                        'label' => false,
                        'translation_domain' => 'AppBundle',
                        'attr' => array(
                            'class' => 'datetimepicker-input',
                            'data-target' => "#dateDemande"
                        )
                    ])
                    ->add('dateFinPrevue', TextType::class, [
                        'required' => false,
                        'label' => false,
                        'translation_domain' => 'AppBundle',
                        'attr' => array(
                            'class' => 'datetimepicker-input',
                            'data-target' => "#dateFinPrevue"
                        )
                    ])
                    ->add('reussi', CheckboxType::class, [
                        'attr' => ['class' => "custom-control-input"],
                        'label' => false,
                        'required' => false,
                        'attr' => ['class' => "custom-control-input"],
                        'label_attr' => ['class' => 'custom-control-label']
                    ])
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


            $builder->get('dateDemande')
                    ->addModelTransformer(new DateTimeTransformer());
            $builder->get('dateFinPrevue')
                    ->addModelTransformer(new DateTimeTransformer());
        }


        if ($options['champ'] == 'etat') {
            $this->buildEtat($builder, $options);
        }

        if ($options['administration'] == true || $options['champ'] == 'lieuRetrait') {
            $this->buildLieuRetrait($builder, $options);
        }

        if ($options['administration'] == true || $options['champ'] == 'dateRetrait') {
            $this->buildDateRetrait($builder, $options);
        }

        if ($options['administration'] == true || $options['champ'] == 'lieuRetour') {
            $this->buildLieuRetour($builder, $options);
        }

        if ($options['administration'] == true || $options['champ'] == 'dateRendu') {
            $this->buildDateRendu($builder, $options);
        }

        if ($options['administration'] == true || $options['champ'] == 'avis') {
            $this->buildAvis($builder, $options);
        }

        if ($options['administration'] == true || $options['champ'] == 'babioles') {
            $this->buildBabioles($builder, $options);
        }



        if ($options['etape'] == 1) {
            $this->buildLieuRetrait($builder, $options);
        }

        if ($options['etape'] == 2) {
            $this->buildDateRetrait($builder, $options);
        }
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ReservationJeu::class,
            'translation_domain' => 'forms',
            'administration' => false,
            'champ' => "aucun",
            'etape' => 0,
        ]);
    }

    private
            function getChoixEtat() {
        $choix = ReservationJeu::codeEtat;

        $output = [];
        foreach ($choix as $k => $v) {
            $output[$v] = $k;
        }

        return $output;
    }

}
