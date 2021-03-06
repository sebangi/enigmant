<?php

namespace App\Form\Chene;

use App\Entity\Chene\JeuEnChene;
use App\Entity\Chene\Babiole;
use App\Repository\Chene\BabioleRepository;
use App\Entity\Chene\CollectionChene;
use App\Repository\Chene\CollectionCheneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class JeuEnCheneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('collectionChene', EntityType::class, [
                'class' => CollectionChene::class,
                'choice_label' => 'nom',
                'query_builder' => function (CollectionCheneRepository $er) {
                        return $er->createQueryBuilder('b')
                            ->orderBy('b.num', 'ASC');
                    },                            
                'required' => false
            ])
            ->add('description')
            ->add('commentairesGourou')
            ->add('num', IntegerType::class, [
               'attr' => [
                   'min' => 1
                ]
            ])
            ->add('disponible', CheckboxType::class, [
                    'attr' => [ 'class' => "custom-control-input" ],
                    'label_attr' => ['class' => 'custom-control-label'],                            
                    'required' => false
                ])
            ->add('construit', CheckboxType::class, [
                    'attr' => [ 'class' => "custom-control-input" ],
                    'label_attr' => ['class' => 'custom-control-label'],                            
                    'required' => false
                ])
            ->add('difficulteObservation', IntegerType::class, [
               'attr' => [
                   'min' => 0,
                   'max' => 10
                ],                            
                'required' => false
            ])
            ->add('difficulteRaisonnement', IntegerType::class, [
               'attr' => [
                   'min' => 0,
                   'max' => 10
                ],                            
                'required' => false
            ])
            ->add('nombreEtapes', IntegerType::class, [
               'attr' => [
                   'min' => 1
                ],                            
                'required' => false
            ])
            ->add('couleur', ColorType::class)
            ->add('tempsLocation', ChoiceType::class, [
                'choices' => $this->getChoixLocation()
            ])
            ->add('niveauDifficulte', ChoiceType::class, [
                'choices' => $this->getNiveauDifficulte()
            ])
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
            ->add('imageFile', FileType::class, [
                'required' => false
            ] )                
            ->add('prix', IntegerType::class, [
               'attr' => [
                   'min' => 0
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JeuEnChene::class,
            'translation_domain' => 'forms'
        ]);
    }
    
    private function getChoixLocation() {
        $choix = JeuEnChene::codeLocation;
        
        $output = [];
        foreach ( $choix as $k => $v )
        {
            $output[$v] = $k;
        }
        
        return $output;
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
