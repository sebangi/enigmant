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

class ReservationJeuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDemande', DateType::class)
            ->add('dateRetrait', DateType::class)
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
            ->add('reussi')
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReservationJeu::class,
            'translation_domain' => 'forms'
        ]);
    }
}
