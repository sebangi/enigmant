<?php

namespace App\Form\Chene;

use App\Entity\Chene\CollectionChene;
use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CollectionCheneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('num', IntegerType::class, [
               'attr' => [
                   'min' => 1
                ]
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ])  
            ->add('jeuEnChenes', EntityType::class, [
                'class' => JeuEnChene::class,
                'choice_label' => 'nom',
                'query_builder' => function (JeuEnCheneRepository $er) {
                        return $er->createQueryBuilder('b')
                            ->orderBy('b.nom', 'ASC');
                    },                            
                'required' => false
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CollectionChene::class,
            'translation_domain' => 'forms'
        ]);
    }
}
