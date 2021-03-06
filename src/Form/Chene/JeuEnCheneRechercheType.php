<?php

namespace App\Form\Chene;

use App\Entity\Chene\JeuEnCheneRecherche;
use App\Entity\Chene\JeuEnChene;
use Symfony\Component\Form\AbstractType;
use App\Entity\Chene\CollectionChene;
use App\Repository\Chene\CollectionCheneRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class JeuEnCheneRechercheType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('disponible', CheckboxType::class, [
                    'attr' => [ 'class' => "custom-control-input" ],
                    'label_attr' => ['class' => 'custom-control-label'],
                    'label' => false,
                    'required' => false
                ])
                ->add('collection', EntityType::class, [
                    'class' => CollectionChene::class,
                    'choice_label' => 'nom',
                    'label' => false,
                    'placeholder' => 'Toutes les collections',
                    'query_builder' => function (CollectionCheneRepository $er) {
                        return $er->createQueryBuilder('b')
                                ->orderBy('b.num', 'ASC');
                    },
                    'required' => false
                ])
                ->add('maxPrix', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Max',
                        'min' => 0
                    ]
                ])
                ->add('minPrix', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Min',
                        'min' => 0
                    ]
                ])
                ->add('maxPrix', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Max',
                        'min' => 0
                    ]
                ])
                ->add('niveauDifficulte', ChoiceType::class, [
                'choices' => $this->getNiveauDifficulte()
                ])
                ->add('minDifficulteRaisonnement', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Min',
                        'min' => 0,
                        'max' => 10
                    ]
                ])
                ->add('maxDifficulteRaisonnement', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Max',
                        'min' => 0,
                        'max' => 10
                    ]
                ])
                ->add('minEtape', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Min',
                        'min' => 0
                    ]
                ])
                ->add('maxEtape', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Max',
                        'min' => 0
                    ]
                ])
                ->add('minDifficulteObservation', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Min',
                        'min' => 0,
                        'max' => 10
                    ]
                ])
                ->add('maxDifficulteObservation', IntegerType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Max',
                        'min' => 0,
                        'max' => 10
                    ]
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
            'data_class' => JeuEnCheneRecherche::class,
            'translation_domain' => 'forms',
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix() {
        return '';
    }
    
    private function getNiveauDifficulte() {
        $choix = JeuEnChene::codeNiveauDifficulte;
        
        $output = [];
        foreach ( $choix as $k => $v )
        {
            $output[$v] = $k;
        }
        
        return $output;
    }
}
