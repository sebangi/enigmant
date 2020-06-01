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
                            'attr' => ['class' => "custom-control-input only-one case_a_cocher1"],
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
                            'attr' => ['class' => "custom-control-input only-one case_a_cocher1"],
                            'label' => false,
                            'label_attr' =>
                            ['class' => 'custom-control-label',
                                'name' => 'checkboxValidation'],
                            'required' => false
                ])
                ->add('retourDomicile', CheckboxType::class, [
                    'attr' => ['class' => "custom-control-input only-one"],
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

    public function buildForm(FormBuilderInterface $builder, array $options) {


        if ($options['champ'] == 'etat') {
            $this->buildEtat($builder, $options);
        } else if ($options['champ'] == 'lieuRetrait') {
            $this->buildLieuRetrait($builder, $options);
        } else if ($options['champ'] == 'dateRetrait') {
            $this->buildDateRetrait($builder, $options);
        } else if ($options['champ'] == 'lieuRetour') {
            $this->buildLieuRetour($builder, $options);
        } else if ($options['champ'] == 'dateRendu') {
            $this->buildDateRendu($builder, $options);
        } else {
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

            if ($options['administration'] == true || $options['etape'] == 1) {
                $this->buildLieuRetrait($builder, $options);
            }

            if ($options['administration'] == true || $options['etape'] == 2) {
                $this->buildDateRetrait($builder, $options);
            }
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

    private function getChoixEtat() {
        $choix = ReservationJeu::codeEtat;

        $output = [];
        foreach ($choix as $k => $v) {
            $output[$v] = $k;
        }

        return $output;
    }

}
