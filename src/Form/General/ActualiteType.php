<?php

namespace App\Form\General;

use App\Entity\General\Actualite;
use App\Entity\General\Theme;
use App\Repository\General\ThemeRepository;
use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Entity\Chene\Babiole;
use App\Repository\Chene\BabioleRepository;
use App\Entity\Chene\CollectionChene;
use App\Repository\Chene\CollectionCheneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActualiteType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre')
                ->add('prioritaire')
                ->add('texte')
                ->add('date')
                ->add('imageFile', FileType::class, [
                    'required' => false
                ])
                ->add('theme', EntityType::class, [
                    'class' => Theme::class,
                    'placeholder' => 'Aucun',
                    'choice_label' => 'nom',
                    'query_builder' => function (ThemeRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->orderBy('t.nom', 'ASC');
                    },
                    'required' => false
                ])
                ->add('babiole', EntityType::class, [
                    'class' => Babiole::class,
                    'placeholder' => 'Aucune',
                    'choice_label' => 'nom',
                    'query_builder' => function (BabioleRepository $er) {
                        return $er->createQueryBuilder('b')
                                ->orderBy('b.nom', 'ASC');
                    },
                    'required' => false
                ])
                ->add('collectionChene', EntityType::class, [
                    'class' => CollectionChene::class,
                    'placeholder' => 'Aucune',
                    'choice_label' => 'nom',
                    'query_builder' => function (CollectionCheneRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->orderBy('c.num', 'ASC');
                    },
                    'required' => false
                ])
                ->add('jeuEnChene', EntityType::class, [
                    'class' => JeuEnChene::class,
                    'placeholder' => 'Aucun',
                    'choice_label' => function ($jeuEnChene) {
                        return $jeuEnChene->getNomEtCollection();
                    },
                    'query_builder' => function (JeuEnCheneRepository $er) {
                        return $er->createQueryBuilder('j')
                                ->orderBy('j.nom', 'ASC');
                    },
                    'required' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Actualite::class,
            'translation_domain' => 'forms'
        ]);
    }

}
